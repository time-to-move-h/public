<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// Data API Tickets_order_details
namespace Moviao\Data\Rad;
use PDO;

class TicketsOrderDetails {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\TicketsOrderDetailsData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO tickets_order_details (ID_TICKETDET, ID_TICKETTYPE, ID_TICORDER, TICDET_CODE, TICDET_DATINS, TICDET_DATMOD, TICDET_EMAIL, TICDET_FNAME, TICDET_INFO, TICDET_LNAME, TICDET_LOCKED) VALUES (?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((! is_numeric($fdata->get_TICKETDET())) ? null :  (int) $fdata->get_TICKETDET());
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
       return false;
    }
	$row1 = ((! is_numeric($fdata->get_TICKETTYPE())) ? null :  (int) $fdata->get_TICKETTYPE());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_INT)) {
       return false;
    }
	$row2 = ((! is_numeric($fdata->get_TICORDER())) ? null :  (int) $fdata->get_TICORDER());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_INT)) {
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
	$row5 = $fdata->get_DATMOD();
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
       return false;
    }
	$row6 = $fdata->get_EMAIL();
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_STR)) {
       return false;
    }
	$row7 = $fdata->get_FNAME();
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_STR)) {
       return false;
    }
	$row8 = $fdata->get_INFO();
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
    
    $strSql = 'SELECT * FROM tickets_order_details';
                
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
        $row['data']['TICKETDET'] =  $obj->ID_TICKETDET;
        $row['data']['TICKETTYPE'] =  $obj->ID_TICKETTYPE;
        $row['data']['TICORDER'] =  $obj->ID_TICORDER;
        $row['data']['CODE'] =  $obj->TICDET_CODE;
        $row['data']['DATINS'] =  $obj->TICDET_DATINS;
        $row['data']['DATMOD'] =  $obj->TICDET_DATMOD;
        $row['data']['EMAIL'] =  $obj->TICDET_EMAIL;
        $row['data']['FNAME'] =  $obj->TICDET_FNAME;
        $row['data']['INFO'] =  $obj->TICDET_INFO;
        $row['data']['LNAME'] =  $obj->TICDET_LNAME;
        $row['data']['LOCKED'] =  $obj->TICDET_LOCKED;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\TicketsOrderDetailsData {
     $ID_TICORDER = isset($form->TICORDER) ? filter_var($form->TICORDER, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_TICKETTYPE = isset($form->TICKETTYPE) ? filter_var($form->TICKETTYPE, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_TICKETDET = isset($form->TICKETDET) ? filter_var($form->TICKETDET, FILTER_SANITIZE_NUMBER_INT): null; 
     $TICDET_FNAME = isset($form->FNAME) ? htmlspecialchars($form->FNAME) : null; 
     $TICDET_LNAME = isset($form->LNAME) ? htmlspecialchars($form->LNAME) : null; 
     $TICDET_EMAIL = isset($form->EMAIL) ? htmlspecialchars($form->EMAIL) : null; 
     $TICDET_INFO = isset($form->INFO) ? htmlspecialchars($form->INFO) : null; 
     $TICDET_CODE = isset($form->CODE) ? htmlspecialchars($form->CODE) : null; 
     $TICDET_DATINS = isset($form->DATINS) ? $form->DATINS : null; 
     $TICDET_LOCKED = isset($form->LOCKED) ? filter_var($form->LOCKED, FILTER_SANITIZE_NUMBER_INT): null; 
     $TICDET_DATMOD = isset($form->DATMOD) ? $form->DATMOD : null;        
     $fdata = new \Moviao\Data\Rad\TicketsOrderDetailsData();   
     $fdata->set_TICORDER($ID_TICORDER);
     $fdata->set_TICKETTYPE($ID_TICKETTYPE);
     $fdata->set_TICKETDET($ID_TICKETDET);
     $fdata->set_FNAME($TICDET_FNAME);
     $fdata->set_LNAME($TICDET_LNAME);
     $fdata->set_EMAIL($TICDET_EMAIL);
     $fdata->set_INFO($TICDET_INFO);
     $fdata->set_CODE($TICDET_CODE);
     $fdata->set_DATINS($TICDET_DATINS);
     $fdata->set_LOCKED($TICDET_LOCKED);
     $fdata->set_DATMOD($TICDET_DATMOD); 
     return $fdata;
}

}