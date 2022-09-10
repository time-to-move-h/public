<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// Data API Tickets_order
namespace Moviao\Data\Rad;
use PDO;

class TicketsOrder {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\TicketsOrderData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO tickets_order (ID_TICORDER_STATUS, ID_USR, TICORDER_ACTIVE, TICORDER_CITY, TICORDER_COUNTRY, TICORDER_DATINS, TICORDER_DATMOD, TICORDER_FIRSTNAME, TICORDER_LASTNAME, TICORDER_MAIL, TICORDER_MPHONE, TICORDER_PCODE, TICORDER_STATE, TICORDER_STREET, TICORDER_STREET2) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = $fdata->get_TICORDER_STATUS();
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_STR)) {
       return false;
    }
	$row1 = ((! is_numeric($fdata->get_USR())) ? null :  (int) $fdata->get_USR());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_INT)) {
       return false;
    }
	$row2 = ((! is_numeric($fdata->get_ACTIVE())) ? null :  (int) $fdata->get_ACTIVE());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_INT)) {
       return false;
    }
	$row3 = $fdata->get_CITY();
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_STR)) {
       return false;
    }
	$row4 = ((! is_numeric($fdata->get_COUNTRY())) ? null :  (int) $fdata->get_COUNTRY());
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_INT)) {
       return false;
    }
	$row5 = $fdata->get_DATINS();
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
       return false;
    }
	$row6 = $fdata->get_DATMOD();
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_STR)) {
       return false;
    }
	$row7 = $fdata->get_FIRSTNAME();
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_STR)) {
       return false;
    }
	$row8 = $fdata->get_LASTNAME();
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_STR)) {
       return false;
    }
	$row9 = $fdata->get_MAIL();
	if (! $this->data->bindParam(10,$row9, PDO::PARAM_STR)) {
       return false;
    }
	$row10 = $fdata->get_MPHONE();
	if (! $this->data->bindParam(11,$row10, PDO::PARAM_STR)) {
       return false;
    }
	$row11 = $fdata->get_PCODE();
	if (! $this->data->bindParam(12,$row11, PDO::PARAM_STR)) {
       return false;
    }
	$row12 = $fdata->get_STATE();
	if (! $this->data->bindParam(13,$row12, PDO::PARAM_STR)) {
       return false;
    }
	$row13 = $fdata->get_STREET();
	if (! $this->data->bindParam(14,$row13, PDO::PARAM_STR)) {
       return false;
    }
	$row14 = $fdata->get_STREET2();
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
    
    $strSql = 'SELECT * FROM tickets_order';
                
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
        $row['data']['TICORDER'] =  $obj->ID_TICORDER;
        $row['data']['TICORDER_STATUS'] =  $obj->ID_TICORDER_STATUS;
        $row['data']['USR'] =  $obj->ID_USR;
        $row['data']['ACTIVE'] =  $obj->TICORDER_ACTIVE;
        $row['data']['CITY'] =  $obj->TICORDER_CITY;
        $row['data']['COUNTRY'] =  $obj->TICORDER_COUNTRY;
        $row['data']['DATINS'] =  $obj->TICORDER_DATINS;
        $row['data']['DATMOD'] =  $obj->TICORDER_DATMOD;
        $row['data']['FIRSTNAME'] =  $obj->TICORDER_FIRSTNAME;
        $row['data']['LASTNAME'] =  $obj->TICORDER_LASTNAME;
        $row['data']['MAIL'] =  $obj->TICORDER_MAIL;
        $row['data']['MPHONE'] =  $obj->TICORDER_MPHONE;
        $row['data']['PCODE'] =  $obj->TICORDER_PCODE;
        $row['data']['STATE'] =  $obj->TICORDER_STATE;
        $row['data']['STREET'] =  $obj->TICORDER_STREET;
        $row['data']['STREET2'] =  $obj->TICORDER_STREET2;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\TicketsOrderData {
     $ID_TICORDER = isset($form->TICORDER) ? filter_var($form->TICORDER, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_TICORDER_STATUS = isset($form->TICORDER_STATUS) ? htmlspecialchars($form->TICORDER_STATUS) : null; 
     $ID_USR = isset($form->USR) ? filter_var($form->USR, FILTER_SANITIZE_NUMBER_INT): null; 
     $TICORDER_FIRSTNAME = isset($form->FIRSTNAME) ? htmlspecialchars($form->FIRSTNAME) : null; 
     $TICORDER_LASTNAME = isset($form->LASTNAME) ? htmlspecialchars($form->LASTNAME) : null; 
     $TICORDER_MAIL = isset($form->MAIL) ? htmlspecialchars($form->MAIL) : null; 
     $TICORDER_MPHONE = isset($form->MPHONE) ? htmlspecialchars($form->MPHONE) : null; 
     $TICORDER_STREET = isset($form->STREET) ? htmlspecialchars($form->STREET) : null; 
     $TICORDER_STREET2 = isset($form->STREET2) ? htmlspecialchars($form->STREET2) : null; 
     $TICORDER_CITY = isset($form->CITY) ? htmlspecialchars($form->CITY) : null; 
     $TICORDER_STATE = isset($form->STATE) ? htmlspecialchars($form->STATE) : null; 
     $TICORDER_PCODE = isset($form->PCODE) ? htmlspecialchars($form->PCODE) : null; 
     $TICORDER_COUNTRY = isset($form->COUNTRY) ? filter_var($form->COUNTRY, FILTER_SANITIZE_NUMBER_INT): null; 
     $TICORDER_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): null; 
     $TICORDER_DATINS = isset($form->DATINS) ? $form->DATINS : null; 
     $TICORDER_DATMOD = isset($form->DATMOD) ? $form->DATMOD : null;        
     $fdata = new \Moviao\Data\Rad\TicketsOrderData();   
     $fdata->set_TICORDER($ID_TICORDER);
     $fdata->set_TICORDER_STATUS($ID_TICORDER_STATUS);
     $fdata->set_USR($ID_USR);
     $fdata->set_FIRSTNAME($TICORDER_FIRSTNAME);
     $fdata->set_LASTNAME($TICORDER_LASTNAME);
     $fdata->set_MAIL($TICORDER_MAIL);
     $fdata->set_MPHONE($TICORDER_MPHONE);
     $fdata->set_STREET($TICORDER_STREET);
     $fdata->set_STREET2($TICORDER_STREET2);
     $fdata->set_CITY($TICORDER_CITY);
     $fdata->set_STATE($TICORDER_STATE);
     $fdata->set_PCODE($TICORDER_PCODE);
     $fdata->set_COUNTRY($TICORDER_COUNTRY);
     $fdata->set_ACTIVE($TICORDER_ACTIVE);
     $fdata->set_DATINS($TICORDER_DATINS);
     $fdata->set_DATMOD($TICORDER_DATMOD); 
     return $fdata;
}

}