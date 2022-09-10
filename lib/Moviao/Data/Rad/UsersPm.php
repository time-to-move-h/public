<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Data API Users_pm
namespace Moviao\Data\Rad;
use PDO;

class UsersPm {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\UsersPmData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO users_pm (USRPM_DATSND, USRPM_MSG, USRPM_READ, USRPM_USR1, USRPM_USR2) VALUES (?,?,?,?,?)";
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((empty($fdata->get_DATSND())) ? date('Y-m-d H:i:s') :  $fdata->get_DATSND());
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_STR)) {
             return false;
        }
	$row1 = ((empty($fdata->get_MSG())) ? '' :  $fdata->get_MSG());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_STR)) {
             return false;
        }
	$row2 = ((empty($fdata->get_READ())) ? 0 :  (int)($fdata->get_READ()));
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_INT)) {
             return false;
        }
	$row3 = ((empty($fdata->get_USR1())) ? 0 :  $fdata->get_USR1());
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_INT)) {
             return false;
        }
	$row4 = ((empty($fdata->get_USR2())) ? 0 :  $fdata->get_USR2());
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
    
    $strSql = "SELECT * FROM users_pm";        
        
        
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
        $row['data']['DATSND'] =  $obj->USRPM_DATSND;
        $row['data']['IDPM'] =  $obj->USRPM_IDPM;
        $row['data']['MSG'] =  $obj->USRPM_MSG;
        $row['data']['READ'] =  $obj->USRPM_READ;
        $row['data']['USR1'] =  $obj->USRPM_USR1;
        $row['data']['USR2'] =  $obj->USRPM_USR2;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\UsersPmData {
     $USRPM_IDPM = isset($form->IDPM) ? filter_var($form->IDPM, FILTER_SANITIZE_STRING): NULL; 
     $USRPM_USR1 = isset($form->USR1) ? filter_var($form->USR1, FILTER_SANITIZE_STRING): NULL; 
     $USRPM_USR2 = isset($form->USR2) ? filter_var($form->USR2, FILTER_SANITIZE_STRING): NULL; 
     $USRPM_MSG = isset($form->MSG) ? filter_var($form->MSG, FILTER_SANITIZE_STRING): NULL; 
     $USRPM_DATSND = isset($form->DATSND) ? filter_var($form->DATSND, FILTER_SANITIZE_STRING): NULL; 
     $USRPM_READ = isset($form->READ) ? filter_var($form->READ, FILTER_SANITIZE_NUMBER_INT): NULL;        
     $fdata = new \Moviao\Data\Rad\UsersPmData();   
     $fdata->set_IDPM($USRPM_IDPM);
     $fdata->set_USR1($USRPM_USR1);
     $fdata->set_USR2($USRPM_USR2);
     $fdata->set_MSG($USRPM_MSG);
     $fdata->set_DATSND($USRPM_DATSND);
     $fdata->set_READ($USRPM_READ); 
     return $fdata;
}
}