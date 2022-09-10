<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Events_list

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

$EVTLST_DATBEG = '';
$EVTLST_DATBEG_ori = '';
$EVTLST_N_FRIEND = '';
$EVTLST_N_FRIEND_ori = '';
$EVTLST_ORG = '';
$EVTLST_ORG_ori = '';
$EVTLST_WAIT = '';
$EVTLST_WAIT_ori = '';
$IDEVT = '';
$IDEVT_ori = '';
$IDUSER = '';
$IDUSER_ori = '';
					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function ajouterEvents_list($IDEVT,$IDUSER,$EVTLST_N_FRIEND,$EVTLST_WAIT,$EVTLST_DATBEG,$EVTLST_ORG) {
	global $data;			
		
	$EVTLST_DATBEG = addslashes($EVTLST_DATBEG);
			
	$strSql = "INSERT INTO events_list (EVTLST_DATBEG, EVTLST_N_FRIEND, EVTLST_ORG, EVTLST_WAIT, IDEVT, IDUSER) VALUES (";
	$strSql .= ((strlen($EVTLST_DATBEG) <= 0) ? "''" :  "'$EVTLST_DATBEG'");
	$strSql .= ", " . ((strlen($EVTLST_N_FRIEND) <= 0) ? "0" :  $EVTLST_N_FRIEND);
	$strSql .= ", " . ((strlen($EVTLST_ORG) <= 0) ? "NULL" :  $EVTLST_ORG);
	$strSql .= ", " . ((strlen($EVTLST_WAIT) <= 0) ? "0" :  $EVTLST_WAIT);
	$strSql .= ", " . ((strlen($IDEVT) <= 0) ? "0" :  $IDEVT);
	$strSql .= ", " . ((strlen($IDUSER) <= 0) ? "0" :  $IDUSER);
	$strSql .= ")";		
	return $data->executeQuery($strSql);
}

// Modification
function modifierEvents_list($EVTLST_DATBEG, $EVTLST_N_FRIEND, $EVTLST_ORG, $EVTLST_WAIT, $IDEVT, $IDUSER) {
	global $data, $util, $EVTLST_DATBEG_ori, $EVTLST_N_FRIEND_ori, $EVTLST_ORG_ori, $EVTLST_WAIT_ori, $IDEVT_ori, $IDUSER_ori;
	$strSql = 'UPDATE events_list SET ';
	$strSql_Req = '';		

	if (testEvents_list($IDEVT_ori, $IDUSER_ori)) {			
				
	$result = getSingleRowEvents_list(	$IDEVT_ori, 	$IDUSER_ori);
	$ligne = $util->getFirstLineObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {
	
		
		$EVTLST_DATBEG_ori = $ligne->EVTLST_DATBEG;
		$EVTLST_N_FRIEND_ori = $ligne->EVTLST_N_FRIEND;
		$EVTLST_ORG_ori = $ligne->EVTLST_ORG;
		$EVTLST_WAIT_ori = $ligne->EVTLST_WAIT;
		$IDEVT_ori = $ligne->IDEVT;
		$IDUSER_ori = $ligne->IDUSER;
	
	}}
	
	$EVTLST_DATBEG = addslashes($EVTLST_DATBEG);
	
		
	if ($EVTLST_DATBEG != addslashes($EVTLST_DATBEG_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVTLST_DATBEG = '$EVTLST_DATBEG'";
	if ($EVTLST_N_FRIEND != addslashes($EVTLST_N_FRIEND_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVTLST_N_FRIEND = '$EVTLST_N_FRIEND'";
	if ($EVTLST_ORG != addslashes($EVTLST_ORG_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVTLST_ORG = '$EVTLST_ORG'";
	if ($EVTLST_WAIT != addslashes($EVTLST_WAIT_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVTLST_WAIT = '$EVTLST_WAIT'";
	if ($IDEVT != addslashes($IDEVT_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDEVT = '$IDEVT'";
	if ($IDUSER != addslashes($IDUSER_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDUSER = '$IDUSER'";
		

	$strSql .= $strSql_Req . " WHERE IDEVT = $IDEVT_ori AND IDUSER = $IDUSER_ori";	
	//echo $strSql;
	return $data->executeQuery($strSql);
}

// Test d'existence
function testEvents_list($IDEVT, $IDUSER) {
	global $data;
	$strSql = "SELECT 1 FROM events_list WHERE  IDEVT = $IDEVT AND IDUSER = $IDUSER ;";
	$res = $data->executeQuery($strSql);
	return $data->testCommun($res);
}

// Récupération enregistrement
function getSingleRowEvents_list($IDEVT, $IDUSER) {
	global $data;
	$strSql = "SELECT * FROM events_list WHERE IDEVT = $IDEVT AND IDUSER = $IDUSER;";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$EVTLST_DATBEG = trim(isset($_POST['EEVTLST_DATBEG']) ? $_POST['EEVTLST_DATBEG'] : '');
$EVTLST_N_FRIEND = trim(isset($_POST['EEVTLST_N_FRIEND']) ? $_POST['EEVTLST_N_FRIEND'] : '');
$EVTLST_ORG = trim(isset($_POST['EEVTLST_ORG']) ? 1 : 0);
$EVTLST_WAIT = trim(isset($_POST['EEVTLST_WAIT']) ? 1 : 0);
$IDEVT = trim(isset($_POST['EIDEVT']) ? $_POST['EIDEVT'] : '');
$IDUSER = trim(isset($_POST['EIDUSER']) ? $_POST['EIDUSER'] : '');
		

$IDEVT_ori = trim(isset($_POST['EIDEVT_ori']) ? $_POST['EIDEVT_ori'] : '');
$IDUSER_ori = trim(isset($_POST['EIDUSER_ori']) ? $_POST['EIDUSER_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! testEvents_list($IDEVT, $IDUSER)) {
					
if (ajouterEvents_list($IDEVT,$IDUSER,$EVTLST_N_FRIEND,$EVTLST_WAIT,$EVTLST_DATBEG,$EVTLST_ORG)) {
		$message = "Insertion OK";	
	} 
}
	break;
	
	case 1 :			

	
if (testEvents_list($IDEVT, $IDUSER)) {
		
if (modifierEvents_list($EVTLST_DATBEG, $EVTLST_N_FRIEND, $EVTLST_ORG, $EVTLST_WAIT, $IDEVT, $IDUSER)) {				
		$message = "Modification OK";	
		} 
	}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDEVT"]) && isset($_GET["EIDUSER"])) {

$IDEVT= $_GET["EIDEVT"]; 
$IDUSER= $_GET["EIDUSER"]; 
				
if (testEvents_list($IDEVT, $IDUSER)) {			
				
$result = getSingleRowEvents_list($IDEVT, $IDUSER);
$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
	$EVTLST_DATBEG = $EVTLST_DATBEG_ori = $ligne->EVTLST_DATBEG;
	$EVTLST_N_FRIEND = $EVTLST_N_FRIEND_ori = $ligne->EVTLST_N_FRIEND;
	$EVTLST_ORG = $EVTLST_ORG_ori = $ligne->EVTLST_ORG;
	$EVTLST_WAIT = $EVTLST_WAIT_ori = $ligne->EVTLST_WAIT;
	$IDEVT = $IDEVT_ori = $ligne->IDEVT;
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



<form action="<?php echo $page; ?>" method="post" name="FEvents_list" id="FEvents_list">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Events_list</div></td>
          </tr>
        <tr>
<td>ID Event</td>
<td><input type="text" size="30" name="EIDEVT" id="EIDEVT" value="<?php echo htmlentities($IDEVT, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>ID User</td>
<td><input type="text" size="30" name="EIDUSER" id="EIDUSER" value="<?php echo htmlentities($IDUSER, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Friends Number</td>
<td><input type="text" size="30" name="EEVTLST_N_FRIEND" id="EEVTLST_N_FRIEND" value="<?php echo htmlentities($EVTLST_N_FRIEND, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Waiting List</td>
<td><input type="checkbox" name="EEVTLST_WAIT"  id="EEVTLST_WAIT" <?php if ($EVTLST_WAIT == "1")  echo "checked=\"checked\""; ?>></td>
</tr>
<tr>
<td>Begin Date</td>
<td><input type="text" size="30" name="EEVTLST_DATBEG" id="EEVTLST_DATBEG" value="<?php echo htmlentities($EVTLST_DATBEG, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Organizer</td>
<td><input type="checkbox" name="EEVTLST_ORG"  id="EEVTLST_ORG" <?php if ($EVTLST_ORG == "1")  echo "checked=\"checked\""; ?>></td>
</tr>

      						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		<input id="EIDEVT_ori" name="EIDEVT_ori" type="hidden"  value="<?php echo $IDEVT_ori; ?>"><input id="EIDUSER_ori" name="EIDUSER_ori" type="hidden"  value="<?php echo $IDUSER_ori; ?>">
		
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
