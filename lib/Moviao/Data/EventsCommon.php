<?php
declare(strict_types=1);
namespace Moviao\Data;
use Moviao\Data\CommonData;
use stdClass;

class EventsCommon extends CommonData {

//private const strip_html = '<h1><h2><h3><h4><h5><h6><strong><i><p><u><ul><ol><li><span><blockquote><a><hr><em><del><sub><sup><img><s><pre><iframe>';
//private const strip_html_array = array('h1','h2','h3','h4','h5','h6','p','u','blockquote','a','i','b','em','span','strong','ul','ol','li','hr','del','sub','sup','img','s','pre','iframe');

private const strip_html = '<h1><h2><h3><h4><h5><h6><strong><p><u><em><br>';
private const strip_html_array = array('h1','h2','h3','h4','h5','h6','p','br','u','em','strong');

public function __construct() {}

/**
 * Create a event
 * @param stdClass $form
 * @return array
 */
public function create_event_t1(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;
    $data = null;
    $urllink = null;
       
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();        

        //  || empty($form->COUNTRY_CODE) || empty($form->STATE) || empty($form->LAT) || empty($form->LON)
        // ISVENUE

        //exit(var_dump(ctype_digit($form->MAXUSE)));


        if (empty($form) || empty($form->TITLE) || ! \is_string($form->TITLE) || empty($form->DESCL) || ! \is_string($form->DESCL) || empty($form->TAGS) || ! \is_array($form->TAGS) || empty($form->ISONLINE) || ! ctype_digit($form->MAXUSE)) {
            return array('result' => false, 'code' => 666);
        }

        // Csrf Protection
        $csrf = new \Moviao\Security\CSRF_Protect();
        if (empty($form->_csrf) || $csrf->verifyRequest($form->_csrf) !== true) {
            return array('result' => false,'code' => 999);
        }

        $form->ISONLINE = (int) mb_substr(filter_var($form->ISONLINE, FILTER_SANITIZE_NUMBER_INT),0,1);

//        if ($form->ISONLINE === '1') {
//            $form->LAT = 55.33;
//            $form->LON = 10.25;
//            $form->rad = 30;
//        }

        //exit(var_dump($form->ISONLINE));

        // Event Type
        if ($this->getSession()->getAccountType() !== 99) {
            $form->EVTTYP = 1;
        } else {
            if ($form->EVTTYP !== '1' && $form->EVTTYP !== '2') {
                $form->EVTTYP = 1;
            } else {
                $form->EVTTYP = (int)$form->EVTTYP;
            }
        }

        if (isset($form->COUNTRY_CODE)) {
            $form->COUNTRY_CODE = mb_substr(filter_var($form->COUNTRY_CODE, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,3);
        } else {
            $form->COUNTRY_CODE = '';
        }

        //echo var_dump($form->EVTTYP);

        // All Day
        $allday = isset($form->ALLDAY) ? 1 : 0;

        // Visibility
        $form->EVTVIS = 1;        

        // Max Use
        $maxuse = filter_var($form->MAXUSE, FILTER_SANITIZE_NUMBER_INT);
        
        if (empty($maxuse)) {
            $maxuse = 20;
        } else if ($maxuse > 1000) {
            $maxuse = 1000;
        }

        $form->MAXUSE = $maxuse;
        
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $date1 = null;
        $date2 = null;

        // Check if Date Begin is superior > NOW()
        if (isset($form->DATBEG) && ($form->DATBEG !== null) && (mb_strlen($form->DATBEG) > 0)) {
            $id_timezone_begin = (int)$form->ZONEIDBEG_ID;
            if ($id_timezone_begin <= 0) {
                return array('result' => false, 'code' => 777);
            }
            $timezone = $event_utils->getTimeZone($id_timezone_begin);
            if ($timezone === null || empty($timezone)) {
                return array('result' => false, 'code' => 888);
            }
            $sdate = $form->DATBEG;                                      
            
            // All Day
            if ($allday === 1) {
//                if (isset($form->DATEND)) {
//                    $form->DATEND = null;
//                }
                $sdate = mb_substr($sdate,0,10);
                $sdate .= ' 00:00';                
            }
                        
            $dateformat = new \Moviao\Util\DateTimeFormat();
            $date1 = $dateformat->parseDateTime($sdate, $timezone);

            if ($dateformat->isInferiorNow($date1,$timezone, ($allday === 1))) {
                return array('result' => false, 'code' => 1112345345);
            }

            if ($date1 != null) {
                $date1->setTimezone(new \DateTimeZone("UTC")); // Store date in UTC
                $form->DATBEG = $date1->format('Y-m-d H:i:s'); // $sdate
            }

        } else {
            $form->DATBEG = null;
        }

        // Check if Date End is superior > NOW()
        if (! empty($form->DATEND)) {
            $id_timezone_end = (int)$form->ZONEIDEND_ID;
            if ($id_timezone_end <= 0) {
                return array('result' => false, 'code' => 777);
            }
            $timezone = $event_utils->getTimeZone($id_timezone_end);

            if ($timezone == null || strlen($timezone) <= 0) {
                return array('result' => false, 'code' => 888);
            }

            $sdate = $form->DATEND;                                        
            $dateformat = new \Moviao\Util\DateTimeFormat();
            $date2 = $dateformat->parseDateTime($sdate, $timezone);

            if ($dateformat->isInferiorNow($date2,$timezone,($allday === 1))) {
                return array('result' => false, 'code' => 1113);
            }

            if ($date2 != null) {
                $date2->setTimezone(new \DateTimeZone("UTC")); // Store date in UTC
                $form->DATEND = $date2->format('Y-m-d H:i:s'); // $sdate
            }

        } else {
            $form->DATEND = null;
        }

        // Check if date2 <  date1
        if ($date1 !== null && $date2 !== null) {
            if ($date2 < $date1) {
                return array('result' => false, 'code' => 543545415468787); 
            }
        }
        
        // Validacion URL
        // if (! empty($form->URL)) {
        //     $pos1 = strrpos($form->URL, 'http');
        //     if ($pos1 === false) {
        //        $form->URL = 'http://' . $form->URL;
        //     }
        //     if (filter_var($form->URL, FILTER_VALIDATE_URL) === false) {
        //         return array('result' => false, 'code' => 369842557854);                     
        //     }
        //     $form->URL = filter_var($form->URL, FILTER_SANITIZE_URL);
        // }   

        $form->URL = null;
        
        // Validation Tags
        if (count($form->TAGS) <= 0) {
            return array('result' => false, 'code' => 112157745454);
        }

        //---------------------------------------------------------------------------------------
        $form->TITLE = $this->sanitizeTitle($form->TITLE);
        $form->DESCL = $this->sanitizeDescription($form->DESCL);

        // // Filter balises HTML5
        // $form->DESCL = strip_tags($form->DESCL, self::strip_html);
        // // Filter html xss
        // $filter = new \Moviao\Http\HTML_Sanitizer;
        // //$allowed_protocols = array('http');
        // $allowed_tags = self::strip_html_array;
        // //$filter->addAllowedProtocols($allowed_protocols);
        // $filter->addAllowedTags($allowed_tags);
        // $form->DESCL = $filter->xss($form->DESCL);

        //exit(var_dump($form->DESCL));
        //---------------------------------------------------------------------------------------

        //exit(var_dump($locationp));        
        $data = parent::getDBConn();
        $IDUSER = parent::getSession()->getIDUSER();       
      
        //--------------------------------------------------------------------------     
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();

        // Country
        $generic_utils = new \Moviao\Data\Util\GenericUtils($this);

        if (! empty($form->COUNTRY_CODE)) {
            $form->COUNTRY_CODE = $generic_utils->getCountryID($form->COUNTRY_CODE);
        } else {
            $form->COUNTRY_CODE = null;
        }

        // Get Channel ID
        $channelID = null;
        if (isset($form->CHANNEL) && empty($form->CHANNEL) === false) {
            $uid = strip_tags(filter_var($form->CHANNEL, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $channel_utils = new \Moviao\Data\Util\ChannelsUtils($this);
            $channelID = $channel_utils->getChannelID($uid);                
        }
        $form->CHANNELID = $channelID;         
        //--------------------------------------------------------------------------  


        //exit(var_dump($form));

        // Create Event
        $arr = $event_utils->create($form);
        $bresult = $arr->result;
        $ID_EVT = $arr->lastid; // Last ID
        $urllink = $arr->urllink;

        if ($bresult === false) {
            parent::setError(5016445412121547);
        }
        
        // Create Event List
//        if ($bresult === true) {
//            $fuserevent = new \stdClass();
//            $fuserevent->idevent = $ID_EVT;
//            $fuserevent->iduser = $IDUSER;
//            $bresult = $event_utils->create_event_user($fuserevent);
//            if ($bresult === false) {
//                parent::setError(502);
//            }
//        }

        // Create Event Admin
        if ($bresult === true) {
            $fuserevent = new \stdClass();
            $fuserevent->idevent = $ID_EVT;
            $fuserevent->iduser = $IDUSER;
            $bresult = $event_utils->create_event_admin($fuserevent);
            if ($bresult === false) {
                parent::setError(502);
            }
        }

        // Create Event Dates
        if ($bresult === true) {

            $fdates_event = new \stdClass();
            $fdates_event->idevent = $ID_EVT;
            $fdates_event->iduser = $IDUSER;
            $fdates_event->datbeg = $form->DATBEG;
            $fdates_event->datend = $form->DATEND;
            $fdates_event->zoneidbeg = (int) $form->ZONEIDBEG_ID;
            $fdates_event->zoneidend = (int) $form->ZONEIDEND_ID;
            $fdates_event->allday = $allday;

            $token = null;
            while(true) {
                $a = random_int(4590, PHP_INT_MAX);
                $b = random_int(9487, PHP_INT_MAX);
                $c = random_int(1203, PHP_INT_MAX);
                $d = new \DateTime();
                $token = substr($a . $b . $c . $d->getTimestamp() , 0, 20);

                $isCodeExist = $event_utils->isTokenEventDateExist($token);
                if (! $isCodeExist) {
                    break;
                }
            }

            //$token =
            $fdates_event->token = $token;

            $fdates = array();
            $fdates[] = $fdates_event;

            //exit(var_dump($fdates));

            $bresult = $event_utils->create_event_dates($fdates);
            if ($bresult === false) {
                parent::setError(503343493);
            }
        }

        // Create Tags List
        if ($bresult === true) {
            if (empty($form->TAGS) === false && \is_array($form->TAGS) === true) {
                $ftagsevent = new \stdClass();
                $ftagsevent->idevent = $ID_EVT;
                $ftagsevent->tags = $form->TAGS;
                $bresult = $event_utils->create_event_tags($ftagsevent);
                if (! $bresult) parent::setError(503);                    
            }             
        }                          
           
    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false; 
        error_log('EventsCommon (DBException) >> create_event_t1 : ' . $e);
    } catch (\Error $e) {
        $bresult = false; 
        error_log('EventsCommon >> create_event_t1 : ' . $e);
    } finally {           
        if ($bresult === true) {
            if ($data !== null) $data->commitTransaction();
        } else { 
            if ($data !== null) $data->rollbackTransaction();
        }    
    }
               
    if ($bresult === true) {
        $array = array('result' => $bresult, 'urllink' => $urllink);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    
    return $array;    
}

/**
 * Attend Event
 * @param stdClass $form
 * @return array
 */
public function attend(\stdClass $form) : array {
    $bresult = false;
    $status = -1;
    $data = null;
    
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->UID) || ! is_string($form->UID) || ! is_string($form->DATBEG)) {
            return array('result' => false,'code' => 666);
        }

        $uid = mb_substr(filter_var($form->UID,FILTER_UNSAFE_RAW),0,60);
        $datbeg = mb_substr(filter_var($form->DATBEG,FILTER_UNSAFE_RAW),0,19);
        
        //exit(var_dump($datbeg));

        $data = parent::getDBConn();

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();        
        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);
        
        if ($eventID <= 0) {
            return array('result' => false,'code' => 777);    
        }


        $isFreeSpace = $event_utils->isEventAttendFreeSpace($eventID, $datbeg);

        //exit(var_dump($datbeg));

        if (! $isFreeSpace) {
            return array('result' => false,'code' => 1954043402);
        }

        $fdata = new \Moviao\Data\Rad\EventsListData();   
        $fdata->set_EVT($eventID);
        $fdata->set_USR(parent::getSession()->getIDUSER());
        $fdata->set_DATBEG($datbeg);

        $event_utils = new \Moviao\Data\Util\EventsUtils($this);            
        $bresult = $event_utils->subscribe($fdata); 
        
        if ($bresult === true) {
            if ($event_utils->isEventConfirm($fdata)) {
                $status = 0;
            } else {
                $status = 1;
            }
        }

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false; 
        error_log('EventsCommon (DBException) >> attend : ' . $e);
    } catch (\Error $e) {
        $bresult = false; 
        error_log('EventsCommon >> attend : ' . $e);
    } finally {
        if ($bresult === true) {
            if (null !== $data) $data->commitTransaction();
        } else { 
            if (null !== $data) $data->rollbackTransaction();
        }    
    }

    if ($bresult === true) {
        $array = array('result' => $bresult, 'status' => $status);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }    
    return $array;
}

/**
 * Unattend Event
 * @param stdClass $form
 * @return array
 */
public function unattend(\stdClass $form) : array {
    $bresult = false;
    $data = null;
            
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->UID) || ! is_string($form->UID) || ! is_string($form->DATBEG)) {
            return array('result' => false,'code' => 666);
        }

        $uid = mb_substr(filter_var($form->UID,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,60);
        $datbeg = mb_substr(filter_var($form->DATBEG,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,19);

        $data = parent::getDBConn();

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();    
        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);

        if ($eventID <= 0) {
            return array('result' => false,'code' => 777);
        }

        $fdata = new \Moviao\Data\Rad\EventsListData();   
        $fdata->set_EVT($eventID);
        $fdata->set_USR(parent::getSession()->getIDUSER());    
        $fdata->set_DATBEG($datbeg);

        $event_utils = new \Moviao\Data\Util\EventsUtils($this);        
        $bresult = $event_utils->updateEventList($fdata, 'C',0,1);
        
    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false; 
        error_log('EventsCommon (DBException) >> unattend : ' . $e);
    } catch (\Error $e) {
        $bresult = false; 
        error_log('EventsCommon >> unattend : ' . $e);
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
 * Publish Event
 * @param stdClass $form
 * @return array
 */
public function publish(\stdClass $form) : array { 
    $bresult = false;
    $data = null;
    
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();
        if (empty($form) || (empty($form->UID)) || ! is_string($form->UID)) {
            return array('result' => false,'code' => 666);
        }

        $data = parent::getDBConn();
        // Format
        $uid = mb_substr(filter_var($form->UID, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH),0,60);
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $data->startTransaction();       
        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);
        if ($eventID <= 0) return array('result' => false,'code' => 777);                 
        $formObj = new \stdClass();
        $formObj->IDEVT = $eventID;
        $formObj->IDUSER = (int)parent::getSession()->getIDUSER();
        // Publish Channel                
        $bresult = $event_utils->publish($formObj);   
        
    } catch (\Moviao\Database\Exception\DBException $dbex) {
        $bresult = false; 
        error_log('EventsCommon (DBException) >> publish : $dbex');
    } catch (\Error $ex) {
        $bresult = false; 
        error_log('EventsCommon >> publish : $ex');        
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
 * Cancel Event
 * @param stdClass $form
 * @return array
 */
public function cancel_event(\stdClass $form) : array {
    $bresult = false;
    $data = null;
    
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->UID) || ! is_string($form->UID) || empty($form->DATBEG) || ! is_string($form->DATBEG)) {
            return array('result' => false,'code' => 666);
        }          
        $data = parent::getDBConn();
        // Format
        $uid = mb_substr(filter_var($form->UID, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH),0,60);

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        
        $data->startTransaction();       
        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);

        if ($eventID <= 0) {
            return array('result' => false,'code' => 777);                 
        }

        $formObj = new \stdClass();
        $formObj->idevent = $eventID;
        $formObj->iduser = parent::getSession()->getIDUSER();                
        $bresult = $event_utils->cancel_event($formObj);          

        $event_utils->cancel_event_tags($formObj); 
        $event_utils->check_event_admin($formObj);
        $event_utils->cancel_event_user($formObj);
        
    } catch (\Moviao\Database\Exception\DBException $dbex) {
        $bresult = false; 
        error_log('EventsCommon (DBException) >> cancel_event : ' . $dbex);
    } catch (\Throwable $e) {
        $bresult = false; 
        error_log('EventsCommon >> cancel_event : ' . $e); 
    } finally {        
        if ($bresult) {
            if (!empty($data)) $data->commitTransaction();
        } else { 
            if (!empty($data)) $data->rollbackTransaction();
        }
    } 
        
    if ($bresult) {
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    
    return $array;     
}

/**
 * Modify Desc
 * @param stdClass $form
 * @return array
 */
public function modify_event_desc(\stdClass $form) : array {
    $bresult = false;
    $data = null;

    //exit(var_dump($form));
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();
        if (empty($form) || empty($form->TITLE) || empty($form->DESCL) || ! is_string($form->TITLE) || ! is_string($form->DESCL) || empty($form->UID) || ! is_string($form->UID)) {
            return array('result' => false, 'code' => 666);
        }

        $csrf = new \Moviao\Security\CSRF_Protect();
        if (empty($form->_csrf) || $csrf->verifyRequest($form->_csrf) !== true) {
            return array('result' => false,'code' => 999);
        }

        $data = parent::getDBConn();

        // Format
        if (mb_strlen($form->DESCL) > 4000) {
            $form->DESCL = mb_substr($form->DESCL, 0, 4000);
        }

        //$url = (! empty($form->URL)) ? mb_substr(filter_var(trim($form->URL), FILTER_SANITIZE_URL),0,150) : null;
        $uid = mb_substr(strip_tags(trim($form->UID)),0,60);
        $url = null; //mb_substr(strip_tags(filter_var(trim($form->URL), FILTER_SANITIZE_URL)),0,150);


        $filtered_title = $this->sanitizeTitle($form->TITLE);
        $filtered_descl = $this->sanitizeDescription($form->DESCL);

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();

        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);

        if ($eventID <= 0) {
            return array('result' => false,'code' => 777);
        }

        $formObj = new \stdClass();
        $formObj->title = $filtered_title;
        $formObj->descl = $filtered_descl;
        $formObj->url = $url;
        $formObj->idevent = $eventID;
        $formObj->iduser = parent::getSession()->getIDUSER();                
        $bresult = $event_utils->modifyEvent_Desc($formObj);
        //exit(var_dump($bresult));
        
    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false; 
        error_log('EventsCommon (DBException) >> modify_event_desc : ' . $e);
    } catch (\Error $e) {
        $bresult = false; 
        error_log('EventsCommon >> modify_event_desc : ' . $e);
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
 * Modify Dates
 * @param stdClass $form
 * @return array
 */
public function modify_event_date(\stdClass $form) : array {
    $bresult = false;
    $data = null;

    try {

        if (empty($form) || empty($form->UID) || ! is_string($form->UID) || empty($form->DATBEG) || ! isset($form->DATEND)) {
            return array('result' => false,'code' => 666);
        }

        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        $data = parent::getDBConn();
        // Format
        $allday = isset($form->ALLDAY) ? 1 : 0;
        $uid = mb_substr(filter_var($form->UID, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH),0,60);

        $event_utils = new \Moviao\Data\Util\EventsUtils($this);

        $date1 = null;
        $date2 = null;

        $id_timezone_begin = null;
        $id_timezone_end = null;

        // Check if Date Begin is superior > NOW()
        if (isset($form->DATBEG) && ($form->DATBEG !== null) && (mb_strlen($form->DATBEG) > 0)) {
            $id_timezone_begin = (int) $form->ZONEIDBEG_ID;
            if ($id_timezone_begin <= 0) {
                return array('result' => false, 'code' => 777);
            }
            $timezone = $event_utils->getTimeZone($id_timezone_begin);
            if ($timezone === null || empty($timezone)) {
                return array('result' => false, 'code' => 888);
            }
            $sdate = $form->DATBEG;

            // All Day
            if ($allday === 1) {
//                if (isset($form->DATEND)) {
//                    $form->DATEND = null;
//                }
                $sdate = mb_substr($sdate,0,10);
                $sdate .= ' 00:00';
            }

            $dateformat = new \Moviao\Util\DateTimeFormat();
            $date1 = $dateformat->parseDateTime($sdate, $timezone);

            //exit(var_dump($allday));

            if ($dateformat->isInferiorNow($date1,$timezone,($allday === 1))) {
                return array('result' => false, 'code' => 1112);
            }

            if ($date1 != null) {
                $date1->setTimezone(new \DateTimeZone("UTC")); // Store date in UTC
                $form->DATBEG = $date1->format('Y-m-d H:i:s'); // $sdate
            }

        } else {
            $form->DATBEG = null;
        }

        // Check if Date End is superior > NOW()
        if (! empty($form->DATEND)) {
            $id_timezone_end = (int)$form->ZONEIDEND_ID;
            if ($id_timezone_end <= 0) {
                return array('result' => false, 'code' => 777);
            }
            $timezone = $event_utils->getTimeZone($id_timezone_end);

            if ($timezone == null || strlen($timezone) <= 0) {
                return array('result' => false, 'code' => 888);
            }

            $sdate = $form->DATEND;
            $dateformat = new \Moviao\Util\DateTimeFormat();
            $date2 = $dateformat->parseDateTime($sdate, $timezone);

            if ($dateformat->isInferiorNow($date2,$timezone,($allday === 1))) {
                return array('result' => false, 'code' => 1113);
            }

            if ($date2 != null) {
                $date2->setTimezone(new \DateTimeZone("UTC")); // Store date in UTC
                $form->DATEND = $date2->format('Y-m-d H:i:s'); // $sdate
            }

        } else {
            $form->DATEND = null;
        }

        $datebeg = empty($form->DATBEG) ? null : mb_substr(filter_var($form->DATBEG, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,16);
        $dateend = empty($form->DATEND) ? null : mb_substr(filter_var($form->DATEND, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,16);

        if (isset($form->ALLDAY)) {
            $allday  = 1;
            $datebeg = empty($datebeg) ? null : mb_substr($datebeg,0,10);
            $dateend = empty($dateend) ? null : mb_substr($dateend,0,10);
        }

        // Check if date2 <  date1
        if ($date1 !== null && $date2 !== null) {
            if ($date2 < $date1) {
                return array('result' => false, 'code' => 543545415468787);
            }
        }

        if (is_null($datebeg) && is_null($dateend)) {
            return array('result' => false, 'code' => 450394565468787);
        }

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();       
        
        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);

        if ($eventID <= 0) {
            return array('result' => false,'code' => 777);                 
        }

        $formObj = new \stdClass();
        $formObj->datebeg = $datebeg;
        $formObj->dateend = $dateend;
        $formObj->zoneidbeg = $id_timezone_begin;
        $formObj->zoneidend = $id_timezone_end;
        $formObj->allday = $allday;
        $formObj->idevent = $eventID;
        $formObj->iduser = parent::getSession()->getIDUSER();                
        $bresult = $event_utils->modifyEvent_Date($formObj);   
        
    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false; 
        error_log('EventsCommon (DBException) >> modify_event_date : ' . $e);
    } catch (\Throwable $e) {
        $bresult = false; 
        error_log('EventsCommon >> modify_event_date : ' . $e);
    } finally {
        if (null !== $data) {
            if ($bresult === true) {        
                $data->commitTransaction();        
            } else {             
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
 * Modify Venue
 * @param stdClass $form
 * @return array
 */
public function modify_event_venue(\stdClass $form) : array {
    $bresult = false;
    $data = null;    
    //exit(var_dump($form));
    
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();
        if (empty($form) || (!isset($form->UID)) || ! is_string($form->UID) || (!isset($form->CITY)) || (!isset($form->COUNTRY)) || (!isset($form->COUNTRY_CODE)) || (!isset($form->LAT)) || (!isset($form->LON)) || (!isset($form->OSMID)) || (!isset($form->PCODE)) || (!isset($form->STATE)) || (!isset($form->STREET)) || (!isset($form->STREET2)) || (!isset($form->VENUE))) {  // || (!isset($form->STREETN))
            return array('result' => false,'code' => 666);
        }          
        $data = parent::getDBConn();
        // Format
        $allday = 0;
        $uid = mb_substr(filter_var($form->UID, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH),0,60);
        //$datebeg = mb_substr(filter_var($form->DATBEG, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,20);
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $data->startTransaction();       
        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);
        if ($eventID <= 0) return array('result' => false,'code' => 777);                 
        $formObj = new \stdClass();
        $formObj->city = mb_substr(filter_var($form->CITY, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,250);
        $formObj->country = mb_substr(filter_var($form->COUNTRY, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,50);
        $formObj->country_code = mb_substr(filter_var($form->COUNTRY_CODE, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,3);
        $formObj->lat = mb_substr(filter_var($form->LAT, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,7);
        $formObj->lon = mb_substr(filter_var($form->LON, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,7);
        $formObj->osmid = mb_substr(filter_var($form->OSMID, FILTER_SANITIZE_NUMBER_INT),0,7);
        $formObj->pcode = mb_substr(filter_var($form->PCODE, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,35);
        $formObj->state = mb_substr(filter_var($form->STATE, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,250);
        $formObj->street = mb_substr(filter_var($form->STREET, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,250);
        $formObj->street2 = mb_substr(filter_var($form->STREET2, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,250);
        $formObj->streetn = mb_substr(filter_var($form->STREETN, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,5);
        $formObj->venue = mb_substr(filter_var($form->VENUE, FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,250);
        $formObj->idevent = $eventID;
        $formObj->iduser = parent::getSession()->getIDUSER();                
        $bresult = $event_utils->modifyEvent_Venue($formObj);   
        
    } catch (\Moviao\Database\Exception\DBException $dbex) {
        $bresult = false; 
        error_log('EventsCommon (DBException) >> modify_event_venue : $dbex');
    } catch (\Error $ex) {
        $bresult = false; 
        error_log('EventsCommon >> modify_event_venue : $ex');
    } finally {
        if ($bresult) {
            if (!empty($data)) $data->commitTransaction();
        } else { 
            if (!empty($data)) $data->rollbackTransaction();
        }
    } 
        
    if ($bresult) {
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    
    return $array;     
}

/**
 * Modify Date
 * @param stdClass $form
 * @return array
 */
public function modify_event_tags(\stdClass $form) : array {
    $bresult = false;
    $data = null;
    
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();
        if (empty($form) || (!isset($form->UID)) || ! is_string($form->UID) || (!isset($form->TAGS)) || (! is_array($form->TAGS))) {
            return array('result' => false,'code' => 666);
        }          
        $data = parent::getDBConn();                
        $uid = mb_substr(filter_var($form->UID, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH),0,60);
        $tags_arr = $form->TAGS;

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();

        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);

        if ($eventID <= 0) {
            return array('result' => false,'code' => 777);
        }
        
        $formObj = new \stdClass();
        $formObj->idtag = $tags_arr;        
        $formObj->idevent = $eventID;
        $formObj->iduser = parent::getSession()->getIDUSER();                                
        $arr = $event_utils->getTagsEvent($formObj);
        
        // Tags To Delete
        $tags_todelete = array();                
        foreach ($arr as $value) {
            $bfound = false;
            foreach ($tags_arr as $value2) {
                if ($value === (int)$value2) {
                    $bfound = true;     
                    break;
                }
            }            
            if ($bfound === false) {
                $tags_todelete[] = $value;
            }
        }
        
        // Tags To Create or Modify        
        $tags_tocreate = array();                
        foreach ($tags_arr as $value) {     
            $bfound = false;
            foreach ($arr as $value2) {               
                if (((int)$value) === $value2) {
                    $bfound = true;     
                    break;
                }
            }            
            if ($bfound === false) {               
                $tags_tocreate[] = $value;
            }
        }
       
        if ((count($tags_todelete) > 0) || (count($tags_tocreate) > 0) ) {
            $bresult = true;
        }
                
        // Delete Tags
        if (count($tags_todelete) > 0) {            
            foreach ($tags_todelete as $value) {                
                $formObj->idtag = (int)$value;
                if ($bresult) $bresult = $event_utils->desactivate_event_tags($formObj);
            }
        }
                
        if (count($tags_tocreate) > 0) { 
            $fcreate = new stdClass();            
            $fcreate->tags = $tags_tocreate;        
            $fcreate->idevent = $eventID;
            $fcreate->iduser = parent::getSession()->getIDUSER(); 
            if ($bresult) $bresult = $event_utils->create_event_tags($fcreate);            
        }
               
    } catch (\Moviao\Database\Exception\DBException $dbex) {
        $bresult = false; 
        error_log('EventsCommon (DBException) >> modify_event_tags : ' . $dbex);
    } catch (\Error $ex) {
        $bresult = false; 
        error_log('EventsCommon >> modify_event_tags : ' . $ex);
    } finally {
        if ($bresult) {
            if (!empty($data)) $data->commitTransaction();
        } else { 
            if (!empty($data)) $data->rollbackTransaction();
        }
    } 
        
    if ($bresult) {
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    
    return $array;     
}

/**
 * Display Events
 * @return array
 */
public function display() : array {    
    $return_data = array();
    
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();  
        $data = parent::getDBConn();
        $IDUSER = parent::getSession()->getIDUSER();                
        $strSql = 'SELECT e.*, (SELECT ed.EVTDAT_DATBEG FROM events_dates ed WHERE ed.ID_EVT=e.ID_EVT LIMIT 1) EVTDAT_DATBEG, (SELECT ed.EVTDAT_DATEND FROM events_dates ed WHERE ed.ID_EVT=e.ID_EVT LIMIT 1) EVTDAT_DATEND FROM events e WHERE exists (SELECT 1 FROM events_dates ed WHERE ed.ID_EVT=e.ID_EVT AND ed.EVT_DATBEG >= Current_date()) AND e.EVT_ONLINE=1 AND e.EVT_ACTIVE=1 AND e.ID_EVTVIS=1 order by e.ID_EVT DESC LIMIT 6;'; //WHERE e.EVT_DATBEG >= NOW()
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $stmt = $data->executeQuery($strSql);
        $i=0;
           
        while ($row = $data->fetchObject()) {          
            if (! empty($row)) {
                $return_data['data'][$i]['CITY'] = $row->ID_CITY;
                $return_data['data'][$i]['CONFIRM'] = $row->EVT_CONFIRM;
                $return_data['data'][$i]['COUNTRY'] = $row->ID_COUNTRY;

                $return_data['data'][$i]['DATBEG'] = empty($row->EVTDAT_DATBEG) ? 'RSVP' :  $row->EVTDAT_DATBEG;
                $return_data['data'][$i]['DATEND'] = $row->EVTDAT_DATEND;

                $return_data['data'][$i]['TITLE'] = strip_tags ( (strlen($row->EVT_TITLE) > 0) ? $row->EVT_TITLE : mb_substr($row->ID_EVTACT, 0, 50));
                $return_data['data'][$i]['DESC'] = mb_substr($row->EVT_DESC,0,100);  // utf8 ???
                $return_data['data'][$i]['FREE'] = $row->EVT_FREE;
                $return_data['data'][$i]['EVT'] = $row->ID_EVT;
                $return_data['data'][$i]['GRP'] = $row->ID_CHA;
                $return_data['data'][$i]['URLLINK'] = $row->EVT_URLLINK;                  
                $i++;
            } 
        }    
    
    } catch (\Error $e) {
        error_log('EventsCommon >> display : ' . $e);
        $return_data = array();
    }
    
    return $return_data;
}

/**
 * Display Events From Channels
 * @param stdClass $form
 * @return array
 */
public function filter(\stdClass $form) : array {
    $bresult = false;
    $return_data = array();

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize(); // TODO: Disable after

        if (empty($form) || empty($form->UID) || ! is_string($form->UID)) {
            return array('result' => false,'code' => 666);
        }

        $IDUSER = parent::getSession()->getIDUSER();
        $data = parent::getDBConn();
        
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $events_utils = new \Moviao\Data\Util\EventsUtils($this);                
        // Get Channel ID
        $channelID = 0;                
        $uid = filter_var(strip_tags($form->UID), FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH);
        
        $channel_utils = new \Moviao\Data\Util\ChannelsUtils($this);
        $channelID = $channel_utils->getChannelID($uid);
        //--------------------------------------------------------------------------     

        $params = new stdClass();
        $params->limit  = 9;

        // Offset
        $offset = 0;
        
        if (! empty($form->O)) {
            try {
                $offset = intval(filter_var($form->O, FILTER_SANITIZE_NUMBER_INT));
            } catch (\Error $e) {                
                $offset = 0;
                error_log('EventsCommon >> filter (int conversion) : ' . $e);
            }   
        }

        $offset *= $params->limit; // Calcul offset
        $params->offset = $offset;

        $params->IDUSER = $IDUSER;
        $params->IDCHA = (int) $channelID;
        $params->past = false;

        if (isset($form->D) && $form->D === 1) {
            $params->past = true;
        }

        $return_data = $events_utils->getFilter($params);
        $bresult = true;
    } catch (\Error $e) {
        $bresult = false;
        error_log('EventsCommon >> filter : ' . $e);
    }   
    
    if ($bresult === true) {
        $array = array('result' => $bresult,'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }    

    return $array;  
}

/**
 * show event
 * @param stdClass $form
 * @return array
 */
public function show(\stdClass $form) : array {    
    $return_data = array();
    $bresult = false;

    try {

        parent::getSession()->startSession();
        
        if (empty($form) || empty($form->UID) || ! is_string($form->UID)) {
            return array('result' => false,'code' => 666);
        }   

        $uid = mb_substr(strip_tags(filter_var($form->UID, FILTER_SANITIZE_FULL_SPECIAL_CHARS)),0,60);
        $data = parent::getDBConn();
        $IDUSER = parent::getSession()->getIDUSER();

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $fdata = new stdClass();                   
        $fdata->URLLINK = $uid;
        $fdata->USR = $IDUSER;
        $fdata->LANG = parent::getSession()->getLanguage();

        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $return_data = $event_utils->show($fdata);

        if (! empty($return_data)) {
            $bresult = true;
        }

    } catch (\Error $e) {
        $bresult = false;
        error_log('EventsCommon >> show : ' . $e);
    }         
    if ($bresult === true) {
        $array = array('result' => $bresult, 'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }

    return $array;
}

/**
 * Display Latest Events
 * @param stdClass $form
 * @return array
 */
public function showLatest(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;
    $return_data = array();
    $iduser = null;
    $lat = null;
    $lng = null;
        
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();
        // Suffix Website .ES, .BE, etc ...
        //$http_utils = new \Moviao\Http\ServerInfo();
        //$suffix = $http_utils->getServerSuffix();
        
        if (empty($form) || ! is_numeric($form->O) || ! isset($form->Q) || ! \is_string($form->Q) || ! isset($form->LAT) || ! isset($form->LON)) {
            return array('result' => false, 'code' => 666);
        }

        $iduser = parent::getSession()->getIDUSER();

        $params = new \stdClass();
        $params->iduser = $iduser;
        $params->limit  = 12;
        $params->query  = trim(filter_var($form->Q, FILTER_UNSAFE_RAW));
        $params->lang   = parent::getSession()->getLanguage();
        $params->rad = filter_var($form->RAD, FILTER_SANITIZE_NUMBER_INT); // radius lat & long

        if (empty($params->query)) {
            $params->query = '';
        }

        $arr = ['today','tomorrow','weekend','week'];
        $params->period = null;
        if (isset($form->P) && (null !== $form->P) && in_array($form->P, $arr)) {
            $params->period = $form->P;        
        }

        $params->tag = null;
        if (isset($form->T) && (null !== $form->T)) { // && in_array($form->t, $arr)
            $params->tag = $form->T;
        }

        // Offset
        $offset = intval(filter_var($form->O, FILTER_SANITIZE_NUMBER_INT));
        $offset *= $params->limit; // Calcul offset
        $params->offset = $offset;

        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        if (isset($form->LAT) && isset($form->LON) && (!empty($form->LON)) && (!empty($form->LAT)) && (is_numeric($form->LON)) && (is_numeric($form->LAT))) {
            $lat = $form->LAT;
            $lng = $form->LON;
        } else {
            //$lat = parent::getSession()->getLatitude();
            //$lng = parent::getSession()->getLongitud();
        }

        // Force Location
        if (empty($lat) || empty($lng)) {
            // Need a location to send a response
            //$array = array('result' => $bresult,'data' => $return_data);
            //return $array;

            $user_utils = new \Moviao\Data\Util\UsersUtils($this);
            $user_prefs = $user_utils->getUserSearchPreference($iduser);
            //exit(var_dump($user_prefs[0]["LAT"]));

            if (! empty($user_prefs)) {
                $lat = $user_prefs[0]["LAT"];
                $lng = $user_prefs[0]["LON"];
            }
        }

        $params->lat = $lat;
        $params->lon = $lng;

       // Save session
        parent::getSession()->setLatitude(strval($params->lat));
        parent::getSession()->setLongitud(strval($params->lon));

        // radius
        if (isset($params->rad) && is_numeric($form->RAD)) {
            $params->rad = doubleval($params->rad);
            if ($params->rad < 1) {
                $params->rad = 30;
            }
        } else {
            $params->rad = 30;
        }

//        if ($suffix == 'ES') {            
//        } else if ($suffix == 'BE') {            
//        } else if ($suffix == 'FR') {            
//        } else {            
//        }
        
        //$params->COUNTRY = 62; // Spain
        
        //-------------------------------------
        // test memcached
        //$m = new \Memcached();
        //$m->addServer('localhost', 11211); // or die ('Could not connect');
        //-------------------------------------                
        //if ($m->get('events_showLatest') === false) {
        $utils = new \Moviao\Data\Util\EventsUtils($this);
        $return_data = $utils->showEvents($params);
        //    $m->set('events_showLatest', $return_data, time() + 300);
        //} else {
        //    $return_data = $m->get('events_showLatest');
        //}
        
        $bresult = true;                
    } catch (\Error $e) {
        $bresult = false;
        error_log('EventsCommon >> showLatest : ' . $e);
        parent::setError(7987987987);        
    }    
    
    if ($bresult === true) {
        $array = array('result' => $bresult,'data' => $return_data);        
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    //exit(var_dump($array));

    return $array;       
}
















/**
 * Get events for Dashboard
 * @param stdClass $form
 * @return array
 */
public function getDashBoard(\stdClass $form) : array {    
    //exit(var_dump($form));
    $return_data = array();
    $data = null;
    $bresult = false;
    
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();        
                
        if (empty($form) || ! isset($form->O) || ! is_numeric($form->O)) {
            return array('result' => false, 'code' => 666);
        }
        
        $data = parent::getDBConn();    
        $params = new \stdClass();
        $params->iduser = parent::getSession()->getIDUSER();
        $params->limit  = 9;
        $params->past = false;

        if (isset($form->D) && $form->D === 1) {
            $params->past = true;
        }

        // Offset
        $offset = (int) filter_var($form->O, FILTER_SANITIZE_NUMBER_INT);
        $offset *= $params->limit; // Calcul offset
        $params->offset = $offset;

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        //$data->startTransaction();  
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);        
        $return_data = $event_utils->getEventsDashboard($params); 
        $bresult = true;

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false; 
        error_log('EventsCommon (DBException) >> getDashBoard : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('EventsCommon >> getDashBoard : ' . $e);
    } 
    
    /*
    finally {
        if ($bresult === true) {
            if (!empty($data)) $data->commitTransaction();
        } else { 
            if (!empty($data)) $data->rollbackTransaction();            
        }    
    } 
    */  
    
    if ($bresult === true) {
        //exit(var_dump($return_data));
        $array = array('result' => $bresult,'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    
    return $array;     
}
















/**
 * Get events for Calendar
 * @param stdClass $form
 * @return array
 */
public function getCalendar(\stdClass $form) : array {    
    //exit(var_dump($form));
    $return_data = array();
    $data = null;
    $bresult = false;
    
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();        
                
        if (empty($form) || ! isset($form->O) || ! is_numeric($form->O)) {
            return array('result' => false, 'code' => 666);
        }
        
        $data = parent::getDBConn();    
        $params = new \stdClass();
        $params->iduser = parent::getSession()->getIDUSER();
        $params->limit  = 9;
        $params->past = false;

        if (isset($form->D) && $form->D === 1) {
            $params->past = true;
        }

        // Offset
        $offset = (int) filter_var($form->O, FILTER_SANITIZE_NUMBER_INT);
        $offset *= $params->limit; // Calcul offset
        $params->offset = $offset;

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();  
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);        
        $return_data = $event_utils->getCalendar($params); 
        $bresult = true;
    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false; 
        error_log('EventsCommon (DBException) >> getCalendar : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('EventsCommon >> getCalendar : ' . $e);
    } finally {
        if ($bresult === true) {
            if (!empty($data)) $data->commitTransaction();
        } else { 
            if (!empty($data)) $data->rollbackTransaction();            
        }    
    }   
    
    if ($bresult === true) {
        //exit(var_dump($return_data));
        $array = array('result' => $bresult,'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    
    return $array;     
}

/**
 * Get Events Calendar for Feed
 * @param stdClass $form
 * @return array
 */
public function getCalendarFeed(\stdClass $form) : array {      
    $bresult = false;
    $return_data = array();    
    $data = null;
    
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();
        $IDUSER = parent::getSession()->getIDUSER();                    
        $parameters = [ 'start' => '', 'end' => '', 'iduser' => $IDUSER ];     
        $data = parent::getDBConn();

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();    
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);        
        $return_data = $event_utils->getCalendarFeed($parameters);

        if (! empty($return_data)) {
            $bresult = true;
        }

    } catch (\Moviao\Database\Exception\DBException $dbex) {
        $bresult = false; 
        error_log('EventsCommon (DBException) >> getCalendarFeed : ' . $dbex);        
    } catch (\Error $ex) {
        $bresult = false;
        error_log('EventsCommon >> getCalendarFeed : ' . $ex);        
    } finally {
        if ($bresult) {
            if (!empty($data)) $data->commitTransaction();
        } else { 
            if (!empty($data)) $data->rollbackTransaction();
        }    
    }   
    
    if ($bresult === true) {
        $array = array('result' => $bresult,'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    
    return $array;     
}

/**
 * Publish Comment
 * @param stdClass $form
 * @return array
 */
private function publishComment(\stdClass $form) : array {
    $bresult = false;
    $data = null;
        
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();        
        
        if (empty($form) || (!isset($form->DESC)) || (strlen($form->DESC) <=0) || (!isset($form->UID)) || (strlen($form->UID) <=0)) {
            return array('result' => 666);
        }           

        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        
        $data->startTransaction();    

        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($form->UID);

        if ($eventID <= 0) {
            return array('result' => false,'code' => 777);
        }                

        $f = new stdClass();
        $f->IDCOMLNK = $eventID; // Event
        $f->IDCOMLNKTYP = 1; // Type Event
        $f->USR = parent::getSession()->getIDUSER();
        $f->DESC = mb_substr(strip_tags($form->DESC),0, 500);
        $f->ACTIVE = 1;
                
        $generic = new Util\GenericUtils($this);
        $bresult = $generic->createComment($f);
        
    } catch (\Moviao\Database\Exception\DBException $dbex) {
        $bresult = false; 
        error_log('EventsCommon (DBException) >> publishComment : '. $dbex);
    } catch (\Error $ex) {
        $bresult = false;
        error_log('EventsCommon >> publishComment : ' . $ex);
    } finally {
        if ($bresult) {
            if (!empty($data)) $data->commitTransaction();
        } else { 
            if (!empty($data)) $data->rollbackTransaction();
        }    
    }   
    
    if ($bresult === true) {        
        $array = array('result' => $bresult);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    
    return $array;    
}

private function loadComments(\stdClass $form) : array {
    $bresult = false;
    $return_data = array();
   
    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();          
        
        if (empty($form) || (!isset($form->UID)) || ! is_string($form->UID) || (strlen($form->UID) <=0)) {
            return array('result' => 666);
        }    

        $data = parent::getDBConn();
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($form->UID);

        if ($eventID <= 0) {
            return array('result' => false,'code' => 777);
        }        

        $generic = new Util\GenericUtils($this);
        $return_data = $generic->loadComments($eventID, 1);
        
        if (count($return_data) > 0) {
            $bresult = true;        
        }

    } catch (\Error $ex) {
        $bresult = false;
        error_log('EventsCommon >> loadComments : ' . $ex);
    }
    
    if ($bresult === true) {
        $array = array('result' => $bresult, 'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    
    return $array;  
} 

/**
 * Display Tags in relation with a Event
 * @param stdClass $form
 * @return array
 */
public function getTags(\stdClass $form) : array {   
    $bresult = false;
    $return_data = array();
      
    try {        
        
        if (empty($form) || empty($form->UID) || ! is_string($form->UID)) {
           return array('result' => false,'code' => 666); 
        }        

        $uid = mb_substr(filter_var($form->UID, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH),0,50);
        $data = parent::getDBConn();

        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $form->LANG = '';
        if (! empty($form->LANG)) {
            $form->LANG = mb_substr(filter_var($form->LANG, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH),0,2);
        }

        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);

        if ($eventID <= 0) {
            return array('result' => false,'code' => 777);
        }

        // Get Events Tags    
        $return_data = $event_utils->getTags($eventID, $form->LANG);
        //return var_dump($return_data);        
        $bresult = true;       
    
    } catch (\Error $ex) {
        $bresult = false;
        error_log('ChannelsCommon >> getTags : ' . $ex);
    }
    
    if ($bresult === true) {        
        $array = array('result' => $bresult, 'data' => $return_data);
    } else {
        $array = array('result' => $bresult,'code' => parent::getError());
    }
    
    return $array;
}



private function sanitizeTitle($title_unsafe) {    
    $filtered_title = null; 

    if (! empty($title_unsafe)) {
        $title_unsafe = htmlentities(mb_substr($title_unsafe,0,150), ENT_QUOTES | ENT_HTML5, 'UTF-8');        
        // Filter html xss title
        $filter = new \Moviao\Http\HTML_Sanitizer;                        
        $filtered_title = $filter->xss($title_unsafe);                     
    }

    return $filtered_title;
}

private function sanitizeDescription($descl_unsafe) {
    $filtered_descl = null; 

    if (! empty($descl_unsafe)) {
        $descl_unsafe = strip_tags($descl_unsafe, self::strip_html);

        if (mb_strlen($descl_unsafe) > 4000) {
            $descl_unsafe = mb_substr($descl_unsafe, 0, 4000);
        }

        // Filter html xss description
        $filter = new \Moviao\Http\HTML_Sanitizer;
        //$allowed_protocols = array('http');
        $allowed_tags = self::strip_html_array;
        //$filter->addAllowedProtocols($allowed_protocols);
        $filter->addAllowedTags($allowed_tags);        
        $filtered_descl = $filter->xss($descl_unsafe);
    }

    return $filtered_descl;
}


}