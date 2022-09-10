<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// Data API /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ucfirst($data->fetchColumn());}*/
namespace Moviao\Data\Rad;
use PDO;
/*@{ 
  //$res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); 
  //return ucfirst(explode("_",$data->fetchColumn())[0]);    
  }*/
class /*@{ 
 $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;");
 $table_name = $data->fetchColumn();
 $pos = strpos($table_name, "_");
 if ($pos !== false) {
    //$table_name = ucfirst(explode("_", $table_name,2)[0]) . ucfirst(explode("_", $table_name,2)[1]);
    $arr = explode('_', $table_name);
    $table_name = '';
    foreach ($arr as $val) {
        $table_name .= ucfirst($val);
    }
 } else {
    $table_name = ucfirst($table_name);
 } 
   
 return $table_name;  
 }*/ {  
private $data;

public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->data = $commonData->getDBConn();
}   

public function create(\Moviao\Data\Rad\/*@{
 $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;");
 $table_name = $data->fetchColumn();
 $pos = strpos($table_name, "_");
 if ($pos !== false) {
    //$table_name = ucfirst(explode("_", $table_name,2)[0]) . ucfirst(explode("_", $table_name,2)[1]);
    $arr = explode('_', $table_name);
    $table_name = '';
    foreach ($arr as $val) {
        $table_name .= ucfirst($val);
    }
 } else {
    $table_name = ucfirst($table_name);
 } 
   
 return $table_name;  
 }*/Data $fdata) : bool {    
    $result = false;    
    try {           
        $strSql = 'INSERT INTO /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return $data->fetchColumn();}*/ (/*@{		
		$res = $data->executeQuery("SELECT IDCHP,TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND AI=0 AND VISIBLE=1;");
		$i = 0;
		$s = '';
                $v = '';    
		while ($enreg = $data->fetchObject($res)) { 
                    if ($i > 0) $s .= ', ';	
                    $s .= $enreg->IDCHP;                    
                    if ($i > 0) $v .= ',';                    
                    if (strpos($enreg->TYPEDONNEE,'point') === false) {
                        $v .= '?';
                    } else {
                        $v .= 'ST_PointFromText(?)';
                    }
                    $i++;
		}
		$s .= ') VALUES (' . $v . ")';";		
                return $s;
                }*/
        $stmt = $this->data->prepare($strSql);
        
        if (null === $stmt) {
            return false;
        }                
                
/*@{
                $res = $data->executeQuery("SELECT IDCHP,TYPEDONNEE,NUL,DATA FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND AI=0 AND VISIBLE=1;");
		$s = '';
                $i = 0;		
		while ($enreg = $data->fetchObject($res)) { 		
                        $c = $enreg->IDCHP;
                        $d = $c;
                        $pos = strpos($d, "_");
                        if ($pos !== false) {
                            $d = explode("_", $d,2)[1];
                        }
                        
                        // Variable Data
                        //$s .= '	$row'.$i.' = null;' . "\r\n";

                        // Default
                        if (empty($enreg->DATA)) {
                           $default = 'null';
                            //if ($enreg->NUL == 1) $default = 'null';
                            //else if (isNumber($enreg->TYPEDONNEE) == 1)
                            //    $default = 0;
                            //else if (isDate($enreg->TYPEDONNEE) == 1)
                            //    $default = 'date(\'Y-m-d H:i:s\')';
                            //else $default = '\'\'';
                            //$s .= $default . ' :  ';

                            // Bind Param Type
                            if (isInteger($enreg->TYPEDONNEE) == 1) {
                                $s .= '	$row'.$i.' = ' . '((! is_numeric($fdata->get_' . $d . '())) ? ' . $default . ' :  ' . '(int) $fdata->get_' . $d . '()' . ");\r\n";
                            } else if (isFloat($enreg->TYPEDONNEE) == 1) {
                                $s .= '	$row'.$i.' = ' . '((! is_numeric($fdata->get_' . $d . '())) ? ' . $default . ' :  ' . '$fdata->get_' . $d . '()' . ");\r\n";
                            } else {
                                $s .= '	$row'.$i.' = $fdata->get_' . $d . '()' . ";\r\n";
                            }

                        } else {
                            //$s .= $enreg->DATA . ' :  ';
                            $s .= '	$row'.$i.' = ' . '((is_null($fdata->get_' . $d . '())) ? ' . $enreg->DATA . ' :  ' . '$fdata->get_' . $d . '()' . ");\r\n";
                        }
                        //$s .= ";\r\n";
 
			//if ($i > 0) $s .= ', ';					
			//if (isNumber($enreg->TYPEDONNEE) == 0) $s .= "\"'\" . ";
            //                        if (isInteger($enreg->TYPEDONNEE))
            //                            $s .=  '(int)($fdata->get_' . $d . '())';
            //                        else
            //                            $s .= '$fdata->get_' . $d . '()';
			//if (isNumber($enreg->TYPEDONNEE) == 0) $s .= " . \"'\"";												
			//$s .= ");\r\n";


                        // Bind Param Type
                        if (isInteger($enreg->TYPEDONNEE) == 1) {
                            $type = 'PDO::PARAM_INT';
                        } else if (isFloat($enreg->TYPEDONNEE) == 1) {
                            $type = 'PDO::PARAM_STR';
                        } else {
                            $type = 'PDO::PARAM_STR';
                        }
                        
                        // Bind Param
                        $s .= '	if (! $this->data->bindParam('.($i + 1).',$row' . $i . ', ' . $type . ')) {' . "\r\n";    
                        $s .= '       return false;' . "\r\n";
                        $s .= '    }' . "\r\n";
 
			$i++;
		}
		
		return $s;		
		}*/	
                
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
    
    $strSql = '/*@{ 
        
        // Requete personnalise                    
        $res2 = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;");
        $table_name = $data->fetchColumn();
        
        $res = $data->executeQuery("SELECT REQUETE FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT';");
        $s = "SELECT ".chr(42)." FROM $table_name";

        if ($enreg = $data->fetchColumn()) { 
            if ((! empty($enreg->REQUETE)) && ($enreg->REQUETE != "null")) {
                $s = $enreg->REQUETE;
                return $s;    
            }		
        }
        
        //$s .= " WHERE ";
	//$res = $data->executeQuery("SELECT IDCHP, TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
	//$i = 0;	
	//while ($enreg = $data->fetchObject($res)) { 
	//	if ($i > 0) $s .= " AND ";
	//	$s .= $enreg->IDCHP . '=';				
	//	if (isNumber($enreg->TYPEDONNEE) == 0) $s .= "'";
	//	$s .= '$' . $enreg->IDCHP;
	//	if (isNumber($enreg->TYPEDONNEE) == 0) $s .= "'";				
	//	$i++;
	//}
	return $s;	
	
	}*/';
                
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
/*@{	
            $res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1;");
            $i = 0;
            $s = '';
            while ($enreg = $data->fetchObject($res)) { 
                if ($i > 0) $s .= "\r\n";
                
                $d = $enreg->IDCHP;
                $pos = strpos($d, "_");
                if ($pos !== false) {
                    $d = explode("_", $d,2)[1];
                }
  
                $s .= '        $row[\'data\'][\'' . $d .  '\'] =  $obj->' . $enreg->IDCHP . ';';
                $i++;
            }
            return $s;
            }*/
        $return_data[]  = $row['data'];
        $i++;        
    } 
    } catch (\Error $e) {
        error_log('RAD Show >> : ' . $e);
    }    
    
    return $return_data;
}

public function filterForm(\stdClass $form) : \Moviao\Data\Rad\/*@{
 $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;");
 $table_name = $data->fetchColumn();
 $pos = strpos($table_name, "_");
 if ($pos !== false) {
    //$table_name = ucfirst(explode("_", $table_name,2)[0]) . ucfirst(explode("_", $table_name,2)[1]);
     $arr = explode('_', $table_name);
    $table_name = '';
    foreach ($arr as $val) {
        $table_name .= ucfirst($val);
    }
 } else {
    $table_name = ucfirst($table_name);
 }

 return $table_name;
 }*/Data {
/*@{ 
$res = $data->executeQuery("SELECT IDCHP,TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' ORDER BY NUMSEQ;");
$i = 0;
$s = '';
while ($enreg = $data->fetchObject($res)) { 
    if ($i > 0) $s .= "\r\n";
    // $EVT_DESC = isset($form->d->DESC) ? filter_var($form->d->DESC, FILTER_SANITIZE_STRING):"";
    $d = $enreg->IDCHP;
    $pos = strpos($d, "_");
    
    if ($pos !== false) {
        $d = explode("_", $d,2)[1];
    }

    if (isInteger($enreg->TYPEDONNEE) == 1) {
        $s .= '     $'.$enreg->IDCHP.' = isset($form->'.$d.') ? filter_var($form->'.$d.', FILTER_SANITIZE_NUMBER_INT): null; ';
    } else if (isFloat($enreg->TYPEDONNEE) == 1) {
        $s .= '     $'.$enreg->IDCHP.' = isset($form->'.$d.') ? filter_var($form->'.$d.', FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION): null; ';
    } else if (isString($enreg->TYPEDONNEE) == 1) {
        $s .= '     $'.$enreg->IDCHP.' = isset($form->'.$d.') ? $this->cleanData($form->'. $d .') : null; ';
    } else {
        $s .= '     $'.$enreg->IDCHP.' = isset($form->'.$d.') ? $form->'.$d.' : null; ';
    }

    //if ($i > 0) $s .= ",";
    $i++;
}
    return $s;
}*/       
     $fdata = new \Moviao\Data\Rad\/*@{ 
 $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;");
 $table_name = $data->fetchColumn();
 $pos = strpos($table_name, "_");
 if ($pos !== false) {
    //$table_name = ucfirst(explode("_", $table_name,2)[0]) . ucfirst(explode("_", $table_name,2)[1]);
    $arr = explode('_', $table_name);
    $table_name = '';
    foreach ($arr as $val) {
        $table_name .= ucfirst($val);
    }
 } else {
    $table_name = ucfirst($table_name);
 } 
   
 return $table_name;  
 }*/Data();   
/*@{ 
$res = $data->executeQuery("SELECT IDCHP,TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' ORDER BY NUMSEQ;");
$i = 0;
$s = '';
while ($enreg = $data->fetchObject($res)) { 
    if ($i > 0) $s .= "\r\n";
    // Example : $fdata->set_TITLE($EVT_TITLE);
    $d = $enreg->IDCHP;
    $pos = strpos($d, "_");
    if ($pos !== false) {
        $d = explode("_", $d,2)[1];
    }        
    $s .= '     $fdata->set_'.$d.'($'.$enreg->IDCHP.');';
    //if ($i > 0) $s .= ",";
    $i++;
}
    return $s;
}*/ 
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