<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// Data API Users_search
namespace Moviao\Data\Rad;
use PDO;

class UsersSearch {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\UsersSearchData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO users_search (ID_USR, USC_DATINS, USC_DATMOD, USC_LAT, USC_LOCATION, USC_LON, USC_QUERY, USC_RAD) VALUES (?,?,?,?,?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((! is_numeric($fdata->get_USR())) ? null :  (int) $fdata->get_USR());
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
	$row3 = $fdata->get_LAT();
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_STR)) {
       return false;
    }
	$row4 = $fdata->get_LOCATION();
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_STR)) {
       return false;
    }
	$row5 = $fdata->get_LON();
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
       return false;
    }
	$row6 = $fdata->get_QUERY();
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_STR)) {
       return false;
    }
	$row7 = ((! is_numeric($fdata->get_RAD())) ? null :  (int) $fdata->get_RAD());
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_INT)) {
       return false;
    }
	
                
    if (! $this->data->execute()) {
        error_log("RAD execute : " . var_export($this->data->errorInfo(),true));
        return false;
    }

    $rowcount = $stmt->rowCount();

    if ($rowcount > 0) {
        $result = true;
    }

    } catch (\Error $e) {
        error_log("RAD Create >> : $e");
    }
    return $result;
}

public function show($where,$orderby = null,$limit = null) : array {
    $return_data = array();    
    try { 
    
    $strSql = 'SELECT * FROM users_search';
                
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
        $row['data']['USR'] =  $obj->ID_USR;
        $row['data']['DATINS'] =  $obj->USC_DATINS;
        $row['data']['DATMOD'] =  $obj->USC_DATMOD;
        $row['data']['LAT'] =  $obj->USC_LAT;
        $row['data']['LOCATION'] =  $obj->USC_LOCATION;
        $row['data']['LON'] =  $obj->USC_LON;
        $row['data']['QUERY'] =  $obj->USC_QUERY;
        $row['data']['RAD'] =  $obj->USC_RAD;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\UsersSearchData {
     $ID_USR = isset($form->USR) ? filter_var($form->USR, FILTER_SANITIZE_NUMBER_INT): null; 
     $USC_QUERY = isset($form->QUERY) ? $this->cleanData($form->QUERY) : null; 
     $USC_LOCATION = isset($form->LOCATION) ? $this->cleanData($form->LOCATION) : null; 
     $USC_LAT = isset($form->LAT) ? $this->cleanData($form->LAT) : null; 
     $USC_LON = isset($form->LON) ? $this->cleanData($form->LON) : null; 
     $USC_RAD = isset($form->RAD) ? filter_var($form->RAD, FILTER_SANITIZE_NUMBER_INT): null; 
     $USC_DATINS = isset($form->DATINS) ? $form->DATINS : null; 
     $USC_DATMOD = isset($form->DATMOD) ? $form->DATMOD : null;        
     $fdata = new \Moviao\Data\Rad\UsersSearchData();   
     $fdata->set_USR($ID_USR);
     $fdata->set_QUERY($USC_QUERY);
     $fdata->set_LOCATION($USC_LOCATION);
     $fdata->set_LAT($USC_LAT);
     $fdata->set_LON($USC_LON);
     $fdata->set_RAD($USC_RAD);
     $fdata->set_DATINS($USC_DATINS);
     $fdata->set_DATMOD($USC_DATMOD); 
     return $fdata;
}

private function cleanData($data)
{
    $data = htmlspecialchars($data);
    $data = \strip_tags($data);
    $data = stripslashes($data);
    $data = trim($data);
    return $data;
}


}