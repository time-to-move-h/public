<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Account_type

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

$ACCTYP_ACTIVE = '';
$ACCTYP_ACTIVE_ori = '';
$ACCTYP_DESC = '';
$ACCTYP_DESC_ori = '';
$IDACCTYP = '';
$IDACCTYP_ori = '';
					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function ajouterAccount_type($IDACCTYP,$ACCTYP_DESC,$ACCTYP_ACTIVE) {
	global $data;			
		
	$ACCTYP_DESC = addslashes($ACCTYP_DESC);
			
	$strSql = "INSERT INTO account_type (ACCTYP_ACTIVE, ACCTYP_DESC, IDACCTYP) VALUES (";
	$strSql .= ((strlen($ACCTYP_ACTIVE) <= 0) ? "0" :  $ACCTYP_ACTIVE);
	$strSql .= ", " . ((strlen($ACCTYP_DESC) <= 0) ? "''" :  "'$ACCTYP_DESC'");
	$strSql .= ", " . ((strlen($IDACCTYP) <= 0) ? "0" :  $IDACCTYP);
	$strSql .= ")";		
	return $data->executeQuery($strSql);
}

// Modification
function modifierAccount_type($ACCTYP_ACTIVE, $ACCTYP_DESC, $IDACCTYP) {
	global $data, $util, $ACCTYP_ACTIVE_ori, $ACCTYP_DESC_ori, $IDACCTYP_ori;
	$strSql = 'UPDATE account_type SET ';
	$strSql_Req = '';		

	if (testAccount_type($IDACCTYP_ori)) {			
				
	$result = getSingleRowAccount_type(	$IDACCTYP_ori);
	$ligne = $util->getFirstLineObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {
	
		
		$ACCTYP_ACTIVE_ori = $ligne->ACCTYP_ACTIVE;
		$ACCTYP_DESC_ori = $ligne->ACCTYP_DESC;
		$IDACCTYP_ori = $ligne->IDACCTYP;
	
	}}
	
	$ACCTYP_DESC = addslashes($ACCTYP_DESC);
	
		
	if ($ACCTYP_ACTIVE != addslashes($ACCTYP_ACTIVE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "ACCTYP_ACTIVE = '$ACCTYP_ACTIVE'";
	if ($ACCTYP_DESC != addslashes($ACCTYP_DESC_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "ACCTYP_DESC = '$ACCTYP_DESC'";
	if ($IDACCTYP != addslashes($IDACCTYP_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDACCTYP = '$IDACCTYP'";
		

	$strSql .= $strSql_Req . " WHERE IDACCTYP = $IDACCTYP_ori";	
	//echo $strSql;
	return $data->executeQuery($strSql);
}

// Test d'existence
function testAccount_type($IDACCTYP) {
	global $data;
	$strSql = "SELECT 1 FROM account_type WHERE  IDACCTYP = $IDACCTYP ;";
	$res = $data->executeQuery($strSql);
	return $data->testCommun($res);
}

// Récupération enregistrement
function getSingleRowAccount_type($IDACCTYP) {
	global $data;
	$strSql = "SELECT * FROM account_type WHERE IDACCTYP = $IDACCTYP;";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$ACCTYP_ACTIVE = trim(isset($_POST['EACCTYP_ACTIVE']) ? 1 : 0);
$ACCTYP_DESC = trim(isset($_POST['EACCTYP_DESC']) ? $_POST['EACCTYP_DESC'] : '');
$IDACCTYP = trim(isset($_POST['EIDACCTYP']) ? $_POST['EIDACCTYP'] : '');
		

$IDACCTYP_ori = trim(isset($_POST['EIDACCTYP_ori']) ? $_POST['EIDACCTYP_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! testAccount_type($IDACCTYP)) {
					
if (ajouterAccount_type($IDACCTYP,$ACCTYP_DESC,$ACCTYP_ACTIVE)) {
		$message = "Insertion OK";	
	} 
}
	break;
	
	case 1 :			

	
if (testAccount_type($IDACCTYP)) {
		
if (modifierAccount_type($ACCTYP_ACTIVE, $ACCTYP_DESC, $IDACCTYP)) {				
		$message = "Modification OK";	
		} 
	}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDACCTYP"])) {

$IDACCTYP= $_GET["EIDACCTYP"]; 
				
if (testAccount_type($IDACCTYP)) {			
				
$result = getSingleRowAccount_type($IDACCTYP);
$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
	$ACCTYP_ACTIVE = $ACCTYP_ACTIVE_ori = $ligne->ACCTYP_ACTIVE;
	$ACCTYP_DESC = $ACCTYP_DESC_ori = $ligne->ACCTYP_DESC;
	$IDACCTYP = $IDACCTYP_ori = $ligne->IDACCTYP;
	
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



<form action="<?php echo $page; ?>" method="post" name="FAccount_type" id="FAccount_type">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Account_type</div></td>
          </tr>
        <tr>
<td>ID</td>
<td><input type="text" size="30" name="EIDACCTYP" id="EIDACCTYP" value="<?php echo htmlentities($IDACCTYP, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Description</td>
<td><input type="text" size="30" name="EACCTYP_DESC" id="EACCTYP_DESC" value="<?php echo htmlentities($ACCTYP_DESC, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Active</td>
<td><input type="checkbox" name="EACCTYP_ACTIVE"  id="EACCTYP_ACTIVE" <?php if ($ACCTYP_ACTIVE == "1")  echo "checked=\"checked\""; ?>></td>
</tr>

      						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		<input id="EIDACCTYP_ori" name="EIDACCTYP_ori" type="hidden"  value="<?php echo $IDACCTYP_ori; ?>">
		
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
