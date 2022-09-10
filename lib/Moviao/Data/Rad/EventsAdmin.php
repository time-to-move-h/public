<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2020-2021.
// Data API Events_admin
namespace Moviao\Data\Rad;
use PDO;

class EventsAdmin {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\EventsAdminData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO events_admin (EVTADM_ACTIVE, EVTADM_DATINS, EVTADM_DATMOD, ID_EVT, ID_USR) VALUES (?,?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((! is_numeric($fdata->get_ACTIVE())) ? null :  (int) $fdata->get_ACTIVE());
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
       return false;
    }
	$row1 = $fdata->get_DATINS();
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_STR)) {
       return false;
    }
	$row2 = $fdata->get_DATMOD();
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
       return false;
    }
	$row3 = ((! is_numeric($fdata->get_EVT())) ? null :  (int) $fdata->get_EVT());
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_INT)) {
       return false;
    }
	$row4 = ((! is_numeric($fdata->get_USR())) ? null :  (int) $fdata->get_USR());
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_INT)) {
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
    
    $strSql = 'SELECT * FROM events_admin';
                
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
        $row['data']['ACTIVE'] =  $obj->EVTADM_ACTIVE;
        $row['data']['DATINS'] =  $obj->EVTADM_DATINS;
        $row['data']['DATMOD'] =  $obj->EVTADM_DATMOD;
        $row['data']['EVT'] =  $obj->ID_EVT;
        $row['data']['USR'] =  $obj->ID_USR;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\EventsAdminData {
     $ID_EVT = isset($form->EVT) ? filter_var($form->EVT, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_USR = isset($form->USR) ? filter_var($form->USR, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVTADM_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVTADM_DATINS = isset($form->DATINS) ? $form->DATINS : null; 
     $EVTADM_DATMOD = isset($form->DATMOD) ? $form->DATMOD : null;        
     $fdata = new \Moviao\Data\Rad\EventsAdminData();   
     $fdata->set_EVT($ID_EVT);
     $fdata->set_USR($ID_USR);
     $fdata->set_ACTIVE($EVTADM_ACTIVE);
     $fdata->set_DATINS($EVTADM_DATINS);
     $fdata->set_DATMOD($EVTADM_DATMOD); 
     return $fdata;
}
}