<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2020-2021.
// Data API Channels_admin
namespace Moviao\Data\Rad;
use PDO;

class ChannelsAdmin {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\ChannelsAdminData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO channels_admin (CHAADM_ACTIVE, CHAADM_DATINS, CHAADM_DATMOD, ID_CHA, ID_USR) VALUES (?,?,?,?,?)';
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
	$row3 = ((! is_numeric($fdata->get_CHA())) ? null :  (int) $fdata->get_CHA());
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
    
    $strSql = 'SELECT * FROM channels_admin';
                
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
        $row['data']['ACTIVE'] =  $obj->CHAADM_ACTIVE;
        $row['data']['DATINS'] =  $obj->CHAADM_DATINS;
        $row['data']['DATMOD'] =  $obj->CHAADM_DATMOD;
        $row['data']['CHA'] =  $obj->ID_CHA;
        $row['data']['USR'] =  $obj->ID_USR;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\ChannelsAdminData {
     $ID_CHA = isset($form->CHA) ? filter_var($form->CHA, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_USR = isset($form->USR) ? filter_var($form->USR, FILTER_SANITIZE_NUMBER_INT): null; 
     $CHAADM_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): null; 
     $CHAADM_DATINS = isset($form->DATINS) ? $form->DATINS : null; 
     $CHAADM_DATMOD = isset($form->DATMOD) ? $form->DATMOD : null;        
     $fdata = new \Moviao\Data\Rad\ChannelsAdminData();   
     $fdata->set_CHA($ID_CHA);
     $fdata->set_USR($ID_USR);
     $fdata->set_ACTIVE($CHAADM_ACTIVE);
     $fdata->set_DATINS($CHAADM_DATINS);
     $fdata->set_DATMOD($CHAADM_DATMOD); 
     return $fdata;
}
}