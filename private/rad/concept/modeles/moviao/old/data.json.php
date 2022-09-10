<?php
// @author Moviao Inc. 
// All rights reserved 2016-2017.
// Json /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ucfirst($data->fetchColumn());}*/

//<editor-fold defaultstate="collapsed" desc="/*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ucfirst($data->fetchColumn());}*/">
// Include Library
require('common.php');
function show(/*@{ 
    
    $res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
    $i = 0;
    $s = '';
    while ($enreg = $data->fetchObject($res)) { 	
        if ($i > 0) $s .= ", ";	
        $s .= '$' . $enreg->IDCHP;
        $i++;
    }
    //return $s;  
    
}*/$IDUSER) {
    global $data,$util,$return_data,$return_errors;  
    $strSql = "/*@{ 
        
        // Requete personnalise
        $res = $data->executeQuery("SELECT REQUETE FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT';");
            
        $res2 = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;");
        $tmp = $data->fetchColumn();

        $s = "Select ".chr(42)." From $tmp";
        if ($enreg = $data->fetchColumn()) { 
            if ((strlen($enreg->REQUETE) > 0) && ($enreg->REQUETE != "null")) {
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
	
	}*/;";
        
    $stmt = $this->data->prepare($strSql);   

    if (! $this->data->execute()) {       
        return $return_data;
    }         
      
    $i=0;
    while ($obj = $data->fetchObject($stmt)) { 
        if (is_null($obj)) {               
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
  
                $s .= '        $return_data[\'data\'][$i][\'' . $d .  '\'] =  preg_replace_callback(\'/&#([0-9a-fx]+);/mi\', \'replace_num_entity\',$obj->' . $enreg->IDCHP . ');';
                $i++;
            }
            return $s;
            }*/
        $i++;        
    }    
}
//</editor-fold>
?>