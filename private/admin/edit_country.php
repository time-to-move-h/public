<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Country

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

$ACTIVE = '';
$ACTIVE_ori = '';
$COUNT_DESC_EN = '';
$COUNT_DESC_EN_ori = '';
$COUNT_DESC_ES = '';
$COUNT_DESC_ES_ori = '';
$COUNT_DESC_FR = '';
$COUNT_DESC_FR_ori = '';
$COUNT_DESC_NL = '';
$COUNT_DESC_NL_ori = '';
$IDCOUNTRY = '';
$IDCOUNTRY_ori = '';
					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function ajouterCountry($IDCOUNTRY,$COUNT_DESC_EN,$COUNT_DESC_FR,$COUNT_DESC_ES,$COUNT_DESC_NL,$ACTIVE) {
	global $data;			
		
	$COUNT_DESC_EN = addslashes($COUNT_DESC_EN);
	$COUNT_DESC_FR = addslashes($COUNT_DESC_FR);
	$COUNT_DESC_ES = addslashes($COUNT_DESC_ES);
	$COUNT_DESC_NL = addslashes($COUNT_DESC_NL);
			
	$strSql = "INSERT INTO country (ACTIVE, COUNT_DESC_EN, COUNT_DESC_ES, COUNT_DESC_FR, COUNT_DESC_NL, IDCOUNTRY) VALUES (";
	$strSql .= ((strlen($ACTIVE) <= 0) ? "0" :  $ACTIVE);
	$strSql .= ", " . ((strlen($COUNT_DESC_EN) <= 0) ? "NULL" :  "'$COUNT_DESC_EN'");
	$strSql .= ", " . ((strlen($COUNT_DESC_ES) <= 0) ? "NULL" :  "'$COUNT_DESC_ES'");
	$strSql .= ", " . ((strlen($COUNT_DESC_FR) <= 0) ? "NULL" :  "'$COUNT_DESC_FR'");
	$strSql .= ", " . ((strlen($COUNT_DESC_NL) <= 0) ? "NULL" :  "'$COUNT_DESC_NL'");
	$strSql .= ", " . ((strlen($IDCOUNTRY) <= 0) ? "0" :  $IDCOUNTRY);
	$strSql .= ")";		
	return $data->executeQuery($strSql);
}

// Modification
function modifierCountry($ACTIVE, $COUNT_DESC_EN, $COUNT_DESC_ES, $COUNT_DESC_FR, $COUNT_DESC_NL, $IDCOUNTRY) {
	global $data, $util, $ACTIVE_ori, $COUNT_DESC_EN_ori, $COUNT_DESC_ES_ori, $COUNT_DESC_FR_ori, $COUNT_DESC_NL_ori, $IDCOUNTRY_ori;
	$strSql = 'UPDATE country SET ';
	$strSql_Req = '';		

	if (testCountry($IDCOUNTRY_ori)) {			
				
	$result = getSingleRowCountry(	$IDCOUNTRY_ori);
	$ligne = $util->getFirstLineObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {
	
		
		$ACTIVE_ori = $ligne->ACTIVE;
		$COUNT_DESC_EN_ori = $ligne->COUNT_DESC_EN;
		$COUNT_DESC_ES_ori = $ligne->COUNT_DESC_ES;
		$COUNT_DESC_FR_ori = $ligne->COUNT_DESC_FR;
		$COUNT_DESC_NL_ori = $ligne->COUNT_DESC_NL;
		$IDCOUNTRY_ori = $ligne->IDCOUNTRY;
	
	}}
	
	$COUNT_DESC_EN = addslashes($COUNT_DESC_EN);
	$COUNT_DESC_FR = addslashes($COUNT_DESC_FR);
	$COUNT_DESC_ES = addslashes($COUNT_DESC_ES);
	$COUNT_DESC_NL = addslashes($COUNT_DESC_NL);
	
		
	if ($ACTIVE != addslashes($ACTIVE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "ACTIVE = '$ACTIVE'";
	if ($COUNT_DESC_EN != addslashes($COUNT_DESC_EN_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "COUNT_DESC_EN = '$COUNT_DESC_EN'";
	if ($COUNT_DESC_ES != addslashes($COUNT_DESC_ES_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "COUNT_DESC_ES = '$COUNT_DESC_ES'";
	if ($COUNT_DESC_FR != addslashes($COUNT_DESC_FR_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "COUNT_DESC_FR = '$COUNT_DESC_FR'";
	if ($COUNT_DESC_NL != addslashes($COUNT_DESC_NL_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "COUNT_DESC_NL = '$COUNT_DESC_NL'";
	if ($IDCOUNTRY != addslashes($IDCOUNTRY_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDCOUNTRY = '$IDCOUNTRY'";
		

	$strSql .= $strSql_Req . " WHERE IDCOUNTRY = $IDCOUNTRY_ori";	
	//echo $strSql;
	return $data->executeQuery($strSql);
}

// Test d'existence
function testCountry($IDCOUNTRY) {
	global $data;
	$strSql = "SELECT 1 FROM country WHERE  IDCOUNTRY = $IDCOUNTRY ;";
	$res = $data->executeQuery($strSql);
	return $data->testCommun($res);
}

// Récupération enregistrement
function getSingleRowCountry($IDCOUNTRY) {
	global $data;
	$strSql = "SELECT * FROM country WHERE IDCOUNTRY = $IDCOUNTRY;";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$ACTIVE = trim(isset($_POST['EACTIVE']) ? 1 : 0);
$COUNT_DESC_EN = trim(isset($_POST['ECOUNT_DESC_EN']) ? $_POST['ECOUNT_DESC_EN'] : '');
$COUNT_DESC_ES = trim(isset($_POST['ECOUNT_DESC_ES']) ? $_POST['ECOUNT_DESC_ES'] : '');
$COUNT_DESC_FR = trim(isset($_POST['ECOUNT_DESC_FR']) ? $_POST['ECOUNT_DESC_FR'] : '');
$COUNT_DESC_NL = trim(isset($_POST['ECOUNT_DESC_NL']) ? $_POST['ECOUNT_DESC_NL'] : '');
$IDCOUNTRY = trim(isset($_POST['EIDCOUNTRY']) ? $_POST['EIDCOUNTRY'] : '');
		

$IDCOUNTRY_ori = trim(isset($_POST['EIDCOUNTRY_ori']) ? $_POST['EIDCOUNTRY_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! testCountry($IDCOUNTRY)) {
					
if (ajouterCountry($IDCOUNTRY,$COUNT_DESC_EN,$COUNT_DESC_FR,$COUNT_DESC_ES,$COUNT_DESC_NL,$ACTIVE)) {
		$message = "Insertion OK";	
	} 
}
	break;
	
	case 1 :			

	
if (testCountry($IDCOUNTRY)) {
		
if (modifierCountry($ACTIVE, $COUNT_DESC_EN, $COUNT_DESC_ES, $COUNT_DESC_FR, $COUNT_DESC_NL, $IDCOUNTRY)) {				
		$message = "Modification OK";	
		} 
	}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDCOUNTRY"])) {

$IDCOUNTRY= $_GET["EIDCOUNTRY"]; 
				
if (testCountry($IDCOUNTRY)) {			
				
$result = getSingleRowCountry($IDCOUNTRY);
$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
	$ACTIVE = $ACTIVE_ori = $ligne->ACTIVE;
	$COUNT_DESC_EN = $COUNT_DESC_EN_ori = $ligne->COUNT_DESC_EN;
	$COUNT_DESC_ES = $COUNT_DESC_ES_ori = $ligne->COUNT_DESC_ES;
	$COUNT_DESC_FR = $COUNT_DESC_FR_ori = $ligne->COUNT_DESC_FR;
	$COUNT_DESC_NL = $COUNT_DESC_NL_ori = $ligne->COUNT_DESC_NL;
	$IDCOUNTRY = $IDCOUNTRY_ori = $ligne->IDCOUNTRY;
	
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



<form action="<?php echo $page; ?>" method="post" name="FCountry" id="FCountry">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Country</div></td>
          </tr>
        <tr>
<td>identifiant pays</td>
<td><input type="text" size="30" name="EIDCOUNTRY" id="EIDCOUNTRY" value="<?php echo htmlentities($IDCOUNTRY, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>description</td>
<td><input type="text" size="30" name="ECOUNT_DESC_EN" id="ECOUNT_DESC_EN" value="<?php echo htmlentities($COUNT_DESC_EN, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Country FR</td>
<td><input type="text" size="30" name="ECOUNT_DESC_FR" id="ECOUNT_DESC_FR" value="<?php echo htmlentities($COUNT_DESC_FR, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Country ES</td>
<td><input type="text" size="30" name="ECOUNT_DESC_ES" id="ECOUNT_DESC_ES" value="<?php echo htmlentities($COUNT_DESC_ES, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Country NL</td>
<td><input type="text" size="30" name="ECOUNT_DESC_NL" id="ECOUNT_DESC_NL" value="<?php echo htmlentities($COUNT_DESC_NL, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Active</td>
<td><input type="checkbox" name="EACTIVE"  id="EACTIVE" <?php if ($ACTIVE == "1")  echo "checked=\"checked\""; ?>></td>
</tr>

      						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		<input id="EIDCOUNTRY_ori" name="EIDCOUNTRY_ori" type="hidden"  value="<?php echo $IDCOUNTRY_ori; ?>">
		
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
