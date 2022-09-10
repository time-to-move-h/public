<?php

// @author Moviao Inc
// Tous Droits réservés 2015.
// Fiche Modifier 
// Table : apps_lnkdata

// Inclusion de bibliothèque
require('../../lib/IO/DatabaseCoreClass.php');
require('../../lib/IO/DataUtilsClass.php');
require('DBApplicationClass.php');
// Instanciation
$data = new DBApplication();
$util = new DataUtils();
// Connexion Mysql
$data->connecterDB();
// Initialisation des Champs			

$DESCR = '';
$DESCR_ori = '';
$IDAPP = '';
$IDAPP_ori = '';
$IDAPPDAT = '';
$IDAPPDAT_ori = '';
$IDAPPDATLNK = '';
$IDAPPDATLNK_ori = '';
$LABEL = '';
$LABEL_ori = '';
$LINK = '';
$LINK_ori = '';
$NAME = '';
$NAME_ori = '';
$NUMSEQ = '';
$NUMSEQ_ori = '';
$PARAMS = '';
$PARAMS_ori = '';
$VISIBLE = '';
$VISIBLE_ori = '';
					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function add($IDAPP,$IDAPPDAT,$IDAPPDATLNK,$NAME,$LABEL,$DESCR,$LINK,$PARAMS,$VISIBLE,$NUMSEQ) {
	global $data,$util;			
		
	$IDAPP = addslashes($IDAPP);
	$IDAPPDAT = addslashes($IDAPPDAT);
	$IDAPPDATLNK = addslashes($IDAPPDATLNK);
	$NAME = addslashes($NAME);
	$LABEL = addslashes($LABEL);
	$DESCR = addslashes($DESCR);
	$LINK = addslashes($LINK);
	$PARAMS = addslashes($PARAMS);
			
	$strSql = "INSERT INTO apps_lnkdata (DESCR, IDAPP, IDAPPDAT, IDAPPDATLNK, LABEL, LINK, NAME, NUMSEQ, PARAMS, VISIBLE) VALUES (";
	$strSql .= ((strlen($DESCR) <= 0) ? "NULL" :  "'$DESCR'");
	$strSql .= ", " . ((strlen($IDAPP) <= 0) ? "''" :  "'$IDAPP'");
	$strSql .= ", " . ((strlen($IDAPPDAT) <= 0) ? "0" :  $IDAPPDAT);
	$strSql .= ", " . ((strlen($IDAPPDATLNK) <= 0) ? "''" :  "'$IDAPPDATLNK'");
	$strSql .= ", " . ((strlen($LABEL) <= 0) ? "NULL" :  "'$LABEL'");
	$strSql .= ", " . ((strlen($LINK) <= 0) ? "NULL" :  "'$LINK'");
	$strSql .= ", " . ((strlen($NAME) <= 0) ? "NULL" :  "'$NAME'");
	$strSql .= ", " . ((strlen($NUMSEQ) <= 0) ? "NULL" :  $NUMSEQ);
	$strSql .= ", " . ((strlen($PARAMS) <= 0) ? "NULL" :  "'$PARAMS'");
	$strSql .= ", " . ((strlen($VISIBLE) <= 0) ? "0" :  $VISIBLE);
	$strSql .= ")";		
	$data->executeQuery($strSql);
        return ($util->RowsAffected($data->getConnexion()) <= 0) ? FALSE : TRUE;
}

// Modification
function modify($DESCR, $IDAPP, $IDAPPDAT, $IDAPPDATLNK, $LABEL, $LINK, $NAME, $NUMSEQ, $PARAMS, $VISIBLE) {
	global $data, $util, $DESCR_ori, $IDAPP_ori, $IDAPPDAT_ori, $IDAPPDATLNK_ori, $LABEL_ori, $LINK_ori, $NAME_ori, $NUMSEQ_ori, $PARAMS_ori, $VISIBLE_ori;
	$strSql = 'UPDATE apps_lnkdata SET ';
	$strSql_Req = '';		

	if (test($IDAPP_ori, $IDAPPDAT_ori, $IDAPPDATLNK_ori)) {			
				
	$result = getSingleRow(	$IDAPP_ori, 	$IDAPPDAT_ori, 	$IDAPPDATLNK_ori);
	$ligne = $util->getFirstLineObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {
		$DESCR_ori = $ligne->DESCR;
		$IDAPP_ori = $ligne->IDAPP;
		$IDAPPDAT_ori = $ligne->IDAPPDAT;
		$IDAPPDATLNK_ori = $ligne->IDAPPDATLNK;
		$LABEL_ori = $ligne->LABEL;
		$LINK_ori = $ligne->LINK;
		$NAME_ori = $ligne->NAME;
		$NUMSEQ_ori = $ligne->NUMSEQ;
		$PARAMS_ori = $ligne->PARAMS;
		$VISIBLE_ori = $ligne->VISIBLE;
	
	}}
	
	$IDAPP = addslashes($IDAPP);
	$IDAPPDAT = addslashes($IDAPPDAT);
	$IDAPPDATLNK = addslashes($IDAPPDATLNK);
	$NAME = addslashes($NAME);
	$LABEL = addslashes($LABEL);
	$DESCR = addslashes($DESCR);
	$LINK = addslashes($LINK);
	$PARAMS = addslashes($PARAMS);
	
		
	if ($DESCR != addslashes($DESCR_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "DESCR = '$DESCR'";
	if ($IDAPP != addslashes($IDAPP_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDAPP = '$IDAPP'";
	if ($IDAPPDAT != addslashes($IDAPPDAT_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDAPPDAT = '$IDAPPDAT'";
	if ($IDAPPDATLNK != addslashes($IDAPPDATLNK_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDAPPDATLNK = '$IDAPPDATLNK'";
	if ($LABEL != addslashes($LABEL_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "LABEL = '$LABEL'";
	if ($LINK != addslashes($LINK_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "LINK = '$LINK'";
	if ($NAME != addslashes($NAME_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "NAME = '$NAME'";
	if ($NUMSEQ != addslashes($NUMSEQ_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "NUMSEQ = '$NUMSEQ'";
	if ($PARAMS != addslashes($PARAMS_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "PARAMS = '$PARAMS'";
	if ($VISIBLE != addslashes($VISIBLE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "VISIBLE = '$VISIBLE'";
	
	$strSql .= $strSql_Req . " WHERE IDAPP = '$IDAPP_ori' AND IDAPPDAT = $IDAPPDAT_ori AND IDAPPDATLNK = '$IDAPPDATLNK_ori'";	
	//echo $strSql;
	$data->executeQuery($strSql);
        return ($util->RowsAffected($data->getConnexion()) <= 0) ? FALSE : TRUE;
}

// Test d'existence
function test($IDAPP, $IDAPPDAT, $IDAPPDATLNK) {
	global $data;
	$strSql = "SELECT 1 FROM apps_lnkdata WHERE  IDAPP = '$IDAPP' AND IDAPPDAT = $IDAPPDAT AND IDAPPDATLNK = '$IDAPPDATLNK' ;";
	$res = $data->executeQuery($strSql);
	return $data->testCommun($res);
}

// Récupération enregistrement
function getSingleRow($IDAPP, $IDAPPDAT, $IDAPPDATLNK) {
	global $data;
	$strSql = "SELECT * FROM apps_lnkdata WHERE IDAPP = '$IDAPP' AND IDAPPDAT = $IDAPPDAT AND IDAPPDATLNK = '$IDAPPDATLNK';";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$DESCR = trim(isset($_POST['EDESCR']) ? $_POST['EDESCR'] : '');
$IDAPP = trim(isset($_POST['EIDAPP']) ? $_POST['EIDAPP'] : '');
$IDAPPDAT = trim(isset($_POST['EIDAPPDAT']) ? $_POST['EIDAPPDAT'] : '');
$IDAPPDATLNK = trim(isset($_POST['EIDAPPDATLNK']) ? $_POST['EIDAPPDATLNK'] : '');
$LABEL = trim(isset($_POST['ELABEL']) ? $_POST['ELABEL'] : '');
$LINK = trim(isset($_POST['ELINK']) ? $_POST['ELINK'] : '');
$NAME = trim(isset($_POST['ENAME']) ? $_POST['ENAME'] : '');
$NUMSEQ = trim(isset($_POST['ENUMSEQ']) ? $_POST['ENUMSEQ'] : '');
$PARAMS = trim(isset($_POST['EPARAMS']) ? $_POST['EPARAMS'] : '');
$VISIBLE = trim(isset($_POST['EVISIBLE']) ? 1 : 0);
		

$IDAPP_ori = trim(isset($_POST['EIDAPP_ori']) ? $_POST['EIDAPP_ori'] : '');
$IDAPPDAT_ori = trim(isset($_POST['EIDAPPDAT_ori']) ? $_POST['EIDAPPDAT_ori'] : '');
$IDAPPDATLNK_ori = trim(isset($_POST['EIDAPPDATLNK_ori']) ? $_POST['EIDAPPDATLNK_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! test($IDAPP, $IDAPPDAT, $IDAPPDATLNK)) {					
    if (add($IDAPP,$IDAPPDAT,$IDAPPDATLNK,$NAME,$LABEL,$DESCR,$LINK,$PARAMS,$VISIBLE,$NUMSEQ)) {
        $message = "Insertion OK";	
    } 
}
	break;
	
	case 1 :			

	
if (test($IDAPP, $IDAPPDAT, $IDAPPDATLNK)) {		
    if (modify($DESCR, $IDAPP, $IDAPPDAT, $IDAPPDATLNK, $LABEL, $LINK, $NAME, $NUMSEQ, $PARAMS, $VISIBLE)) {				
            $message = "Modification OK";	
        } 
}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDAPP"]) && isset($_GET["EIDAPPDAT"]) && isset($_GET["EIDAPPDATLNK"])) {

$IDAPP= $_GET["EIDAPP"]; 
$IDAPPDAT= $_GET["EIDAPPDAT"]; 
$IDAPPDATLNK= $_GET["EIDAPPDATLNK"]; 
				
if (test($IDAPP, $IDAPPDAT, $IDAPPDATLNK)) {			
				
$result = getSingleRow($IDAPP, $IDAPPDAT, $IDAPPDATLNK);
$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
  $DESCR = $DESCR_ori = $ligne->DESCR;
  $IDAPP = $IDAPP_ori = $ligne->IDAPP;
  $IDAPPDAT = $IDAPPDAT_ori = $ligne->IDAPPDAT;
  $IDAPPDATLNK = $IDAPPDATLNK_ori = $ligne->IDAPPDATLNK;
  $LABEL = $LABEL_ori = $ligne->LABEL;
  $LINK = $LINK_ori = $ligne->LINK;
  $NAME = $NAME_ori = $ligne->NAME;
  $NUMSEQ = $NUMSEQ_ori = $ligne->NUMSEQ;
  $PARAMS = $PARAMS_ori = $ligne->PARAMS;
  $VISIBLE = $VISIBLE_ori = $ligne->VISIBLE;
	
}			
$mode = 1;
}}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Edit apps_lnkdata</title>
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
    <h1 class="fontface" id="title">The Generator</h1>
	<!--end title-->
  </div>    
	</header>
	<!--end header--> 
	<nav>
		<?php require_once('menu.inc.php'); ?>
    </nav>
	<div id="wrapper"><!-- #wrapper -->
	<section id="main"><!-- #main content and sidebar area --><!-- end of sidebar1 --><!-- end of sidebar -->

<form action="<?php echo $page; ?>" method="post" name="FApps_lnkdata" id="FApps_lnkdata">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Liens Form</div></td>
          </tr>
        <tr>
<td>ID Application</td>
<td><input type="text" size="30" name="EIDAPP" id="EIDAPP" value="<?php echo htmlentities($IDAPP, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>ID Template</td>
<td><input type="text" size="30" name="EIDAPPDAT" id="EIDAPPDAT" value="<?php echo htmlentities($IDAPPDAT, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>ID Link</td>
<td><input type="text" size="30" name="EIDAPPDATLNK" id="EIDAPPDATLNK" value="<?php echo htmlentities($IDAPPDATLNK, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Nom</td>
<td><input type="text" size="30" name="ENAME" id="ENAME" value="<?php echo htmlentities($NAME, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Libelle</td>
<td><input type="text" size="30" name="ELABEL" id="ELABEL" value="<?php echo htmlentities($LABEL, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Description</td>
<td><input type="text" size="30" name="EDESCR" id="EDESCR" value="<?php echo htmlentities($DESCR, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Lien</td>
<td><input type="text" size="30" name="ELINK" id="ELINK" value="<?php echo htmlentities($LINK, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Parametres</td>
<td><input type="text" size="30" name="EPARAMS" id="EPARAMS" value="<?php echo htmlentities($PARAMS, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Visible</td>
<td><input type="checkbox" name="EVISIBLE"  id="EVISIBLE" <?php if ($VISIBLE == "1")  echo "checked=\"checked\""; ?>></td>
</tr>
<tr>
<td>No Ordre</td>
<td><input type="text" size="30" name="ENUMSEQ" id="ENUMSEQ" value="<?php echo htmlentities($NUMSEQ, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>

      						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		<input id="EIDAPP_ori" name="EIDAPP_ori" type="hidden"  value="<?php echo $IDAPP_ori; ?>"><input id="EIDAPPDAT_ori" name="EIDAPPDAT_ori" type="hidden"  value="<?php echo $IDAPPDAT_ori; ?>"><input id="EIDAPPDATLNK_ori" name="EIDAPPDATLNK_ori" type="hidden"  value="<?php echo $IDAPPDATLNK_ori; ?>">
		
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