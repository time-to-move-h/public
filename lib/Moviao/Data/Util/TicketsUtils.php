<?php
declare(strict_types=1);
namespace Moviao\Data\Util;
use PDO;
use DateTime;

class TicketsUtils extends BaseUtils {

private const DEFAULT_CURRENCY = 'EUR';

/**
 * Create Ticket
 * @param stdClass $form
 * @return array
 */
public function create(\stdClass $form) : \stdClass {
    $resultObj = new \stdClass();
    $resultObj->lastid = -1;

    // Create Ticket
    $ticket = new \Moviao\Data\Rad\TicketsType($this->commonData);
    $form->DATINS = date('Y-m-d H:i:s');
    $form->ACTIVE = 1; // TODO: Create Unabled
    $form->ONLINE = 1; // Online
    $form->CURRENCY_ID = self::DEFAULT_CURRENCY; // TODO: Change Default currency
    $fdata = $ticket->filterForm($form);
    //exit(var_dump($fdata));

    // Correction quantity max
    if ($fdata->get_QTE() < $fdata->get_MAXQTE()) {
        $fdata->set_MAXQTE($fdata->get_QTE());
    }

    $resultObj->result = $ticket->create($fdata);

    if ($resultObj->result === true) {
        $resultObj->lastid = $this->getData()->getDBConn()->lastInsertId();
    }

    return $resultObj;
}

/**
 * Modify Ticket
 * @param \stdClass $form
 * @return bool
 */
public function modify(\stdClass $form) : bool {
    $strSql = 'UPDATE tickets_type SET TICTYPE_NAME=:name,TICTYPE_DESCL=:descl,TICTYPE_QTE=:qte,TICTYPE_PRICE=:price,TICTYPE_MINQTE=:minqte,TICTYPE_MAXQTE=:maxqte,TICTYPE_DATMOD=UTC_TIMESTAMP() WHERE ID_TICKETTYPE=:id AND TICTYPE_ACTIVE=1 AND exists (SELECT 1 FROM events_list e where e.ID_EVT=ID_EVT AND e.ID_EVTROLE=1 AND e.ID_USR=:iduser AND e.EVTLST_ACTIVE=1 LIMIT 1);'; // TICTYPE_SALE_END, TICTYPE_SALE_START
    $params = [
        [ 'parameter' => ':id', 'value' => $form->ID, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':name', 'value' => $form->NAME, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':descl', 'value' => $form->DESCL, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':qte', 'value' => $form->QTE, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':price', 'value' => $form->PRICE, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':minqte', 'value' => $form->MINQTE, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':maxqte', 'value' => $form->MAXQTE, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ]];
    return $this->data->executeNonQuery($strSql, $params);
}

/**
 * Create Link between event and ticket
 * @param \stdClass $form
 * @return bool
 * @throws \Moviao\Database\Exception\DBException
 */
public function create_ticket_event(\stdClass $form) : bool {
    $strSql = 'INSERT INTO tickets_events (ID_EVT,EVTDAT_DATBEG,ID_TICKETTYPE,TICEVT_ACTIVE,TICEVT_DATINS) VALUES (:idevent,:datbeg,:idticket,1,UTC_TIMESTAMP());';
    $params = [
        [ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':datbeg', 'value' => $form->datbeg, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':idticket', 'value' => $form->idticket, 'type' => PDO::PARAM_INT ]];
    return $this->data->executeNonQuery($strSql, $params);
}

/**
 * Delete Ticket
 * @param stdClass $form
 * @return array
 */
public function delete(\stdClass $form) : bool {
    $bresult = false;
    $strSql = 'UPDATE tickets_events set TICEVT_ACTIVE=0 WHERE ID_EVT=:idevent AND ID_TICKETTYPE=:idticket AND TICEVT_ACTIVE=1 AND exists (SELECT 1 FROM events_admin e where e.ID_EVT=:idevent AND e.ID_USR=:iduser AND e.EVTADM_ACTIVE=1 LIMIT 1) LIMIT 1;';
    $params = [
        [ 'parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':idticket', 'value' => $form->idticket, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ]];
    $bresult = $this->data->executeNonQuery($strSql, $params);

    //exit(var_dump($strSql));

    if ($bresult === true) {
        $strSql = 'UPDATE tickets_type set TICTYPE_ACTIVE=0 WHERE ID_TICKETTYPE=:idticket AND TICTYPE_ACTIVE=1 AND exists (SELECT 1 FROM events_admin e where e.ID_EVT=:idevent AND e.ID_USR=:iduser AND e.EVTADM_ACTIVE=1 LIMIT 1) LIMIT 1;';
        $params = [
            ['parameter' => ':idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT],
            ['parameter' => ':idticket', 'value' => $form->idticket, 'type' => PDO::PARAM_INT],
            ['parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT]];
        $bresult = $this->data->executeNonQuery($strSql, $params);
    }

    return $bresult;
}



/**
 * Get All Tickets and Events for a Admin
 * @param stdClass $form
 * @return array
 */
public function getTicketsDashboard(\stdClass $form) : array {
    $return_data = array();
    $strSql = 'SELECT DISTINCT t.*, e.EVT_TITLE FROM tickets_type t, tickets_events te, events e, events_admin ea WHERE t.ID_TICKETTYPE=te.ID_TICKETTYPE AND te.TICEVT_ACTIVE=1 AND t.TICTYPE_ACTIVE=1 AND t.TICTYPE_ONLINE=1 AND e.ID_EVT=te.ID_EVT AND e.ID_EVT=ea.ID_EVT AND ea.ID_USR=:iduser AND ea.EVTADM_ACTIVE=1 ORDER BY t.TICTYPE_NAME;';
    $params = [[ 'parameter' => 'iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ]];
    $rows = $this->data->readAllObject($strSql, $params);

    if (empty($rows)) {
        return $return_data;
    }

    foreach ($rows as $obj) {

        if (empty($obj)) {
            break;
        }

        $tickettype = strval($obj->ID_TICKETTYPE);

        $qte_available = $this->getTicketOrderQteNoOrdered($tickettype);

        //exit(var_dump($qte_available !== null ));
        $row['data']['ID'] =  $obj->ID_TICKETTYPE;
        $row['data']['TITLE'] =  $obj->EVT_TITLE;
        $row['data']['NAME'] =  $obj->TICTYPE_NAME;
        $row['data']['QTE'] =  $obj->TICTYPE_QTE;
        $row['data']['QTE_AVAILABLE'] = $qte_available;
        $row['data']['PRICE'] =  $obj->TICTYPE_PRICE;
        $row['data']['SALE_START'] =  $obj->TICTYPE_SALE_START;
        $row['data']['SALE_END'] =  $obj->TICTYPE_SALE_END;
        $return_data[]  = $row['data'];
    }

    return $return_data;
}







/**
 * Get All Tickets From an Event
 * @param stdClass $form
 * @return array
 */
public function getAllTickets(\stdClass $form) : array {
    $return_data = array();
    $strSql = 'SELECT DISTINCT t.* FROM tickets_type t, tickets_events te WHERE t.ID_TICKETTYPE=te.ID_TICKETTYPE AND te.TICEVT_ACTIVE=1 AND t.TICTYPE_ACTIVE=1 AND t.TICTYPE_ONLINE=1 AND te.ID_EVT=:idevent ORDER BY t.TICTYPE_NAME;';
    $params = [[ 'parameter' => 'idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ]];
    $rows = $this->data->readAllObject($strSql, $params);

    if (empty($rows)) {
        return $return_data;
    }

    foreach ($rows as $obj) {

        if (empty($obj)) {
            break;
        }

        $max_qte = 10;
        $tickettype = strval($obj->ID_TICKETTYPE);
        $qte_available = $this->getTicketOrderQteAvailable($tickettype);

        //exit(var_dump($qte_available));

        if ($qte_available != null && $qte_available < $max_qte && $qte_available > 0) {
            $max_qte = $qte_available;
        } else if ($qte_available !== null && $qte_available == 0) {
            $max_qte = 0;
        }

        //exit(var_dump($qte_available !== null ));

        $row['data']['ID'] =  $obj->ID_TICKETTYPE;
        $row['data']['NAME'] =  $obj->TICTYPE_NAME;
        $row['data']['DESCL'] =  $obj->TICTYPE_DESCL;
        $row['data']['QTE'] =  $obj->TICTYPE_QTE;
        $row['data']['PRICE'] =  $obj->TICTYPE_PRICE;
        $row['data']['MINQTE'] =  $obj->TICTYPE_MINQTE;
        $row['data']['MAXQTE'] =  $max_qte;
        $row['data']['SALE_START'] =  $obj->TICTYPE_SALE_START;
        $row['data']['SALE_END'] =  $obj->TICTYPE_SALE_END;
        $return_data[]  = $row['data'];
    }
    return $return_data;
}

/**
 * Get Ticket Info From an Event
 * @param \stdClass $form
 * @return array
 * @throws \Moviao\Database\Exception\DBException
 */
public function getTicket(\stdClass $form) : array {

    $return_data = array();

    $strSql = 'SELECT DISTINCT t.* '
        . 'FROM tickets_type t, tickets_events te, events e '
        . 'WHERE t.ID_TICKETTYPE=te.ID_TICKETTYPE AND te.TICEVT_ACTIVE=1 AND t.TICTYPE_ACTIVE=1 AND t.TICTYPE_ONLINE=1 AND te.ID_EVT=:idevent AND t.ID_TICKETTYPE=:idticket AND e.ID_EVT=te.ID_EVT '
        . 'ORDER BY t.TICTYPE_NAME';

    $params = [[ 'parameter' => 'idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ],[ 'parameter' => ':idticket', 'value' => $form->idticket, 'type' => PDO::PARAM_INT ]];
    $row = $this->data->readLineObject($strSql, $params);

    if (empty($row)) {
        return $return_data;
    }

    $return_data['ID'] = $row->ID_TICKETTYPE;
    $return_data['NAME'] = $row->TICTYPE_NAME;
    $return_data['DESCL'] = $row->TICTYPE_DESCL;
    $return_data['QTE'] = $row->TICTYPE_QTE;
    $return_data['PRICE'] = $row->TICTYPE_PRICE;
    $return_data['PRICEHT'] = $row->TICTYPE_PRICEHT;
    $return_data['TVA'] = $row->TICTYPE_TVA;
    $return_data['CURRENCY'] = $row->TIC_CURRENCY_ID;
    $return_data['MINQTE'] = $row->TICTYPE_MINQTE;
    $return_data['MAXQTE'] = $row->TICTYPE_MAXQTE;
    $return_data['SALE_START'] = $row->TICTYPE_SALE_START;
    $return_data['SALE_END'] = $row->TICTYPE_SALE_END;

    return $return_data;
}

/* Tickets Order */

/**
 * Create Ticket Order
 * @param \stdClass $form
 * @return \stdClass
 */
public function create_order(\stdClass $form) : \stdClass {
    $resultObj = new \stdClass();
    $resultObj->lastid = -1;

    // Create Ticket
    $ticket = new \Moviao\Data\Rad\TicketsOrder($this->commonData);
    $form->DATINS = date('Y-m-d H:i:s');
    $form->ACTIVE = 1; // Create Enabled
    $fdata = $ticket->filterForm($form);
    //exit(var_dump($fdata));
    $resultObj->result = $ticket->create($fdata);

    if ($resultObj->result === true) {
        $resultObj->lastid = $this->getData()->getDBConn()->lastInsertId();
    }

    return $resultObj;
}

/**
 * Create Ticket Order Item
 * @param \stdClass $form
 * @return \stdClass
 */
public function create_order_item(\stdClass $form) : bool {
    $resultObj = new \stdClass();
    // Create Ticket item
    $ticket = new \Moviao\Data\Rad\TicketsOrderItems($this->commonData);
    $form->ITEM_DATINS = date('Y-m-d H:i:s');
    $fdata = $ticket->filterForm($form);
    $resultObj->result = $ticket->create($fdata);
    return $resultObj->result;
}

/**
 * Create Ticket Detail
 * @param \stdClass $form
 * @return bool
 */
public function create_order_detail(\stdClass $form) : bool {
    $resultObj = new \stdClass();
    $ticket_detail = new \Moviao\Data\Rad\TicketsOrderDetails($this->commonData);
    $form->DATINS = date('Y-m-d H:i:s');
    $fdata = $ticket_detail->filterForm($form);
    $resultObj->result = $ticket_detail->create($fdata);
    return $resultObj->result;
}


/**
 * Update Ticket detail
 * @param \stdClass $form
 * @return bool
 * @throws \Moviao\Database\Exception\DBException
 */
public function update_order_detail(\stdClass $form) : bool {
    $strSql = 'UPDATE tickets_order_details SET TICDET_FNAME=:fname,TICDET_LNAME=:lname,TICDET_EMAIL=:email,TICDET_LOCKED=1,TICDET_DATMOD=UTC_TIMESTAMP() WHERE ID_TICORDER=:ticorder AND ID_TICKETTYPE=:tickettype AND ID_TICKETDET=:ticketdet AND TICDET_LOCKED=0 LIMIT 1;';
    $params = [
        [ 'parameter' => ':ticorder', 'value' => $form->TICORDER, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':tickettype', 'value' => $form->TICKETTYPE, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':ticketdet', 'value' => $form->TICKETDET, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':fname', 'value' => $form->FNAME, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':lname', 'value' => $form->LNAME, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':email', 'value' => $form->EMAIL, 'type' => PDO::PARAM_STR ]
    ];
    return $this->data->executeNonQuery($strSql, $params);
}



public function isOrderDetailExist(string $code) : bool {
    $strSql = 'SELECT 1 FROM tickets_order_details WHERE TICDET_CODE=? LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $code, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row === '1') return true;
    return false;
}

/**
 * Get My Tickets
 * @param stdClass $form
 * @return array
 */
public function getMyTickets(\stdClass $form) : array {

    $return_data = array();

    //$strSql = 'SELECT * FROM tickets_order t1 WHERE t1.TICORDER_ACTIVE=1 AND t1.ID_USR=:iduser ORDER BY t1.TICORDER_DATINS DESC;';
    $strSql = 'SELECT DISTINCT e.EVT_TITLE, ot.ID_TICORDER, ot.TICORDER_DATINS FROM tickets_order ot, tickets_order_items toi, tickets_events te, events e WHERE ot.ID_TICORDER=toi.ID_TICORDER AND toi.ID_TICKETTYPE=te.ID_TICKETTYPE AND te.ID_EVT=e.ID_EVT AND ot.TICORDER_ACTIVE=1 AND ot.ID_USR=:iduser ORDER BY ot.TICORDER_DATINS DESC;';

    $params = [[ 'parameter' => 'iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ]];
    $rows = $this->data->readAllObject($strSql, $params);

    if (empty($rows)) {
        return $return_data;
    }

    foreach ($rows as $obj) {

        if (empty($obj)) {
            break;
        }

        $row = array();

        $row['data']['TITLE'] =  $obj->EVT_TITLE;
        $row['data']['TICORDER'] =  $obj->ID_TICORDER;
        $row['data']['DATINS'] =  $obj->TICORDER_DATINS;

        $strSql2 = 'SELECT e.EVT_TITLE, e.EVT_PICTURE, e.EVT_PICTURE_MIN ,t3.TICTYPE_NAME, t1.TICORDER_ITEM_QTE, t1.TICORDER_ITEM_PRICE FROM tickets_order_items t1, tickets_events t2, tickets_type t3, events e WHERE t1.ID_TICKETTYPE=t3.ID_TICKETTYPE AND t2.ID_EVT=e.ID_EVT AND t1.ID_TICKETTYPE=t2.ID_TICKETTYPE AND t2.TICEVT_ACTIVE=1 AND t1.ID_TICORDER=:idticorder ORDER BY t1.TICORDER_ITEM_DATINS DESC;';
        $params2 = [[ 'parameter' => 'idticorder', 'value' => $obj->ID_TICORDER, 'type' => PDO::PARAM_INT ]];
        $rows2 = $this->data->readAllObject($strSql2, $params2);

        $ticket_details = array();
        foreach ($rows2 as $obj2) {
            $row2['data']['TITLE'] = $obj2->EVT_TITLE;
            $row2['data']['NAME'] = $obj2->TICTYPE_NAME;
            $row2['data']['ITEM_QTE'] = $obj2->TICORDER_ITEM_QTE;
            $row2['data']['ITEM_PRICE'] = $obj2->TICORDER_ITEM_PRICE;
            $row2['data']['PICTURE'] = $obj2->EVT_PICTURE;
            $row2['data']['PICTURE_MIN'] = $obj2->EVT_PICTURE_MIN;
            $ticket_details[] = $row2['data'];
        }

        $row['data']['DETAILS'] = $ticket_details;
        $return_data[]  = $row['data'];
    }
    return $return_data;
}

/**
 * Get My Ticket Details
 * @param \stdClass $form
 * @return array
 * @throws \Moviao\Database\Exception\DBException
 */
public function getMyTicket(\stdClass $form) : array {
    $return_data = array();

    $strSql = 'SELECT t1.ID_TICORDER,t1.TICORDER_VENUE,t1.TICORDER_STREET,t1.TICORDER_STREETN,t1.TICORDER_STREET2,t1.TICORDER_CITY,t1.TICORDER_STATE,t1.TICORDER_PCODE, (SELECT name FROM countries WHERE id=t1.TICORDER_COUNTRY) COUNTRY ,e.EVT_TITLE,(SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVT_DATBEG, (SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVT_DATEND, (SELECT EVTDAT_ALLDAY FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1) EVT_ALLDAY, (SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDBEG LIMIT 1) TIMEZONE_BEG,(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDEND LIMIT 1) TIMEZONE_END ,t4.TICTYPE_NAME,t2.TICORDER_ITEM_PRICE,t5.TICDET_FNAME,t5.TICDET_LNAME FROM tickets_order t1, tickets_order_items t2, tickets_events t3, tickets_type t4, tickets_order_details t5, events e WHERE t1.TICORDER_ACTIVE=1 AND t1.ID_USR=:iduser AND t1.ID_TICORDER=:idorder AND t1.ID_TICORDER=t5.ID_TICORDER AND t5.ID_TICKETDET=:idticket AND t5.TICDET_LOCKED=1 AND t2.ID_TICKETTYPE=t4.ID_TICKETTYPE AND t3.ID_EVT=e.ID_EVT AND t2.ID_TICKETTYPE=t3.ID_TICKETTYPE AND t3.TICEVT_ACTIVE=1 AND t2.ID_TICORDER=:idorder;';
    $params = [[ 'parameter' => 'iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ],[ 'parameter' => 'idorder', 'value' => $form->idorder, 'type' => PDO::PARAM_INT ],[ 'parameter' => 'idticket', 'value' => $form->idticket, 'type' => PDO::PARAM_INT ]];
    $row_ticket = $this->data->readLineObject($strSql, $params);

    $return_data['TICORDER'] = $row_ticket->ID_TICORDER;

    $return_data['EVT_TITLE'] = $row_ticket->EVT_TITLE;
    $return_data['EVT_DATBEG'] = $row_ticket->EVT_DATBEG;
    $return_data['TIMEZONE_BEG'] = $row_ticket->TIMEZONE_BEG;
    $return_data['EVT_DATEND'] = $row_ticket->EVT_DATEND;
    $return_data['TIMEZONE_END'] = $row_ticket->TIMEZONE_END;
    $return_data['EVT_ALLDAY'] = $row_ticket->EVT_ALLDAY;

    $return_data['TICKET_NAME'] = $row_ticket->TICTYPE_NAME;
    $return_data['BUYER_FNAME'] = $row_ticket->TICDET_FNAME;
    $return_data['BUYER_LNAME'] = $row_ticket->TICDET_LNAME;
    $return_data['ITEM_PRICE'] = $row_ticket->TICORDER_ITEM_PRICE;

    $return_data['ADDRESS_VENUE'] = $row_ticket->TICORDER_VENUE;
    $return_data['ADDRESS_STREET'] = $row_ticket->TICORDER_STREET;
    $return_data['ADDRESS_STREETN'] = $row_ticket->TICORDER_STREETN;
    $return_data['ADDRESS_STREET2'] = $row_ticket->TICORDER_STREET2;
    $return_data['ADDRESS_CITY'] = $row_ticket->TICORDER_CITY;
    $return_data['ADDRESS_STATE'] = $row_ticket->TICORDER_STATE;
    $return_data['ADDRESS_PCODE'] = $row_ticket->TICORDER_PCODE;
    $return_data['ADDRESS_COUNTRY'] = $row_ticket->COUNTRY;

    return $return_data;
}


/**
 * Get My Ticket Details
 * @param \stdClass $form
 * @return array
 * @throws \Moviao\Database\Exception\DBException
 */
public function getTicketPDF(\stdClass $form) : array {
        $return_data = array();

        $strSql = 'SELECT t1.ID_TICORDER,t1.TICORDER_VENUE,t1.TICORDER_STREET,t1.TICORDER_STREETN,t1.TICORDER_STREET2,t1.TICORDER_CITY,t1.TICORDER_STATE,t1.TICORDER_PCODE, (SELECT name FROM countries WHERE id=t1.TICORDER_COUNTRY) COUNTRY ,e.EVT_TITLE,(SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVT_DATBEG, (SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVT_DATEND, (SELECT EVTDAT_ALLDAY FROM events_dates WHERE ID_EVT=e.ID_EVT LIMIT 1) EVT_ALLDAY, (SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDBEG LIMIT 1) TIMEZONE_BEG,(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDEND LIMIT 1) TIMEZONE_END ,t4.TICTYPE_NAME,t2.TICORDER_ITEM_PRICE,t5.TICDET_FNAME,t5.TICDET_LNAME FROM tickets_order t1, tickets_order_items t2, tickets_events t3, tickets_type t4, tickets_order_details t5, events e WHERE t1.TICORDER_ACTIVE=1 AND t1.ID_TICORDER=:idorder AND t1.ID_TICORDER=t5.ID_TICORDER AND t5.ID_TICKETDET=:idticket AND t5.TICDET_LOCKED=1 AND t2.ID_TICKETTYPE=t4.ID_TICKETTYPE AND t3.ID_EVT=e.ID_EVT AND t2.ID_TICKETTYPE=t3.ID_TICKETTYPE AND t3.TICEVT_ACTIVE=1 AND t2.ID_TICORDER=:idorder;';
        $params = [[ 'parameter' => 'idorder', 'value' => $form->idorder, 'type' => PDO::PARAM_INT ],[ 'parameter' => 'idticket', 'value' => $form->idticket, 'type' => PDO::PARAM_INT ]];
        $row_ticket = $this->data->readLineObject($strSql, $params);

        $return_data['TICORDER'] = $row_ticket->ID_TICORDER;

        $return_data['EVT_TITLE'] = $row_ticket->EVT_TITLE;
        $return_data['EVT_DATBEG'] = $row_ticket->EVT_DATBEG;
        $return_data['TIMEZONE_BEG'] = $row_ticket->TIMEZONE_BEG;
        $return_data['EVT_DATEND'] = $row_ticket->EVT_DATEND;
        $return_data['TIMEZONE_END'] = $row_ticket->TIMEZONE_END;
        $return_data['EVT_ALLDAY'] = $row_ticket->EVT_ALLDAY;

        $return_data['TICKET_NAME'] = $row_ticket->TICTYPE_NAME;
        $return_data['BUYER_FNAME'] = $row_ticket->TICDET_FNAME;
        $return_data['BUYER_LNAME'] = $row_ticket->TICDET_LNAME;
        $return_data['ITEM_PRICE'] = $row_ticket->TICORDER_ITEM_PRICE;

        $return_data['ADDRESS_VENUE'] = $row_ticket->TICORDER_VENUE;
        $return_data['ADDRESS_STREET'] = $row_ticket->TICORDER_STREET;
        $return_data['ADDRESS_STREETN'] = $row_ticket->TICORDER_STREETN;
        $return_data['ADDRESS_STREET2'] = $row_ticket->TICORDER_STREET2;
        $return_data['ADDRESS_CITY'] = $row_ticket->TICORDER_CITY;
        $return_data['ADDRESS_STATE'] = $row_ticket->TICORDER_STATE;
        $return_data['ADDRESS_PCODE'] = $row_ticket->TICORDER_PCODE;
        $return_data['ADDRESS_COUNTRY'] = $row_ticket->COUNTRY;

        return $return_data;
    }


/**
 * Get My Tickets Details
 * @param stdClass $form
 * @return array
 */
public function getMyTicketsDetails(\stdClass $form) : array {
    $return_data = array();
    $strSql = 'SELECT * FROM tickets_order t1 WHERE t1.TICORDER_ACTIVE=1 AND t1.ID_TICORDER_STATUS="PAYMENT_ACCEPTED" AND t1.ID_USR=:iduser ORDER BY t1.TICORDER_DATINS DESC LIMIT :limit OFFSET :offset;';
    $params = [
        [ 'parameter' => 'iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':limit', 'value' => $form->limit, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':offset', 'value' => $form->offset, 'type' => PDO::PARAM_INT ]
        ];
    $rows = $this->data->readAllObject($strSql, $params);

    if (empty($rows)) {
        return $return_data;
    }

    foreach ($rows as $obj) {
        if (empty($obj)) break;
        $row = array();
        $row['data']['TICORDER'] =  $obj->ID_TICORDER;
        $row['data']['DATINS'] =  $obj->TICORDER_DATINS;
        //unset($row);

        $strSql2 = 'SELECT e.EVT_TITLE, e.EVT_PICTURE, e.EVT_PICTURE_MIN, d.ID_TICKETTYPE,d.ID_TICKETDET,d.TICDET_FNAME,d.TICDET_LNAME,d.TICDET_EMAIL,d.TICDET_LOCKED, d.TICDET_CODE,t2.TICTYPE_NAME, t1.TICORDER_ITEM_QTE, t1.TICORDER_ITEM_PRICE FROM tickets_order_items t1, tickets_type t2, tickets_order_details d, tickets_events t3, events e WHERE t1.ID_TICKETTYPE=t2.ID_TICKETTYPE AND d.ID_TICORDER=t1.ID_TICORDER AND d.ID_TICKETTYPE=t2.ID_TICKETTYPE AND t1.ID_TICORDER=:idorder AND t3.ID_EVT=e.ID_EVT AND t1.ID_TICKETTYPE=t3.ID_TICKETTYPE ORDER BY d.ID_TICKETDET;';
        $params2 = [[ 'parameter' => 'idorder', 'value' =>  $obj->ID_TICORDER, 'type' => PDO::PARAM_INT ]];

        $rows2 = $this->data->readAllObject($strSql2, $params2);

        $ticket_details = array();
        foreach ($rows2 as $obj2) {
            $row2['data']['TITLE'] = $obj2->EVT_TITLE;
            $row2['data']['NAME'] = $obj2->TICTYPE_NAME;
            $row2['data']['PICTURE'] = $obj2->EVT_PICTURE;
            $row2['data']['PICTURE_MIN'] = $obj2->EVT_PICTURE_MIN;
            $row2['data']['ITEM_QTE'] = $obj2->TICORDER_ITEM_QTE;
            $row2['data']['ITEM_PRICE'] = $obj2->TICORDER_ITEM_PRICE;
            $row2['data']['TICKETTYPE'] = $obj2->ID_TICKETTYPE;
            $row2['data']['TICKETDET'] = $obj2->ID_TICKETDET;
            $row2['data']['FNAME'] = $obj2->TICDET_FNAME;
            $row2['data']['LNAME'] = $obj2->TICDET_LNAME;
            $row2['data']['EMAIL'] = $obj2->TICDET_EMAIL;
            $row2['data']['CODE'] = $obj2->TICDET_CODE;

            $ticket_details[] = $row2['data'];
        }

        $row['data']['DETAILS'] = $ticket_details;
        $return_data[]  = $row['data'];
    }
    return $return_data;
}

/**
 * Get Order Details
 * @param string $idorder
 * @return array
 * @throws \Moviao\Database\Exception\DBException
 *
 */
public function getOrderDetails(string $idorder) : array {

    $return_data = array();
    $ticket_details = array();

    $d1 = null;
    $d2 = null;
    $datbegin_timestamp = 0;
    $datend_timestamp = 0;
    $datevent_formatted = null;
    $allday = false;

    $strSql = 'SELECT e.EVT_TITLE, ot.ID_TICORDER, ot.TICORDER_DATINS, ot.ID_TICORDER_STATUS '

        . ',(SELECT EVTDAT_DATBEG FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATBEG '
        . ',(SELECT EVTDAT_DATEND FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_DATEND '
        . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDBEG ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_BEG '
        . ',(SELECT zone_name FROM events_dates, zone WHERE ID_EVT=e.ID_EVT AND zone_id=ID_ZONEIDEND ORDER BY EVTDAT_DATBEG LIMIT 1) TIMEZONE_END '
        . ',(SELECT EVTDAT_ALLDAY FROM events_dates WHERE ID_EVT=e.ID_EVT ORDER BY EVTDAT_DATBEG LIMIT 1) EVTDAT_ALLDAY '

        . 'FROM tickets_order ot, tickets_order_items toi, tickets_events te, events e WHERE ot.ID_TICORDER=toi.ID_TICORDER AND toi.ID_TICKETTYPE=te.ID_TICKETTYPE AND te.ID_EVT=e.ID_EVT AND ot.TICORDER_ACTIVE=1 AND ot.ID_TICORDER=:idorder LIMIT 1;';

    $params = [[ 'parameter' => 'idorder', 'value' => $idorder, 'type' => PDO::PARAM_INT ]];
    $row_order = $this->data->readLineObject($strSql, $params);

    if (empty($row_order)) {
        return $return_data;
    }

    $dateformat = new \Moviao\Util\DateTimeFormat();

    // Date Formatted
    if ($row_order->EVTDAT_DATBEG !== null) {
        $date_start = new DateTime($row_order->EVTDAT_DATBEG,new \DateTimeZone('UTC'));
        $date_start->setTimezone(new \DateTimeZone($row_order->TIMEZONE_BEG));

        $date_end = null;
        if ($row_order->EVTDAT_DATEND !== null) {
            $date_end = new DateTime($row_order->EVTDAT_DATEND,new \DateTimeZone('UTC'));
            $date_end->setTimezone(new \DateTimeZone($row_order->TIMEZONE_END));
        }

        if (isset($row_order->EVTDAT_ALLDAY) && $row_order->EVTDAT_ALLDAY === '1') {
            $allday = true;
        }

        // TODO> Lang
        $datevent_formatted = $dateformat->formatDate($date_start,$date_end, "en-GB",$allday,false);
    }

    // Date Begin
    if ($row_order->EVTDAT_DATBEG !== null && ! empty($row_order->EVTDAT_DATBEG)) {
        $d1 = new DateTime($row_order->EVTDAT_DATBEG, new \DateTimeZone($row_order->TIMEZONE_BEG));
        $datbegin = $d1->getTimestamp();
        $datbegin_timestamp = $datbegin * 1000;
    }

    // Date End
    if ($row_order->EVTDAT_DATEND !== null && ! empty($row_order->EVTDAT_DATEND)) {
        $d2 = new DateTime($row_order->EVTDAT_DATEND, new \DateTimeZone($row_order->TIMEZONE_END));
        $datbegin = $d2->getTimestamp();
        $datend_timestamp = $datbegin * 1000;
    }

    $row['TITLE'] =  $row_order->EVT_TITLE;
    $row['TICORDER'] =  $row_order->ID_TICORDER;
    $row['STATUS'] =  $row_order->ID_TICORDER_STATUS;
    $row['DATINS'] =  $row_order->TICORDER_DATINS;

    $row['ALLDAY'] = $row_order->EVTDAT_ALLDAY;
    $row['DATBEG'] = $dateformat->formatShortDate($d1);
    $row['DATEND'] = $dateformat->formatShortDate($d2);
    $row['DATBEG_TIMESTAMP'] = $datbegin_timestamp;
    $row['DATEND_TIMESTAMP'] = $datend_timestamp;
    $row['DATFORMATTED'] = $datevent_formatted;
    $row['TIMEZONE_BEG'] = $row_order->TIMEZONE_BEG;
    $row['TIMEZONE_END'] = $row_order->TIMEZONE_END === null ? '' : $row_order->TIMEZONE_END;

    unset($row_order);

    $strSql2 = 'SELECT d.ID_TICKETTYPE,d.ID_TICKETDET,d.TICDET_FNAME,d.TICDET_LNAME,d.TICDET_EMAIL,d.TICDET_LOCKED,  d.TICDET_CODE ,  d.TICDET_CHECKINSTATUS, d.TICDET_CHECKINDATE   ,t2.TICTYPE_NAME FROM tickets_order_items t1, tickets_type t2, tickets_order_details d WHERE t1.ID_TICKETTYPE=t2.ID_TICKETTYPE AND d.ID_TICORDER=t1.ID_TICORDER AND d.ID_TICKETTYPE=t2.ID_TICKETTYPE AND t1.ID_TICORDER=:idorder ORDER BY d.ID_TICKETDET;';
    $params2 = [[ 'parameter' => 'idorder', 'value' => $idorder, 'type' => PDO::PARAM_INT ]];
    $rows2 = $this->data->readAllObject($strSql2, $params2);

    foreach ($rows2 as $obj2) {
        $row2['TICKETTYPE'] = $obj2->ID_TICKETTYPE;
        $row2['TICKETDET'] = $obj2->ID_TICKETDET;
        $row2['FNAME'] = $obj2->TICDET_FNAME;
        $row2['LNAME'] = $obj2->TICDET_LNAME;
        $row2['EMAIL'] = $obj2->TICDET_EMAIL;
        $row2['EMAIL'] = $obj2->TICDET_EMAIL;
        $row2['CODE'] = $obj2->TICDET_CODE;
        $row2['CHECKINSTATUS'] = $obj2->TICDET_CHECKINSTATUS;
        $row2['CHECKINDATE'] = $obj2->TICDET_CHECKINDATE;
        $row2['LOCKED'] = $obj2->TICDET_LOCKED;
        $ticket_details[] = $row2;
    }

    $row['DETAILS'] = $ticket_details;
    $return_data = $row;

    return $return_data;
}

public function getTicketTypeQte(string $tickettypeId) : ?int {
    $result = null;

    $strSql = 'SELECT TICTYPE_QTE FROM tickets_type WHERE ID_TICKETTYPE=? LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $tickettypeId, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row !== false) {
        $result = (int)($row);
    }

    return $result;
}

public function getTicketOrderQteOrdered(string $tickettypeId) : ?int {
    $result = null;

    $strSql = 'SELECT SUM(TICORDER_ITEM_QTE) FROM tickets_order_items WHERE ID_TICKETTYPE=?;';
    $params = [[ 'parameter' => 1, 'value' => $tickettypeId, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row !== false) {
        $result = (int)($row);
    }

    return $result;
}

public function getTicketOrderQteAvailable(string $tickettypeId) : ?int {
    $result = null;
    $strSql = 'SELECT IFNULL((SELECT TICTYPE_QTE FROM tickets_type WHERE ID_TICKETTYPE=:id LIMIT 1),0) - IFNULL(SUM(TICORDER_ITEM_QTE), 0) - IFNULL((SELECT IFNULL(SUM(TICLOC_QTE), 0) FROM tickets_locks WHERE ID_TICKETTYPE=:id),0) FROM tickets_order_items WHERE ID_TICKETTYPE=:id;';
    $params = [[ 'parameter' => ':id', 'value' => $tickettypeId, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row !== false) {
        $result = (int)($row);
    }
    return $result;
}

public function getTicketOrderQteNoOrdered(string $tickettypeId) : ?int {
    $result = null;
    $strSql = 'SELECT IFNULL((SELECT TICTYPE_QTE FROM tickets_type WHERE ID_TICKETTYPE=:id LIMIT 1),0) - IFNULL(SUM(TICORDER_ITEM_QTE), 0) FROM tickets_order_items WHERE ID_TICKETTYPE=:id;';
    $params = [[ 'parameter' => ':id', 'value' => $tickettypeId, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row !== false) {
        $result = (int)($row);
    }
    return $result;
}











/**
 * Create Ticket Lock
 * @param \stdClass $form
 * @return bool
 */
public function create_ticket_lock(\stdClass $form) : bool {
    $resultObj = new \stdClass();
    $ticket_lock = new \Moviao\Data\Rad\TicketsLocks($this->commonData);
    $form->DATINS = date('Y-m-d H:i:s');
    $fdata = $ticket_lock->filterForm($form);
    $resultObj->result = $ticket_lock->create($fdata);
    return $resultObj->result;
}


/**
 * Delete Ticket Lock
 * @return array
 */
public function delete_ticket_lock_outdated() : bool {
    $bresult = false;
    $strSql = 'DELETE FROM tickets_locks WHERE UTC_TIMESTAMP() > DATE_ADD(TICLOC_DATINS, INTERVAL 10 MINUTE)';
    $params = [];
    $bresult = $this->data->executeNonQuery($strSql, $params);
    return $bresult;
}

/**
 * Delete Ticket Lock
 * @param stdClass $form
 * @return array
 */
public function delete_ticket_lock(\stdClass $form) : bool {
    $strSql = 'DELETE FROM tickets_locks WHERE ID_SESSION = :session AND ID_TICKETTYPE = :tickettype';
    $params = [
        [ 'parameter' => ':session', 'value' => $form->SESSION, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':tickettype', 'value' => $form->TICKETTYPE, 'type' => PDO::PARAM_STR ]];
    $bresult = $this->data->executeNonQuery($strSql, $params);
    return $bresult;
}

/**
 * Delete Ticket Lock
 * @param stdClass $form
 * @return array
 */
public function delete_all_ticket_lock(\stdClass $form) : bool {
    $strSql = 'DELETE FROM tickets_locks WHERE ID_SESSION = :session';
    $params = [[ 'parameter' => ':session', 'value' => $form->SESSION, 'type' => PDO::PARAM_STR ]];
    $bresult = $this->data->executeNonQuery($strSql, $params);
    return $bresult;
}










/**
 * init Scan Ticket Event
 * @param \stdClass $form
 * @return array
 * @throws \Moviao\Database\Exception\DBException
 */
public function initScanTicket(\stdClass $form) : array {

    $return_data = null;
    $strSql = 'SELECT e.ID_EVT, e.EVT_TITLE, ed.EVTDAT_DATBEG, ed.EVTDAT_DATEND '
    . ',(SELECT zone_name FROM zone WHERE zone_id=ed.ID_ZONEIDBEG LIMIT 1) TIMEZONE_BEG '
    . ',(SELECT zone_name FROM zone WHERE zone_id=ed.ID_ZONEIDEND LIMIT 1) TIMEZONE_END '
    . ' FROM events e, events_dates ed '
    . 'WHERE e.ID_EVT=ed.ID_EVT AND ed.EVTDAT_TOKEN=:token AND ed.EVTDAT_ONLINE=1 AND e.EVT_ACTIVE=1 AND e.EVT_ONLINE=1 AND ed.EVTDAT_ACTIVE=1 ORDER BY ed.EVTDAT_DATINS DESC LIMIT 1';
    $params = [[ 'parameter' => 'token', 'value' => $form->token, 'type' => PDO::PARAM_STR ]];
    $row_event = $this->data->readLineObject($strSql, $params);

    if ($row_event !== false) {

        $return_data = array();

        $return_data['TITLE'] = $row_event->EVT_TITLE;
        $return_data['EVT'] = $row_event->ID_EVT;

        // Date Begin
        $datebegin_iso8601 = null;
        if (! is_null($row_event->EVTDAT_DATBEG)) {
            $dt = new \DateTime($row_event->EVTDAT_DATBEG); // '2010-12-30 23:21:46'
            $dt->setTimezone(new \DateTimeZone($row_event->TIMEZONE_BEG)); // 'UTC'
            $datebegin_iso8601 = $dt->format('c'); //  Y-m-d\TH:i:s.u\Z
        }

        $return_data['DATBEG'] = $datebegin_iso8601;
        $return_data['TIMEZONE_BEG'] = $row_event->TIMEZONE_BEG;

        // Date End
        $dateend_iso8601 = null;
        if (! is_null($row_event->EVTDAT_DATEND)) {
            $dt2 = new \DateTime($row_event->EVTDAT_DATEND); // '2010-12-30 23:21:46'
            $dt2->setTimezone(new \DateTimeZone($row_event->TIMEZONE_END)); // 'UTC'
            $dateend_iso8601 = $dt2->format('c'); // Y-m-d\TH:i:s.u\Z
        }

        $return_data['DATEND'] = $dateend_iso8601 ;
        $return_data['TIMEZONE_END'] = $row_event->TIMEZONE_END;
    }

    return $return_data;
}


// SELECT * FROM  events_dates ed, tickets_events te, tickets_order_details tod WHERE te.ID_TICKETTYPE=tod.ID_TICKETTYPE AND te.ID_EVT=ed.ID_EVT AND ed.EVTDAT_ONLINE=1 AND ed.EVTDAT_ACTIVE=1 AND ed.EVTDAT_TOKEN=04884936968338013846 AND tod .TICDET_CODE=1398414767330

/**
 * Scan Ticket
 * @param \stdClass $form
 * @return array
 * @throws \Moviao\Database\Exception\DBException
 */
public function scanTicket(\stdClass $form) : ?array {

    $return_data = null;

    $strSql = 'SELECT ed.*, tod.*, tt.TICTYPE_NAME '
        . ',(SELECT zone_name FROM zone WHERE zone_id=ed.ID_ZONEIDBEG LIMIT 1) TIMEZONE_BEG '
        . ',(SELECT zone_name FROM zone WHERE zone_id=ed.ID_ZONEIDEND LIMIT 1) TIMEZONE_END '
        . 'FROM events_dates ed, tickets_events te, tickets_order_details tod, tickets_type tt WHERE te.ID_TICKETTYPE=tod.ID_TICKETTYPE AND te.ID_EVT=ed.ID_EVT AND ed.EVTDAT_ONLINE=1 AND ed.EVTDAT_ACTIVE=1 AND tt.ID_TICKETTYPE=tod.ID_TICKETTYPE AND ed.ID_EVT=:idevent AND ed.EVTDAT_TOKEN=:token AND tod.TICDET_CODE=:code LIMIT 1';
    $params = [
        [ 'parameter' => 'idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 'token', 'value' => $form->token, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 'code', 'value' => $form->code, 'type' => PDO::PARAM_STR ]
        ];
    $row_ticket = $this->data->readLineObject($strSql, $params);

    //error_log(var_export($row_ticket, true));

    if ($row_ticket !== false) {

        $return_data = array();

        $return_data['TICORDER'] = $row_ticket->ID_TICORDER;
        $return_data['TICKETTYPE'] = $row_ticket->ID_TICKETTYPE;
        $return_data['TICKETDET'] = $row_ticket->ID_TICKETDET;
        $return_data['EVT'] = $row_ticket->ID_EVT;

        // Date Begin
        $datebegin_iso8601 = null;
        if (! is_null($row_ticket->EVTDAT_DATBEG)) {
            $dt = new \DateTime($row_ticket->EVTDAT_DATBEG); // '2010-12-30 23:21:46'
            $dt->setTimezone(new \DateTimeZone($row_ticket->TIMEZONE_BEG)); // 'UTC'
            $datebegin_iso8601 = $dt->format('c'); //  Y-m-d\TH:i:s.u\Z
        }

        $return_data['DATBEG'] = $datebegin_iso8601;
        $return_data['TIMEZONE_BEG'] = $row_ticket->TIMEZONE_BEG;

        // Date End
        $dateend_iso8601 = null;
        if (! is_null($row_ticket->EVTDAT_DATEND)) {
            $dt2 = new \DateTime($row_ticket->EVTDAT_DATEND); // '2010-12-30 23:21:46'
            $dt2->setTimezone(new \DateTimeZone($row_ticket->TIMEZONE_END)); // 'UTC'
            $dateend_iso8601 = $dt2->format('c'); // Y-m-d\TH:i:s.u\Z
        }

        $return_data['DATEND'] = $dateend_iso8601 ;
        $return_data['TIMEZONE_END'] = $row_ticket->TIMEZONE_END;
        $return_data['FNAME'] = $row_ticket->TICDET_FNAME;
        $return_data['LNAME'] = $row_ticket->TICDET_LNAME;
        $return_data['NAME'] = $row_ticket->TICTYPE_NAME;
        $return_data['EMAIL'] = $row_ticket->TICDET_EMAIL;
        $return_data['CHECKINSTATUS'] = $row_ticket->TICDET_CHECKINSTATUS;
        $return_data['CHECKINDATE'] = $row_ticket->TICDET_CHECKINDATE;
    }

    return $return_data;
}


/**
 * Validate Ticket (Check in)
 * @param \stdClass $form
 * @return bool
 * @throws \Moviao\Database\Exception\DBException
 */
public function validateTicket(\stdClass $form) : bool {
    $strSql = 'UPDATE tickets_order_details SET TICDET_CHECKINSTATUS=1,TICDET_LOCKED=1,TICDET_CHECKINDATE=:checkindate,TICDET_DATMOD=UTC_TIMESTAMP() WHERE ID_TICORDER=:ticorder AND ID_TICKETTYPE=:tickettype AND ID_TICKETDET=:ticketdet AND TICDET_CHECKINSTATUS IS NULL AND TICDET_CHECKINDATE IS NULL LIMIT 1;';
    $params = [
        [ 'parameter' => ':ticorder', 'value' => $form->TICORDER, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':tickettype', 'value' => $form->TICKETTYPE, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':ticketdet', 'value' => $form->TICKETDET, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':checkindate', 'value' => $form->CHECKINDATE, 'type' => PDO::PARAM_STR ]
    ];
    return $this->data->executeNonQuery($strSql, $params);
}


/**
 * Unvalidate Ticket (Check out)
 * @param \stdClass $form
 * @return bool
 * @throws \Moviao\Database\Exception\DBException
 */
public function unValidateTicket(\stdClass $form) : bool {
    $strSql = 'UPDATE tickets_order_details SET TICDET_CHECKINSTATUS=null,TICDET_LOCKED=0,TICDET_CHECKINDATE=null,TICDET_DATMOD=UTC_TIMESTAMP() WHERE ID_TICORDER=:ticorder AND ID_TICKETTYPE=:tickettype AND ID_TICKETDET=:ticketdet AND TICDET_CHECKINSTATUS IS NOT NULL AND TICDET_CHECKINDATE IS NOT NULL LIMIT 1;';
    $params = [
        [ 'parameter' => ':ticorder', 'value' => $form->TICORDER, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':tickettype', 'value' => $form->TICKETTYPE, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':ticketdet', 'value' => $form->TICKETDET, 'type' => PDO::PARAM_INT ]
    ];
    return $this->data->executeNonQuery($strSql, $params);
}







/**
 * Get All Tickets From an Event
 * @param stdClass $form
 * @return array
 */
public function getGuestsList(\stdClass $form) : array {
    $return_data = array();
    $strSql = 'SELECT DISTINCT t.TICTYPE_NAME, tod.ID_TICORDER, tod.ID_TICKETTYPE, tod.ID_TICKETDET, tod.TICDET_CHECKINSTATUS, tod.TICDET_CHECKINDATE , tod.TICDET_FNAME, tod.TICDET_LNAME, tod.TICDET_EMAIL, tod.TICDET_CODE FROM tickets_type t, tickets_events te, tickets_order_details tod WHERE t.ID_TICKETTYPE=te.ID_TICKETTYPE AND t.ID_TICKETTYPE=tod.ID_TICKETTYPE AND te.TICEVT_ACTIVE=1 AND t.TICTYPE_ACTIVE=1 AND t.TICTYPE_ONLINE=1 AND te.ID_EVT=:idevent ORDER BY tod.ID_TICORDER, tod.ID_TICKETDET;';
    $params = [[ 'parameter' => 'idevent', 'value' => $form->idevent, 'type' => PDO::PARAM_INT ]];
    $rows = $this->data->readAllObject($strSql, $params);

    if (empty($rows)) {
        return $return_data;
    }

    foreach ($rows as $obj) {

        if (empty($obj)) {
            break;
        }

        $max_qte = 10;
        $tickettype = strval($obj->ID_TICKETTYPE);
        $qte_available = $this->getTicketOrderQteAvailable($tickettype);

        //exit(var_dump($qte_available));

        if ($qte_available != null && $qte_available < $max_qte && $qte_available > 0) {
            $max_qte = $qte_available;
        } else if ($qte_available !== null && $qte_available == 0) {
            $max_qte = 0;
        }

        //exit(var_dump($qte_available !== null ));

        $row['data']['TICORDER'] =  $obj->ID_TICORDER;
        $row['data']['TICKETTYPE'] =  $obj->ID_TICKETTYPE;
        $row['data']['TICKETDET'] =  $obj->ID_TICKETDET;
        $row['data']['CHECKINSTATUS'] =  $obj->TICDET_CHECKINSTATUS;
        $row['data']['TICKETCODE'] = $obj->TICDET_CODE;        
        
        // CheckIN Date 
        $checkindate_iso8601 = null;
        if (! is_null($obj->TICDET_CHECKINDATE)) {
            $dt = new \DateTime($obj->TICDET_CHECKINDATE); // '2010-12-30 23:21:46'
            $dt->setTimezone(new \DateTimeZone('UTC'));
            $checkindate_iso8601 = $dt->format('c'); //  Y-m-d\TH:i:s.u\Z
        }

        $row['data']['CHECKINDATE'] =  $checkindate_iso8601;
        $row['data']['NAME'] =  $obj->TICTYPE_NAME;
        $row['data']['FNAME'] =  $obj->TICDET_FNAME;
        $row['data']['LNAME'] =  $obj->TICDET_LNAME;        
        $row['data']['EMAIL '] =  $obj->TICDET_EMAIL;
        
        $return_data[]  = $row['data'];
    }
    return $return_data;
}









}