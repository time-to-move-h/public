<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Data API Events_list
namespace Moviao\Data\Rad;
use PDO;

class EventsList {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\EventsListData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO events_list (EVTLST_ACTIVE, EVTLST_CONFIRM, EVTLST_DATBEG, EVTLST_DATCONF, EVTLST_DATEINS, EVTLST_DATMOD, EVTLST_N_FRIEND, EVTLST_STATUS, EVTLST_WAIT, ID_EVT, ID_EVTLST, ID_EVTROLE, ID_USR) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((empty($fdata->get_ACTIVE())) ? 1 :  (int)($fdata->get_ACTIVE()));
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
             return false;
        }
	$row1 = ((empty($fdata->get_CONFIRM())) ? 0 :  (int)($fdata->get_CONFIRM()));
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_INT)) {
             return false;
        }
	$row2 = ((empty($fdata->get_DATBEG())) ? NULL :  $fdata->get_DATBEG());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
             return false;
        }
	$row3 = ((empty($fdata->get_DATCONF())) ? NULL :  $fdata->get_DATCONF());
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_STR)) {
             return false;
        }
	$row4 = ((empty($fdata->get_DATEINS())) ? date('Y-m-d H:i:s') :  $fdata->get_DATEINS());
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_STR)) {
             return false;
        }
	$row5 = ((empty($fdata->get_DATMOD())) ? NULL :  $fdata->get_DATMOD());
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
             return false;
        }
	$row6 = ((empty($fdata->get_N_FRIEND())) ? 0 :  (int)($fdata->get_N_FRIEND()));
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_INT)) {
             return false;
        }
	$row7 = ((empty($fdata->get_STATUS())) ? '' :  $fdata->get_STATUS());
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_STR)) {
             return false;
        }
	$row8 = ((empty($fdata->get_WAIT())) ? 0 :  (int)($fdata->get_WAIT()));
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_INT)) {
             return false;
        }
	$row9 = ((empty($fdata->get_EVT())) ? 0 :  $fdata->get_EVT());
	if (! $this->data->bindParam(10,$row9, PDO::PARAM_INT)) {
             return false;
        }
	$row10 = ((empty($fdata->get_EVTLST())) ? 0 :  $fdata->get_EVTLST());
	if (! $this->data->bindParam(11,$row10, PDO::PARAM_INT)) {
             return false;
        }
	$row11 = ((empty($fdata->get_EVTROLE())) ? 0 :  (int)($fdata->get_EVTROLE()));
	if (! $this->data->bindParam(12,$row11, PDO::PARAM_INT)) {
             return false;
        }
	$row12 = ((empty($fdata->get_USR())) ? 0 :  $fdata->get_USR());
	if (! $this->data->bindParam(13,$row12, PDO::PARAM_INT)) {
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
    
    $strSql = "SELECT * FROM events_list";        
        
        
    // Where Clause ---------------------
    if (null !== $where) {
       if (is_array($where)) {
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
        if (is_string($orderby)) {
            $strSql .= " ORDER BY $orderby";                           
        }        
    }
    //-----------------------------------
    
    // Limit Clause ---------------------
    if (null !== $limit) {
        if (is_int($limit)) {
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
       if (is_array($where)) {            
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
        if (null === $obj) break;
        $row['data']['ACTIVE'] =  $obj->EVTLST_ACTIVE;
        $row['data']['CONFIRM'] =  $obj->EVTLST_CONFIRM;
        $row['data']['DATBEG'] =  $obj->EVTLST_DATBEG;
        $row['data']['DATCONF'] =  $obj->EVTLST_DATCONF;
        $row['data']['DATEINS'] =  $obj->EVTLST_DATEINS;
        $row['data']['DATMOD'] =  $obj->EVTLST_DATMOD;
        $row['data']['N_FRIEND'] =  $obj->EVTLST_N_FRIEND;
        $row['data']['STATUS'] =  $obj->EVTLST_STATUS;
        $row['data']['WAIT'] =  $obj->EVTLST_WAIT;
        $row['data']['EVT'] =  $obj->ID_EVT;
        $row['data']['EVTLST'] =  $obj->ID_EVTLST;
        $row['data']['EVTROLE'] =  $obj->ID_EVTROLE;
        $row['data']['USR'] =  $obj->ID_USR;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\EventsListData {
     $ID_EVT = isset($form->EVT) ? filter_var($form->EVT, FILTER_SANITIZE_STRING): NULL; 
     $ID_USR = isset($form->USR) ? filter_var($form->USR, FILTER_SANITIZE_STRING): NULL; 
     $ID_EVTLST = isset($form->EVTLST) ? filter_var($form->EVTLST, FILTER_SANITIZE_STRING): NULL; 
     $EVTLST_N_FRIEND = isset($form->N_FRIEND) ? filter_var($form->N_FRIEND, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $EVTLST_WAIT = isset($form->WAIT) ? filter_var($form->WAIT, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $EVTLST_DATBEG = isset($form->DATBEG) ? filter_var($form->DATBEG, FILTER_SANITIZE_STRING): NULL; 
     $EVTLST_CONFIRM = isset($form->CONFIRM) ? filter_var($form->CONFIRM, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $ID_EVTROLE = isset($form->EVTROLE) ? filter_var($form->EVTROLE, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $EVTLST_STATUS = isset($form->STATUS) ? filter_var($form->STATUS, FILTER_SANITIZE_STRING): NULL; 
     $EVTLST_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $EVTLST_DATEINS = isset($form->DATEINS) ? filter_var($form->DATEINS, FILTER_SANITIZE_STRING): NULL; 
     $EVTLST_DATCONF = isset($form->DATCONF) ? filter_var($form->DATCONF, FILTER_SANITIZE_STRING): NULL; 
     $EVTLST_DATMOD = isset($form->DATMOD) ? filter_var($form->DATMOD, FILTER_SANITIZE_STRING): NULL;        
     $fdata = new \Moviao\Data\Rad\EventsListData();   
     $fdata->set_EVT($ID_EVT);
     $fdata->set_USR($ID_USR);
     $fdata->set_EVTLST($ID_EVTLST);
     $fdata->set_N_FRIEND($EVTLST_N_FRIEND);
     $fdata->set_WAIT($EVTLST_WAIT);
     $fdata->set_DATBEG($EVTLST_DATBEG);
     $fdata->set_CONFIRM($EVTLST_CONFIRM);
     $fdata->set_EVTROLE($ID_EVTROLE);
     $fdata->set_STATUS($EVTLST_STATUS);
     $fdata->set_ACTIVE($EVTLST_ACTIVE);
     $fdata->set_DATEINS($EVTLST_DATEINS);
     $fdata->set_DATCONF($EVTLST_DATCONF);
     $fdata->set_DATMOD($EVTLST_DATMOD); 
     return $fdata;
}
}