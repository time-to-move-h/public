<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Lnklist

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
$IDLST = '';
$IDLST_ori = '';
$IDLSTLNK = '';
$IDLSTLNK_ori = '';
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
function ajouterLnklist($IDAPP,$IDLST,$IDLSTLNK,$NAME,$LABEL,$DESCR,$LINK,$PARAMS,$VISIBLE,$NUMSEQ) {
	global $data;			
		
	$IDAPP = addslashes($IDAPP);
	$IDLST = addslashes($IDLST);
	$IDLSTLNK = addslashes($IDLSTLNK);
	$NAME = addslashes($NAME);
	$LABEL = addslashes($LABEL);
	$DESCR = addslashes($DESCR);
	$LINK = addslashes($LINK);
	$PARAMS = addslashes($PARAMS);
			
	$strSql = "INSERT INTO lnklist (DESCR, IDAPP, IDLST, IDLSTLNK, LABEL, LINK, NAME, NUMSEQ, PARAMS, VISIBLE) VALUES (";
	$strSql .= ((strlen($DESCR) <= 0) ? "NULL" :  "'$DESCR'");
	$strSql .= ", " . ((strlen($IDAPP) <= 0) ? "''" :  "'$IDAPP'");
	$strSql .= ", " . ((strlen($IDLST) <= 0) ? "''" :  "'$IDLST'");
	$strSql .= ", " . ((strlen($IDLSTLNK) <= 0) ? "''" :  "'$IDLSTLNK'");
	$strSql .= ", " . ((strlen($LABEL) <= 0) ? "NULL" :  "'$LABEL'");
	$strSql .= ", " . ((strlen($LINK) <= 0) ? "NULL" :  "'$LINK'");
	$strSql .= ", " . ((strlen($NAME) <= 0) ? "NULL" :  "'$NAME'");
	$strSql .= ", " . ((strlen($NUMSEQ) <= 0) ? "NULL" :  $NUMSEQ);
	$strSql .= ", " . ((strlen($PARAMS) <= 0) ? "NULL" :  "'$PARAMS'");
	$strSql .= ", " . ((strlen($VISIBLE) <= 0) ? "0" :  $VISIBLE);
	$strSql .= ")";		
	return $data->executeQuery($strSql);
}

// Modification
function modifierLnklist($DESCR, $IDAPP, $IDLST, $IDLSTLNK, $LABEL, $LINK, $NAME, $NUMSEQ, $PARAMS, $VISIBLE) {
	global $data, $util, $DESCR_ori, $IDAPP_ori, $IDLST_ori, $IDLSTLNK_ori, $LABEL_ori, $LINK_ori, $NAME_ori, $NUMSEQ_ori, $PARAMS_ori, $VISIBLE_ori;
	$strSql = 'UPDATE lnklist SET ';
	$strSql_Req = '';		

	if (testLnklist($IDAPP_ori, $IDLST_ori, $IDLSTLNK_ori)) {			
				
	$result = getSingleRowLnklist(	$IDAPP_ori, 	$IDLST_ori, 	$IDLSTLNK_ori);
	$ligne = $util->getFirstLineObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {
	
		
		$DESCR_ori = $ligne->DESCR;
		$IDAPP_ori = $ligne->IDAPP;
		$IDLST_ori = $ligne->IDLST;
		$IDLSTLNK_ori = $ligne->IDLSTLNK;
		$LABEL_ori = $ligne->LABEL;
		$LINK_ori = $ligne->LINK;
		$NAME_ori = $ligne->NAME;
		$NUMSEQ_ori = $ligne->NUMSEQ;
		$PARAMS_ori = $ligne->PARAMS;
		$VISIBLE_ori = $ligne->VISIBLE;
	
	}}
	
	$IDAPP = addslashes($IDAPP);
	$IDLST = addslashes($IDLST);
	$IDLSTLNK = addslashes($IDLSTLNK);
	$NAME = addslashes($NAME);
	$LABEL = addslashes($LABEL);
	$DESCR = addslashes($DESCR);
	$LINK = addslashes($LINK);
	$PARAMS = addslashes($PARAMS);
	
		
	if ($DESCR != addslashes($DESCR_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "DESCR = '$DESCR'";
	if ($IDAPP != addslashes($IDAPP_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDAPP = '$IDAPP'";
	if ($IDLST != addslashes($IDLST_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDLST = '$IDLST'";
	if ($IDLSTLNK != addslashes($IDLSTLNK_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDLSTLNK = '$IDLSTLNK'";
	if ($LABEL != addslashes($LABEL_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "LABEL = '$LABEL'";
	if ($LINK != addslashes($LINK_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "LINK = '$LINK'";
	if ($NAME != addslashes($NAME_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "NAME = '$NAME'";
	if ($NUMSEQ != addslashes($NUMSEQ_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "NUMSEQ = '$NUMSEQ'";
	if ($PARAMS != addslashes($PARAMS_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "PARAMS = '$PARAMS'";
	if ($VISIBLE != addslashes($VISIBLE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "VISIBLE = '$VISIBLE'";
		

	$strSql .= $strSql_Req . " WHERE IDAPP = '$IDAPP_ori' AND IDLST = '$IDLST_ori' AND IDLSTLNK = '$IDLSTLNK_ori'";	
	//echo $strSql;
	return $data->executeQuery($strSql);
}

// Test d'existence
function testLnklist($IDAPP, $IDLST, $IDLSTLNK) {
	global $data;
	$strSql = "SELECT 1 FROM lnklist WHERE  IDAPP = '$IDAPP' AND IDLST = '$IDLST' AND IDLSTLNK = '$IDLSTLNK' ;";
	$res = $data->executeQuery($strSql);
	return $data->testCommun($res);
}

// Récupération enregistrement
function getSingleRowLnklist($IDAPP, $IDLST, $IDLSTLNK) {
	global $data;
	$strSql = "SELECT * FROM lnklist WHERE IDAPP = '$IDAPP' AND IDLST = '$IDLST' AND IDLSTLNK = '$IDLSTLNK';";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$DESCR = trim(isset($_POST['EDESCR']) ? $_POST['EDESCR'] : '');
$IDAPP = trim(isset($_POST['EIDAPP']) ? $_POST['EIDAPP'] : '');
$IDLST = trim(isset($_POST['EIDLST']) ? $_POST['EIDLST'] : '');
$IDLSTLNK = trim(isset($_POST['EIDLSTLNK']) ? $_POST['EIDLSTLNK'] : '');
$LABEL = trim(isset($_POST['ELABEL']) ? $_POST['ELABEL'] : '');
$LINK = trim(isset($_POST['ELINK']) ? $_POST['ELINK'] : '');
$NAME = trim(isset($_POST['ENAME']) ? $_POST['ENAME'] : '');
$NUMSEQ = trim(isset($_POST['ENUMSEQ']) ? $_POST['ENUMSEQ'] : '');
$PARAMS = trim(isset($_POST['EPARAMS']) ? $_POST['EPARAMS'] : '');
$VISIBLE = trim(isset($_POST['EVISIBLE']) ? 1 : 0);
		

$IDAPP_ori = trim(isset($_POST['EIDAPP_ori']) ? $_POST['EIDAPP_ori'] : '');
$IDLST_ori = trim(isset($_POST['EIDLST_ori']) ? $_POST['EIDLST_ori'] : '');
$IDLSTLNK_ori = trim(isset($_POST['EIDLSTLNK_ori']) ? $_POST['EIDLSTLNK_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! testLnklist($IDAPP, $IDLST, $IDLSTLNK)) {
					
if (ajouterLnklist($IDAPP,$IDLST,$IDLSTLNK,$NAME,$LABEL,$DESCR,$LINK,$PARAMS,$VISIBLE,$NUMSEQ)) {
		$message = "Insertion OK";	
	} 
}
	break;
	
	case 1 :			

	
if (testLnklist($IDAPP, $IDLST, $IDLSTLNK)) {
		
if (modifierLnklist($DESCR, $IDAPP, $IDLST, $IDLSTLNK, $LABEL, $LINK, $NAME, $NUMSEQ, $PARAMS, $VISIBLE)) {				
		$message = "Modification OK";	
		} 
	}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDAPP"]) && isset($_GET["EIDLST"]) && isset($_GET["EIDLSTLNK"])) {

$IDAPP= $_GET["EIDAPP"]; 
$IDLST= $_GET["EIDLST"]; 
$IDLSTLNK= $_GET["EIDLSTLNK"]; 
				
if (testLnklist($IDAPP, $IDLST, $IDLSTLNK)) {			
				
$result = getSingleRowLnklist($IDAPP, $IDLST, $IDLSTLNK);
$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
	$DESCR = $DESCR_ori = $ligne->DESCR;
	$IDAPP = $IDAPP_ori = $ligne->IDAPP;
	$IDLST = $IDLST_ori = $ligne->IDLST;
	$IDLSTLNK = $IDLSTLNK_ori = $ligne->IDLSTLNK;
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

<form action="<?php echo $page; ?>" method="post" name="FLnklist" id="FLnklist">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Lnklist</div></td>
          </tr>
        <tr>
<td>ID Application</td>
<td><input type="text" size="30" name="EIDAPP" id="EIDAPP" value="<?php echo htmlentities($IDAPP, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>ID Liste</td>
<td><input type="text" size="30" name="EIDLST" id="EIDLST" value="<?php echo htmlentities($IDLST, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>ID Link</td>
<td><input type="text" size="30" name="EIDLSTLNK" id="EIDLSTLNK" value="<?php echo htmlentities($IDLSTLNK, ENT_COMPAT,'UTF-8', true); ?>"></td>
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
		
		<input id="EIDAPP_ori" name="EIDAPP_ori" type="hidden"  value="<?php echo $IDAPP_ori; ?>"><input id="EIDLST_ori" name="EIDLST_ori" type="hidden"  value="<?php echo $IDLST_ori; ?>"><input id="EIDLSTLNK_ori" name="EIDLSTLNK_ori" type="hidden"  value="<?php echo $IDLSTLNK_ori; ?>">
		
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