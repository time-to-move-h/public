<?php
declare(strict_types=1);
namespace Moviao\Data;
use Moviao\Data\CommonData;
use stdClass;

class TicketsCommon extends CommonData {

/**
 * Create Ticket
 * @param stdClass $form
 * @return array
 */
public function create(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;
    $array = array();
    $urllink = null;

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->DATBEG) || empty($form->NAME) || empty($form->QTE) || empty($form->MINQTE) || ! isset($form->MAXQTE) || ! isset($form->PRICE) || ! is_string($form->NAME) || ! ctype_digit($form->QTE)  || ! ctype_digit($form->MINQTE) || ! ctype_digit($form->MAXQTE) || ! is_string($form->PRICE)) {
            return array("result" => false, "code" => 666);
        }

        //exit(var_dump($form));

        // Csrf Protection
//        $csrf = new \Moviao\Security\CSRF_Protect();
//        if (empty($form->_csrf) || $csrf->verifyRequest($form->_csrf) !== true) {
//            return array("result" => false,"code" => 999);
//        }

        $uid = mb_substr(filter_var($form->UID,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,60);
        $form->DATBEG = mb_substr($form->DATBEG, 0, 16);
        $form->NAME = strip_tags(mb_substr($form->NAME, 0, 250));
        $form->QTE = filter_var($form->QTE, FILTER_SANITIZE_NUMBER_INT);
        $form->MINQTE = filter_var($form->MINQTE, FILTER_SANITIZE_NUMBER_INT);
        $form->MAXQTE = filter_var($form->MAXQTE, FILTER_SANITIZE_NUMBER_INT);
        $form->PRICE  = filter_var(str_replace(',','.', $form->PRICE), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        //exit(var_dump($form->PRICE));

        //---------------------------------------------------------------------------------------
        if (isset($form->DESCL) && mb_strlen($form->DESCL) > 4000) {
            $form->DESCL = mb_substr($form->DESCL, 0, 4000);
        }
        // Filter balises HTML5
        $form->DESCL = strip_tags($form->DESCL); //self::strip_html
        // Filter html xss
        //$filter = new \Moviao\Http\HTML_Sanitizer;
        //$allowed_protocols = array('http');
        //$allowed_tags = self::strip_html_array;
        //$filter->addAllowedProtocols($allowed_protocols);
        //$filter->addAllowedTags($allowed_tags);
        //$form->DESCL = $filter->xss($form->DESCL);
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

        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);
        if ($eventID <= 0) {
            return array("result" => false,"code" => 78541236);
        }
        //--------------------------------------------------------------------
        
        // Create Ticket
        $ticket_utils = new \Moviao\Data\Util\TicketsUtils($this);
        $arr = $ticket_utils->create($form);
        $bresult = $arr->result;
        $ID_TICKET_TYPE = $arr->lastid; // Last ID
        if ($bresult !== true) {
            parent::setError(54125987);
        }
        //--------------------------------------------------------------------

        // Create Ticket Link
        if ($bresult === true) {
            $ticket_detail = new \stdClass();
            $ticket_detail->idevent = $eventID;
            $ticket_detail->iduser = $IDUSER;
            $ticket_detail->idticket = $ID_TICKET_TYPE;
            $ticket_detail->datbeg = $form->DATBEG;

            $bresult = $ticket_utils->create_ticket_event($ticket_detail);
            if ($bresult !== true) {
                parent::setError(63258741);
            }
        }
        //--------------------------------------------------------------------

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('TicketsCommon (DBException) >> create : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('TicketsCommon >> create : ' . $e);
    } finally {
        if ($bresult === true) {
            if ($data != null) $data->commitTransaction();
        } else {
            if ($data != null) $data->rollbackTransaction();
        }
    }

    if ($bresult === true) {
        $array = array("result" => $bresult);
    } else {
        $array = array("result" => $bresult,"code" => parent::getError());
    }

    return $array;
}

/**
 * Delete Ticket
 * @param stdClass $form
 * @return array
 */
public function delete(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;
    $array = array();
    $urllink = null;

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->UID) || empty($form->ID) || ! is_string($form->UID) || ! ctype_digit($form->ID)) {
            return array("result" => false, "code" => 666);
        }

        // Csrf Protection
//        $csrf = new \Moviao\Security\CSRF_Protect();
//        if (empty($form->_csrf) || $csrf->verifyRequest($form->_csrf) !== true) {
//            return array("result" => false,"code" => 999);
//        }

        $uid = mb_substr(filter_var($form->UID,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,60);
        $ID_TICKET_TYPE = filter_var($form->ID, FILTER_SANITIZE_NUMBER_INT);

        //exit(var_dump($locationp));
        $data = parent::getDBConn();
        $IDUSER = parent::getSession()->getIDUSER();
        //--------------------------------------------------------------------------
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();

        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);
        if ($eventID <= 0) {
            return array("result" => false,"code" => 52145632);
        }
        //--------------------------------------------------------------------
        // Delete Ticket Link
        $ticket_utils = new \Moviao\Data\Util\TicketsUtils($this);
        $ticket_detail = new \stdClass();
        $ticket_detail->idevent = $eventID;
        $ticket_detail->iduser = intval($IDUSER);
        $ticket_detail->idticket = intval($ID_TICKET_TYPE);
        $bresult = $ticket_utils->delete($ticket_detail);

        if ($bresult !== true) {
            parent::setError(845212545);
            error_log('TicketsCommon >> delete : Error deleting ticket !');
            //exit(var_dump($ticket_detail));
        }
        //--------------------------------------------------------------------

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('TicketsCommon (DBException) >> delete : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('TicketsCommon >> delete : ' . $e);
    } finally {
        if ($bresult === true) {
            if ($data != null) $data->commitTransaction();
        } else {
            if ($data != null) $data->rollbackTransaction();
        }
    }

    if ($bresult === true) {
        $array = array("result" => $bresult);
    } else {
        $array = array("result" => $bresult,"code" => parent::getError());
    }

    return $array;
}

/**
 * Modify Ticket
 * @param stdClass $form
 * @return array
 */
public function modify(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;
    $array = array();
    $urllink = null;
    $data = null;

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->NAME) || empty($form->DESCL) || empty($form->QTE) || empty($form->MINQTE) || empty($form->MAXQTE) || empty($form->PRICE) || ! is_string($form->NAME) || ! is_string($form->DESCL) || ! ctype_digit($form->QTE)  || ! ctype_digit($form->MINQTE) || ! ctype_digit($form->MAXQTE) || ! ctype_digit($form->PRICE)) {
            return array("result" => false, "code" => 666);
        }

        // Csrf Protection
//        $csrf = new \Moviao\Security\CSRF_Protect();
//        if (empty($form->_csrf) || $csrf->verifyRequest($form->_csrf) !== true) {
//            return array("result" => false,"code" => 999);
//        }

        $uid = mb_substr(filter_var($form->UID,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,60);
        $idevent = null;

        if (isset($form->ID) && ctype_digit($form->ID)) {
            $idevent = (int) $form->ID;
        }

        if (is_null($idevent)) {
            return array("result" => false, "code" => 667);
        }

        $form->NAME = mb_substr(strip_tags($form->NAME), 0, 250);
        $form->QTE = filter_var($form->QTE, FILTER_SANITIZE_NUMBER_INT);
        $form->MINQTE = filter_var($form->MINQTE, FILTER_SANITIZE_NUMBER_INT);
        $form->MAXQTE = filter_var($form->MAXQTE, FILTER_SANITIZE_NUMBER_INT);
        $form->PRICE  = filter_var($form->PRICE, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        //exit(var_dump($form->PRICE));

        //---------------------------------------------------------------------------------------
        if (mb_strlen($form->DESCL) > 4000) {
            $form->DESCL = mb_substr($form->DESCL, 0, 4000);
        }
        // Filter balises HTML5
        $form->DESCL = strip_tags($form->DESCL); //self::strip_html
        // Filter html xss
        //$filter = new \Moviao\Http\HTML_Sanitizer;
        //$allowed_protocols = array('http');
        //$allowed_tags = self::strip_html_array;
        //$filter->addAllowedProtocols($allowed_protocols);
        //$filter->addAllowedTags($allowed_tags);
        //$form->DESCL = $filter->xss($form->DESCL);
        //exit(var_dump($form->DESCL));
        //---------------------------------------------------------------------------------------

        //exit(var_dump($locationp));
        $data = parent::getDBConn();
        $iduser = parent::getSession()->getIDUSER();
        $form->iduser = $iduser;

        //--------------------------------------------------------------------------
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();

        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);
        if ($eventID <= 0) {
            return array("result" => false,"code" => 78541236);
        }

        //--------------------------------------------------------------------
        // Modify Ticket
        $ticket_utils = new \Moviao\Data\Util\TicketsUtils($this);
        $bresult = $ticket_utils->modify($form);
        if ($bresult !== true) {
            parent::setError(52126851);
        }
        //--------------------------------------------------------------------

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('TicketsCommon (DBException) >> modify : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('TicketsCommon >> modify : ' . $e);
    } finally {
        if ($bresult === true) {
            if ($data != null) {
                $data->commitTransaction();
            }
        } else {
            if ($data != null) {
                $data->rollbackTransaction();
            }
        }
    }

    if ($bresult === true) {
        $array = array("result" => $bresult);
    } else {
        $array = array("result" => $bresult,"code" => parent::getError());
    }

    return $array;
}

/**
  * Get All Tickets From an Event
 * @param stdClass $form
 * @return array
 */
public function getAllTickets(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;
    $urllink = null;
    $return_data = array();

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->UID) || ! is_string($form->UID)) {
            return array("result" => false, "code" => 666);
        }

        $uid = mb_substr(filter_var($form->UID,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,60);
        $data = parent::getDBConn();
        //$IDUSER = parent::getSession()->getIDUSER();
        //--------------------------------------------------------------------------
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);
        unset($event_utils);
        if ($eventID <= 0) {
            return array("result" => false,"code" => 52314587);
        }

        $ticket_detail = new \stdClass();
        $ticket_detail->idevent = $eventID;
        //--------------------------------------------------------------------
        // Create Ticket
        $ticket_utils = new \Moviao\Data\Util\TicketsUtils($this);
        $return_data = $ticket_utils->getAllTickets($ticket_detail);
        if (is_array($return_data)) {
            $bresult = true;
        }
        //--------------------------------------------------------------------

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('TicketsCommon (DBException) >> getAllTickets : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('TicketsCommon >> getAllTickets : ' . $e);
    }

    if ($bresult === true) {
        $array = array("result" => $bresult,"data" => $return_data);
    } else {
        $array = array("result" => $bresult,"code" => parent::getError());
    }

    return $array;
}

/**
 * Get Ticket Detail
 * @param stdClass $form
 * @return array
 */
public function getTicket(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;
    $urllink = null;
    $return_data = array();

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->UID) || ! is_string($form->UID) || ! is_string($form->UID) || ! ctype_digit($form->ID)) {
            return array("result" => false, "code" => 666);
        }

        $uid = mb_substr(filter_var($form->UID,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,60);
        $ID_TICKET_TYPE = filter_var($form->ID, FILTER_SANITIZE_NUMBER_INT);

        $data = parent::getDBConn();
        $IDUSER = parent::getSession()->getIDUSER();
        //--------------------------------------------------------------------------
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        // Get Event ID
        $ticket_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $ticket_utils->getEventID($uid);
        if ($eventID <= 0) {
            return array("result" => false,"code" => 52314587);
        }
        $ticket_detail = new \stdClass();
        $ticket_detail->idevent = $eventID;
        //--------------------------------------------------------------------
        // Create Ticket
        $ticket_utils = new \Moviao\Data\Util\TicketsUtils($this);
        $ticket_detail->idevent = $eventID;
        $ticket_detail->iduser = $IDUSER;
        $ticket_detail->idticket = $ID_TICKET_TYPE;

        $return_data = $ticket_utils->getTicket($ticket_detail);
        if (is_array($return_data)) $bresult = true;
        //--------------------------------------------------------------------

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('TicketsCommon (DBException) >> getTicket : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('TicketsCommon >> getTicket : ' . $e);
    }

    if ($bresult === true) {
        $array = array("result" => $bresult,"data" => $return_data);
    } else {
        $array = array("result" => $bresult,"code" => parent::getError());
    }

    return $array;
}

/**
 * Get My Tickets
 * @param stdClass $form
 * @return array
 */
public function getMyTickets() : array {
    $bresult = false;
    $return_data = array();

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        $data = parent::getDBConn();
        $iduser = parent::getSession()->getIDUSER();
        //--------------------------------------------------------------------------
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $ticket_detail = new \stdClass();
        $ticket_detail->iduser = $iduser;
        //--------------------------------------------------------------------
        $ticket_utils = new \Moviao\Data\Util\TicketsUtils($this);
        $return_data = $ticket_utils->getMyTickets($ticket_detail);
        
        if (is_array($return_data)) {
            $bresult = true;
        }
        //--------------------------------------------------------------------

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('TicketsCommon (DBException) >> getMyTickets : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('TicketsCommon >> getMyTickets : ' . $e);
    }

    if ($bresult === true) {
        $array = array("result" => $bresult,"data" => $return_data);
    } else {
        $array = array("result" => $bresult,"code" => parent::getError());
    }

    return $array;
}

/**
 * Get My Tickets Details
 * @param stdClass $form
 * @return array
 */
public function getMyTicketsDetails(\stdClass $form) : array {
    $bresult = false;
    $return_data = array();

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || ! isset($form->O) || ! ctype_digit($form->O)) {
            return array('result' => false,'code' => 666);
        }

        $data = parent::getDBConn();
        $iduser = parent::getSession()->getIDUSER();
        //--------------------------------------------------------------------------
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $ticket_detail = new \stdClass();
        $ticket_detail->iduser = $iduser;

        $ticket_detail->limit  = 9;
        // Offset
        $offset = intval(filter_var($form->O, FILTER_SANITIZE_NUMBER_INT));
        $offset *= $ticket_detail->limit; // Calcul offset
        $ticket_detail->offset = $offset;

        //--------------------------------------------------------------------
        $ticket_utils = new \Moviao\Data\Util\TicketsUtils($this);
        $return_data = $ticket_utils->getMyTicketsDetails($ticket_detail);

        if (is_array($return_data)) {
            $bresult = true;
        }
        //--------------------------------------------------------------------

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('TicketsCommon (DBException) >> getMyTicketsDetails : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('TicketsCommon >> getMyTicketsDetails : ' . $e);
    }

    if ($bresult === true) {
        $array = array("result" => $bresult,"data" => $return_data);
    } else {
        $array = array("result" => $bresult,"code" => parent::getError());
    }

    return $array;
}

/**
* Update tickets details
* @param stdClass $form
* @return array
*/
public function updateTicketDetails(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;
    $array = array();
    $data = null;

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->TICORDER) || empty($form->TICKETTYPE) || empty($form->TICKETDET) || empty($form->FNAME) || empty($form->LNAME)) {
            return array('result' => false, 'code' => 666);
        }

        //exit(var_dump($form));


//        // Csrf Protection
////        $csrf = new \Moviao\Security\CSRF_Protect();
////        if (empty($form->_csrf) || $csrf->verifyRequest($form->_csrf) !== true) {
////            return array("result" => false,"code" => 999);
////        }

        $data = parent::getDBConn();
        //$IDUSER = parent::getSession()->getIDUSER();

        //--------------------------------------------------------------------------
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }
        $data->startTransaction();

        $ticket_utils = new \Moviao\Data\Util\TicketsUtils($this);

        $i=0;
        foreach ($form->TICKETDET as $ticketdet) {
            $ttype = filter_var($form->TICKETTYPE[$i], FILTER_SANITIZE_NUMBER_INT);
            $fname = mb_substr(strip_tags(filter_var(trim($form->FNAME[$i]), FILTER_SANITIZE_FULL_SPECIAL_CHARS)), 0, 255);
            $lname = mb_substr(strip_tags(filter_var(trim($form->LNAME[$i]), FILTER_SANITIZE_FULL_SPECIAL_CHARS)), 0, 255);
            $email = mb_substr(filter_var(trim($form->EMAIL[$i]), FILTER_SANITIZE_EMAIL), 0, 255);

            // Ignore empty data
            if (empty($fname) || empty($lname) || empty($email)) {
                continue;
            }

            // mb_substr(strip_tags($form->NAME), 0, 250);
            // $form->QTE = filter_var($form->QTE, FILTER_SANITIZE_NUMBER_INT);

            $ticket_detail = new \stdClass();
            $ticket_detail->TICORDER = $form->TICORDER;
            $ticket_detail->TICKETTYPE = $ttype;
            $ticket_detail->TICKETDET = $ticketdet;
            $ticket_detail->FNAME = $fname;
            $ticket_detail->LNAME = $lname;
            $ticket_detail->EMAIL = $email;

            //--------------------------------------------------------------------
            $bresult = $ticket_utils->update_order_detail($ticket_detail);
            if ($bresult !== true) {
                parent::setError(521254852);
                break;
            }
            //--------------------------------------------------------------------
            $i++;
        }

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('TicketsCommon (DBException) >> updateTicketDetails : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('TicketsCommon >> updateTicketDetails : ' . $e);
    } finally {
        if ($bresult === true) {
            if (! empty($data)) $data->commitTransaction();
        } else {
            if (! empty($data)) $data->rollbackTransaction();
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
 * Unlock tickets
 * @param stdClass $form
 * @return array
 */
public function unlockTicket(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;
    $array = array();
    $data = null;

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();
        $sessionid = session_id();

//        if (empty($form)) {
//            return array('result' => false, 'code' => 666);
//        }

//        // Csrf Protection
////        $csrf = new \Moviao\Security\CSRF_Protect();
////        if (empty($form->_csrf) || $csrf->verifyRequest($form->_csrf) !== true) {
////            return array("result" => false,"code" => 999);
////        }

        $data = parent::getDBConn();

        //--------------------------------------------------------------------------
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $data->startTransaction();

        $form_ticket_lock = new stdClass();
        $form_ticket_lock->SESSION = $sessionid;

        $ticket_utils = new \Moviao\Data\Util\TicketsUtils($this);
        $bresult = $ticket_utils->delete_all_ticket_lock($form_ticket_lock);

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('TicketsCommon (DBException) >> unlockTicket : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('TicketsCommon >> unlockTicket : ' . $e);
    } finally {
        if ($bresult === true) {
            if (! empty($data)) {
                $data->commitTransaction();
            }
        } else {
            if (! empty($data)) {
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
 * init Scan Ticket Event
 * @param stdClass $form
 * @return array
 */
public function initScanTicketEvent(\stdClass $form) : array {

    $result = false;
    $return_data = array();

    try {
        parent::getSession()->startSession();
        //parent::getSession()->Authorize();

        if (empty($form) || empty($form->TOKEN) || ! is_string($form->TOKEN)) {
            return array('result' => false,'code' => 666);
        }

        $token = filter_var($form->TOKEN, FILTER_SANITIZE_NUMBER_INT);

        $data = parent::getDBConn();
        //$iduser = parent::getSession()->getIDUSER();

        //--------------------------------------------------------------------------
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $event_detail = new \stdClass();
        $event_detail->token = $token;
        //--------------------------------------------------------------------
        $ticket_util = new \Moviao\Data\Util\TicketsUtils($this);
        $return_data = $ticket_util->initScanTicket($event_detail);

        if (is_array($return_data)) {
            $result = true;
        }
        //--------------------------------------------------------------------

    } catch (\Moviao\Database\Exception\DBException $e) {
        $result = false;
        error_log('TicketsCommon (DBException) >> initScanTicket : ' . $e);
    } catch (\Error $e) {
        $result = false;
        error_log('TicketsCommon >> initScanTicket : ' . $e);
    }

    if ($result === true) {
        $array = array("result" => $result,"data" => $return_data);
    } else {
        $array = array("result" => $result,"code" => parent::getError());
    }

    return $array;
}



/**
 * Scan Ticket
 * @param stdClass $form
 * @return array
 */
public function scanTicket(\stdClass $form) : array {

    $result = false;
    $return_data = array();

    try {
        parent::getSession()->startSession();
        //parent::getSession()->Authorize();

        if (empty($form) || empty($form->CODE) || ! is_string($form->CODE) || empty($form->TOKEN) || ! is_string($form->TOKEN) || empty($form->EVT) || ! is_string($form->EVT)) {
            return array('result' => false,'code' => 666);
        }

        $code = filter_var($form->CODE, FILTER_SANITIZE_NUMBER_INT);
        $token = filter_var($form->TOKEN, FILTER_SANITIZE_NUMBER_INT);
        //$idevent = filter_var($form->EVT, FILTER_SANITIZE_NUMBER_INT);
        $uid = mb_substr(filter_var($form->EVT, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH),0,60);
        $checkin = filter_var($form->CHECKIN, FILTER_SANITIZE_NUMBER_INT);

        $data = parent::getDBConn();
        //$iduser = parent::getSession()->getIDUSER();

        //--------------------------------------------------------------------------
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);


        $ticket_detail = new \stdClass();
        $ticket_detail->idevent = $eventID;
        $ticket_detail->token = $token;
        $ticket_detail->code = $code;
        //--------------------------------------------------------------------
        $ticket_util = new \Moviao\Data\Util\TicketsUtils($this);
        $return_data = $ticket_util->scanTicket($ticket_detail);

        //error_log(var_export($return_data, true));

        if (is_array($return_data)) {

            $ticket_update = new \stdClass();
            $ticket_update->TICORDER = $return_data["TICORDER"];
            $ticket_update->TICKETTYPE = $return_data["TICKETTYPE"];
            $ticket_update->TICKETDET = $return_data["TICKETDET"];
            $ticket_update->CHECKINDATE = date("Y-m-d H:i:s");
            $ticket_update->CHECKIN = $checkin;
            
            if (is_null($return_data['CHECKINSTATUS']) && is_null($return_data['CHECKINDATE']) && $checkin === '1') {            
                // Check in
                $result = $ticket_util->validateTicket($ticket_update);
            } else if ( (! is_null($return_data['CHECKINSTATUS']) || ! is_null($return_data['CHECKINDATE'])) && $checkin === '0') {
                // Check out
                $result = $ticket_util->unValidateTicket($ticket_update);
            }

            if ($result) {
                // Reload Data
                $return_data = $ticket_util->scanTicket($ticket_detail);
            }


        }
        //--------------------------------------------------------------------

    } catch (\Moviao\Database\Exception\DBException $e) {
        $result = false;
        error_log('TicketsCommon (DBException) >> scanTicket : ' . $e);
    } catch (\Error $e) {
        $result = false;
        error_log('TicketsCommon >> scanTicket : ' . $e);
    }

    if (!is_null($return_data) && is_array($return_data) && ! empty($return_data)) {
        $array = array("result" => $result,"data" => $return_data);
    } else {
        $array = array("result" => $result,"code" => parent::getError());
    }

    return $array;
}













/**
  * Get All Guests From an Event + Date
 * @param stdClass $form
 * @return array
 */
public function getGuestsList(\stdClass $form) : array {
    //exit(var_dump($form));
    $bresult = false;
    $urllink = null;
    $return_data = array();

    try {
        parent::getSession()->startSession();
        parent::getSession()->Authorize();

        if (empty($form) || empty($form->UID) || ! is_string($form->UID)) {
            return array("result" => false, "code" => 666);
        }

        $uid = mb_substr(filter_var($form->UID,FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,60);
        $data = parent::getDBConn();
        //$IDUSER = parent::getSession()->getIDUSER();
        //--------------------------------------------------------------------------
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        // Get Event ID
        $event_utils = new \Moviao\Data\Util\EventsUtils($this);
        $eventID = $event_utils->getEventID($uid);
        unset($event_utils);
        if ($eventID <= 0) {
            return array("result" => false,"code" => 52314587);
        }

        $ticket_detail = new \stdClass();
        $ticket_detail->idevent = $eventID;
        //--------------------------------------------------------------------
        // Create Ticket
        $ticket_utils = new \Moviao\Data\Util\TicketsUtils($this);
        $return_data = $ticket_utils->getGuestsList($ticket_detail);
        if (is_array($return_data)) {
            $bresult = true;
        }
        //--------------------------------------------------------------------

    } catch (\Moviao\Database\Exception\DBException $e) {
        $bresult = false;
        error_log('TicketsCommon (DBException) >> getAllTickets : ' . $e);
    } catch (\Error $e) {
        $bresult = false;
        error_log('TicketsCommon >> getAllTickets : ' . $e);
    }

    if ($bresult === true) {
        $array = array("result" => $bresult,"data" => $return_data);
    } else {
        $array = array("result" => $bresult,"code" => parent::getError());
    }

    return $array;
}






/**
 * Check in Guest
 * @param stdClass $form
 * @return array
 */
public function checkinGuest(\stdClass $form) : array {

    $result = false;
    $return_data = array();

    try {
        parent::getSession()->startSession();
        //parent::getSession()->Authorize();

        if (empty($form) || empty($form->CODE) || ! is_string($form->CODE) || empty($form->TOKEN) || ! is_string($form->TOKEN) || empty($form->EVT) || ! is_string($form->EVT)) {
            return array('result' => false,'code' => 666);
        }

        $code = filter_var($form->CODE, FILTER_SANITIZE_NUMBER_INT);
        $token = filter_var($form->TOKEN, FILTER_SANITIZE_NUMBER_INT);
        $idevent = filter_var($form->EVT, FILTER_SANITIZE_NUMBER_INT);

        $data = parent::getDBConn();
        //$iduser = parent::getSession()->getIDUSER();

        //--------------------------------------------------------------------------
        if (! $data->connectDBA()) {
            return array('result' => false,'code' => 888);
        }

        $ticket_detail = new \stdClass();
        $ticket_detail->idevent = $idevent;
        $ticket_detail->token = $token;
        $ticket_detail->code = $code;
        //--------------------------------------------------------------------
        $ticket_util = new \Moviao\Data\Util\TicketsUtils($this);
        $return_data = $ticket_util->scanTicket($ticket_detail);

        error_log(var_export($return_data, true));

        if (is_array($return_data)) {

            if (is_null($return_data['CHECKINSTATUS']) || is_null($return_data['CHECKINDATE'])) {
                $ticket_update = new \stdClass();
                $ticket_update->TICORDER = $return_data["TICORDER"];
                $ticket_update->TICKETTYPE = $return_data["TICKETTYPE"];
                $ticket_update->TICKETDET = $return_data["TICKETDET"];
                $ticket_update->CHECKINDATE = date("Y-m-d H:i:s");
                $result = $ticket_util->validateTicket($ticket_update);

                if ($result) {
                    // Reload Data
                    $return_data = $ticket_util->scanTicket($ticket_detail);
                }
                //error_log(var_export($result, true));
            }

        }
        //--------------------------------------------------------------------

    } catch (\Moviao\Database\Exception\DBException $e) {
        $result = false;
        error_log('TicketsCommon (DBException) >> scanTicket : ' . $e);
    } catch (\Error $e) {
        $result = false;
        error_log('TicketsCommon >> scanTicket : ' . $e);
    }

    if (!is_null($return_data) && is_array($return_data) && ! empty($return_data)) {
        $array = array("result" => $result,"data" => $return_data);
    } else {
        $array = array("result" => $result,"code" => parent::getError());
    }

    return $array;
}
















}