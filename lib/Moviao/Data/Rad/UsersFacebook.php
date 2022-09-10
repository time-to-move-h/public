<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Data API Users_facebook
namespace Moviao\Data\Rad;
use PDO;

class UsersFacebook {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\UsersFacebookData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO users_facebook (UFB_BIRTHDAY, UFB_COVER, UFB_DATEINS, UFB_DATEMOD, UFB_FNAME, UFB_ID, UFB_LINK, UFB_LNAME, UFB_MAIL, UFB_MNAME, UFB_NAME, UFB_PICTURE) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((empty($fdata->get_BIRTHDAY())) ? NULL :  $fdata->get_BIRTHDAY());
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_STR)) {
             return false;
        }
	$row1 = ((empty($fdata->get_COVER())) ? NULL :  $fdata->get_COVER());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_STR)) {
             return false;
        }
	$row2 = ((empty($fdata->get_DATEINS())) ? date('Y-m-d H:i:s') :  $fdata->get_DATEINS());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
             return false;
        }
	$row3 = ((empty($fdata->get_DATEMOD())) ? NULL :  $fdata->get_DATEMOD());
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_STR)) {
             return false;
        }
	$row4 = ((empty($fdata->get_FNAME())) ? '' :  $fdata->get_FNAME());
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_STR)) {
             return false;
        }
	$row5 = ((empty($fdata->get_ID())) ? 0 :  $fdata->get_ID());
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_INT)) {
             return false;
        }
	$row6 = ((empty($fdata->get_LINK())) ? NULL :  $fdata->get_LINK());
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_STR)) {
             return false;
        }
	$row7 = ((empty($fdata->get_LNAME())) ? '' :  $fdata->get_LNAME());
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_STR)) {
             return false;
        }
	$row8 = ((empty($fdata->get_MAIL())) ? NULL :  $fdata->get_MAIL());
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_STR)) {
             return false;
        }
	$row9 = ((empty($fdata->get_MNAME())) ? NULL :  $fdata->get_MNAME());
	if (! $this->data->bindParam(10,$row9, PDO::PARAM_STR)) {
             return false;
        }
	$row10 = ((empty($fdata->get_NAME())) ? '' :  $fdata->get_NAME());
	if (! $this->data->bindParam(11,$row10, PDO::PARAM_STR)) {
             return false;
        }
	$row11 = ((empty($fdata->get_PICTURE())) ? NULL :  $fdata->get_PICTURE());
	if (! $this->data->bindParam(12,$row11, PDO::PARAM_STR)) {
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
    
    $strSql = "SELECT * FROM users_facebook";        
        
        
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
        $row['data']['BIRTHDAY'] =  $obj->UFB_BIRTHDAY;
        $row['data']['COVER'] =  $obj->UFB_COVER;
        $row['data']['DATEINS'] =  $obj->UFB_DATEINS;
        $row['data']['DATEMOD'] =  $obj->UFB_DATEMOD;
        $row['data']['FNAME'] =  $obj->UFB_FNAME;
        $row['data']['ID'] =  $obj->UFB_ID;
        $row['data']['LINK'] =  $obj->UFB_LINK;
        $row['data']['LNAME'] =  $obj->UFB_LNAME;
        $row['data']['MAIL'] =  $obj->UFB_MAIL;
        $row['data']['MNAME'] =  $obj->UFB_MNAME;
        $row['data']['NAME'] =  $obj->UFB_NAME;
        $row['data']['PICTURE'] =  $obj->UFB_PICTURE;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\UsersFacebookData {
     $UFB_ID = isset($form->ID) ? filter_var($form->ID, FILTER_SANITIZE_STRING): NULL; 
     $UFB_MAIL = isset($form->MAIL) ? filter_var($form->MAIL, FILTER_SANITIZE_STRING): NULL; 
     $UFB_NAME = isset($form->NAME) ? filter_var($form->NAME, FILTER_SANITIZE_STRING): NULL; 
     $UFB_FNAME = isset($form->FNAME) ? filter_var($form->FNAME, FILTER_SANITIZE_STRING): NULL; 
     $UFB_MNAME = isset($form->MNAME) ? filter_var($form->MNAME, FILTER_SANITIZE_STRING): NULL; 
     $UFB_LNAME = isset($form->LNAME) ? filter_var($form->LNAME, FILTER_SANITIZE_STRING): NULL; 
     $UFB_LINK = isset($form->LINK) ? filter_var($form->LINK, FILTER_SANITIZE_STRING): NULL; 
     $UFB_BIRTHDAY = isset($form->BIRTHDAY) ? filter_var($form->BIRTHDAY, FILTER_SANITIZE_STRING): NULL; 
     $UFB_COVER = isset($form->COVER) ? filter_var($form->COVER, FILTER_SANITIZE_STRING): NULL; 
     $UFB_PICTURE = isset($form->PICTURE) ? filter_var($form->PICTURE, FILTER_SANITIZE_STRING): NULL; 
     $UFB_DATEINS = isset($form->DATEINS) ? filter_var($form->DATEINS, FILTER_SANITIZE_STRING): NULL; 
     $UFB_DATEMOD = isset($form->DATEMOD) ? filter_var($form->DATEMOD, FILTER_SANITIZE_STRING): NULL;        
     $fdata = new \Moviao\Data\Rad\UsersFacebookData();   
     $fdata->set_ID($UFB_ID);
     $fdata->set_MAIL($UFB_MAIL);
     $fdata->set_NAME($UFB_NAME);
     $fdata->set_FNAME($UFB_FNAME);
     $fdata->set_MNAME($UFB_MNAME);
     $fdata->set_LNAME($UFB_LNAME);
     $fdata->set_LINK($UFB_LINK);
     $fdata->set_BIRTHDAY($UFB_BIRTHDAY);
     $fdata->set_COVER($UFB_COVER);
     $fdata->set_PICTURE($UFB_PICTURE);
     $fdata->set_DATEINS($UFB_DATEINS);
     $fdata->set_DATEMOD($UFB_DATEMOD); 
     return $fdata;
}
}