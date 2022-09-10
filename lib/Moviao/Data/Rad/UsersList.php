<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Data API Users_list
namespace Moviao\Data\Rad;
use PDO;

class UsersList {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\UsersListData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO users_list (ID_USR, ID_USR2, USR_ACTIVE, USR_CONFIRM, USR_DATCONF, USR_DATINS, USR_DATMOD, USR_IGNORE, USR_REQ) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((empty($fdata->get_USR())) ? 0 :  $fdata->get_USR());
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
             return false;
        }
	$row1 = ((empty($fdata->get_USR2())) ? 0 :  $fdata->get_USR2());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_INT)) {
             return false;
        }
	$row2 = ((empty($fdata->get_ACTIVE())) ? 0 :  (int)($fdata->get_ACTIVE()));
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_INT)) {
             return false;
        }
	$row3 = ((empty($fdata->get_CONFIRM())) ? 0 :  (int)($fdata->get_CONFIRM()));
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_INT)) {
             return false;
        }
	$row4 = ((empty($fdata->get_DATCONF())) ? NULL :  $fdata->get_DATCONF());
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_STR)) {
             return false;
        }
	$row5 = ((empty($fdata->get_DATINS())) ? date('Y-m-d H:i:s') :  $fdata->get_DATINS());
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
             return false;
        }
	$row6 = ((empty($fdata->get_DATMOD())) ? NULL :  $fdata->get_DATMOD());
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_STR)) {
             return false;
        }
	$row7 = ((empty($fdata->get_IGNORE())) ? 0 :  (int)($fdata->get_IGNORE()));
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_INT)) {
             return false;
        }
	$row8 = ((empty($fdata->get_REQ())) ? 0 :  (int)($fdata->get_REQ()));
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_INT)) {
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
    
    $strSql = "SELECT * FROM users_list";        
        
        
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
        $row['data']['USR'] =  $obj->ID_USR;
        $row['data']['USR2'] =  $obj->ID_USR2;
        $row['data']['ACTIVE'] =  $obj->USR_ACTIVE;
        $row['data']['CONFIRM'] =  $obj->USR_CONFIRM;
        $row['data']['DATCONF'] =  $obj->USR_DATCONF;
        $row['data']['DATINS'] =  $obj->USR_DATINS;
        $row['data']['DATMOD'] =  $obj->USR_DATMOD;
        $row['data']['IGNORE'] =  $obj->USR_IGNORE;
        $row['data']['REQ'] =  $obj->USR_REQ;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\UsersListData {
     $ID_USR = isset($form->USR) ? filter_var($form->USR, FILTER_SANITIZE_STRING): NULL; 
     $ID_USR2 = isset($form->USR2) ? filter_var($form->USR2, FILTER_SANITIZE_STRING): NULL; 
     $USR_REQ = isset($form->REQ) ? filter_var($form->REQ, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $USR_IGNORE = isset($form->IGNORE) ? filter_var($form->IGNORE, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $USR_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $USR_CONFIRM = isset($form->CONFIRM) ? filter_var($form->CONFIRM, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $USR_DATCONF = isset($form->DATCONF) ? filter_var($form->DATCONF, FILTER_SANITIZE_STRING): NULL; 
     $USR_DATINS = isset($form->DATINS) ? filter_var($form->DATINS, FILTER_SANITIZE_STRING): NULL; 
     $USR_DATMOD = isset($form->DATMOD) ? filter_var($form->DATMOD, FILTER_SANITIZE_STRING): NULL;        
     $fdata = new \Moviao\Data\Rad\UsersListData();   
     $fdata->set_USR($ID_USR);
     $fdata->set_USR2($ID_USR2);
     $fdata->set_REQ($USR_REQ);
     $fdata->set_IGNORE($USR_IGNORE);
     $fdata->set_ACTIVE($USR_ACTIVE);
     $fdata->set_CONFIRM($USR_CONFIRM);
     $fdata->set_DATCONF($USR_DATCONF);
     $fdata->set_DATINS($USR_DATINS);
     $fdata->set_DATMOD($USR_DATMOD); 
     return $fdata;
}
}