<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// Data API Tickets_type
namespace Moviao\Data\Rad;
use PDO;

class TicketsType {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\TicketsTypeData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO tickets_type (TICTYPE_ACTIVE, TICTYPE_DATINS, TICTYPE_DATMOD, TICTYPE_DESCL, TICTYPE_MAXQTE, TICTYPE_MINQTE, TICTYPE_NAME, TICTYPE_ONLINE, TICTYPE_PRICE, TICTYPE_PRICEHT, TICTYPE_QTE, TICTYPE_SALE_END, TICTYPE_SALE_START, TICTYPE_TVA, TIC_CURRENCY_ID) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((! is_numeric($fdata->get_ACTIVE())) ? null :  (int) $fdata->get_ACTIVE());
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
	$row3 = $fdata->get_DESCL();
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_STR)) {
       return false;
    }
	$row4 = ((! is_numeric($fdata->get_MAXQTE())) ? null :  (int) $fdata->get_MAXQTE());
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_INT)) {
       return false;
    }
	$row5 = ((! is_numeric($fdata->get_MINQTE())) ? null :  (int) $fdata->get_MINQTE());
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_INT)) {
       return false;
    }
	$row6 = $fdata->get_NAME();
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_STR)) {
       return false;
    }
	$row7 = ((! is_numeric($fdata->get_ONLINE())) ? null :  (int) $fdata->get_ONLINE());
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_INT)) {
       return false;
    }
	$row8 = ((! is_numeric($fdata->get_PRICE())) ? null :  $fdata->get_PRICE());
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_STR)) {
       return false;
    }
	$row9 = ((! is_numeric($fdata->get_PRICEHT())) ? null :  $fdata->get_PRICEHT());
	if (! $this->data->bindParam(10,$row9, PDO::PARAM_STR)) {
       return false;
    }
	$row10 = ((! is_numeric($fdata->get_QTE())) ? null :  (int) $fdata->get_QTE());
	if (! $this->data->bindParam(11,$row10, PDO::PARAM_INT)) {
       return false;
    }
	$row11 = $fdata->get_SALE_END();
	if (! $this->data->bindParam(12,$row11, PDO::PARAM_STR)) {
       return false;
    }
	$row12 = $fdata->get_SALE_START();
	if (! $this->data->bindParam(13,$row12, PDO::PARAM_STR)) {
       return false;
    }
	$row13 = ((! is_numeric($fdata->get_TVA())) ? null :  $fdata->get_TVA());
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
    
    $strSql = 'SELECT * FROM tickets_type';
                
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
        $row['data']['TICKETTYPE'] =  $obj->ID_TICKETTYPE;
        $row['data']['ACTIVE'] =  $obj->TICTYPE_ACTIVE;
        $row['data']['DATINS'] =  $obj->TICTYPE_DATINS;
        $row['data']['DATMOD'] =  $obj->TICTYPE_DATMOD;
        $row['data']['DESCL'] =  $obj->TICTYPE_DESCL;
        $row['data']['MAXQTE'] =  $obj->TICTYPE_MAXQTE;
        $row['data']['MINQTE'] =  $obj->TICTYPE_MINQTE;
        $row['data']['NAME'] =  $obj->TICTYPE_NAME;
        $row['data']['ONLINE'] =  $obj->TICTYPE_ONLINE;
        $row['data']['PRICE'] =  $obj->TICTYPE_PRICE;
        $row['data']['PRICEHT'] =  $obj->TICTYPE_PRICEHT;
        $row['data']['QTE'] =  $obj->TICTYPE_QTE;
        $row['data']['SALE_END'] =  $obj->TICTYPE_SALE_END;
        $row['data']['SALE_START'] =  $obj->TICTYPE_SALE_START;
        $row['data']['TVA'] =  $obj->TICTYPE_TVA;
        $row['data']['CURRENCY_ID'] =  $obj->TIC_CURRENCY_ID;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\TicketsTypeData {
     $ID_TICKETTYPE = isset($form->TICKETTYPE) ? filter_var($form->TICKETTYPE, FILTER_SANITIZE_NUMBER_INT): null; 
     $TICTYPE_NAME = isset($form->NAME) ? $this->cleanData($form->NAME) : null; 
     $TICTYPE_DESCL = isset($form->DESCL) ? $this->cleanData($form->DESCL) : null; 
     $TICTYPE_QTE = isset($form->QTE) ? filter_var($form->QTE, FILTER_SANITIZE_NUMBER_INT): null; 
     $TICTYPE_MINQTE = isset($form->MINQTE) ? filter_var($form->MINQTE, FILTER_SANITIZE_NUMBER_INT): null; 
     $TICTYPE_MAXQTE = isset($form->MAXQTE) ? filter_var($form->MAXQTE, FILTER_SANITIZE_NUMBER_INT): null; 
     $TICTYPE_PRICE = isset($form->PRICE) ? filter_var($form->PRICE, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION): null; 
     $TICTYPE_PRICEHT = isset($form->PRICEHT) ? filter_var($form->PRICEHT, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION): null; 
     $TICTYPE_SALE_START = isset($form->SALE_START) ? $form->SALE_START : null; 
     $TICTYPE_SALE_END = isset($form->SALE_END) ? $form->SALE_END : null; 
     $TICTYPE_TVA = isset($form->TVA) ? filter_var($form->TVA, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION): null; 
     $TICTYPE_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): null; 
     $TICTYPE_DATINS = isset($form->DATINS) ? $form->DATINS : null; 
     $TIC_CURRENCY_ID = isset($form->CURRENCY_ID) ? $this->cleanData($form->CURRENCY_ID) : null; 
     $TICTYPE_DATMOD = isset($form->DATMOD) ? $form->DATMOD : null; 
     $TICTYPE_ONLINE = isset($form->ONLINE) ? filter_var($form->ONLINE, FILTER_SANITIZE_NUMBER_INT): null;        
     $fdata = new \Moviao\Data\Rad\TicketsTypeData();   
     $fdata->set_TICKETTYPE($ID_TICKETTYPE);
     $fdata->set_NAME($TICTYPE_NAME);
     $fdata->set_DESCL($TICTYPE_DESCL);
     $fdata->set_QTE($TICTYPE_QTE);
     $fdata->set_MINQTE($TICTYPE_MINQTE);
     $fdata->set_MAXQTE($TICTYPE_MAXQTE);
     $fdata->set_PRICE($TICTYPE_PRICE);
     $fdata->set_PRICEHT($TICTYPE_PRICEHT);
     $fdata->set_SALE_START($TICTYPE_SALE_START);
     $fdata->set_SALE_END($TICTYPE_SALE_END);
     $fdata->set_TVA($TICTYPE_TVA);
     $fdata->set_ACTIVE($TICTYPE_ACTIVE);
     $fdata->set_DATINS($TICTYPE_DATINS);
     $fdata->set_CURRENCY_ID($TIC_CURRENCY_ID);
     $fdata->set_DATMOD($TICTYPE_DATMOD);
     $fdata->set_ONLINE($TICTYPE_ONLINE); 
     return $fdata;
}

private function cleanData($data)
{
    //$data = htmlspecialchars($data);
    //$data = \strip_tags($data);
    //$data = stripslashes($data);
    $data = trim($data);
    return $data;
}


}