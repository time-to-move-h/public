<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Data API Channels_location
namespace Moviao\Data\Rad;
use PDO;

class ChannelsLocation {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\ChannelsLocationData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO channels_location (CHALOC_ACTIVE, CHALOC_CITY, CHALOC_COUNTRY, CHALOC_COUNTRY_CODE, CHALOC_DATINS, CHALOC_DATMOD, CHALOC_LOCATIONP, CHALOC_OSMID, CHALOC_PCODE, CHALOC_STATE, CHALOC_STREET, CHALOC_STREET2, CHALOC_STREETN, CHALOC_VENUE, ID_CHA) VALUES (?,?,?,?,?,?,ST_PointFromText(?),?,?,?,?,?,?,?,?)";
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((empty($fdata->get_ACTIVE())) ? 0 :  (int)($fdata->get_ACTIVE()));
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
             return false;
        }
	$row1 = ((empty($fdata->get_CITY())) ? '' :  $fdata->get_CITY());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_STR)) {
             return false;
        }
	$row2 = ((empty($fdata->get_COUNTRY())) ? '' :  $fdata->get_COUNTRY());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
             return false;
        }
	$row3 = ((empty($fdata->get_COUNTRY_CODE())) ? 0 :  (int)($fdata->get_COUNTRY_CODE()));
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_INT)) {
             return false;
        }
	$row4 = ((empty($fdata->get_DATINS())) ? date('Y-m-d H:i:s') :  $fdata->get_DATINS());
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_STR)) {
             return false;
        }
	$row5 = ((empty($fdata->get_DATMOD())) ? NULL :  $fdata->get_DATMOD());
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
             return false;
        }
	$row6 = ((empty($fdata->get_LOCATIONP())) ? '' :  $fdata->get_LOCATIONP());
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_STR)) {
             return false;
        }
	$row7 = ((empty($fdata->get_OSMID())) ? NULL :  $fdata->get_OSMID());
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_INT)) {
             return false;
        }
	$row8 = ((empty($fdata->get_PCODE())) ? NULL :  $fdata->get_PCODE());
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_STR)) {
             return false;
        }
	$row9 = ((empty($fdata->get_STATE())) ? '' :  $fdata->get_STATE());
	if (! $this->data->bindParam(10,$row9, PDO::PARAM_STR)) {
             return false;
        }
	$row10 = ((empty($fdata->get_STREET())) ? NULL :  $fdata->get_STREET());
	if (! $this->data->bindParam(11,$row10, PDO::PARAM_STR)) {
             return false;
        }
	$row11 = ((empty($fdata->get_STREET2())) ? NULL :  $fdata->get_STREET2());
	if (! $this->data->bindParam(12,$row11, PDO::PARAM_STR)) {
             return false;
        }
	$row12 = ((empty($fdata->get_STREETN())) ? NULL :  $fdata->get_STREETN());
	if (! $this->data->bindParam(13,$row12, PDO::PARAM_STR)) {
             return false;
        }
	$row13 = ((empty($fdata->get_VENUE())) ? NULL :  $fdata->get_VENUE());
	if (! $this->data->bindParam(14,$row13, PDO::PARAM_STR)) {
             return false;
        }
	$row14 = ((empty($fdata->get_CHA())) ? 0 :  $fdata->get_CHA());
	if (! $this->data->bindParam(15,$row14, PDO::PARAM_INT)) {
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
    
    $strSql = "SELECT * FROM channels_location";        
        
        
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
        $row['data']['ACTIVE'] =  $obj->CHALOC_ACTIVE;
        $row['data']['CITY'] =  $obj->CHALOC_CITY;
        $row['data']['COUNTRY'] =  $obj->CHALOC_COUNTRY;
        $row['data']['COUNTRY_CODE'] =  $obj->CHALOC_COUNTRY_CODE;
        $row['data']['DATINS'] =  $obj->CHALOC_DATINS;
        $row['data']['DATMOD'] =  $obj->CHALOC_DATMOD;
        $row['data']['LOCATIONP'] =  $obj->CHALOC_LOCATIONP;
        $row['data']['OSMID'] =  $obj->CHALOC_OSMID;
        $row['data']['PCODE'] =  $obj->CHALOC_PCODE;
        $row['data']['STATE'] =  $obj->CHALOC_STATE;
        $row['data']['STREET'] =  $obj->CHALOC_STREET;
        $row['data']['STREET2'] =  $obj->CHALOC_STREET2;
        $row['data']['STREETN'] =  $obj->CHALOC_STREETN;
        $row['data']['VENUE'] =  $obj->CHALOC_VENUE;
        $row['data']['CHA'] =  $obj->ID_CHA;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\ChannelsLocationData {
     $ID_CHA = isset($form->CHA) ? filter_var($form->CHA, FILTER_SANITIZE_STRING): NULL; 
     $CHALOC_COUNTRY = isset($form->COUNTRY) ? filter_var($form->COUNTRY, FILTER_SANITIZE_STRING): NULL; 
     $CHALOC_COUNTRY_CODE = isset($form->COUNTRY_CODE) ? filter_var($form->COUNTRY_CODE, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $CHALOC_STATE = isset($form->STATE) ? filter_var($form->STATE, FILTER_SANITIZE_STRING): NULL; 
     $CHALOC_PCODE = isset($form->PCODE) ? filter_var($form->PCODE, FILTER_SANITIZE_STRING): NULL; 
     $CHALOC_CITY = isset($form->CITY) ? filter_var($form->CITY, FILTER_SANITIZE_STRING): NULL; 
     $CHALOC_STREET = isset($form->STREET) ? filter_var($form->STREET, FILTER_SANITIZE_STRING): NULL; 
     $CHALOC_STREETN = isset($form->STREETN) ? filter_var($form->STREETN, FILTER_SANITIZE_STRING): NULL; 
     $CHALOC_STREET2 = isset($form->STREET2) ? filter_var($form->STREET2, FILTER_SANITIZE_STRING): NULL; 
     $CHALOC_OSMID = isset($form->OSMID) ? filter_var($form->OSMID, FILTER_SANITIZE_STRING): NULL; 
     $CHALOC_LOCATIONP = isset($form->LOCATIONP) ? filter_var($form->LOCATIONP, FILTER_SANITIZE_STRING): NULL; 
     $CHALOC_VENUE = isset($form->VENUE) ? filter_var($form->VENUE, FILTER_SANITIZE_STRING): NULL; 
     $CHALOC_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $CHALOC_DATINS = isset($form->DATINS) ? filter_var($form->DATINS, FILTER_SANITIZE_STRING): NULL; 
     $CHALOC_DATMOD = isset($form->DATMOD) ? filter_var($form->DATMOD, FILTER_SANITIZE_STRING): NULL;        
     $fdata = new \Moviao\Data\Rad\ChannelsLocationData();   
     $fdata->set_CHA($ID_CHA);
     $fdata->set_COUNTRY($CHALOC_COUNTRY);
     $fdata->set_COUNTRY_CODE($CHALOC_COUNTRY_CODE);
     $fdata->set_STATE($CHALOC_STATE);
     $fdata->set_PCODE($CHALOC_PCODE);
     $fdata->set_CITY($CHALOC_CITY);
     $fdata->set_STREET($CHALOC_STREET);
     $fdata->set_STREETN($CHALOC_STREETN);
     $fdata->set_STREET2($CHALOC_STREET2);
     $fdata->set_OSMID($CHALOC_OSMID);
     $fdata->set_LOCATIONP($CHALOC_LOCATIONP);
     $fdata->set_VENUE($CHALOC_VENUE);
     $fdata->set_ACTIVE($CHALOC_ACTIVE);
     $fdata->set_DATINS($CHALOC_DATINS);
     $fdata->set_DATMOD($CHALOC_DATMOD); 
     return $fdata;
}
}