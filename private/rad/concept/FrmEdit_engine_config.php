<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Engine_config

// Inclusion de bibliothèque
require('../../../lib/Moviao/Database/DatabaseCore.php');
//require('../../../lib/Moviao/Database/DataUtils.php');
require('DBApplicationClass.php');

// Instanciation
$data = new \Moviao\Database\DBApplication();
//$util = new \Moviao\Database\DataUtils();

// Connexion Mysql
$data->connecterDB();

// Initialisation des Champs			

$FILEDES = '';
$FILEDES_ori = '';
$FILESOU = '';
$FILESOU_ori = '';
$IDENG = '';
$IDENG_ori = '';
					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function ajouterEngine_config($IDENG,$FILESOU,$FILEDES) {
	global $data;			
		
	$FILESOU = addslashes($FILESOU);
	$FILEDES = addslashes($FILEDES);
			
	$strSql = "INSERT INTO engine_config (FILEDES, FILESOU, IDENG) VALUES (";
	$strSql .= ((strlen($FILEDES) <= 0) ? "''" :  "'$FILEDES'");
	$strSql .= ", " . ((strlen($FILESOU) <= 0) ? "''" :  "'$FILESOU'");
	$strSql .= ", " . ((strlen($IDENG) <= 0) ? "0" :  $IDENG);
	$strSql .= ")";		
	return $data->executeQuery($strSql);
}

// Modification
function modifierEngine_config($FILEDES, $FILESOU, $IDENG) {
	global $data, $util, $FILEDES_ori, $FILESOU_ori, $IDENG_ori;
	$strSql = 'UPDATE engine_config SET ';
	$strSql_Req = '';		

	if (testEngine_config($IDENG_ori)) {			
				
	$result = getSingleRowEngine_config(	$IDENG_ori);
	$ligne = $data->fetchObject();	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {	
            $FILEDES_ori = $ligne->FILEDES;
            $FILESOU_ori = $ligne->FILESOU;
            $IDENG_ori = $ligne->IDENG;	
	}}
	
	$FILESOU = addslashes($FILESOU);
	$FILEDES = addslashes($FILEDES);
			
	if ($FILEDES != addslashes($FILEDES_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "FILEDES = '$FILEDES'";
	if ($FILESOU != addslashes($FILESOU_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "FILESOU = '$FILESOU'";
	if ($IDENG != addslashes($IDENG_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDENG = '$IDENG'";
		

	$strSql .= $strSql_Req . " WHERE IDENG = $IDENG_ori";	
	//echo $strSql;
	return $data->executeQuery($strSql);
}

// Test d'existence
function testEngine_config($IDENG) {
	global $data;
	$strSql = "SELECT 1 FROM engine_config WHERE  IDENG = $IDENG ;";
	$res = $data->executeQuery($strSql);
	$count = $data->rowCount();
        return ($count > 0) ? true : false;
}

// Récupération enregistrement
function getSingleRowEngine_config($IDENG) {
	global $data;
	$strSql = "SELECT * FROM engine_config WHERE IDENG = $IDENG;";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$FILEDES = trim(isset($_POST['EFILEDES']) ? $_POST['EFILEDES'] : '');
$FILESOU = trim(isset($_POST['EFILESOU']) ? $_POST['EFILESOU'] : '');
$IDENG = trim(isset($_POST['EIDENG']) ? $_POST['EIDENG'] : '');

$IDENG_ori = trim(isset($_POST['EIDENG_ori']) ? $_POST['EIDENG_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! testEngine_config($IDENG)) {
					
if (ajouterEngine_config($IDENG,$FILESOU,$FILEDES)) {
		$message = "Insertion OK";	
	} 
}
	break;
	
	case 1 :			

	
if (testEngine_config($IDENG)) {
		
if (modifierEngine_config($FILEDES, $FILESOU, $IDENG)) {				
		$message = "Modification OK";	
		} 
	}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDENG"])) {

$IDENG= $_GET["EIDENG"]; 
				
if (testEngine_config($IDENG)) {			
				
$result = getSingleRowEngine_config($IDENG);
$ligne = $result->fetch(PDO::FETCH_OBJ); 

//echo var_dump($result);

//$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
    $FILEDES = $FILEDES_ori = $ligne->FILEDES;
    $FILESOU = $FILESOU_ori = $ligne->FILESOU;
    $IDENG = $IDENG_ori = $ligne->IDENG;	
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

<form action="<?php echo $page; ?>" method="post" name="FEngine_config" id="FEngine_config">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Engine_config</div></td>
          </tr>
        <tr>
<td>ID Engine</td>
<td><input type="text" size="30" name="EIDENG" id="EIDENG" value="<?php echo htmlentities($IDENG, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Fichier Source</td>
<td><input type="text" size="30" name="EFILESOU" id="EFILESOU" value="<?php echo htmlentities($FILESOU, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Fichier Destination</td>
<td><input type="text" size="30" name="EFILEDES" id="EFILEDES" value="<?php echo htmlentities($FILEDES, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>

      						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		<input id="EIDENG_ori" name="EIDENG_ori" type="hidden"  value="<?php echo $IDENG_ori; ?>">
		
		</td>
    </tr>
	</table>
  </div>
</form>
<?php $data->disconnect(); ?>
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