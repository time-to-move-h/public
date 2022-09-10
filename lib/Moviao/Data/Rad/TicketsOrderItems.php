<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// Data API Tickets_order_items
namespace Moviao\Data\Rad;
use PDO;

class TicketsOrderItems {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\TicketsOrderItemsData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO tickets_order_items (EVTDAT_DATBEG, EVTDAT_DATEND, ID_TICKETTYPE, ID_TICORDER, ID_ZONEIDBEG, ID_ZONEIDEND, TICORDER_ITEM_DATINS, TICORDER_ITEM_DATMOD, TICORDER_ITEM_PRICE, TICORDER_ITEM_PRICEHT, TICORDER_ITEM_PROCESSINGFEE, TICORDER_ITEM_QTE, TICORDER_ITEM_SERVICEFEE, TICORDER_ITEM_TVA, TIC_CURRENCY_ID) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = $fdata->get_DATBEG();
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_STR)) {
       return false;
    }
	$row1 = $fdata->get_DATEND();
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_STR)) {
       return false;
    }
	$row2 = ((! is_numeric($fdata->get_TICKETTYPE())) ? null :  (int) $fdata->get_TICKETTYPE());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_INT)) {
       return false;
    }
	$row3 = ((! is_numeric($fdata->get_TICORDER())) ? null :  (int) $fdata->get_TICORDER());
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_INT)) {
       return false;
    }
	$row4 = ((! is_numeric($fdata->get_ZONEIDBEG())) ? null :  (int) $fdata->get_ZONEIDBEG());
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_INT)) {
       return false;
    }
	$row5 = ((! is_numeric($fdata->get_ZONEIDEND())) ? null :  (int) $fdata->get_ZONEIDEND());
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_INT)) {
       return false;
    }
	$row6 = $fdata->get_ITEM_DATINS();
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_STR)) {
       return false;
    }
	$row7 = $fdata->get_ITEM_DATMOD();
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_STR)) {
       return false;
    }
	$row8 = ((! is_numeric($fdata->get_ITEM_PRICE())) ? null :  $fdata->get_ITEM_PRICE());
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_STR)) {
       return false;
    }
	$row9 = ((! is_numeric($fdata->get_ITEM_PRICEHT())) ? null :  $fdata->get_ITEM_PRICEHT());
	if (! $this->data->bindParam(10,$row9, PDO::PARAM_STR)) {
       return false;
    }
	$row10 = ((! is_numeric($fdata->get_ITEM_PROCESSINGFEE())) ? null :  $fdata->get_ITEM_PROCESSINGFEE());
	if (! $this->data->bindParam(11,$row10, PDO::PARAM_STR)) {
       return false;
    }
	$row11 = ((! is_numeric($fdata->get_ITEM_QTE())) ? null :  (int) $fdata->get_ITEM_QTE());
	if (! $this->data->bindParam(12,$row11, PDO::PARAM_INT)) {
       return false;
    }
	$row12 = ((! is_numeric($fdata->get_ITEM_SERVICEFEE())) ? null :  $fdata->get_ITEM_SERVICEFEE());
	if (! $this->data->bindParam(13,$row12, PDO::PARAM_STR)) {
       return false;
    }
	$row13 = ((! is_numeric($fdata->get_ITEM_TVA())) ? null :  $fdata->get_ITEM_TVA());
	if (! $this->data->bindParam(14,$row13, PDO::PARAM_STR)) {
       return false;
    }
	$row14 = $fdata->get_CURRENCY_ID();
	if (! $this->data->bindParam(15,$row14, PDO::PARAM_STR)) {
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
    
    $strSql = 'SELECT * FROM tickets_order_items';
                
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
        $row['data']['DATBEG'] =  $obj->EVTDAT_DATBEG;
        $row['data']['DATEND'] =  $obj->EVTDAT_DATEND;
        $row['data']['TICKETTYPE'] =  $obj->ID_TICKETTYPE;
        $row['data']['TICORDER'] =  $obj->ID_TICORDER;
        $row['data']['ZONEIDBEG'] =  $obj->ID_ZONEIDBEG;
        $row['data']['ZONEIDEND'] =  $obj->ID_ZONEIDEND;
        $row['data']['ITEM_DATINS'] =  $obj->TICORDER_ITEM_DATINS;
        $row['data']['ITEM_DATMOD'] =  $obj->TICORDER_ITEM_DATMOD;
        $row['data']['ITEM_PRICE'] =  $obj->TICORDER_ITEM_PRICE;
        $row['data']['ITEM_PRICEHT'] =  $obj->TICORDER_ITEM_PRICEHT;
        $row['data']['ITEM_PROCESSINGFEE'] =  $obj->TICORDER_ITEM_PROCESSINGFEE;
        $row['data']['ITEM_QTE'] =  $obj->TICORDER_ITEM_QTE;
        $row['data']['ITEM_SERVICEFEE'] =  $obj->TICORDER_ITEM_SERVICEFEE;
        $row['data']['ITEM_TVA'] =  $obj->TICORDER_ITEM_TVA;
        $row['data']['CURRENCY_ID'] =  $obj->TIC_CURRENCY_ID;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\TicketsOrderItemsData {
     $ID_TICORDER = isset($form->TICORDER) ? filter_var($form->TICORDER, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_TICKETTYPE = isset($form->TICKETTYPE) ? filter_var($form->TICKETTYPE, FILTER_SANITIZE_NUMBER_INT): null; 
     $TICORDER_ITEM_QTE = isset($form->ITEM_QTE) ? filter_var($form->ITEM_QTE, FILTER_SANITIZE_NUMBER_INT): null; 
     $TICORDER_ITEM_PRICE = isset($form->ITEM_PRICE) ? filter_var($form->ITEM_PRICE, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION): null; 
     $TICORDER_ITEM_DATINS = isset($form->ITEM_DATINS) ? $form->ITEM_DATINS : null; 
     $TICORDER_ITEM_PRICEHT = isset($form->ITEM_PRICEHT) ? filter_var($form->ITEM_PRICEHT, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION): null; 
     $TICORDER_ITEM_TVA = isset($form->ITEM_TVA) ? filter_var($form->ITEM_TVA, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION): null; 
     $TIC_CURRENCY_ID = isset($form->CURRENCY_ID) ? htmlspecialchars($form->CURRENCY_ID) : null; 
     $TICORDER_ITEM_DATMOD = isset($form->ITEM_DATMOD) ? $form->ITEM_DATMOD : null; 
     $TICORDER_ITEM_SERVICEFEE = isset($form->ITEM_SERVICEFEE) ? filter_var($form->ITEM_SERVICEFEE, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION): null; 
     $EVTDAT_DATBEG = isset($form->DATBEG) ? $form->DATBEG : null; 
     $TICORDER_ITEM_PROCESSINGFEE = isset($form->ITEM_PROCESSINGFEE) ? filter_var($form->ITEM_PROCESSINGFEE, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION): null; 
     $EVTDAT_DATEND = isset($form->DATEND) ? $form->DATEND : null; 
     $ID_ZONEIDBEG = isset($form->ZONEIDBEG) ? filter_var($form->ZONEIDBEG, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_ZONEIDEND = isset($form->ZONEIDEND) ? filter_var($form->ZONEIDEND, FILTER_SANITIZE_NUMBER_INT): null;        
     $fdata = new \Moviao\Data\Rad\TicketsOrderItemsData();   
     $fdata->set_TICORDER($ID_TICORDER);
     $fdata->set_TICKETTYPE($ID_TICKETTYPE);
     $fdata->set_ITEM_QTE($TICORDER_ITEM_QTE);
     $fdata->set_ITEM_PRICE($TICORDER_ITEM_PRICE);
     $fdata->set_ITEM_DATINS($TICORDER_ITEM_DATINS);
     $fdata->set_ITEM_PRICEHT($TICORDER_ITEM_PRICEHT);
     $fdata->set_ITEM_TVA($TICORDER_ITEM_TVA);
     $fdata->set_CURRENCY_ID($TIC_CURRENCY_ID);
     $fdata->set_ITEM_DATMOD($TICORDER_ITEM_DATMOD);
     $fdata->set_ITEM_SERVICEFEE($TICORDER_ITEM_SERVICEFEE);
     $fdata->set_DATBEG($EVTDAT_DATBEG);
     $fdata->set_ITEM_PROCESSINGFEE($TICORDER_ITEM_PROCESSINGFEE);
     $fdata->set_DATEND($EVTDAT_DATEND);
     $fdata->set_ZONEIDBEG($ID_ZONEIDBEG);
     $fdata->set_ZONEIDEND($ID_ZONEIDEND); 
     return $fdata;
}

}