<?php
declare(strict_types=1);
namespace Moviao\Data;
use stdClass;
use Moviao\Data\Util\EmailUtils;

class UsersCommon extends CommonData {

private const PWD_TYPE_OTP = '1'; // OTP Password
private const PWD_TYPE_NORMAL = '2'; // Normal Password

// Google Identity
private const CLIENT_GOOGLE_IDENTITY_ID = '901321113948-afpee2ivmq2vkejf1b4t59iai6b8vcc8.apps.googleusercontent.com';

/**
 * Get User Public Profile
 * @return array
 */
public function getUserPublicProfile(\stdClass $form) : array {

    $bresult = false;
    $return_data = array();

    try {

        if (empty($form) || empty($form->UUID) || ! is_string($form->UUID) || mb_strlen($form->UUID) < 4) {
            return array('result' => false,'code' => 666);
        }

        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        $IDUSER = parent::getSession()->getIDUSER();

        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $uuid = mb_substr(filter_var($form->UUID,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,100);

        $user_utils = new \Moviao\Data\Util\UsersUtils($this);

//        if (empty($uid)) {
//            $uid = $user_utils->getUserUUID($IDUSER);
//        }

        $uuid_check = \Moviao\Security\UUID::is_valid($uuid);
        $options = array('options' => array('regexp' => '/^[A-Za-z][A-Za-z0-9_]{5,14}$/'));
        if ((filter_var($uuid, FILTER_VALIDATE_REGEXP, $options) === false) && $uuid_check === false) {
            return array('result' => false, 'code' => 700);
        }

        $userProfile = $user_utils->getUserIDFromUUID($uuid);

        if (null !== $userProfile) {

            $return_data = $user_utils->getUserPublicProfile($userProfile);

            if (! empty($return_data)) {
                $bresult = true;
            }

            if ($bresult) {
                $IDUSER2 = $user_utils->getUserIDFromUUID($uuid);
                if (! empty($IDUSER2) && ! empty($IDUSER)) {
                    $fdata = new \Moviao\Data\Rad\UsersListData();
                    $fdata->set_USR($IDUSER);
                    $fdata->set_USR2($IDUSER2);
                    $subscription = $user_utils->isConnected($fdata);
                    //error_log("subcription : $subscription , iduser : $IDUSER , iduser2 : $IDUSER2 ");
                    $return_data['FOLLOWING'] = $subscription;
                }
            }
        }
    } catch (\Error $e) {
        $bresult = false;
        error_log('UsersCommon >> getUserPublicProfile : ' . $e);
    }

    if ($bresult === true) {
        $array = array('result' => $bresult, 'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}

/**
 * Login otp phase 1
 * @param stdClass $form
 * @return array
 */
public function login_p1(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;
    $pwd_type = self::PWD_TYPE_OTP;

    try {

        if (empty($form) || empty($form->ACCOUNT) || ! \is_string($form->ACCOUNT) || (mb_strlen($form->ACCOUNT) < 4) || (mb_strlen($form->ACCOUNT) > 250)) {
            return array('result' => false,'code' => 666);
        }

        parent::getSession()->startSession();

        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $user_auth = new \Moviao\Data\Util\UsersAuth($this);

        // Check if it's an email or mobile number
        $account = $user_auth->formatUserAccount($form->ACCOUNT,'BE'); // TODO: Modify country selection
        $userdata = $user_auth->getUserAuth($account);

        // Bad password ----------------------------------------------------------- 
        if (! empty($userdata)) {

            // if password == null we use otp (send password to email or sms)
            if (empty($userdata->UAC_PWD)) {
                $code = (string)$user_auth->generateCode();
                $bresult = $user_auth->login_otp_p1($account, $code);
            } else {
                $bresult = true; // Normal Password
                $pwd_type = self::PWD_TYPE_NORMAL;
            }

            if ($bresult === true && $pwd_type === self::PWD_TYPE_OTP) {

                if ($user_auth->validateEmail($account)) {
                    // Send Email
                    try {
                        $csrf = new \Moviao\Security\CSRF_Protect();
                        $token = $csrf->getToken();
                        $account_encoded = urlencode($account);
                        $params = ['CODE' => $code,'ACCOUNT' => $account_encoded, 'CSRF' => $token];
                        $email_utils = new EmailUtils();
                        $template = './app/view/mailing/signin_otp_user_FR.xml';

                        $server = new \Moviao\Http\ServerInfo();
                        $suffix = $server->getServerSuffix();
                        $server_host = $server->getServerHost();

                        if ($suffix !== 'LOCALHOST' && ! is_null($server_host) && substr($server_host, 0, 8) !== "192.168.") {
                            $bresult = $email_utils->sendEmail($account, $template, $params);
                            unset($email_utils);
                        }

                    } catch (\Error $e) {
                        error_log('UsersCommon >> login_p1 >> email : ' . $e);
                        parent::setError(3045453);
                    }

                } else {
                    // Send Short Message (SMS)
                    // TODO: ADD SMS Gateway
                }
            }            
        } 
        //-------------------------------------------------------------------------                 
    } catch (\Error $e) {
        $bresult = false;
        error_log('UsersCommon >> login_otp_p1 : ' . $e);
        parent::setError(4545301);
    }

    if ($bresult === true) {
        $array = array('result' => $bresult,'pwd_type' => $pwd_type);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }    
    
    return $array;
}

/**
 * Login otp phase 2
 * @param stdClass $form
 * @return array
 * @throws \Moviao\Database\Exception\DBException
 */
public function login_p2(\stdClass $form) : array {
    //exit(var_dump($form));
    //exit(is_numeric($form->PWD_TYPE));

    $bresult = false;
    $userdata = null;
                
    try {
        // || empty($form->_csrf) || mb_strlen($form->_csrf) !== 64
        if (empty($form) || empty($form->ACCOUNT) || ! is_string($form->ACCOUNT) || mb_strlen($form->ACCOUNT) < 4 || mb_strlen($form->ACCOUNT) > 255 || empty($form->PWD) || ! is_string($form->PWD) || mb_strlen($form->PWD) < 1 || mb_strlen($form->PWD) > 255 || empty($form->PWD_TYPE) || ! is_numeric($form->PWD_TYPE)) {
            return array('result' => false,'code' => 666);
        }

        parent::getSession()->startSession();
        //$csrf = new \Moviao\Security\CSRF_Protect();

        //$output = 'form = ' . $form->_csrf . ' - sess = ' . $csrf->getToken() . ' - reponse = ' . $csrf->verifyRequest($form->_csrf);
        //exit(var_dump($output));

//        if ($csrf->verifyRequest($form->_csrf) !== true) {
//            return array('result' => false,'code' => 999);
//        }

        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $user_auth = new \Moviao\Data\Util\UsersAuth($this);
        // Check if it's an email or mobile number
        $account = $user_auth->formatUserAccount($form->ACCOUNT,'BE'); // TODO: Modify country selection
        $pwd = trim(mb_substr(filter_var($form->PWD,FILTER_SANITIZE_FULL_SPECIAL_CHARS), 0, 255));
        //----------------------------------------------------------------------
        $server = new \Moviao\Http\ServerInfo();
        $suffix = $server->getServerSuffix();
        $server_host = $server->getServerHost();

        //exit(var_dump($suffix));

        // Is Account Validated  -----------------------------------------------
        if (! $user_auth->isUserValid($account)) {
            return array('result' => false,'code' => 7200550933);
        }
        //----------------------------------------------------------------------

        //exit(var_dump($server->getServerHost()));
        //exit(var_dump($server_host));
        // \str_starts_with($server_host, '192.168.')
        
        if ($suffix !== 'LOCALHOST' && ! is_null($server_host) && substr($server_host, 0, 8) !== "192.168.") {
            $userdata = $user_auth->login_auto($account, $pwd); // Test if user session exists
        } else {
            $userdata = $user_auth->getUser($account); // Autologin for local use
        }
        //----------------------------------------------------------------------
                
        // Bad password --------------------------------------------------------
        if ($userdata === null) {
            $userdata = $user_auth->getUser($account);
            if (!empty($userdata)) {
                $iduser = $userdata->get_USR();
                $user_auth->updateLastAcc($iduser,1);                
                // check unauthorized access
                if ($user_auth->check_access($iduser)) {
                    $user_auth->lock_account($iduser); // Lock account
                    // TODO: Send email for user to unlock his account
                } 
                $userdata = null;
            }                    
        }
        //----------------------------------------------------------------------

        if ($userdata === null || (! $user_auth->authenticate($userdata))) {
            $bresult = false; // Bad Auth 
        } else {

            self::setupUserSession($userdata);

            // $userid = filter_var($userdata->get_USR(),FILTER_SANITIZE_NUMBER_INT); // ID User
            // $acctype = (int) $userdata->get_ACCTYP(); // Account Type
            // $user_uuid = $userdata->get_UUID(); // UUID
            // $user_typaco = (int) $userdata->get_TYPACO(); // User Account Type (Email, Google, Facebook)

            // Destroy all variables of the current session.
            // session_unset();
            // parent::getSession()->setIDUSER($userid);
            // parent::getSession()->setUSER_UUID($user_uuid);
            // parent::getSession()->setAccountType($acctype);
            // parent::getSession()->setUserAccountType($user_typaco);
            // parent::getSession()->setLanguage($userdata->get_LANG());
            //exit(var_dump($user_uuid));

            $bresult = true;
        }                
    } catch (\Error $e) {
        $bresult = false;
        error_log('UsersCommon >> login_p2 : ' . $e);
        parent::setError(30454445441);
    }

    if ($bresult === true) {
        $user_utils = new \Moviao\Data\Util\UsersUtils($this);
        $userid = filter_var($userdata->get_USR(),FILTER_SANITIZE_NUMBER_INT);
        $return_data = $user_utils->getUserSessionInfo($userid);
        $array = array('result' => $bresult, 'data' => $return_data);
        //exit(var_dump($return_data));
        //exit(var_dump($_SESSION));
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    //exit(var_dump($array));
    
    return $array;
}

/**
 * Login facebook v6
 * @param stdClass $form
 * @return array
 * @throws \Facebook\Exceptions\FacebookSDKException
 */
public function login_facebook_v6(\stdClass $form) : array {
    $bresult = false;
    $bnew = false;

    try {
        parent::getSession()->startSession();

        //exit(__DIR__);

        require_once __DIR__ . '../../../../package/facebook-login/vendor/autoload.php'; 

        $fb = new \JanuSoftware\Facebook\Facebook([
            'app_id' => '867246163456148',
            'app_secret' => '8ae1bbc716a2a4a37456f42a519f6ae5',
            'default_graph_version' => 'v13.0',
            'default_access_token' => 'e7ad03165bd29da5db59a77807d5d6b6', // optional
        ]);

        // Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
        //   $helper = $fb->getRedirectLoginHelper();
        $helper = $fb->getJavaScriptHelper();
        //   $helper = $fb->getCanvasHelper();
        //   $helper = $fb->getPageTabHelper();
        //   $helper = $fb->getRedirectLoginHelper();

        // Get the \Facebook\GraphNodes\GraphUser object for the current user.
        // If you provided a 'default_access_token', the '{access-token}' is optional.
        $accessToken = $helper->getAccessToken();
        //exit(var_dump($accessToken));
        $response = $fb->get('/me?locale=en_US&fields=first_name,middle_name,last_name,link,birthday,location,name,email,age_range,picture,cover', $accessToken);
        //$response = $fb->get('/me', $accessToken);

    } catch(\JanuSoftware\Facebook\Exception\ResponseException $e) {
        // When Graph returns an error
        $response = null;
        error_log('Graph returned an error: ' . $e->getMessage());
    }

    //} catch(\JanuSoftware\Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        //$response = null;
        //error_log('Facebook SDK returned an error: ' . $e->getMessage());
    //}

    if ((null !== $response) && (! empty($accessToken))) {
        
        try {
                $mail_fb = null;
                // Get the base class GraphNode from the response
                $graphNode = $response->getGraphNode();
                //$me = $response->getGraphUser();
                //$pic = $response->getGraphEvent()
                //echo 'Logged in as ' . var_dump($me->getPicture()['url']) . '\n';
                //echo 'Values : ' . var_dump($graphNode->getField("id")) . '\n';

                //$graphNode->getField('name');
                //exit();

                // Logged in. //echo 'accesstoken';
                $data = parent::getDBConn();

                if (! $data->connectDBA()) {
                    return array('result' => false,'code' => 888);
                }

                $user_utils = new \Moviao\Data\Util\UsersUtils($this);

                // Login or create account
                $facebook_id = $graphNode->getField("id"); //$graphNode->getId();

                if (! empty($facebook_id)) {

                    $isAccountFacebookExist = $user_utils->isAccountFacebookExist($facebook_id);
                    
                    if (! $isAccountFacebookExist) {

                        $bnew = true;
                        // Check if Facebook account exist
                        $mail_fb = $graphNode->getField("email");
                        $data->startTransaction();

                        // Create Facebook Account
                        $bresult = $user_utils->create_user_facebook($graphNode);                      

                        // Check if account with email exist
                        if ((empty($mail_fb)) || (!$user_utils->isAccountExist($mail_fb))) {
                           
                            // Create Full User Account + email
                           if ($bresult === true) {
                               $bresult = $user_utils->create_user_account_facebook($graphNode);
                               //exit("create full facebook account ");
                           }

                        } elseif ($bresult === true) {
                            $bresult = $user_utils->associate_account_facebook($graphNode);
                        }

                    } else {
                        $bresult = true; // Account already exist and automatic login
                    }

                    // Set Session to user
                    if ($bresult == true) {
                        $user_auth = new \Moviao\Data\Util\UsersAuth($this);
                        $userdata = $user_auth->getUser($facebook_id);
                        if (null !== $userdata) {

                            self::setupUserSession($userdata);

                            // $userid = $userdata->get_USR();
                            // if (! empty($userid)) {
                            //     parent::getSession()->setData('IDUSER', strval($userid));
                            // } else {
                            //     $bresult = false;
                            // }

                        } else {
                            $bresult = false;
                            parent::setError(461342478);
                        }
                    }

                    // Sendmail ------------------------------------------------------------
                    // if ($bresult === true && $bnew === true  && 1 == 2) {
                    //     $params = ['FNAME' => $graphNode->getField("first_name"),'LNAME' => $graphNode->getField("last_name")];
                    //     $email_utils = new EmailUtils();
                    //     // Send Email to customer
                    //     if (null !== $mail_fb) {
                    //         try {
                    //             $template = './app/view/mailing/signup_user_EN.xml';
                    //             $email_utils->sendEmail($mail_fb, $template, $params);
                    //         } catch (\Error $e) {
                    //             error_log('UsersCommon >> signup facebook >> email : ' . $e);
                    //             parent::setError(303458253);
                    //         }
                    //     }

                    //     // Send Email to Moviao Team
                    //     try {
                    //         $template = './app/view/mailing/signup_user_EN.xml';
                    //         $email_utils->sendEmail('moviaonetwork@gmail.com', $template, $params);
                    //     } catch (\Error $e) {
                    //         error_log('UsersCommon >> signup facebook >> email2 : ' . $e);
                    //         parent::setError(304745896);
                    //     }
                    // }
                    //----------------------------------------------------------------------


                } else {
                    $bresult = false;
                    parent::setError(68523578);
                }
            } catch (\Error $e) {
                $bresult = false;
                parent::setError(96587445441);
                error_log('UsersCommon >> login_facebook_v6 : ' . $e);
            } finally {
                if ($bnew === true) {
                    if ($bresult === true) {
                        if (null !== $data) $data->commitTransaction();
                    } else {
                        if (null !== $data) $data->rollbackTransaction();
                    }
                }
            }
    }

    if ($bresult === true) {
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}









/**
 * Login google identity v1
 * @param stdClass $form
 * @return array
 * @throws 
 */
public function login_google_v1(\stdClass $form) : array {

    $bresult = false;
    $bnew = false;

    // Fields
    $fields = array(
        "google_id" => null,
        "email" => null,
        "email_verified" => null,
        "name" => null,
        "picture" => null,
        "given_name" => null,
        "family_name" => null);
    
    try {

        if (! isset($form->token) || empty($form->token)) {
            return array('result' => false,'code' => 785452121547);
        }

        // Get $id_token jwt token signed message
        $id_token = $form->token;

        parent::getSession()->startSession();
        
        require_once __DIR__ . '../../../../package/googleidentity/vendor/autoload.php'; 
                
        $client = new \Google\Client(['client_id' => self::CLIENT_GOOGLE_IDENTITY_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($id_token);
        if ($payload) {           
            // If request specified a G Suite domain:
            //$domain = $payload['hd'];
            //exit(var_dump($payload));

            $fields["userid"] = $payload['sub'];
            $fields["email"] = $payload['email'];
            $fields["email_verified"] = $payload['email_verified'];
            $fields["name"] = $payload['name'];
            $fields["picture"] = $payload['picture'];
            $fields["given_name"] = $payload['given_name'];
            $fields["family_name"] = $payload['family_name'];

        } else {
            // Invalid ID token
            return array('result' => false,'code' => 787875454544545);
        }

    } catch(\Exception $e) {
        // When Graph returns an error
        $response = null;
        error_log('Google Identity returned an error ');
    }

    if ((null !== $payload) && (! empty($fields["userid"]))) {
        
        try {
                
            $data = parent::getDBConn();
            if (! $data->connectDBA()) {
                return array('result' => false,'code' => 888);
            }

            $user_utils = new \Moviao\Data\Util\UsersUtils($this);

            // Login or create account
            if (! empty($fields["userid"])) {

                $isAccountExist = $user_utils->isAccountGoogleExist($fields["userid"]);

                if (! $isAccountExist) {

                    $bnew = true;
                    $data->startTransaction();

                    // Create Facebook Account
                    $bresult = $user_utils->create_user_google($fields);                      

                    //exit("account created :  " . var_dump($bresult));

                    // Check if account with email exist
                    if ((empty($fields["email"])) || (! $user_utils->isAccountExist($fields["email"]))) {
                        
                        // Create Full User Account + email
                        if ($bresult === true) {
                            $bresult = $user_utils->create_user_account_google($fields);
                            //exit("create full facebook account ");
                        }

                    } elseif ($bresult === true) {
                        $bresult = $user_utils->associate_account_google($fields);
                    }

                } else {
                    $bresult = true; // Account already exist and automatic login
                }

                // Set Session to user
                if ($bresult == true && ! empty($fields["userid"])) {
                    $user_auth = new \Moviao\Data\Util\UsersAuth($this);
                    $userdata = $user_auth->getUser($fields["userid"]);

                    if (null !== $userdata) {

                        self::setupUserSession($userdata);

                        // $userid = $userdata->get_USR();
                        // if (! empty($userid)) {
                        //     parent::getSession()->setData('IDUSER', strval($userid));
                        // } else {
                        //     $bresult = false;
                        // }

                    } else {
                        $bresult = false;
                        parent::setError(8721452155415420);
                    }
                }

                    // Sendmail ------------------------------------------------------------
                    // if ($bresult === true && $bnew === true  && 1 == 2) {
                    //     $params = ['FNAME' => $graphNode->getField("first_name"),'LNAME' => $graphNode->getField("last_name")];
                    //     $email_utils = new EmailUtils();
                    //     // Send Email to customer
                    //     if (null !== $mail_fb) {
                    //         try {
                    //             $template = './app/view/mailing/signup_user_EN.xml';
                    //             $email_utils->sendEmail($mail_fb, $template, $params);
                    //         } catch (\Error $e) {
                    //             error_log('UsersCommon >> signup facebook >> email : ' . $e);
                    //             parent::setError(303458253);
                    //         }
                    //     }

                    //     // Send Email to Moviao Team
                    //     try {
                    //         $template = './app/view/mailing/signup_user_EN.xml';
                    //         $email_utils->sendEmail('moviaonetwork@gmail.com', $template, $params);
                    //     } catch (\Error $e) {
                    //         error_log('UsersCommon >> signup facebook >> email2 : ' . $e);
                    //         parent::setError(304745896);
                    //     }
                    // }
                    //----------------------------------------------------------------------

                } else {
                    $bresult = false;
                    parent::setError(564456454752211);
                }

            } catch (\Throwable $th) {
                $bresult = false;
                parent::setError(54514245478787787);
                error_log('UsersCommon >> login_google_v1 : ' . $th);
            } finally {
                if ($bnew === true) {
                    if ($bresult === true) {
                        if (null !== $data) $data->commitTransaction();
                    } else {
                        if (null !== $data) $data->rollbackTransaction();
                    }
                }
            }
    }

    if ($bresult === true) {
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}


















/**
 * Reset Password
 * @param stdClass $form
 * @return array
 */
public function resetPwd(\stdClass $form) : array {
    $bresult = false;
    $data = null;
    $account = null;
    $auth = null;
    $iduser = null;

    try {

        if (empty($form) || empty($form->P1) || empty($form->P2)) {
            return array('result' => false,'code' => 666);
        }

        // (!isset($form->auth)) || (!isset($form->acc))
        parent::getSession()->startSession();

        if (parent::getSession()->isValid()) {
            $iduser = parent::getSession()->getIDUSER();
        } else {
            $account = isset($form->acc) ? trim(mb_substr(filter_var($form->acc,FILTER_SANITIZE_EMAIL), 0, 255)) : null;
            $auth = isset($form->auth) ? mb_substr(filter_var($form->auth,FILTER_SANITIZE_FULL_SPECIAL_CHARS), 0, 50) : null;
        }

        $pwd1 = mb_substr(filter_var($form->P1,FILTER_SANITIZE_FULL_SPECIAL_CHARS), 0, 50);
        $pwd2 = mb_substr(filter_var($form->P2,FILTER_SANITIZE_FULL_SPECIAL_CHARS), 0, 50);

        // password != repeat
        if ($pwd1 !== $pwd2) {
            return array('result' => false,'code' => 667);
        }

        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();
        $user_utils = new \Moviao\Data\Util\UsersUtils($this);

        if (! parent::getSession()->isValid()) {
            if (empty($account)) {
                return array('result' => false, 'code' => 668);
            }
            $iduser = $user_utils->getIDUSR($account);
            if (empty($iduser)) {
                return array('result' => false, 'code' => 669);
            }

            // Reset User Link
            $array = ['iduser' => $iduser,'account' => $account,'auth' => $auth,'pwd' => $pwd1];
            $bresult = $user_utils->resetUser($array);
        } else {
            $bresult = true;
        }

        // Reset Account Password
        if ($bresult === true) {
            $array = ['iduser' => $iduser,'pwd' => $pwd1];
            $bresult = $user_utils->resetAccount($array);
        }

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('UsersCommon (DBException) >> resetPwd : $e');
    } catch (\Error $e) {
        $bresult = false;
        error_log('UsersCommon >> resetPwd : $e');
    } finally {
        if ($bresult === true) {
            if(! empty($data)) $data->commitTransaction();
        } else {
            if(! empty($data)) $data->rollbackTransaction();
        }
    }

    if ($bresult === true) {
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}

public function recover(\stdClass $form) : array {
    $bresult = false;
    $data = null;

    try {

        if (empty($form) || empty($form->account)) {
            return array('result' => false,'code' => 666);
        }

        parent::getSession()->startSession();
        $data = parent::getDBConn();
        $user_utils = new \Moviao\Data\Util\UsersUtils($this);
        $email_sanitized = trim(mb_substr(filter_var($form->account,FILTER_SANITIZE_EMAIL), 0, 255));

        if ($user_utils->validateEmail($email_sanitized)) {

            if (! $data->connectDBA()) {
                return array('result' => false,'code' => 888);
            }

            $data->startTransaction();
            $userdata = $user_utils->getUser($email_sanitized);

            if (! empty($userdata)) {
                if ($userdata->get_ACTIVE() === '1') {

                    // Insert Users Reset
                    $auth = mb_substr($user_utils->generateCodeReset(),0,50);
                    $ip = filter_var($_SERVER['REMOTE_ADDR'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                    $array = [
                        'iduser' => $userdata->get_USR(),
                        'account' => $email_sanitized,
                        'auth' => $auth,
                        'ip' => $ip];
                    $bresult = $user_utils->requestReset($array);

                    // Send Mail
                    if ($bresult === true) {
                        $params = [ 'ACCOUNT' => $form->account, 'AUTH' => $auth, 'DOMAIN' => 'messenger.moviao.com' ];
                        $template = './app/view/mailing/recover_EN.xml';
                        $email_utils = new EmailUtils();
                        $bresult = $email_utils->sendEmail($email_sanitized, $template,$params);

                        if($bresult == false) {
                            parent::setError(301);
                        }
                    } else {
                        parent::setError(305);
                    }

                } else {
                    parent::setError(302);
                }
            } else {
                parent::setError(303);
            }
        } else {
            parent::setError(304);
        }

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('UsersCommon (DBException) >> recover : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('UsersCommon >> recover : ' . $e);
        parent::setError(301);
    } finally {
        if ($bresult === true) {
            if(! empty($data)) {
                $data->commitTransaction();
            }
        } else {
            if(! empty($data)) {
                $data->rollbackTransaction();
            }
        }
    }

    if ($bresult === true) {
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}

/**
 * Signup an account
 * @param stdClass $form
 * @return array
 * @throws \libphonenumber\NumberParseException
 */
public function signup(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;      
    $data = null;    
    //exit(var_dump($form));
    // Account Type 
    // 1 = Normal    
    try {
        // || empty($form->BDATE) || empty($form->GENDER)
        if (empty($form) || empty($form->FNAME) || empty($form->LNAME) || empty($form->EMAIL) || empty($form->CNT) || empty($form->PWD) || empty($form->ACCOUNTTYPE) || empty($form->GENDER)) {
            return array('result' => false,'code' => 3456354654);
        }
        // || mb_strlen(trim($form->BDATE)) <> 10 || mb_strlen(trim($form->GENDER)) <> 1
        if (trim($form->FNAME) === '' || trim($form->LNAME) === '' || mb_strlen(trim($form->FNAME)) < 3 || mb_strlen(trim($form->LNAME)) < 3 || mb_strlen(trim($form->EMAIL)) < 5 || mb_strlen(trim($form->CNT)) < 2 || mb_strlen(trim($form->PWD)) < 5) {
            return array('result' => false,'code' => 4426547454);
        }

        parent::getSession()->startSession();

        // Csrf Protection
        //$csrf = new \Moviao\Security\CSRF_Protect();
        //exit(var_dump($csrf->getToken()));
//        if (empty($form->_csrf) || $csrf->verifyRequest($form->_csrf) !== true) {
//            return array('result' => false,'code' => 999);
//        }

//        if (is_null($form) || (!isset($form->FNAME)) || (!isset($form->LNAME)) || (!isset($form->ACCOUNT)) || (!isset($form->CNT)) || (!isset($form->PWD)) || (!isset($form->PWD2))) {        
//            return array('result' => false,'code' => 666);
//        } 
//        if ((strlen($form->FNAME)<=3) || (strlen($form->LNAME)<=3) || (strlen($form->ACCOUNT)<=3) || (strlen($form->CNT)<=1) || (strlen($form->PWD)< 6) || (strlen($form->PWD2)< 6)) {        
//            return array('result' => false,'code' => 667);
//        }         
//        if ($form->PWD != $form->PWD2) {
//            return array('result' => false,'code' => 4878778);
//        }        
//        $options = [
//            'cost' => 12,
//            'salt' => 'SpnKndMxHqBz6xcRRFHysUxBYPEdTu6XEFmMvvhSLLK47nc922',
//        ];
//        echo password_hash('rasmuslerdorf', PASSWORD_BCRYPT, $options).'\n';
                
        // GeoLocation ------------------------------------------
        //    $ip = $_SERVER['REMOTE_ADDR'];
        //    $geoloc = new \GeoIP\GeoLocation();
        //    $geoloc->initDB();
        //    $cnt = $geoloc->getCountryFromIP($ip);
        //-------------------------------------------------------
        // Filter                       
        $form->EMAIL = trim(mb_substr(filter_var($form->EMAIL,FILTER_SANITIZE_EMAIL), 0, 255));
        $form->ACCOUNT = $form->EMAIL;

        if (isset($form->MPHONE)) {
            $form->MPHONE = trim(filter_var($form->MPHONE, FILTER_SANITIZE_NUMBER_INT));
        } else {
            $form->MPHONE = null;
        }

//        if (isset($form->MPHONE_CODE)) {
//            $form->MPHONE_CODE = filter_var($form->MPHONE_CODE, FILTER_SANITIZE_NUMBER_INT);
//        } else {
//            $form->MPHONE_CODE = '';
//        }

        // Format mobile phone
        $form->MPHONE_VALID = null;
        if (! empty($form->MPHONE)) {
            $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $phoneNumberObject = $phoneNumberUtil->parse($form->MPHONE, null);
            $form->MPHONE_VALID = $phoneNumberUtil->format($phoneNumberObject, \libphonenumber\PhoneNumberFormat::E164);
        }

        $form->EMAIL = trim(mb_substr(filter_var(strip_tags($form->EMAIL), FILTER_SANITIZE_EMAIL),0,255));
        $form->FNAME = trim(mb_substr(filter_var(strip_tags($form->FNAME), FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,255));
        $form->LNAME = trim(mb_substr(filter_var(strip_tags($form->LNAME), FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,255));
        $form->CNT = trim(mb_substr(filter_var(strip_tags($form->CNT), FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,3));
        $form->ACCOUNTTYPE = mb_substr(filter_var($form->ACCOUNTTYPE, FILTER_SANITIZE_NUMBER_INT),0,1);
        $form->GENDER = mb_substr(filter_var($form->GENDER, FILTER_SANITIZE_NUMBER_INT),0,1);


        //error_log("create  ??? " . var_export($form, true));

        // Validate Email Account
        $user_utils = new \Moviao\Data\Util\UsersUtils($this);         
        if (! $user_utils->validateEmail($form->ACCOUNT)) {
            return array('result' => false,'code' => 5454445454);            
        }
               
        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        
        $data->startTransaction();
        // Check Invitation Code --------------------------------    
//        $invit = new \Moviao\Data\Util\InvitationUtils($this);
//        $invited = $invit->isInvited($form);    
//        if (! $invited) {
//            return array('result' => false,'code' => 670);
//        }
        //------------------------------------------------------          
        $random = $user_utils->generateCode();
        $form->CODE = (string) $random;

        $bresult = $user_utils->Signup($form);   
        
        // Automatic Account Validation + Creation User
        if ($bresult === true) {
            $bresult = $user_utils->preValidateAccount($form);
//            if ($bresult === true) {
//                $userdata = $user_utils->getUser($form->ACCOUNT);
//                $user_auth = new \Moviao\Data\Util\UsersAuth($this);
//                if (! $user_auth->authenticate($userdata)) {
//                    $bresult = false; // Bad Auth
//                } else {
//                    $bresult = true;
//                    parent::getSession()->setData('IDUSER', filter_var($userdata->get_USR(),FILTER_SANITIZE_NUMBER_INT));
//                }
//            }
        }

        // Sendmail ------------------------------------------------------------
        $server = new \Moviao\Http\ServerInfo();
        $suffix = $server->getServerSuffix();
        $server_host = $server->getServerHost();
        $template_file = './app/view/mailing/signup_user_EN.xml';

        if ($bresult === true && $suffix !== 'LOCALHOST' && substr($server_host, 0, 8) !== "192.168.") {
            $params = ['FNAME' => $form->FNAME,'LNAME' => $form->LNAME,'ACCOUNT' => $form->ACCOUNT,'VALCODE' => $form->CODE, 'EMAIL' => $form->EMAIL, 'DOMAIN' => 'messenger.moviao.com'];
            $email_utils = new EmailUtils();             
            try {               
                $template = $template_file;
                $email_utils->sendEmail($form->ACCOUNT, $template, $params);        
            } catch (\Error $e) {
                error_log('UsersCommon >> signup >> email : ' .$e);
                parent::setError(303);
            }

            try {            
                $template = $template_file;
                $email_utils->sendEmail('moviaonetwork@gmail.com', $template, $params);
            } catch (\Error $e) {
                error_log('UsersCommon >> signup >> email2 : ' . $e);
                parent::setError(304);
            }
        }
        //----------------------------------------------------------------------
        
    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('UsersCommon (DBException) >> signup : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('UsersCommon >> signup : ' . $e);
        parent::setError(305);        
    } finally {    
        if ($bresult === true) {
            if(! empty($data)) {
                $data->commitTransaction();
            }
        } else { 
            if(! empty($data)) {
                $data->rollbackTransaction();
            }
        }   
    }    
        
    if ($bresult === true) {
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    
    return $array;    
}

public function show(?string $uid) : array {
    $bresult = false;
    $return_data = array();

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();
        $IDUSER = parent::getSession()->getIDUSER();
        $data = parent::getDBConn();

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $user_utils = new \Moviao\Data\Util\UsersUtils($this);
        if (empty($uid)) {
            $uid = $user_utils->getUserUUID($IDUSER);
        }
        if (null !== $uid) {
            $uuid_check = \Moviao\Security\UUID::is_valid($uid);
            $options = array('options' => array('regexp' => '/^[A-Za-z][A-Za-z0-9_]{5,14}$/'));
            if ((filter_var($uid, FILTER_VALIDATE_REGEXP, $options) === false) && $uuid_check === false) {
                return array('result' => false, 'code' => 700);
            }
            $return_data = $user_utils->show($uid);
            if (! empty($return_data)) {
                $IDUSER2 = $user_utils->getUserIDFromUUID($uid);
                if (! empty($IDUSER2) && ! empty($IDUSER)) {
                    $bresult = true;
                    $fdata = new \Moviao\Data\Rad\UsersListData();
                    $fdata->set_USR($IDUSER);
                    $fdata->set_USR2($IDUSER2);
                    $subscription = $user_utils->isConnected($fdata);
                    $return_data[0]['SUBSCRIPTION'] = $subscription;
                }
            }
        }
    } catch (\Error $e) {
        $bresult = false;
        error_log('UsersCommon >> show : ' . $e);
    }

    if ($bresult === true) {
        $array = array('result' => $bresult, 'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}

/**
 * Follow a user
 * @param stdClass $form
 * @return array
 */
public function follow(\stdClass $form) : array {
    $bresult = false;
    $data = null;
            
    try {

        if (empty($form) || empty($form->UUID) || ! is_string($form->UUID) || mb_strlen($form->UUID) < 4) {
            return array('result' => false,'code' => 666);
        }

        $uuid = mb_substr(filter_var($form->UUID,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,100);

        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        $uuid_check = \Moviao\Security\UUID::is_valid($uuid);

        $options = array('options'=>array('regexp'=>'/^[A-Za-z][A-Za-z0-9_]{5,14}$/'));
        if ((filter_var($uuid, FILTER_VALIDATE_REGEXP, $options) === false) && $uuid_check === false) {
            return false;
        }

        $data = parent::getDBConn();
        $IDUSER = parent::getSession()->getIDUSER();

        $user_utils = new \Moviao\Data\Util\UsersUtils($this);

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();

        $IDUSER2 = $user_utils->getUserIDFromUUID($uuid);

        if ($IDUSER2 == null) {
            return array('result' => false,'code' => 667);
        }

        if ($IDUSER == $IDUSER2) {
            return array('result' => false,'code' => 668);
        }

        $result_status = $user_utils->follow($IDUSER, $IDUSER2);

        if ($result_status == 0 || $result_status == 1) {
            $bresult = true;
        }      
                
    } catch (\Moviao\Database\Exception\DBException $dbex) {
        $bresult = false;
        error_log('UsersCommon (DBException) >> follow : $dbex');
    } catch (\Error $ex) {
        $bresult = false;
        error_log('UsersCommon >> follow : $ex');
        //parent::setError(301);        
    } finally {
        if ($bresult === true) {
            if(! empty($data)) {
                $data->commitTransaction();
            }
        } else { 
            if(!  empty($data)) {
                $data->rollbackTransaction();
            }
        }
    }  
    
    if ($bresult === true) {
        $array = array('result' => $bresult,'status' => $result_status);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }    
    
    return $array;
}

/**
 * Unfollow a user
 * @param stdClass $form
 * @return array
 */
public function unfollow(\stdClass $form) : array {
    $bresult = false;
    $data = null;
    
    try {

        if (empty($form) || empty($form->UUID) || ! is_string($form->UUID) || mb_strlen($form->UUID) < 4) {
            return array('result' => false,'code' => 666);
        }

        $uuid = mb_substr(filter_var($form->UUID,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,100);

        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        $uuid_check = \Moviao\Security\UUID::is_valid($uuid);

        $options = array('options'=>array('regexp'=>'/^[A-Za-z][A-Za-z0-9_]{5,14}$/'));
        if ((filter_var($uuid, FILTER_VALIDATE_REGEXP, $options) === false) && $uuid_check === false) {
            return false;
        }

        $data = parent::getDBConn();    
        $IDUSER = parent::getSession()->getIDUSER();
        $user_utils = new \Moviao\Data\Util\UsersUtils($this);

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();

        $IDUSER2 = $user_utils->getUserIDFromUUID($uuid);

        if ($IDUSER2 == null) {
            return array('result' => false,'code' => 667);
        }

        if ($IDUSER == $IDUSER2) {
            return array('result' => false,'code' => 668);
        }

        if (! empty($IDUSER2)) {
            $bresult = $user_utils->unfollow(parent::getSession()->getIDUSER(), $IDUSER2);
        }
        
    } catch (\Moviao\Database\Exception\DBException $dbex) {
        $bresult = false;
        error_log('UsersCommon (DBException) >> unfollow : $dbex');
    } catch (\Error $ex) {
        $bresult = false;
        error_log('UsersCommon >> unfollow : $ex');
        parent::setError(301);        
    } finally {
        if ($bresult === true) {
            if(! empty($data)) {
                $data->commitTransaction();
            }
        } else { 
            if(! empty($data)) {
                $data->rollbackTransaction();
            }
        }
    }  
    
    if ($bresult === true) {
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }    
    
    return $array;
}

/**
 * Confirm a relation
 * @param stdClass $form
 * @return array
 */
public function confirmRelation(\stdClass $form) : array {
    $bresult = false;
    $data = null;
    
    try {

        if (empty($form) || empty($form->UUID) || ! is_string($form->UUID) || mb_strlen($form->UUID) < 4) {
            return array('result' => false,'code' => 666);
        }

        $uuid = mb_substr(filter_var($form->UUID,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,100);

        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        $IDUSER = parent::getSession()->getIDUSER();

        $uuid_check = \Moviao\Security\UUID::is_valid($uuid);
        $options = array('options'=>array('regexp'=>'/^[A-Za-z][A-Za-z0-9_]{5,14}$/'));

        if ((filter_var($uuid, FILTER_VALIDATE_REGEXP, $options) === false) && $uuid_check === false) {
            return false;
        }

        $data = parent::getDBConn();    

        $user_utils = new \Moviao\Data\Util\UsersUtils($this);

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();

        $IDUSER2 = $user_utils->getUserIDFromUUID($uuid);

        if (empty($IDUSER2)) {
            return array('result' => false,'code' => 667);
        }

        if ($IDUSER === $IDUSER2) {
            return array('result' => false,'code' => 668);
        }

        if (! empty($IDUSER2)) {
            $bresult = $user_utils->confirmRelation($IDUSER, $IDUSER2);
        }
        
    } catch (\Moviao\Database\Exception\DBException $dbex) {
        $bresult = false;
        error_log('UsersCommon (DBException) >> confirmRelation : ' . $dbex);
    } catch (\Error $ex) {
        $bresult = false;
        error_log('UsersCommon >> confirmRelation : ' . $ex);
        parent::setError(301);        
    } finally {
        if ($bresult === true) {
            if(! empty($data)) {
                $data->commitTransaction();
            }
        } else { 
            if(! empty($data)) {
                $data->rollbackTransaction();
            }
        }
    }  
    
    if ($bresult === true) {
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }    
    
    return $array;
}

/**
 * Validate new account
 * @param stdClass $form
 * @return array|bool[]
 */
public function validate(\stdClass $form) : array {
    $bresult = false;
    $data = null;

    try {
        parent::getSession()->startSession();

        if (empty($form) && (!isset($form->ACCOUNT)) && (!isset($form->CODE))) {
            return array('result' => false,'code' => 666);
        }
        $form->ACCOUNT = trim(substr(filter_var($form->ACCOUNT,FILTER_SANITIZE_EMAIL), 0, 255));
        $form->CODE = filter_var($form->CODE, FILTER_SANITIZE_NUMBER_INT);
        // Validate Email Account
        $user_utils = new \Moviao\Data\Util\UsersUtils($this);
        if (! $user_utils->validateEmail($form->ACCOUNT)) {
            return array('result' => false,'code' => 667);
        }
        if (filter_var($form->CODE, FILTER_VALIDATE_INT) === false) {
            return array('result' => false,'code' => 668);
        }
        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $data->startTransaction();
        $user_utils = new \Moviao\Data\Util\UsersUtils($this);
        $bresult = $user_utils->validateAccount($form);

        if ($bresult === true) {
            //$pwd = $user_utils->getUserPwd($form->ACCOUNT);
            //if (! is_null($pwd)) {
                try {
                    $params = [ 'EMAIL' => $form->ACCOUNT ];
                    //$params = [];
                    // Sendmail ------------------------------------------------------------
                    $email_utils = new EmailUtils();
                    $template = './app/view/mailing/validate_account_EN.xml';
                    $email_utils->sendEmail($form->ACCOUNT, $template, $params);
                } catch (\Error $ex) {
                    error_log('UsersCommon >> validate >> sendEmail : ' . $ex);
                    parent::setError(544541419678);
                }
            //}
        }

    } catch (\Moviao\Database\Exception\DBException $dbex) {
        $bresult = false;
        error_log('UsersCommon (DBException) >> validate : ' . $dbex);
        parent::setError(874546444444);
    } catch (\Error $ex) {
        $bresult = false;
        error_log('UsersCommon >> validate : ' . $ex);
        parent::setError(896546444444);
    } finally {
        if ($bresult === true) {
            if(! is_null($data)) {
                $data->commitTransaction();
            }
        } else {
            if(! is_null($data)) {
                $data->rollbackTransaction();
            }
        }
    }

    if ($bresult === true) {
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}

/**
 * Save User Search Preference
 * @param stdClass $form
 * @return array
 */
public function saveSearchPreference(\stdClass $form) : array {
    $bresult = false;
    $data = null;

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->CITY) || ! is_string($form->CITY) || mb_strlen($form->CITY) < 3) {
            return array('result' => false,'code' => 666);
        }
        
        $data = parent::getDBConn();

        $city = mb_substr(filter_var($form->CITY,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,150);

        // Default Location Amsterdam Starting point
        if ($city === 'Amsterdam') {
            $lat = '52.3680';
            $lng = '4.9036';
        } else if ($city === 'Brussels') {
            // Brussels : 50°51'1.62"N, 4°20'55.61"E
            $lat = '50.5116';
            $lng = '4.2055';
        }

        $user_pref = new stdClass();
        $user_pref->LOCATION = $city;
        $user_pref->LAT = $lat;
        $user_pref->LON = $lng;
        $user_pref->USR = parent::getSession()->getIDUSER();
        $user_pref->DATINS = date('Y-m-d H:i:s');
        $user_pref->RAD = 30;

        $user_utils = new \Moviao\Data\Util\UsersUtils($this);

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();
        $bresult = $user_utils->saveUserSearchPreference($user_pref);

    } catch (\Moviao\Database\Exception\DBException $dbex) {
        $bresult = false;
        error_log('UsersCommon (DBException) >> saveSearchPreference : ' . $dbex);
    } catch (\Error $ex) {
        $bresult = false;
        error_log('UsersCommon >> saveSearchPreference : ' . $ex);
        //parent::setError(301);
    } finally {
        if ($bresult === true) {
            if(! empty($data)) {
                $data->commitTransaction();
            }
        } else {
            if(! empty($data)) {
                $data->rollbackTransaction();
            }
        }
    }

    if ($bresult === true) {
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}




/**
 * Save User Business Information
 * @param stdClass $form
 * @return array
 */
public function saveBusinessInformation(\stdClass $form) : array {
    $bresult = false;
    $data = null;

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || ! is_string($form->VAT) || mb_strlen($form->VAT) < 8 || ! is_string($form->COMPANY_NAME) || mb_strlen($form->COMPANY_NAME) < 2) {
            return array('result' => false,'code' => 666);
        }

        $vatnumber = mb_substr(filter_var($form->VAT,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,15);
        $company_name = mb_substr(filter_var($form->COMPANY_NAME,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,200);

        $validator = new \Ddeboer\Vatin\Validator;
        $bool = $validator->isValid($vatnumber); // 'NL123456789B01'

        if (! $bool) {
            return array('result' => false,'code' => 93847584);
        }

        $user_pref = new stdClass();
        $user_pref->VAT = $vatnumber;
        $user_pref->COMPANY_NAME = $company_name;
        $user_pref->IDUSER = parent::getSession()->getIDUSER();

        $data = parent::getDBConn();
        $user_utils = new \Moviao\Data\Util\UsersUtils($this);

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();
        $bresult = $user_utils->saveBusinessInformation($user_pref);

    } catch (\Moviao\Database\Exception\DBException $dbex) {
        $bresult = false;
        error_log('UsersCommon (DBException) >> saveBusinessInformation : ' . $dbex);
    } catch (\Error $ex) {
        $bresult = false;
        error_log('UsersCommon >> saveBusinessInformation : ' . $ex);
        //parent::setError(301);
    } finally {
        if ($bresult === true) {
            if(! empty($data)) {
                $data->commitTransaction();
            }
        } else {
            if(! empty($data)) {
                $data->rollbackTransaction();
            }
        }
    }

    if ($bresult === true) {
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}














// WTF ???
private function filter(\stdClass $form) : array {
        $return_data = array();

        try {
            parent::getSession()->startSession();
            parent::getSession()->Authorize();
            $data = parent::getDBConn();
            $IDUSER = parent::getSession()->getIDUSER();
            $IDGRP = (isset($form->GRP)) ? (int)$form->GRP : 0;
            $IDEVT = (isset($form->EVT)) ? (int)$form->EVT : 0;
            if ($IDGRP > 0)
                $strSql = 'SELECT u.USR_NNAME,u.USR_NDISP,(SELECT USRIMG_FILE FROM users_img WHERE ID_USR=u.ID_USR AND ID_UAL=0 AND USRIMG_PROFILE=1 LIMIT 1) FILE,(SELECT USRIMG_FOLDER FROM users_img WHERE ID_USR=u.ID_USR AND ID_UAL=0 AND USRIMG_PROFILE=1 LIMIT 1) FOLDER FROM users u,groups_list g WHERE g.ID_GRP=$IDGRP AND g.ID_USR=u.ID_USR AND g.GRPLST_ACTIVE=1 AND u.USR_ACTIVE=1 LIMIT 100;';
            else
                $strSql = 'SELECT u.USR_NNAME,u.USR_NDISP FROM users u,events_list e WHERE e.ID_EVT=$IDEVT AND e.ID_USR=u.ID_USR AND e.EVTLST_ACTIVE=1 AND u.USR_ACTIVE=1 LIMIT 100;';
            //echo $strSql;
            if (! $data->connectDBA()) {
                return array('result' => false,'code' => 888);
            }
            $res_t = $data->executeQuery($strSql);
            $i=0;

            while ($obj = $util->getLineObject($res_t)) {
                if (!is_null($obj)) {
                    $return_data['data'][$i]['NNAME'] = $obj->USR_NNAME;
                    $return_data['data'][$i]['NDISP'] = $obj->USR_NDISP;
                    $return_data['data'][$i]['FILE'] = $obj->FILE;
                    $return_data['data'][$i]['FOLDER'] = $obj->FOLDER;
                    //$return_data['data'][$i]['USR_IGNORE'] = $obj->USR_IGNORE;
                    $i++;
                }
            }

        } catch (\Error $ex) {
            $return_data = array();
        }

        return $return_data;
    }

private function getContacts()  : array  {
    $return_data = array();  
    $bresult = false;
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();        
        $IDUSER = parent::getSession()->getIDUSER();
        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $user_utils = new \Moviao\Data\Util\UsersUtils($this);
        $return_data = $user_utils->getContacts($IDUSER); 
        $bresult = true;
    } catch (\Error $ex) {
        $bresult = false;
        error_log('UsersCommon >> getContacts : $ex');
    }     
        
    if ($bresult) {
        $array = array('result' => $bresult, 'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    
    return $array; 
}

private function search(string $form) : array {
    $return_data = array();
    $bresult = false;
    
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();
        if (empty($form)) {
            return $return_data;
        }      
        $form = filter_var($form, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH);             
        $IDUSER = parent::getSession()->getIDUSER();        
        $search_form = new stdClass();
        $search_form->pattern = $form;
        $search_form->iduser = $IDUSER;        
        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $user_utils = new \Moviao\Data\Util\UsersUtils($this);
        $return_data = $user_utils->searchUser($search_form);  
        $bresult = true;
    } catch (\Error $ex) {
        $bresult = false;
        error_log('UsersCommon >> search : $ex');
    }    
        
    if ($bresult === true) {
        $array = array('result' => $bresult, 'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    
    return $array;
}

public function getEventMembers(\stdClass $form) : array {
    $bresult = false;
    $return_data = array(); 
    $limit = 10000; // Max 10000 guests
    
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        // || (!isset($form->DATBEG)) || empty($form->DATBEG)

        if (is_null($form) || (!isset($form->UID)) || empty($form->UID)) { 
            return array('result' => false,'code' => 666);
        }   

        $urllink = null;
        $datbeg = null;

        $IDUSER = parent::getSession()->getIDUSER();                  
        $data = parent::getDBConn();

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        // Get Event ID
        $urllink = mb_substr(strip_tags(trim($form->UID)),0,60);

        if (isset($form->DATBEG)) {
            $datbeg = mb_substr(strip_tags(trim($form->DATBEG)),0,19);
        }
        
        if (isset($form->LIMIT)) {
            $limit = intval(filter_var($form->LIMIT, FILTER_SANITIZE_NUMBER_INT));
            if ($limit > 10000) {
                $limit = 10000;
            }
        }
        
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($urllink);
        
        if ($eventID <= 0) {
            return array('result' => false,'code' => 777);
        }        

        $f = new stdClass();
        $f->idevent = $eventID;
        $f->lang = 'en-GB'; // TODO : Integrate lang

        $eventDates = $event_utils->getEventDates($f);
        
        if (is_null($datbeg) && count($eventDates) > 0) {
            // TODO: Format Iso 8601
            $datbeg = $eventDates[0]['DATBEG'];
        }

        //------------------------        

        $user_utils = new \Moviao\Data\Util\UsersUtils($this);
        $return_data = $user_utils->getEventMembers($eventID,$IDUSER,$datbeg,$limit); 
        $bresult = true;

    } catch (\Error $ex) {
        $bresult = false;
        error_log('UsersCommon >> getEventMembers : ' . $ex);
    } 
    
    if ($bresult === true) {
        $array = array('result' => $bresult, 'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }    
    return $array;   
}

public function getChannelMembers(\stdClass $form) : array {
    
    $bresult = false;
    $return_data = array();
    $limit = 10000; // TODO: Max 10000 members
       
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();    

        if (is_null($form) || (!isset($form->UID)) || empty($form->UID)) {          
            return array('result' => false,'code' => 666);
        }         

        $uid = mb_substr(filter_var($form->UID, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH),0,60);      
        
        if (isset($form->LIMIT)) {
            $limit = intval(filter_var($form->LIMIT, FILTER_SANITIZE_NUMBER_INT));
            if ($limit > 10000) {
                $limit = 10000;
            }
        }

        $IDUSER = parent::getSession()->getIDUSER();                  
        $data = parent::getDBConn();
        
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        // Get Channel ID
        $channel_utils = new \Moviao\Data\Util\ChannelsUtils($this);
        $channelID = $channel_utils->getChannelID($uid);

        if ($channelID <= 0) {
            return array('result' => false,'code' => 777);
        }

        $user_utils = new \Moviao\Data\Util\UsersUtils($this);
        $return_data = $user_utils->getChannelMembers($channelID,$IDUSER,$limit); 
        $bresult = true;

    } catch (\Error $ex) {
        $bresult = false;
        error_log('UsersCommon >> getChannelMembers : ' . $ex);
    } 
    
    if ($bresult === true) {
        $array = array('result' => $bresult, 'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }    
    return $array;   
}

public function getUserInfo(\stdClass $form) : array {
    $bresult = false;
    $return_data = array();   
    
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();        
        $IDUSER = parent::getSession()->getIDUSER();
        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        //------------------------        
        $user_utils = new \Moviao\Data\Util\UsersUtils($this);
        $return_data = $user_utils->getUserSessionInfo($IDUSER); 
        if(count($return_data) > 0) $bresult = true;
    } catch (\Error $ex) {
        $bresult = false;
        error_log('UsersCommon >> getUserInfo : $ex');
    } 
    
    if ($bresult === true) {
        $array = array('result' => $bresult, 'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }    
    return $array;    
}

public function readUserPresence(\stdClass $form) : array
{
    $bresult = false;
    $return_data = array();

    try {

        if (empty($form) || empty($form->UUID) || ! is_string($form->UUID) || mb_strlen($form->UUID) < 4) {
            return array('result' => false,'code' => 666);
        }

        parent::getSession()->startSession();
        //parent::getSession()->Authorize();
        $uuid = $form->UUID;
        $data = parent::getDBConn();
        if (!$data->connectDBA()) {
            return array('result' => false, 'code' => 888);
        }
        //------------------------
        $user_utils = new \Moviao\Data\Util\UsersUtils($this);
        $return_data = $user_utils->readUserFromUUID($uuid);
        if (count($return_data) > 0) {
            $bresult = true;
        }

    } catch (\Error $ex) {
        $bresult = false;
        error_log('UsersCommon >> readUserPresence : $ex');
    }

    if ($bresult === true) {
        $array = array('result' => $bresult, 'data' => $return_data);
    } else {
        $array = array('result' => $bresult, 'code' => parent::getError());
    }

    return $array;
}

// Apply User Session from different login method
private function setupUserSession(\Moviao\Data\Rad\UsersData $userdata): bool
{
    $result = false;

    if (! empty($userdata)) {
        $result = true;
        $userid = strval($userdata->get_USR()); // ID User
        $acctype = (int) $userdata->get_ACCTYP(); // Account Type
        $user_uuid = $userdata->get_UUID(); // UUID
        $user_typaco = (int) $userdata->get_TYPACO(); // User Account Type (Email, Google, Facebook)

        // Destroy all variables of the current session.
        session_unset();
        parent::getSession()->setIDUSER($userid);
        parent::getSession()->setUSER_UUID($user_uuid);
        parent::getSession()->setAccountType($acctype);
        parent::getSession()->setUserAccountType($user_typaco);
        parent::getSession()->setLanguage($userdata->get_LANG());
    }

    return $result;
}


}