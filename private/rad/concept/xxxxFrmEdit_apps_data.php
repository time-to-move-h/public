<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Apps_data

// Inclusion de bibliothèque
require('../../CLASS/IO/DatabaseCoreClass.php');
require('../../CLASS/IO/DataUtilsClass.php');
require('DBApplicationClass.php');

// Instanciation
$data = new DBApplication();
$util = new DataUtils();

// Connexion Mysql
$data->connecterDB();

// Initialisation des Champs			

$DESCRIPTION = '';
$DESCRIPTION_ori = '';
$IDAPP = '';
$IDAPP_ori = '';
$IDAPPDAT = '';
$IDAPPDAT_ori = '';
$IDENG = '';
$IDENG_ori = '';
$IDTYPDAT = '';
$IDTYPDAT_ori = '';
$LIBELLE = '';
$LIBELLE_ori = '';
$REQUETE = '';
$REQUETE_ori = '';
					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function ajouterApps_data($IDAPP,$IDAPPDAT,$IDTYPDAT,$LIBELLE,$DESCRIPTION,$REQUETE,$IDENG) {
	global $data;			
		
	$IDAPP = addslashes($IDAPP);
	$IDAPPDAT = addslashes($IDAPPDAT);
	$LIBELLE = addslashes($LIBELLE);
	$DESCRIPTION = addslashes($DESCRIPTION);
	$REQUETE = addslashes($REQUETE);
			
	$strSql = "INSERT INTO apps_data (DESCRIPTION, IDAPP, IDAPPDAT, IDENG, IDTYPDAT, LIBELLE, REQUETE) VALUES (";
	$strSql .= ((strlen($DESCRIPTION) <= 0) ? "NULL" :  "'$DESCRIPTION'");
	$strSql .= ", " . ((strlen($IDAPP) <= 0) ? "''" :  "'$IDAPP'");
	$strSql .= ", " . ((strlen($IDAPPDAT) <= 0) ? "''" :  "'$IDAPPDAT'");
	$strSql .= ", " . ((strlen($IDENG) <= 0) ? "0" :  $IDENG);
	$strSql .= ", " . ((strlen($IDTYPDAT) <= 0) ? "0" :  $IDTYPDAT);
	$strSql .= ", " . ((strlen($LIBELLE) <= 0) ? "''" :  "'$LIBELLE'");
	$strSql .= ", " . ((strlen($REQUETE) <= 0) ? "NULL" :  "'$REQUETE'");
	$strSql .= ")";		
	return $data->executeQuery($strSql);
}

// Modification
function modifierApps_data($DESCRIPTION, $IDAPP, $IDAPPDAT, $IDENG, $IDTYPDAT, $LIBELLE, $REQUETE) {
	global $data, $util, $DESCRIPTION_ori, $IDAPP_ori, $IDAPPDAT_ori, $IDENG_ori, $IDTYPDAT_ori, $LIBELLE_ori, $REQUETE_ori;
	$strSql = 'UPDATE apps_data SET ';
	$strSql_Req = '';		

	if (testApps_data($IDAPP_ori, $IDAPPDAT_ori, $IDTYPDAT_ori)) {			
				
	$result = getSingleRowApps_data(	$IDAPP_ori, 	$IDAPPDAT_ori, 	$IDTYPDAT_ori);
	$ligne = $util->getFirstLineObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {
	
		
		$DESCRIPTION_ori = $ligne->DESCRIPTION;
		$IDAPP_ori = $ligne->IDAPP;
		$IDAPPDAT_ori = $ligne->IDAPPDAT;
		$IDENG_ori = $ligne->IDENG;
		$IDTYPDAT_ori = $ligne->IDTYPDAT;
		$LIBELLE_ori = $ligne->LIBELLE;
		$REQUETE_ori = $ligne->REQUETE;
	
	}}
	
	$IDAPP = addslashes($IDAPP);
	$IDAPPDAT = addslashes($IDAPPDAT);
	$LIBELLE = addslashes($LIBELLE);
	$DESCRIPTION = addslashes($DESCRIPTION);
	$REQUETE = addslashes($REQUETE);
	
		
	if ($DESCRIPTION != addslashes($DESCRIPTION_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "DESCRIPTION = '$DESCRIPTION'";
	if ($IDAPP != addslashes($IDAPP_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDAPP = '$IDAPP'";
	if ($IDAPPDAT != addslashes($IDAPPDAT_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDAPPDAT = '$IDAPPDAT'";
	if ($IDENG != addslashes($IDENG_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDENG = '$IDENG'";
	if ($IDTYPDAT != addslashes($IDTYPDAT_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDTYPDAT = '$IDTYPDAT'";
	if ($LIBELLE != addslashes($LIBELLE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "LIBELLE = '$LIBELLE'";
	if ($REQUETE != addslashes($REQUETE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "REQUETE = '$REQUETE'";
		

	$strSql .= $strSql_Req . " WHERE IDAPP = '$IDAPP_ori' AND IDAPPDAT = '$IDAPPDAT_ori' AND IDTYPDAT = $IDTYPDAT_ori";	
	//echo $strSql;
	return $data->executeQuery($strSql);
}

// Test d'existence
function testApps_data($IDAPP, $IDAPPDAT, $IDTYPDAT) {
	global $data;
	$strSql = "SELECT 1 FROM apps_data WHERE  IDAPP = '$IDAPP' AND IDAPPDAT = '$IDAPPDAT' AND IDTYPDAT = $IDTYPDAT ;";
	$res = $data->executeQuery($strSql);
	return $data->testCommun($res);
}

// Récupération enregistrement
function getSingleRowApps_data($IDAPP, $IDAPPDAT, $IDTYPDAT) {
	global $data;
	$strSql = "SELECT * FROM apps_data WHERE IDAPP = '$IDAPP' AND IDAPPDAT = '$IDAPPDAT' AND IDTYPDAT = $IDTYPDAT;";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$DESCRIPTION = trim(isset($_POST['EDESCRIPTION']) ? $_POST['EDESCRIPTION'] : '');
$IDAPP = trim(isset($_POST['EIDAPP']) ? $_POST['EIDAPP'] : '');
$IDAPPDAT = trim(isset($_POST['EIDAPPDAT']) ? $_POST['EIDAPPDAT'] : '');
$IDENG = trim(isset($_POST['EIDENG']) ? $_POST['EIDENG'] : '');
$IDTYPDAT = trim(isset($_POST['EIDTYPDAT']) ? $_POST['EIDTYPDAT'] : '');
$LIBELLE = trim(isset($_POST['ELIBELLE']) ? $_POST['ELIBELLE'] : '');
$REQUETE = trim(isset($_POST['EREQUETE']) ? $_POST['EREQUETE'] : '');
		

$IDAPP_ori = trim(isset($_POST['EIDAPP_ori']) ? $_POST['EIDAPP_ori'] : '');
$IDAPPDAT_ori = trim(isset($_POST['EIDAPPDAT_ori']) ? $_POST['EIDAPPDAT_ori'] : '');
$IDTYPDAT_ori = trim(isset($_POST['EIDTYPDAT_ori']) ? $_POST['EIDTYPDAT_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! testApps_data($IDAPP, $IDAPPDAT, $IDTYPDAT)) {
					
if (ajouterApps_data($IDAPP,$IDAPPDAT,$IDTYPDAT,$LIBELLE,$DESCRIPTION,$REQUETE,$IDENG)) {
		$message = "Insertion OK";	
	} 
}
	break;
	
	case 1 :			

	
if (testApps_data($IDAPP, $IDAPPDAT, $IDTYPDAT)) {
		
if (modifierApps_data($DESCRIPTION, $IDAPP, $IDAPPDAT, $IDENG, $IDTYPDAT, $LIBELLE, $REQUETE)) {				
		$message = "Modification OK";	
		} 
	}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDAPP"]) && isset($_GET["EIDAPPDAT"]) && isset($_GET["EIDTYPDAT"])) {

$IDAPP= $_GET["EIDAPP"]; 
$IDAPPDAT= $_GET["EIDAPPDAT"]; 
$IDTYPDAT= $_GET["EIDTYPDAT"]; 
				
if (testApps_data($IDAPP, $IDAPPDAT, $IDTYPDAT)) {			
				
$result = getSingleRowApps_data($IDAPP, $IDAPPDAT, $IDTYPDAT);
$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
	$DESCRIPTION = $DESCRIPTION_ori = $ligne->DESCRIPTION;
	$IDAPP = $IDAPP_ori = $ligne->IDAPP;
	$IDAPPDAT = $IDAPPDAT_ori = $ligne->IDAPPDAT;
	$IDENG = $IDENG_ori = $ligne->IDENG;
	$IDTYPDAT = $IDTYPDAT_ori = $ligne->IDTYPDAT;
	$LIBELLE = $LIBELLE_ori = $ligne->LIBELLE;
	$REQUETE = $REQUETE_ori = $ligne->REQUETE;
	
}			
$mode = 1;
}}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>NewsSource</title>
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
    <h1 class="fontface" id="title">Loan System</h1>
	<!--end title-->
  </div>    
	</header>
	<!--end header--> 
	<nav>
		<?php require_once('menu.inc.php'); ?>
    </nav>
	<div id="wrapper"><!-- #wrapper -->
	<section id="main"><!-- #main content and sidebar area --><!-- end of sidebar1 --><!-- end of sidebar -->

<form action="<?php echo $page; ?>" method="post" name="FApps_data" id="FApps_data">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Apps_data</div></td>
          </tr>
        <tr>
<td>ID APP</td>
<td><input type="text" size="30" name="EIDAPP" id="EIDAPP" value="<?php echo htmlentities($IDAPP, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>ID Data</td>
<td><input type="text" size="30" name="EIDAPPDAT" id="EIDAPPDAT" value="<?php echo htmlentities($IDAPPDAT, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Type Data</td>
<td><input type="text" size="30" name="EIDTYPDAT" id="EIDTYPDAT" value="<?php echo htmlentities($IDTYPDAT, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Libelle</td>
<td><input type="text" size="30" name="ELIBELLE" id="ELIBELLE" value="<?php echo htmlentities($LIBELLE, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Description</td>
<td><input type="text" size="30" name="EDESCRIPTION" id="EDESCRIPTION" value="<?php echo htmlentities($DESCRIPTION, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Requete</td>
<td><input type="text" size="30" name="EREQUETE" id="EREQUETE" value="<?php echo htmlentities($REQUETE, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Engine</td>
<td><input type="text" size="30" name="EIDENG" id="EIDENG" value="<?php echo htmlentities($IDENG, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>

      						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		<input id="EIDAPP_ori" name="EIDAPP_ori" type="hidden"  value="<?php echo $IDAPP_ori; ?>"><input id="EIDAPPDAT_ori" name="EIDAPPDAT_ori" type="hidden"  value="<?php echo $IDAPPDAT_ori; ?>"><input id="EIDTYPDAT_ori" name="EIDTYPDAT_ori" type="hidden"  value="<?php echo $IDTYPDAT_ori; ?>">
		
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