<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2018-2019.
// Data API Users_account
namespace Moviao\Data\Rad;
use PDO;

class UsersAccount {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\UsersAccountData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO users_account (ID_TYPACO, ID_USR, UAC_ACCOUNT, UAC_ACTIVE, UAC_DATEINS, UAC_DATEMOD, UAC_LOCKED, UAC_PWD, UAC_PWD_OTP) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((empty($fdata->get_TYPACO())) ? 0 :  (int)($fdata->get_TYPACO()));
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
             return false;
        }
	$row1 = ((empty($fdata->get_USR())) ? 0 :  $fdata->get_USR());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_INT)) {
             return false;
        }
	$row2 = ((empty($fdata->get_ACCOUNT())) ? '' :  $fdata->get_ACCOUNT());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
             return false;
        }
	$row3 = ((empty($fdata->get_ACTIVE())) ? 0 :  (int)($fdata->get_ACTIVE()));
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_INT)) {
             return false;
        }
	$row4 = ((empty($fdata->get_DATEINS())) ? date('Y-m-d H:i:s') :  $fdata->get_DATEINS());
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_STR)) {
             return false;
        }
	$row5 = ((empty($fdata->get_DATEMOD())) ? NULL :  $fdata->get_DATEMOD());
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
             return false;
        }
	$row6 = ((empty($fdata->get_LOCKED())) ? 0 :  (int)($fdata->get_LOCKED()));
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_INT)) {
             return false;
        }
	$row7 = ((empty($fdata->get_PWD())) ? NULL :  $fdata->get_PWD());
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_STR)) {
             return false;
        }
	$row8 = ((empty($fdata->get_PWD_OTP())) ? NULL :  $fdata->get_PWD_OTP());
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_STR)) {
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
    
    $strSql = "SELECT * FROM users_account";        
        
        
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
        $row['data']['TYPACO'] =  $obj->ID_TYPACO;
        $row['data']['USR'] =  $obj->ID_USR;
        $row['data']['ACCOUNT'] =  $obj->UAC_ACCOUNT;
        $row['data']['ACTIVE'] =  $obj->UAC_ACTIVE;
        $row['data']['DATEINS'] =  $obj->UAC_DATEINS;
        $row['data']['DATEMOD'] =  $obj->UAC_DATEMOD;
        $row['data']['LOCKED'] =  $obj->UAC_LOCKED;
        $row['data']['PWD'] =  $obj->UAC_PWD;
        $row['data']['PWD_OTP'] =  $obj->UAC_PWD_OTP;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\UsersAccountData {
     $ID_USR = isset($form->USR) ? filter_var($form->USR, FILTER_SANITIZE_STRING): NULL; 
     $UAC_ACCOUNT = isset($form->ACCOUNT) ? filter_var($form->ACCOUNT, FILTER_SANITIZE_STRING): NULL; 
     $ID_TYPACO = isset($form->TYPACO) ? filter_var($form->TYPACO, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $UAC_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $UAC_PWD = isset($form->PWD) ? filter_var($form->PWD, FILTER_SANITIZE_STRING): NULL; 
     $UAC_PWD_OTP = isset($form->PWD_OTP) ? filter_var($form->PWD_OTP, FILTER_SANITIZE_STRING): NULL; 
     $UAC_LOCKED = isset($form->LOCKED) ? filter_var($form->LOCKED, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $UAC_DATEINS = isset($form->DATEINS) ? filter_var($form->DATEINS, FILTER_SANITIZE_STRING): NULL; 
     $UAC_DATEMOD = isset($form->DATEMOD) ? filter_var($form->DATEMOD, FILTER_SANITIZE_STRING): NULL;        
     $fdata = new \Moviao\Data\Rad\UsersAccountData();   
     $fdata->set_USR($ID_USR);
     $fdata->set_ACCOUNT($UAC_ACCOUNT);
     $fdata->set_TYPACO($ID_TYPACO);
     $fdata->set_ACTIVE($UAC_ACTIVE);
     $fdata->set_PWD($UAC_PWD);
     $fdata->set_PWD_OTP($UAC_PWD_OTP);
     $fdata->set_LOCKED($UAC_LOCKED);
     $fdata->set_DATEINS($UAC_DATEINS);
     $fdata->set_DATEMOD($UAC_DATEMOD); 
     return $fdata;
}
}