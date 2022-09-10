<?php
namespace Moviao\Database;

class DBApplication extends DatabaseCore {

// Constructeur
function __construct() {}


function connecterDB() {
    //parent::connecterBase("localhost:3306","moviao","moviao","generator");
    $config['db'] = array(
      'host'      => 'moviao-db',
      'username'  => 'root',
      'password'  => 'Hyjbe10vv5*',
      'dbname'    => 'generator'
    );
    //"localhost","moviao","moviao","moviao"
    return parent::connect($config['db']);
}
//############################################################

// Requete Sp�ciale Bool�enne
function executerBRequete($requete) {
        $reponse = parent::executeQuery($requete);	

        if ($reponse) 
                return (true);
        else
                return (false);
}

// Test Commun
function testCommun($ressource) {	
        if ($ressource)	$nombre = mysqli_num_rows($ressource);
        else $nombre = 0;			
        if ($ressource)	mysqli_free_result($ressource); // Lib�ration des ressources		
        if ($nombre > 0) return (true);
        else return (false);
}
}
?>