<?php
class SqlGenerator {	
function __construct() {}
function getInsert($table_name, $champs) {
    // INSERT
    // insert into X (X,X,X) values (X,X,X)
    $coeur = "";
    $corps = "INSERT INTO $table_name (";
    /* First part */
    for ($i = 0; $i < count($champs); $i++) {
            $ligne = $champs[$i];

            if ($ligne[6] != 'auto_increment') {
                    $coeur .=  $ligne[0];
                    if ($i != count($champs) - 1) $coeur .= ', ';	
            }
    }
    $coeur .= ') VALUES (';
    $corps .= $coeur;	
    $coeur = "";
    /* Second Part */	
    for ($i = 0; $i < count($champs); $i++) {
            $ligne = $champs[$i];
            if ($ligne[6] != 'auto_increment') {
                    if ($this->isNumber($ligne[1]) == 0) $coeur .= "'";
                    $coeur .= '$' . $ligne[0];
                    if ($this->isNumber($ligne[1]) == 0) $coeur .= "'";

                    if ($i != count($champs) - 1) $coeur .= ", ";
            }
    }
    $resultat = $corps . $coeur . ");\n";
    return ($resultat);		
}
function getDelete($table_name, $champs) {
    // DELETE
    // delete from X where X = X and X = 'X'
    $corps = "DELETE FROM $table_name WHERE ";
    $coeur = "";
    $controle = 0;
    /* First part */
    for ($i = 0; $i < count($champs); $i++) {
            $ligne = $champs[$i];
            if ($ligne[4] == 'PRI') {				
                    if ($controle == 1) $coeur .= ' AND ';
                    $coeur .=  $ligne[0] . ' = ';

                    if ($this->isNumber($ligne[1]) == 0) $coeur .= "'";
                    $coeur .= '$' . $ligne[0];
                    if ($this->isNumber($ligne[1]) == 0) $coeur .= "'";		
                    $controle = 1;		
            }
    }			
    $resultat = $corps . $coeur . ";\n"; 	
    return ($resultat);
}
function getUpdate($table_name, $champs) {
    // UPDATE
    // UPDATE X SET X = X , X = 'X' WHERE X = X AND X = 'X'
    $corps = "UPDATE $table_name SET ";
    $coeur = "";
    $controle = 0;	
    /* First part */
    for ($i = 0; $i < count($champs); $i++) {
            $ligne = $champs[$i];

            if ($ligne[4] != 'PRI') {				
                    if ($controle == 1) $coeur .= ', ';
                    $coeur .=  $ligne[0] . ' = ';

                    if ($this->isNumber($ligne[1]) == 0) $coeur .= "'";
                    $coeur .= '$' . $ligne[0];
                    if ($this->isNumber($ligne[1]) == 0) $coeur .= "'";		
                    $controle = 1;		
            }
    }	
    $controle = 0;
    $coeur .= " WHERE ";
    /* Second part */
    for ($i = 0; $i < count($champs); $i++) {
            $ligne = $champs[$i];

            if ($ligne[4] == 'PRI') {				
                    if ($controle == 1) $coeur .= ' AND ';
                    $coeur .=  $ligne[0] . ' = ';

                    if ($this->isNumber($ligne[1]) == 0) $coeur .= "'";
                    $coeur .= '$' . $ligne[0];
                    if ($this->isNumber($ligne[1]) == 0) $coeur .= "'";		
                    $controle = 1;		
            }
    }	

    $resultat = $corps . $coeur . ";\n";
    return($resultat);
}
function isNumber($chaine) {	
    if (strpos($chaine,'int') > -1) return (1);
    if (strpos($chaine,'float') > -1) return (1);
    if (strpos($chaine,'double') > -1) return (1);
    if (strpos($chaine,'tinyint') > -1) return (1);
    if (strpos($chaine,'bigint') > -1) return (1);
    if (strpos($chaine,'smallint') > -1) return (1);
    if (strpos($chaine,'mediumint') > -1) return (1);
    if (strpos($chaine,'decimal') > -1) return (1);
    return(0);	
}
}
?>