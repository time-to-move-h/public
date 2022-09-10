<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2020-2021.
// Data API Users_validation
namespace Moviao\Data\Rad;
use PDO;

class UsersValidation {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\UsersValidationData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO users_validation (ID_ACCTYP, UVA_ACTIVE, UVA_CNT, UVA_CODE, UVA_DATINS, UVA_EMAIL, UVA_EMAIL_CONFIRMED, UVA_FNAME, UVA_LASTIP, UVA_LNAME, UVA_LOCKED, UVA_MPHONE, UVA_MPHONE_CONFIRMED, UVA_PWD, UVA_SEX, UVA_TIMEZONE) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((! is_numeric($fdata->get_ACCTYP())) ? null :  (int) $fdata->get_ACCTYP());
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
       return false;
    }
	$row1 = ((! is_numeric($fdata->get_ACTIVE())) ? null :  (int) $fdata->get_ACTIVE());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_INT)) {
       return false;
    }
	$row2 = $fdata->get_CNT();
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
       return false;
    }
	$row3 = $fdata->get_CODE();
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_STR)) {
       return false;
    }
	$row4 = $fdata->get_DATINS();
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_STR)) {
       return false;
    }
	$row5 = $fdata->get_EMAIL();
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
       return false;
    }
	$row6 = ((! is_numeric($fdata->get_EMAIL_CONFIRMED())) ? null :  (int) $fdata->get_EMAIL_CONFIRMED());
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_INT)) {
       return false;
    }
	$row7 = $fdata->get_FNAME();
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_STR)) {
       return false;
    }
	$row8 = $fdata->get_LASTIP();
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_STR)) {
       return false;
    }
	$row9 = $fdata->get_LNAME();
	if (! $this->data->bindParam(10,$row9, PDO::PARAM_STR)) {
       return false;
    }
	$row10 = ((! is_numeric($fdata->get_LOCKED())) ? null :  (int) $fdata->get_LOCKED());
	if (! $this->data->bindParam(11,$row10, PDO::PARAM_INT)) {
       return false;
    }
	$row11 = $fdata->get_MPHONE();
	if (! $this->data->bindParam(12,$row11, PDO::PARAM_STR)) {
       return false;
    }
	$row12 = ((! is_numeric($fdata->get_MPHONE_CONFIRMED())) ? null :  (int) $fdata->get_MPHONE_CONFIRMED());
	if (! $this->data->bindParam(13,$row12, PDO::PARAM_INT)) {
       return false;
    }
	$row13 = $fdata->get_PWD();
	if (! $this->data->bindParam(14,$row13, PDO::PARAM_STR)) {
       return false;
    }
	$row14 = ((! is_numeric($fdata->get_SEX())) ? null :  (int) $fdata->get_SEX());
	if (! $this->data->bindParam(15,$row14, PDO::PARAM_INT)) {
       return false;
    }
	$row15 = $fdata->get_TIMEZONE();
	if (! $this->data->bindParam(16,$row15, PDO::PARAM_STR)) {
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
    
    $strSql = 'SELECT * FROM users_validation';
                
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
        $row['data']['ACCTYP'] =  $obj->ID_ACCTYP;
        $row['data']['USRVAL'] =  $obj->ID_USRVAL;
        $row['data']['ACTIVE'] =  $obj->UVA_ACTIVE;
        $row['data']['CNT'] =  $obj->UVA_CNT;
        $row['data']['CODE'] =  $obj->UVA_CODE;
        $row['data']['DATINS'] =  $obj->UVA_DATINS;
        $row['data']['EMAIL'] =  $obj->UVA_EMAIL;
        $row['data']['EMAIL_CONFIRMED'] =  $obj->UVA_EMAIL_CONFIRMED;
        $row['data']['FNAME'] =  $obj->UVA_FNAME;
        $row['data']['LASTIP'] =  $obj->UVA_LASTIP;
        $row['data']['LNAME'] =  $obj->UVA_LNAME;
        $row['data']['LOCKED'] =  $obj->UVA_LOCKED;
        $row['data']['MPHONE'] =  $obj->UVA_MPHONE;
        $row['data']['MPHONE_CONFIRMED'] =  $obj->UVA_MPHONE_CONFIRMED;
        $row['data']['PWD'] =  $obj->UVA_PWD;
        $row['data']['SEX'] =  $obj->UVA_SEX;
        $row['data']['TIMEZONE'] =  $obj->UVA_TIMEZONE;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\UsersValidationData {
     $ID_USRVAL = isset($form->USRVAL) ? filter_var($form->USRVAL, FILTER_SANITIZE_NUMBER_INT): null; 
     $UVA_EMAIL = isset($form->EMAIL) ? filter_var($form->EMAIL, FILTER_SANITIZE_STRING): null; 
     $UVA_MPHONE = isset($form->MPHONE) ? filter_var($form->MPHONE, FILTER_SANITIZE_STRING): null; 
     $UVA_FNAME = isset($form->FNAME) ? filter_var($form->FNAME, FILTER_SANITIZE_STRING): null; 
     $UVA_LNAME = isset($form->LNAME) ? filter_var($form->LNAME, FILTER_SANITIZE_STRING): null; 
     $UVA_CODE = isset($form->CODE) ? filter_var($form->CODE, FILTER_SANITIZE_STRING): null; 
     $UVA_DATINS = isset($form->DATINS) ? $form->DATINS : null; 
     $UVA_CNT = isset($form->CNT) ? filter_var($form->CNT, FILTER_SANITIZE_STRING): null; 
     $UVA_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): null; 
     $UVA_LASTIP = isset($form->LASTIP) ? filter_var($form->LASTIP, FILTER_SANITIZE_STRING): null; 
     $UVA_LOCKED = isset($form->LOCKED) ? filter_var($form->LOCKED, FILTER_SANITIZE_NUMBER_INT): null; 
     $UVA_TIMEZONE = isset($form->TIMEZONE) ? filter_var($form->TIMEZONE, FILTER_SANITIZE_STRING): null; 
     $UVA_PWD = isset($form->PWD) ? filter_var($form->PWD, FILTER_SANITIZE_STRING): null; 
     $ID_ACCTYP = isset($form->ACCTYP) ? filter_var($form->ACCTYP, FILTER_SANITIZE_NUMBER_INT): null; 
     $UVA_SEX = isset($form->SEX) ? filter_var($form->SEX, FILTER_SANITIZE_NUMBER_INT): null; 
     $UVA_EMAIL_CONFIRMED = isset($form->EMAIL_CONFIRMED) ? filter_var($form->EMAIL_CONFIRMED, FILTER_SANITIZE_NUMBER_INT): null; 
     $UVA_MPHONE_CONFIRMED = isset($form->MPHONE_CONFIRMED) ? filter_var($form->MPHONE_CONFIRMED, FILTER_SANITIZE_NUMBER_INT): null;        
     $fdata = new \Moviao\Data\Rad\UsersValidationData();   
     $fdata->set_USRVAL($ID_USRVAL);
     $fdata->set_EMAIL($UVA_EMAIL);
     $fdata->set_MPHONE($UVA_MPHONE);
     $fdata->set_FNAME($UVA_FNAME);
     $fdata->set_LNAME($UVA_LNAME);
     $fdata->set_CODE($UVA_CODE);
     $fdata->set_DATINS($UVA_DATINS);
     $fdata->set_CNT($UVA_CNT);
     $fdata->set_ACTIVE($UVA_ACTIVE);
     $fdata->set_LASTIP($UVA_LASTIP);
     $fdata->set_LOCKED($UVA_LOCKED);
     $fdata->set_TIMEZONE($UVA_TIMEZONE);
     $fdata->set_PWD($UVA_PWD);
     $fdata->set_ACCTYP($ID_ACCTYP);
     $fdata->set_SEX($UVA_SEX);
     $fdata->set_EMAIL_CONFIRMED($UVA_EMAIL_CONFIRMED);
     $fdata->set_MPHONE_CONFIRMED($UVA_MPHONE_CONFIRMED); 
     return $fdata;
}
}