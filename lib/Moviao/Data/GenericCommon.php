<?php
declare(strict_types=1);

namespace Moviao\Data;
use Moviao\Data\CommonData;
use stdClass;

class GenericCommon extends CommonData {       

public function __construct() {}

/**
 * Get all tags with language selection
 * @param stdClass $form
 * @return array
 */
public function getTags(\stdClass $form) : array {
    $bresult = false;
    $return_data = array();
    try {
        parent::getSession()->startSession();
        //parent::getSession()->Authorize();
        if (empty($form) || ! isset($form->L)) {
            return array("result" => false,"code" => 666);
        }
        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $generic_utils = new \Moviao\Data\Util\GenericUtils($this);
        $term = mb_substr(filter_var($form->L, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH),0,2);
        if ($term !== 'fr' && $term !== 'es' && $term !== 'en') {
            $term = 'en';
        }
        $return_data = $generic_utils->getTags($term);
        $bresult = true;
    } catch (\Error $ex) {
        $bresult = false;
        error_log("GenericCommon >> getTags : $ex");
    }
    if ($bresult === true) {
        $array = array("result" => $bresult, "data" => $return_data);
    } else {
        $array = array("result" => $bresult,"code" => parent::getError());
    }
    return $array;
}

public function searchTags(\stdClass $form) : array {
    $bresult = false;
    $return_data = array();
    try {
        parent::getSession()->startSession();
        //parent::getSession()->Authorize();
        if (empty($form) || (!isset($form->q))) {
            return array("result" => false,"code" => 666);
        }
        $query = filter_var($form->q, FILTER_SANITIZE_STRING);
        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $generic_utils = new \Moviao\Data\Util\GenericUtils($this);
        //$term = substr(filter_var($form->L, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH),0,2);
        //if ($term != 'fr' || $term != 'es') {
        $term = 'fr';
        //}
        $return_data = $generic_utils->searchTags($term,$query);
        $bresult = true;
    } catch (\Error $ex) {
        $bresult = false;
        error_log("GenericCommon >> getTags : $ex");
    }
    return $return_data;
}

public function getCountries() : array {
    $bresult = false;

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();
        $return_data = array();

        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $generic_utils = new \Moviao\Data\Util\GenericUtils($this);

        $return_data = $generic_utils->loadCountries(); 
        if (count($return_data)>0) {
            $bresult = true;
        }
    } catch (\Error $ex) {
        $bresult = false;
        error_log("GenericCommon >> getCountries : $ex");
    }     
        
    if ($bresult === true) {
        $array = array("result" => $bresult,"data" => $return_data);
    } else {
        $array = array("result" => $bresult,"code" => parent::getError());
    }    
    return $array;       
}

// GeoLocation : method a verifier
//public function detectCountry() : array {
//    $bresult = false;
//    $code_country = "";
//
//    $ip = $_SERVER["REMOTE_ADDR"];
//    $geoloc = new \GeoIP\GeoLocation();
//    $geoloc->initDB();
//    $code_country = $geoloc->getCountryFromIP($ip);
//    // TODO:  To change
//    if (strlen($code_country) <= 0) {
//        $code_country = "ES";
//    }
//    $bresult = true;
//
//    if ($bresult) {
//        $array = array("result" => $bresult, "data" => $code_country);
//    } else {
//        $array = array("result" => $bresult,"code" => parent::getError());
//    }
//
//    return $array;
//}

//public function publishFeed(\stdClass $form) : array {
//    $bresult = false;
//    $data = null;
//
//    try {
//        parent::getSession()->Authorize();
//        if (is_null($form) || (!isset($form->MSG)) || (strlen($form->MSG) <=0)) {
//            return array("result" => 666);
//        }
//        $data = parent::getDBConn();
//                if (! $data->connectDBA()) {
//            return array('result' => false,'code' => 888);
//        }
//        $data->startTransaction();
//        $f = new stdClass();
//        $f->IDCOMLNK = NULL; // Link
//        $f->IDLNKTYP = 1; // Type Feed
//        $f->USR = parent::getSession()->getIDUSER();
//        $f->MSG = substr(filter_var(strip_tags($form->MSG), FILTER_SANITIZE_STRING),0, 250);
//        $f->ACTIVE = 1;
//        if (isset($form->IMG) && (!is_null($form->IMG)) && strlen($form->IMG)>0){
//            $f->IMG = filter_var($form->IMG, FILTER_SANITIZE_URL);
//        }
//        $generic = new Util\GenericUtils($this);
//        $bresult = $generic->createFeed($f);
//
//    } catch (\Moviao\Database\Exception\DBException $dbex) {
//        $bresult = false;
//        error_log("GenericCommon (DBException) >> publishFeed : $dbex");
//    } catch (\Error $ex) {
//        $bresult = false;
//        error_log("GenericCommon >> publishFeed : $ex");
//    } finally {
//        if ($bresult === true) {
//            if (!is_null($data)) $data->commitTransaction();
//        } else {
//            if (!is_null($data)) $data->rollbackTransaction();
//        }
//    }
//
//    if ($bresult === true) {
//        $array = array("result" => $bresult);
//    } else {
//        $array = array("result" => $bresult,"code" => parent::getError());
//    }
//
//    return $array;
//}

//public function readFeeds(\stdClass $form) : array {
//    $bresult = false;
//    $return_data = array();
//    $onlyUser = true;
//
//    try {
//        parent::getSession()->Authorize();
//        if ((! isset($form)) || (! property_exists($form,'uid'))) {
//            return array("result" => false,"code" => 666);
//        }
//        $uid = $form->uid;
//        $iduser_uid = NULL;
//        $data = parent::getDBConn();
//                if (! $data->connectDBA()) {
//            return array('result' => false,'code' => 888);
//        }
//        if (!is_null($uid)) {
//            $uuid_check = \Moviao\Security\UUID::is_valid($uid);
//            $options = array("options"=>array("regexp"=>"/^[A-Za-z][A-Za-z0-9_]{5,14}$/"));
//            if ((filter_var($uid, FILTER_VALIDATE_REGEXP, $options) === false) && $uuid_check === false) {
//                return array("result" => false,"code" => 667);
//            }
//            $user_utils = new \Moviao\Data\Util\UsersUtils($this);
//            $iduser_uid = $user_utils->getUserIDFromUUID($uid);
//        } else {
//            $onlyUser = false;
//        }
//        $f = new stdClass();
//        if (is_null($uid)) {
//            $f->iduser = parent::getSession()->getIDUSER();
//        } else {
//            $f->iduser = $iduser_uid;
//        }
//        $f->restrictUserOnly = $onlyUser;
//
//        $generic_utils = new \Moviao\Data\Util\GenericUtils($this);
//        $return_data = $generic_utils->readFeeds($f);
//        $bresult = true;
//    } catch (\Error $ex) {
//        $bresult = false;
//        error_log("GenericCommon >> readFeeds : $ex");
//    }
//    if ($bresult === true) {
//        $array = array("result" => $bresult, "data" => $return_data);
//    } else {
//        $array = array("result" => $bresult,"code" => parent::getError());
//    }
//    return $array;
//}
}