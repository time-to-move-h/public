<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// Data API Tickets_locks
namespace Moviao\Data\Rad;
use PDO;

class TicketsLocks {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\TicketsLocksData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO tickets_locks (ID_SESSION, ID_TICKETTYPE, TICLOC_DATINS, TICLOC_QTE) VALUES (?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = $fdata->get_SESSION();
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_STR)) {
       return false;
    }
	$row1 = ((! is_numeric($fdata->get_TICKETTYPE())) ? null :  (int) $fdata->get_TICKETTYPE());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_INT)) {
       return false;
    }
	$row2 = $fdata->get_DATINS();
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
       return false;
    }
	$row3 = ((! is_numeric($fdata->get_QTE())) ? null :  (int) $fdata->get_QTE());
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_INT)) {
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
    
    $strSql = 'SELECT * FROM tickets_locks';
                
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
        $row['data']['SESSION'] =  $obj->ID_SESSION;
        $row['data']['TICKETTYPE'] =  $obj->ID_TICKETTYPE;
        $row['data']['DATINS'] =  $obj->TICLOC_DATINS;
        $row['data']['QTE'] =  $obj->TICLOC_QTE;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\TicketsLocksData {
     $ID_TICKETTYPE = isset($form->TICKETTYPE) ? filter_var($form->TICKETTYPE, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_SESSION = isset($form->SESSION) ? htmlspecialchars($form->SESSION) : null; 
     $TICLOC_QTE = isset($form->QTE) ? filter_var($form->QTE, FILTER_SANITIZE_NUMBER_INT): null; 
     $TICLOC_DATINS = isset($form->DATINS) ? $form->DATINS : null;        
     $fdata = new \Moviao\Data\Rad\TicketsLocksData();   
     $fdata->set_TICKETTYPE($ID_TICKETTYPE);
     $fdata->set_SESSION($ID_SESSION);
     $fdata->set_QTE($TICLOC_QTE);
     $fdata->set_DATINS($TICLOC_DATINS); 
     return $fdata;
}

}