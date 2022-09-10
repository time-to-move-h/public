<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Data API Comments
namespace Moviao\Data\Rad;
use PDO;

class Comments {  
private $commonData;
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\CommentsData $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = "INSERT INTO comments (COM_ACTIVE, COM_DATCRE, COM_DESC, COM_IDCOMLNK, COM_IDCOMLNKTYP, ID_USR) VALUES (?,?,?,?,?,?)";
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((empty($fdata->get_ACTIVE())) ? 0 :  (int)($fdata->get_ACTIVE()));
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
             return false;
        }
	$row1 = ((empty($fdata->get_DATCRE())) ? date('Y-m-d H:i:s') :  $fdata->get_DATCRE());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_STR)) {
             return false;
        }
	$row2 = ((empty($fdata->get_DESC())) ? '' :  $fdata->get_DESC());
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
             return false;
        }
	$row3 = ((empty($fdata->get_IDCOMLNK())) ? 0 :  $fdata->get_IDCOMLNK());
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_INT)) {
             return false;
        }
	$row4 = ((empty($fdata->get_IDCOMLNKTYP())) ? 0 :  (int)($fdata->get_IDCOMLNKTYP()));
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_INT)) {
             return false;
        }
	$row5 = ((empty($fdata->get_USR())) ? 0 :  $fdata->get_USR());
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_INT)) {
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
    
    $strSql = "SELECT * FROM comments";        
        
        
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
        $row['data']['ACTIVE'] =  $obj->COM_ACTIVE;
        $row['data']['DATCRE'] =  $obj->COM_DATCRE;
        $row['data']['DESC'] =  $obj->COM_DESC;
        $row['data']['IDCOM'] =  $obj->COM_IDCOM;
        $row['data']['IDCOMLNK'] =  $obj->COM_IDCOMLNK;
        $row['data']['IDCOMLNKTYP'] =  $obj->COM_IDCOMLNKTYP;
        $row['data']['USR'] =  $obj->ID_USR;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log("RAD Show >> : $e");
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\CommentsData {
     $COM_IDCOM = isset($form->IDCOM) ? filter_var($form->IDCOM, FILTER_SANITIZE_STRING): NULL; 
     $COM_IDCOMLNK = isset($form->IDCOMLNK) ? filter_var($form->IDCOMLNK, FILTER_SANITIZE_STRING): NULL; 
     $COM_IDCOMLNKTYP = isset($form->IDCOMLNKTYP) ? filter_var($form->IDCOMLNKTYP, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $ID_USR = isset($form->USR) ? filter_var($form->USR, FILTER_SANITIZE_STRING): NULL; 
     $COM_DESC = isset($form->DESC) ? filter_var($form->DESC, FILTER_SANITIZE_STRING): NULL; 
     $COM_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): NULL; 
     $COM_DATCRE = isset($form->DATCRE) ? filter_var($form->DATCRE, FILTER_SANITIZE_STRING): NULL;        
     $fdata = new \Moviao\Data\Rad\CommentsData();   
     $fdata->set_IDCOM($COM_IDCOM);
     $fdata->set_IDCOMLNK($COM_IDCOMLNK);
     $fdata->set_IDCOMLNKTYP($COM_IDCOMLNKTYP);
     $fdata->set_USR($ID_USR);
     $fdata->set_DESC($COM_DESC);
     $fdata->set_ACTIVE($COM_ACTIVE);
     $fdata->set_DATCRE($COM_DATCRE); 
     return $fdata;
}
}