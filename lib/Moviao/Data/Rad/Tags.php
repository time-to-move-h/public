<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Data API Tags
namespace Moviao\Data\Rad;
use PDO;

class Tags {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\TagsData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO tags (ID_TAG, TAG_DESC_EN, TAG_DESC_ES, TAG_DESC_FR, TAG_IDLINK) VALUES (?,?,?,?,?)";
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((empty($fdata->get_TAG())) ? 0 :  (int)($fdata->get_TAG()));
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
             return false;
        }
	$row1 = ((empty($fdata->get_DESC_EN())) ? '' :  $fdata->get_DESC_EN());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_STR)) {
             return false;
        }
	$row2 = ((empty($fdata->get_DESC_ES())) ? '' :  $fdata->get_DESC_ES());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
             return false;
        }
	$row3 = ((empty($fdata->get_DESC_FR())) ? '' :  $fdata->get_DESC_FR());
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_STR)) {
             return false;
        }
	$row4 = ((empty($fdata->get_IDLINK())) ? NULL :  (int)($fdata->get_IDLINK()));
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
    
    $strSql = "SELECT * FROM tags";        
        
        
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
        $row['data']['TAG'] =  $obj->ID_TAG;
        $row['data']['DESC_EN'] =  $obj->TAG_DESC_EN;
        $row['data']['DESC_ES'] =  $obj->TAG_DESC_ES;
        $row['data']['DESC_FR'] =  $obj->TAG_DESC_FR;
        $row['data']['IDLINK'] =  $obj->TAG_IDLINK;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\TagsData {
     $ID_TAG = isset($form->TAG) ? filter_var($form->TAG, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $TAG_IDLINK = isset($form->IDLINK) ? filter_var($form->IDLINK, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $TAG_DESC_FR = isset($form->DESC_FR) ? filter_var($form->DESC_FR, FILTER_SANITIZE_STRING): NULL; 
     $TAG_DESC_ES = isset($form->DESC_ES) ? filter_var($form->DESC_ES, FILTER_SANITIZE_STRING): NULL; 
     $TAG_DESC_EN = isset($form->DESC_EN) ? filter_var($form->DESC_EN, FILTER_SANITIZE_STRING): NULL;        
     $fdata = new \Moviao\Data\Rad\TagsData();   
     $fdata->set_TAG($ID_TAG);
     $fdata->set_IDLINK($TAG_IDLINK);
     $fdata->set_DESC_FR($TAG_DESC_FR);
     $fdata->set_DESC_ES($TAG_DESC_ES);
     $fdata->set_DESC_EN($TAG_DESC_EN); 
     return $fdata;
}
}