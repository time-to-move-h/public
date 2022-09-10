<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2018-2019.
// Data API Tickets_order_action
namespace Moviao\Data\Rad;
use PDO;

class TicketsOrderAction {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\TicketsOrderActionData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO tickets_order_action (ID_TICORDER, ID_TICORDER_STATUS, TICORDER_ACTION_DATINS) VALUES (?,?,?)";
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((! is_numeric($fdata->get_TICORDER())) ? null :  (int)($fdata->get_TICORDER()));
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
       return false;
    }
	$row1 = $fdata->get_TICORDER_STATUS();
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_STR)) {
       return false;
    }
	$row2 = $fdata->get_ACTION_DATINS();
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
    
    $strSql = "SELECT * FROM tickets_order_action";        
        
        
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
        $row['data']['TICORDER'] =  $obj->ID_TICORDER;
        $row['data']['TICORDER_ACTION'] =  $obj->ID_TICORDER_ACTION;
        $row['data']['TICORDER_STATUS'] =  $obj->ID_TICORDER_STATUS;
        $row['data']['ACTION_DATINS'] =  $obj->TICORDER_ACTION_DATINS;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\TicketsOrderActionData {
     $ID_TICORDER_ACTION = isset($form->TICORDER_ACTION) ? filter_var($form->TICORDER_ACTION, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_TICORDER = isset($form->TICORDER) ? filter_var($form->TICORDER, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_TICORDER_STATUS = isset($form->TICORDER_STATUS) ? filter_var($form->TICORDER_STATUS, FILTER_SANITIZE_STRING): null; 
     $TICORDER_ACTION_DATINS = isset($form->ACTION_DATINS) ? $form->ACTION_DATINS : null;        
     $fdata = new \Moviao\Data\Rad\TicketsOrderActionData();   
     $fdata->set_TICORDER_ACTION($ID_TICORDER_ACTION);
     $fdata->set_TICORDER($ID_TICORDER);
     $fdata->set_TICORDER_STATUS($ID_TICORDER_STATUS);
     $fdata->set_ACTION_DATINS($TICORDER_ACTION_DATINS); 
     return $fdata;
}
}