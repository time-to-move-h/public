<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2018-2019.
// Data API Channels_list
namespace Moviao\Data\Rad;
use PDO;

class ChannelsList {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\ChannelsListData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO channels_list (CHALST_ACTIVE, CHALST_CONFIRM, CHALST_DATECONF, CHALST_DATEINS, CHALST_DATMOD, CHALST_STATUS, ID_CHA, ID_CHALST, ID_CHAROLE, ID_USR) VALUES (?,?,?,?,?,?,?,?,?,?)";
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
	$row2 = ((empty($fdata->get_DATECONF())) ? NULL :  $fdata->get_DATECONF());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
             return false;
        }
	$row3 = ((empty($fdata->get_DATEINS())) ? date('Y-m-d H:i:s') :  $fdata->get_DATEINS());
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_STR)) {
             return false;
        }
	$row4 = ((empty($fdata->get_DATMOD())) ? NULL :  $fdata->get_DATMOD());
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_STR)) {
             return false;
        }
	$row5 = ((empty($fdata->get_STATUS())) ? '' :  $fdata->get_STATUS());
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
             return false;
        }
	$row6 = ((empty($fdata->get_CHA())) ? 0 :  $fdata->get_CHA());
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_INT)) {
             return false;
        }
	$row7 = ((empty($fdata->get_CHALST())) ? 0 :  $fdata->get_CHALST());
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_INT)) {
             return false;
        }
	$row8 = ((empty($fdata->get_CHAROLE())) ? 0 :  (int)($fdata->get_CHAROLE()));
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_INT)) {
             return false;
        }
	$row9 = ((empty($fdata->get_USR())) ? 0 :  $fdata->get_USR());
	if (! $this->data->bindParam(10,$row9, PDO::PARAM_INT)) {
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
    
    $strSql = "SELECT * FROM channels_list";        
        
        
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
        $row['data']['ACTIVE'] =  $obj->CHALST_ACTIVE;
        $row['data']['CONFIRM'] =  $obj->CHALST_CONFIRM;
        $row['data']['DATECONF'] =  $obj->CHALST_DATECONF;
        $row['data']['DATEINS'] =  $obj->CHALST_DATEINS;
        $row['data']['DATMOD'] =  $obj->CHALST_DATMOD;
        $row['data']['STATUS'] =  $obj->CHALST_STATUS;
        $row['data']['CHA'] =  $obj->ID_CHA;
        $row['data']['CHALST'] =  $obj->ID_CHALST;
        $row['data']['CHAROLE'] =  $obj->ID_CHAROLE;
        $row['data']['USR'] =  $obj->ID_USR;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\ChannelsListData {
     $ID_CHA = isset($form->CHA) ? filter_var($form->CHA, FILTER_SANITIZE_STRING): NULL; 
     $ID_USR = isset($form->USR) ? filter_var($form->USR, FILTER_SANITIZE_STRING): NULL; 
     $ID_CHALST = isset($form->CHALST) ? filter_var($form->CHALST, FILTER_SANITIZE_STRING): NULL; 
     $ID_CHAROLE = isset($form->CHAROLE) ? filter_var($form->CHAROLE, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $CHALST_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $CHALST_CONFIRM = isset($form->CONFIRM) ? filter_var($form->CONFIRM, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $CHALST_STATUS = isset($form->STATUS) ? filter_var($form->STATUS, FILTER_SANITIZE_STRING): NULL; 
     $CHALST_DATECONF = isset($form->DATECONF) ? filter_var($form->DATECONF, FILTER_SANITIZE_STRING): NULL; 
     $CHALST_DATEINS = isset($form->DATEINS) ? filter_var($form->DATEINS, FILTER_SANITIZE_STRING): NULL; 
     $CHALST_DATMOD = isset($form->DATMOD) ? filter_var($form->DATMOD, FILTER_SANITIZE_STRING): NULL;        
     $fdata = new \Moviao\Data\Rad\ChannelsListData();   
     $fdata->set_CHA($ID_CHA);
     $fdata->set_USR($ID_USR);
     $fdata->set_CHALST($ID_CHALST);
     $fdata->set_CHAROLE($ID_CHAROLE);
     $fdata->set_ACTIVE($CHALST_ACTIVE);
     $fdata->set_CONFIRM($CHALST_CONFIRM);
     $fdata->set_STATUS($CHALST_STATUS);
     $fdata->set_DATECONF($CHALST_DATECONF);
     $fdata->set_DATEINS($CHALST_DATEINS);
     $fdata->set_DATMOD($CHALST_DATMOD); 
     return $fdata;
}
}