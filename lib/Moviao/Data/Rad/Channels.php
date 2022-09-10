<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2020-2021.
// Data API Channels
namespace Moviao\Data\Rad;
use PDO;

class Channels {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\ChannelsData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO channels (CHA_ABOUT, CHA_ACTIVE, CHA_CONFIRM, CHA_DATINS, CHA_DATMOD, CHA_DESCL, CHA_NAME, CHA_OFFICIAL, CHA_ONLINE, CHA_ORG, CHA_PICTURE, CHA_PICTURE_MIN, CHA_PICTURE_RND, CHA_TITLE, ID_CHAVIS) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = $fdata->get_ABOUT();
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_STR)) {
       return false;
    }
	$row1 = ((! is_numeric($fdata->get_ACTIVE())) ? null :  (int) $fdata->get_ACTIVE());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_INT)) {
       return false;
    }
	$row2 = ((! is_numeric($fdata->get_CONFIRM())) ? null :  (int) $fdata->get_CONFIRM());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_INT)) {
       return false;
    }
	$row3 = $fdata->get_DATINS();
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_STR)) {
       return false;
    }
	$row4 = $fdata->get_DATMOD();
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_STR)) {
       return false;
    }
	$row5 = $fdata->get_DESCL();
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
       return false;
    }
	$row6 = $fdata->get_NAME();
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_STR)) {
       return false;
    }
	$row7 = ((! is_numeric($fdata->get_OFFICIAL())) ? null :  (int) $fdata->get_OFFICIAL());
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_INT)) {
       return false;
    }
	$row8 = ((! is_numeric($fdata->get_ONLINE())) ? null :  (int) $fdata->get_ONLINE());
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_INT)) {
       return false;
    }
	$row9 = $fdata->get_ORG();
	if (! $this->data->bindParam(10,$row9, PDO::PARAM_STR)) {
       return false;
    }
	$row10 = $fdata->get_PICTURE();
	if (! $this->data->bindParam(11,$row10, PDO::PARAM_STR)) {
       return false;
    }
	$row11 = $fdata->get_PICTURE_MIN();
	if (! $this->data->bindParam(12,$row11, PDO::PARAM_STR)) {
       return false;
    }
	$row12 = $fdata->get_PICTURE_RND();
	if (! $this->data->bindParam(13,$row12, PDO::PARAM_STR)) {
       return false;
    }
	$row13 = $fdata->get_TITLE();
	if (! $this->data->bindParam(14,$row13, PDO::PARAM_STR)) {
       return false;
    }
	$row14 = ((! is_numeric($fdata->get_CHAVIS())) ? null :  (int) $fdata->get_CHAVIS());
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
    
    $strSql = 'SELECT * FROM channels';
                
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
        $row['data']['ABOUT'] =  $obj->CHA_ABOUT;
        $row['data']['ACTIVE'] =  $obj->CHA_ACTIVE;
        $row['data']['CONFIRM'] =  $obj->CHA_CONFIRM;
        $row['data']['DATINS'] =  $obj->CHA_DATINS;
        $row['data']['DATMOD'] =  $obj->CHA_DATMOD;
        $row['data']['DESCL'] =  $obj->CHA_DESCL;
        $row['data']['NAME'] =  $obj->CHA_NAME;
        $row['data']['OFFICIAL'] =  $obj->CHA_OFFICIAL;
        $row['data']['ONLINE'] =  $obj->CHA_ONLINE;
        $row['data']['ORG'] =  $obj->CHA_ORG;
        $row['data']['PICTURE'] =  $obj->CHA_PICTURE;
        $row['data']['PICTURE_MIN'] =  $obj->CHA_PICTURE_MIN;
        $row['data']['PICTURE_RND'] =  $obj->CHA_PICTURE_RND;
        $row['data']['TITLE'] =  $obj->CHA_TITLE;
        $row['data']['CHA'] =  $obj->ID_CHA;
        $row['data']['CHAVIS'] =  $obj->ID_CHAVIS;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\ChannelsData {
     $ID_CHA = isset($form->CHA) ? filter_var($form->CHA, FILTER_SANITIZE_NUMBER_INT): null; 
     $CHA_NAME = isset($form->NAME) ? filter_var($form->NAME, FILTER_SANITIZE_STRING): null; 
     $CHA_TITLE = isset($form->TITLE) ? filter_var($form->TITLE, FILTER_SANITIZE_STRING): null; 
     $CHA_PICTURE = isset($form->PICTURE) ? filter_var($form->PICTURE, FILTER_SANITIZE_STRING): null; 
     $CHA_PICTURE_MIN = isset($form->PICTURE_MIN) ? filter_var($form->PICTURE_MIN, FILTER_SANITIZE_STRING): null; 
     $CHA_PICTURE_RND = isset($form->PICTURE_RND) ? filter_var($form->PICTURE_RND, FILTER_SANITIZE_STRING): null; 
     $CHA_DESCL = isset($form->DESCL) ? filter_var($form->DESCL, FILTER_SANITIZE_STRING): null; 
     $CHA_ABOUT = isset($form->ABOUT) ? filter_var($form->ABOUT, FILTER_SANITIZE_STRING): null; 
     $CHA_ORG = isset($form->ORG) ? filter_var($form->ORG, FILTER_SANITIZE_STRING): null; 
     $ID_CHAVIS = isset($form->CHAVIS) ? filter_var($form->CHAVIS, FILTER_SANITIZE_NUMBER_INT): null; 
     $CHA_ONLINE = isset($form->ONLINE) ? filter_var($form->ONLINE, FILTER_SANITIZE_NUMBER_INT): null; 
     $CHA_OFFICIAL = isset($form->OFFICIAL) ? filter_var($form->OFFICIAL, FILTER_SANITIZE_NUMBER_INT): null; 
     $CHA_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): null; 
     $CHA_CONFIRM = isset($form->CONFIRM) ? filter_var($form->CONFIRM, FILTER_SANITIZE_NUMBER_INT): null; 
     $CHA_DATINS = isset($form->DATINS) ? $form->DATINS : null; 
     $CHA_DATMOD = isset($form->DATMOD) ? $form->DATMOD : null;        
     $fdata = new \Moviao\Data\Rad\ChannelsData();   
     $fdata->set_CHA($ID_CHA);
     $fdata->set_NAME($CHA_NAME);
     $fdata->set_TITLE($CHA_TITLE);
     $fdata->set_PICTURE($CHA_PICTURE);
     $fdata->set_PICTURE_MIN($CHA_PICTURE_MIN);
     $fdata->set_PICTURE_RND($CHA_PICTURE_RND);
     $fdata->set_DESCL($CHA_DESCL);
     $fdata->set_ABOUT($CHA_ABOUT);
     $fdata->set_ORG($CHA_ORG);
     $fdata->set_CHAVIS($ID_CHAVIS);
     $fdata->set_ONLINE($CHA_ONLINE);
     $fdata->set_OFFICIAL($CHA_OFFICIAL);
     $fdata->set_ACTIVE($CHA_ACTIVE);
     $fdata->set_CONFIRM($CHA_CONFIRM);
     $fdata->set_DATINS($CHA_DATINS);
     $fdata->set_DATMOD($CHA_DATMOD); 
     return $fdata;
}
}