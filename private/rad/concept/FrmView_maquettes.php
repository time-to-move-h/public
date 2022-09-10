<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Consulter 
// Table : maquettes

// Inclusion de bibliothèque
require('../../lib/IO/DatabaseCoreClass.php');
require('DBApplicationClass.php');
require('../../lib/IO/DataUtilsClass.php');


// Instanciation
$data = new DBApplication();
$util = new DataUtils();
$page = $_SERVER["PHP_SELF"];

// Connexion Mysql
$data->connecterDB();

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

$IDAPP = (isset($_POST["LApplication"])) ? $_POST["LApplication"] : '';

$EIDAPP = '';
if (isset($_GET["EIDAPP"])) $EIDAPP = $_GET["EIDAPP"];
if (isset($_POST["EIDAPP"])) $EIDAPP = $_POST["EIDAPP"];



// Suppression
function supprimerMaquettes($IDAPP,$IDMAQ) {
	global $data;
	
	$result = $data->executeQuery("DELETE FROM maquettes WHERE IDAPP = '$IDAPP' AND IDMAQ = '$IDMAQ';");
	return $data->executeQuery("DELETE FROM sousmaquettes WHERE IDAPP = '$IDAPP' AND IDMAQ = '$IDMAQ';");	
}


/* Suppression */
if (isset($_POST["BSupprimer"])) {

	if (isset($_POST["ID"])) {
		$id = $_POST["ID"];

	    for ($i = 0; $i < count($id); $i++) {
			$tab = preg_split("/[#]+/", $id[$i]);
			if (count($tab > 0)) {
					supprimerMaquettes(addslashes($tab[0]),addslashes($tab[1]));					
			}
		}
	}
}
							
/* Modification */
if (isset($_POST["BModifier"])) {
	if (isset($_POST["ID"])) {
		$id = $_POST["ID"];
		if (count($id > 0) ) {
			$data->deconnecter();
			
		for ($i = 0; $i < count($id); $i++) {
				$tab = preg_split("/[#]+/", $id[$i]);
		}	
		
		header("Location: FrmEdit_maquettes.php?" . "EIDAPP=" . addslashes($tab[0]) . '&' . "EIDMAQ=" . addslashes($tab[1]));				
					
		}	
	}
}


/* Details */
if (isset($_POST["BSousListe"])) {
	if (isset($_POST["ID"])) {
		$id = $_POST["ID"];
		if (count($id > 0) ) {
			$data->deconnecter();
			
		for ($i = 0; $i < count($id); $i++) {
				$tab = preg_split("/[#]+/", $id[$i]);
		}	
		
		header("Location: FrmView_sousmaquettes.php?" . "EIDAPP=" . addslashes($tab[0]) . '&' . "EIDMAQ=" . addslashes($tab[1]));				
					
		}	
	}
}


/* Creation */
if (isset($_POST["BCreer"])) {		
	header("Location: FrmEdit_maquettes.php");				
}

/* Importer */
if (isset($_POST["BImporter"])) {		
	header("Location: FrmImport_maquettes.php?EIDAPP=" . $EIDAPP);				
}

/* Generer */
if (isset($_POST["BGenListe"])) {	
	if (isset($_POST["ID"])) {
		$id = $_POST["ID"];
		if (count($id > 0) ) {
			$data->deconnecter();				
			//print_r($id);
			//exit();			
			//header("Location: engine.php?" . "EIDAPP=" . addslashes($tab[0]) . "&EIDLST=" . addslashes($tab[1]) . "&EIDENG=" . addslashes($tab[2]));									
			header("Location: engine.php?" . http_build_query($id));											
		}	
	}			
}

// ########################################
// Nbre denregistrements

$resNbre = $data->executeQuery("SELECT COUNT(*) FROM maquettes;");
$TCount = $util->getLine($resNbre);
$NbreEnreg = 0;
$NbreEnreg = $TCount[0];

// Accès Data
$res = $data->executeQuery("SELECT * FROM maquettes LIMIT $debut , $fin;");
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




<form method="post" action="<?php echo $page;?>" name="FMaquettes">
<div class="CSSTableGenerator" >
<table width="100%"  border="1">
  <tr>
    <td colspan="7">		
	</td>
    </tr>
  <tr>
    <td colspan="7"><?php echo $NbreEnreg;?> Maquettes</td>
  </tr>
  <tr>
    <td width="6%">&nbsp;</td>
		<td width="15%"></td>
	<td width="15%"></td>
	<td width="15%"></td>
	<td width="15%"></td>
	<td width="15%"></td>
	<td width="15%"></td>
</tr>

  <?php
  	while ($enreg = $util->getLine($res)) {  
  		$tab = "  <tr>";
    	$tab .= " <td>";  	
		$tab .= "<input name=\"ID[]\" type=\"checkbox\" id=\"ID[]\" value=\"" . $enreg[0] . "#" . $enreg[1] . "#" . $enreg[5] . "\"/>";
		
		$tab .= "</td>";
				$tab .= "<td>" . $enreg[0] . "</td>";
		$tab .= "<td>" . $enreg[1] . "</td>";
		$tab .= "<td>" . $enreg[2] . "</td>";
		$tab .= "<td>" . $enreg[3] . "</td>";
		$tab .= "<td>" . $enreg[4] . "</td>";
		$tab .= "<td>" . $enreg[5] . "</td>";

			
		$tab .= "</tr>";
	
		echo $tab;		
  	}  
	
	$data->deconnecter();
  ?>
  <tr>
    <td colspan="7"><input name="BCreer" type="submit" id="BCreer" value="Creer">
      <input name="BSupprimer" type="submit" id="BSupprimer" value="Supprimer">
        <input name="BModifier" type="submit" id="BModifier" value="Modifier">
        <input name="BImporter" type="submit" id="BImporter" value="Importer">
        <input name="BSousListe" type="submit" id="BSousListe" value="Details">
        <input name="BGenListe" type="submit" id="BGenListe" value="Generer Maquette">
<input name="HSaut" type="hidden" id="HSaut" value="<?php echo $debut; ?>"> 
        <input name="HPage" type="hidden" id="HPage"  value="<?php echo $plage; ?>">        <input name="EIDAPP" type="hidden" id="EIDAPP" value="<?php echo $EIDAPP; ?>"></td>
    </tr>
  <tr>
    <td colspan="7">&nbsp;</td>
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
	if ($plage > 0) $lien = '<a href="' . $page . '?HSaut=' . ($plage - ($EnregMax * $NbrePages)) . '&HPage='  . ($plage - ($EnregMax * $NbrePages)) . '">&amp;lt;&amp;lt;</a> - ';
		
	for ($i = 0; $i < $NbreMax;$i++) {
		if ($i > 0) $lien .= ' - ';
		$lien .= '<a href="' . $page . '?HSaut=' . $tour . '&HPage=' .  $plage . '">' . (floor($tour / $EnregMax) + 1) . '</a>';
		$tour += $EnregMax;	
	}
	
	// Droite
	if (($plage + ($EnregMax * $NbrePages)) < $NbreEnreg) $lien .= ' - <a href="' . $page . '?HSaut=' . $tour . '&HPage=' . $tour . '">&amp;gt;&amp;gt;</a>';
	
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