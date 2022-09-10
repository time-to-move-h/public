<?php

// @author Moviao Inc
// Tous Droits réservés 2015.
// Fiche Modifier 
// Table : apps_subdata

// Inclusion de bibliothèque
require('../../../lib/Moviao/Database/DatabaseCore.php');
//require('../../lib/IO/DataUtilsClass.php');
require('DBApplicationClass.php');
// Instanciation
$data = new \Moviao\Database\DBApplication();
// Connexion Mysql
$data->connecterDB();
// Initialisation des Champs			

$AI = '';
$AI_ori = '';
$DATA = '';
$DATA_ori = '';
$DESCRIPTION = '';
$DESCRIPTION_ori = '';
$IDAPP = '';
$IDAPP_ori = '';
$IDAPPDAT = '';
$IDAPPDAT_ori = '';
$IDCHP = '';
$IDCHP_ori = '';
$LIBELLE = '';
$LIBELLE_ori = '';
$NUL = '';
$NUL_ori = '';
$NUMSEQ = '';
$NUMSEQ_ori = '';
$PRIMAIRE = '';
$PRIMAIRE_ori = '';
$REQUETE = '';
$REQUETE_ori = '';
$TAILLE = '';
$TAILLE_ori = '';
$TYPEDONNEE = '';
$TYPEDONNEE_ori = '';
$VISIBLE = '';
$VISIBLE_ori = '';
					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function add($IDAPP,$IDAPPDAT,$IDCHP,$LIBELLE,$DESCRIPTION,$REQUETE,$NUMSEQ,$TYPEDONNEE,$PRIMAIRE,$VISIBLE,$TAILLE,$AI,$NUL,$DATA) {
	global $data;			
		
	$IDAPP = addslashes($IDAPP);
	$IDAPPDAT = addslashes($IDAPPDAT);
	$IDCHP = addslashes($IDCHP);
	$LIBELLE = addslashes($LIBELLE);
	$DESCRIPTION = addslashes($DESCRIPTION);
	$REQUETE = addslashes($REQUETE);
	$TYPEDONNEE = addslashes($TYPEDONNEE);
	$DATA = addslashes($DATA);
			
	$strSql = "INSERT INTO apps_subdata (AI, DATA, DESCRIPTION, IDAPP, IDAPPDAT, IDCHP, LIBELLE, NUL, NUMSEQ, PRIMAIRE, REQUETE, TAILLE, TYPEDONNEE, VISIBLE) VALUES (";
	$strSql .= ((strlen($AI) <= 0) ? "0" :  $AI);
	$strSql .= ", " . ((strlen($DATA) <= 0) ? "NULL" :  "'$DATA'");
	$strSql .= ", " . ((strlen($DESCRIPTION) <= 0) ? "NULL" :  "'$DESCRIPTION'");
	$strSql .= ", " . ((strlen($IDAPP) <= 0) ? "''" :  "'$IDAPP'");
	$strSql .= ", " . ((strlen($IDAPPDAT) <= 0) ? "0" :  $IDAPPDAT);
	$strSql .= ", " . ((strlen($IDCHP) <= 0) ? "''" :  "'$IDCHP'");
	$strSql .= ", " . ((strlen($LIBELLE) <= 0) ? "''" :  "'$LIBELLE'");
	$strSql .= ", " . ((strlen($NUL) <= 0) ? "0" :  $NUL);
	$strSql .= ", " . ((strlen($NUMSEQ) <= 0) ? "NULL" :  $NUMSEQ);
	$strSql .= ", " . ((strlen($PRIMAIRE) <= 0) ? "0" :  $PRIMAIRE);
	$strSql .= ", " . ((strlen($REQUETE) <= 0) ? "NULL" :  "'$REQUETE'");
	$strSql .= ", " . ((strlen($TAILLE) <= 0) ? "0" :  $TAILLE);
	$strSql .= ", " . ((strlen($TYPEDONNEE) <= 0) ? "NULL" :  "'$TYPEDONNEE'");
	$strSql .= ", " . ((strlen($VISIBLE) <= 0) ? "0" :  $VISIBLE);
	$strSql .= ")";		
	$data->executeQuery($strSql);
        return ($data->rowCount() <= 0) ? FALSE : TRUE;
}

// Modification
function modify($AI, $DATA, $DESCRIPTION, $IDAPP, $IDAPPDAT, $IDCHP, $LIBELLE, $NUL, $NUMSEQ, $PRIMAIRE, $REQUETE, $TAILLE, $TYPEDONNEE, $VISIBLE) {
	global $data, $AI_ori, $DATA_ori, $DESCRIPTION_ori, $IDAPP_ori, $IDAPPDAT_ori, $IDCHP_ori, $LIBELLE_ori, $NUL_ori, $NUMSEQ_ori, $PRIMAIRE_ori, $REQUETE_ori, $TAILLE_ori, $TYPEDONNEE_ori, $VISIBLE_ori;
	$strSql = 'UPDATE apps_subdata SET ';
	$strSql_Req = '';		

	if (test($IDAPP_ori, $IDAPPDAT_ori, $IDCHP_ori)) {			
				
	$result = getSingleRow(	$IDAPP_ori, 	$IDAPPDAT_ori, 	$IDCHP_ori);
	$ligne = $data->fetchObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (! empty($ligne)) {
		$AI_ori = $ligne->AI;
		$DATA_ori = $ligne->DATA;
		$DESCRIPTION_ori = $ligne->DESCRIPTION;
		$IDAPP_ori = $ligne->IDAPP;
		$IDAPPDAT_ori = $ligne->IDAPPDAT;
		$IDCHP_ori = $ligne->IDCHP;
		$LIBELLE_ori = $ligne->LIBELLE;
		$NUL_ori = $ligne->NUL;
		$NUMSEQ_ori = $ligne->NUMSEQ;
		$PRIMAIRE_ori = $ligne->PRIMAIRE;
		$REQUETE_ori = $ligne->REQUETE;
		$TAILLE_ori = $ligne->TAILLE;
		$TYPEDONNEE_ori = $ligne->TYPEDONNEE;
		$VISIBLE_ori = $ligne->VISIBLE;
	
	}}
	
	$IDAPP = addslashes($IDAPP);
	$IDAPPDAT = addslashes($IDAPPDAT);
	$IDCHP = addslashes($IDCHP);
	$LIBELLE = addslashes($LIBELLE);
	$DESCRIPTION = addslashes($DESCRIPTION);
	$REQUETE = addslashes($REQUETE);
	$TYPEDONNEE = addslashes($TYPEDONNEE);
	$DATA = addslashes($DATA);
			
	if ($AI != addslashes($AI_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "AI = '$AI'";
	if ($DATA != addslashes($DATA_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "DATA = '$DATA'";
	if ($DESCRIPTION != addslashes($DESCRIPTION_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "DESCRIPTION = '$DESCRIPTION'";
	if ($IDAPP != addslashes($IDAPP_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDAPP = '$IDAPP'";
	if ($IDAPPDAT != addslashes($IDAPPDAT_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDAPPDAT = '$IDAPPDAT'";
	if ($IDCHP != addslashes($IDCHP_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDCHP = '$IDCHP'";
	if ($LIBELLE != addslashes($LIBELLE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "LIBELLE = '$LIBELLE'";
	if ($NUL != addslashes($NUL_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "NUL = '$NUL'";
	if ($NUMSEQ != addslashes($NUMSEQ_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "NUMSEQ = '$NUMSEQ'";
	if ($PRIMAIRE != addslashes($PRIMAIRE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "PRIMAIRE = '$PRIMAIRE'";
	if ($REQUETE != addslashes($REQUETE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "REQUETE = '$REQUETE'";
	if ($TAILLE != addslashes($TAILLE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "TAILLE = '$TAILLE'";
	if ($TYPEDONNEE != addslashes($TYPEDONNEE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "TYPEDONNEE = '$TYPEDONNEE'";
	if ($VISIBLE != addslashes($VISIBLE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "VISIBLE = '$VISIBLE'";
	
	$strSql .= $strSql_Req . " WHERE IDAPP = '$IDAPP_ori' AND IDAPPDAT = $IDAPPDAT_ori AND IDCHP = '$IDCHP_ori'";	
	//echo $strSql;
	$data->executeQuery($strSql);
        return ($data->rowCount() <= 0) ? FALSE : TRUE;
}

// Test d'existence
function test($IDAPP, $IDAPPDAT, $IDCHP) {
    global $data;
    $strSql = "SELECT 1 FROM apps_subdata WHERE  IDAPP = '$IDAPP' AND IDAPPDAT = $IDAPPDAT AND IDCHP = '$IDCHP' ;";
    $res = $data->executeQuery($strSql);
    $row = $data->fetchColumn();
    if ($row == 1) {
        return true;
    } else {
        return false;
    }        
}

// Récupération enregistrement
function getSingleRow($IDAPP, $IDAPPDAT, $IDCHP) {
    global $data;
    $strSql = "SELECT * FROM apps_subdata WHERE IDAPP = '$IDAPP' AND IDAPPDAT = $IDAPPDAT AND IDCHP = '$IDCHP';";
    return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$AI = trim(isset($_POST['EAI']) ? 1 : 0);
$DATA = trim(isset($_POST['EDATA']) ? $_POST['EDATA'] : '');
$DESCRIPTION = trim(isset($_POST['EDESCRIPTION']) ? $_POST['EDESCRIPTION'] : '');
$IDAPP = trim(isset($_POST['EIDAPP']) ? $_POST['EIDAPP'] : '');
$IDAPPDAT = trim(isset($_POST['EIDAPPDAT']) ? $_POST['EIDAPPDAT'] : '');
$IDCHP = trim(isset($_POST['EIDCHP']) ? $_POST['EIDCHP'] : '');
$LIBELLE = trim(isset($_POST['ELIBELLE']) ? $_POST['ELIBELLE'] : '');
$NUL = trim(isset($_POST['ENUL']) ? 1 : 0);
$NUMSEQ = trim(isset($_POST['ENUMSEQ']) ? $_POST['ENUMSEQ'] : '');
$PRIMAIRE = trim(isset($_POST['EPRIMAIRE']) ? 1 : 0);
$REQUETE = trim(isset($_POST['EREQUETE']) ? $_POST['EREQUETE'] : '');
$TAILLE = trim(isset($_POST['ETAILLE']) ? $_POST['ETAILLE'] : '');
$TYPEDONNEE = trim(isset($_POST['ETYPEDONNEE']) ? $_POST['ETYPEDONNEE'] : '');
$VISIBLE = trim(isset($_POST['EVISIBLE']) ? 1 : 0);
		

$IDAPP_ori = trim(isset($_POST['EIDAPP_ori']) ? $_POST['EIDAPP_ori'] : '');
$IDAPPDAT_ori = trim(isset($_POST['EIDAPPDAT_ori']) ? $_POST['EIDAPPDAT_ori'] : '');
$IDCHP_ori = trim(isset($_POST['EIDCHP_ori']) ? $_POST['EIDCHP_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! test($IDAPP, $IDAPPDAT, $IDCHP)) {					
    if (add($IDAPP,$IDAPPDAT,$IDCHP,$LIBELLE,$DESCRIPTION,$REQUETE,$NUMSEQ,$TYPEDONNEE,$PRIMAIRE,$VISIBLE,$TAILLE,$AI,$NUL,$DATA)) {
        $message = "Insertion OK";	
    } 
}
	break;
	
	case 1 :			

	
if (test($IDAPP, $IDAPPDAT, $IDCHP)) {		
    if (modify($AI, $DATA, $DESCRIPTION, $IDAPP, $IDAPPDAT, $IDCHP, $LIBELLE, $NUL, $NUMSEQ, $PRIMAIRE, $REQUETE, $TAILLE, $TYPEDONNEE, $VISIBLE)) {				
            $message = "Modification OK";	
        } 
}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDAPP"]) && isset($_GET["EIDAPPDAT"]) && isset($_GET["EIDCHP"])) {

$IDAPP= $_GET["EIDAPP"]; 
$IDAPPDAT= $_GET["EIDAPPDAT"]; 
$IDCHP= $_GET["EIDCHP"]; 
				
if (test($IDAPP, $IDAPPDAT, $IDCHP)) {			
				
$result = getSingleRow($IDAPP, $IDAPPDAT, $IDCHP);
$ligne = $data->fetchObject($result);	
// Récupération d'un tableau d'enregistrement	
if (! empty($ligne)) {
  $AI = $AI_ori = $ligne->AI;
  $DATA = $DATA_ori = $ligne->DATA;
  $DESCRIPTION = $DESCRIPTION_ori = $ligne->DESCRIPTION;
  $IDAPP = $IDAPP_ori = $ligne->IDAPP;
  $IDAPPDAT = $IDAPPDAT_ori = $ligne->IDAPPDAT;
  $IDCHP = $IDCHP_ori = $ligne->IDCHP;
  $LIBELLE = $LIBELLE_ori = $ligne->LIBELLE;
  $NUL = $NUL_ori = $ligne->NUL;
  $NUMSEQ = $NUMSEQ_ori = $ligne->NUMSEQ;
  $PRIMAIRE = $PRIMAIRE_ori = $ligne->PRIMAIRE;
  $REQUETE = $REQUETE_ori = $ligne->REQUETE;
  $TAILLE = $TAILLE_ori = $ligne->TAILLE;
  $TYPEDONNEE = $TYPEDONNEE_ori = $ligne->TYPEDONNEE;
  $VISIBLE = $VISIBLE_ori = $ligne->VISIBLE;
	
}			
$mode = 1;
}}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Edit apps_subdata</title>
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

<form action="<?php echo $page; ?>" method="post" name="FApps_subdata" id="FApps_subdata">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Champs</div></td>
          </tr>
        <tr>
<td>ID App</td>
<td><input type="text" size="30" name="EIDAPP" id="EIDAPP" value="<?php echo htmlentities($IDAPP, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>ID Template</td>
<td><input type="text" size="30" name="EIDAPPDAT" id="EIDAPPDAT" value="<?php echo htmlentities($IDAPPDAT, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>ID Field</td>
<td><input type="text" size="30" name="EIDCHP" id="EIDCHP" value="<?php echo htmlentities($IDCHP, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Label</td>
<td><input type="text" size="30" name="ELIBELLE" id="ELIBELLE" value="<?php echo htmlentities($LIBELLE, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Description</td>
<td><input type="text" size="30" name="EDESCRIPTION" id="EDESCRIPTION" value="<?php echo htmlentities($DESCRIPTION, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Query</td>
<td><input type="text" size="30" name="EREQUETE" id="EREQUETE" value="<?php echo htmlentities($REQUETE, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>N�</td>
<td><input type="text" size="30" name="ENUMSEQ" id="ENUMSEQ" value="<?php echo htmlentities($NUMSEQ, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Data Type</td>
<td><input type="text" size="30" name="ETYPEDONNEE" id="ETYPEDONNEE" value="<?php echo htmlentities($TYPEDONNEE, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Primary</td>
<td><input type="checkbox" name="EPRIMAIRE"  id="EPRIMAIRE" <?php if ($PRIMAIRE == "1")  echo "checked=\"checked\""; ?>></td>
</tr>
<tr>
<td>Visible</td>
<td><input type="checkbox" name="EVISIBLE"  id="EVISIBLE" <?php if ($VISIBLE == "1")  echo "checked=\"checked\""; ?>></td>
</tr>
<tr>
<td>Size</td>
<td><input type="text" size="30" name="ETAILLE" id="ETAILLE" value="<?php echo htmlentities($TAILLE, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>AI</td>
<td><input type="checkbox" name="EAI"  id="EAI" <?php if ($AI == "1")  echo "checked=\"checked\""; ?>></td>
</tr>
<tr>
<td>NULL</td>
<td><input type="checkbox" name="ENUL"  id="ENUL" <?php if ($NUL == "1")  echo "checked=\"checked\""; ?>></td>
</tr>
<tr>
<td>Data</td>
<td><input type="text" size="30" name="EDATA" id="EDATA" value="<?php echo htmlentities($DATA, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>

      						
	  <tr>
      <td><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
      <input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		<input id="EIDAPP_ori" name="EIDAPP_ori" type="hidden"  value="<?php echo $IDAPP_ori; ?>"><input id="EIDAPPDAT_ori" name="EIDAPPDAT_ori" type="hidden"  value="<?php echo $IDAPPDAT_ori; ?>"><input id="EIDCHP_ori" name="EIDCHP_ori" type="hidden"  value="<?php echo $IDCHP_ori; ?>">
		
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