<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Events

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

$EVT_BOX = '';
$EVT_BOX_ori = '';
$EVT_CITY = '';
$EVT_CITY_ori = '';
$EVT_COUNTRY = '';
$EVT_COUNTRY_ori = '';
$EVT_DATBEG = '';
$EVT_DATBEG_ori = '';
$EVT_DATEND = '';
$EVT_DATEND_ori = '';
$EVT_DESC = '';
$EVT_DESC_ori = '';
$EVT_DESCL = '';
$EVT_DESCL_ori = '';
$EVT_DESCRDV = '';
$EVT_DESCRDV_ori = '';
$EVT_FREE = '';
$EVT_FREE_ori = '';
$EVT_MAXUSE = '';
$EVT_MAXUSE_ori = '';
$EVT_MULTI = '';
$EVT_MULTI_ori = '';
$EVT_PCODE = '';
$EVT_PCODE_ori = '';
$EVT_STREET = '';
$EVT_STREET_ori = '';
$EVT_STREETN = '';
$EVT_STREETN_ori = '';
$IDEVT = '';
$IDEVT_ori = '';
$IDGRP = '';
$IDGRP_ori = '';
					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function ajouterEvents($IDGRP,$EVT_DESC,$EVT_DESCL,$EVT_DATBEG,$EVT_DATEND,$EVT_FREE,$EVT_MAXUSE,$EVT_MULTI,$EVT_STREET,$EVT_STREETN,$EVT_BOX,$EVT_PCODE,$EVT_CITY,$EVT_COUNTRY,$EVT_DESCRDV) {
	global $data;			
		
	$EVT_DESC = addslashes($EVT_DESC);
	$EVT_DESCL = addslashes($EVT_DESCL);
	$EVT_DATBEG = addslashes($EVT_DATBEG);
	$EVT_DATEND = addslashes($EVT_DATEND);
	$EVT_FREE = addslashes($EVT_FREE);
	$EVT_STREET = addslashes($EVT_STREET);
	$EVT_STREETN = addslashes($EVT_STREETN);
	$EVT_BOX = addslashes($EVT_BOX);
	$EVT_PCODE = addslashes($EVT_PCODE);
	$EVT_CITY = addslashes($EVT_CITY);
	$EVT_DESCRDV = addslashes($EVT_DESCRDV);
			
	$strSql = "INSERT INTO events (EVT_BOX, EVT_CITY, EVT_COUNTRY, EVT_DATBEG, EVT_DATEND, EVT_DESC, EVT_DESCL, EVT_DESCRDV, EVT_FREE, EVT_MAXUSE, EVT_MULTI, EVT_PCODE, EVT_STREET, EVT_STREETN, IDGRP) VALUES (";
	$strSql .= ((strlen($EVT_BOX) <= 0) ? "''" :  "'$EVT_BOX'");
	$strSql .= ", " . ((strlen($EVT_CITY) <= 0) ? "''" :  "'$EVT_CITY'");
	$strSql .= ", " . ((strlen($EVT_COUNTRY) <= 0) ? "0" :  $EVT_COUNTRY);
	$strSql .= ", " . ((strlen($EVT_DATBEG) <= 0) ? "NULL" :  "'$EVT_DATBEG'");
	$strSql .= ", " . ((strlen($EVT_DATEND) <= 0) ? "NULL" :  "'$EVT_DATEND'");
	$strSql .= ", " . ((strlen($EVT_DESC) <= 0) ? "NULL" :  "'$EVT_DESC'");
	$strSql .= ", " . ((strlen($EVT_DESCL) <= 0) ? "NULL" :  "'$EVT_DESCL'");
	$strSql .= ", " . ((strlen($EVT_DESCRDV) <= 0) ? "''" :  "'$EVT_DESCRDV'");
	$strSql .= ", " . ((strlen($EVT_FREE) <= 0) ? "''" :  "'$EVT_FREE'");
	$strSql .= ", " . ((strlen($EVT_MAXUSE) <= 0) ? "0" :  $EVT_MAXUSE);
	$strSql .= ", " . ((strlen($EVT_MULTI) <= 0) ? "0" :  $EVT_MULTI);
	$strSql .= ", " . ((strlen($EVT_PCODE) <= 0) ? "''" :  "'$EVT_PCODE'");
	$strSql .= ", " . ((strlen($EVT_STREET) <= 0) ? "''" :  "'$EVT_STREET'");
	$strSql .= ", " . ((strlen($EVT_STREETN) <= 0) ? "''" :  "'$EVT_STREETN'");
	$strSql .= ", " . ((strlen($IDGRP) <= 0) ? "NULL" :  $IDGRP);
	$strSql .= ")";		
	return $data->executeQuery($strSql);
}

// Modification
function modifierEvents($EVT_BOX, $EVT_CITY, $EVT_COUNTRY, $EVT_DATBEG, $EVT_DATEND, $EVT_DESC, $EVT_DESCL, $EVT_DESCRDV, $EVT_FREE, $EVT_MAXUSE, $EVT_MULTI, $EVT_PCODE, $EVT_STREET, $EVT_STREETN, $IDEVT, $IDGRP) {
	global $data, $util, $EVT_BOX_ori, $EVT_CITY_ori, $EVT_COUNTRY_ori, $EVT_DATBEG_ori, $EVT_DATEND_ori, $EVT_DESC_ori, $EVT_DESCL_ori, $EVT_DESCRDV_ori, $EVT_FREE_ori, $EVT_MAXUSE_ori, $EVT_MULTI_ori, $EVT_PCODE_ori, $EVT_STREET_ori, $EVT_STREETN_ori, $IDEVT_ori, $IDGRP_ori;
	$strSql = 'UPDATE events SET ';
	$strSql_Req = '';		

	if (testEvents($IDEVT_ori)) {			
				
	$result = getSingleRowEvents(	$IDEVT_ori);
	$ligne = $util->getFirstLineObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {
	
		
		$EVT_BOX_ori = $ligne->EVT_BOX;
		$EVT_CITY_ori = $ligne->EVT_CITY;
		$EVT_COUNTRY_ori = $ligne->EVT_COUNTRY;
		$EVT_DATBEG_ori = $ligne->EVT_DATBEG;
		$EVT_DATEND_ori = $ligne->EVT_DATEND;
		$EVT_DESC_ori = $ligne->EVT_DESC;
		$EVT_DESCL_ori = $ligne->EVT_DESCL;
		$EVT_DESCRDV_ori = $ligne->EVT_DESCRDV;
		$EVT_FREE_ori = $ligne->EVT_FREE;
		$EVT_MAXUSE_ori = $ligne->EVT_MAXUSE;
		$EVT_MULTI_ori = $ligne->EVT_MULTI;
		$EVT_PCODE_ori = $ligne->EVT_PCODE;
		$EVT_STREET_ori = $ligne->EVT_STREET;
		$EVT_STREETN_ori = $ligne->EVT_STREETN;
		$IDEVT_ori = $ligne->IDEVT;
		$IDGRP_ori = $ligne->IDGRP;
	
	}}
	
	$EVT_DESC = addslashes($EVT_DESC);
	$EVT_DESCL = addslashes($EVT_DESCL);
	$EVT_DATBEG = addslashes($EVT_DATBEG);
	$EVT_DATEND = addslashes($EVT_DATEND);
	$EVT_FREE = addslashes($EVT_FREE);
	$EVT_STREET = addslashes($EVT_STREET);
	$EVT_STREETN = addslashes($EVT_STREETN);
	$EVT_BOX = addslashes($EVT_BOX);
	$EVT_PCODE = addslashes($EVT_PCODE);
	$EVT_CITY = addslashes($EVT_CITY);
	$EVT_DESCRDV = addslashes($EVT_DESCRDV);
	
		
	if ($EVT_BOX != addslashes($EVT_BOX_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_BOX = '$EVT_BOX'";
	if ($EVT_CITY != addslashes($EVT_CITY_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_CITY = '$EVT_CITY'";
	if ($EVT_COUNTRY != addslashes($EVT_COUNTRY_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_COUNTRY = '$EVT_COUNTRY'";
	if ($EVT_DATBEG != addslashes($EVT_DATBEG_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_DATBEG = '$EVT_DATBEG'";
	if ($EVT_DATEND != addslashes($EVT_DATEND_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_DATEND = '$EVT_DATEND'";
	if ($EVT_DESC != addslashes($EVT_DESC_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_DESC = '$EVT_DESC'";
	if ($EVT_DESCL != addslashes($EVT_DESCL_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_DESCL = '$EVT_DESCL'";
	if ($EVT_DESCRDV != addslashes($EVT_DESCRDV_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_DESCRDV = '$EVT_DESCRDV'";
	if ($EVT_FREE != addslashes($EVT_FREE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_FREE = '$EVT_FREE'";
	if ($EVT_MAXUSE != addslashes($EVT_MAXUSE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_MAXUSE = '$EVT_MAXUSE'";
	if ($EVT_MULTI != addslashes($EVT_MULTI_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_MULTI = '$EVT_MULTI'";
	if ($EVT_PCODE != addslashes($EVT_PCODE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_PCODE = '$EVT_PCODE'";
	if ($EVT_STREET != addslashes($EVT_STREET_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_STREET = '$EVT_STREET'";
	if ($EVT_STREETN != addslashes($EVT_STREETN_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "EVT_STREETN = '$EVT_STREETN'";
	if ($IDEVT != addslashes($IDEVT_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDEVT = '$IDEVT'";
	if ($IDGRP != addslashes($IDGRP_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDGRP = '$IDGRP'";
		

	$strSql .= $strSql_Req . " WHERE IDEVT = $IDEVT_ori";	
	//echo $strSql;
	return $data->executeQuery($strSql);
}

// Test d'existence
function testEvents($IDEVT) {
	global $data;
	$strSql = "SELECT 1 FROM events WHERE  IDEVT = $IDEVT ;";
	$res = $data->executeQuery($strSql);
	return $data->testCommun($res);
}

// Récupération enregistrement
function getSingleRowEvents($IDEVT) {
	global $data;
	$strSql = "SELECT * FROM events WHERE IDEVT = $IDEVT;";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$EVT_BOX = trim(isset($_POST['EEVT_BOX']) ? $_POST['EEVT_BOX'] : '');
$EVT_CITY = trim(isset($_POST['EEVT_CITY']) ? $_POST['EEVT_CITY'] : '');
$EVT_COUNTRY = trim(isset($_POST['EEVT_COUNTRY']) ? $_POST['EEVT_COUNTRY'] : '');
$EVT_DATBEG = trim(isset($_POST['EEVT_DATBEG']) ? $_POST['EEVT_DATBEG'] : '');
$EVT_DATEND = trim(isset($_POST['EEVT_DATEND']) ? $_POST['EEVT_DATEND'] : '');
$EVT_DESC = trim(isset($_POST['EEVT_DESC']) ? $_POST['EEVT_DESC'] : '');
$EVT_DESCL = trim(isset($_POST['EEVT_DESCL']) ? $_POST['EEVT_DESCL'] : '');
$EVT_DESCRDV = trim(isset($_POST['EEVT_DESCRDV']) ? $_POST['EEVT_DESCRDV'] : '');
$EVT_FREE = trim(isset($_POST['EEVT_FREE']) ? $_POST['EEVT_FREE'] : '');
$EVT_MAXUSE = trim(isset($_POST['EEVT_MAXUSE']) ? $_POST['EEVT_MAXUSE'] : '');
$EVT_MULTI = trim(isset($_POST['EEVT_MULTI']) ? $_POST['EEVT_MULTI'] : '');
$EVT_PCODE = trim(isset($_POST['EEVT_PCODE']) ? $_POST['EEVT_PCODE'] : '');
$EVT_STREET = trim(isset($_POST['EEVT_STREET']) ? $_POST['EEVT_STREET'] : '');
$EVT_STREETN = trim(isset($_POST['EEVT_STREETN']) ? $_POST['EEVT_STREETN'] : '');
$IDEVT = trim(isset($_POST['EIDEVT']) ? $_POST['EIDEVT'] : '');
$IDGRP = trim(isset($_POST['EIDGRP']) ? $_POST['EIDGRP'] : '');
		

$IDEVT_ori = trim(isset($_POST['EIDEVT_ori']) ? $_POST['EIDEVT_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! testEvents($IDEVT)) {
					
if (ajouterEvents($IDGRP,$EVT_DESC,$EVT_DESCL,$EVT_DATBEG,$EVT_DATEND,$EVT_FREE,$EVT_MAXUSE,$EVT_MULTI,$EVT_STREET,$EVT_STREETN,$EVT_BOX,$EVT_PCODE,$EVT_CITY,$EVT_COUNTRY,$EVT_DESCRDV)) {
		$message = "Insertion OK";	
	} 
}
	break;
	
	case 1 :			

	
if (testEvents($IDEVT)) {
		
if (modifierEvents($EVT_BOX, $EVT_CITY, $EVT_COUNTRY, $EVT_DATBEG, $EVT_DATEND, $EVT_DESC, $EVT_DESCL, $EVT_DESCRDV, $EVT_FREE, $EVT_MAXUSE, $EVT_MULTI, $EVT_PCODE, $EVT_STREET, $EVT_STREETN, $IDEVT, $IDGRP)) {				
		$message = "Modification OK";	
		} 
	}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDEVT"])) {

$IDEVT= $_GET["EIDEVT"]; 
				
if (testEvents($IDEVT)) {			
				
$result = getSingleRowEvents($IDEVT);
$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
	$EVT_BOX = $EVT_BOX_ori = $ligne->EVT_BOX;
	$EVT_CITY = $EVT_CITY_ori = $ligne->EVT_CITY;
	$EVT_COUNTRY = $EVT_COUNTRY_ori = $ligne->EVT_COUNTRY;
	$EVT_DATBEG = $EVT_DATBEG_ori = $ligne->EVT_DATBEG;
	$EVT_DATEND = $EVT_DATEND_ori = $ligne->EVT_DATEND;
	$EVT_DESC = $EVT_DESC_ori = $ligne->EVT_DESC;
	$EVT_DESCL = $EVT_DESCL_ori = $ligne->EVT_DESCL;
	$EVT_DESCRDV = $EVT_DESCRDV_ori = $ligne->EVT_DESCRDV;
	$EVT_FREE = $EVT_FREE_ori = $ligne->EVT_FREE;
	$EVT_MAXUSE = $EVT_MAXUSE_ori = $ligne->EVT_MAXUSE;
	$EVT_MULTI = $EVT_MULTI_ori = $ligne->EVT_MULTI;
	$EVT_PCODE = $EVT_PCODE_ori = $ligne->EVT_PCODE;
	$EVT_STREET = $EVT_STREET_ori = $ligne->EVT_STREET;
	$EVT_STREETN = $EVT_STREETN_ori = $ligne->EVT_STREETN;
	$IDEVT = $IDEVT_ori = $ligne->IDEVT;
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



<form action="<?php echo $page; ?>" method="post" name="FEvents" id="FEvents">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Events</div></td>
          </tr>
        <tr>
<td>ID Event</td>
<td><input type="text" size="30" name="EIDEVT" id="EIDEVT" value="<?php echo htmlentities($IDEVT, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>ID Group</td>
<td><input type="text" size="30" name="EIDGRP" id="EIDGRP" value="<?php echo htmlentities($IDGRP, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_DESC" id="EEVT_DESC" value="<?php echo htmlentities($EVT_DESC, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_DESCL" id="EEVT_DESCL" value="<?php echo htmlentities($EVT_DESCL, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_DATBEG" id="EEVT_DATBEG" value="<?php echo htmlentities($EVT_DATBEG, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_DATEND" id="EEVT_DATEND" value="<?php echo htmlentities($EVT_DATEND, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_FREE" id="EEVT_FREE" value="<?php echo htmlentities($EVT_FREE, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_MAXUSE" id="EEVT_MAXUSE" value="<?php echo htmlentities($EVT_MAXUSE, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_MULTI" id="EEVT_MULTI" value="<?php echo htmlentities($EVT_MULTI, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_STREET" id="EEVT_STREET" value="<?php echo htmlentities($EVT_STREET, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_STREETN" id="EEVT_STREETN" value="<?php echo htmlentities($EVT_STREETN, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_BOX" id="EEVT_BOX" value="<?php echo htmlentities($EVT_BOX, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_PCODE" id="EEVT_PCODE" value="<?php echo htmlentities($EVT_PCODE, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_CITY" id="EEVT_CITY" value="<?php echo htmlentities($EVT_CITY, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_COUNTRY" id="EEVT_COUNTRY" value="<?php echo htmlentities($EVT_COUNTRY, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" size="30" name="EEVT_DESCRDV" id="EEVT_DESCRDV" value="<?php echo htmlentities($EVT_DESCRDV, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>

      						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		<input id="EIDEVT_ori" name="EIDEVT_ori" type="hidden"  value="<?php echo $IDEVT_ori; ?>">
		
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
