<?php
declare(strict_types=1);
namespace Moviao\Data\Util;
use PDO;

class UsersUtils extends BaseUtils {

//public function __construct() {}

// Users Account Type
private const USER_ACCOUNT_TYPE_EMAIL = 1;
private const USER_ACCOUNT_TYPE_MOBILE = 2;
private const USER_ACCOUNT_TYPE_FACEBOOK = 3;
private const USER_ACCOUNT_TYPE_GOOGLE = 4;


// Account Type
private const ACCOUNT_TYPE_NORMAL = 1;
private const ACCOUNT_TYPE_PREMIUM = 2;

public function getUserPublicProfile(string $iduser) : array {
    $return_data = array();

    $strSql = 'SELECT * from users WHERE ID_USR=:iduser AND USR_ACTIVE=1 AND USR_LOCKED=0 LIMIT 1;';
    $params = [[ 'parameter' => ':iduser', 'value' => $iduser, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readLineObject($strSql, $params);

    if ($row !== false) {
        $return_data['NDISP'] = $row->USR_NDISP;
        $return_data['UUID'] = $row->USR_UUID;
        $return_data['PICTURE'] = $row->USR_PICTURE;
        $return_data['BACKGROUND'] = $row->USR_BACKGROUND;
        $return_data['BACKGROUND_MIN'] = $row->USR_BACKGROUND_MIN;
        $return_data['OFFICIAL'] = $row->USR_OFFICIAL;
    }

    return $return_data;
}

// user session info
public function getUserSessionInfo(string $IDUSER) : array {
    $return_data = array();    
    $strSql = 'SELECT USR_NDISP,USR_UUID,USR_PICTURE,USR_BUSINESS_CONFIRMED,USR_EMAIL FROM users WHERE ID_USR=? LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $IDUSER, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readLine($strSql, $params);    
    if (is_null($row) || $row == FALSE) {
        return $return_data;
    } else {    
        $return_data['NDISP'] = $row['USR_NDISP']; 
        $return_data['UUID'] = $row['USR_UUID'];
        $return_data['PICTURE'] = $row['USR_PICTURE'];
        $return_data['BUSINESS_CONFIRMED'] = $row['USR_BUSINESS_CONFIRMED'];
        $return_data['EMAIL'] = $row['USR_EMAIL'];
    }     
    return $return_data;
}

public function readUserFromUUID(string $uuid) : array {
    $return_data = array();
    $strSql = 'SELECT USR_NDISP,USR_PICTURE FROM users WHERE USR_UUID=? LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $uuid, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readLine($strSql, $params);

    if (is_null($row) || $row == FALSE) {
        return $return_data;
    } else {
        $return_data['NDISP'] = $row['USR_NDISP'];
        $return_data['AVATAR'] = $row['USR_PICTURE'];
    }
    return $return_data;
}

/**
 * Verify is user are connected together
 * @param \Moviao\Data\Rad\UsersListData $fdata
 * @return int
 * @throws \Moviao\Database\Exception\DBException
 */
public function isConnected(\Moviao\Data\Rad\UsersListData $fdata) : int {
    $result = -1;
    $strSql = 'SELECT USR_CONFIRM,USR_REQ FROM users_list WHERE ID_USR=? AND ID_USR2=? AND USR_ACTIVE=1 LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $fdata->get_USR(), 'type' => PDO::PARAM_STR ],[ 'parameter' => 2, 'value' => $fdata->get_USR2(), 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readLineObject($strSql, $params);

    if ($row !== false) {
        $confirm = (int) $row->USR_CONFIRM;
        $request = (int) $row->USR_REQ;

        if ($confirm === 0 && $request === 0) {
            $result = 2; // User need to confirm
        } else {
            $result = $confirm;
        }
    }

    return $result;
}    

// login otp phase 1
public function login_otp_p1(string $account, string $code) : bool {
    $result = false;
    $strSql = 'UPDATE users_account SET UAC_PWD_OTP=? WHERE UAC_ACCOUNT=? AND UAC_LOCKED=0 AND UAC_ACTIVE=1 LIMIT 1;';
    $password_argon2 = password_hash($code, PASSWORD_ARGON2I);
    $params = [[ 'parameter' => 1, 'value' => $password_argon2, 'type' => PDO::PARAM_STR ], [ 'parameter' => 2, 'value' => $account, 'type' => PDO::PARAM_STR ]];
    return $this->data->executeNonQuery($strSql, $params);
}

/**
 * Login Autoselect (Normal password or OTP)
 * @param string $account
 * @param string $pwd_otp
 * @return \Moviao\Data\model\UsersDataAuth|null
 * @throws \Moviao\Database\Exception\DBException
 */
public function login_auto(string $account,string $pwd_otp) : ?\Moviao\Data\model\UsersDataAuth {
    $strSql = 'SELECT u.ID_USR,uac.UAC_PWD,uac.UAC_PWD_OTP,u.USR_UUID,uac.UAC_ACTIVE,uac.UAC_LOCKED,u.USR_FNAME,u.USR_LNAME,u.USR_NNAME,u.USR_NDISP,u.ID_ACCTYP,u.USR_LANG,uac.ID_TYPACO FROM users u, users_account uac WHERE u.ID_USR=uac.ID_USR AND uac.UAC_ACCOUNT=? LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $account, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readLineObject($strSql, $params);
    if (empty($row)) {
        return null;
    } else {
        $hash_password = null;
        if ($row->UAC_PWD !== null) {
            $hash_password = $row->UAC_PWD; // Normal password
        } else if ($row->UAC_PWD_OTP !== null) {
            $hash_password = $row->UAC_PWD_OTP; // OTP Password
        }
        if (password_verify($pwd_otp, $hash_password)) {
            return $this->getUserData($row);
        } else {
            return null;
        }
    }   
}

//public function login(string $ACCOUNT,string $PWD) : ?\Moviao\Data\Rad\UsersData {
//    $strSql = 'SELECT u.ID_USR,uac.UAC_PWD,u.USR_UUID,uac.UAC_ACTIVE,uac.UAC_LOCKED,u.USR_FNAME,u.USR_LNAME,u.USR_NNAME,u.USR_NDISP,u.ID_ACCTYP FROM users u, users_account uac WHERE u.ID_USR=uac.ID_USR AND uac.UAC_ACCOUNT=? LIMIT 1;';
//    $params = [[ 'parameter' => 1, 'value' => $ACCOUNT, 'type' => PDO::PARAM_STR ]];
//    $row = $this->data->readLineObject($strSql, $params);
//    if ($row === FALSE) {
//        return null;
//    } else {
//        $hash = $row->UAC_PWD;
//        //exit(var_dump($hash));
//        if (password_verify($PWD, $hash)) {
//            return $this->getUserData($row);
//        } else {
//            return null;
//        }
//    }
//}

//public function login2(string $IDUSER) : ?\Moviao\Data\Rad\UsersData {
//    $strSql = 'SELECT u.ID_USR,u.USR_UUID,uac.UAC_ACTIVE,uac.UAC_LOCKED,u.USR_FNAME,u.USR_LNAME,u.USR_NNAME,u.USR_NDISP,u.ID_ACCTYP FROM users u, users_account uac WHERE u.ID_USR=uac.ID_USR AND u.ID_USR=? LIMIT 1';
//    $params = [[ 'parameter' => 1, 'value' => $IDUSER, 'type' => PDO::PARAM_STR ]];
//    $row = $this->data->readLineObject($strSql, $params);
//    if ($row === FALSE) {
//        return null;
//    } else {
//        return $this->getUserData($row);
//    }
//}


//,\Facebook\GraphNodes\GraphNode $graphNode
public function associate_account_facebook(\JanuSoftware\Facebook\GraphNode\GraphNode $user_fb) : bool {
    $result = false;

    $userid = $user_fb->getField("id");
    $mail_fb = $user_fb->getField("email");
    // Link the existing account with facebook
    $userdata = $this->getUser($mail_fb);
    if (! empty($userdata)) {
    
        try {
            // -----------------------------------------------------
            // Create User Account Linked with existing account
            //$random = $this->generateCode();
            //$code = strval($random);
            //$pwd = substr(number_format(time() * mt_rand(),0,'',''),0,10);
            //$pwd_hash = $user_val['PWD']; //password_hash($pwd, PASSWORD_DEFAULT);
            //$password_argon2 = password_hash($code, PASSWORD_ARGON2I);
            $useraccount_data = new \Moviao\Data\Rad\UsersAccountData();
            $useraccount_data->set_TYPACO(self::USER_ACCOUNT_TYPE_FACEBOOK);
            $useraccount_data->set_ACCOUNT($userid);
            $useraccount_data->set_USR($userdata->get_USR());
            $useraccount_data->set_ACTIVE(1);
            $useraccount_data->set_PWD(null);
            $useraccount_data->set_PWD_OTP(null); // $password_argon2
            $useraccount_data->set_DATEINS(date('Y-m-d H:i:s'));
            $useraccount = new \Moviao\Data\Rad\UsersAccount(parent::getData());
            $result = $useraccount->create($useraccount_data);
            
            if (! $result) {
                $this->getData()->setError(16473073);
                return false;
            }
            // -----------------------------------------------------
        } catch (\Error $ex) {
            error_log('UsersUtils >> associate_account_facebook : ' . $ex);
            $this->getData()->setError(13646154);
            return false;
        }
    }

    return $result;
}

function isAccountFacebookExist(string $userid) : bool {
   $bresult = false;
   $strSql = 'SELECT 1 FROM users_facebook WHERE UFB_ID=? LIMIT 1;';
   $params = [[ 'parameter' => 1, 'value' => $userid , 'type' => PDO::PARAM_INT ]];
   $row = $this->data->readColumn($strSql, $params);
   if ($row === FALSE) {
       $bresult = false;
   } else {
       $bresult = true;
   }
   return $bresult;
}

public function associate_account_google(array $fields) : bool {
    $result = false;

    $userid = $fields["userid"];
    $mail = $fields["email"];

    // Link the existing account with google
    $userdata = $this->getUser($mail);
    if (! empty($userdata)) {
        
        try {
            // -----------------------------------------------------
            // Create User Account Linked with existing account
            //$random = $this->generateCode();
            //$code = strval($random);
            //$pwd = substr(number_format(time() * mt_rand(),0,'',''),0,10);
            //$pwd_hash = $user_val['PWD']; //password_hash($pwd, PASSWORD_DEFAULT);
            //$password_argon2 = password_hash($code, PASSWORD_ARGON2I);
            $useraccount_data = new \Moviao\Data\Rad\UsersAccountData();
            $useraccount_data->set_TYPACO(self::USER_ACCOUNT_TYPE_GOOGLE);
            $useraccount_data->set_ACCOUNT($userid);
            $useraccount_data->set_USR($userdata->get_USR());
            $useraccount_data->set_ACTIVE(1);
            $useraccount_data->set_PWD(null);
            $useraccount_data->set_PWD_OTP(null);
            $useraccount_data->set_DATEINS(date('Y-m-d H:i:s'));
            $useraccount = new \Moviao\Data\Rad\UsersAccount(parent::getData());
            $result = $useraccount->create($useraccount_data);
            
            if (! $result) {
                $this->getData()->setError(16473073);
                return false;
            }
            // -----------------------------------------------------
        } catch (\Error $ex) {
            error_log('UsersUtils >> associate_account_google : ' . $ex);
            $this->getData()->setError(13646154);
            return false;
        }
    }

    return $result;
}

function isAccountGoogleExist(string $userid) : bool {
   $bresult = false;
   $strSql = 'SELECT 1 FROM users_google WHERE UGG_ID=? LIMIT 1;';
   $params = [[ 'parameter' => 1, 'value' => $userid , 'type' => PDO::PARAM_STR ]];
   $row = $this->data->readColumn($strSql, $params);
   if ($row === FALSE) {
       $bresult = false;
   } else {
       $bresult = true;
   }
   return $bresult;
}

// ,\Facebook\GraphNodes\GraphNode $graphNode
public function create_user_facebook(\JanuSoftware\Facebook\GraphNode\GraphNode $user_fb) : bool {
    $bresult = false;

    $fdata = new \Moviao\Data\Rad\UsersFacebookData();
    $fdata->set_ID($user_fb->getField("id"));
    $fdata->set_NAME($user_fb->getField("name"));
    $fdata->set_FNAME($user_fb->getField("first_name"));
    $fdata->set_MNAME($user_fb->getField("middle_name"));
    $fdata->set_LNAME($user_fb->getField("last_name"));
    $fdata->set_MAIL($user_fb->getField("email"));
    $fdata->set_BIRTHDAY($user_fb->getField("birthday"));
    $fdata->set_LINK($user_fb->getField("link"));
    $fdata->set_DATEINS(date('Y-m-d H:i:s'));
    
    // if (null !== $user_fb->getField('cover')) {
    //     if (null !== $user_fb->getField('cover')->getField('source')) {
    //         $fdata->set_COVER($user_fb->getField('cover')->getField('source'));
    //     }
    // }

    // if (null !== $user_fb->getField("picture")) {
    //     if (null !== $user_fb->getField("picture")->getField("url")) {
    //         $fdata->set_PICTURE($user_fb->getField("picture")->getField("url"));
    //     }
    // }

    $user_rad = new \Moviao\Data\Rad\UsersFacebook($this->commonData);
    //$user_rad->filterForm($fdata);
    $bresult = $user_rad->create($fdata);

    return $bresult;
}

// ,\Facebook\GraphNodes\GraphNode $graphNode
public function create_user_account_facebook(\JanuSoftware\Facebook\GraphNode\GraphNode $user_fb) {
    $result = false;

    try {
        $uuid = $this->generateUUID(); // Generate UUID
        //exit(var_dump($user_val));
        // User Language
        $array_lang = array('es-ES', 'fr-BE', 'en-GB');
        $user_language = new \Moviao\Http\UserLanguage('en-GB', $array_lang);
        // Create User Profile
        $user_data = new \Moviao\Data\Rad\UsersData();
        $user_data->set_ACCTYP(self::ACCOUNT_TYPE_NORMAL);
        $user_data->set_FNAME($user_fb->getField("first_name"));
        $user_data->set_LNAME($user_fb->getField("last_name"));
        $user_data->set_COUNTRY('Belgium'); // TODO: Change Country
        $user_data->set_ZONEID(52); // TODO: Timezone to change
        $user_data->set_UUID(strtoupper($uuid));
        $user_data->set_NDISP($user_fb->getField("name"));
        $user_data->set_ACTIVE(1);
        $user_data->set_LOCKED(0);
        $user_data->set_LANG($user_language->parseLang());
        $user_data->set_EMAIL($user_fb->getField("email"));
        $user_data->set_MPHONE(null);
        $user_data->set_DATEINS(date('Y-m-d H:i:s'));

//        if (null !== $graphNode->getField('cover')) {
//            if (null !== $graphNode->getField('cover')['source']) {
//                $user_data->set_BACKGROUND($graphNode->getField('cover')['source']);
//            }
//        }
//
//        if (null !== $user_fb->getPicture()) {
//            if (null !== $user_fb->getPicture()['url']) {
//                $user_data->set_PICTURE($user_fb->getPicture()['url']);
//            }
//        }

        $user = new \Moviao\Data\Rad\Users(parent::getData());
        $result = $user->create($user_data);

        if (! $result) {
            $this->getData()->setError(28391795);
            return false;
        }
        // -----------------------------------------------------
        $ID_USR = $this->data->lastInsertId(); // Get Last ID
        if (((int)$ID_USR) <= 0) {
            $this->getData()->setError(91278036);
            return false;
        }
    } catch (\Error $ex) {
        error_log('UsersUtils >> create_user_facebook : ' . $ex);
        $this->getData()->setError(26039108);
        return false;
    }

    try {
        // -----------------------------------------------------
        // Create User Account with facebook id
        //$random = $this->generateCode();
        //$pwd = substr(number_format(time() * mt_rand(),0,'',''),0,10);
        //$pwd_hash = $user_val['PWD']; //password_hash($pwd, PASSWORD_DEFAULT);
        //$random = $this->generateCode();
        //$password_argon2 = password_hash($random, PASSWORD_ARGON2I);
        $useraccount_data = new \Moviao\Data\Rad\UsersAccountData();
        $useraccount_data->set_TYPACO(self::USER_ACCOUNT_TYPE_FACEBOOK);
        $useraccount_data->set_ACCOUNT($user_fb->getField("id"));
        $useraccount_data->set_USR($ID_USR);
        $useraccount_data->set_ACTIVE(1);
        $useraccount_data->set_PWD(null);
        $useraccount_data->set_PWD_OTP(null); // $password_argon2
        $useraccount_data->set_DATEINS(date('Y-m-d H:i:s'));
        $useraccount = new \Moviao\Data\Rad\UsersAccount(parent::getData());
        $result = $useraccount->create($useraccount_data);

        if (! $result) {
            $this->getData()->setError(56431294);
            return false;
        }
        // -----------------------------------------------------

    } catch (\Error $ex) {
        $this->getData()->setError(85936889);
        error_log('UsersUtils >> create_user_facebook > User Account : ' . $ex);
        return false;
    }

    if (! empty($user_fb->getField("email"))) {
        try {
            // -----------------------------------------------------
            // Create User Account with mail
            $random = strval($this->generateCode());
            //$pwd = substr(number_format(time() * mt_rand(),0,'',''),0,10);
            //$pwd_hash = $user_val['PWD']; //password_hash($pwd, PASSWORD_DEFAULT);
            //$random = $this->generateCode();
            $password_argon2 = password_hash($random, PASSWORD_ARGON2I);
            $useraccount_data = new \Moviao\Data\Rad\UsersAccountData();
            $useraccount_data->set_TYPACO(self::USER_ACCOUNT_TYPE_EMAIL);
            $useraccount_data->set_ACCOUNT($user_fb->getEmail());
            $useraccount_data->set_USR($ID_USR);
            $useraccount_data->set_ACTIVE(1);
            $useraccount_data->set_PWD(null);
            $useraccount_data->set_PWD_OTP($password_argon2);
            $useraccount_data->set_DATEINS(date('Y-m-d H:i:s'));
            $useraccount = new \Moviao\Data\Rad\UsersAccount(parent::getData());
            $result = $useraccount->create($useraccount_data);

            if (! $result) {
                $this->getData()->setError(56431294);
                return false;
            }
            // -----------------------------------------------------
        } catch (\Error $ex) {
            $this->getData()->setError(28927217);
            error_log('UsersUtils >> create_user_facebook > User Account : ' . $ex);
            return false;
        }
    }

    return $result;
}

public function create_user_google(array $fields) : bool {
    $bresult = false;

    try {
        $fdata = new \Moviao\Data\Rad\UsersGoogleData();
        $fdata->set_ID($fields["userid"]);    
        $fdata->set_NAME($fields["name"]);
        $fdata->set_FNAME($fields["given_name"]);
        $fdata->set_MNAME(null);
        $fdata->set_LNAME($fields["family_name"]);
        $fdata->set_MAIL($fields["email"]);
        $fdata->set_BIRTHDAY(null);
        $fdata->set_DATEINS(date('Y-m-d H:i:s'));

        $user_rad = new \Moviao\Data\Rad\UsersGoogle($this->commonData);
        //$user_rad->filterForm($fdata);
        $bresult = $user_rad->create($fdata);

    } catch (\Throwable $th) {
        error_log('UsersUtils >> create_user_google : ' . var_export($th));
    }

    return $bresult;
}

public function create_user_account_google(array $fields) {
    $result = false;

    try {
        $uuid = $this->generateUUID(); // Generate UUID
        //exit(var_dump($user_val));
        // User Language
        $array_lang = array('es-ES', 'fr-BE', 'en-GB');
        $user_language = new \Moviao\Http\UserLanguage('en-GB', $array_lang);
        // Create User Profile
        $user_data = new \Moviao\Data\Rad\UsersData();
        $user_data->set_ACCTYP(self::ACCOUNT_TYPE_NORMAL);
        $user_data->set_FNAME($fields["given_name"]);
        $user_data->set_LNAME($fields["family_name"]);
        $user_data->set_COUNTRY('Belgium'); // TODO: Change Country
        $user_data->set_ZONEID(52); // TODO: Timezone to change
        $user_data->set_UUID(strtoupper($uuid));
        $user_data->set_NDISP($fields["name"]);
        $user_data->set_ACTIVE(1);
        $user_data->set_LOCKED(0);
        $user_data->set_LANG($user_language->parseLang());
        $user_data->set_EMAIL($fields["email"]);
        $user_data->set_MPHONE(null);
        $user_data->set_DATEINS(date('Y-m-d H:i:s'));

//        if (null !== $graphNode->getField('cover')) {
//            if (null !== $graphNode->getField('cover')['source']) {
//                $user_data->set_BACKGROUND($graphNode->getField('cover')['source']);
//            }
//        }
//
//        if (null !== $user_fb->getPicture()) {
//            if (null !== $user_fb->getPicture()['url']) {
//                $user_data->set_PICTURE($user_fb->getPicture()['url']);
//            }
//        }

        $user = new \Moviao\Data\Rad\Users(parent::getData());
        $result = $user->create($user_data);

        if (! $result) {
            $this->getData()->setError(28391795);
            return false;
        }
        // -----------------------------------------------------
        $ID_USR = $this->data->lastInsertId(); // Get Last ID
        if (((int)$ID_USR) <= 0) {
            $this->getData()->setError(91278036);
            return false;
        }
    } catch (\Error $ex) {
        error_log('UsersUtils >> create_user_google : ' . $ex);
        $this->getData()->setError(26039108);
        return false;
    }

    try {
        // -----------------------------------------------------
        // Create User Account with google id
        //$random = $this->generateCode();
        //$pwd = substr(number_format(time() * mt_rand(),0,'',''),0,10);
        //$pwd_hash = $user_val['PWD']; //password_hash($pwd, PASSWORD_DEFAULT);
        //$random = $this->generateCode();
        //$password_argon2 = password_hash($random, PASSWORD_ARGON2I);
        $useraccount_data = new \Moviao\Data\Rad\UsersAccountData();
        $useraccount_data->set_TYPACO(self::USER_ACCOUNT_TYPE_GOOGLE);
        $useraccount_data->set_ACCOUNT($fields["userid"]);
        $useraccount_data->set_USR($ID_USR);
        $useraccount_data->set_ACTIVE(1);
        $useraccount_data->set_PWD(null);
        $useraccount_data->set_PWD_OTP(null); 
        $useraccount_data->set_DATEINS(date('Y-m-d H:i:s'));
        $useraccount = new \Moviao\Data\Rad\UsersAccount(parent::getData());
        $result = $useraccount->create($useraccount_data);

        if (! $result) {
            $this->getData()->setError(78754545445);
            return false;
        }
        // -----------------------------------------------------

    } catch (\Error $ex) {
        $this->getData()->setError(5484545775454);
        error_log('UsersUtils >> create_user_google > User Account : ' . $ex);
        return false;
    }

    if (! empty($fields["email"])) {
        try {
            // -----------------------------------------------------
            // Create User Account with mail
            $random = strval($this->generateCode());
            //$pwd = substr(number_format(time() * mt_rand(),0,'',''),0,10);
            //$pwd_hash = $user_val['PWD']; //password_hash($pwd, PASSWORD_DEFAULT);
            //$random = $this->generateCode();
            $password_argon2 = password_hash($random, PASSWORD_ARGON2I);
            $useraccount_data = new \Moviao\Data\Rad\UsersAccountData();
            $useraccount_data->set_TYPACO(self::USER_ACCOUNT_TYPE_EMAIL);
            $useraccount_data->set_ACCOUNT($fields["email"]);
            $useraccount_data->set_USR($ID_USR);
            $useraccount_data->set_ACTIVE(1);
            $useraccount_data->set_PWD(null);
            $useraccount_data->set_PWD_OTP($password_argon2);
            $useraccount_data->set_DATEINS(date('Y-m-d H:i:s'));
            $useraccount = new \Moviao\Data\Rad\UsersAccount(parent::getData());
            $result = $useraccount->create($useraccount_data);

            if (! $result) {
                $this->getData()->setError(2545454241581);
                return false;
            }
            
        } catch (\Error $ex) {
            $this->getData()->setError(645564121574574);
            error_log('UsersUtils >> create_user_google > User Account : ' . $ex);
            return false;
        }
    }

    return $result;
}

public function formatUserAccount(string $account_unsafe, string $default_country) : string {
    $account = '';
    if ($this->validateEmail($account_unsafe)) {
        // Email
        $account = mb_substr(filter_var(trim($account_unsafe),FILTER_SANITIZE_EMAIL), 0, 255);
    } else {
        $account_unsafe = mb_substr(filter_var(trim($account_unsafe), FILTER_SANITIZE_NUMBER_INT), 0, 255);
        // Mobile phone
        $phoneNumberObject = null;
        try {
            $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $phoneNumberObject = $phoneNumberUtil->parse($account_unsafe, $default_country);
            //exit(var_dump($phoneNumberObject));
            if (!empty($phoneNumberObject)) {
                if ($phoneNumberUtil->isValidNumber($phoneNumberObject)) {
                    $account = $phoneNumberUtil->format($phoneNumberObject, \libphonenumber\PhoneNumberFormat::E164);
                }
            }
        } catch (\libphonenumber\NumberParseException $nex) {
            error_log('UsersCommon >> formatAccount >> NumberParseException : $nex');
        } catch (\Error $ex) {
            error_log('UsersCommon >> formatAccount >> Error : $ex');
        }
    }
    return $account;
}

public function lock_account(string $IDUSER) : bool {
    $strSql = 'UPDATE users_account SET UAC_LOCKED=1 WHERE ID_USR=? AND UAC_LOCKED=0 LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $IDUSER, 'type' => PDO::PARAM_STR ]];        
    $result = $this->data->executeNonQuery($strSql, $params);  
    return $result;     
}

// Check Account locked (last 15min)
public function check_access(string $iduser) : bool {
    $strSql = 'SELECT 1 FROM DUAL WHERE (SELECT COUNT(*) FROM users_access WHERE ID_USR=? AND UAA_BAD=1 AND UAA_DATACC >= now()-900) > 4';
    $params = [[ 'parameter' => 1, 'value' => $iduser, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);            
    if ($row === false) {        
        return false;
    } else {                   
        return true;       
    }
}

protected function getUserData($obj) : ?\Moviao\Data\model\UsersDataAuth { 
    if ($obj) {
        $user = new \Moviao\Data\model\UsersDataAuth();
        $user->set_USR($obj->ID_USR);
        $user->set_UUID($obj->USR_UUID);
        $user->set_FNAME($obj->USR_FNAME);
        $user->set_LNAME($obj->USR_LNAME);
        $user->set_NNAME($obj->USR_NNAME);
        $user->set_NDISP($obj->USR_NDISP);
        $user->set_ACTIVE($obj->UAC_ACTIVE);
        $user->set_LOCKED($obj->UAC_LOCKED);
        $user->set_ACCTYP($obj->ID_ACCTYP);
        $user->set_LANG($obj->USR_LANG);
        $user->set_TYPACO($obj->ID_TYPACO);
        return $user;
    }        
    return null;
}

// Get data from User for auth
public function getUserAuth(?string $account): ?\stdClass
{
    if ($account === null) return null;
    $strSql = 'SELECT uac.UAC_PWD FROM users_account uac WHERE uac.UAC_ACCOUNT=? AND uac.UAC_ACTIVE=1 LIMIT 1;';
    $params = [['parameter' => 1, 'value' => $account, 'type' => PDO::PARAM_STR]];
    $row = $this->data->readLineObject($strSql, $params);
    if ($row === false) {
        return null;
    } else {
        $result = new \stdClass();
        $result->UAC_PWD = $row->UAC_PWD;
        return $result;
    }
}

// Get data from User
public function getUser(?string $account) : ?\Moviao\Data\model\UsersDataAuth {
    
    if ($account === null) {
        return null;
    }

    $strSql = 'SELECT u.ID_USR,u.USR_UUID,uac.UAC_ACTIVE,uac.UAC_LOCKED,uac.UAC_PWD,u.USR_FNAME,u.USR_LNAME,u.USR_NNAME,u.USR_NDISP,u.ID_ACCTYP,u.USR_LANG,uac.ID_TYPACO FROM users u, users_account uac WHERE u.ID_USR=uac.ID_USR AND uac.UAC_ACCOUNT=? LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $account, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readLineObject($strSql, $params);

    if ($row === false || empty($row)) {
        return null;
    } else {
        return $this->getUserData($row);        
    }
}

public  function getUserCountry(string $IDUSER) : ?string {
    $strSql = 'SELECT USR_COUNTRY FROM users WHERE ID_USR=? LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $IDUSER, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row === FALSE) {        
        return null;
    } else {                   
        return $row;        
    }   
}

/**
 * 
 * Get IDUSER From Email or Phone number
 * 
 * @param string $account Email or Phone Number
 * @return int ID User 
 *
 *  */
public function getIDUSR(string $account) : int {
    $strSql = 'SELECT ID_USR FROM users_account WHERE UAC_ACCOUNT=? AND UAC_ACTIVE=1 LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $account, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);    
    if (is_null($row) || $row == FALSE) {
        return 0;
    } else {
        return (int)($row);
    }
}

// Update User Last Access
public function updateLastAcc(string $iduser,int $bad) : bool {
    $result = false;
    $remote_addr = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';    
    $strSql = 'INSERT INTO users_access (ID_USR,UAA_DATACC,UAA_IP,UAA_BAD) values (?,UTC_TIMESTAMP(),?,?);'; // Update Last access
    $params = [[ 'parameter' => 1, 'value' => $iduser, 'type' => PDO::PARAM_STR ],[ 'parameter' => 2, 'value' => $remote_addr, 'type' => PDO::PARAM_STR ],[ 'parameter' => 3, 'value' => $bad, 'type' => PDO::PARAM_INT ]];
    $result = $this->data->executeNonQuery($strSql, $params);  
    return $result;      
}    

// Get User ID
//private function getUserID(string $NNAME) : int {
//    $strSql = 'SELECT ID_USR FROM users WHERE USR_NNAME=? AND USR_ACTIVE=1 LIMIT 1;';
//    $params = [[ 'parameter' => 1, 'value' => $NNAME, 'type' => PDO::PARAM_STR ]];
//    $row = $this->data->readColumn($strSql, $params);
//    if ($row === false) {
//        return 0;
//    } else {
//        return (int)$row;
//    }
//}

// Get User ID
public function getUserIDFromUUID(string $uuid) : ?string {
    $strSql = 'SELECT ID_USR FROM users WHERE USR_UUID=? AND USR_ACTIVE=1 LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $uuid, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);

    if ($row === false) {
        return null;
    } else {
        return (string) $row;
    }
}

// Get User UUID
public function getUserUUID(string $IDUSER) : ?string {
    $strSql = 'SELECT USR_UUID FROM users WHERE ID_USR=? LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $IDUSER, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row === false) {
        return null;
    } else {
        return $row;
    }
}

public function getUserInfo(string $IDUSER) : array {
    $return_data = array();
    try {                                   
        $where = [];
        $where[] = ['name' => 'ID_USR', 'value' => $IDUSER, 'type' => 1];
        $where[] = ['name' => 'USR_ACTIVE', 'value' => 1, 'type' => 1];
        $orderby = NULL;
        $limit = 1;
        $user = new  \Moviao\Data\Rad\Users(parent::getData());
        $return_data = $user->show($where, $orderby, $limit);
    } catch (Exception $ex) {
       $this->getData()->setError(501);
    }     
    return $return_data;
}

public function getUserPwd(string $account) : ?string {
    $strSql = 'SELECT UAC_PWD FROM users_account WHERE UAC_ACCOUNT=? LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $account, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params); 
    if ($row === false) {
        return null;
    } else {
        return (string) $row;
    }              
}

/**
 * Request reset password
 * @param array $form
 * @return bool
 * @throws \Moviao\Database\Exception\DBException
 */
public function requestReset(array $form) : bool {    
    $result = false;
    // Reset all request 
    $strSql = 'UPDATE users_reset SET URS_ACTIVE=0,URS_DATMOD=UTC_TIMESTAMP() WHERE ID_USR=? AND URS_ACCOUNT=? AND URS_ACTIVE=1 LIMIT 1;';
    $params = [ 
        [ 'parameter' => 1, 'value' => $form['iduser'], 'type' => PDO::PARAM_INT ],
        [ 'parameter' => 2, 'value' => $form['account'], 'type' => PDO::PARAM_STR ]
    ];        
    $result = $this->data->executeNonQuery($strSql, $params);
    // Insert New request
    $strSql = 'INSERT INTO users_reset (ID_USR,URS_ACCOUNT,URS_AUTH,URS_IP,URS_ACTIVE,URS_DATINS) VALUES (?,?,?,?,1,UTC_TIMESTAMP());';
    $params = [ 
        [ 'parameter' => 1, 'value' => $form['iduser'], 'type' => PDO::PARAM_INT ],
        [ 'parameter' => 2, 'value' => $form['account'], 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 3, 'value' => $form['auth'], 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 4, 'value' => $form['ip'], 'type' => PDO::PARAM_STR ]
    ];
    $result = $this->data->executeNonQuery($strSql, $params);
    return $result;
}

/**
 * Reset User
 * @param array $form
 * @return bool
 * @throws \Moviao\Database\Exception\DBException
 */
public function resetUser(array $form) : bool {
    $result = false;
    // Check if request exist
    $strSql = 'SELECT 1 FROM users_reset WHERE ID_USR=? AND URS_ACCOUNT=? AND URS_ACTIVE=1 AND URS_AUTH=? LIMIT 1 FOR UPDATE;';
    $params1 = [ 
        [ 'parameter' => 1, 'value' => $form['iduser'], 'type' => PDO::PARAM_INT ],
        [ 'parameter' => 2, 'value' => $form['account'], 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 3, 'value' => $form['auth'], 'type' => PDO::PARAM_STR ]
    ];
    $row = $this->data->readColumn($strSql, $params1);
    if (empty($row))  {
        return false;
    }
    // Update Users Reset
    $strSql = 'UPDATE users_reset SET URS_ACTIVE=0,URS_DATMOD=UTC_TIMESTAMP() WHERE ID_USR=? AND URS_ACCOUNT=? AND URS_ACTIVE=1 AND URS_AUTH=? LIMIT 1;';
    return $this->data->executeNonQuery($strSql, $params1);
}

/**
 * Reset Account (Password)
 * @param array $form
 * @return bool
 * @throws \Moviao\Database\Exception\DBException
 */
public function resetAccount(array $form) : bool {
    $password_argon2 = password_hash($form['pwd'], PASSWORD_ARGON2I);
    $strSql = 'UPDATE users_account SET UAC_PWD=?,UAC_DATEMOD=UTC_TIMESTAMP() WHERE ID_USR=? AND UAC_ACTIVE=1 AND UAC_LOCKED=0;';
    $params2 = [
        [ 'parameter' => 1, 'value' => $password_argon2, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 2, 'value' => $form['iduser'], 'type' => PDO::PARAM_STR ]
    ];
    return $this->data->executeNonQuery($strSql, $params2);
}

public function follow(string $IDUSER,string $IDUSER2) : int {
    $result = false; 
    $result_status = -1;
    //$IDUSER2 = $this->getUserIDFromUUID($uuid);

    if ($IDUSER === $IDUSER2) {
        return $result_status;
    }

    $fdata = new \Moviao\Data\Rad\UsersListData();   
    $fdata->set_USR($IDUSER);
    $fdata->set_USR2($IDUSER2);
    $fdata->set_ACTIVE(1);
    $fdata->set_CONFIRM(0); // Must wait confirmation by the user
    $fdata->set_REQ(1);    
    $iSubscribed = $this->isConnected($fdata);
    //$confirmation = ($this->isGroupConfirm($fdata)) ? 0 : 1;
        
    if ($iSubscribed === -1) { 
        if ($IDUSER2 != null) {
            $user_list = new \Moviao\Data\Rad\UsersList($this->commonData);                               
            // Sens 1 : User1 >> User2
            $result = $user_list->create($fdata);            
            // Sens 2 : User2 >> User1       
            if ($result) {                
                $fdata->set_USR($IDUSER2);
                $fdata->set_USR2($IDUSER);
                $fdata->set_REQ(0);
                $fdata->set_CONFIRM(0);
                $result = $user_list->create($fdata);
            }
        }        
    } else if ($iSubscribed === 0) {
        $result = true;
    }
    
    if ($result === true) {
        $result_status = 0; // For the moment Zero because all needs confirmation
    }
    
    return $result_status;
}

public function unfollow(string $IDUSER,string $IDUSER2) : bool {
    $strSql = 'UPDATE users_list SET USR_ACTIVE=0,USR_DATMOD=UTC_TIMESTAMP() WHERE (ID_USR=:IDUSER AND ID_USR2=:IDUSER2 AND USR_ACTIVE=1) OR (ID_USR2=:IDUSER AND ID_USR=:IDUSER2 AND USR_ACTIVE=1) LIMIT 2;';    
    $params = [[ 'parameter' => ':IDUSER', 'value' => $IDUSER, 'type' => PDO::PARAM_STR ],[ 'parameter' => ':IDUSER2', 'value' => $IDUSER2, 'type' => PDO::PARAM_STR ]];
    return $this->data->executeNonQuery($strSql, $params);    
}

public function confirmRelation(string $IDUSER,string $IDUSER2) : bool {
    $strSql = 'UPDATE users_list SET USR_CONFIRM=1,USR_DATMOD=UTC_TIMESTAMP(),USR_DATCONF=UTC_TIMESTAMP() WHERE (ID_USR=:IDUSER AND ID_USR2=:IDUSER2 AND USR_ACTIVE=1 AND USR_CONFIRM=0 AND USR_REQ=0) OR (ID_USR2=:IDUSER AND ID_USR=:IDUSER2 AND USR_ACTIVE=1 AND USR_CONFIRM=0 AND USR_REQ=1) LIMIT 2;';    
    $params = [[ 'parameter' => ':IDUSER', 'value' => $IDUSER, 'type' => PDO::PARAM_STR ],[ 'parameter' => ':IDUSER2', 'value' => $IDUSER2, 'type' => PDO::PARAM_STR ]];
    return $this->data->executeNonQuery($strSql, $params);        
}

public function isAccountExist(string $account) : bool {
    $strSql = 'SELECT 1 FROM users_account WHERE UAC_ACCOUNT=:account LIMIT 1;';
    $params = [['parameter' => 'account', 'value' => $account, 'type' => PDO::PARAM_STR]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row === false) {
        return false;
    } else {
        return true;
    }
}

public function isUUIDExist(string $uuid) : bool {
    $strSql = 'SELECT 1 FROM users WHERE USR_UUID=? LIMIT 1;';
    $params = [['parameter' => 1, 'value' => $uuid, 'type' => PDO::PARAM_STR]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row === false) {
        return false;
    } else {
        return true;
    }
}

public function isUserValid(string $account) : bool {
    $strSql = 'SELECT 1 FROM users_validation WHERE (UVA_EMAIL=:account OR UVA_MPHONE=:account) AND UVA_ACTIVE=1 LIMIT 1;';
    $params = [[ 'parameter' => 'account', 'value' => $account, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);            
    if ($row === false) {
        return false;
    } else {
        return true;
    }
}

public function isUserValidationExists(string $account) : bool {
    $strSql = 'SELECT 1 FROM users_validation WHERE (UVA_EMAIL=:account OR UVA_MPHONE=:account) LIMIT 1;';
    $params = [[ 'parameter' => 'account', 'value' => $account, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row === false) {
        return false;
    } else {
        return true;
    }
}

public function validateEmail(string $mail) : bool {
    return \voku\helper\EmailCheck::isValid($mail);
//    if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
//        return true;
//    } else {
//        return false;
//    }
}

public function generateCode() : int {
    $random = substr(number_format(time() * mt_rand(),0,'',''),0,6);
    return (int)$random;
}

public function generateCodeReset() : string {
    $bytes = random_bytes(50);
    return bin2hex($bytes);
}

public function Signup(\stdClass $form) : bool {

    $result = false;
    // -----------------------------------------------------
    // Check if account with email exists
    if ($this->isAccountExist($form->EMAIL)) {
        $this->getData()->setError(56237620);
        return false;    
    }
    // -----------------------------------------------------
    // Check if account with mobile phone exists
    if (! empty($form->MPHONE_VALID)) {
        if ($this->isAccountExist($form->MPHONE_VALID)) {
            $this->getData()->setError(71610534);
            return false;
        }
    }
    // -----------------------------------------------------    
    if ($this->isUserValidationExists($form->EMAIL)) {
        $this->getData()->setError(30317756);
        return false;    
    }
    // -----------------------------------------------------	
    // Add New User               	    
    $ipaddress = null;

    if (filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    }
    // -----------------------------------------------------

    $password_argon2 = null;

    if (! empty($form->PWD)) {
        $password_argon2 = password_hash($form->PWD, PASSWORD_ARGON2I);
    }

    if (! ($form->ACCOUNTTYPE === '1' || $form->ACCOUNTTYPE === '2')) {
        $form->ACCOUNTTYPE = self::ACCOUNT_TYPE_NORMAL;
    }

    if (! ($form->GENDER === '1' || $form->GENDER === '2')) {
        $form->GENDER = null;
    }

    // $form->GENDER = 1;

    $userdata = new \Moviao\Data\Rad\UsersValidationData();
    $userdata->set_EMAIL($form->EMAIL);
    $userdata->set_MPHONE($form->MPHONE_VALID);
    $userdata->set_EMAIL_CONFIRMED(0);
    $userdata->set_MPHONE_CONFIRMED(0);
    $userdata->set_FNAME($form->FNAME);
    $userdata->set_LNAME($form->LNAME);
    $userdata->set_CODE($form->CODE);
    $userdata->set_LASTIP($ipaddress);
    $userdata->set_CNT($form->CNT);
    $userdata->set_ACCTYP($form->ACCOUNTTYPE);
    $userdata->set_SEX($form->GENDER);
    $userdata->set_TIMEZONE(null);
    $userdata->set_PWD($password_argon2);
    $userdata->set_ACTIVE(0);
    $userdata->set_LOCKED(0);
    $userdata->set_DATINS(date('Y-m-d H:i:s'));
    
    $user = new \Moviao\Data\Rad\UsersValidation(parent::getData());
    $result = $user->create($userdata);

    if (!$result) {
        $this->getData()->setError(84518746);
    }    

    return $result;  
}

// Get Validation Code
public function getValCode(string $account) : ?string {
    $strSql = 'SELECT UVA_CODE FROM users_validation WHERE UVA_EMAIL=:account OR UVA_MPHONE=:account LIMIT 1;';
    $params = [[ 'parameter' => 'account', 'value' => $account, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row === false) {
        return null;
    } else {
        return (string) $row;
    }
}

public function enableUserVal(string $account) : bool {
    $strSql = 'UPDATE users_validation SET UVA_ACTIVE=1 WHERE (UVA_EMAIL=:account OR UVA_MPHONE=:account) AND UVA_ACTIVE=0 LIMIT 1;'; // , UVA_EMAIL_CONFIRMED=1
    $params = [[ 'parameter' => 'account', 'value' => $account, 'type' => PDO::PARAM_STR ]];
    return $this->data->executeNonQuery($strSql, $params);
}

//public function disableUserVal(string $account) : bool {
//    $strSql = 'UPDATE users_validation SET UVA_ACTIVE=0 WHERE (UVA_EMAIL=:account OR UVA_MPHONE=:account) AND UVA_ACTIVE=1 LIMIT 1;';
//    $params = [[ 'parameter' => 'account', 'value' => $account, 'type' => PDO::PARAM_STR ]];
//    return $this->data->executeNonQuery($strSql, $params);
//}

// user Account
public function getUserAccount(string $account) : ?array {
    $strSql = 'SELECT u.UVA_FNAME,u.UVA_LNAME,(IFNULL((SELECT c.id FROM countries c WHERE c.code=u.UVA_CNT),0)) ID_COUNTRY, IFNULL((SELECT c.zone_id FROM zone c WHERE c.country_code=u.UVA_CNT LIMIT 1),0) ZONEID,u.UVA_PWD, u.ID_ACCTYP, u.UVA_SEX FROM users_validation u WHERE u.UVA_EMAIL=:account OR u.UVA_MPHONE=:account LIMIT 1;';
    $params = [[ 'parameter' => 'account', 'value' => $account, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readLine($strSql, $params);    
    if (is_null($row) || $row == FALSE) {
        return null;
    } else {    
        $user_data = array();
        $user_data['FNAME'] = $row['UVA_FNAME'];
        $user_data['LNAME'] = $row['UVA_LNAME'];
        $user_data['COUNTRY'] = $row['ID_COUNTRY'];
        $user_data['ZONEID'] = $row['ZONEID'];
        $user_data['PWD'] = $row['UVA_PWD'];
        $user_data['ACCTYP'] = $row['ID_ACCTYP'];
        $user_data['SEX'] = $row['UVA_SEX'];
        return $user_data;
    }        
}

public function preValidateAccount(\stdClass $form) : bool {

    $result = false;
    //$code = substr(filter_var($form->CODE, FILTER_SANITIZE_NUMBER_INT),0,20);
    
    // -----------------------------------------------------    
//    if (!$this->isUserValid($form->EMAIL)) {
//        $this->GetData()->setError(301);
//        return false;
//    }
    // -----------------------------------------------------    
//    $code_db = $this->getValCode($form->EMAIL);
//    if (is_null($code_db)) {
//        $this->GetData()->setError(302);
//        return false;
//    }
    // -----------------------------------------------------
//    if ($code <> $code_db) {
//        $this->GetData()->setError(303);
//        return false;
//    }
    // -----------------------------------------------------    
    $user_val = $this->getUserAccount($form->EMAIL);
    if (empty($user_val)) {
        $this->getData()->setError(89568662);
        return false;
    }

    //exit(var_dump($user_val));

    // -----------------------------------------------------  
    $uuid = $this->generateUUID();
    // -----------------------------------------------------       

    try {
        
        // User Language
        $array_lang = array('es-ES', 'fr-BE', 'en-GB');
        $user_language = new \Moviao\Http\UserLanguage('en-GB', $array_lang);
        
        // Create User Profile 
        $user_data = new \Moviao\Data\Rad\UsersData();
        $user_data->set_ACCTYP($user_val['ACCTYP']); // self::ACCOUNT_TYPE_NORMAL
        $user_data->set_SEX($user_val['SEX']);
        $user_data->set_FNAME($user_val['FNAME']);
        $user_data->set_LNAME($user_val['LNAME']);
        $user_data->set_CITY('0');
        $user_data->set_STATE('0');
        //$user_data->set_COUNTRY($user_val['COUNTRY']);
        $user_data->set_COUNTRY_CODE($user_val['COUNTRY']);
        $user_data->set_ZONEID($user_val['ZONEID']);                
        $user_data->set_UUID(strtoupper($uuid));
        $ndisp = trim(mb_substr(filter_var($user_val['FNAME'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,255));
        $user_data->set_NDISP($ndisp);
        $user_data->set_ACTIVE(1);
        $user_data->set_LOCKED(0);
        $user_data->set_LANG($user_language->parseLang());
        $user_data->set_EMAIL($form->EMAIL);
        $user_data->set_MPHONE($form->MPHONE_VALID);
        $user_data->set_DATEINS(date('Y-m-d H:i:s'));
        $user = new \Moviao\Data\Rad\Users(parent::getData());

        //exit(var_dump($user_data));
        $result = $user->create($user_data);         
        if (! $result) {
            error_log('preValidateAccount >> signup xxxx: ');
            $this->getData()->setError(33381938);
            return false;
        }                
        // -----------------------------------------------------
        $ID_USR = $this->data->lastInsertId(); // Get Last ID                
        if ((int)($ID_USR) <= 0) {
            $this->getData()->setError(14724067);
            return false;
        }                
    } catch (\Error $ex) {
        error_log('UsersUtils >> validateAccount : ' . $ex);
        $this->getData()->setError(18977991);
        return false;
    }    

    try {
        // -----------------------------------------------------      
        // Create User Account    
        //$random = $this->generateCode();
        //$pwd = substr(number_format(time() * mt_rand(),0,'',''),0,10);
        //$pwd_hash = $user_val['PWD']; //password_hash($pwd, PASSWORD_DEFAULT);
        $password_argon2 = password_hash($form->PWD, PASSWORD_ARGON2I);
        $useraccount_data = new \Moviao\Data\Rad\UsersAccountData();
        $useraccount_data->set_TYPACO(self::USER_ACCOUNT_TYPE_EMAIL);
        $useraccount_data->set_ACCOUNT($form->EMAIL);
        $useraccount_data->set_USR($ID_USR);
        $useraccount_data->set_ACTIVE(1);
        $useraccount_data->set_PWD($password_argon2);
        $useraccount_data->set_PWD_OTP(null);
        $useraccount = new \Moviao\Data\Rad\UsersAccount(parent::getData());
        $result = $useraccount->create($useraccount_data);

        if (! $result) {
            $this->getData()->setError(20330731);
            return false;
        }

        // -----------------------------------------------------
        // Associate Mobile Account
        if (! empty($form->MPHONE_VALID)) {
            $useraccount_data2 = new \Moviao\Data\Rad\UsersAccountData();
            $useraccount_data2->set_TYPACO(self::USER_ACCOUNT_TYPE_MOBILE);
            $useraccount_data2->set_ACCOUNT($form->MPHONE_VALID);
            $useraccount_data2->set_USR($ID_USR);
            $useraccount_data2->set_ACTIVE(1);
            $useraccount_data2->set_PWD($password_argon2);
            $useraccount_data2->set_PWD_OTP(null);
            $useraccount2 = new \Moviao\Data\Rad\UsersAccount(parent::getData());
            $result = $useraccount2->create($useraccount_data2);
            if (! $result) {
                $this->getData()->setError(73016551);
                return false;
            }
        }

    } catch (\Error $ex) {
        error_log('UsersUtils >> create user account : ' . $ex);
        $this->getData()->setError(44593945);
        return false;
    } 

    return $result;
}

private function generateUUID() : string {
    $uuid = null;
    while(true) {
        $uuid = \Moviao\Security\UUID::v4();
        if ((!$this->isUUIDExist($uuid)) && (null !== $uuid)) {
            break;
        }
    }
    return $uuid;
}

public function validateAccount(\stdClass $form) : bool {
    $result = false;
    $account = mb_substr(filter_var($form->ACCOUNT, FILTER_SANITIZE_EMAIL),0,255);
    $code = mb_substr(filter_var($form->CODE, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,20);
    
    // -----------------------------------------------------    
    if ($this->isUserValid($account)) {
        $this->getData()->setError(4545478781);
        return false;        
    }
    // -----------------------------------------------------    
    $code_db = $this->getValCode($account);
    if (is_null($code_db)) {
        $this->getData()->setError(3555445502);
        return false;
    }
    // -----------------------------------------------------	     
    if ($code <> $code_db) { 
        $this->getData()->setError(34545403);
        return false;         
    } 
    // -----------------------------------------------------  
    try {
        // Enable User Validation
        if ($this->enableUserVal($account)) {
            $result = true;
        } else {
            $this->getData()->setError(35665624);
        }
    } catch (Exception $ex) {
        $this->getData()->setError(4545345425);
        return false;
    }

    return $result;
}

public function show(?string $uid) : array {
    $return_data = array(); 
       
    if (empty($uid)) {
        return $return_data;
    }    

    $uuid_check = \Moviao\Security\UUID::is_valid($uid);

    if ($uuid_check) {
        $field_name = 'USR_UUID';
    } else {
        $field_name = 'USR_NNAME';
    }

    // Clause Where
    $where = [];
    $where[] = ['name' => $field_name, 'value' => lcfirst($uid), 'type' => 2];
    $where[] = ['name' => 'USR_ACTIVE', 'value' => 1, 'type' => 1];
    $orderby = NULL;
    $limit = 1;

    $user = new \Moviao\Data\Rad\Users(parent::getData());
    $return_data = $user->show($where, $orderby, $limit);

    return $return_data;
}

// Contacts
public function getContacts(int $uid) : ?array
{
    $return_data = array(); 
    $strSql = 'SELECT b.USR_NDISP,b.USR_NNAME,b.USR_UUID,b.USR_ABOUT,b.USR_PICTURE,a.USR_REQ,(SELECT USR_CONFIRM FROM users_list WHERE ID_USR2=b.ID_USR AND ID_USR=:iduser AND USR_ACTIVE=1 LIMIT 1) SUBSCRIPTION FROM users_list a,users b WHERE a.ID_USR2=b.ID_USR AND a.USR_ACTIVE=1 AND a.ID_USR=:iduser';    
    $stmt = $this->data->prepare($strSql);  
    if ($stmt == null) {
        $this->getData()->setError(501);
        return null;
    }       
    if (! $this->data->bindParam(':iduser',$uid,PDO::PARAM_INT)) {
        $this->getData()->setError(502);
        return null;
    }   
    if (! $this->data->execute()) {
        $this->getData()->setError(4754541245);
        return null;
    }    
    while ($obj = $this->data->fetchObject()) {

        if (is_null($obj)) {
            break;
        }

        //echo $obj->ID_USR2;                 
        $row['NDISP'] =  $obj->USR_NDISP; 
        $row['NNAME'] =  $obj->USR_NNAME; 
        $row['UUID'] =  $obj->USR_UUID;
        $row['ABOUT'] =  $obj->USR_ABOUT;
        $row['PICTURE'] =  $obj->USR_PICTURE;
        $row['SUBSCRIPTION'] =  $obj->SUBSCRIPTION;
        $row['REQ'] =  $obj->USR_REQ;
        $return_data[]  = $row;         
    }
    
    return $return_data;
}

// Search Users
public function searchUser(\stdClass $form) : array {
    $return_data = array();         
    $pattern = '%'.$form->pattern.'%';    
    $strSql = 'SELECT USR_NDISP,USR_NNAME,USR_UUID,USR_ABOUT,USR_PICTURE,(SELECT USR_CONFIRM FROM users_list WHERE ID_USR2=users.ID_USR AND ID_USR=:iduser AND USR_ACTIVE=1 LIMIT 1) SUBSCRIPTION FROM users WHERE USR_ACTIVE=1  AND USR_NDISP LIKE :pattern AND ID_USR <> :iduser AND ID_USR NOT IN (SELECT ID_USR2 FROM users_list WHERE ID_USR=:iduser AND USR_ACTIVE=1 AND USR_IGNORE=0)  order by ID_USR DESC LIMIT 15;';            
    $params = [[ 'parameter' => ':pattern', 'value' => $pattern, 'type' => PDO::PARAM_STR ],[ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ]];    
    $row = $this->data->readAllObject($strSql, $params);      
    if ($row !== FALSE) {        
        foreach ($row as $obj) {
            if (empty($obj)) break;
            $row['NDISP'] =  $obj->USR_NDISP; 
            $row['NNAME'] =  $obj->USR_NNAME; 
            $row['UUID'] =  $obj->USR_UUID;
            $row['ABOUT'] =  $obj->USR_ABOUT;
            $row['PICTURE'] =  $obj->USR_PICTURE;
            $row['SUBSCRIPTION'] =  $obj->SUBSCRIPTION;
            $return_data[]  = $row;      
        }       
    } 
    return $return_data;
}

public function getEventMembers(int $IDEVT,string $IDUSER, string $datbeg, int $limit) : array {
    $return_data = array(); 
    $strSql = 'SELECT b.USR_NDISP,b.USR_NNAME,b.USR_UUID,b.USR_ABOUT,b.USR_PICTURE,(SELECT USR_CONFIRM FROM users_list WHERE ID_USR=b.ID_USR AND ID_USR2=? AND USR_ACTIVE=1 LIMIT 1) SUBSCRIPTION FROM events_list a,users b WHERE a.ID_USR=b.ID_USR AND a.EVTLST_ACTIVE=1 AND a.ID_EVT=? AND a.EVTLST_DATBEG=? ORDER BY b.USR_NDISP LIMIT ?';
    $params = [
        [ 'parameter' => 1, 'value' => $IDUSER, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 2, 'value' => $IDEVT, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => 3, 'value' => $datbeg, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 4, 'value' => $limit, 'type' => PDO::PARAM_INT ]
    ];
    
    $rows = $this->data->readAllObject($strSql, $params);

    foreach ($rows as $obj) {
        if (empty($obj)) break;
        $row['NDISP'] =  $obj->USR_NDISP; 
        $row['NNAME'] =  $obj->USR_NNAME; 
        $row['UUID'] =  $obj->USR_UUID;
        $row['ABOUT'] =  $obj->USR_ABOUT;
        $row['PICTURE'] =  $obj->USR_PICTURE;
        $row['SUBSCRIPTION'] =  $obj->SUBSCRIPTION;
        $return_data[]  = $row;         
    }    

    return $return_data;
}

public function getChannelMembers(int $IDCHA,string $IDUSER, int $limit) : array {
    $return_data = array(); 
    $strSql = 'SELECT b.USR_NDISP,b.USR_NNAME,b.USR_UUID,b.USR_ABOUT,b.USR_PICTURE,(SELECT USR_CONFIRM FROM users_list WHERE ID_USR=b.ID_USR AND ID_USR2=? AND USR_ACTIVE=1 LIMIT 1) SUBSCRIPTION FROM channels_list a,users b WHERE a.ID_USR=b.ID_USR AND a.CHALST_ACTIVE=1 AND a.ID_CHA=? LIMIT ?';
    $params = [
        [ 'parameter' => 1, 'value' => $IDUSER, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 2, 'value' => $IDCHA, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 3, 'value' => $limit, 'type' => PDO::PARAM_INT ]
    ];
    
    $rows = $this->data->readAllObject($strSql, $params);
    
    foreach ($rows as $obj) {
        if (empty($obj)) break;
        $row['NDISP'] =  $obj->USR_NDISP; 
        $row['NNAME'] =  $obj->USR_NNAME; 
        $row['UUID'] =  $obj->USR_UUID;
        $row['ABOUT'] =  $obj->USR_ABOUT;
        $row['PICTURE'] =  $obj->USR_PICTURE;
        $row['SUBSCRIPTION'] =  $obj->SUBSCRIPTION;
        $return_data[]  = $row;         
    }    
    return $return_data;
}

public function updateImageProfile(string $IDUSER,string $img) : bool {
    $strSql = 'UPDATE users SET USR_PICTURE=?,USR_DATEMOD=UTC_TIMESTAMP() WHERE ID_USR=? AND USR_ACTIVE=1 AND USR_LOCKED=0 LIMIT 1;';
    $params = [        
        [ 'parameter' => 1, 'value' => $img, 'type' => PDO::PARAM_STR ],                
        [ 'parameter' => 2, 'value' => $IDUSER, 'type' => PDO::PARAM_INT ]];    
    return $this->data->executeNonQuery($strSql, $params);
}

public function updateBackgroundImageProfile(string $IDUSER, string $picture_loc,?string $picture_loc_mini) : bool {
    $strSql = 'UPDATE users SET USR_BACKGROUND=?,USR_BACKGROUND_MIN=?,USR_DATEMOD=UTC_TIMESTAMP() WHERE ID_USR=? AND USR_ACTIVE=1 AND USR_LOCKED=0 LIMIT 1;';
        $params = [        
        [ 'parameter' => 1, 'value' => $picture_loc, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 2, 'value' => $picture_loc_mini, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 3, 'value' => $IDUSER, 'type' => PDO::PARAM_INT ]];
    return $this->data->executeNonQuery($strSql, $params);
}

// User Location
public function saveLocation(\stdClass $form) : bool {    
    $result = false;    
    try {
        $comObj = new \Moviao\Data\Rad\UsersLocation(parent::getData());
        $fdata = $comObj->filterForm($form);
        $result = $comObj->create($fdata); 
    } catch (\Error $ex) {  
        error_log('GenericUtils >> saveUserLocation = ' . $ex);
    }    
    return $result;
}

public function isUserPreferenceConfigured(string $iduser) : bool {
    $strSql = 'SELECT 1 FROM DUAL WHERE (SELECT COUNT(*) FROM users_search WHERE ID_USR=?) > 0';
    $params = [[ 'parameter' => 1, 'value' => $iduser, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row == null || $row === false) {
        return false;
    } else {
        return true;
    }
}


// User Search
public function saveUserSearchPreference(\stdClass $form) : bool {
    $result = false;
    try {
        $comObj = new \Moviao\Data\Rad\UsersSearch(parent::getData());
        $fdata = $comObj->filterForm($form);
        $result = $comObj->create($fdata);
    } catch (\Error $ex) {
        error_log('GenericUtils >> saveUserLocation = ' . $ex);
    }
    return $result;
}


// User Business Information
public function saveBusinessInformation(\stdClass $form) : bool {
    $result = false;
    try {
        $strSql = 'UPDATE users SET USR_VAT= :vat ,USR_COMPANY_NAME= :companyname, USR_BUSINESS_CONFIRMED=1 WHERE ID_USR = :iduser AND USR_ACTIVE=1;';
        $params = [[ 'parameter' => ':vat', 'value' => $form->VAT, 'type' => PDO::PARAM_STR ],
                   [ 'parameter' => ':companyname', 'value' => $form->COMPANY_NAME, 'type' => PDO::PARAM_STR ],
                   [ 'parameter' => ':iduser', 'value' => $form->IDUSER, 'type' => PDO::PARAM_STR ]
            ];

        $result = $this->data->executeNonQuery($strSql, $params);
    } catch (\Error $ex) {
        error_log('GenericUtils >> saveBusinessInformation = ' . $ex);
    }
    return $result;
}

// User Search - Get Last Config
public function getUserSearchPreference(string $iduser) : array {
    $result = null;
    try {
        $where = [];
        $where[] = ['name' => 'ID_USR', 'value' => $iduser, 'type' => 1];
        $orderby = "USC_DATINS DESC";
        $limit = 1;
        $comObj = new \Moviao\Data\Rad\UsersSearch(parent::getData());
        $result = $comObj->show($where, $orderby, $limit);
    } catch (\Error $ex) {
        error_log('GenericUtils >> getUserSearchPreference = ' . $ex);
    }
    return $result;
}

//public function getLastLocation(\stdClass $form) : array {
//    $return_data = array();
//    return $return_data;
//}

// SSO ---------------------------------------------------------------------
public function saveSSO(string $sessionid, string $tokenid, string $key) : bool {
    $result = false;
    $strSql = 'INSERT INTO sso (SSO_SESSIONID,SSO_TOKENID,SSO_KEY,SSO_DATINS) VALUES (?,?,?,UTC_TIMESTAMP());';
    $params = [
        [ 'parameter' => 1, 'value' => $sessionid, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 2, 'value' => $tokenid, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 3, 'value' => $key, 'type' => PDO::PARAM_STR ]
    ];
    $result = $this->data->executeNonQuery($strSql, $params);
    return $result;
}

public function getSessionSSO(string $tokenid) : array {
    $return_data = array();
    $strSql = 'SELECT SSO_SESSIONID,SSO_KEY FROM sso WHERE SSO_TOKENID = ? AND SSO_ACTIVE=1';
    $params = [[ 'parameter' => 1, 'value' => $tokenid, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readLineObject($strSql, $params);

    if ($row !== false) {
        $return_data['SESSIONID'] = $row->SSO_SESSIONID;
        $return_data['KEY'] = $row->SSO_KEY;
    }

    return $return_data;
}

public function cleanSSO(string $tokenid) : bool {
    $result = false;
    $strSql = 'UPDATE sso SET SSO_ACTIVE=0 WHERE SSO_TOKENID = ? AND SSO_ACTIVE=1;';
    $params = [[ 'parameter' => 1, 'value' => $tokenid, 'type' => PDO::PARAM_STR ]];
    $result = $this->data->executeNonQuery($strSql, $params);
    return $result;
}

}