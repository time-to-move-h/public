<?php

namespace Moviao\Data;
use Moviao\Data\CommonData;
//use Moviao\Data\Util\EmailUtils;

class CustomerCommon extends CommonData {       

public function __construct() {}

// sendMessage
//public function sendMessage($form) {
//    //exit(var_dump($form));
//    $result = false;
//    try {
//        $address = "djamil.hammouche@gmail.com";
//        $template = "../../app/view/mailing/contact_form.xml";
//        $email_utils = new EmailUtils();
//        $result = $email_utils->sendEmail($address, $template, $form);
//    } catch (\Error $ex) {
//        $result = FALSE;
//        error_log("CustomerCommon >> sendMessage : $ex");
//    }
//
//    return $result;
//}

//function detectCountry() {
//    $code_country = "";
//    try {
//        // GeoLocation ---------
//        $ip = $_SERVER["REMOTE_ADDR"];
//        $geoloc = new \GeoIP\GeoLocation();
//        $geoloc->initDB();
//        $code_country = $geoloc->getCountryFromIP($ip);
//        // TODO:  To change
//        if (strlen($code_country) <= 0) {
//            $code_country = "ES";
//        }
//    //----------------------
//    } catch (\Error $ex) {
//        error_log("CustomerCommon >> detectCountry : $ex");
//    }
//
//    return $code_country;
//}

}