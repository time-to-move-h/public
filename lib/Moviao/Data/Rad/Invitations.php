<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Data API Invitations
namespace Moviao\Data\Rad;
use PDO;

class Invitations {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\InvitationsData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO invitations (INV_ACCOUNT, INV_CODE, INV_DATINS) VALUES (?,?,?)";
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((empty($fdata->get_ACCOUNT())) ? '' :  $fdata->get_ACCOUNT());
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_STR)) {
             return false;
        }
	$row1 = ((empty($fdata->get_CODE())) ? 0 :  (int)($fdata->get_CODE()));
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_INT)) {
             return false;
        }
	$row2 = ((empty($fdata->get_DATINS())) ? date('Y-m-d H:i:s') :  $fdata->get_DATINS());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
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
    
    $strSql = "SELECT * FROM invitations";        
        
        
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
        $row['data']['IDINVIT'] =  $obj->IDINVIT;
        $row['data']['ACCOUNT'] =  $obj->INV_ACCOUNT;
        $row['data']['CODE'] =  $obj->INV_CODE;
        $row['data']['DATINS'] =  $obj->INV_DATINS;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\InvitationsData {
     $IDINVIT = isset($form->IDINVIT) ? filter_var($form->IDINVIT, FILTER_SANITIZE_STRING): NULL; 
     $INV_ACCOUNT = isset($form->ACCOUNT) ? filter_var($form->ACCOUNT, FILTER_SANITIZE_STRING): NULL; 
     $INV_CODE = isset($form->CODE) ? filter_var($form->CODE, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $INV_DATINS = isset($form->DATINS) ? filter_var($form->DATINS, FILTER_SANITIZE_STRING): NULL;        
     $fdata = new \Moviao\Data\Rad\InvitationsData();   
     $fdata->set_IDINVIT($IDINVIT);
     $fdata->set_ACCOUNT($INV_ACCOUNT);
     $fdata->set_CODE($INV_CODE);
     $fdata->set_DATINS($INV_DATINS); 
     return $fdata;
}
}