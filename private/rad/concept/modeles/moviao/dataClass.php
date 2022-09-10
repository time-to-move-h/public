<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// DataClass /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ucfirst($data->fetchColumn());}*/
namespace Moviao\Data\Rad;
/*@{ 
  //$res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); 
  //return ucfirst(explode("_",$util->getFirstLine($res)[0])[0]);    
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
 }*/Data {     
/*@{ 
$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' ORDER BY NUMSEQ;");
$i = 0;
$s = '';
while ($enreg = $data->fetchObject($res)) { 
    if ($i > 0) $s .= "\r\n";
    $s .= 'private $' . $enreg->IDCHP . " = null;";
    //if ($i > 0) $s .= ",";
    $i++;
}
    return $s;
}*/   

public function __construct() {}
// Getters 
/*@{ 
$res = $data->executeQuery("SELECT IDCHP,TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' ORDER BY NUMSEQ;");
$i = 0;
$s = '';
while ($enreg = $data->fetchObject($res)) { 
    //if ($i > 0) $s .= ",";
    $c = $enreg->IDCHP;
    $d = $c;
    $pos = strpos($d, "_");
    if ($pos !== false) {
        $d = explode("_", $d,2)[1];
    }  
    $s .= "public function get_$d() {return \$this->$c;}\r\n";
    $i++;
}
    return $s;
}*/   

/*@{ 
$res = $data->executeQuery("SELECT IDCHP,TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' ORDER BY NUMSEQ;");
$i = 0;
$s = '';
while ($enreg = $data->fetchObject($res)) { 
    //if ($i > 0) $s .= ",";
    $dataType = '';
    $c = $enreg->IDCHP;
    $t = $enreg->TYPEDONNEE;
    //$data_type = convertToPhp($t); 
    //if (strlen($data_type) > 0) $data_type = $data_type . ' ';
    $d = $c;
    $pos = strpos($d, "_");
    if ($pos !== false) {
        $d = explode("_", $d,2)[1];
    }

    if (isInteger($enreg->TYPEDONNEE) == 1) {
        //$dataType = '?int ';
    } else if (isFloat($enreg->TYPEDONNEE) == 1) {
        //$dataType = '?float ';
    } else if (isString($enreg->TYPEDONNEE) == 1) {
        $dataType = '?string ';
    } else {
        //$dataType = '?string ';
    }

    $s .= "public function set_$d($dataType\$$c) {\$this->$c=\$$c;}\r\n";
    $i++;
}
    return $s;
}*/}