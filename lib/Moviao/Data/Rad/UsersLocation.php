<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Data API Users_location
namespace Moviao\Data\Rad;
use PDO;

class UsersLocation {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\UsersLocationData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO users_location (ID_USR, ULO_DATLOC, ULO_LOC) VALUES (?,?,ST_PointFromText(?))";
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((empty($fdata->get_USR())) ? 0 :  $fdata->get_USR());
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
             return false;
        }
	$row1 = ((empty($fdata->get_DATLOC())) ? date('Y-m-d H:i:s') :  $fdata->get_DATLOC());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_STR)) {
             return false;
        }
	$row2 = ((empty($fdata->get_LOC())) ? NULL :  $fdata->get_LOC());
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
    
    $strSql = "SELECT * FROM users_location";        
        
        
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
        $row['data']['DATLOC'] =  $obj->ULO_DATLOC;
        $row['data']['LOC'] =  $obj->ULO_LOC;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\UsersLocationData {
     $ID_USR = isset($form->USR) ? filter_var($form->USR, FILTER_SANITIZE_STRING): NULL; 
     $ULO_DATLOC = isset($form->DATLOC) ? filter_var($form->DATLOC, FILTER_SANITIZE_STRING): NULL; 
     $ULO_LOC = isset($form->LOC) ? filter_var($form->LOC, FILTER_SANITIZE_STRING): NULL;        
     $fdata = new \Moviao\Data\Rad\UsersLocationData();   
     $fdata->set_USR($ID_USR);
     $fdata->set_DATLOC($ULO_DATLOC);
     $fdata->set_LOC($ULO_LOC); 
     return $fdata;
}
}