<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Groups_list

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

$GRPLST_ACTIVE = '';
$GRPLST_ACTIVE_ori = '';
$GRPLST_DATEINS = '';
$GRPLST_DATEINS_ori = '';
$IDGRPLST = '';
$IDGRPLST_ori = '';
$IDUSER = '';
$IDUSER_ori = '';
					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function ajouterGroups_list($IDGRPLST,$IDUSER,$GRPLST_DATEINS,$GRPLST_ACTIVE) {
	global $data;			
		
	$GRPLST_DATEINS = addslashes($GRPLST_DATEINS);
			
	$strSql = "INSERT INTO groups_list (GRPLST_ACTIVE, GRPLST_DATEINS, IDGRPLST, IDUSER) VALUES (";
	$strSql .= ((strlen($GRPLST_ACTIVE) <= 0) ? "0" :  $GRPLST_ACTIVE);
	$strSql .= ", " . ((strlen($GRPLST_DATEINS) <= 0) ? "''" :  "'$GRPLST_DATEINS'");
	$strSql .= ", " . ((strlen($IDGRPLST) <= 0) ? "0" :  $IDGRPLST);
	$strSql .= ", " . ((strlen($IDUSER) <= 0) ? "0" :  $IDUSER);
	$strSql .= ")";		
	return $data->executeQuery($strSql);
}

// Modification
function modifierGroups_list($GRPLST_ACTIVE, $GRPLST_DATEINS, $IDGRPLST, $IDUSER) {
	global $data, $util, $GRPLST_ACTIVE_ori, $GRPLST_DATEINS_ori, $IDGRPLST_ori, $IDUSER_ori;
	$strSql = 'UPDATE groups_list SET ';
	$strSql_Req = '';		

	if (testGroups_list($IDGRPLST_ori)) {			
				
	$result = getSingleRowGroups_list(	$IDGRPLST_ori);
	$ligne = $util->getFirstLineObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {
	
		
		$GRPLST_ACTIVE_ori = $ligne->GRPLST_ACTIVE;
		$GRPLST_DATEINS_ori = $ligne->GRPLST_DATEINS;
		$IDGRPLST_ori = $ligne->IDGRPLST;
		$IDUSER_ori = $ligne->IDUSER;
	
	}}
	
	$GRPLST_DATEINS = addslashes($GRPLST_DATEINS);
	
		
	if ($GRPLST_ACTIVE != addslashes($GRPLST_ACTIVE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "GRPLST_ACTIVE = '$GRPLST_ACTIVE'";
	if ($GRPLST_DATEINS != addslashes($GRPLST_DATEINS_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "GRPLST_DATEINS = '$GRPLST_DATEINS'";
	if ($IDGRPLST != addslashes($IDGRPLST_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDGRPLST = '$IDGRPLST'";
	if ($IDUSER != addslashes($IDUSER_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDUSER = '$IDUSER'";
		

	$strSql .= $strSql_Req . " WHERE IDGRPLST = $IDGRPLST_ori";	
	//echo $strSql;
	return $data->executeQuery($strSql);
}

// Test d'existence
function testGroups_list($IDGRPLST) {
	global $data;
	$strSql = "SELECT 1 FROM groups_list WHERE  IDGRPLST = $IDGRPLST ;";
	$res = $data->executeQuery($strSql);
	return $data->testCommun($res);
}

// Récupération enregistrement
function getSingleRowGroups_list($IDGRPLST) {
	global $data;
	$strSql = "SELECT * FROM groups_list WHERE IDGRPLST = $IDGRPLST;";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$GRPLST_ACTIVE = trim(isset($_POST['EGRPLST_ACTIVE']) ? 1 : 0);
$GRPLST_DATEINS = trim(isset($_POST['EGRPLST_DATEINS']) ? $_POST['EGRPLST_DATEINS'] : '');
$IDGRPLST = trim(isset($_POST['EIDGRPLST']) ? $_POST['EIDGRPLST'] : '');
$IDUSER = trim(isset($_POST['EIDUSER']) ? $_POST['EIDUSER'] : '');
		

$IDGRPLST_ori = trim(isset($_POST['EIDGRPLST_ori']) ? $_POST['EIDGRPLST_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! testGroups_list($IDGRPLST)) {
					
if (ajouterGroups_list($IDGRPLST,$IDUSER,$GRPLST_DATEINS,$GRPLST_ACTIVE)) {
		$message = "Insertion OK";	
	} 
}
	break;
	
	case 1 :			

	
if (testGroups_list($IDGRPLST)) {
		
if (modifierGroups_list($GRPLST_ACTIVE, $GRPLST_DATEINS, $IDGRPLST, $IDUSER)) {				
		$message = "Modification OK";	
		} 
	}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDGRPLST"])) {

$IDGRPLST= $_GET["EIDGRPLST"]; 
				
if (testGroups_list($IDGRPLST)) {			
				
$result = getSingleRowGroups_list($IDGRPLST);
$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
	$GRPLST_ACTIVE = $GRPLST_ACTIVE_ori = $ligne->GRPLST_ACTIVE;
	$GRPLST_DATEINS = $GRPLST_DATEINS_ori = $ligne->GRPLST_DATEINS;
	$IDGRPLST = $IDGRPLST_ori = $ligne->IDGRPLST;
	$IDUSER = $IDUSER_ori = $ligne->IDUSER;
	
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



<form action="<?php echo $page; ?>" method="post" name="FGroups_list" id="FGroups_list">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Groups_list</div></td>
          </tr>
        <tr>
<td>ID</td>
<td><input type="text" size="30" name="EIDGRPLST" id="EIDGRPLST" value="<?php echo htmlentities($IDGRPLST, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>User</td>
<td><input type="text" size="30" name="EIDUSER" id="EIDUSER" value="<?php echo htmlentities($IDUSER, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Insertion Date</td>
<td><input type="text" size="30" name="EGRPLST_DATEINS" id="EGRPLST_DATEINS" value="<?php echo htmlentities($GRPLST_DATEINS, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Active</td>
<td><input type="checkbox" name="EGRPLST_ACTIVE"  id="EGRPLST_ACTIVE" <?php if ($GRPLST_ACTIVE == "1")  echo "checked=\"checked\""; ?>></td>
</tr>

      						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		<input id="EIDGRPLST_ori" name="EIDGRPLST_ori" type="hidden"  value="<?php echo $IDGRPLST_ori; ?>">
		
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
