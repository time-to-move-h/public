<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2020-2021.
// Data API Events_dates
namespace Moviao\Data\Rad;
use PDO;

class EventsDates {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\EventsDatesData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO events_dates (EVTDAT_ACTIVE, EVTDAT_ALLDAY, EVTDAT_DATBEG, EVTDAT_DATEND, EVTDAT_DATINS, EVTDAT_DATMOD, EVTDAT_ONLINE, EVTDAT_TOKEN, ID_EVT, ID_ZONEIDBEG, ID_ZONEIDEND) VALUES (?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((! is_numeric($fdata->get_ACTIVE())) ? null :  (int) $fdata->get_ACTIVE());
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
       return false;
    }
	$row1 = ((! is_numeric($fdata->get_ALLDAY())) ? null :  (int) $fdata->get_ALLDAY());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_INT)) {
       return false;
    }
	$row2 = $fdata->get_DATBEG();
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
       return false;
    }
	$row3 = $fdata->get_DATEND();
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
	$row6 = ((! is_numeric($fdata->get_ONLINE())) ? null :  (int) $fdata->get_ONLINE());
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_INT)) {
       return false;
    }
	$row7 = $fdata->get_TOKEN();
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_STR)) {
       return false;
    }
	$row8 = ((! is_numeric($fdata->get_EVT())) ? null :  (int) $fdata->get_EVT());
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_INT)) {
       return false;
    }
	$row9 = ((! is_numeric($fdata->get_ZONEIDBEG())) ? null :  (int) $fdata->get_ZONEIDBEG());
	if (! $this->data->bindParam(10,$row9, PDO::PARAM_INT)) {
       return false;
    }
	$row10 = ((! is_numeric($fdata->get_ZONEIDEND())) ? null :  (int) $fdata->get_ZONEIDEND());
	if (! $this->data->bindParam(11,$row10, PDO::PARAM_INT)) {
       return false;
    }
	
                
    if (! $this->data->execute()) {
        return false;
    }
    $rowcount = $stmt->rowCount();
    if ($rowcount > 0) $result = true;

    } catch (\Error $e) {
        error_log("RAD Create >> : $e");
    }
    return $result;
}

public function show($where,$orderby = null,$limit = null) : array {
    $return_data = array();    
    try { 
    
    $strSql = 'SELECT * FROM events_dates';
                
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
        $row['data']['ACTIVE'] =  $obj->EVTDAT_ACTIVE;
        $row['data']['ALLDAY'] =  $obj->EVTDAT_ALLDAY;
        $row['data']['DATBEG'] =  $obj->EVTDAT_DATBEG;
        $row['data']['DATEND'] =  $obj->EVTDAT_DATEND;
        $row['data']['DATINS'] =  $obj->EVTDAT_DATINS;
        $row['data']['DATMOD'] =  $obj->EVTDAT_DATMOD;
        $row['data']['ONLINE'] =  $obj->EVTDAT_ONLINE;
        $row['data']['TOKEN'] =  $obj->EVTDAT_TOKEN;
        $row['data']['EVT'] =  $obj->ID_EVT;
        $row['data']['ZONEIDBEG'] =  $obj->ID_ZONEIDBEG;
        $row['data']['ZONEIDEND'] =  $obj->ID_ZONEIDEND;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\EventsDatesData {
     $ID_EVT = isset($form->EVT) ? filter_var($form->EVT, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVTDAT_DATBEG = isset($form->DATBEG) ? $form->DATBEG : null; 
     $EVTDAT_DATEND = isset($form->DATEND) ? $form->DATEND : null; 
     $ID_ZONEIDBEG = isset($form->ZONEIDBEG) ? filter_var($form->ZONEIDBEG, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_ZONEIDEND = isset($form->ZONEIDEND) ? filter_var($form->ZONEIDEND, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVTDAT_ALLDAY = isset($form->ALLDAY) ? filter_var($form->ALLDAY, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVTDAT_ONLINE = isset($form->ONLINE) ? filter_var($form->ONLINE, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVTDAT_TOKEN = isset($form->TOKEN) ? filter_var($form->TOKEN, FILTER_SANITIZE_STRING): null; 
     $EVTDAT_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVTDAT_DATINS = isset($form->DATINS) ? $form->DATINS : null; 
     $EVTDAT_DATMOD = isset($form->DATMOD) ? $form->DATMOD : null;        
     $fdata = new \Moviao\Data\Rad\EventsDatesData();   
     $fdata->set_EVT($ID_EVT);
     $fdata->set_DATBEG($EVTDAT_DATBEG);
     $fdata->set_DATEND($EVTDAT_DATEND);
     $fdata->set_ZONEIDBEG($ID_ZONEIDBEG);
     $fdata->set_ZONEIDEND($ID_ZONEIDEND);
     $fdata->set_ALLDAY($EVTDAT_ALLDAY);
     $fdata->set_ONLINE($EVTDAT_ONLINE);
     $fdata->set_TOKEN($EVTDAT_TOKEN);
     $fdata->set_ACTIVE($EVTDAT_ACTIVE);
     $fdata->set_DATINS($EVTDAT_DATINS);
     $fdata->set_DATMOD($EVTDAT_DATMOD); 
     return $fdata;
}
}