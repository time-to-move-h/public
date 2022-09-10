<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// Data API Events
namespace Moviao\Data\Rad;
use PDO;

class Events {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\EventsData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO events (EVT_ACTIVE, EVT_CITY, EVT_CONFIRM, EVT_COUNTRY, EVT_DATINS, EVT_DATMOD, EVT_DESCL, EVT_DESCRDV, EVT_FREE, EVT_ISONLINE, EVT_LANG, EVT_LOCATIONP, EVT_MAXUSE, EVT_MULTI, EVT_ONLINE, EVT_OSMID, EVT_PCODE, EVT_PICTURE, EVT_PICTURE_MIN, EVT_STATE, EVT_STREET, EVT_STREET2, EVT_STREETN, EVT_TITLE, EVT_URL, EVT_URLLINK, EVT_VENUE, ID_CHA, ID_COUNTRY_CODE, ID_EVTACT, ID_EVTLINK, ID_EVTPOI, ID_EVTTYP, ID_EVTVIS) VALUES (?,?,?,?,?,?,?,?,?,?,?,ST_PointFromText(?),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((! is_numeric($fdata->get_ACTIVE())) ? null :  (int) $fdata->get_ACTIVE());
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
       return false;
    }
	$row1 = $fdata->get_CITY();
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_STR)) {
       return false;
    }
	$row2 = ((! is_numeric($fdata->get_CONFIRM())) ? null :  (int) $fdata->get_CONFIRM());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_INT)) {
       return false;
    }
	$row3 = $fdata->get_COUNTRY();
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_STR)) {
       return false;
    }
	$row4 = $fdata->get_DATINS();
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_STR)) {
       return false;
    }
	$row5 = $fdata->get_DATMOD();
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
       return false;
    }
	$row6 = $fdata->get_DESCL();
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_STR)) {
       return false;
    }
	$row7 = $fdata->get_DESCRDV();
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_STR)) {
       return false;
    }
	$row8 = ((! is_numeric($fdata->get_FREE())) ? null :  (int) $fdata->get_FREE());
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_INT)) {
       return false;
    }
	$row9 = ((! is_numeric($fdata->get_ISONLINE())) ? null :  (int) $fdata->get_ISONLINE());
	if (! $this->data->bindParam(10,$row9, PDO::PARAM_INT)) {
       return false;
    }
	$row10 = $fdata->get_LANG();
	if (! $this->data->bindParam(11,$row10, PDO::PARAM_STR)) {
       return false;
    }
	$row11 = $fdata->get_LOCATIONP();
	if (! $this->data->bindParam(12,$row11, PDO::PARAM_STR)) {
       return false;
    }
	$row12 = ((! is_numeric($fdata->get_MAXUSE())) ? null :  (int) $fdata->get_MAXUSE());
	if (! $this->data->bindParam(13,$row12, PDO::PARAM_INT)) {
       return false;
    }
	$row13 = ((! is_numeric($fdata->get_MULTI())) ? null :  (int) $fdata->get_MULTI());
	if (! $this->data->bindParam(14,$row13, PDO::PARAM_INT)) {
       return false;
    }
	$row14 = ((! is_numeric($fdata->get_ONLINE())) ? null :  (int) $fdata->get_ONLINE());
	if (! $this->data->bindParam(15,$row14, PDO::PARAM_INT)) {
       return false;
    }
	$row15 = ((! is_numeric($fdata->get_OSMID())) ? null :  (int) $fdata->get_OSMID());
	if (! $this->data->bindParam(16,$row15, PDO::PARAM_INT)) {
       return false;
    }
	$row16 = $fdata->get_PCODE();
	if (! $this->data->bindParam(17,$row16, PDO::PARAM_STR)) {
       return false;
    }
	$row17 = $fdata->get_PICTURE();
	if (! $this->data->bindParam(18,$row17, PDO::PARAM_STR)) {
       return false;
    }
	$row18 = $fdata->get_PICTURE_MIN();
	if (! $this->data->bindParam(19,$row18, PDO::PARAM_STR)) {
       return false;
    }
	$row19 = $fdata->get_STATE();
	if (! $this->data->bindParam(20,$row19, PDO::PARAM_STR)) {
       return false;
    }
	$row20 = $fdata->get_STREET();
	if (! $this->data->bindParam(21,$row20, PDO::PARAM_STR)) {
       return false;
    }
	$row21 = $fdata->get_STREET2();
	if (! $this->data->bindParam(22,$row21, PDO::PARAM_STR)) {
       return false;
    }
	$row22 = $fdata->get_STREETN();
	if (! $this->data->bindParam(23,$row22, PDO::PARAM_STR)) {
       return false;
    }
	$row23 = $fdata->get_TITLE();
	if (! $this->data->bindParam(24,$row23, PDO::PARAM_STR)) {
       return false;
    }
	$row24 = $fdata->get_URL();
	if (! $this->data->bindParam(25,$row24, PDO::PARAM_STR)) {
       return false;
    }
	$row25 = $fdata->get_URLLINK();
	if (! $this->data->bindParam(26,$row25, PDO::PARAM_STR)) {
       return false;
    }
	$row26 = $fdata->get_VENUE();
	if (! $this->data->bindParam(27,$row26, PDO::PARAM_STR)) {
       return false;
    }
	$row27 = ((! is_numeric($fdata->get_CHA())) ? null :  (int) $fdata->get_CHA());
	if (! $this->data->bindParam(28,$row27, PDO::PARAM_INT)) {
       return false;
    }
	$row28 = ((! is_numeric($fdata->get_COUNTRY_CODE())) ? null :  (int) $fdata->get_COUNTRY_CODE());
	if (! $this->data->bindParam(29,$row28, PDO::PARAM_INT)) {
       return false;
    }
	$row29 = $fdata->get_EVTACT();
	if (! $this->data->bindParam(30,$row29, PDO::PARAM_STR)) {
       return false;
    }
	$row30 = ((! is_numeric($fdata->get_EVTLINK())) ? null :  (int) $fdata->get_EVTLINK());
	if (! $this->data->bindParam(31,$row30, PDO::PARAM_INT)) {
       return false;
    }
	$row31 = $fdata->get_EVTPOI();
	if (! $this->data->bindParam(32,$row31, PDO::PARAM_STR)) {
       return false;
    }
	$row32 = ((! is_numeric($fdata->get_EVTTYP())) ? null :  (int) $fdata->get_EVTTYP());
	if (! $this->data->bindParam(33,$row32, PDO::PARAM_INT)) {
       return false;
    }
	$row33 = ((! is_numeric($fdata->get_EVTVIS())) ? null :  (int) $fdata->get_EVTVIS());
	if (! $this->data->bindParam(34,$row33, PDO::PARAM_INT)) {
       return false;
    }
	
                
    if (! $this->data->execute()) {
        error_log("RAD execute : " . var_export($this->data->errorInfo(),true));
        return false;
    }

    $rowcount = $stmt->rowCount();

    if ($rowcount > 0) {
        $result = true;
    }

    } catch (\Error $e) {
        error_log("RAD Create >> : $e");
    }
    return $result;
}

public function show($where,$orderby = null,$limit = null) : array {
    $return_data = array();    
    try { 
    
    $strSql = 'SELECT * FROM events';
                
    // Where Clause ---------------------
    if (null !== $where) {
       if (\is_array($where)) {
            $strSql .= " WHERE ";                
            $i = 0;
            foreach ($where as $key => $valor) {
                if ($i > 0) $strSql .= " AND ";
                $strSql .= " {$valor['name']} = ? ";                                
                $i++;
            }
        }        
    }
    //-----------------------------------
    
    // Order by Clause ------------------
    if (null !== $orderby) {
        if (\is_string($orderby)) {
            $strSql .= " ORDER BY $orderby";                           
        }        
    }
    //-----------------------------------
    
    // Limit Clause ---------------------
    if (null !== $limit) {
        if (\is_int($limit)) {
            $strSql .= " LIMIT $limit";                           
        }        
    }
    //-----------------------------------
        
    $stmt = $this->data->prepare($strSql);       
    if ($stmt === false) {       
        return $return_data;
    }   
        
    // Where Value ---------------------
    if (null !== $where) {
       if (\is_array($where)) {
            $i = 1;
            foreach ($where as $key => $valor) {
                if (! $this->data->bindParam($i,$valor['value'], $valor['type'])) {
                    break;
                }
                $i++;
            }
        }        
    }
    //-----------------------------------
    
    if (! $this->data->execute()) {       
        return $return_data;
    }      
      
    $i=0;
    while ($obj = $this->data->fetchObject($stmt)) { 
        if (null === $obj) {
            break;
        }
        $row['data']['ACTIVE'] =  $obj->EVT_ACTIVE;
        $row['data']['CITY'] =  $obj->EVT_CITY;
        $row['data']['CONFIRM'] =  $obj->EVT_CONFIRM;
        $row['data']['COUNTRY'] =  $obj->EVT_COUNTRY;
        $row['data']['DATINS'] =  $obj->EVT_DATINS;
        $row['data']['DATMOD'] =  $obj->EVT_DATMOD;
        $row['data']['DESCL'] =  $obj->EVT_DESCL;
        $row['data']['DESCRDV'] =  $obj->EVT_DESCRDV;
        $row['data']['FREE'] =  $obj->EVT_FREE;
        $row['data']['ISONLINE'] =  $obj->EVT_ISONLINE;
        $row['data']['LANG'] =  $obj->EVT_LANG;
        $row['data']['LOCATIONP'] =  $obj->EVT_LOCATIONP;
        $row['data']['MAXUSE'] =  $obj->EVT_MAXUSE;
        $row['data']['MULTI'] =  $obj->EVT_MULTI;
        $row['data']['ONLINE'] =  $obj->EVT_ONLINE;
        $row['data']['OSMID'] =  $obj->EVT_OSMID;
        $row['data']['PCODE'] =  $obj->EVT_PCODE;
        $row['data']['PICTURE'] =  $obj->EVT_PICTURE;
        $row['data']['PICTURE_MIN'] =  $obj->EVT_PICTURE_MIN;
        $row['data']['STATE'] =  $obj->EVT_STATE;
        $row['data']['STREET'] =  $obj->EVT_STREET;
        $row['data']['STREET2'] =  $obj->EVT_STREET2;
        $row['data']['STREETN'] =  $obj->EVT_STREETN;
        $row['data']['TITLE'] =  $obj->EVT_TITLE;
        $row['data']['URL'] =  $obj->EVT_URL;
        $row['data']['URLLINK'] =  $obj->EVT_URLLINK;
        $row['data']['VENUE'] =  $obj->EVT_VENUE;
        $row['data']['CHA'] =  $obj->ID_CHA;
        $row['data']['COUNTRY_CODE'] =  $obj->ID_COUNTRY_CODE;
        $row['data']['EVT'] =  $obj->ID_EVT;
        $row['data']['EVTACT'] =  $obj->ID_EVTACT;
        $row['data']['EVTLINK'] =  $obj->ID_EVTLINK;
        $row['data']['EVTPOI'] =  $obj->ID_EVTPOI;
        $row['data']['EVTTYP'] =  $obj->ID_EVTTYP;
        $row['data']['EVTVIS'] =  $obj->ID_EVTVIS;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\EventsData {
     $ID_EVT = isset($form->EVT) ? filter_var($form->EVT, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVT_URLLINK = isset($form->URLLINK) ? $this->cleanData($form->URLLINK) : null; 
     $ID_EVTTYP = isset($form->EVTTYP) ? filter_var($form->EVTTYP, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_CHA = isset($form->CHA) ? filter_var($form->CHA, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_EVTLINK = isset($form->EVTLINK) ? filter_var($form->EVTLINK, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_EVTACT = isset($form->EVTACT) ? $this->cleanData($form->EVTACT) : null; 
     $ID_EVTPOI = isset($form->EVTPOI) ? $this->cleanData($form->EVTPOI) : null; 
     $ID_EVTVIS = isset($form->EVTVIS) ? filter_var($form->EVTVIS, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVT_TITLE = isset($form->TITLE) ? $this->cleanData($form->TITLE) : null; 
     $EVT_DESCL = isset($form->DESCL) ? $this->cleanData($form->DESCL) : null; 
     $EVT_FREE = isset($form->FREE) ? filter_var($form->FREE, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVT_MAXUSE = isset($form->MAXUSE) ? filter_var($form->MAXUSE, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVT_MULTI = isset($form->MULTI) ? filter_var($form->MULTI, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVT_STREET = isset($form->STREET) ? $this->cleanData($form->STREET) : null; 
     $EVT_STREETN = isset($form->STREETN) ? $this->cleanData($form->STREETN) : null; 
     $EVT_VENUE = isset($form->VENUE) ? $this->cleanData($form->VENUE) : null; 
     $EVT_PCODE = isset($form->PCODE) ? $this->cleanData($form->PCODE) : null; 
     $EVT_STREET2 = isset($form->STREET2) ? $this->cleanData($form->STREET2) : null; 
     $EVT_DESCRDV = isset($form->DESCRDV) ? $this->cleanData($form->DESCRDV) : null; 
     $EVT_CITY = isset($form->CITY) ? $this->cleanData($form->CITY) : null; 
     $EVT_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVT_STATE = isset($form->STATE) ? $this->cleanData($form->STATE) : null; 
     $EVT_ONLINE = isset($form->ONLINE) ? filter_var($form->ONLINE, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVT_COUNTRY = isset($form->COUNTRY) ? $this->cleanData($form->COUNTRY) : null; 
     $EVT_CONFIRM = isset($form->CONFIRM) ? filter_var($form->CONFIRM, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVT_LOCATIONP = isset($form->LOCATIONP) ? $form->LOCATIONP : null; 
     $ID_COUNTRY_CODE = isset($form->COUNTRY_CODE) ? filter_var($form->COUNTRY_CODE, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVT_DATINS = isset($form->DATINS) ? $form->DATINS : null; 
     $EVT_PICTURE = isset($form->PICTURE) ? $this->cleanData($form->PICTURE) : null; 
     $EVT_OSMID = isset($form->OSMID) ? filter_var($form->OSMID, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVT_DATMOD = isset($form->DATMOD) ? $form->DATMOD : null; 
     $EVT_URL = isset($form->URL) ? $this->cleanData($form->URL) : null; 
     $EVT_PICTURE_MIN = isset($form->PICTURE_MIN) ? $this->cleanData($form->PICTURE_MIN) : null; 
     $EVT_ISONLINE = isset($form->ISONLINE) ? filter_var($form->ISONLINE, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVT_LANG = isset($form->LANG) ? $this->cleanData($form->LANG) : null;        
     $fdata = new \Moviao\Data\Rad\EventsData();   
     $fdata->set_EVT($ID_EVT);
     $fdata->set_URLLINK($EVT_URLLINK);
     $fdata->set_EVTTYP($ID_EVTTYP);
     $fdata->set_CHA($ID_CHA);
     $fdata->set_EVTLINK($ID_EVTLINK);
     $fdata->set_EVTACT($ID_EVTACT);
     $fdata->set_EVTPOI($ID_EVTPOI);
     $fdata->set_EVTVIS($ID_EVTVIS);
     $fdata->set_TITLE($EVT_TITLE);
     $fdata->set_DESCL($EVT_DESCL);
     $fdata->set_FREE($EVT_FREE);
     $fdata->set_MAXUSE($EVT_MAXUSE);
     $fdata->set_MULTI($EVT_MULTI);
     $fdata->set_STREET($EVT_STREET);
     $fdata->set_STREETN($EVT_STREETN);
     $fdata->set_VENUE($EVT_VENUE);
     $fdata->set_PCODE($EVT_PCODE);
     $fdata->set_STREET2($EVT_STREET2);
     $fdata->set_DESCRDV($EVT_DESCRDV);
     $fdata->set_CITY($EVT_CITY);
     $fdata->set_ACTIVE($EVT_ACTIVE);
     $fdata->set_STATE($EVT_STATE);
     $fdata->set_ONLINE($EVT_ONLINE);
     $fdata->set_COUNTRY($EVT_COUNTRY);
     $fdata->set_CONFIRM($EVT_CONFIRM);
     $fdata->set_LOCATIONP($EVT_LOCATIONP);
     $fdata->set_COUNTRY_CODE($ID_COUNTRY_CODE);
     $fdata->set_DATINS($EVT_DATINS);
     $fdata->set_PICTURE($EVT_PICTURE);
     $fdata->set_OSMID($EVT_OSMID);
     $fdata->set_DATMOD($EVT_DATMOD);
     $fdata->set_URL($EVT_URL);
     $fdata->set_PICTURE_MIN($EVT_PICTURE_MIN);
     $fdata->set_ISONLINE($EVT_ISONLINE);
     $fdata->set_LANG($EVT_LANG); 
     return $fdata;
}

private function cleanData($data)
{
    //$data = htmlspecialchars($data);
    //$data = \strip_tags($data);
    //$data = stripslashes($data);
    $data = trim($data);
    return $data;
}


}