<?php
declare(strict_types=1);
namespace Moviao\Data;
use Moviao\Data\CommonData;
use stdClass;

class ChannelsCommon extends CommonData {

private const strip_html = '<h1><h2><h3><h4><strong><i><p><u><ul><li><span><blockquote><a><hr><em><del><sub><sup><img>';
private const strip_html_array = array('h1','h2','h3','h4','p','u','blockquote','a','i','b','em','span','strong','ul','ol','li','hr','del','sub','sup','img');

public function __construct() {}

/**
 * Create a new Channel
 * @param stdClass $form
 * @return array
 */
public function create(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;
    $data = null;

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();
        //exit(var_dump($form));

        // || empty($form->NAME) || ! \is_string($form->NAME)
        if (empty($form) || empty($form->TITLE) || empty($form->DESCL) || ! \is_string($form->TITLE) || ! \is_string($form->DESCL)) {
            return array('result' => false,'code' => 666);
        }

        // Csrf Protection
        $csrf = new \Moviao\Security\CSRF_Protect();
        if (empty($form->_csrf) || $csrf->verifyRequest($form->_csrf) !== true) {
            return array('result' => false,'code' => 999);
        }

        // Check Name Pattern
//        $pattern = '/^[A-Za-z0-9_]{1,50}$/';
//        if (! preg_match($pattern, $form->NAME)) {
//            return array('result' => false,'code' => 667);
//        }

        // Filter Title
        $form->TITLE = strip_tags(filter_var(mb_substr($form->TITLE,0, 150), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        // Filter balises HTML5
        $form->DESCL = strip_tags(mb_substr($form->DESCL, 0, 4000), self::strip_html);

        // Filter html xss
        $filter = new \Moviao\Http\HTML_Sanitizer;
        $allowed_protocols = array('http');
        $allowed_tags = self::strip_html_array;
        $filter->addAllowedProtocols($allowed_protocols);
        $filter->addAllowedTags($allowed_tags);
        $form->DESCL = $filter->xss($form->DESCL);

        $data = parent::getDBConn();
        $IDUSER = parent::getSession()->getIDUSER();
        $channel_utils = new \Moviao\Data\Util\ChannelsUtils($this);
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();

        $form->NAME = null;
        while (true) {
            $token = $channel_utils->generateToken(25);
            $form->NAME = mb_substr($token,0, 29);
            // Get Channel ID
            $channelID = $channel_utils->getChannelID($token);
            if ($channelID > 0) {
                continue;
            }
            break;
        }

        $arr = $channel_utils->create($form);
        $bresult = $arr->result;
        $ID_CHA = $arr->lastid; // Last ID

        //exit(var_dump($arr));

        if ($bresult === false) {
            parent::setError(45435467);
        }

        // Create Channel Admin
        if ($bresult === true) {
            $fuserchannel = new \stdClass();
            $fuserchannel->idcha = $ID_CHA;
            $fuserchannel->iduser = $IDUSER;
            $bresult = $channel_utils->create_channel_admin($fuserchannel);

            if ($bresult === false) {
                parent::setError(99876546533522);
            }
        }

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('ChannelsCommon (DBException) >> create : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('ChannelsCommon >> create : ' . $e);
    } finally {
        if ($bresult === true) {
           if (null !== $data) {
               $data->commitTransaction();
           }
        } else {
           if (null !== $data) {
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
 * Subscribe channel
 * @param stdClass $form
 * @return array
 */
public function subscribe(\stdClass $form) : array {
   $bresult = false;
   $data = null;

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->UID) || ! \is_string($form->UID)) {
            return array('result' => false,'code' => 666);
        }

        // Format
        $uid = mb_substr(filter_var($form->UID, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH),0,50);
        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $data->startTransaction();
        // Get Channel ID
        $channel_utils = new \Moviao\Data\Util\ChannelsUtils($this);
        $channelID = $channel_utils->getChannelID($uid);

        if ($channelID <= 0) {
            return array('result' => false,'code' => 777);
        }
        // Data
        $fdata = new \Moviao\Data\Rad\ChannelsListData();
        $fdata->set_CHA($channelID);
        $fdata->set_USR(parent::getSession()->getIDUSER());
        $group_utils = new \Moviao\Data\Util\channelsUtils($this);
        $bresult = $group_utils->subscribe($fdata);

        if ($bresult === true) {
            $group_utils->updateSubscribersCounter($channelID);
        }

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('ChannelsCommon (DBException) >> subscribe : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('ChannelsCommon >> subscribe : '  . $e);
        parent::setError(566200);
    } finally {
        if ($bresult === true) {
            if (null !== $data) $data->commitTransaction();
        } else {
            if (null !== $data) $data->rollbackTransaction();
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
 * Unsubscribe
 * @param stdClass $form
 * @return array
 */
public function unsubscribe(\stdClass $form) : array {
    $bresult = false;
    $data = null;

    try {

        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->UID) || ! \is_string($form->UID)) {
            return array('result' => false,'code' => 666);
        }

        // Format
        $uid = mb_substr(filter_var($form->UID, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH),0,50);
        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $data->startTransaction();
        // Get Channel ID
        $channel_utils = new \Moviao\Data\Util\ChannelsUtils($this);
        $channelID = $channel_utils->getChannelID($uid);

        if ($channelID <= 0) {
            return array('result' => false,'code' => 777);
        }

        $fdata = new \Moviao\Data\Rad\ChannelsListData();
        $fdata->set_CHA((int)($channelID));
        $fdata->set_USR(parent::getSession()->getIDUSER());
        $group_utils = new \Moviao\Data\Util\channelsUtils($this);
        $bresult = $group_utils->updateChannelList($fdata, 0,1);

        if ($bresult === true) {
            $group_utils->updateSubscribersCounter($channelID);
        }

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('ChannelsCommon (DBException) >> unsubscribe : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('ChannelsCommon >> unsubscribe : ' . $e);
    } finally {
        if ($bresult === true) {
            if (null !== $data) $data->commitTransaction();
        } else {
            if (null !== $data) $data->rollbackTransaction();
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
 * Publish Channel
 * @param stdClass $form
 * @return array
 */
public function publish(\stdClass $form) : array {
    $bresult = false;
    $data = null;
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->UID) || ! \is_string($form->UID)) {
            return array('result' => false,'code' => 666);
        }

        $data = parent::getDBConn();
        $uid = mb_substr(filter_var($form->UID, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,50);

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $data->startTransaction();
        // Get Channel ID
        $channel_utils = new \Moviao\Data\Util\ChannelsUtils($this);
        $channelID = $channel_utils->getChannelID($uid);

        if ($channelID <= 0) {
            return array('result' => false,'code' => 777);
        }

        $formObj = new \stdClass();
        $formObj->IDCHA = $channelID;
        $formObj->IDUSER = parent::getSession()->getIDUSER();

        // Publish Channel
        $bresult = $channel_utils->publish($formObj);

        //exit(var_dump($bresult));


        //if ($bresult) {
        //    $bresult = $channel_utils->publish_location($formObj);
        //}
    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('ChannelsCommon (DBException) >> publish : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('ChannelsCommon >> publish : ' . $e);
    } finally {
        if ($bresult === true) {
            if (null !== $data) $data->commitTransaction();
        } else {
             if (null !== $data) $data->rollbackTransaction();
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
 * Modify Channel
 * @param stdClass $form
 * @return array
 */
public function modify_desc(\stdClass $form) : array {
    $bresult = false;
    $data = null;

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->UID) || ! \is_string($form->UID) || empty($form->TITLE) || empty($form->DESCL) || ! \is_string($form->TITLE) || ! \is_string($form->DESCL)) {
            return array('result' => false,'code' => 666);
        }

        $data = parent::getDBConn();
        $uid = mb_substr(filter_var($form->UID, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH),0,50);
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $data->startTransaction();
        // Get Channel ID
        $channel_utils = new \Moviao\Data\Util\ChannelsUtils($this);
        $channelID = $channel_utils->getChannelID($uid);
        if ($channelID < 1) return array('result' => false,'code' => 777);
        $formObj = new \stdClass();

        //---------------------------------------------------------------------------------------
        if (mb_strlen($form->TITLE) > 150) {
            $form->TITLE = mb_substr($form->TITLE, 0, 150);
        }
        $formObj->title = strip_tags(filter_var($form->TITLE, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        //---------------------------------------------------------------------------------------

        if (mb_strlen($form->DESCL) > 4000) {
            $form->DESCL = mb_substr($form->DESCL, 0, 4000);
        }

        // Filter balises HTML5
        $formObj->descl = strip_tags($form->DESCL, self::strip_html);

        // Filter html xss
        $filter = new \Moviao\Http\HTML_Sanitizer;
        $allowed_protocols = array('http');
        $allowed_tags = self::strip_html_array;
        $filter->addAllowedProtocols($allowed_protocols);
        $filter->addAllowedTags($allowed_tags);
        $formObj->descl = $filter->xss($formObj->descl);
        //---------------------------------------------------------------------------------------

        $formObj->idchannel = $channelID;
        $formObj->iduser = parent::getSession()->getIDUSER();
        // Modify Channel
        $bresult = $channel_utils->modify_channel_desc($formObj);

    } catch (\Moviao\Database\Exception\DBException $dbex) {
        $bresult = false;
        error_log('ChannelsCommon (DBException) >> modify_desc : $dbex');
    } catch (\Error $e) {
        $bresult = false;
        error_log('ChannelsCommon >> modify_desc : ' . $e);
    } finally {
        if ($bresult === true) {
            if (null !== $data) $data->commitTransaction();
        } else {
            if (null !==  $data) $data->rollbackTransaction();
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
 * show
 * @param stdClass $form
 * @return array
 */
public function show(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;
    $return_data = array();

    try {

        if (empty($form) || empty($form->UID) || ! \is_string($form->UID)) {
            return array('result' => false,'code' => 666);
        }

        parent::getSession()->startSession();
        $uid = mb_substr(strip_tags(filter_var($form->UID,FILTER_SANITIZE_FULL_SPECIAL_CHARS)),0,50);
        $data = parent::getDBConn();
        
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $channel_utils = new \Moviao\Data\Util\ChannelsUtils($this);
        // Get Channel ID
        $channelID = $channel_utils->getChannelID($uid);

        if ($channelID <= 0) {
            return $return_data;
        }

        $fdata = new \Moviao\Data\Rad\ChannelsListData();
        $fdata->set_CHA($channelID);
        $fdata->set_USR(parent::getSession()->getIDUSER());
        $return_data = $channel_utils->show($fdata);
        $bresult = true;
    } catch (\Error $e) {
        $bresult = false;
        $return_data = array();
        error_log('ChannelsCommon >> show : ' . $e);
    }

    if ($bresult === true) {
        $array = array('result' => $bresult,'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}

/**
 * Display Latest Channels
 * @param stdClass $form
 * @return array
 * @throws \Moviao\Database\Exception\DBException
 */
public function showLatest(\stdClass $form) : array {
    
    $return_data = array();
    $bresult = false;

    try {

        //exit(var_dump($form));    

        //parent::getSession()->Authorize();
        if (empty($form) || ! isset($form->O) || ! is_numeric($form->O)) {
            return array('result' => false,'code' => 666);
        }

        parent::getSession()->startSession();

        $offset = intval(filter_var($form->O, FILTER_SANITIZE_NUMBER_INT));

        if (empty($offset)) {
            $offset = 0;
        }

        // Suffix Website .ES, .BE, etc ...
        //$http_utils = new \Moviao\Http\ServerInfo();
        //$suffix = $http_utils->getServerSuffix();

        $params = new \stdClass();
        $params->limit  = 9;
        $params->query = null;
        
        if (isset($form->Q)) {
            $params->query  = mb_trim(filter_var(strip_tags($form->Q), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        }

        $offset *= $params->limit; // Calcul offset
        $params->offset = $offset;
        $params->IDUSER = parent::getSession()->getIDUSER();

        // Tag Category
//        if (isset($form->t) && empty($form->t) === false && is_numeric($form->t)) {
//            //exit(var_dump($form->t));
//            $arr = ['1003','1014','1001','1016','1012'];
//            $tag = (int)($form->t);
//            if (in_array($tag, $arr)) {
//                $params->tag = $tag;
//            }
//        }

//        if (isset($form->lat) && isset($form->lng) && (!empty($form->lng)) && (!empty($form->lat)) && (is_numeric($form->lng)) && (is_numeric($form->lat))) {
//            $params->lat = $form->lat;
//            $params->lng = $form->lng;
//            parent::getSession()->setLatitude($form->lat);
//            parent::getSession()->setLongitud($form->lng);
//        } else {
//            $lat = parent::getSession()->getLatitude();
//            $lng = parent::getSession()->getLongitud();
//            if (empty($lat) || empty($lng) || empty($lat) || empty($lng)) {
//                // Need a location to send a response
//                $array = array('result' => $bresult,'data' => $return_data);
//                return $array;
//            }
//            $params->lat = $lat;
//            $params->lng = $lng;
//        }

//        if ($suffix == 'ES') {
//            $f->COUNTRY = 62; // Spain
//        } else if ($suffix == 'BE') {
//            $f->COUNTRY = 18; // Belgique
//        } else if ($suffix == 'FR') {
//            $f->COUNTRY = 69; // France
//        } else {
//            $f->COUNTRY = 62; // Spain by default
//        }

        $data = parent::getDBConn();

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $utils = new \Moviao\Data\Util\ChannelsUtils($this);
        $return_data = $utils->showChannels($params);
        $bresult = true;
    } catch (\Error $e) {
        $bresult = false;
        error_log('ChannelsCommon >> showLatest : ' . $e);
    }

    if ($bresult === true) {
        $array = array('result' => $bresult,'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}

/**
 * Get Channels
 * @param stdClass $form
 * @return array
 */
public function getChannels(\stdClass $form) : array {
    $bresult = false;
    $return_data = array();

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || ! isset($form->O) || !is_numeric($form->O)) {
            return array('result' => false,'code' => 666);
        }

        $iduser = parent::getSession()->getIDUSER();
        $params = new stdClass();
        $params->limit  = 9;
        // Offset
        $offset =  intval(filter_var($form->O, FILTER_SANITIZE_NUMBER_INT));
        $offset *= $params->limit; // Calcul offset
        $params->offset = $offset;
        $params->iduser = $iduser;
        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $channel_utils = new \Moviao\Data\Util\ChannelsUtils($this);
        $return_data = $channel_utils->getChannels($params);
        $bresult = true;
    } catch (\Error $e) {
        $bresult = false;
        error_log('ChannelsCommon >> getChannels : ' . $e);
    }

    if ($bresult === true) {
        $array = array('result' => $bresult, 'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}

/**
 * Get Channel for Combo
 * @return array
 */
public function getChannelsCombo() : array {
    $bresult = false;
    $return_data = array();

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();
        $data = parent::getDBConn();
        $iduser = parent::getSession()->getIDUSER();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $channel_utils = new \Moviao\Data\Util\ChannelsUtils($this);
        $return_data = $channel_utils->getChannelsCombo($iduser);
        $bresult = true;
    } catch (\Error $e) {
        $bresult = false;
        error_log('ChannelsCommon >> getChannelsCombo : ' . $e);
    }

    if ($bresult === true) {
        $array = array('result' => $bresult, 'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}

}