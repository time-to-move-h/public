<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : /*@{ return ucfirst($TABLE_NAME);}*/

// Inclusion de bibliothèque
require('../../CLASS/IO/DatabaseCoreClass.php');
require('../../CLASS/DBApplicationClass.php');
require('../../CLASS/IO/DataUtilsClass.php');

// Instanciation
$data = new DBApplication();
$util = new DataUtils();

// Connexion Mysql
$data->connecterDB();

// Initialisation des Champs			

/*@{

$resultat = '';
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];
 		$resultat .= '$' . $reponse[0] . " = \"\";\r\n";
	}			
}

return $resultat;
}*/					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function ajouter/*@{ return ucfirst($TABLE_NAME);}*/(/*@{

$resultat = '';
$controle = 0;
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];
		if ($reponse[6] != 'auto_increment') {
			if ($controle > 0) $resultat .= ', ';
			$resultat .= '$' . $reponse[0];
			$controle = 1;
		}
	}			
}

return $resultat;
}*/) {
	global $data;
	return $data->executeQuery("/*@{ return getSqlInsert();}*/");
}

// Modification
function modifier/*@{ return ucfirst($TABLE_NAME);}*/(/*@{
$resultat = '';
$controle = 0;
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];		
		if ($controle > 0) $resultat .= ', ';
		$resultat .= '$' . $reponse[0];
		$controle = 1;
	}			
}
return $resultat;
}*/) {
	global $data;
	return $data->executeQuery("/*@{ return getSQLUpdate();}*/");
}

// Test d\'existence
function test/*@{ return ucfirst($TABLE_NAME);}*/(/*@{ return getPrimaryKeysVariable();}*/) {
	global $data;
	$ressource = $data->executeQuery("SELECT 1 FROM /*@{ return $TABLE_NAME;}*/ WHERE  /*@{ return getPrimaryKeysSQL();}*/ ;");
	return $data->testCommun($ressource);
}

// Récupération enregistrement
function getSingleRow/*@{ return ucfirst($TABLE_NAME);}*/(/*@{ return getPrimaryKeysVariable();}*/) {
	global $data;
	return $data->executeQuery("SELECT * FROM /*@{ return $TABLE_NAME;}*/ WHERE /*@{ return getPrimaryKeysSQL();}*/;");
}









// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

/*@{
$resultat = '';
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];
 		$resultat .= '$' . $reponse[0] . ' = $_POST[\'E' . $reponse[0] . "'];\r\n";
	}			
}

return $resultat;
}*/			
$mode = $_POST["HMode"];

switch ($mode) {

	case 0 :	
if (! test/*@{ return ucfirst($TABLE_NAME);}*/(/*@{

$resultat = '';
$controle = 0;
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];
		if ($reponse[4] == 'PRI') {
			if ($controle > 0) $resultat .= ', ';
			$resultat .= '$' . $reponse[0];
			$controle = 1;
		}
	}			
}

return $resultat;
}*/)) {

					
if (ajouter/*@{ return ucfirst($TABLE_NAME);}*/(/*@{

$resultat = '';
$controle = 0;
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];
		if ($reponse[6] != 'auto_increment') {
			if ($controle > 0) $resultat .= ', ';
			$resultat .= '$' . $reponse[0];
			$controle = 1;
		}
	}			
}

return $resultat;
}*/)) {
$message = "Insertion OK";	
	} 
}
	break;
	
	case 1 :			

	
if (test/*@{ return ucfirst($TABLE_NAME);}*/(/*@{

$resultat = '';
$controle = 0;
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];
		if ($reponse[4] == 'PRI') {
			if ($controle > 0) $resultat .= ', ';
			$resultat .= '$' . $reponse[0];
			$controle = 1;
		}
	}			
}

return $resultat;
}*/)) {

		
if (modifier/*@{ return ucfirst($TABLE_NAME);}*/(/*@{

$resultat = '';
$controle = 0;
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];		
		if ($controle > 0) $resultat .= ', ';
		$resultat .= '$' . $reponse[0];
		$controle = 1;
	}			
}

return $resultat;
}*/)) {				
$message = "Modification OK";	
		} 
	}
}

/*@{

$resultat = '';
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];
 		$resultat .= '$' . $reponse[0] . " = \"\";\r\n";
	}			
}

return $resultat;
}*/	
				
}
					
// ----------------------------------- LECTURE
if (/*@{		
		
$controle = 0;
$resultat = '';
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];
		if ($reponse[4] == 'PRI') {
			if ($controle > 0) $resultat .= ' && ';
			$resultat .= 'isset($_GET["E' . $reponse[0] . '"])';
			$controle = 1;
		}
	}			
	$resultat .= "";
	
	return $resultat;
}}*/) {

/*@{

$resultat = '';	
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];
		if ($reponse[4] == 'PRI') {								
			$resultat .= '	$' . $reponse[0] . '= $_GET["E' . $reponse[0] . '"]; ' . "\r\n";								
		}
	}					
}

return $resultat;
}*/

				
if (test/*@{ return ucfirst($TABLE_NAME);}*/(/*@{

$resultat = '';
$controle = 0;
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];
		if ($reponse[4] == 'PRI') {
			if ($controle > 0) $resultat .= ', ';
			$resultat .= '$' . $reponse[0];
			$controle = 1;
		}
	}			
}

return $resultat;
}*/)) {			
				
$result = getSingleRow/*@{ return ucfirst($TABLE_NAME);}*/(/*@{

$resultat = '';
$controle = 0;
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];
		if ($reponse[4] == 'PRI') {
			if ($controle > 0) $resultat .= ', ';
			$resultat .= '$' . $reponse[0];
			$controle = 1;
		}
	}			
}

return $resultat;
}*/);
$ligne = $util->getFirstLine($result);	// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
/*@{
	
$resultat = '';
if (count($CHAMPS) > 0) {
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];
		$resultat .= '			$' . $reponse[0] . ' = $ligne[' . $i . '];' . "\n";
	}			
}	
return $resultat;
}*/			

}			
$mode = 1;

}	
	
}

// Deconnexion
$data->deconnecter();
?>




<form action="<?php echo $page; ?>" method="post" name="F/*@{ return ucfirst($TABLE_NAME);}*/" id="F/*@{ return ucfirst($TABLE_NAME);}*/">

/*@{

$resultat = '';

if (count($CHAMPS) > 0) {
	$resultat .= '<table width="500" border="1" align="center">' . "\n";
	$resultat .= '  
	<tr>
	  <td height="12" colspan="2"><div align="center">' . ucfirst($TABLE_NAME) . '</div></td>
	  </tr>
	';
	for ($i=0;$i < count($CHAMPS);$i++) {
		$reponse = $CHAMPS[$i];							
		$resultat .= '<tr>' . "\n" . '<td>';
		$resultat .= $reponse[0];
		$resultat .= '</td>' . "\n" . '<td>';
		$resultat .= '<input type="text" name="E' . $reponse[0] . '" value="<?php echo $' . $reponse[0] . '; ?>">';
		$resultat .= '</td>' . "\n" . '</tr>' . "\n";									
	}			
	$resultat .= "\n";						
}	

return $resultat;
					
}*/
						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?></td>
    </tr>
	</table>
</form>
