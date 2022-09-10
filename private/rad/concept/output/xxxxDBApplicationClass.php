<?php

/*
	...........................................
	.  Class DBApplication extends DatabaseCore .
	...........................................
	
	INTERFACE SPECIALISE	
*/

// Classe dérivé de DatabaseCore
class DBApplication extends DatabaseCore {

// Constructeur
function DBApplication() {}

// Connexion Préconfiguré
//############################################################
function connecterDB() {
	parent::connecterBase("localhost:3306","root","","GENERATEUR");
	// TODO : A changer
}
//############################################################

	// Requete Spéciale Booléenne
	function executerBRequete($requete) {
		$reponse = parent::executeQuery($requete);	
		
		if ($reponse) 
			return (true);
		else
			return (false);
	}

	// Test Commun
	function testCommun($ressource) {
	
		if ($ressource)	
			$nombre = mysql_num_rows($ressource);
		else
			$nombre = 0;
			
		if ($ressource)
			mysql_free_result($ressource); // Libération des ressources
		
		if ($nombre > 0) 
			return (true);
		else
			return (false);
	}


}

?>