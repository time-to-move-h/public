<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// Data API Users_google
namespace Moviao\Data\Rad;
use PDO;

class UsersGoogle {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\UsersGoogleData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO users_google (UGG_BIRTHDAY, UGG_COVER, UGG_DATEINS, UGG_DATEMOD, UGG_FNAME, UGG_ID, UGG_LINK, UGG_LNAME, UGG_MAIL, UGG_MNAME, UGG_NAME, UGG_PICTURE) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = $fdata->get_BIRTHDAY();
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_STR)) {
       return false;
    }
	$row1 = $fdata->get_COVER();
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_STR)) {
       return false;
    }
	$row2 = $fdata->get_DATEINS();
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
       return false;
    }
	$row3 = $fdata->get_DATEMOD();
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_STR)) {
       return false;
    }
	$row4 = $fdata->get_FNAME();
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_STR)) {
       return false;
    }
	$row5 = $fdata->get_ID();
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
       return false;
    }
	$row6 = $fdata->get_LINK();
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_STR)) {
       return false;
    }
	$row7 = $fdata->get_LNAME();
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_STR)) {
       return false;
    }
	$row8 = $fdata->get_MAIL();
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_STR)) {
       return false;
    }
	$row9 = $fdata->get_MNAME();
	if (! $this->data->bindParam(10,$row9, PDO::PARAM_STR)) {
       return false;
    }
	$row10 = $fdata->get_NAME();
	if (! $this->data->bindParam(11,$row10, PDO::PARAM_STR)) {
       return false;
    }
	$row11 = $fdata->get_PICTURE();
	if (! $this->data->bindParam(12,$row11, PDO::PARAM_STR)) {
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
    
    $strSql = 'SELECT * FROM users_google';
                
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
        $row['data']['BIRTHDAY'] =  $obj->UGG_BIRTHDAY;
        $row['data']['COVER'] =  $obj->UGG_COVER;
        $row['data']['DATEINS'] =  $obj->UGG_DATEINS;
        $row['data']['DATEMOD'] =  $obj->UGG_DATEMOD;
        $row['data']['FNAME'] =  $obj->UGG_FNAME;
        $row['data']['ID'] =  $obj->UGG_ID;
        $row['data']['LINK'] =  $obj->UGG_LINK;
        $row['data']['LNAME'] =  $obj->UGG_LNAME;
        $row['data']['MAIL'] =  $obj->UGG_MAIL;
        $row['data']['MNAME'] =  $obj->UGG_MNAME;
        $row['data']['NAME'] =  $obj->UGG_NAME;
        $row['data']['PICTURE'] =  $obj->UGG_PICTURE;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\UsersGoogleData {
     $UGG_ID = isset($form->ID) ? $this->cleanData($form->ID) : null; 
     $UGG_MAIL = isset($form->MAIL) ? $this->cleanData($form->MAIL) : null; 
     $UGG_NAME = isset($form->NAME) ? $this->cleanData($form->NAME) : null; 
     $UGG_FNAME = isset($form->FNAME) ? $this->cleanData($form->FNAME) : null; 
     $UGG_MNAME = isset($form->MNAME) ? $this->cleanData($form->MNAME) : null; 
     $UGG_LNAME = isset($form->LNAME) ? $this->cleanData($form->LNAME) : null; 
     $UGG_LINK = isset($form->LINK) ? $this->cleanData($form->LINK) : null; 
     $UGG_BIRTHDAY = isset($form->BIRTHDAY) ? $form->BIRTHDAY : null; 
     $UGG_COVER = isset($form->COVER) ? $this->cleanData($form->COVER) : null; 
     $UGG_PICTURE = isset($form->PICTURE) ? $this->cleanData($form->PICTURE) : null; 
     $UGG_DATEINS = isset($form->DATEINS) ? $form->DATEINS : null; 
     $UGG_DATEMOD = isset($form->DATEMOD) ? $form->DATEMOD : null;        
     $fdata = new \Moviao\Data\Rad\UsersGoogleData();   
     $fdata->set_ID($UGG_ID);
     $fdata->set_MAIL($UGG_MAIL);
     $fdata->set_NAME($UGG_NAME);
     $fdata->set_FNAME($UGG_FNAME);
     $fdata->set_MNAME($UGG_MNAME);
     $fdata->set_LNAME($UGG_LNAME);
     $fdata->set_LINK($UGG_LINK);
     $fdata->set_BIRTHDAY($UGG_BIRTHDAY);
     $fdata->set_COVER($UGG_COVER);
     $fdata->set_PICTURE($UGG_PICTURE);
     $fdata->set_DATEINS($UGG_DATEINS);
     $fdata->set_DATEMOD($UGG_DATEMOD); 
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