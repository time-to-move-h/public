<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : /*@{ return ucfirst($IDAPPDAT);}*/

// Inclusion de bibliothèque
require('../CLASS/IO/DatabaseCoreClass.php');
require('../CLASS/IO/DataUtilsClass.php');
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
function ajouter/*@{ return ucfirst($IDAPPDAT);}*/(/*@{

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
	global $data;			
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
	$strSql = "INSERT INTO /*@{ return $IDAPPDAT;}*/ (/*@{
		
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
	return $data->executeQuery($strSql);
}

// Modification
function modifier/*@{ return ucfirst($IDAPPDAT);}*/(/*@{

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
	$strSql = 'UPDATE /*@{ return $IDAPPDAT;}*/ SET ';
	$strSql_Req = '';		

	if (test/*@{ return ucfirst($IDAPPDAT);}*/(/*@{
	
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
				
	$result = getSingleRow/*@{ return ucfirst($IDAPPDAT);}*/(/*@{

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
	return $data->executeQuery($strSql);
}

// Test d'existence
function test/*@{ return ucfirst($IDAPPDAT);}*/(/*@{ 

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
	$strSql = "SELECT 1 FROM /*@{ return $IDAPPDAT;}*/ WHERE  /*@{ 
	
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
function getSingleRow/*@{ return ucfirst($IDAPPDAT);}*/(/*@{ 

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
	$strSql = "SELECT * FROM /*@{ return $IDAPPDAT;}*/ WHERE /*@{ 	
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
if (! test/*@{ return ucfirst($IDAPPDAT);}*/(/*@{

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
					
if (ajouter/*@{ return ucfirst($IDAPPDAT);}*/(/*@{

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

	
if (test/*@{ return ucfirst($IDAPPDAT);}*/(/*@{

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
		
if (modifier/*@{ return ucfirst($IDAPPDAT);}*/(/*@{
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
if (test/*@{ return ucfirst($IDAPPDAT);}*/(/*@{

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
				
$result = getSingleRow/*@{ return ucfirst($IDAPPDAT);}*/(/*@{

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
		$s .= '	$' . $enreg->IDCHP . ' = ' . '$' . $enreg->IDCHP . '_ori = $ligne->' . $enreg->IDCHP . ";\r\n";
		$i++;
	}
	return $s;
}*/	
}			
$mode = 1;
}}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Moviao Admin - Movimiento Ahora !</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Moviao Admin</a>
            </div>
            
            <!-- Top Menu Items -->
			<?php require_once('inc/menu_top.inc.php'); ?>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <?php require_once('inc/menu.inc.php'); ?>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Dashboard <small>Statistics Overview</small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->



<form action="<?php echo $page; ?>" method="post" name="F/*@{ return ucfirst($IDAPPDAT);}*/" id="F/*@{ return ucfirst($IDAPPDAT);}*/">
<div class="CSSTableGenerator">
/*@{

	$res = $data->executeQuery("SELECT IDCHP, LIBELLE,TYPEDONNEE, REQUETE, TAILLE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1 ORDER BY NUMSEQ;");
	$i = 0;
	    
    $s = '<table width="500" border="1" align="center">';
    $s .= '  
        <tr>
          <td height="12" colspan="2"><div align="center">' . ucfirst($IDAPPDAT) . '</div></td>
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

  </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
