<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Data API Feeds
namespace Moviao\Data\Rad;
use PDO;

class Feeds {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\FeedsData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO feeds (FDS_ACTIVE, FDS_DATCRE, FDS_IDLNK, FDS_IDLNKTYP, FDS_IMG, FDS_MSG, ID_USR) VALUES (?,?,?,?,?,?,?)";
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((empty($fdata->get_ACTIVE())) ? 0 :  (int)($fdata->get_ACTIVE()));
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
             return false;
        }
	$row1 = ((empty($fdata->get_DATCRE())) ? date('Y-m-d H:i:s') :  $fdata->get_DATCRE());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_STR)) {
             return false;
        }
	$row2 = ((empty($fdata->get_IDLNK())) ? NULL :  $fdata->get_IDLNK());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_INT)) {
             return false;
        }
	$row3 = ((empty($fdata->get_IDLNKTYP())) ? NULL :  (int)($fdata->get_IDLNKTYP()));
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_INT)) {
             return false;
        }
	$row4 = ((empty($fdata->get_IMG())) ? NULL :  $fdata->get_IMG());
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_STR)) {
             return false;
        }
	$row5 = ((empty($fdata->get_MSG())) ? NULL :  $fdata->get_MSG());
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
             return false;
        }
	$row6 = ((empty($fdata->get_USR())) ? 0 :  $fdata->get_USR());
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_INT)) {
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
    
    $strSql = "SELECT * FROM feeds";        
        
        
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
        $row['data']['ACTIVE'] =  $obj->FDS_ACTIVE;
        $row['data']['DATCRE'] =  $obj->FDS_DATCRE;
        $row['data']['IDFDS'] =  $obj->FDS_IDFDS;
        $row['data']['IDLNK'] =  $obj->FDS_IDLNK;
        $row['data']['IDLNKTYP'] =  $obj->FDS_IDLNKTYP;
        $row['data']['IMG'] =  $obj->FDS_IMG;
        $row['data']['MSG'] =  $obj->FDS_MSG;
        $row['data']['USR'] =  $obj->ID_USR;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\FeedsData {
     $FDS_IDFDS = isset($form->IDFDS) ? filter_var($form->IDFDS, FILTER_SANITIZE_STRING): NULL; 
     $FDS_IDLNK = isset($form->IDLNK) ? filter_var($form->IDLNK, FILTER_SANITIZE_STRING): NULL; 
     $FDS_IDLNKTYP = isset($form->IDLNKTYP) ? filter_var($form->IDLNKTYP, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $ID_USR = isset($form->USR) ? filter_var($form->USR, FILTER_SANITIZE_STRING): NULL; 
     $FDS_MSG = isset($form->MSG) ? filter_var($form->MSG, FILTER_SANITIZE_STRING): NULL; 
     $FDS_IMG = isset($form->IMG) ? filter_var($form->IMG, FILTER_SANITIZE_STRING): NULL; 
     $FDS_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $FDS_DATCRE = isset($form->DATCRE) ? filter_var($form->DATCRE, FILTER_SANITIZE_STRING): NULL;        
     $fdata = new \Moviao\Data\Rad\FeedsData();   
     $fdata->set_IDFDS($FDS_IDFDS);
     $fdata->set_IDLNK($FDS_IDLNK);
     $fdata->set_IDLNKTYP($FDS_IDLNKTYP);
     $fdata->set_USR($ID_USR);
     $fdata->set_MSG($FDS_MSG);
     $fdata->set_IMG($FDS_IMG);
     $fdata->set_ACTIVE($FDS_ACTIVE);
     $fdata->set_DATCRE($FDS_DATCRE); 
     return $fdata;
}
}