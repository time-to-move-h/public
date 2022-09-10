<?php
declare(strict_types=1);
namespace Moviao\Data\Util;
use PDO;
use DateTime;

class EventsUtils extends BaseUtils {

public function create(\stdClass $form) : \stdClass {

    $resultObj = new \stdClass();
    $resultObj->lastid = -1;
    $resultObj->urllink = null;

    // Location
    if (! empty($form->LAT) && ! empty($form->LON)) {
        $locationp = 'POINT(' . $form->LAT . ' ' . $form->LON . ')';
    } else {
        $locationp = null;
    }

    //$allday = isset($form->ALLDAY) ? 1 : 0;
    $userconfirm = isset($form->CONFIRM) ? 1 : 0;
    $urllink = $this->generateEventUrl($form->TITLE);
    //$id_timezone_begin = (int) $form->ZONEIDBEG_ID;
    //$id_timezone_end = (int) $form->ZONEIDEND_ID;
    
    // Create Event
    $event = new \Moviao\Data\Rad\Events($this->commonData);  
    $fdata = $event->filterForm($form);  
    $fdata->set_EVTTYP($form->EVTTYP); // Event Type
    $fdata->set_CONFIRM($userconfirm); // Aucune Confirmation
    $fdata->set_CHA($form->CHANNELID);
    $fdata->set_ACTIVE(1);
    $fdata->set_ONLINE(0); // Wait For publishing
    //$fdata->set_ALLDAY($allday);
    $fdata->set_URLLINK($urllink);
    //$fdata->set_ZONEIDBEG($id_timezone_begin);
    //$fdata->set_ZONEIDEND($id_timezone_end);
    $fdata->set_LOCATIONP($locationp);
    $fdata->set_DATINS(date('Y-m-d H:i:s'));
    $fdata->set_ISONLINE($form->ISONLINE);

    //exit(var_dump($form));

    $resultObj->result = $event->create($fdata);
    
    if ($resultObj->result === true) {
        $resultObj->lastid = $this->getData()->getDBConn()->lastInsertId();
        $resultObj->urllink = $urllink;
    }
       
    return $resultObj;
}

public function generateEventUrl(string $salt) : string {
    $urllink = '';
    // Generation URL ----------------------------------------------------------         
    $t = (int) microtime(true);
    $micro = (int) sprintf('%06d',($t - floor($t)) * 1000000);
    $d = new DateTime(date('Y-m-d H:i:s.'.$micro,$t));        
    $text_format = new \Moviao\Text\TextFormatter();
    $urllink = 'event-' . substr($text_format->slug($salt),0,30) . '-' . $d->format('YmdHisu');        
    //--------------------------------------------------------------------------  
    return $urllink;
}

public function create_event_user(\stdClass $form) : bool {
    $bresult = false;            
    $event_list = new \Moviao\Data\Rad\EventsList($this->commonData);             
    $fdata = new \Moviao\Data\Rad\EventsListData(); 
    $fdata->set_EVT($form->idevent);
    $fdata->set_USR($form->iduser);
    //    $fdata->set_N_FRIEND($EVTLST_N_FRIEND);
    //    $fdata->set_EVTLST($ID_EVTLST);
    //    $fdata->set_WAIT($EVTLST_WAIT);
    //    $fdata->set_DATBEG($EVTLST_DATBEG);
    $fdata->set_EVTROLE(1); // Admin 
    $fdata->set_CONFIRM(1);
    $fdata->set_ACTIVE(1);
    $bresult = $event_list->create($fdata);    
    return $bresult;    
}

public function create_event_admin(\stdClass $form) : bool {
    $bresult = false;
    $event_admin = new \Moviao\Data\Rad\EventsAdmin($this->commonData);
    $fdata = new \Moviao\Data\Rad\EventsAdminData();
    $fdata->set_EVT($form->idevent);
    $fdata->set_USR($form->iduser);
    $fdata->set_ACTIVE(1);
    $fdata->set_DATINS(date('Y-m-d H:i:s') );
    $bresult = $event_admin->create($fdata);
    return $bresult;
}

public function create_event_dates(array $dates_split) : bool {
    $result = false;
    $event_dates = new \Moviao\Data\Rad\EventsDates($this->commonData);

    foreach ($dates_split as $value) {

        if (! empty($value)) {

            //exit(var_dump($value));

            $fdata_dates = new \Moviao\Data\Rad\EventsDatesData();
            $fdata_dates->set_EVT($value->idevent);
            $fdata_dates->set_DATBEG($value->datbeg);
            $fdata_dates->set_DATEND($value->datend);
            $fdata_dates->set_ZONEIDBEG($value->zoneidbeg);
            $fdata_dates->set_ZONEIDEND($value->zoneidend);
            $fdata_dates->set_ALLDAY($value->allday);
            $fdata_dates->set_TOKEN($value->token);
            $fdata_dates->set_ONLINE(1);
            $fdata_dates->set_ACTIVE(1);
            $fdata_dates->set_DATINS(date('Y-m-d H:i:s'));
            $result = $event_dates->create($fdata_dates);

            if (! $result) {
                $this->getData()->setError(4646445412121547);
                break;
            }

            $fdata_dates = null;
        }

    }
    return $result;
}

public function create_event_tags(\stdClass $form) : bool {
    $bresult = false;
    $tags_split = $form->tags;                    
    $event_tags = new \Moviao\Data\Rad\EventsTags($this->commonData);
    foreach ($tags_split as $tag_value) {
        if (ctype_digit($tag_value)) {
            $ID_TAG = (int)($tag_value);
            if ($ID_TAG > 0) {                            
                $fdata_tags = new \Moviao\Data\Rad\EventsTagsData();
                $fdata_tags->set_EVT($form->idevent);
                $fdata_tags->set_TAG($ID_TAG);
                $fdata_tags->set_ACTIVE(1);
                $fdata_tags->set_DATCRE(date('Y-m-d H:i:s'));
                $bresult = $event_tags->create($fdata_tags);

                if (! $bresult) {
                    $this->getData()->setError(4646445412121547);
                    break;
                }
                $fdata_tags = null;
            }
        }        
    } 
    return $bresult;
}

public function getMaxEventID(\Moviao\Data\Rad\EventsListData $fdata) : int {
    $strSql = 'SELECT MAX(ID_EVTLST) FROM events_list WHERE ID_EVT=? AND ID_USR=? FOR UPDATE;';
    $params = [[ 'parameter' => 1, 'value' => $fdata->get_EVT(), 'type' => PDO::PARAM_INT ],[ 'parameter' => 2, 'value' => $fdata->get_USR(), 'type' => PDO::PARAM_INT ]];
    $row = $this->data->readColumn($strSql, $params);
    $max = 1;    
    if ($row !== false) {
        $max = (int)($row);
        if (empty($max) || (! is_numeric($max))) {
            $max = 1;
        } else {
            $max = $max + 1;            
        }            
    }
    return $max;    
}

public function isEventConfirm(\Moviao\Data\Rad\EventsListData $fdata) : bool {
    $strSql = 'SELECT EVT_CONFIRM FROM events WHERE ID_EVT=? LIMIT 1;';        
    $params = [[ 'parameter' => 1, 'value' => $fdata->get_EVT(), 'type' => PDO::PARAM_INT ]];    
    $row = $this->data->readColumn($strSql, $params);    
    if ($row === 1) return true;
    return false;
}

private function isOrganizer(\Moviao\Data\Rad\EventsListData $fdata) : bool {
    $strSql = 'SELECT 1 FROM events_admin WHERE ID_EVT=? AND ID_USR=? AND EVTADM_ACTIVE=1 LIMIT 1;';
    $params = [['parameter' => 1, 'value' => $fdata->get_EVT(), 'type' => PDO::PARAM_INT ],
               ['parameter' => 2, 'value' => $fdata->get_USR(), 'type' => PDO::PARAM_INT ]];
    $row = $this->data->readColumn($strSql, $params);

    if ($row !== false) {
        return true;
    }

    return false;
}

public function getEventID(string $urllink) : int {
    $eventID = 0;
    $strSql = 'SELECT ID_EVT FROM events WHERE UPPER(EVT_URLLINK)=UPPER(?) LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $urllink, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params); 
    if ($row !== false) {
        $eventID = (int)($row);
    }
    return $eventID;
}

public function isSubscribed(\Moviao\Data\Rad\EventsListData $fdata) : int {
    $iSubscribed = -1; // Non Exist
    $strSql = 'SELECT EVTLST_CONFIRM FROM events_list WHERE ID_EVT=? AND ID_USR=? LIMIT 1;';
    $params = [
        [ 'parameter' => 1, 'value' => $fdata->get_EVT(), 'type' => PDO::PARAM_INT ],
        [ 'parameter' => 2, 'value' => $fdata->get_USR(), 'type' => PDO::PARAM_INT ]];

    $row = $this->data->readColumn($strSql, $params);

    //echo var_dump($row);
    //exit();

    if ($row !== false) {
        if ($row === 1) {
            $iSubscribed = 1; // Enabled
        } else if ($row === 0)  {
            $iSubscribed = 0; // Enabled but not confirmed
        }
    }
    return $iSubscribed;
}


public function subscribe(\Moviao\Data\Rad\EventsListData $fdata) : bool {
    $result = false;
    $iSubscribed = $this->isSubscribed($fdata);
    $confirmation = ($this->isEventConfirm($fdata)) ? 0 : 1;
    $organizer = ($this->isOrganizer($fdata)) ? 1 : 2;

    if ($iSubscribed === -1) {         
        $fdata->set_CONFIRM($confirmation);
        $max = $this->getMaxEventID($fdata);
        $fdata->set_EVTLST($max);
        $fdata->set_EVTROLE($organizer);
        $fdata->set_STATUS('A'); // A == Attend
        $event_list = new \Moviao\Data\Rad\EventsList($this->commonData);
        $result = $event_list->create($fdata);          
    } else {
        $result = $this->updateEventList($fdata, 'A',1,0);
    }

    return $result;
}


public function updateEventList(\Moviao\Data\Rad\EventsListData $fdata, string $status, int $new_active, int $old_active) : bool {
    $strSql = 'UPDATE events_list SET EVTLST_STATUS=?,EVTLST_ACTIVE=?,EVTLST_DATMOD=UTC_TIMESTAMP() WHERE ID_EVT=? AND ID_USR=? AND EVTLST_DATBEG=? AND EVTLST_ACTIVE=? LIMIT 1;';
    $params = [
        [ 'parameter' => 1, 'value' => $status, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 2, 'value' => $new_active, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => 3, 'value' => $fdata->get_EVT(), 'type' => PDO::PARAM_INT ],
        [ 'parameter' => 4, 'value' => $fdata->get_USR(), 'type' => PDO::PARAM_INT ],
        [ 'parameter' => 5, 'value' => $fdata->get_DATBEG(), 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 6, 'value' => $old_active, 'type' => PDO::PARAM_INT ]];

    return $this->data->executeNonQuery($strSql, $params);
}

public function publish(\stdClass $formObj) : bool {
    $strSql = 'UPDATE events SET EVT_ONLINE=1,EVT_DATMOD=UTC_TIMESTAMP() WHERE ID_EVT=? AND EVT_ONLINE=0 AND EVT_ACTIVE=1 AND 1=(SELECT 1 FROM events_admin WHERE ID_EVT=events.ID_EVT AND EVTADM_ACTIVE=1 AND ID_USR=? LIMIT 1) LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $formObj->IDEVT, 'type' => PDO::PARAM_INT ],[ 'parameter' => 2, 'value' => $formObj->IDUSER, 'type' => PDO::PARAM_INT ]];
    return $this->data->executeNonQuery($strSql, $params);
}

public function show(\stdClass $fdata) : array {
    $return_data = array();
    $d1 = null;
    $d2 = null;
    $datbegin_timestamp = 0;
    $datend_timestamp = 0;
    $datevent_formatted = null;
    $datebegin_iso8601 = null;
    $dateend_iso8601 = null;
    $allday = false;

    if (! isset($fdata->USR)) {
        $fdata->USR = 0;
    }

    $strSql = 'SELECT e.* '
            . ',ST_X(EVT_LOCATIONP) LATITUDE '
            . ',ST_Y(EVT_LOCATIONP) LONGITUDE '
            . ',(SELECT u.USR_UUID FROM users u, events_admin el WHERE u.ID_USR=el.ID_USR AND el.ID_EVT=e.ID_EVT AND el.EVTADM_ACTIVE=1 LIMIT 1) USR_UUID '
            . ',(SELECT u.USR_NDISP FROM users u, events_admin el WHERE u.ID_USR=el.ID_USR AND el.ID_EVT=e.ID_EVT AND el.EVTADM_ACTIVE=1 LIMIT 1) USR_NDISP '
            . ',(SELECT u.USR_PICTURE FROM users u, events_admin el WHERE u.ID_USR=el.ID_USR AND el.ID_EVT=e.ID_EVT AND el.EVTADM_ACTIVE=1 LIMIT 1) USR_AVATAR '
            . ',(SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATBEG '
            . ',(SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATEND '
            . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDBEG ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_BEG '
            . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDEND ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_END '
            . ',(SELECT EVTDAT_ALLDAY FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_ALLDAY '
            . ',IFNULL((SELECT EVTLST_CONFIRM FROM events_list WHERE ID_EVT=e.ID_EVT AND ID_USR=:iduser AND EVTLST_ACTIVE=1 LIMIT 1),-1) SUBSCRIPTION '
            . ',IFNULL((SELECT COUNT(*) FROM events_list WHERE ID_EVT=e.ID_EVT AND EVTLST_ACTIVE=1), 0) USR_COUNTER_ATTENDEES '
            . ',(SELECT 1 FROM events_admin WHERE ID_EVT=e.ID_EVT AND ID_USR=:iduser AND EVTADM_ACTIVE=1 LIMIT 1) ROLE '
            . ',(SELECT ID_USR FROM events_admin WHERE ID_EVT=e.ID_EVT AND ID_USR=:iduser AND EVTADM_ACTIVE=1 LIMIT 1) USR '
            . ',(SELECT CHA_NAME FROM channels WHERE ID_CHA=e.ID_CHA AND CHA_ACTIVE=1) CHA_NAME '
            . ',(SELECT CHA_TITLE FROM channels WHERE ID_CHA=e.ID_CHA AND CHA_ACTIVE=1) CHA_TITLE '
            . ',(SELECT CHA_ONLINE FROM channels WHERE ID_CHA=e.ID_CHA AND CHA_ACTIVE=1) CHA_ONLINE '
            . ',(SELECT CHA_PICTURE_RND FROM channels WHERE ID_CHA=e.ID_CHA AND CHA_ACTIVE=1) CHA_PICTURE_RND '
            . ',(SELECT CHA_PICTURE_MIN FROM channels WHERE ID_CHA=e.ID_CHA AND CHA_ACTIVE=1) CHA_PICTURE_MIN '
            . ',(SELECT CHA_COUNTER_FOLLOWERS FROM channels WHERE ID_CHA=e.ID_CHA AND CHA_ACTIVE=1) CHA_COUNTER_FOLLOWERS '
            . ',(SELECT 1 FROM tickets_events WHERE ID_EVT=e.ID_EVT AND TICEVT_ACTIVE=1 LIMIT 1) IS_TICKET '
            . 'FROM events e WHERE e.EVT_ACTIVE=1 AND e.EVT_URLLINK=:urllink;';

    $params = [
        [ 'parameter' => ':iduser', 'value' => $fdata->USR, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':urllink', 'value' => $fdata->URLLINK, 'type' => PDO::PARAM_STR ]];        
    $row = $this->data->readLineObject($strSql, $params);

    $dateformat = new \Moviao\Util\DateTimeFormat();

    // Date Formatted
    if ($row->EVTDAT_DATBEG !== null) {
        $date_start = new DateTime($row->EVTDAT_DATBEG,new \DateTimeZone('UTC'));
        $date_start->setTimezone(new \DateTimeZone($row->TIMEZONE_BEG));

        $date_end = null;
        if ($row->EVTDAT_DATEND !== null) {
            $date_end = new DateTime($row->EVTDAT_DATEND,new \DateTimeZone('UTC'));
            $date_end->setTimezone(new \DateTimeZone($row->TIMEZONE_END));
        }

        if (isset($row->EVTDAT_ALLDAY) && $row->EVTDAT_ALLDAY === '1') {
            $allday = true;
        }

        $lang = $fdata->LANG ?? 'en-GB';
        $datevent_formatted = $dateformat->formatDate($date_start,$date_end,$lang,$allday,false);
    }

    // Date Begin
    if ($row->EVTDAT_DATBEG !== null && ! empty($row->EVTDAT_DATBEG)) {
        $d1 = new DateTime($row->EVTDAT_DATBEG, new \DateTimeZone($row->TIMEZONE_BEG));
        $datbegin = $d1->getTimestamp();
        $datbegin_timestamp = $datbegin * 1000;
    }

    // Date End
    if ($row->EVTDAT_DATEND !== null && ! empty($row->EVTDAT_DATEND)) {
        $d2 = new DateTime($row->EVTDAT_DATEND, new \DateTimeZone($row->TIMEZONE_END));
        $datbegin = $d2->getTimestamp();
        $datend_timestamp = $datbegin * 1000;
    }

    // --------------------------------------------------------------------------------
    // Date Begin ISO 8601    
    if (! is_null($row->EVTDAT_DATBEG)) {
        $dt = new \DateTime($row->EVTDAT_DATBEG); // '2010-12-30 23:21:46'
        $dt->setTimezone(new \DateTimeZone($row->TIMEZONE_BEG)); // 'UTC'
        $datebegin_iso8601 = $dt->format('c'); //  Y-m-d\TH:i:s.u\Z
    }
    // --------------------------------------------------------------------------------
    // Date End ISO 8601    
    if (! is_null($row->EVTDAT_DATEND)) {
        $dt2 = new \DateTime($row->EVTDAT_DATEND); // '2010-12-30 23:21:46'
        $dt2->setTimezone(new \DateTimeZone($row->TIMEZONE_END)); // 'UTC'
        $dateend_iso8601 = $dt2->format('c'); // Y-m-d\TH:i:s.u\Z
    }    
    // --------------------------------------------------------------------------------

    //exit(var_dump($row));
    if ($row !== false) {
        $return_data['ID'] = $row->ID_EVT;
        $return_data['EVTTYP'] = $row->ID_EVTTYP;
        $return_data['URLLINK'] = $row->EVT_URLLINK;
        $return_data['TITLE'] = $row->EVT_TITLE;
        $return_data['DESCL'] = $row->EVT_DESCL;
        $return_data['URL'] = $row->EVT_URL;
        $return_data['ALLDAY'] = $row->EVTDAT_ALLDAY;
        $return_data['DATBEG'] = $dateformat->formatShortDate($d1);
        $return_data['DATEND'] = $dateformat->formatShortDate($d2);
        $return_data['DATBEG_TIMESTAMP'] = $datbegin_timestamp;
        $return_data['DATEND_TIMESTAMP'] = $datend_timestamp;
        $return_data['DATFORMATTED'] = $datevent_formatted;
        $return_data['TIMEZONE_BEG'] = empty($row->TIMEZONE_BEG) ? '' : $row->TIMEZONE_BEG;
        $return_data['TIMEZONE_END'] = empty($row->TIMEZONE_END) ? '' : $row->TIMEZONE_END;
        $return_data['DATBEG_ISO8601'] = $datebegin_iso8601;
        $return_data['DATEND_ISO8601'] = $dateend_iso8601;
        $return_data['VENUE'] = is_null($row->EVT_VENUE) ? '' : $row->EVT_VENUE;
        $return_data['CITY'] = is_null($row->EVT_CITY) ? '' : $row->EVT_CITY;
        $return_data['STATE'] = is_null($row->EVT_STATE) ? '' : $row->EVT_STATE;
        $return_data['COUNTRY'] = is_null($row->EVT_COUNTRY) ? '' : $row->EVT_COUNTRY;
        $return_data['PCODE'] = is_null($row->EVT_PCODE) ? '' : $row->EVT_PCODE;
        $return_data['STREET'] = is_null($row->EVT_STREET) ? '' : $row->EVT_STREET;
        $return_data['STREET2'] = is_null($row->EVT_STREET2) ? '' : $row->EVT_STREET2;
        $return_data['STREETN'] = is_null($row->EVT_STREETN) ? '' : $row->EVT_STREETN;
        $return_data['LATITUDE'] = is_null($row->LATITUDE) ? '' : $row->LATITUDE;
        $return_data['LONGITUDE'] = is_null($row->LONGITUDE) ? '' : $row->LONGITUDE;
        $return_data['CHA_NAME'] = is_null($row->CHA_NAME) ? '' : $row->CHA_NAME;
        $return_data['CHA_TITLE'] = is_null($row->CHA_TITLE) ? '' : $row->CHA_TITLE;
        $return_data['CHA_ONLINE'] = is_null($row->CHA_ONLINE) ? '' : $row->CHA_ONLINE;
        $return_data['CHA_PICTURE_RND'] = is_null($row->CHA_PICTURE_RND) ? '' : $row->CHA_PICTURE_RND;
        $return_data['CHA_COUNTER_FOLLOWERS'] = is_null($row->CHA_COUNTER_FOLLOWERS) ? '' : $row->CHA_COUNTER_FOLLOWERS;
        $return_data['SUBSCRIPTION'] = $row->SUBSCRIPTION;
        $return_data['ONLINE'] = $row->EVT_ONLINE;
        $return_data['USR_UUID'] = $row->USR_UUID;
        $return_data['USR_NDISP'] = $row->USR_NDISP;
        $return_data['USR_AVATAR'] = $row->USR_AVATAR;
        $return_data['ROLE'] = $row->ROLE;        
        $return_data['PICTURE'] = is_null($row->EVT_PICTURE) ? '' :  $row->EVT_PICTURE;
        $return_data['PICTURE_MIN'] = is_null($row->EVT_PICTURE_MIN) ? '' :  $row->EVT_PICTURE_MIN;
        $return_data['IS_TICKET'] = $row->IS_TICKET === 1;
        $return_data['ISONLINE'] = $row->EVT_ISONLINE;
        $return_data['MAXUSE'] = $row->EVT_MAXUSE;
        $return_data['COUNTER_ATTENDEES'] = $row->USR_COUNTER_ATTENDEES;        
    }

    return $return_data;
}

/**
 * Show Latest Events
 * @param \stdClass $form
 * @return array
 * @throws \Moviao\Database\Exception\DBException
 */
public function showEvents(\stdClass $form) : array {
    $return_data = array();
    $tag = null;
    $strSql = 'SELECT '
                . 'e.*'
                . ',(SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATBEG '
                . ',(SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATEND '
                . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDBEG LIMIT 1) TIMEZONE_BEG '
                . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDEND LIMIT 1) TIMEZONE_END '
                . ',(SELECT EVTDAT_ALLDAY FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1) EVTDAT_ALLDAY '
                . ',(SELECT EVTLST_CONFIRM FROM events_list WHERE ID_EVT=e.ID_EVT AND ID_USR=:iduser AND EVTLST_ACTIVE=1 LIMIT 1) SUBSCRIPTION '
                . ',(SELECT 1 FROM tickets_events WHERE ID_EVT=e.ID_EVT AND TICEVT_ACTIVE=1 LIMIT 1) IS_TICKET '
                . 'FROM events e WHERE '
                . 'e.EVT_ONLINE=1 '
                . 'AND e.EVT_ACTIVE=1 '
                . 'AND e.ID_EVTVIS=1 '
                . 'AND  ( (DATE((SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1)) >= CURDATE() AND (SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) IS NULL ) '
                . 'OR  ((CURDATE() <= DATE((SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1))))) ';

    $params = [[ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ]];

    // Period (Dates)
    if (empty($form->period) === false) {
        if ($form->period === 'today') {
            $strSql .= 'AND DATE((SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1)) = CURDATE() ';
        } else if ($form->period === 'tomorrow') {
            $strSql  .= 'AND DATE(EVTDAT_DATBEG) = DATE_ADD(CURDATE(), INTERVAL 1 DAY) ';
        } else if ($form->period === 'weekend') {
            $strSql  .= 'AND DATE(EVTDAT_DATBEG) >= (CURDATE() + INTERVAL 4 - weekday(CURDATE()) DAY) AND DATE(EVTDAT_DATBEG) <= (CURDATE() + INTERVAL 6 - weekday(CURDATE()) DAY) ';
        }  else if ($form->period === 'week') {
            $strSql  .= 'AND DATE(EVTDAT_DATBEG) <=  DATE_ADD(CURDATE(), INTERVAL 7 DAY) ';
        }
    }

    // Location
    if (! empty($form->lat) && ! empty($form->lon)) {
        $radius = intval($form->rad) * 1000; // Convert km to meters
        $strSql .= 'AND ((ST_Distance_Sphere(e.EVT_LOCATIONP, ST_PointFromText(:location)) <= :radius) OR (e.EVT_ISONLINE=1))';
        //$strSql  .= 'AND (GLength(LineStringFromWKB(LineString(e.EVT_LOCATIONP,GeomFromText(:location))))) <= 1 ';
        $locationp = 'POINT('.$form->lat.' '.$form->lon.')';
        $params[] = [ 'parameter' => ':location', 'value' => $locationp, 'type' => PDO::PARAM_STR ];
        $params[] = [ 'parameter' => ':radius', 'value' => $radius, 'type' => PDO::PARAM_INT ];
    }

    // Query
    if (! empty($form->query)) {
//        $alltags = $this->getAllTags();
//        foreach ($alltags as $key => $value) {
//            $r = strtolower($value['DESC']);
//            $s = strtolower($form->query);
//            if (strpos($s, $r) === false) {
//                continue;
//            } else {
//                $tag = (int) $value['TAG'];
//                break;
//            }
//        }
        //exit(var_dump($value['DESC']));
        // AI IntelliSearch
//        if ($tag !== null) {
//            //exit(var_dump($tag));
//            $strSql .= 'AND e.ID_EVT IN (SELECT t.ID_EVT FROM events_tags t WHERE t.ID_EVT=e.ID_EVT AND t.ID_TAG=:idtag AND t.EVTTAG_ACTIVE=1) ';
//            $params[] = [ 'parameter' => ':idtag', 'value' => $tag, 'type' => PDO::PARAM_INT ];
//        } else {
            $strSql .= 'AND (MATCH(e.EVT_TITLE,e.EVT_DESCL) AGAINST(:q IN BOOLEAN MODE)) ';
            $params[] = [ 'parameter' => ':q', 'value' => $form->query . '*', 'type' => PDO::PARAM_STR ];
        //}
    }

    // Tag
    if (! empty($form->tag) && is_numeric($form->tag)) {
        $tag = null;
        $alltags = $this->getAllTags();
        foreach ($alltags as $key => $value) {
            $r = strtolower($value['TAG']);
            $s = strtolower(strval($form->tag));
            if (strpos($s, $r) === false) {
                continue;
            } else {
                $tag = (int) $value['TAG'];
                break;
            }
        }
        
        if ($tag !== null) {
            $strSql .= 'AND e.ID_EVT IN (SELECT t.ID_EVT FROM events_tags t WHERE t.ID_EVT=e.ID_EVT AND t.ID_TAG=:idtag AND t.EVTTAG_ACTIVE=1) ';
            $params[] = [ 'parameter' => ':idtag', 'value' => $tag, 'type' => PDO::PARAM_INT ];
        }
    }

    $strSql .= 'ORDER BY EVTDAT_DATBEG LIMIT :limit OFFSET :offset;';
    $params[] = [ 'parameter' => ':limit', 'value' => $form->limit, 'type' => PDO::PARAM_INT ];
    $params[] = [ 'parameter' => ':offset', 'value' => $form->offset, 'type' => PDO::PARAM_INT ];

//        if (!empty($form->query)) {
//            if (! $this->data->bindParam(':q',$form->query . '*',PDO::PARAM_STR)) return $return_data; // Query
//        }
        
//        if (! empty($form->period)) {
//            switch($form->period) {
//                case '1003':
//                    if (! $this->data->bindParam(':idtag',1003,PDO::PARAM_INT)) return $return_data; // Fiesta
//                    break;
//                case '1014':
//                    if (! $this->data->bindParam(':idtag',1014,PDO::PARAM_INT)) return $return_data; // Conciertos
//                    break;
//                case '1001':
//                    if (! $this->data->bindParam(':idtag',1001,PDO::PARAM_INT)) return $return_data; // Festival
//                    break;
//                case '1016':
//                    if (! $this->data->bindParam(':idtag',1016,PDO::PARAM_INT)) return $return_data; // Pro
//                    break;
//                case '1012':
//                    if (! $this->data->bindParam(':idtag',1012,PDO::PARAM_INT)) return $return_data; // Deportes
//                    break;
//            }
//        }
                
//        if (! $this->data->execute()) {
//            return $return_data;
//        }

        $returned_events = $this->data->readAllObject($strSql, $params);
        //exit(var_dump($returned_events));

        foreach ($returned_events as $obj) {

            if ($obj === null) {
                continue;
            }

            $datbegin_timestamp = 0;
            $datend_timestamp = 0;
            $datevent_formatted = null;
            $allday = false;

            // Date Formatted
            if ($obj->EVTDAT_DATBEG !== null) {
                $date_start = new DateTime($obj->EVTDAT_DATBEG,new \DateTimeZone('UTC'));
                $date_start->setTimezone(new \DateTimeZone($obj->TIMEZONE_BEG));

                $date_end = null;
                if ($obj->EVTDAT_DATEND !== null) {
                    $date_end = new DateTime($obj->EVTDAT_DATEND,new \DateTimeZone('UTC'));
                    $date_end->setTimezone(new \DateTimeZone($obj->TIMEZONE_END));
                }

                $dateformat = new \Moviao\Util\DateTimeFormat();

                if ((isset($obj->EVT_ALLDAY)) && $obj->EVT_ALLDAY === '1') {
                    $allday = true;
                }

                $datevent_formatted = $dateformat->formatDate($date_start,$date_end,$form->lang ,$allday,true);
            }

            // Date Begin
            if ($obj->EVTDAT_DATBEG !== null && mb_strlen($obj->EVTDAT_DATBEG) > 0) {
                $date = new DateTime($obj->EVTDAT_DATBEG, new \DateTimeZone($obj->TIMEZONE_BEG));
                $datbegin = $date->getTimestamp();
                $datbegin_timestamp = $datbegin * 1000;
            }

            // Date End
            if ($obj->EVTDAT_DATEND !== null && strlen($obj->EVTDAT_DATEND) > 0) {
                $date = new DateTime($obj->EVTDAT_DATEND, new \DateTimeZone($obj->TIMEZONE_END));
                $datbegin = $date->getTimestamp();
                $datend_timestamp = $datbegin * 1000;
            }

            $row['data']['TITLE'] = strip_tags($obj->EVT_TITLE);
            $row['data']['DATBEG'] = $datbegin_timestamp;
            $row['data']['DATEND'] = $datend_timestamp;
            $row['data']['ALLDAY'] = $obj->EVTDAT_ALLDAY;
            $row['data']['DATFORMATTED'] = $datevent_formatted;
            $row['data']['TIMEZONE_BEG'] = $obj->TIMEZONE_BEG;
            $row['data']['TIMEZONE_END'] = empty($obj->TIMEZONE_END) ? '' : $obj->TIMEZONE_END;
            $row['data']['PICTURE_MIN'] = $obj->EVT_PICTURE_MIN;
            $row['data']['URLLINK'] = $obj->EVT_URLLINK;
            $row['data']['CITY'] = is_null($obj->EVT_CITY) ? '' : $obj->EVT_CITY;
            $row['data']['SUBSCRIPTION'] = $obj->SUBSCRIPTION;
            $row['data']['IS_TICKET'] = ($obj->IS_TICKET === '1') ? true : false;

            $return_data[] = $row['data'];                    
        }

    return $return_data;
}

public function cancel_event(\stdClass $form) : bool {
    $strSql = 'UPDATE events SET EVT_ACTIVE=0,EVT_ONLINE=0,EVT_DATMOD=UTC_TIMESTAMP() WHERE ID_EVT=:idevent AND ID_EVT IN (SELECT ID_EVT FROM events_admin WHERE ID_EVT=events.ID_EVT AND ID_USR=:iduser AND EVTADM_ACTIVE=1) LIMIT 1;';
    $params = [[ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ],[ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ]];    
    return $this->data->executeNonQuery($strSql, $params); 
}

public function cancel_event_tags(\stdClass $form) : bool {
    $strSql = 'UPDATE events_tags SET EVTTAG_ACTIVE=0,EVTTAG_DATMOD=UTC_TIMESTAMP() WHERE ID_EVT=:idevent AND EVTTAG_ACTIVE=1 AND ID_EVT IN (SELECT ID_EVT FROM events_admin WHERE ID_EVT=:idevent AND ID_USR=:iduser AND EVTADM_ACTIVE=1) LIMIT 50;';
    $params = [[ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ],[ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ]];    
    return $this->data->executeNonQuery($strSql, $params); 
}

public function cancel_event_user(\stdClass $form) : bool {
    $strSql = 'UPDATE events_list SET EVTLST_ACTIVE=0,EVTLST_DATMOD=UTC_TIMESTAMP() WHERE ID_EVT=:idevent AND EVTLST_ACTIVE=1;';
    $params = [[ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ]];    
    return $this->data->executeNonQuery($strSql, $params); 
}

public function check_event_admin(\stdClass $form) : bool {
    $strSql = 'SELECT 1 FROM events_admin WHERE ID_EVT=:idevent AND ID_USR=:iduser AND EVTADM_ACTIVE=1;';
    $params = [[ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ],[ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ]];    
    return ($this->data->readColumn($strSql, $params) === false) ? false : true;
}
        
// Modify Event Description
public function modifyEvent_Desc(\stdClass $form) : bool {
    $strSql = 'UPDATE events SET EVT_TITLE=:title,EVT_DESCL=:descl,EVT_URL=:url,EVT_DATMOD=UTC_TIMESTAMP() WHERE ID_EVT=:idevent AND ID_EVT IN (SELECT ID_EVT FROM events_admin WHERE ID_EVT=events.ID_EVT AND ID_USR=:iduser AND EVTADM_ACTIVE=1) LIMIT 1;';
    $params = [
        [ 'parameter' => ':title', 'value' => $form->title, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':descl', 'value' => $form->descl, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':url', 'value' => $form->url, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ]];    
    return $this->data->executeNonQuery($strSql, $params); 
}

// Modify Event Date
public function modifyEvent_Date(\stdClass $form) : bool {
    $strSql = 'UPDATE events_dates SET EVTDAT_DATBEG=:datebeg,EVTDAT_DATEND=:dateend,ID_ZONEIDBEG=:zoneidbeg,ID_ZONEIDEND=:zoneidend,EVTDAT_ALLDAY=:allday,EVTDAT_DATMOD=UTC_TIMESTAMP() WHERE ID_EVT=:idevent AND ID_EVT IN (SELECT ID_EVT FROM events_admin WHERE ID_EVT=events_dates.ID_EVT AND ID_USR=:iduser AND EVTADM_ACTIVE=1) LIMIT 1;';
    $params = [
        [ 'parameter' => ':datebeg', 'value' => $form->datebeg, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':dateend', 'value' => $form->dateend, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':zoneidbeg', 'value' => $form->zoneidbeg, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':zoneidend', 'value' => $form->zoneidend, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':allday', 'value' => $form->allday, 'type' => PDO::PARAM_INT ],        
        [ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ]];    
    return $this->data->executeNonQuery($strSql, $params); 
}

// Modify Event Location
public function modifyEvent_Venue(\stdClass $form) : bool {
    $locationp = 'POINT(' . $form->lat . ' ' . $form->lon . ')';
    $strSql = 'UPDATE events SET EVT_CITY=:city,EVT_COUNTRY=:country,ID_COUNTRY_CODE=:country_code,EVT_LOCATIONP=ST_PointFromText(:locationp) ,EVT_OSMID=:osmid ,EVT_PCODE=:pcode ,EVT_STATE=:state ,EVT_STREET=:street ,EVT_STREET2=:street2 ,EVT_STREETN=:streetn ,EVT_VENUE=:venue,EVT_DATMOD=UTC_TIMESTAMP() WHERE ID_EVT=:idevent AND ID_EVT IN (SELECT ID_EVT FROM events_admin WHERE ID_EVT=events.ID_EVT AND ID_USR=:iduser AND EVTADM_ACTIVE=1);';
    $params = [
        [ 'parameter' => ':city', 'value' => $form->city, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':country', 'value' => $form->country, 'type' => PDO::PARAM_STR ],        
        [ 'parameter' => ':country_code', 'value' => $form->country_code, 'type' => PDO::PARAM_INT ],        
        [ 'parameter' => ':locationp', 'value' => $locationp, 'type' => PDO::PARAM_STR ],                
        [ 'parameter' => ':osmid', 'value' => $form->osmid, 'type' => PDO::PARAM_INT ],        
        [ 'parameter' => ':pcode', 'value' => $form->pcode, 'type' => PDO::PARAM_STR ],        
        [ 'parameter' => ':state', 'value' => $form->state, 'type' => PDO::PARAM_STR ],        
        [ 'parameter' => ':street', 'value' => $form->street, 'type' => PDO::PARAM_STR ],        
        [ 'parameter' => ':street2', 'value' => $form->street2, 'type' => PDO::PARAM_STR ],        
        [ 'parameter' => ':streetn', 'value' => $form->streetn, 'type' => PDO::PARAM_STR ],        
        [ 'parameter' => ':venue', 'value' => $form->venue, 'type' => PDO::PARAM_STR ],                        
        [ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ]];    
    return $this->data->executeNonQuery($strSql, $params); 
}

//function isTag_event_tags(\stdClass $form) : bool {       
//    $strSql = 'SELECT 1 FROM events_tags WHERE ID_EVT=:idevent AND ID_TAG=:idtag';         
//    $params = [[ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ],[ 'parameter' => ':idtag', 'value' => $form->idtag, 'type' => PDO::PARAM_INT ]];
//    $row = $this->data->readColumn($strSql, $params);    
//    return ($row === false) ?  false : true;
//}
//
//// Desactivate Event Tags
//function activate_event_tags(\stdClass $form) : bool {    
//    $strSql = 'UPDATE events_tags SET EVTTAG_ACTIVE=1,EVTTAG_DATMOD=UTC_TIMESTAMP() WHERE ID_EVT=:idevent AND ID_TAG=:idtag AND EVTTAG_ACTIVE=0 AND ID_EVT IN (SELECT ID_EVT FROM events_admin WHERE ID_EVT=:idevent AND ID_USR=:iduser AND ID_EVTROLE=1 AND EVTLST_ACTIVE=1) LIMIT 1;';
//    $params = [        
//        [ 'parameter' => ':idtag', 'value' => $form->idtag, 'type' => PDO::PARAM_INT ],        
//        [ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ],
//        [ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ]];    
//    return $this->data->executeNonQuery($strSql, $params); 
//}

// Desactivate Event Tags
public function desactivate_event_tags(\stdClass $form) : bool {
    $strSql = 'UPDATE events_tags SET EVTTAG_ACTIVE=0,EVTTAG_DATMOD=UTC_TIMESTAMP() WHERE ID_EVT=:idevent AND ID_TAG=:idtag AND EVTTAG_ACTIVE=1 AND ID_EVT IN (SELECT ID_EVT FROM events_admin WHERE ID_EVT=:idevent AND ID_USR=:iduser AND EVTADM_ACTIVE=1) LIMIT 1;';
    $params = [        
        [ 'parameter' => ':idtag', 'value' => $form->idtag, 'type' => PDO::PARAM_INT ],        
        [ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ]];    
    return $this->data->executeNonQuery($strSql, $params); 
}

public function getTagsEvent(\stdClass $form) : array {
    $return_data = array();         
    $strSql = 'SELECT ID_TAG FROM events_tags WHERE ID_EVT=:idevent AND EVTTAG_ACTIVE=1 order by ID_TAG'; // LIMIT 10         
    $params = [[ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ]];
    $rows = $this->data->readAllObject($strSql, $params);    
    if (count($rows) < 1) return $return_data;                        
    //exit(var_dump($rows));        
    foreach ($rows as $obj) {
        if (is_null($obj)) continue;   
        $return_data[]  = (int)($obj->ID_TAG);
    } 
    return $return_data;
}

public function getDashBoard(\stdClass $form) : array {
    $return_data = array();

    try {

        if ($form->past === false) {
            $strSql = 'SELECT DISTINCT e.* '
                    . ',(SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATBEG '
                    . ',(SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATEND '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDBEG ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_BEG '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDEND ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_END '
                    . ',(SELECT EVTDAT_ALLDAY FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_ALLDAY '
                    . 'FROM events e  '
                    . ' INNER JOIN events_list l ON l.ID_EVT=e.ID_EVT AND l.EVTLST_ACTIVE=1 AND l.ID_USR=:iduser  '
                    //. ' INNER JOIN events_admin a ON a.ID_EVT=e.ID_EVT AND a.EVTADM_ACTIVE=1 AND a.ID_USR=:iduser  '
                    . ' WHERE '
                    //. ' e.ID_EVT=l.ID_EVT '
                    //. 'AND l.EVTLST_ACTIVE=1 '
                    //. 'AND l.ID_USR=:iduser  '
                    //. 'AND a.EVTLST_ACTIVE=1 '
                    //. 'AND a.ID_USR=:iduser  '
                    . '  ( (DATE((SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1)) >= CURDATE() AND (SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) IS NULL ) '
                    . 'OR  ((CURDATE() <= DATE((SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1))))) '

                    . ' UNION SELECT DISTINCT e.* '
                    . ',(SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATBEG '
                    . ',(SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATEND '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDBEG ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_BEG '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDEND ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_END '
                     . ',(SELECT EVTDAT_ALLDAY FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_ALLDAY '
                     . 'FROM events e  '
                     //. ' INNER JOIN events_admin l ON l.ID_EVT=e.ID_EVT AND l.EVTLST_ACTIVE=1 AND l.ID_USR=:iduser  '
                     . ' INNER JOIN events_admin a ON a.ID_EVT=e.ID_EVT AND a.EVTADM_ACTIVE=1 AND a.ID_USR=:iduser  '
                     . ' WHERE '
                     //. ' e.ID_EVT=l.ID_EVT '
                     //. 'AND l.EVTLST_ACTIVE=1 '
                     //. 'AND l.ID_USR=:iduser  '
                     //. 'AND a.EVTLST_ACTIVE=1 '
                     //. 'AND a.ID_USR=:iduser  '
                     . '  ( (DATE((SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1)) >= CURDATE() AND (SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) IS NULL ) '
                     . 'OR  ((CURDATE() <= DATE((SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1))))) '
                    . 'order by EVTDAT_DATBEG ASC LIMIT :limit OFFSET :offset;';


        } else {
            $strSql = 'SELECT DISTINCT e.* '
                    . ',(SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATBEG '
                    . ',(SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATEND '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDBEG ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_BEG '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDEND ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_END '
                    . ',(SELECT EVTDAT_ALLDAY FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_ALLDAY '
                   . ' FROM events e, events_list l WHERE '
                   . 'e.ID_EVT=l.ID_EVT '
                   . 'AND l.EVTLST_ACTIVE=1 '
                   . 'AND l.ID_USR=:iduser '
                   . 'AND  ( (DATE((SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1)) < CURDATE() AND (SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) IS NULL ) '
                   . 'OR  ((CURDATE() > DATE((SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1))))) '
                   . 'order by EVTDAT_DATBEG DESC LIMIT :limit OFFSET :offset;';
        }

        $params = [
            [ 'parameter' => 'iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ],
            [ 'parameter' => 'limit', 'value' => $form->limit, 'type' => PDO::PARAM_INT ],
            [ 'parameter' => 'offset', 'value' => $form->offset, 'type' => PDO::PARAM_INT ]
            ];

        $rows = $this->data->readAllObject($strSql, $params);

        if (empty($rows)) {
            return $return_data;
        }

        //exit(var_dump($rows));        
        foreach ($rows as $obj) {

            if (empty($obj)) {
                continue;
            }


            // Date Begin
            $datebegin_iso8601 = null;
            if (! is_null($obj->EVTDAT_DATBEG)) {
                $dt = new \DateTime($obj->EVTDAT_DATBEG); // '2010-12-30 23:21:46'
                $dt->setTimezone(new \DateTimeZone($obj->TIMEZONE_BEG)); // 'UTC'
                $datebegin_iso8601 = $dt->format('c'); //  Y-m-d\TH:i:s.u\Z
            }

            $row['data']['DATBEG'] = $datebegin_iso8601;
            $row['data']['TIMEZONE_BEG'] = empty($obj->TIMEZONE_BEG) ? '' : $obj->TIMEZONE_BEG;
            
            // --------------------------------------------------------------------------------

            // Date End
            $dateend_iso8601 = null;
            if (! is_null($obj->EVTDAT_DATEND)) {
                $dt2 = new \DateTime($obj->EVTDAT_DATEND); // '2010-12-30 23:21:46'
                $dt2->setTimezone(new \DateTimeZone($obj->TIMEZONE_END)); // 'UTC'
                $dateend_iso8601 = $dt2->format('c'); // Y-m-d\TH:i:s.u\Z
            }

            $row['data']['DATEND'] = $dateend_iso8601 ;
            $row['data']['TIMEZONE_END'] = empty($obj->TIMEZONE_END) ? '' : $obj->TIMEZONE_END;

            // --------------------------------------------------------------------------------

            $row['data']['ALLDAY'] = $obj->EVTDAT_ALLDAY;
            $row['data']['TITLE'] = $obj->EVT_TITLE;
            $row['data']['EVTVIS'] = $obj->ID_EVTVIS;
            $row['data']['PICTURE'] = $obj->EVT_PICTURE;
            $row['data']['PICTURE_MIN'] = empty($obj->EVT_PICTURE_MIN) ? '' :  $obj->EVT_PICTURE_MIN;
            $row['data']['URLLINK'] = $obj->EVT_URLLINK;
            $row['data']['ONLINE'] = $obj->EVT_ONLINE;
            $return_data[]  = $row['data'];                    
        } 

    } catch (\Error $e) {
        error_log('EventsUtils >> getDashBoard : ' . $e);
    }    
      
    return $return_data;
}

public function getCalendar(\stdClass $form) : array {
    $return_data = array();

    try {

        if ($form->past === false) {
            $strSql = 'SELECT DISTINCT e.* '
                    . ',(SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATBEG '
                    . ',(SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATEND '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDBEG ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_BEG '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDEND ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_END '
                    . ',(SELECT EVTDAT_ALLDAY FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_ALLDAY '
                    . 'FROM events e  '
                    . ' INNER JOIN events_list l ON l.ID_EVT=e.ID_EVT AND l.EVTLST_ACTIVE=1 AND l.ID_USR=:iduser  '
                    //. ' INNER JOIN events_admin a ON a.ID_EVT=e.ID_EVT AND a.EVTADM_ACTIVE=1 AND a.ID_USR=:iduser  '
                    . ' WHERE '
                    //. ' e.ID_EVT=l.ID_EVT '
                    //. 'AND l.EVTLST_ACTIVE=1 '
                    //. 'AND l.ID_USR=:iduser  '
                    //. 'AND a.EVTLST_ACTIVE=1 '
                    //. 'AND a.ID_USR=:iduser  '
                    . '  ( (DATE((SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1)) >= CURDATE() AND (SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) IS NULL ) '
                    . 'OR  ((CURDATE() <= DATE((SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1))))) '

                    . ' UNION SELECT DISTINCT e.* '
                    . ',(SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATBEG '
                    . ',(SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATEND '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDBEG ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_BEG '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDEND ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_END '
                     . ',(SELECT EVTDAT_ALLDAY FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_ALLDAY '
                     . 'FROM events e  '
                     //. ' INNER JOIN events_admin l ON l.ID_EVT=e.ID_EVT AND l.EVTLST_ACTIVE=1 AND l.ID_USR=:iduser  '
                     . ' INNER JOIN events_admin a ON a.ID_EVT=e.ID_EVT AND a.EVTADM_ACTIVE=1 AND a.ID_USR=:iduser  '
                     . ' WHERE '
                     //. ' e.ID_EVT=l.ID_EVT '
                     //. 'AND l.EVTLST_ACTIVE=1 '
                     //. 'AND l.ID_USR=:iduser  '
                     //. 'AND a.EVTLST_ACTIVE=1 '
                     //. 'AND a.ID_USR=:iduser  '
                     . '  ( (DATE((SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1)) >= CURDATE() AND (SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) IS NULL ) '
                     . 'OR  ((CURDATE() <= DATE((SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1))))) '
                    . 'order by EVTDAT_DATBEG ASC LIMIT :limit OFFSET :offset;';


        } else {
            $strSql = 'SELECT DISTINCT e.* '
                    . ',(SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATBEG '
                    . ',(SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATEND '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDBEG ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_BEG '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDEND ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_END '
                    . ',(SELECT EVTDAT_ALLDAY FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_ALLDAY '
                   . ' FROM events e, events_list l WHERE '
                   . 'e.ID_EVT=l.ID_EVT '
                   . 'AND l.EVTLST_ACTIVE=1 '
                   . 'AND l.ID_USR=:iduser '
                   . 'AND  ( (DATE((SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1)) < CURDATE() AND (SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) IS NULL ) '
                   . 'OR  ((CURDATE() > DATE((SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1))))) '
                   . 'order by EVTDAT_DATBEG DESC LIMIT :limit OFFSET :offset;';
        }

        $params = [
            [ 'parameter' => 'iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ],
            [ 'parameter' => 'limit', 'value' => $form->limit, 'type' => PDO::PARAM_INT ],
            [ 'parameter' => 'offset', 'value' => $form->offset, 'type' => PDO::PARAM_INT ]
            ];

        $rows = $this->data->readAllObject($strSql, $params);

        if (empty($rows)) {
            return $return_data;
        }

        //exit(var_dump($form));        
        foreach ($rows as $obj) {

            if (empty($obj)) {
                continue;
            }

            $row['data']['DATBEG'] = $obj->EVTDAT_DATBEG;
            $row['data']['DATEND'] = $obj->EVTDAT_DATEND;
            $row['data']['ALLDAY'] = $obj->EVTDAT_ALLDAY;
            $row['data']['TIMEZONE_BEG'] = $obj->TIMEZONE_BEG;
            $row['data']['TIMEZONE_END'] = empty($obj->TIMEZONE_END) ? '' : $obj->TIMEZONE_END;
            $row['data']['TITLE'] = $obj->EVT_TITLE;
            $row['data']['EVTVIS'] = $obj->ID_EVTVIS;
            $row['data']['PICTURE'] = $obj->EVT_PICTURE;
            $row['data']['URLLINK'] = $obj->EVT_URLLINK;
            $row['data']['ONLINE'] = $obj->EVT_ONLINE;
            $return_data[]  = $row['data'];                    
        } 
    } catch (\Error $e) {
        error_log('EventsUtils >> getCalendar : ' . $e);
    }    
      
    return $return_data;
}

public function getCalendarFeed(array $parameters) : array {
    $return_data = array();     
    try {     
        $strSql = 'SELECT DISTINCT e.EVT_TITLE,DATE(EVT_DATBEG)DATBEG,DATE(EVT_DATEND) DATEND FROM events e, events_list l WHERE e.ID_EVT=l.ID_EVT AND l.EVTLST_ACTIVE=1 AND e.EVT_DATBEG >= ? AND (e.EVT_DATEND <= ? OR e.EVT_DATEND IS NULL) AND l.ID_USR=? order by e.EVT_DATBEG LIMIT 50';                
        $params = [
            [ 'parameter' => 1, 'value' => $parameters['start'], 'type' => PDO::PARAM_STR ],
            [ 'parameter' => 2, 'value' => $parameters['end'], 'type' => PDO::PARAM_STR ],
            [ 'parameter' => 3, 'value' => $parameters['iduser'], 'type' => PDO::PARAM_INT ]                
        ];
        $rows = $this->data->readAllObject($strSql, $params);    
        if (count($rows) < 1) {
            return $return_data;
        }                
        //exit(var_dump($rows));        
        foreach ($rows as $obj) {
            if (empty($obj)) continue;   
            $row['data']['title'] =  $obj->EVT_TITLE;
            $row['data']['start'] =  $obj->DATBEG;
            $row['data']['end'] =  $obj->DATEND;
            $return_data[]  = $row['data'];            
        } 
        
    } catch (\Error $ex) {
       error_log('EventsUtils >> getCalendarFeed : $ex');
    }        
    return $return_data;
}

/**
 * Filter events from Channel
 * @param \stdClass $form
 * @return array
 * @throws \Moviao\Database\Exception\DBException
 */
public function getFilter(\stdClass $form) : array {
    $return_data = array();

    try {

        // AND e.EVT_ONLINE=1
        // AND ID_USR=:iduser


        if ($form->past === false) {
            $strSql = 'SELECT e.* '
                    . ',(SELECT EVTLST_CONFIRM FROM events_list WHERE ID_EVT=e.ID_EVT AND EVTLST_ACTIVE=1 LIMIT 1) SUBSCRIPTION '
                    . ',(SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATBEG '
                    . ',(SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATEND '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDBEG LIMIT 1) TIMEZONE_BEG '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDEND LIMIT 1) TIMEZONE_END '
                    . ',(SELECT EVTDAT_ALLDAY FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1) EVTDAT_ALLDAY '
                    . ' FROM events e WHERE '
                    . 'e.EVT_ACTIVE=1 AND e.ID_EVTVIS=1 AND e.ID_CHA=:idcha '
                    . 'AND  ( (DATE((SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1)) >= CURDATE() AND (SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) IS NULL ) '
                    . 'OR  ((CURDATE() <= DATE((SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1))))) '
                    . 'order by EVTDAT_DATBEG ASC LIMIT :limit OFFSET :offset;';

        } else {
            $strSql = 'SELECT e.* '
                    . ',(SELECT EVTLST_CONFIRM FROM events_list WHERE ID_EVT=e.ID_EVT AND EVTLST_ACTIVE=1 LIMIT 1) SUBSCRIPTION '
                    . ',(SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATBEG '
                    . ',(SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATEND '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDBEG LIMIT 1) TIMEZONE_BEG '
                    . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDEND LIMIT 1) TIMEZONE_END '
                    . ',(SELECT EVTDAT_ALLDAY FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1) EVTDAT_ALLDAY '
                    . ' FROM events e WHERE '
                    . ' e.EVT_ACTIVE=1 AND e.ID_EVTVIS=1 AND e.ID_CHA=:idcha '
                    . 'AND  ( (DATE((SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1)) < CURDATE() AND (SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) IS NULL ) '
                    . 'OR  ((CURDATE() > DATE((SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1))))) '
                    . ' order by EVTDAT_DATBEG DESC LIMIT :limit OFFSET :offset;';        }

        $params = [
//            [ 'parameter' => 'iduser', 'value' => $form->IDUSER, 'type' => PDO::PARAM_INT ],
            [ 'parameter' => 'idcha', 'value' => $form->IDCHA, 'type' => PDO::PARAM_INT ],
            [ 'parameter' => 'limit', 'value' => $form->limit, 'type' => PDO::PARAM_INT ],
            [ 'parameter' => 'offset', 'value' => $form->offset, 'type' => PDO::PARAM_INT ]

        ];

        $rows = $this->data->readAllObject($strSql, $params);
        //exit(var_dump($rows));

        if (empty($rows)) {
            return $return_data;
        }

        //exit(var_dump($rows));
        foreach ($rows as $obj) {
            if (empty($obj)) continue;

            $datbegin_timestamp = 0;
            $datend_timestamp = 0;
            //$datevent_formatted = null;

            // Date Begin
            if ($obj->EVTDAT_DATBEG !== null && mb_strlen($obj->EVTDAT_DATBEG) > 0) {
                $date = new DateTime($obj->EVTDAT_DATBEG, new \DateTimeZone($obj->TIMEZONE_BEG));
                $datbegin = $date->getTimestamp();
                $datbegin_timestamp = $datbegin * 1000;
            }

            // Date End
            if ($obj->EVTDAT_DATEND !== null && strlen($obj->EVTDAT_DATEND) > 0) {
                $date = new DateTime($obj->EVTDAT_DATEND, new \DateTimeZone($obj->TIMEZONE_END));
                $datbegin = $date->getTimestamp();
                $datend_timestamp = $datbegin * 1000;
            }

            $row['data']['TITLE'] = $obj->EVT_TITLE;
            $row['data']['DATBEG'] = $datbegin_timestamp;
            $row['data']['DATEND'] = $datend_timestamp;
            $row['data']['ALLDAY'] = $obj->EVTDAT_ALLDAY;
            $row['data']['TIMEZONE_BEG'] = $obj->TIMEZONE_BEG;
            $row['data']['TIMEZONE_END'] = empty($obj->TIMEZONE_END) ? '' : $obj->TIMEZONE_END;
            $row['data']['URLLINK'] = $obj->EVT_URLLINK;
            $row['data']['PICTURE_MIN'] = empty($obj->EVT_PICTURE_MIN) ? '' :  $obj->EVT_PICTURE_MIN;
            $row['data']['ONLINE'] =  $obj->EVT_ONLINE;
            $row['data']['SUBSCRIPTION'] = $obj->SUBSCRIPTION;
            $row['data']['CITY'] = $obj->EVT_CITY;
            $return_data[]  = $row['data'];
        }

    } catch (\Error $e) {
        $return_data = null;
        error_log('EventsUtils >> getFilter : ' . $e);
    }
    
    return $return_data;
}

public function updateBackgroundImage(string $event_name, string $iduser, string $picture_loc, ?string $picture_loc_mini) : bool {
    $strSql = 'UPDATE events SET EVT_PICTURE=?,EVT_PICTURE_MIN=?,EVT_DATMOD=UTC_TIMESTAMP() WHERE UPPER(EVT_URLLINK)=UPPER(?) AND ID_EVT IN (SELECT ID_EVT FROM events_admin WHERE ID_EVT=events.ID_EVT AND ID_USR=? AND EVTADM_ACTIVE=1);';
    $params = [        
        [ 'parameter' => 1, 'value' => $picture_loc, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 2, 'value' => $picture_loc_mini, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 3, 'value' => $event_name, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 4, 'value' => $iduser, 'type' => PDO::PARAM_STR ]];
    return $this->data->executeNonQuery($strSql, $params);
}

// TODO: Modify this to point to database
public function getTimeZone(int $id_timezone) : string {
    $result = null;
    if ($id_timezone == 52) {
        $result = 'Europe/Brussels';
    } else if ($id_timezone == 146) {
        $result = 'Europe/Madrid';
    } else if ($id_timezone == 147) {
        $result = 'Africa/Ceuta';
    } else if ($id_timezone == 148) {
        $result = 'Atlantic/Canary';
    }
    return $result;
}

// Get Tags in relation with a event
public function getTags(int $eventID,string $lang) : array {
    $return_data = array();

    if ($lang === 'FR') {
        $desc_lang = 'TAG_DESC_FR';
    } elseif ($lang === 'ES') {
        $desc_lang = 'TAG_DESC_ES';
    } else {
        $desc_lang = 'TAG_DESC_EN';
    }

    $strSql = 'SELECT t.ID_TAG,t.' . $desc_lang . ' DES_TAG FROM tags t,events_tags e WHERE t.ID_TAG=e.ID_TAG AND e.ID_EVT=? AND e.EVTTAG_ACTIVE=1;';
    $params = [[ 'parameter' => 1, 'value' => $eventID, 'type' => PDO::PARAM_INT ]];
    $rows = $this->data->readAllObject($strSql, $params);    
    if (empty($rows)) {
        return $return_data;
    }
    foreach ($rows as $obj) {
        if (empty($obj)) break;                    
        $row['data']['TAG'] = $obj->ID_TAG;
        $row['data']['DESC'] = $obj->DES_TAG;
        $return_data[]  = $row['data'];            
    }      
    return $return_data;
}

// Get All Tags
public function getAllTags() : array {
    $return_data = array();
    $strSql = 'SELECT ID_TAG,TAG_DESC_FR FROM tags WHERE TAG_ACTIVE=1 ORDER BY TAG_DESC_FR;';
    $params = [];
    $rows = $this->data->readAllObject($strSql, $params);
    if (count($rows) < 1) return $return_data;
    foreach ($rows as $obj) {
        if (empty($obj)) break;
        $row['data']['TAG'] = $obj->ID_TAG;
        $row['data']['DESC'] = $obj->TAG_DESC_FR;
        $return_data[]  = $row['data'];
    }
    return $return_data;
}

/**
 * Get Events Dates Active (For admin only)
 * @param \stdClass $form
 * @return array
 * @throws \Moviao\Database\Exception\DBException
 */
public function getEventDates(\stdClass $form) : array {
    $return_data = array();
    $strSql = 'SELECT ed.*,(SELECT zone_name FROM zone WHERE zone_id=ed.ID_ZONEIDBEG) TIMEZONE_BEG,(SELECT zone_name FROM zone WHERE zone_id=ed.ID_ZONEIDEND) TIMEZONE_END FROM events_dates ed WHERE ed.ID_EVT=:idevent AND ed.EVTDAT_ACTIVE=1 ORDER BY ed.EVTDAT_DATBEG';
    $params = [[ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ]];
    $rows = $this->data->readAllObject($strSql, $params);
    
    if (empty($rows)) {
        return $return_data;
    }

    foreach ($rows as $obj) {
        if (is_null($obj)) {
            continue;
        }

        $allday = false;
        $datevent_formatted = null;

        // Date Formatted
        if ($obj->EVTDAT_DATBEG !== null) {
            $date_start = new DateTime($obj->EVTDAT_DATBEG,new \DateTimeZone('UTC'));
            $date_start->setTimezone(new \DateTimeZone($obj->TIMEZONE_BEG));

            $date_end = null;
            if ($obj->EVTDAT_DATEND !== null) {
                $date_end = new DateTime($obj->EVTDAT_DATEND,new \DateTimeZone('UTC'));
                $date_end->setTimezone(new \DateTimeZone($obj->TIMEZONE_END));
            }

            $dateformat = new \Moviao\Util\DateTimeFormat();
            if ((isset($obj->EVT_ALLDAY)) && $obj->EVT_ALLDAY === '1') {
                $allday = true;
            }

            if (isset($form->lang)) {
                $lang = $form->lang ?? 'en-GB';
            } else {
                $lang = 'en-GB';
            }
            
            $datevent_formatted = $dateformat->formatDate($date_start,$date_end,$lang ,$allday,true);
        }

        $row['data']['DATBEG'] = $obj->EVTDAT_DATBEG;
        $row['data']['TIMEZONE_BEG'] = $obj->TIMEZONE_BEG;
        $row['data']['DATFORMATTED'] = $datevent_formatted;

        $return_data[] = $row['data'];
    }
    return $return_data;
}




/**
 * Get All Events for a Admin
 * @param stdClass $form
 * @return array
 */
public function getEventsDashboard(\stdClass $form) : array {
    $return_data = array();

    $strSql = 'SELECT DISTINCT e.*,ed.EVTDAT_DATBEG, ed.EVTDAT_DATEND, ed.EVTDAT_ALLDAY, ed.EVTDAT_TOKEN, ed.EVTDAT_ONLINE '
        . ',(SELECT zone_name FROM zone WHERE zone_id=ed.ID_ZONEIDBEG LIMIT 1) TIMEZONE_BEG '
        . ',(SELECT zone_name FROM zone WHERE zone_id=ed.ID_ZONEIDEND LIMIT 1) TIMEZONE_END '
        . 'FROM events e, events_admin ea, events_dates ed '
        . 'WHERE e.ID_EVT=ea.ID_EVT AND e.ID_EVT=ed.ID_EVT AND e.EVT_ACTIVE=1 AND ed.EVTDAT_DATBEG >= DATE_ADD(NOW(), INTERVAL - 1 DAY) AND ea.ID_USR=:iduser ORDER BY EVTDAT_DATBEG;';
    $params = [[ 'parameter' => 'iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ]];
    $rows = $this->data->readAllObject($strSql, $params);

    if (empty($rows)) {
        return $return_data;
    }

    foreach ($rows as $obj) {

        if (empty($obj)) {
            break;
        }

        // Date Begin
        $datebegin_iso8601 = null;
        if (! is_null($obj->EVTDAT_DATBEG)) {
            $dt = new \DateTime($obj->EVTDAT_DATBEG); // '2010-12-30 23:21:46'
            $dt->setTimezone(new \DateTimeZone($obj->TIMEZONE_BEG)); // 'UTC'
            $datebegin_iso8601 = $dt->format('c'); //  Y-m-d\TH:i:s.u\Z
        }

        $row['data']['DATBEG'] = $datebegin_iso8601;
        $row['data']['TIMEZONE_BEG'] = empty($obj->TIMEZONE_BEG) ? '' : $obj->TIMEZONE_BEG;
        
        // --------------------------------------------------------------------------------

        // Date End
        $dateend_iso8601 = null;
        if (! is_null($obj->EVTDAT_DATEND)) {
            $dt2 = new \DateTime($obj->EVTDAT_DATEND); // '2010-12-30 23:21:46'
            $dt2->setTimezone(new \DateTimeZone($obj->TIMEZONE_END)); // 'UTC'
            $dateend_iso8601 = $dt2->format('c'); // Y-m-d\TH:i:s.u\Z
        }

        $row['data']['DATEND'] = $dateend_iso8601 ;
        $row['data']['TIMEZONE_END'] = empty($obj->TIMEZONE_END) ? '' : $obj->TIMEZONE_END;

        // --------------------------------------------------------------------------------

        //exit(var_dump($qte_available !== null ));
        $row['data']['ID'] =  $obj->ID_EVT;
        $row['data']['TITLE'] = $obj->EVT_TITLE;
        $row['data']['ALLDAY'] = $obj->EVTDAT_ALLDAY;
        $row['data']['TOKEN'] =  $obj->EVTDAT_TOKEN;        
        $row['data']['EVTVIS'] = $obj->ID_EVTVIS;
        $row['data']['PICTURE'] = $obj->EVT_PICTURE;
        $row['data']['PICTURE_MIN'] = empty($obj->EVT_PICTURE_MIN) ? '' :  $obj->EVT_PICTURE_MIN;
        $row['data']['URLLINK'] = $obj->EVT_URLLINK;
        $row['data']['ONLINE'] = $obj->EVT_ONLINE;

//        $row['data']['QTE_AVAILABLE'] = 0;
//        $row['data']['PRICE'] =  $obj->TICTYPE_PRICE;
//        $row['data']['SALE_START'] =  $obj->TICTYPE_SALE_START;
//        $row['data']['SALE_END'] =  $obj->TICTYPE_SALE_END;

        $return_data[]  = $row['data'];
    }

    return $return_data;
}

public function isTokenEventDateExist(string $token) : bool {
    $strSql = 'SELECT 1 FROM events_dates WHERE EVTDAT_TOKEN=? AND EVTDAT_ACTIVE=1 ORDER BY EVTDAT_DATINS DESC LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $token, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row === 1) return true;
    return false;
}


public function isEventAttendFreeSpace(int $idevent, string $datbeg) : bool {
    $strSql = 'SELECT 1 FROM DUAL WHERE (select EVT_MAXUSE from events where ID_EVT=:idevent AND EVT_ACTIVE=1) > IFNULL((SELECT COUNT(*) FROM events_list WHERE ID_EVT=:idevent AND EVTLST_DATBEG=:datbeg AND EVTLST_ACTIVE=1), 0) LIMIT 1;';
    $params = [
        [ 'parameter' => 'idevent', 'value' => $idevent, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => 'datbeg', 'value' => $datbeg, 'type' => PDO::PARAM_STR ]
    ];
    $row = $this->data->readColumn($strSql, $params);    
    return ($row === 1) ? true : false;
}

}