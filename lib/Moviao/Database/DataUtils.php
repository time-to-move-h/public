<?php
declare(strict_types=1);

namespace Moviao\Database;
class DataUtils {	
function __construct() {}			
// First Line (Extracteur Premiere Cellule)
function getFirstCell($MRessource) {
    if ($MRessource) {
        if (mysqli_num_rows($MRessource) > 0) {
            $tableau = mysqli_fetch_array($MRessource);
            mysqli_free_result($MRessource);
            return($tableau[0]);
        }
    } else
            return ('');					
}		
// First Line (Extracteur Premiere ligne)
function getFirstLine($MRessource) {
    if ($MRessource) {
        if (mysqli_num_rows($MRessource) > 0) {
            $tableau = mysqli_fetch_array($MRessource); 
            mysqli_free_result($MRessource);
            return($tableau);
        }
    } else
            return (0);					
}				
// First Line Object (Extracteur Premiere ligne)
function getFirstLineObject($MRessource) {
    if ($MRessource) {
        if (mysqli_num_rows($MRessource) > 0) {
            $objet = mysqli_fetch_object($MRessource); 
            mysqli_free_result($MRessource); 
            return($objet);
        }
    } else
        return (0);					
}			
function getLine($MRessource) {
    if ($MRessource) {
        if (mysqli_num_rows($MRessource) > 0) {
            $tableau = mysqli_fetch_array($MRessource);
            return($tableau);
        }
    } else
        return (0);
}
function getLineObject($MRessource) {
    if ($MRessource) {
        if (mysqli_num_rows($MRessource) > 0) {
            $objet = mysqli_fetch_object($MRessource); 
            return($objet);
        }
    } else
        return (0);
}						
function getNumRows($MRessource) {
    if ($MRessource) {
        return (mysqli_num_rows($MRessource));
    } else {
        return (0);
    }	
}			
function RowsAffected($link) {
    return mysqli_affected_rows($link);
}			
function row_exists($MRessource) {
    if ($MRessource) $nb = mysqli_num_rows($MRessource);
    else $nb = 0;		
    if ($MRessource) mysqli_free_result($MRessource); 
    if ($nb > 0) return (true);
    else return (false);
}
function getLastInsertID($MRessource) {
    return (mysqli_insert_id($MRessource));
}					
function getError($MRessource) {		
    if ($MRessource) {
        return (mysqli_errno($MRessource));
    } else {
        return (0);
    }	
}				
function escapeString($str, $MRessource) {
    return mysqli_real_escape_string($str, $MRessource);
}}?>