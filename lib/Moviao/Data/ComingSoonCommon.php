<?php
declare(strict_types=1);
namespace Moviao\Data;
use Moviao\Data\CommonData;
use Moviao\Data\Util\EmailUtils;
use PDO;
use stdClass;

class ComingSoonCommon extends CommonData {

public function __construct() {}

// Login user
public function signup_invite(\stdClass $form) : array {
    $bresult = false;
    
    if (empty($form) || empty($form->MAIL) || mb_strlen($form->MAIL) < 5 || empty($form->CNT) || mb_strlen($form->CNT) <> 2) {
        parent::setError(301);
        return array("result" => $bresult,"code" => parent::getError());        
    }

    $data = null;  
    $params = [];     
    try {                
        $mail = strtolower(trim((substr($form->MAIL, 0, 350)))); // Length Limiter                
        if (! filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            parent::setError(302);
            return array("result" => $bresult,"code" => parent::getError()); 
        }

        // check country code
        $code_country = filter_var($form->CNT, FILTER_SANITIZE_STRING);
        $accepted_code_country = "/^(AF|AX|AL|DZ|AS|AD|AO|AI|AQ|AG|AR|AM|AW|AU|AT|AZ|BS|BH|BD|BB|BY|BE|BZ|BJ|BM|BT|BO|BQ|BA|BW|BV|BR|IO|BN|BG|BF|BI|KH|CM|CA|CV|KY|CF|TD|CL|CN|CX|CC|CO|KM|CG|CD|CK|CR|CI|HR|CU|CW|CY|CZ|DK|DJ|DM|DO|EC|EG|SV|GQ|ER|EE|ET|FK|FO|FJ|FI|FR|GF|PF|TF|GA|GM|GE|DE|GH|GI|GR|GL|GD|GP|GU|GT|GG|GN|GW|GY|HT|HM|VA|HN|HK|HU|IS|IN|ID|IR|IQ|IE|IM|IL|IT|JM|JP|JE|JO|KZ|KE|KI|KP|KR|KW|KG|LA|LV|LB|LS|LR|LY|LI|LT|LU|MO|MK|MG|MW|MY|MV|ML|MT|MH|MQ|MR|MU|YT|MX|FM|MD|MC|MN|ME|MS|MA|MZ|MM|NA|NR|NP|NL|NC|NZ|NI|NE|NG|NU|NF|MP|NO|OM|PK|PW|PS|PA|PG|PY|PE|PH|PN|PL|PT|PR|QA|RE|RO|RU|RW|BL|SH|KN|LC|MF|PM|VC|WS|SM|ST|SA|SN|RS|SC|SL|SG|SX|SK|SI|SB|SO|ZA|GS|SS|ES|LK|SD|SR|SJ|SZ|SE|CH|SY|TW|TJ|TZ|TH|TL|TG|TK|TO|TT|TN|TR|TM|TC|TV|UG|UA|AE|GB|US|UM|UY|UZ|VU|VE|VN|VG|VI|WF|EH|YE|ZM|ZW|ZZ)$/";
        if (! preg_match($accepted_code_country, $code_country)) {
            parent::setError(303);
            return array("result" => $bresult,"code" => parent::getError());
        }

        $data = parent::getDBConn();

        if (! $data->connectDBA()) {
            parent::setError(304);
            return array("result" => $bresult,"code" => parent::getError());
        }        
        $data->startTransaction();       
                               
        $strSql = 'SELECT 1 FROM comingsoon WHERE COM_MAIL = ? LIMIT 1;';
        $params = [[ "parameter" => 1, "value" => $mail, "type" => PDO::PARAM_STR ]];
        $row = $this->data->readLine($strSql, $params);

        if (is_null($row) || $row == false) {
            // Not exists
        } else { 
            parent::setError(307);        
            return array("result" => $bresult,"code" => parent::getError());
        }
        
        // Geo Location
        //$user_location = new Util\LocationUtils($this);
        //$code_country = $user_location->detectCountry();
        //$userlang = new \Moviao\Http\UserLanguage();
        //$lang_detected = substr($userlang->getFirstUserLanguage(),0,5);
        
        $array_lang = array("es-ES", "fr-BE", "en-GB");
        $userlanguage = new \Moviao\Http\UserLanguage("en-GB", $array_lang);
        $lang_detected = $userlanguage->parseLang();
                        
        // Insert Email
        $strSql = "INSERT INTO comingsoon (COM_MAIL,COM_CNT,COM_LANG,COM_IP,COM_USER_AGENT) VALUES (?,?,?,?,?);";
        $params = [
            [ "parameter" => 1, "value" => $mail, "type" => PDO::PARAM_STR ],
            [ "parameter" => 2, "value" => $code_country, "type" => PDO::PARAM_STR ],
            [ "parameter" => 3, "value" => $lang_detected, "type" => PDO::PARAM_STR ],
            [ "parameter" => 4, "value" => strip_tags($_SERVER['REMOTE_ADDR']), "type" => PDO::PARAM_STR ],
            [ "parameter" => 5, "value" => strip_tags($_SERVER['HTTP_USER_AGENT']), "type" => PDO::PARAM_STR ]
            ];
        $bresult = $this->data->executeNonQuery($strSql, $params);
               
        // Invitations code ----------------------------------------------------
        $INV_CODE = rand(10011, 99998);        
        $form2 = new \stdClass();
        $form2->ACCOUNT = $form->MAIL;
        $form2->CODE = $INV_CODE;
        $form2->DATINS = "";        
        $params = [ "ACCOUNT" => $form->MAIL,"CODE" => $INV_CODE ];        
        $invit = new \Moviao\Data\Rad\Invitations($this);
        $fdata = $invit->filterForm($form2);           
        $bresult = $invit->create($fdata);
        if ($bresult === false) {
            parent::setError(312);
        }

    } catch (\Error $e) {
        $bresult = false;
        error_log('ComingSoonCommon >> signup_invite : ' . $e);
        parent::setError(313);
    } finally {
         if ($bresult === true) {
           if (! empty($data)) $data->commitTransaction();
        } else { 
           if (! empty($data)) $data->rollbackTransaction();
        }
    }

    $server = new \Moviao\Http\ServerInfo();
    $suffix = $server->getServerSuffix();
    if ($suffix !== 'LOCALHOST') {
        $email_utils = new EmailUtils();

        if ($bresult === true) {
            try {
                $params = [];
                // Sendmail ------------------------------------------------------------
                $template = "./app/view/mailing/comingsoon_EN.xml";
                $email_utils->sendEmail($form->MAIL, $template, $params);
            } catch (\Error $e) {
                error_log('ComingSoonCommon >> signup_invite >> sendEmail : ' . $e);
                parent::setError(314);
            }
        }

        try {
            $params = [];
            $params['ACCOUNT'] = $form->MAIL;
            $address = "moviaonetwork@gmail.com";
            $template = "./app/view/mailing/comingsoon_internal.xml";
            $email_utils->sendEmail($address, $template, $params);
        } catch (\Error $e) {
            error_log('ComingSoonCommon >> signup_invite2 >> sendEmail : ' . $e);
            parent::setError(315);
        }
    }
    //----------------------------------------------------------------------
        
    if ($bresult === true) {
        $array = array("result" => $bresult);
    } else {
        $array = array("result" => $bresult,"code" => parent::getError());
    }
    
    return $array;    
}}