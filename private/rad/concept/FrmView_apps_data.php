<?php

// @author Moviao Inc
// Tous Droits réservés 2015.
// Fiche Consulter 
// Table : apps_data

// Inclusion de bibliothèque
require('../../../lib/Moviao/Database/DatabaseCore.php');
//require('../../lib/IO/DataUtilsClass.php');
require('DBApplicationClass.php');
// Instanciation
$data = new \Moviao\Database\DBApplication();
//$util = new DataUtils();
$page = $_SERVER["PHP_SELF"];
// Connexion Mysql
$data->connecterDB();
// Initialisation des Champs primaire			
$IDAPP = '';
$IDAPPDAT = '';
	
$IDAPP = trim(isset($_GET['EIDAPP']) ? $_GET['EIDAPP'] : '');
$IDAPPDAT = trim(isset($_GET['EIDAPPDAT']) ? $_GET['EIDAPPDAT'] : '');
	
$EnregMax = 15; // Nombre denregistrements Max.
$plage = 0; // Selecteur de plage
$debut = 0; // Pointeur denregistrement
$fin = $EnregMax; // Nbre d'enregistrements
// ########################################

/* Gestion des Sauts */
if (isset($_GET["HSaut"])) $debut = ((int) $_GET["HSaut"]) ? $_GET["HSaut"] : 0;
else if (isset($_POST["HSaut"])) $debut = ((int) $_POST["HSaut"]) ? $_POST["HSaut"] : 0;
/* Gestion des Pages */
if (isset($_GET["HPage"])) $plage = ((int) $_GET["HPage"]) ? $_GET["HPage"] : 0;
else if (isset($_POST["HPage"])) $plage = ((int) $_POST["HPage"]) ? $_POST["HPage"] : 0;
// ########################################

// Suppression
function supprimerApps_data($IDAPP,$IDAPPDAT) {
    global $data;
    return $data->executeQuery("DELETE FROM apps_data WHERE IDAPP = '$IDAPP' AND IDAPPDAT = '$IDAPPDAT';");
}

/* Suppression */
if (isset($_POST["BSupprimer"])) {
    if (isset($_POST["ID"])) {
            $id = $_POST["ID"];
        for ($i = 0; $i < count($id); $i++) {
                    $tab = preg_split("/[#]+/", $id[$i]);
                    if (count($tab > 0)) {
                                    supprimerApps_data(addslashes($tab[0]),addslashes($tab[1]));					
                    }
            }
    }
}
							
/* Modification */
if (isset($_POST["BModifier"])) {
	if (isset($_POST["ID"])) {
		$id = $_POST["ID"];
		if (count($id) > 0) {
			$data->disconnect();			
			for ($i = 0; $i < count($id); $i++) {
				$tab = preg_split("/[#]+/", $id[$i]);
			}			
			header("Location: FrmEdit_apps_data.php?" . "EIDAPP=" . addslashes($tab[0]) . '&' . "EIDAPPDAT=" . addslashes($tab[1]));									
		}	
	}
}


/* Creation */
if (isset($_POST["BCreer"])) {		
    header("Location: FrmEdit_apps_data.php");				
}

// ########## LINK LISTE #########

if (isset($_POST["BSubImport"])) {
	if (isset($_POST["ID"])) {
	$id = $_POST["ID"];
 	if (count($id) > 0) {
		$data->disconnect();
		for ($i = 0; $i < count($id); $i++) {
			$tab = preg_split("/[#]+/", $id[$i]);
		}
	header("Location: FrmImport_Template.php?" . "EIDAPP=" . addslashes($tab[0]) . '&' . "EIDAPPDAT=" . addslashes($tab[1]));
}}}
if (isset($_POST["BSubChamps"])) {
	if (isset($_POST["ID"])) {
	$id = $_POST["ID"];
 	if (count($id) > 0) {
		$data->disconnect();
		for ($i = 0; $i < count($id); $i++) {
			$tab = preg_split("/[#]+/", $id[$i]);
		}
	header("Location: FrmView_apps_subdata.php?" . "EIDAPP=" . addslashes($tab[0]) . '&' . "EIDAPPDAT=" . addslashes($tab[1]));
}}}
if (isset($_POST["BSubGen"])) {
	if (isset($_POST["ID"])) {
	$id = $_POST["ID"];
 	if (count($id) > 0) {
		$data->disconnect();
		for ($i = 0; $i < count($id); $i++) {
			$tab = preg_split("/[#]+/", $id[$i]);
		}
	header("Location: engine.php?" . http_build_query($id));
}}}
if (isset($_POST["BSubLinkList"])) {
	if (isset($_POST["ID"])) {
	$id = $_POST["ID"];
 	if (count($id) > 0) {
		$data->disconnect();
		for ($i = 0; $i < count($id); $i++) {
			$tab = preg_split("/[#]+/", $id[$i]);
		}
	header("Location: FrmView_apps_lnkdata.php?" . "EIDAPP=" . addslashes($tab[0]) . '&' . "EIDAPPDAT=" . addslashes($tab[1]));
}}}


// ########################################
// Accès Data
$strSql = "Select * From apps_data Where ((IDAPP='$IDAPP') OR ('$IDAPP'='')) ORDER BY IDAPPDAT DESC";
// Nbre d'enregistrements
$resNb = $data->executeQuery($strSql);
$NbreEnreg = $data->rowCount();
// Selection Enregistrements 
$res = $data->executeQuery("$strSql LIMIT $debut , $fin;");
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
		}.CSSTableGenerator tr:hover td{
			
		}
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


<form method="post" action="<?php echo $page;?>" name="F3">
<div class="CSSTableGenerator">
<table width="100%"  border="1">
  <tr>
    <td colspan="9">		
	</td>
    </tr>
  <tr>
    <td colspan="9"><?php echo $NbreEnreg;?> Apps_data</td>
  </tr>
  <tr>
    <td width="6%">&nbsp;</td>
		<td width="15%">ID APP</td>
	<td width="15%">ID Apps Data</td>
	<td width="15%">Table</td>
	<td width="15%">Libelle</td>
	<td width="15%">Description</td>
	<td width="15%">Requete</td>
	<td width="15%">Engine</td>
	<td width="15%">File Name</td>
</tr>

  <?php
  	while ($enreg = $data->fetchObject()) {  
  		$tab = "  <tr>";
    	$tab .= " <td>";  	
		$tab .= "<input name=\"ID[]\" type=\"checkbox\" id=\"ID[]\" value=\"" . $enreg->IDAPP . "#" . $enreg->IDAPPDAT . "\"/>";
		
		$tab .= "</td>";
		$tab .= "<td>" . $enreg->IDAPP . "</td>";
		$tab .= "<td>" . $enreg->IDAPPDAT . "</td>";
		$tab .= "<td>" . $enreg->TAB . "</td>";
		$tab .= "<td>" . $enreg->LIBELLE . "</td>";
		$tab .= "<td>" . $enreg->DESCRIPTION . "</td>";
		$tab .= "<td>" . $enreg->REQUETE . "</td>";
		$tab .= "<td>" . $enreg->IDENG . "</td>";
		$tab .= "<td>" . $enreg->FILE . "</td>";

			
		$tab .= "</tr>";
	
		echo $tab;		
  	}  
	
	$data->disconnect();
  ?>
  <tr>
    <td colspan="9"><input name="BCreer" type="submit" id="BCreer" value="Creer">
      <input name="BSupprimer" type="submit" id="BSupprimer" value="Supprimer">
        <input name="BModifier" type="submit" id="BModifier" value="Modifier">        
        <input name="BSubImport" id="BSubImport" type="submit" value="Importer">
<input name="BSubChamps" id="BSubChamps" type="submit" value="Champs">
<input name="BSubGen" id="BSubGen" type="submit" value="Generer Liste">
<input name="BSubLinkList" id="BSubLinkList" type="submit" value="Liens de liste">
        
        <input name="HSaut" type="hidden" id="HSaut" value="<?php echo $debut; ?>"> 
        <input name="HPage" type="hidden" id="HPage"  value="<?php echo $plage; ?>">        </td>
    </tr>
  <tr>
    <td colspan="9">&nbsp;</td>
  </tr>
</table>
</div>
<br>
<?php
if ($NbreEnreg > $EnregMax) {
	$tete = "<table width=\"100%\" border=\"1\">";
	$tete .= "  <tr>";
	$tete .= "   <td>";

	echo $tete;
	
	// Gestion des pages
	$NbrePages = 20; // Nombre de pages maximum
	$NbreMax = 0; // Limiteur de pages
	$NbrePagesTotal = 0; // Nbre de pages totale
	$tour = $plage; // Pointeur denregistrements
	$lien = ''; // Lien
	
	// Transmission Data
	$var_pri = "&EIDAPP=$IDAPP&EIDAPPDAT=$IDAPPDAT";		
	
	// Calcul
	$NbrePagesTotal = floor($NbreEnreg / $EnregMax);	
	if (($NbreEnreg % $EnregMax) != 0) $NbrePagesTotal++;
	// Limite	
	if ((($NbrePagesTotal - ($tour / $EnregMax)) > $NbrePages)) 
		$NbreMax = $NbrePages;
	else
		$NbreMax = ($NbrePagesTotal - ($tour / $EnregMax));
	// --------------	
	// Gauche
	if ($plage > 0) $lien = '<a href="' . $page . '?HSaut=' . ($plage - ($EnregMax * $NbrePages)) . '&HPage='  . ($plage - ($EnregMax * $NbrePages)) . $var_pri . '">&amp;lt;&amp;lt;</a> - ';
		
	for ($i = 0; $i < $NbreMax;$i++) {
		if ($i > 0) $lien .= ' - ';
		$lien .= '<a href="' . $page . '?HSaut=' . $tour . '&HPage=' .  $plage .  $var_pri . '">' . (floor($tour / $EnregMax) + 1) . '</a>';
		$tour += $EnregMax;	
	}
	
	// Droite
	if (($plage + ($EnregMax * $NbrePages)) < $NbreEnreg) $lien .= ' - <a href="' . $page . '?HSaut=' . $tour . '&HPage=' . $tour . $var_pri . '">&amp;gt;&amp;gt;</a>';
	
	echo $lien;	
	
	$pied = "</td>";
  	$pied .= "</tr>";
	$pied .= "</table>";
		
	echo $pied;
}
?>
</form>

	  <p>&nbsp;</p>
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
<!-- Free template distributed by http://freehtml5templates.com -->
</body>
</html>