<?php
// @author Moviao Inc. 
// All rights reserved 2022-2023.
// CrudClass /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ucfirst($util->getFirstLine($res)[0]);}*/
namespace  Data\Rad\Crud;
/*@{ 
  //$res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); 
  //return ucfirst(explode("_",$util->getFirstLine($res)[0])[0]);    
  }*/
class /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ucfirst($util->getFirstLine($res)[0]);}*/Crud/*@{ //$res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ucfirst($util->getFirstLine($res)[0]);}*/ {  

    
function loadDataForm($request) {
/*@{ 
$res = $data->executeQuery("SELECT IDCHP,TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' ORDER BY NUMSEQ;");
$i = 0;
$s = '';
while ($enreg = $util->getLineObject($res)) { 
    if ($i > 0) $s .= "\r\n";
    // $EVT_DESC = isset($request->d->DESC) ? filter_var($request->d->DESC, FILTER_SANITIZE_STRING):"";
    $d = $enreg->IDCHP ?? ' ';
    $pos = strpos($d, "_");
    if ($pos !== false) {
        $d = explode("_", $d,2)[1]; 
    }

    if (isString($enreg->TYPEDONNEE) == 1) {        
        $s .= '     $'.$enreg->IDCHP.' = isset($request->'.$d.') ? cleanData($request->'.$d.'):""; ';
    }

    if (isInteger($enreg->TYPEDONNEE) == 1) {
        $filter="FILTER_SANITIZE_NUMBER_INT"; 
        $s .= '     $'.$enreg->IDCHP.' = isset($request->'.$d.') ? filter_var($request->'.$d.', '. $filter .'):""; ';
    }

    if (isFloat($enreg->TYPEDONNEE) == 1) {
        $filter="FILTER_SANITIZE_NUMBER_FLOAT";     
        $s .= '     $'.$enreg->IDCHP.' = isset($request->'.$d.') ? filter_var($request->'.$d.', '. $filter .'):""; ';
    }

    //if ($i > 0) $s .= ",";
    $i++;
}
    return $s;
}*/       
     $fdata = new /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ucfirst($util->getFirstLine($res)[0]);}*/Data();   
/*@{ 
$res = $data->executeQuery("SELECT IDCHP,TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' ORDER BY NUMSEQ;");
$i = 0;
$s = '';

while ($enreg = $util->getLineObject($res)) { 
    if ($i > 0) $s .= "\r\n";
    // Example : $fdata->set_TITLE($EVT_TITLE);
    $d = $enreg->IDCHP ?? ' ';
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

function cleanData($data)
{
    $data = htmlspecialchars($data);
    $data = \strip_tags($data);
    $data = stripslashes($data);
    $data = trim($data);
    return $data;
}


}
?>