<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2018-2019.
// Data API Users
namespace Moviao\Data\Rad;
use PDO;

class Users {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\UsersData $fdata) : bool {    
    $result = false;    
    try {

        //error_log("RAD create  ??? " . var_export($fdata, true));

        $strSql = 'INSERT INTO users (ID_ACCTYP, ID_ZONEID, USR_ABOUT, USR_ACTIVE, USR_ADDRESS, USR_BACKGROUND, USR_BACKGROUND_MIN, USR_BOX, USR_CITY, USR_CONST, USR_COUNTRY, USR_COUNTRY_CODE, USR_DATEINS, USR_DATEMOD, USR_DATEVAL, USR_DBIRTH, USR_EMAIL, USR_FNAME, USR_LANG, USR_LNAME, USR_LOCKED, USR_MAST, USR_MNAME, USR_MPHONE, USR_NDISP, USR_NNAME, USR_OFFICIAL, USR_PCODE, USR_PICTURE, USR_PNAME, USR_PROFILE, USR_SEX, USR_STATE, USR_STREET, USR_STREETN, USR_SUBJECT, USR_UUID, USR_WEBSITE) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
	$row0 = ((! is_numeric($fdata->get_ACCTYP())) ? null :  (int) $fdata->get_ACCTYP());
	if (! $this->data->bindParam(1,$row0, PDO::PARAM_INT)) {
       return false;
    }
	$row1 = ((! is_numeric($fdata->get_ZONEID())) ? null :  (int) $fdata->get_ZONEID());
	if (! $this->data->bindParam(2,$row1, PDO::PARAM_INT)) {
       return false;
    }
	$row2 = $fdata->get_ABOUT();
	if (! $this->data->bindParam(3,$row2, PDO::PARAM_STR)) {
       return false;
    }
	$row3 = ((! is_numeric($fdata->get_ACTIVE())) ? null :  (int) $fdata->get_ACTIVE());
	if (! $this->data->bindParam(4,$row3, PDO::PARAM_INT)) {
       return false;
    }
	$row4 = $fdata->get_ADDRESS();
	if (! $this->data->bindParam(5,$row4, PDO::PARAM_STR)) {
       return false;
    }
	$row5 = $fdata->get_BACKGROUND();
	if (! $this->data->bindParam(6,$row5, PDO::PARAM_STR)) {
       return false;
    }
	$row6 = $fdata->get_BACKGROUND_MIN();
	if (! $this->data->bindParam(7,$row6, PDO::PARAM_STR)) {
       return false;
    }
	$row7 = $fdata->get_BOX();
	if (! $this->data->bindParam(8,$row7, PDO::PARAM_STR)) {
       return false;
    }
	$row8 = $fdata->get_CITY();
	if (! $this->data->bindParam(9,$row8, PDO::PARAM_STR)) {
       return false;
    }
	$row9 = ((! is_numeric($fdata->get_CONST())) ? null :  (int) $fdata->get_CONST());
	if (! $this->data->bindParam(10,$row9, PDO::PARAM_INT)) {
       return false;
    }
	$row10 = $fdata->get_COUNTRY();
	if (! $this->data->bindParam(11,$row10, PDO::PARAM_STR)) {
       return false;
    }
	$row11 = ((! is_numeric($fdata->get_COUNTRY_CODE())) ? null :  (int) $fdata->get_COUNTRY_CODE());
	if (! $this->data->bindParam(12,$row11, PDO::PARAM_INT)) {
       return false;
    }
	$row12 = $fdata->get_DATEINS();
	if (! $this->data->bindParam(13,$row12, PDO::PARAM_STR)) {
       return false;
    }
	$row13 = $fdata->get_DATEMOD();
	if (! $this->data->bindParam(14,$row13, PDO::PARAM_STR)) {
       return false;
    }
	$row14 = $fdata->get_DATEVAL();
	if (! $this->data->bindParam(15,$row14, PDO::PARAM_STR)) {
       return false;
    }
	$row15 = $fdata->get_DBIRTH();
	if (! $this->data->bindParam(16,$row15, PDO::PARAM_STR)) {
       return false;
    }
	$row16 = $fdata->get_EMAIL();
	if (! $this->data->bindParam(17,$row16, PDO::PARAM_STR)) {
       return false;
    }
	$row17 = $fdata->get_FNAME();
	if (! $this->data->bindParam(18,$row17, PDO::PARAM_STR)) {
       return false;
    }
	$row18 = $fdata->get_LANG();
	if (! $this->data->bindParam(19,$row18, PDO::PARAM_STR)) {
       return false;
    }
	$row19 = $fdata->get_LNAME();
	if (! $this->data->bindParam(20,$row19, PDO::PARAM_STR)) {
       return false;
    }
	$row20 = ((! is_numeric($fdata->get_LOCKED())) ? null :  (int) $fdata->get_LOCKED());
	if (! $this->data->bindParam(21,$row20, PDO::PARAM_INT)) {
       return false;
    }
	$row21 = ((! is_numeric($fdata->get_MAST())) ? null :  (int) $fdata->get_MAST());
	if (! $this->data->bindParam(22,$row21, PDO::PARAM_INT)) {
       return false;
    }
	$row22 = $fdata->get_MNAME();
	if (! $this->data->bindParam(23,$row22, PDO::PARAM_STR)) {
       return false;
    }
	$row23 = $fdata->get_MPHONE();
	if (! $this->data->bindParam(24,$row23, PDO::PARAM_STR)) {
       return false;
    }
	$row24 = $fdata->get_NDISP();
	if (! $this->data->bindParam(25,$row24, PDO::PARAM_STR)) {
       return false;
    }
	$row25 = $fdata->get_NNAME();
	if (! $this->data->bindParam(26,$row25, PDO::PARAM_STR)) {
       return false;
    }
	$row26 = ((! is_numeric($fdata->get_OFFICIAL())) ? null :  (int) $fdata->get_OFFICIAL());
	if (! $this->data->bindParam(27,$row26, PDO::PARAM_INT)) {
       return false;
    }
	$row27 = $fdata->get_PCODE();
	if (! $this->data->bindParam(28,$row27, PDO::PARAM_STR)) {
       return false;
    }
	$row28 = $fdata->get_PICTURE();
	if (! $this->data->bindParam(29,$row28, PDO::PARAM_STR)) {
       return false;
    }
	$row29 = $fdata->get_PNAME();
	if (! $this->data->bindParam(30,$row29, PDO::PARAM_STR)) {
       return false;
    }
	$row30 = $fdata->get_PROFILE();
	if (! $this->data->bindParam(31,$row30, PDO::PARAM_STR)) {
       return false;
    }
	$row31 = ((! is_numeric($fdata->get_SEX())) ? null :  (int) $fdata->get_SEX());
	if (! $this->data->bindParam(32,$row31, PDO::PARAM_INT)) {
       return false;
    }
	$row32 = $fdata->get_STATE();
	if (! $this->data->bindParam(33,$row32, PDO::PARAM_STR)) {
       return false;
    }
	$row33 = $fdata->get_STREET();
	if (! $this->data->bindParam(34,$row33, PDO::PARAM_STR)) {
       return false;
    }
	$row34 = $fdata->get_STREETN();
	if (! $this->data->bindParam(35,$row34, PDO::PARAM_STR)) {
       return false;
    }
	$row35 = $fdata->get_SUBJECT();
	if (! $this->data->bindParam(36,$row35, PDO::PARAM_STR)) {
       return false;
    }
	$row36 = $fdata->get_UUID();
	if (! $this->data->bindParam(37,$row36, PDO::PARAM_STR)) {
       return false;
    }
	$row37 = $fdata->get_WEBSITE();
	if (! $this->data->bindParam(38,$row37, PDO::PARAM_STR)) {
       return false;
    }
	
                
    if (! $this->data->execute()) {
        error_log("RAD execute false ??? "  . var_export($this->data->errorCode(),true)   );
        return false;
    }
    $rowcount = $stmt->rowCount();
    if ($rowcount > 0) {
        $result = true;
    }

    //error_log("RAD rowcount false ??? " . $rowcount);


    } catch (\Error $e) {
        error_log("RAD Create >> : $e");
    }
    return $result;
}

public function show($where,$orderby = null,$limit = null) : array {
    $return_data = array();    
    try { 
    
    $strSql = 'SELECT * FROM users';
                
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
        $row['data']['ACCTYP'] =  $obj->ID_ACCTYP;
        $row['data']['USR'] =  $obj->ID_USR;
        $row['data']['ZONEID'] =  $obj->ID_ZONEID;
        $row['data']['ABOUT'] =  $obj->USR_ABOUT;
        $row['data']['ACTIVE'] =  $obj->USR_ACTIVE;
        $row['data']['ADDRESS'] =  $obj->USR_ADDRESS;
        $row['data']['BACKGROUND'] =  $obj->USR_BACKGROUND;
        $row['data']['BACKGROUND_MIN'] =  $obj->USR_BACKGROUND_MIN;
        $row['data']['BOX'] =  $obj->USR_BOX;
        $row['data']['CITY'] =  $obj->USR_CITY;
        $row['data']['CONST'] =  $obj->USR_CONST;
        $row['data']['COUNTRY'] =  $obj->USR_COUNTRY;
        $row['data']['COUNTRY_CODE'] =  $obj->USR_COUNTRY_CODE;
        $row['data']['DATEINS'] =  $obj->USR_DATEINS;
        $row['data']['DATEMOD'] =  $obj->USR_DATEMOD;
        $row['data']['DATEVAL'] =  $obj->USR_DATEVAL;
        $row['data']['DBIRTH'] =  $obj->USR_DBIRTH;
        $row['data']['EMAIL'] =  $obj->USR_EMAIL;
        $row['data']['FNAME'] =  $obj->USR_FNAME;
        $row['data']['LANG'] =  $obj->USR_LANG;
        $row['data']['LNAME'] =  $obj->USR_LNAME;
        $row['data']['LOCKED'] =  $obj->USR_LOCKED;
        $row['data']['MAST'] =  $obj->USR_MAST;
        $row['data']['MNAME'] =  $obj->USR_MNAME;
        $row['data']['MPHONE'] =  $obj->USR_MPHONE;
        $row['data']['NDISP'] =  $obj->USR_NDISP;
        $row['data']['NNAME'] =  $obj->USR_NNAME;
        $row['data']['OFFICIAL'] =  $obj->USR_OFFICIAL;
        $row['data']['PCODE'] =  $obj->USR_PCODE;
        $row['data']['PICTURE'] =  $obj->USR_PICTURE;
        $row['data']['PNAME'] =  $obj->USR_PNAME;
        $row['data']['PROFILE'] =  $obj->USR_PROFILE;
        $row['data']['SEX'] =  $obj->USR_SEX;
        $row['data']['STATE'] =  $obj->USR_STATE;
        $row['data']['STREET'] =  $obj->USR_STREET;
        $row['data']['STREETN'] =  $obj->USR_STREETN;
        $row['data']['SUBJECT'] =  $obj->USR_SUBJECT;
        $row['data']['UUID'] =  $obj->USR_UUID;
        $row['data']['WEBSITE'] =  $obj->USR_WEBSITE;
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\UsersData {
     $ID_USR = isset($form->USR) ? filter_var($form->USR, FILTER_SANITIZE_NUMBER_INT): null; 
     $USR_UUID = isset($form->UUID) ? filter_var($form->UUID, FILTER_SANITIZE_STRING): null; 
     $USR_SUBJECT = isset($form->SUBJECT) ? filter_var($form->SUBJECT, FILTER_SANITIZE_STRING): null; 
     $USR_ABOUT = isset($form->ABOUT) ? filter_var($form->ABOUT, FILTER_SANITIZE_STRING): null; 
     $USR_NNAME = isset($form->NNAME) ? filter_var($form->NNAME, FILTER_SANITIZE_STRING): null; 
     $USR_NDISP = isset($form->NDISP) ? filter_var($form->NDISP, FILTER_SANITIZE_STRING): null; 
     $USR_FNAME = isset($form->FNAME) ? filter_var($form->FNAME, FILTER_SANITIZE_STRING): null; 
     $USR_LNAME = isset($form->LNAME) ? filter_var($form->LNAME, FILTER_SANITIZE_STRING): null; 
     $USR_MNAME = isset($form->MNAME) ? filter_var($form->MNAME, FILTER_SANITIZE_STRING): null; 
     $USR_PNAME = isset($form->PNAME) ? filter_var($form->PNAME, FILTER_SANITIZE_STRING): null; 
     $USR_DBIRTH = isset($form->DBIRTH) ? $form->DBIRTH : null; 
     $USR_STREET = isset($form->STREET) ? filter_var($form->STREET, FILTER_SANITIZE_STRING): null; 
     $USR_STREETN = isset($form->STREETN) ? filter_var($form->STREETN, FILTER_SANITIZE_STRING): null; 
     $USR_BOX = isset($form->BOX) ? filter_var($form->BOX, FILTER_SANITIZE_STRING): null; 
     $USR_PCODE = isset($form->PCODE) ? filter_var($form->PCODE, FILTER_SANITIZE_STRING): null; 
     $USR_CITY = isset($form->CITY) ? filter_var($form->CITY, FILTER_SANITIZE_STRING): null; 
     $USR_STATE = isset($form->STATE) ? filter_var($form->STATE, FILTER_SANITIZE_STRING): null; 
     $USR_COUNTRY = isset($form->COUNTRY) ? filter_var($form->COUNTRY, FILTER_SANITIZE_STRING): null; 
     $USR_COUNTRY_CODE = isset($form->COUNTRY_CODE) ? filter_var($form->COUNTRY_CODE, FILTER_SANITIZE_NUMBER_INT): null; 
     $USR_ADDRESS = isset($form->ADDRESS) ? filter_var($form->ADDRESS, FILTER_SANITIZE_STRING): null; 
     $USR_SEX = isset($form->SEX) ? filter_var($form->SEX, FILTER_SANITIZE_NUMBER_INT): null; 
     $USR_EMAIL = isset($form->EMAIL) ? filter_var($form->EMAIL, FILTER_SANITIZE_STRING): null; 
     $USR_MPHONE = isset($form->MPHONE) ? filter_var($form->MPHONE, FILTER_SANITIZE_STRING): null; 
     $USR_MAST = isset($form->MAST) ? filter_var($form->MAST, FILTER_SANITIZE_NUMBER_INT): null; 
     $USR_CONST = isset($form->CONST) ? filter_var($form->CONST, FILTER_SANITIZE_NUMBER_INT): null; 
     $ID_ACCTYP = isset($form->ACCTYP) ? filter_var($form->ACCTYP, FILTER_SANITIZE_NUMBER_INT): null; 
     $USR_PROFILE = isset($form->PROFILE) ? filter_var($form->PROFILE, FILTER_SANITIZE_STRING): null; 
     $USR_PICTURE = isset($form->PICTURE) ? filter_var($form->PICTURE, FILTER_SANITIZE_STRING): null; 
     $USR_BACKGROUND = isset($form->BACKGROUND) ? filter_var($form->BACKGROUND, FILTER_SANITIZE_STRING): null; 
     $USR_WEBSITE = isset($form->WEBSITE) ? filter_var($form->WEBSITE, FILTER_SANITIZE_STRING): null; 
     $ID_ZONEID = isset($form->ZONEID) ? filter_var($form->ZONEID, FILTER_SANITIZE_NUMBER_INT): null; 
     $USR_LANG = isset($form->LANG) ? filter_var($form->LANG, FILTER_SANITIZE_STRING): null; 
     $USR_BACKGROUND_MIN = isset($form->BACKGROUND_MIN) ? filter_var($form->BACKGROUND_MIN, FILTER_SANITIZE_STRING): null; 
     $USR_ACTIVE = isset($form->ACTIVE) ? filter_var($form->ACTIVE, FILTER_SANITIZE_NUMBER_INT): null; 
     $USR_LOCKED = isset($form->LOCKED) ? filter_var($form->LOCKED, FILTER_SANITIZE_NUMBER_INT): null; 
     $USR_DATEINS = isset($form->DATEINS) ? $form->DATEINS : null; 
     $USR_DATEVAL = isset($form->DATEVAL) ? $form->DATEVAL : null; 
     $USR_DATEMOD = isset($form->DATEMOD) ? $form->DATEMOD : null; 
     $USR_OFFICIAL = isset($form->OFFICIAL) ? filter_var($form->OFFICIAL, FILTER_SANITIZE_NUMBER_INT): null;        
     $fdata = new \Moviao\Data\Rad\UsersData();   
     $fdata->set_USR($ID_USR);
     $fdata->set_UUID($USR_UUID);
     $fdata->set_SUBJECT($USR_SUBJECT);
     $fdata->set_ABOUT($USR_ABOUT);
     $fdata->set_NNAME($USR_NNAME);
     $fdata->set_NDISP($USR_NDISP);
     $fdata->set_FNAME($USR_FNAME);
     $fdata->set_LNAME($USR_LNAME);
     $fdata->set_MNAME($USR_MNAME);
     $fdata->set_PNAME($USR_PNAME);
     $fdata->set_DBIRTH($USR_DBIRTH);
     $fdata->set_STREET($USR_STREET);
     $fdata->set_STREETN($USR_STREETN);
     $fdata->set_BOX($USR_BOX);
     $fdata->set_PCODE($USR_PCODE);
     $fdata->set_CITY($USR_CITY);
     $fdata->set_STATE($USR_STATE);
     $fdata->set_COUNTRY($USR_COUNTRY);
     $fdata->set_COUNTRY_CODE($USR_COUNTRY_CODE);
     $fdata->set_ADDRESS($USR_ADDRESS);
     $fdata->set_SEX($USR_SEX);
     $fdata->set_EMAIL($USR_EMAIL);
     $fdata->set_MPHONE($USR_MPHONE);
     $fdata->set_MAST($USR_MAST);
     $fdata->set_CONST($USR_CONST);
     $fdata->set_ACCTYP($ID_ACCTYP);
     $fdata->set_PROFILE($USR_PROFILE);
     $fdata->set_PICTURE($USR_PICTURE);
     $fdata->set_BACKGROUND($USR_BACKGROUND);
     $fdata->set_WEBSITE($USR_WEBSITE);
     $fdata->set_ZONEID($ID_ZONEID);
     $fdata->set_LANG($USR_LANG);
     $fdata->set_BACKGROUND_MIN($USR_BACKGROUND_MIN);
     $fdata->set_ACTIVE($USR_ACTIVE);
     $fdata->set_LOCKED($USR_LOCKED);
     $fdata->set_DATEINS($USR_DATEINS);
     $fdata->set_DATEVAL($USR_DATEVAL);
     $fdata->set_DATEMOD($USR_DATEMOD);
     $fdata->set_OFFICIAL($USR_OFFICIAL); 
     return $fdata;
}
}