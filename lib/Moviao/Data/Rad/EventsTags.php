<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2020-2021.
// Data API Events_tags
namespace Moviao\Data\Rad;
use PDO;

class EventsTags {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\EventsTagsData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO events_tags (EVTTAG_ACTIVE, EVTTAG_DATCRE, EVTTAG_DATMOD, ID_EVT, ID_TAG) VALUES (?,?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((! is_numeric($fdata->get_ACTIVE())) ? null :  (int) $fdata->get_ACTIVE());
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
       return false;
    }
	$row1 = $fdata->get_DATCRE();
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_STR)) {
       return false;
    }
	$row2 = $fdata->get_DATMOD();
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
       return false;
    }
	$row3 = ((! is_numeric($fdata->get_EVT())) ? null :  (int) $fdata->get_EVT());
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_INT)) {
       return false;
    }
	$row4 = ((! is_numeric($fdata->get_TAG())) ? null :  (int) $fdata->get_TAG());
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_INT)) {
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
    
    $strSql = 'SELECT * FROM events_tags';
                
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
        $row['data']['ACTIVE'] =  $obj->EVTTAG_ACTIVE;
        $row['data']['DATCRE'] =  $obj->EVTTAG_DATCRE;
        $row['data']['DATMOD'] =  $obj->EVTTAG_DATMOD;
        $row['data']['EVT'] =  $obj->ID_EVT;
        $row['data']['TAG'] =  $obj->ID_TAG;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\EventsTagsData {
     $ID_EVT = isset($form->EVT) ? filter_var($form->EVT, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_TAG = isset($form->TAG) ? filter_var($form->TAG, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVTTAG_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): null; 
     $EVTTAG_DATCRE = isset($form->DATCRE) ? $form->DATCRE : null; 
     $EVTTAG_DATMOD = isset($form->DATMOD) ? $form->DATMOD : null;        
     $fdata = new \Moviao\Data\Rad\EventsTagsData();   
     $fdata->set_EVT($ID_EVT);
     $fdata->set_TAG($ID_TAG);
     $fdata->set_ACTIVE($EVTTAG_ACTIVE);
     $fdata->set_DATCRE($EVTTAG_DATCRE);
     $fdata->set_DATMOD($EVTTAG_DATMOD); 
     return $fdata;
}
}