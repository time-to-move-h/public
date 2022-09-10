<?php

/*
    ...........................................
    .  Class DBApplication extends DatabaseCore .
    ...........................................

    INTERFACE SPECIALISE	
*/

// Classe derive de DatabaseCore
class DBApplication extends DatabaseCore {
// Constructeur
function DBApplication() {}
// Connexion Preconfigure
//############################################################
function connecterDB() {
    $server = $_SERVER["SERVER_NAME"];
    //echo $server;
    if ($server <> "localhost" && $server <> "127.0.0.1" )
        return parent::connecterBase("localhost:3306","nightconcept","Hyjbe10vv5*","moviao");
    else	
        return parent::connecterBase("localhost:3306","root","root","moviao");
}
//############################################################

// Requete Sp�ciale Bool�enne
function executerBRequete($requete) {
	$reponse = parent::executeQuery($requete);		
	if ($reponse) return (true);
	else return (false);
}
// Test Commun
function testCommun($ressource) {
	if ($ressource)	$nombre = mysqli_num_rows($ressource);
	else $nombre = 0;		
	if ($ressource)	mysqli_free_result($ressource); // Lib�ration des ressources	
	if ($nombre > 0) return (true);
	else return (false);
}
function escapeString($str) {	
	if (! get_magic_quotes_gpc()) 
		return mysqli_real_escape_string($str, parent::getConnexion());
	else 
		return $str;
}
function clean_String($str, $max_length) {  
  $new_string = $str;
  $in_string = ltrim($str);       
  $in_string = rtrim($in_string);
  if (round($max_length) < 1) {  
    $max_length = 131072; // 128K
  }
  if (strlen($in_string) > $max_length) {
    $new_string = substr($in_string,0,$max_length);
  }
  return $new_string;
}
function quote_smart($value)
{
    if( is_array($value) ) {
        return array_map("quote_smart", $value);
    } else {
        if( get_magic_quotes_gpc() ) {
            $value = stripslashes($value);
        }
        if( $value == '' ) {
            $value = 'NULL';
        } if( !is_numeric($value) || $value[0] == '0' ) {
            $value = "'".mysqli_real_escape_string($value)."'";
        }
        return $value;
    }
}
}
?>