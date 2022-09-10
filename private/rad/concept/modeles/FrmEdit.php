<?php

// @author Moviao Inc
// Tous Droits réservés 2015.
// Fiche Modifier 
// Table : /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return $util->getFirstLine($res)[0];}*/

// Inclusion de bibliothèque
require('../../class/IO/DatabaseCoreClass.php');
require('../../class/IO/DataUtilsClass.php');
require('DBApplicationClass.php');
// Instanciation
$data = new DBApplication();
$util = new DataUtils();
// Connexion Mysql
$data->connecterDB();
// Initialisation des Champs			

/*@{	
 
$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1;");
$i = 0;
$s = '';
while ($enreg = $util->getLineObject($res)) { 
	//if ($i > 0) $s .= ",";
	$s .= '$' . $enreg->IDCHP . " = '';\r\n";
	$s .= '$' . $enreg->IDCHP . "_ori = '';\r\n";	
	$i++;
}
return $s;
 
}*/					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function add(/*@{

$res = $data->executeQuery("SELECT IDCHP,AI FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND AI=0 AND VISIBLE=1 ORDER BY NUMSEQ;");
$i = 0;
$s = '';
while ($enreg = $util->getLineObject($res)) { 
	if ($i > 0) $s .= ",";
	$s .= '$' . $enreg->IDCHP;
	$i++;	
}
return $s;

}*/) {
	global $data,$util;			
/*@{
	//000111222
	$res = $data->executeQuery("SELECT IDCHP, TYPEDONNEE,NUL,AI FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND AI=0 AND VISIBLE=1 ORDER BY NUMSEQ");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 			
		if ($enreg->NUL == 1) 
			$s .= '	if (strlen($' . $enreg->IDCHP . ') <= 0)  $' . $enreg->IDCHP . ' = \'null\';' . "\r\n";			
		if ($enreg->TYPEDONNEE == 'int(11)' or $enreg->TYPEDONNEE == 'double') 
			$s .= '	if (strlen($' . $enreg->IDCHP . ') <= 0)  $' . $enreg->IDCHP . ' = 0;' . "\r\n";	
		$i++;
	}
	//return $s;

	}*/		
/*@{
	//000111222
	$res = $data->executeQuery("SELECT IDCHP, TYPEDONNEE,NUL,AI FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND AI=0 AND VISIBLE=1 ORDER BY NUMSEQ");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 
		if (! ($enreg->TYPEDONNEE == 'int(11)' or $enreg->TYPEDONNEE == 'double' or $enreg->TYPEDONNEE == 'tinyint(1)')) 
		$s .= '	$' . $enreg->IDCHP . ' = addslashes($' . $enreg->IDCHP . ');' . "\r\n";	
		$i++;
	}
	return $s;

	}*/			
	$strSql = "INSERT INTO /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return $util->getFirstLine($res)[0];}*/ (/*@{
		
		$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND AI=0 AND VISIBLE=1;");
		$i = 0;
		$s = '';
		while ($enreg = $util->getLineObject($res)) { 
			if ($i > 0) $s .= ', ';	
			$s .= $enreg->IDCHP;
			$i++;
		}
		$s .= ') VALUES (' . "\";\r\n";		
		
		$res = $data->executeQuery("SELECT IDCHP, TYPEDONNEE,NUL FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND AI=0 AND VISIBLE=1;");
		$i = 0;		
		while ($enreg = $util->getLineObject($res)) { 
			
			$s .= '	$strSql .= ' . (($i > 0) ? '", " . ' : '') . '((strlen($' . $enreg->IDCHP . ') <= 0) ? "' . (($enreg->NUL == 1) ? 'NULL' : ((isNumber($enreg->TYPEDONNEE) == 0) ? '\'\'' : '0')) . '" :  ';
			//if ($i > 0) $s .= ', ';						
			
			if (isNumber($enreg->TYPEDONNEE) == 0) $s .= "\"'";
			$s .= '$' . $enreg->IDCHP;
			if (isNumber($enreg->TYPEDONNEE) == 0) $s .= "'\"";									
			
			$s .= ");\r\n";
			$i++;
		}
		$s .= '	$strSql .= ")";';
		
		return $s;		
		}*/		
	$data->executeQuery($strSql);
        return ($util->RowsAffected($data->getConnexion()) <= 0) ? FALSE : TRUE;
}

// Modification
function modify(/*@{

$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1;");
$i = 0;
$s = '';
while ($enreg = $util->getLineObject($res)) { 
	if ($i > 0) $s .= ", ";
	$s .= '$' . $enreg->IDCHP;
	$i++;
}
return $s;

}*/) {
	global $data, $util, /*@{

$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1;");
$i = 0;
$s = '';
while ($enreg = $util->getLineObject($res)) { 
	if ($i > 0) $s .= ", ";
	$s .= '$' . $enreg->IDCHP . '_ori';
	$i++;
}
return $s;

}*/;
	$strSql = 'UPDATE /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return $util->getFirstLine($res)[0];}*/ SET ';
	$strSql_Req = '';		

	if (test(/*@{
	
		$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
		$i = 0;
		$s = '';
		while ($enreg = $util->getLineObject($res)) { 	
			if ($i > 0) $s .= ", ";	
			$s .= '$' . $enreg->IDCHP . '_ori';
			$i++;
		}
		return $s;
	
	}*/)) {			
				
	$result = getSingleRow(/*@{

	$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 	
		if ($i > 0) $s .= ", ";	
		$s .= '	$' . $enreg->IDCHP . '_ori';
		$i++;
	}
	return $s;

	}*/);
	$ligne = $util->getFirstLineObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {
/*@{	
        $res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 	
		$s .= '		$' . $enreg->IDCHP . '_ori = $ligne->' . $enreg->IDCHP . ";\r\n";
		$i++;
	}
	return $s;
	}*/	
	}}
	
/*@{
	//000111222
	$res = $data->executeQuery("SELECT IDCHP, TYPEDONNEE,NUL,AI FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND AI=0 AND VISIBLE=1 ORDER BY NUMSEQ");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 
		if (! ($enreg->TYPEDONNEE == 'int(11)' or $enreg->TYPEDONNEE == 'double' or $enreg->TYPEDONNEE == 'tinyint(1)')) 
		$s .= '	$' . $enreg->IDCHP . ' = addslashes($' . $enreg->IDCHP . ');' . "\r\n";	
		$i++;
	}
	return $s;

	}*/	
		
/*@{ 	
		// Corps de la requete
		$res = $data->executeQuery("SELECT IDCHP, TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1;");
		$i = 0;
		$s = '';	
	
		// UPDATE
		// update table SET X = 'X' where X = X , X = 'X'		
		while ($enreg = $util->getLineObject($res)) {								
			$s .= '	if ($' . $enreg->IDCHP . ' != addslashes($' . $enreg->IDCHP; 
			$s .= '_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "';
			//if ($i > 0) $s .= ' , ';			
			$s .= $enreg->IDCHP . ' = \'$' . $enreg->IDCHP. '\'";' . "\r\n";						
			$i++;
		}
		return $s;	
	
	}*/	
	$strSql .= $strSql_Req . " WHERE /*@{ 
		// xxxyyy
		$res = $data->executeQuery("SELECT IDCHP, TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
		$i = 0;
		$s = '';
		while ($enreg = $util->getLineObject($res)) { 
			if ($i > 0) $s .= ' AND ';	
			$s .= $enreg->IDCHP . ' = ';
			if (isNumber($enreg->TYPEDONNEE) == 0) $s .= "'";
			$s .= '$' . $enreg->IDCHP . '_ori';
			if (isNumber($enreg->TYPEDONNEE) == 0) $s .= "'";			
			$i++;
		}
		return $s;
	
	}*/";	
	//echo $strSql;
	$data->executeQuery($strSql);
        return ($util->RowsAffected($data->getConnexion()) <= 0) ? FALSE : TRUE;
}

// Test d'existence
function test(/*@{ 

$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
$i = 0;
$s = '';
while ($enreg = $util->getLineObject($res)) { 
	if ($i > 0) $s .= ", ";
	$s .= '$' . $enreg->IDCHP;
	$i++;
}
return $s;

}*/) {
	global $data;
	$strSql = "SELECT 1 FROM /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return $util->getFirstLine($res)[0];}*/ WHERE  /*@{ 
	
		$res = $data->executeQuery("SELECT IDCHP,TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
		$i = 0;
		$s = '';
		while ($enreg = $util->getLineObject($res)) { 
			if ($i > 0) $s .= " AND ";			
			$s .= $enreg->IDCHP . ' = ';				
			if (isNumber($enreg->TYPEDONNEE) == 0) $s .= "'";
			$s .= '$' . $enreg->IDCHP;
			if (isNumber($enreg->TYPEDONNEE) == 0) $s .= "'";	
			$i++;
		}
		return $s;
	
	}*/ ;";
	$res = $data->executeQuery($strSql);
	return $data->testCommun($res);
}

// Récupération enregistrement
function getSingleRow(/*@{ 

	$res = $data->executeQuery("SELECT IDCHP,TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 
		if ($i > 0) $s .= ", ";
		$s .= '$' . $enreg->IDCHP;		
		$i++;
	}
	return $s;


}*/) {
	global $data;
	$strSql = "SELECT * FROM /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return $util->getFirstLine($res)[0];}*/ WHERE /*@{ 	
	$res = $data->executeQuery("SELECT IDCHP, TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 
		if ($i > 0) $s .= " AND ";
		$s .= $enreg->IDCHP . ' = ';				
		if (isNumber($enreg->TYPEDONNEE) == 0) $s .= "'";
		$s .= '$' . $enreg->IDCHP;
		if (isNumber($enreg->TYPEDONNEE) == 0) $s .= "'";				
		$i++;
	}
	return $s;	
	
	}*/;";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

/*@{
	//000111222
	$res = $data->executeQuery("SELECT IDCHP, TYPEDONNEE,NUL FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 
	
		if ($enreg->TYPEDONNEE == 'tinyint(1)')
			$s.= '$' . $enreg->IDCHP . ' = trim(isset($_POST[\'E' . $enreg->IDCHP . "']) ? 1 : 0);\r\n";
		else
			$s .= '$' . $enreg->IDCHP . ' = trim(isset($_POST[\'E' . $enreg->IDCHP . '\']) ? $_POST[\'E' . $enreg->IDCHP . "'] : '');\r\n";
			
//		if ($enreg->NUL == 1) 
//			$s .= 'if (strlen($' . $enreg->IDCHP . ') <= 0)  $' . $enreg->IDCHP . ' = \'null\';' . "\r\n";
//			
//		if ($enreg->TYPEDONNEE == 'int(11)' or $enreg->TYPEDONNEE == 'double') 
//			$s .= 'if (strlen($' . $enreg->IDCHP . ') <= 0)  $' . $enreg->IDCHP . ' = 0;' . "\r\n";
	
		$i++;
	}
	return $s;

}*/		

/*@{
	//recuperation champs d'origine
	$res = $data->executeQuery("SELECT IDCHP,TYPEDONNEE,NUL FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1 AND PRIMAIRE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 	
		if ($enreg->TYPEDONNEE == 'tinyint(1)')
			$s.= '$' . $enreg->IDCHP . '_ori = trim(isset($_POST[\'E' . $enreg->IDCHP . "_ori']) ? 1 : 0);\r\n";
		else
			$s .= '$' . $enreg->IDCHP . '_ori = trim(isset($_POST[\'E' . $enreg->IDCHP . '_ori\']) ? $_POST[\'E' . $enreg->IDCHP . "_ori'] : '');\r\n";
		$i++;
	}
	return $s;

}*/	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! test(/*@{

	$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 
		if ($i > 0) $s .= ", ";
		$s .= '$' . $enreg->IDCHP;
		$i++;
	}
	return $s;
	
}*/)) {					
    if (add(/*@{

$res = $data->executeQuery("SELECT IDCHP,AI FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND AI=0 AND VISIBLE=1 ORDER BY NUMSEQ;");
$i = 0;
$s = '';
while ($enreg = $util->getLineObject($res)) { 
	if ($enreg->AI != 1) {
		if ($i > 0) $s .= ",";
		$s .= '$' . $enreg->IDCHP;
		$i++;
	}
}
return $s;

}*/)) {
        $message = "Insertion OK";	
    } 
}
	break;
	
	case 1 :			

	
if (test(/*@{

	$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 
		if ($i > 0) $s .= ", ";
		$s .= '$' . $enreg->IDCHP;
		$i++;
	}
	return $s;
}*/)) {		
    if (modify(/*@{
	//999
	$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 
		if ($i > 0) $s .= ", ";
		$s .= '$' . $enreg->IDCHP;
		$i++;
	}
	return $s;

}*/)) {				
            $message = "Modification OK";	
        } 
}
}

/*@{
	
	$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 
		//$s .= '$' . $enreg->IDCHP . " = \"\";\r\n";		
		$i++;
	}
	return $s;

}*/					
}
					
// ----------------------------------- LECTURE
if (/*@{		
		
	$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 
		if ($i > 0) $s .= " && ";
		$s .= 'isset($_GET["E' . $enreg->IDCHP . '"])';
		$i++;
	}
	return $s;		

}*/) {

/*@{
	$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 		
		$s .= '$' . $enreg->IDCHP . '= $_GET["E' . $enreg->IDCHP . '"]; ' . "\r\n";
		$i++;
	}
	return $s;
}*/				
if (test(/*@{

	$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 	
		if ($i > 0) $s .= ", ";	
		$s .= '$' . $enreg->IDCHP;
		$i++;
	}
	return $s;

}*/)) {			
				
$result = getSingleRow(/*@{

	$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 	
		if ($i > 0) $s .= ", ";	
		$s .= '$' . $enreg->IDCHP;
		$i++;
	}
	return $s;

}*/);
$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
/*@{	
	$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 	
		$s .= '  $' . $enreg->IDCHP . ' = ' . '$' . $enreg->IDCHP . '_ori = $ligne->' . $enreg->IDCHP . ";\r\n";
		$i++;
	}
	return $s;
}*/	
}			
$mode = 1;
}}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Edit /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return $util->getFirstLine($res)[0];}*/</title>
	<link rel="stylesheet" href="styles.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="print.css" media="print" />
	<style type="text/css" media="screen">
		h1.fontface {font: 32px/38px 'MichromaRegular', Arial, sans-serif;letter-spacing: 0;}
		p.style1 {font: 18px/27px 'MichromaRegular', Arial, sans-serif;}		
		@font-face {
		font-family: 'MichromaRegular';
		src: url('Michroma-webfont.eot');
		src: url('Michroma-webfont.eot?#iefix') format('embedded-opentype'),
			 url('Michroma-webfont.woff') format('woff'),
			 url('Michroma-webfont.ttf') format('truetype'),
			 url('Michroma-webfont.svg#MichromaRegular') format('svg');
		font-weight: normal;
		font-style: normal;
		}		
		.CSSTableGenerator {
		margin:0px;padding:0px;
		width:100%;
		box-shadow: 10px 10px 5px #888888;		
		
		-moz-border-radius-bottomleft:0px;
		-webkit-border-bottom-left-radius:0px;
		border-bottom-left-radius:0px;
		
		-moz-border-radius-bottomright:0px;
		-webkit-border-bottom-right-radius:0px;
		border-bottom-right-radius:0px;
		
		-moz-border-radius-topright:0px;
		-webkit-border-top-right-radius:0px;
		border-top-right-radius:0px;
		
		-moz-border-radius-topleft:0px;
		-webkit-border-top-left-radius:0px;
		border-top-left-radius:0px;
		}.CSSTableGenerator table{
			border-collapse: collapse;
				border-spacing: 0;
			width:100%;
			height:100%;
			margin:0px;padding:0px;
		}.CSSTableGenerator tr:last-child td:last-child {
			-moz-border-radius-bottomright:0px;
			-webkit-border-bottom-right-radius:0px;
			border-bottom-right-radius:0px;
		}
		.CSSTableGenerator table tr:first-child td:first-child {
			-moz-border-radius-topleft:0px;
			-webkit-border-top-left-radius:0px;
			border-top-left-radius:0px;
		}
		.CSSTableGenerator table tr:first-child td:last-child {
			-moz-border-radius-topright:0px;
			-webkit-border-top-right-radius:0px;
			border-top-right-radius:0px;
		}.CSSTableGenerator tr:last-child td:first-child{
			-moz-border-radius-bottomleft:0px;
			-webkit-border-bottom-left-radius:0px;
			border-bottom-left-radius:0px;
		}.CSSTableGenerator tr:hover td{}
		.CSSTableGenerator tr:nth-child(odd){ background-color:#56aaff; }
		.CSSTableGenerator tr:nth-child(even)    { background-color:#ffffff; }.CSSTableGenerator td{
			vertical-align:middle;			
			border:1px solid #000000;
			border-width:0px 1px 1px 0px;
			text-align:left;
			padding:7px;
			font-size:10px;
			font-family:Arial;
			font-weight:normal;
			color:#000000;
		}.CSSTableGenerator tr:last-child td{
			border-width:0px 1px 0px 0px;
		}.CSSTableGenerator tr td:last-child{
			border-width:0px 0px 1px 0px;
		}.CSSTableGenerator tr:last-child td:last-child{
			border-width:0px 0px 0px 0px;
		}
		.CSSTableGenerator tr:first-child td{
				background:-o-linear-gradient(bottom, #113b66 5%, #56aaff 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #113b66), color-stop(1, #56aaff) );
			background:-moz-linear-gradient( center top, #113b66 5%, #56aaff 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#113b66", endColorstr="#56aaff");	background: -o-linear-gradient(top,#113b66,56aaff);
		
			background-color:#113b66;
			border:0px solid #000000;
			text-align:center;
			border-width:0px 0px 1px 1px;
			font-size:14px;
			font-family:Arial;
			font-weight:bold;
			color:#ffffff;
		}
		.CSSTableGenerator tr:first-child:hover td{
			background:-o-linear-gradient(bottom, #113b66 5%, #56aaff 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #113b66), color-stop(1, #56aaff) );
			background:-moz-linear-gradient( center top, #113b66 5%, #56aaff 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#113b66", endColorstr="#56aaff");	background: -o-linear-gradient(top,#113b66,56aaff);
		
			background-color:#113b66;
		}
		.CSSTableGenerator tr:first-child td:first-child{
			border-width:0px 0px 1px 0px;
		}
		.CSSTableGenerator tr:first-child td:last-child{
			border-width:0px 0px 1px 1px;
		}
	</style>
	<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->	
</head>
<body>
<header>
 <div class="container1">
    <!--start title-->
    <h1 class="fontface" id="title">/*@{ $res = $data->executeQuery("SELECT LIBELLE FROM apps WHERE IDAPP='$IDAPP';"); return $util->getFirstLine($res)[0];}*/</h1>
	<!--end title-->
  </div>    
	</header>
	<!--end header--> 
	<nav>
		<?php require_once('menu.inc.php'); ?>
    </nav>
	<div id="wrapper"><!-- #wrapper -->
	<section id="main"><!-- #main content and sidebar area --><!-- end of sidebar1 --><!-- end of sidebar -->

<form action="<?php echo $page; ?>" method="post" name="F/*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ucfirst($util->getFirstLine($res)[0]);}*/" id="F/*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ucfirst($util->getFirstLine($res)[0]);}*/">
<div class="CSSTableGenerator">
/*@{

	$res = $data->executeQuery("SELECT IDCHP, LIBELLE,TYPEDONNEE, REQUETE, TAILLE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1 ORDER BY NUMSEQ;");
	$i = 0;
        
        $res2 = $data->executeQuery("SELECT LIBELLE FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); 
        $tmp = $util->getFirstLine($res2)[0];
	    
    $s = '<table width="500" border="1" align="center">';
    $s .= '  
        <tr>
          <td height="12" colspan="2"><div align="center">' . ucfirst($tmp) . '</div></td>
          </tr>
        ';    
    
	while ($enreg = $util->getLineObject($res)) { 	    			
  								
		$s .= '<tr>' . "\n" . '<td>';
		$s .= $enreg->LIBELLE;
	 	$s .= '</td>' . "\n" . '<td>';
		
        // Case a cocher
        if ($enreg->TYPEDONNEE == 'tinyint(1)') {
       		$s .= '<input type="checkbox" name="E' . $enreg->IDCHP . '"  id="E' . $enreg->IDCHP . '" <?php if ($' . $enreg->IDCHP . ' == "1")  echo "checked=\"checked\""; ?>>';
		} else if  (($enreg->REQUETE != 'null') and (strlen($enreg->REQUETE) > 0)) {      	
		// Select
            $s .= '<select name="E' . $enreg->IDCHP . '" id="E' . $enreg->IDCHP . "\">\r\n";			  					            
            $s .= "<?php " . "\r\n";
			$s .= '$s =  ""' . ";\r\n";
            $s .= '$res = $data->executeQuery("' . $enreg->REQUETE . "\")" . ";\r\n";
			$s .= '	$s .= "<option value=\"\"></option>"' . ";\r\n";
			$s .= 'while ($enreg = $util->getLine($res)) {' . "\r\n";         			
			$s .= '	$sel = ""' . ";\r\n";
			$s .= ' if ($' . $enreg->IDCHP . ' == $enreg[0]) $sel = "selected"' . ";\r\n";  
						
			$s .= '	$s .= "<option value=\"{$enreg[0]}\" $sel>{$enreg[1]}</option>"' . ";\r\n";
			$s .= "}" . "\r\n";				
			$s .= 'echo $s' . ";\r\n";			
			$s .= "?>" . "\r\n";   
			$s .= "</select>" . "\r\n";
			
        } else {   
			// Input Text
			
			if ($enreg->TAILLE >= 100) {
		       $s .= '<textarea cols="50" rows="8" name="E' . $enreg->IDCHP . '" id="E' . $enreg->IDCHP . '"><?php echo htmlentities($' . $enreg->IDCHP . ', ENT_COMPAT,\'UTF-8\', true); ?></textarea>';		        			   		   
			} else {
				$size_field = $enreg->TAILLE;
				if ($size_field <= 0) $size_field = 30;
				$s .= '<input type="text" size="' . $size_field . '" name="E' . $enreg->IDCHP . '" id="E' . $enreg->IDCHP . '" value="<?php echo htmlentities($' . $enreg->IDCHP . ', ENT_COMPAT,\'UTF-8\', true); ?>">';		        	
			}						   
        }

       	$s .= '</td>' . "\n" . '</tr>' . "\n";	
                
		$i++;
	}
    $s .= "\n";
	return $s;

					
}*/      						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		/*@{
						
			$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
			$i = 0;
			$s = '';
			while ($enreg = $util->getLineObject($res)) { 	
				//if ($i > 0) $s .= ", ";	
				$s .= '<input id="E' . $enreg->IDCHP . '_ori" name="E' . $enreg->IDCHP . '_ori" type="hidden"  value="<?php echo $' . $enreg->IDCHP . '_ori; ?>">';
				$i++;
			}
			return $s;			
					
		}*/
		
		</td>
    </tr>
	</table>
  </div>
</form>
<?php $data->deconnecter(); ?>
	  <p>&nbsp;</p>
	</section><!-- end of #main content and sidebar-->
</div>
		<footer>
		<div class="container1">
		<section id="footer-area">

			<section id="footer-outer-block">
					<aside class="footer-segment">
							<h4>News</h4>
								<ul>
									<li><a href="#">xxx</a></li>
								</ul>
					</aside><!-- end of #first footer segment -->
					<aside class="footer-segment">
							<h4>About Us</h4>
								<ul>
									<li><a href="#">xxx</a></li>
								</ul>
					</aside><!-- end of #second footer segment -->
					<aside class="footer-segment">
					  <h4>Contact Us</h4>
					  <ul>
					    <li><a href="#">xxx</a></li>
					    </ul>
					  </aside>
					<!-- end of #third footer segment -->					
					<aside class="footer-segment">
					  <h4>Blahdyblah</h4>
					  <p>xx</p>
					  </aside>
					<!-- end of #fourth footer segment -->
			</section><!-- end of footer-outer-block -->
		</section>
		</div>
		</footer>
</body>
</html>