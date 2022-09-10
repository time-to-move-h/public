<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Data API Channels_tags
namespace Moviao\Data\Rad;
use PDO;

class ChannelsTags {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\ChannelsTagsData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO channels_tags (CHATAG_ACTIVE, CHATAG_DATCRE, CHATAG_DATMOD, ID_CHA, ID_TAG) VALUES (?,?,?,?,?)";
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
	$row2 = ((empty($fdata->get_DATMOD())) ? NULL :  $fdata->get_DATMOD());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
             return false;
        }
	$row3 = ((empty($fdata->get_CHA())) ? 0 :  $fdata->get_CHA());
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_INT)) {
             return false;
        }
	$row4 = ((empty($fdata->get_TAG())) ? 0 :  (int)($fdata->get_TAG()));
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
    
    $strSql = "SELECT * FROM channels_tags";        
        
        
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
        $row['data']['ACTIVE'] =  $obj->CHATAG_ACTIVE;
        $row['data']['DATCRE'] =  $obj->CHATAG_DATCRE;
        $row['data']['DATMOD'] =  $obj->CHATAG_DATMOD;
        $row['data']['CHA'] =  $obj->ID_CHA;
        $row['data']['TAG'] =  $obj->ID_TAG;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\ChannelsTagsData {
     $ID_CHA = isset($form->CHA) ? filter_var($form->CHA, FILTER_SANITIZE_STRING): NULL; 
     $ID_TAG = isset($form->TAG) ? filter_var($form->TAG, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $CHATAG_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $CHATAG_DATCRE = isset($form->DATCRE) ? filter_var($form->DATCRE, FILTER_SANITIZE_STRING): NULL; 
     $CHATAG_DATMOD = isset($form->DATMOD) ? filter_var($form->DATMOD, FILTER_SANITIZE_STRING): NULL;        
     $fdata = new \Moviao\Data\Rad\ChannelsTagsData();   
     $fdata->set_CHA($ID_CHA);
     $fdata->set_TAG($ID_TAG);
     $fdata->set_ACTIVE($CHATAG_ACTIVE);
     $fdata->set_DATCRE($CHATAG_DATCRE);
     $fdata->set_DATMOD($CHATAG_DATMOD); 
     return $fdata;
}
}