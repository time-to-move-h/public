<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Status_con

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

$IDSTCON = '';
$IDSTCON_ori = '';
$STCON_ACTIVE = '';
$STCON_ACTIVE_ori = '';
$STCON_DESC = '';
$STCON_DESC_ori = '';
					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function ajouterStatus_con($IDSTCON,$STCON_DESC,$STCON_ACTIVE) {
	global $data;			
		
	$STCON_DESC = addslashes($STCON_DESC);
			
	$strSql = "INSERT INTO status_con (IDSTCON, STCON_ACTIVE, STCON_DESC) VALUES (";
	$strSql .= ((strlen($IDSTCON) <= 0) ? "0" :  $IDSTCON);
	$strSql .= ", " . ((strlen($STCON_ACTIVE) <= 0) ? "0" :  $STCON_ACTIVE);
	$strSql .= ", " . ((strlen($STCON_DESC) <= 0) ? "''" :  "'$STCON_DESC'");
	$strSql .= ")";		
	return $data->executeQuery($strSql);
}

// Modification
function modifierStatus_con($IDSTCON, $STCON_ACTIVE, $STCON_DESC) {
	global $data, $util, $IDSTCON_ori, $STCON_ACTIVE_ori, $STCON_DESC_ori;
	$strSql = 'UPDATE status_con SET ';
	$strSql_Req = '';		

	if (testStatus_con($IDSTCON_ori)) {			
				
	$result = getSingleRowStatus_con(	$IDSTCON_ori);
	$ligne = $util->getFirstLineObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {
	
		
		$IDSTCON_ori = $ligne->IDSTCON;
		$STCON_ACTIVE_ori = $ligne->STCON_ACTIVE;
		$STCON_DESC_ori = $ligne->STCON_DESC;
	
	}}
	
	$STCON_DESC = addslashes($STCON_DESC);
	
		
	if ($IDSTCON != addslashes($IDSTCON_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDSTCON = '$IDSTCON'";
	if ($STCON_ACTIVE != addslashes($STCON_ACTIVE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "STCON_ACTIVE = '$STCON_ACTIVE'";
	if ($STCON_DESC != addslashes($STCON_DESC_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "STCON_DESC = '$STCON_DESC'";
		

	$strSql .= $strSql_Req . " WHERE IDSTCON = $IDSTCON_ori";	
	//echo $strSql;
	return $data->executeQuery($strSql);
}

// Test d'existence
function testStatus_con($IDSTCON) {
	global $data;
	$strSql = "SELECT 1 FROM status_con WHERE  IDSTCON = $IDSTCON ;";
	$res = $data->executeQuery($strSql);
	return $data->testCommun($res);
}

// Récupération enregistrement
function getSingleRowStatus_con($IDSTCON) {
	global $data;
	$strSql = "SELECT * FROM status_con WHERE IDSTCON = $IDSTCON;";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$IDSTCON = trim(isset($_POST['EIDSTCON']) ? $_POST['EIDSTCON'] : '');
$STCON_ACTIVE = trim(isset($_POST['ESTCON_ACTIVE']) ? 1 : 0);
$STCON_DESC = trim(isset($_POST['ESTCON_DESC']) ? $_POST['ESTCON_DESC'] : '');
		

$IDSTCON_ori = trim(isset($_POST['EIDSTCON_ori']) ? $_POST['EIDSTCON_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! testStatus_con($IDSTCON)) {
					
if (ajouterStatus_con($IDSTCON,$STCON_DESC,$STCON_ACTIVE)) {
		$message = "Insertion OK";	
	} 
}
	break;
	
	case 1 :			

	
if (testStatus_con($IDSTCON)) {
		
if (modifierStatus_con($IDSTCON, $STCON_ACTIVE, $STCON_DESC)) {				
		$message = "Modification OK";	
		} 
	}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDSTCON"])) {

$IDSTCON= $_GET["EIDSTCON"]; 
				
if (testStatus_con($IDSTCON)) {			
				
$result = getSingleRowStatus_con($IDSTCON);
$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
	$IDSTCON = $IDSTCON_ori = $ligne->IDSTCON;
	$STCON_ACTIVE = $STCON_ACTIVE_ori = $ligne->STCON_ACTIVE;
	$STCON_DESC = $STCON_DESC_ori = $ligne->STCON_DESC;
	
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



<form action="<?php echo $page; ?>" method="post" name="FStatus_con" id="FStatus_con">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Status_con</div></td>
          </tr>
        <tr>
<td>ID</td>
<td><input type="text" size="30" name="EIDSTCON" id="EIDSTCON" value="<?php echo htmlentities($IDSTCON, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Description</td>
<td><input type="text" size="30" name="ESTCON_DESC" id="ESTCON_DESC" value="<?php echo htmlentities($STCON_DESC, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Active</td>
<td><input type="checkbox" name="ESTCON_ACTIVE"  id="ESTCON_ACTIVE" <?php if ($STCON_ACTIVE == "1")  echo "checked=\"checked\""; ?>></td>
</tr>

      						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		<input id="EIDSTCON_ori" name="EIDSTCON_ori" type="hidden"  value="<?php echo $IDSTCON_ori; ?>">
		
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
