<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Status_matri

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

$IDSTMA = '';
$IDSTMA_ori = '';
$STMA_DESC = '';
$STMA_DESC_ori = '';
					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function ajouterStatus_matri($STMA_DESC) {
	global $data;			
		
	$STMA_DESC = addslashes($STMA_DESC);
			
	$strSql = "INSERT INTO status_matri (STMA_DESC) VALUES (";
	$strSql .= ((strlen($STMA_DESC) <= 0) ? "''" :  "'$STMA_DESC'");
	$strSql .= ")";		
	return $data->executeQuery($strSql);
}

// Modification
function modifierStatus_matri($IDSTMA, $STMA_DESC) {
	global $data, $util, $IDSTMA_ori, $STMA_DESC_ori;
	$strSql = 'UPDATE status_matri SET ';
	$strSql_Req = '';		

	if (testStatus_matri($IDSTMA_ori)) {			
				
	$result = getSingleRowStatus_matri(	$IDSTMA_ori);
	$ligne = $util->getFirstLineObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {
	
		
		$IDSTMA_ori = $ligne->IDSTMA;
		$STMA_DESC_ori = $ligne->STMA_DESC;
	
	}}
	
	$STMA_DESC = addslashes($STMA_DESC);
	
		
	if ($IDSTMA != addslashes($IDSTMA_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDSTMA = '$IDSTMA'";
	if ($STMA_DESC != addslashes($STMA_DESC_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "STMA_DESC = '$STMA_DESC'";
		

	$strSql .= $strSql_Req . " WHERE IDSTMA = $IDSTMA_ori";	
	//echo $strSql;
	return $data->executeQuery($strSql);
}

// Test d'existence
function testStatus_matri($IDSTMA) {
	global $data;
	$strSql = "SELECT 1 FROM status_matri WHERE  IDSTMA = $IDSTMA ;";
	$res = $data->executeQuery($strSql);
	return $data->testCommun($res);
}

// Récupération enregistrement
function getSingleRowStatus_matri($IDSTMA) {
	global $data;
	$strSql = "SELECT * FROM status_matri WHERE IDSTMA = $IDSTMA;";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$IDSTMA = trim(isset($_POST['EIDSTMA']) ? $_POST['EIDSTMA'] : '');
$STMA_DESC = trim(isset($_POST['ESTMA_DESC']) ? $_POST['ESTMA_DESC'] : '');
		

$IDSTMA_ori = trim(isset($_POST['EIDSTMA_ori']) ? $_POST['EIDSTMA_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! testStatus_matri($IDSTMA)) {
					
if (ajouterStatus_matri($STMA_DESC)) {
		$message = "Insertion OK";	
	} 
}
	break;
	
	case 1 :			

	
if (testStatus_matri($IDSTMA)) {
		
if (modifierStatus_matri($IDSTMA, $STMA_DESC)) {				
		$message = "Modification OK";	
		} 
	}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDSTMA"])) {

$IDSTMA= $_GET["EIDSTMA"]; 
				
if (testStatus_matri($IDSTMA)) {			
				
$result = getSingleRowStatus_matri($IDSTMA);
$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
	$IDSTMA = $IDSTMA_ori = $ligne->IDSTMA;
	$STMA_DESC = $STMA_DESC_ori = $ligne->STMA_DESC;
	
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



<form action="<?php echo $page; ?>" method="post" name="FStatus_matri" id="FStatus_matri">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Status_matri</div></td>
          </tr>
        <tr>
<td>ID</td>
<td><input type="text" size="30" name="EIDSTMA" id="EIDSTMA" value="<?php echo htmlentities($IDSTMA, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Description</td>
<td><input type="text" size="30" name="ESTMA_DESC" id="ESTMA_DESC" value="<?php echo htmlentities($STMA_DESC, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>

      						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		<input id="EIDSTMA_ori" name="EIDSTMA_ori" type="hidden"  value="<?php echo $IDSTMA_ori; ?>">
		
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
