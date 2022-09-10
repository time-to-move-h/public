<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Groups

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

$GRP_DESC = '';
$GRP_DESC_ori = '';
$GRP_LOGO = '';
$GRP_LOGO_ori = '';
$GRP_NAME = '';
$GRP_NAME_ori = '';
$GRP_TITLE = '';
$GRP_TITLE_ori = '';
$IDCATGRP = '';
$IDCATGRP_ori = '';
$IDCITY = '';
$IDCITY_ori = '';
$IDCOUNTRY = '';
$IDCOUNTRY_ori = '';
$IDGRP = '';
$IDGRP_ori = '';
					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function ajouterGroups($GRP_NAME,$GRP_TITLE,$GRP_LOGO,$IDCATGRP,$GRP_DESC,$IDCOUNTRY,$IDCITY) {
	global $data;			
		
	$GRP_NAME = addslashes($GRP_NAME);
	$GRP_TITLE = addslashes($GRP_TITLE);
	$GRP_LOGO = addslashes($GRP_LOGO);
	$GRP_DESC = addslashes($GRP_DESC);
			
	$strSql = "INSERT INTO groups (GRP_DESC, GRP_LOGO, GRP_NAME, GRP_TITLE, IDCATGRP, IDCITY, IDCOUNTRY) VALUES (";
	$strSql .= ((strlen($GRP_DESC) <= 0) ? "NULL" :  "'$GRP_DESC'");
	$strSql .= ", " . ((strlen($GRP_LOGO) <= 0) ? "NULL" :  "'$GRP_LOGO'");
	$strSql .= ", " . ((strlen($GRP_NAME) <= 0) ? "''" :  "'$GRP_NAME'");
	$strSql .= ", " . ((strlen($GRP_TITLE) <= 0) ? "NULL" :  "'$GRP_TITLE'");
	$strSql .= ", " . ((strlen($IDCATGRP) <= 0) ? "NULL" :  $IDCATGRP);
	$strSql .= ", " . ((strlen($IDCITY) <= 0) ? "NULL" :  $IDCITY);
	$strSql .= ", " . ((strlen($IDCOUNTRY) <= 0) ? "NULL" :  $IDCOUNTRY);
	$strSql .= ")";		
	return $data->executeQuery($strSql);
}

// Modification
function modifierGroups($GRP_DESC, $GRP_LOGO, $GRP_NAME, $GRP_TITLE, $IDCATGRP, $IDCITY, $IDCOUNTRY, $IDGRP) {
	global $data, $util, $GRP_DESC_ori, $GRP_LOGO_ori, $GRP_NAME_ori, $GRP_TITLE_ori, $IDCATGRP_ori, $IDCITY_ori, $IDCOUNTRY_ori, $IDGRP_ori;
	$strSql = 'UPDATE groups SET ';
	$strSql_Req = '';		

	if (testGroups($IDGRP_ori)) {			
				
	$result = getSingleRowGroups(	$IDGRP_ori);
	$ligne = $util->getFirstLineObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {
	
		
		$GRP_DESC_ori = $ligne->GRP_DESC;
		$GRP_LOGO_ori = $ligne->GRP_LOGO;
		$GRP_NAME_ori = $ligne->GRP_NAME;
		$GRP_TITLE_ori = $ligne->GRP_TITLE;
		$IDCATGRP_ori = $ligne->IDCATGRP;
		$IDCITY_ori = $ligne->IDCITY;
		$IDCOUNTRY_ori = $ligne->IDCOUNTRY;
		$IDGRP_ori = $ligne->IDGRP;
	
	}}
	
	$GRP_NAME = addslashes($GRP_NAME);
	$GRP_TITLE = addslashes($GRP_TITLE);
	$GRP_LOGO = addslashes($GRP_LOGO);
	$GRP_DESC = addslashes($GRP_DESC);
	
		
	if ($GRP_DESC != addslashes($GRP_DESC_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "GRP_DESC = '$GRP_DESC'";
	if ($GRP_LOGO != addslashes($GRP_LOGO_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "GRP_LOGO = '$GRP_LOGO'";
	if ($GRP_NAME != addslashes($GRP_NAME_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "GRP_NAME = '$GRP_NAME'";
	if ($GRP_TITLE != addslashes($GRP_TITLE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "GRP_TITLE = '$GRP_TITLE'";
	if ($IDCATGRP != addslashes($IDCATGRP_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDCATGRP = '$IDCATGRP'";
	if ($IDCITY != addslashes($IDCITY_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDCITY = '$IDCITY'";
	if ($IDCOUNTRY != addslashes($IDCOUNTRY_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDCOUNTRY = '$IDCOUNTRY'";
	if ($IDGRP != addslashes($IDGRP_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDGRP = '$IDGRP'";
		

	$strSql .= $strSql_Req . " WHERE IDGRP = $IDGRP_ori";	
	//echo $strSql;
	return $data->executeQuery($strSql);
}

// Test d'existence
function testGroups($IDGRP) {
	global $data;
	$strSql = "SELECT 1 FROM groups WHERE  IDGRP = $IDGRP ;";
	$res = $data->executeQuery($strSql);
	return $data->testCommun($res);
}

// Récupération enregistrement
function getSingleRowGroups($IDGRP) {
	global $data;
	$strSql = "SELECT * FROM groups WHERE IDGRP = $IDGRP;";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$GRP_DESC = trim(isset($_POST['EGRP_DESC']) ? $_POST['EGRP_DESC'] : '');
$GRP_LOGO = trim(isset($_POST['EGRP_LOGO']) ? $_POST['EGRP_LOGO'] : '');
$GRP_NAME = trim(isset($_POST['EGRP_NAME']) ? $_POST['EGRP_NAME'] : '');
$GRP_TITLE = trim(isset($_POST['EGRP_TITLE']) ? $_POST['EGRP_TITLE'] : '');
$IDCATGRP = trim(isset($_POST['EIDCATGRP']) ? $_POST['EIDCATGRP'] : '');
$IDCITY = trim(isset($_POST['EIDCITY']) ? $_POST['EIDCITY'] : '');
$IDCOUNTRY = trim(isset($_POST['EIDCOUNTRY']) ? $_POST['EIDCOUNTRY'] : '');
$IDGRP = trim(isset($_POST['EIDGRP']) ? $_POST['EIDGRP'] : '');
		

$IDGRP_ori = trim(isset($_POST['EIDGRP_ori']) ? $_POST['EIDGRP_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! testGroups($IDGRP)) {
					
if (ajouterGroups($GRP_NAME,$GRP_TITLE,$GRP_LOGO,$IDCATGRP,$GRP_DESC,$IDCOUNTRY,$IDCITY)) {
		$message = "Insertion OK";	
	} 
}
	break;
	
	case 1 :			

	
if (testGroups($IDGRP)) {
		
if (modifierGroups($GRP_DESC, $GRP_LOGO, $GRP_NAME, $GRP_TITLE, $IDCATGRP, $IDCITY, $IDCOUNTRY, $IDGRP)) {				
		$message = "Modification OK";	
		} 
	}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDGRP"])) {

$IDGRP= $_GET["EIDGRP"]; 
				
if (testGroups($IDGRP)) {			
				
$result = getSingleRowGroups($IDGRP);
$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
	$GRP_DESC = $GRP_DESC_ori = $ligne->GRP_DESC;
	$GRP_LOGO = $GRP_LOGO_ori = $ligne->GRP_LOGO;
	$GRP_NAME = $GRP_NAME_ori = $ligne->GRP_NAME;
	$GRP_TITLE = $GRP_TITLE_ori = $ligne->GRP_TITLE;
	$IDCATGRP = $IDCATGRP_ori = $ligne->IDCATGRP;
	$IDCITY = $IDCITY_ori = $ligne->IDCITY;
	$IDCOUNTRY = $IDCOUNTRY_ori = $ligne->IDCOUNTRY;
	$IDGRP = $IDGRP_ori = $ligne->IDGRP;
	
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



<form action="<?php echo $page; ?>" method="post" name="FGroups" id="FGroups">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Groups</div></td>
          </tr>
        <tr>
<td>ID</td>
<td><input type="text" size="30" name="EIDGRP" id="EIDGRP" value="<?php echo htmlentities($IDGRP, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Name</td>
<td><input type="text" size="30" name="EGRP_NAME" id="EGRP_NAME" value="<?php echo htmlentities($GRP_NAME, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Title</td>
<td><input type="text" size="30" name="EGRP_TITLE" id="EGRP_TITLE" value="<?php echo htmlentities($GRP_TITLE, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Logo</td>
<td><input type="text" size="30" name="EGRP_LOGO" id="EGRP_LOGO" value="<?php echo htmlentities($GRP_LOGO, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Category</td>
<td><input type="text" size="30" name="EIDCATGRP" id="EIDCATGRP" value="<?php echo htmlentities($IDCATGRP, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Description</td>
<td><input type="text" size="30" name="EGRP_DESC" id="EGRP_DESC" value="<?php echo htmlentities($GRP_DESC, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Country</td>
<td><input type="text" size="30" name="EIDCOUNTRY" id="EIDCOUNTRY" value="<?php echo htmlentities($IDCOUNTRY, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>City</td>
<td><input type="text" size="30" name="EIDCITY" id="EIDCITY" value="<?php echo htmlentities($IDCITY, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>

      						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		<input id="EIDGRP_ori" name="EIDGRP_ori" type="hidden"  value="<?php echo $IDGRP_ori; ?>">
		
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
