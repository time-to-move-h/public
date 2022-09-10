<?php

// @author Moviao Inc
// Tous Droits réservés 2015.
// Fiche Consulter 
// Table : /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return $util->getFirstLine($res)[0];}*/

// Inclusion de bibliothèque
require('../../class/IO/DatabaseCoreClass.php');
require('../../class/IO/DataUtilsClass.php');
require('DBApplicationClass.php');
// Instanciation
$data = new DBApplication();
$util = new DataUtils();
$page = $_SERVER["PHP_SELF"];
// Connexion Mysql
$data->connecterDB();
// Initialisation des Champs primaire			
/*@{
$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
$i = 0;
$s = '';
while ($enreg = $util->getLineObject($res)) { 
    $s .= '$' . $enreg->IDCHP . " = '';\r\n";
    $i++;
}
return $s;
}*/	
/*@{
// Recuperation du formulaire
$res = $data->executeQuery("SELECT IDCHP, TYPEDONNEE,NUL FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
$i = 0;
$s = '';
while ($enreg = $util->getLineObject($res)) { 	
    if ($enreg->TYPEDONNEE == 'tinyint(1)')
            $s.= '$' . $enreg->IDCHP . ' = trim(isset($_GET[\'E' . $enreg->IDCHP . "']) ? 1 : 0);\r\n";
    else
            $s .= '$' . $enreg->IDCHP . ' = trim(isset($_GET[\'E' . $enreg->IDCHP . '\']) ? $_GET[\'E' . $enreg->IDCHP . "'] : '');\r\n";

    $i++;
}
return $s;
}*/	
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
function supprimer/*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ucfirst($util->getFirstLine($res)[0]);}*/(/*@{ 
 
$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
$i = 0;
$s = '';
while ($enreg = $util->getLineObject($res)) { 
	if ($i > 0) $s .= ",";
	$s .= '$' . $enreg->IDCHP;
	$i++;
}
return $s;
 
}*/) {
	global $data;
	return $data->executeQuery("/*@{ 		
		$res = $data->executeQuery("SELECT IDCHP, TYPEDONNEE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
		$i = 0;
		$s = '';	                    
                $table = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;");                	
		// DELETE
		// delete from X where X = X and X = 'X'
		$corps = "DELETE FROM {$util->getFirstLine($table)[0]} WHERE ";
		$coeur = "";
		
		while ($enreg = $util->getLineObject($res)) { 
			if ($i > 0) $coeur .= ' AND ';
			$coeur .= $enreg->IDCHP . ' = ';			
			if (isNumber($enreg->TYPEDONNEE) == 0) $coeur .= "'";
			$coeur .= '$' . $enreg->IDCHP;
			if (isNumber($enreg->TYPEDONNEE) == 0) $coeur .= "'";			
			$i++;
		}
		$s = $corps . $coeur . ';'; 	
		return $s;	
	
	}*/");
}

/* Suppression */
if (isset($_POST["BSupprimer"])) {
	if (isset($_POST["ID"])) {
		$id = $_POST["ID"];
	    for ($i = 0; $i < count($id); $i++) {
			$tab = preg_split("/[#]+/", $id[$i]);
			if (count($tab > 0)) {
					supprimer/*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ucfirst($util->getFirstLine($res)[0]);}*/(/*@{							
															
					// cle primaire
					$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
					$i = 0;
					$s = '';
					while ($enreg = $util->getLineObject($res)) { 
						if ($i > 0) $s .= ",";
						$s .= 'addslashes($tab[' . $i . '])';
						$i++;
					}
					return $s;				
									
					}*/);					
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
			header("Location: FrmEdit_/*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ($util->getFirstLine($res)[0]);}*/.php?"/*@{				
		// cle primaire
		$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
		$i = 0;
		$s = '';
		while ($enreg = $util->getLineObject($res)) { 
			if ($i > 0) $s .= " . '&'";
			$s .= ' . "E' . $enreg->IDCHP . '=" . addslashes($tab[' . $i . '])';
			$i++;
		}
		return $s;		
						
		}*/);									
		}	
	}
}


/* Creation */
if (isset($_POST["BCreer"])) {		
    header("Location: FrmEdit_/*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return $util->getFirstLine($res)[0];}*/.php");				
}

// ########## LINK LISTE #########

/*@{				
// Fonction ---> apps_lnkdata
$res = $data->executeQuery("SELECT NAME,LABEL,LINK,PARAMS FROM apps_lnkdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1 ORDER BY NUMSEQ;");
$i = 0;
$s = '';
while ($enreg = $util->getLineObject($res)) { 
	
	$s .= 'if (isset($_POST["'.$enreg->NAME.'"])) {' . "\r\n"
		. '	if (isset($_POST["ID"])) {' . "\r\n"
		. '	$id = $_POST["ID"];' . "\r\n"
		. ' 	if (count($id > 0) ) {' . "\r\n"
		. '		$data->deconnecter();' . "\r\n"
		. '		for ($i = 0; $i < count($id); $i++) {' . "\r\n"
		. '			$tab = preg_split("/[#]+/", $id[$i]);' . "\r\n"
		. '		}' . "\r\n"
		. '	header("Location: '.$enreg->LINK.'?"';
						
		// cle primaire ---------------------------------
		if (strlen($enreg->PARAMS) <=0) {
			$res2 = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
			$i2 = 0;		
			while ($enreg2 = $util->getLineObject($res2)) { 
				if ($i2 > 0) $s .= " . '&'";
				$s .= ' . "E' . $enreg2->IDCHP . '=" . addslashes($tab[' . $i2 . '])';
				$i2++;
			}
		} else {
		//-----------------------------------------------
			$s .= ' . ' . $enreg->PARAMS;		
		}
		$s .= ');' . "\r\n"
		. '}'
			
	. '}'
.'}' . "\r\n";	
	$i++;
}
return $s;								
}*/

// ########################################
// Accès Data
$strSql = "/*@{
	
// Requete personnalise1
$res = $data->executeQuery("SELECT REQUETE FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT';");
$table = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;");
$s = "Select ".chr(42)." From {$util->getFirstLine($table)[0]}";
if ($enreg = $util->getLineObject($res)) { 
	if ((strlen($enreg->REQUETE) > 0) && ($enreg->REQUETE != "null")) {
		$s = $enreg->REQUETE;	
	}		
}
return $s;		
	
}*/";
// Nbre d'enregistrements
$resNb = $data->executeQuery($strSql);
$NbreEnreg = $util->getNbreEnreg($resNb);
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


<form method="post" action="<?php echo $page;?>" name="F/*@{ return ucfirst($IDAPPDAT);}*/">
<div class="CSSTableGenerator">
<table width="100%"  border="1">
  <tr>
    <td colspan="/*@{     
	    $res = $data->executeQuery("SELECT COUNT(IDCHP) FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT';");
		return ((int)($util->getLine($res)[0]) + 1);
    }*/">		
	</td>
    </tr>
  <tr>
    <td colspan="/*@{     
    
   	    $res = $data->executeQuery("SELECT COUNT(IDCHP) FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT';");
		return ((int)($util->getLine($res)[0]) + 1);   
    
    }*/"><?php echo $NbreEnreg;?> /*@{ $res = $data->executeQuery("SELECT TAB FROM apps_data WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;"); return ucfirst($util->getFirstLine($res)[0]);}*/</td>
  </tr>
  <tr>
    <td width="6%">&nbsp;</td>
	/*@{
    
    	// recuperation champs visible
        $res = $data->executeQuery("SELECT IDCHP,LIBELLE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1 ORDER BY NUMSEQ;");
        $i = 0;
        $s = '';
        while ($enreg = $util->getLineObject($res)) {                         
			$s .= '	<td width="15%">' . $enreg->LIBELLE . '</td>' . "\n";							            
            $i++;
        }
        return $s;

   }*/</tr>

  <?php
  	while ($enreg = $util->getLineObject($res)) {  
  		$tab = "  <tr>";
    	$tab .= " <td>";  	
		$tab .= "<input name=\"ID[]\" type=\"checkbox\" id=\"ID[]\" value=\""/*@{
			
		   	// recuperation champs primaire
			$res = $data->executeQuery("SELECT IDCHP,LIBELLE FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
			$i = 0;
			$s = '';
			while ($enreg = $util->getLineObject($res)) {             
				if ($i > 0) $s .= ' . "#"';
				$s .= ' . $enreg->' . $enreg->IDCHP;					            
				$i++;
			}
			$s .= ' . "\"/>"';
			return $s;
		}*/;
		
		$tab .= "</td>";
/*@{			
		
		   	// recuperation champs visible
			$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1 ORDER BY NUMSEQ;");
			$i = 0;
			$s = '';
			while ($enreg = $util->getLineObject($res)) {             
				$s .= '		$tab .= "<td>" . $enreg->'. $enreg->IDCHP .' . "</td>";' . "\n";							            
				$i++;
			}
			return $s;		
						
		}*/
			
		$tab .= "</tr>";
	
		echo $tab;		
  	}  
	
	$data->deconnecter();
  ?>
  <tr>
    <td colspan="/*@{     
       	$res = $data->executeQuery("SELECT COUNT(IDCHP) FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1;");
        return ((int)($util->getLine($res)[0]) + 1);        
                
     }*/"><input name="BCreer" type="submit" id="BCreer" value="Creer">
      <input name="BSupprimer" type="submit" id="BSupprimer" value="Supprimer">
        <input name="BModifier" type="submit" id="BModifier" value="Modifier">        
        /*@{        		
		   	// Insertion Lien dans la liste 
			$res = $data->executeQuery("SELECT NAME,LABEL,LINK FROM apps_lnkdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1 ORDER BY NUMSEQ;");
			$i = 0;
			$s = '';
			while ($enreg = $util->getLineObject($res)) {             
				$s .= '<input name="'.$enreg->NAME.'" id="'.$enreg->NAME.'" type="submit" value="'.$enreg->LABEL.'">' . "\n";							            
				$i++;
			}
			return $s;							
            
		}*/        
        <input name="HSaut" type="hidden" id="HSaut" value="<?php echo $debut; ?>"> 
        <input name="HPage" type="hidden" id="HPage"  value="<?php echo $plage; ?>">        </td>
    </tr>
  <tr>
    <td colspan="/*@{     
   	    $res = $data->executeQuery("SELECT COUNT(IDCHP) FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND VISIBLE=1;");
		return ((int)($util->getLine($res)[0]) + 1);     
     }*/">&nbsp;</td>
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
	$var_pri = "/*@{
	$res = $data->executeQuery("SELECT IDCHP FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT='$IDAPPDAT' AND PRIMAIRE=1;");
	$i = 0;
	$s = '';
	while ($enreg = $util->getLineObject($res)) { 
		$s .= '&E' . $enreg->IDCHP . '=$' . $enreg->IDCHP;
		$i++;
	}
	return $s;
	}*/";		
	
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