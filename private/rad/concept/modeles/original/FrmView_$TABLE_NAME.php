<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Consulter 
// Table : /*@{return $TABLE_NAME;}*/

// Inclusion de bibliothèque
require('../../CLASS/IO/DatabaseCoreClass.php');
require('../../CLASS/DBApplicationClass.php');
require('../../CLASS/IO/DataUtilsClass.php');

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
if (isset($_GET["HSaut"])) 
	$debut = ((int) $_GET["HSaut"]) ? $_GET["HSaut"] : 0;
else
	if (isset($_POST["HSaut"])) 
		$debut = ((int) $_POST["HSaut"]) ? $_POST["HSaut"] : 0;

/* Gestion des Pages */
if (isset($_GET["HPage"])) 
	$plage = ((int) $_GET["HPage"]) ? $_GET["HPage"] : 0;
else
	if (isset($_POST["HPage"])) 
		$plage = ((int) $_POST["HPage"]) ? $_POST["HPage"] : 0;
// ########################################

// Suppression
function supprimer/*@{ return ucfirst($TABLE_NAME);}*/(/*@{ return getPrimaryKeysVariable();}*/) {
	global $data;
	return $data->executeQuery("/*@{ return getSQLDelete(); }*/");
}


/* Suppression */
if (isset($_POST["BSupprimer"])) {

	if (isset($_POST["ID"])) {
		$id = $_POST["ID"];

	    for ($i = 0; $i < count($id); $i++) {
			$tab = split("#", $id[$i]);
			if (count($tab > 0)) {
					supprimer/*@{ return ucfirst($TABLE_NAME);}*/(/*@{						
					$resultat = '';
					$controle = 0;
					if (count($CHAMPS) > 0) {
						for ($i=0;$i < count($CHAMPS);$i++) {
							$reponse = $CHAMPS[$i];
							if ($reponse[4] == 'PRI') {
								if ($controle > 0) $resultat .= ', ';
								$resultat .= 'addslashes($tab[' . $i . '])';
								$controle = 1;
							}
						}			
					}

					return $resultat;						
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
				$tab = split("#", $id[$i]);
		}	
		
		header("Location: FrmEdit_/*@{ return ucfirst($TABLE_NAME);}*/.php?"/*@{		
		
		$resultat = '';
		$controle = 0;
		if (count($CHAMPS) > 0) {
				for ($i=0;$i < count($CHAMPS);$i++) {
					$reponse = $CHAMPS[$i];
					if ($reponse[4] == 'PRI') {								
						if ($i > 0) $resultat .= " . '&'"; 
						$resultat .= ' . "E' . $reponse[0] . '=" . addslashes($tab[' . $controle . '])';
						$controle++;
				}
			}			
		}			
			
		return $resultat;	
				
		}*/);				
					
		}	
	}
}


/* Creation */
if (isset($_POST["BCreer"])) {		
	header("Location: FrmEdit_/*@{ return ucfirst($TABLE_NAME);}*/.php");				
}

// ########################################
// Nbre denregistrements

$resNbre = $data->executeQuery("SELECT COUNT(*) FROM /*@{ return $TABLE_NAME;}*/;");
$TCount = $util->getLine($resNbre);
$NbreEnreg = 0;
$NbreEnreg = $TCount[0];

// Accès Data
$res = $data->executeQuery("SELECT * FROM /*@{ return $TABLE_NAME;}*/ LIMIT $debut , $fin;");
?>		



<form method="post" action="<?php echo $page;?>" name="F/*@{ return ucfirst($TABLE_NAME);}*/">
<table width="100%"  border="1">
  <tr>
    <td colspan="/*@{ return (count($CHAMPS) + 1);}*/">		
	/*@{
	$resultat = '';
	//$info->selectionnerBdd("generateur");
	//$res = $info->executeQuery("SELECT LIBELLE, IDLSTLNK FROM LNKLISTES WHERE IDLST='" . $IDLST . "' ORDER BY NUMSEQ;");	
		
	//while ($row = $util->getRow($res)) {  		
	//	$resultat .= '<a href="FrmConsulter_' . $row["IDLSTLNK"] . '.php" >' . $row["LIBELLE"] . '</a>' . "\n";		
	//}
			
	return $resultat;
	
	}*/</td>
    </tr>
  <tr>
    <td colspan="/*@{ return (count($CHAMPS) + 1);}*/"><?php echo $NbreEnreg;?> /*@{ return ucfirst($TABLE_NAME);}*/</td>
  </tr>
  <tr>
    <td width="6%">&nbsp;</td>
	/*@{
		$resultat = '';	
		if (count($CHAMPS) > 0) {
			for ($i=0;$i < count($CHAMPS);$i++) {
				$reponse = $CHAMPS[$i];								
				$resultat .= '	<td width="15%">' . $reponse[0] . '</td>' . "\n";							
			}			
		}   
		
		return $resultat;
   }*/</tr>

  <?php
  	while ($enreg = $util->getLine($res)) {  
  		$tab = "  <tr>";
    	$tab .= " <td>";  	
		$tab .= "<input name=\"ID[]\" type=\"checkbox\" id=\"ID[]\" value=\""/*@{
		 	$resultat = '';
			$controle = 0;
			if (count($CHAMPS) > 0) {
				for ($i=0;$i < count($CHAMPS);$i++) {
					$reponse = $CHAMPS[$i];
					if ($reponse[4] == 'PRI') {
						if ($controle > 0) $resultat .= ' . "#"';
						$resultat .= ' . $enreg[' . $i . ']';						
						$controle = 1;
					}
				}			
			}			

			$resultat .= ' . ';
			return $resultat;
		}*/"\">";
		
		$tab .= "</td>";
		/*@{	
			$resultat = '';
			if (count($CHAMPS) > 0) {
				for ($i=0;$i < count($CHAMPS);$i++) {
					$reponse = $CHAMPS[$i];
					$resultat .= '		$tab .= "<td>" . $enreg[' . $i . '] . "</td>";' . "\n";						
				}			
			}
			
			return $resultat;		
			
		}*/
			
		$tab .= "</tr>";
	
		echo $tab;		
  	}  
	
	$data->deconnecter();
  ?>
  <tr>
    <td colspan="/*@{ return (count($CHAMPS) + 1); }*/"><input name="BCreer" type="submit" id="BCreer" value="Creer">
      <input name="BSupprimer" type="submit" id="BSupprimer" value="Supprimer">
        <input name="BModifier" type="submit" id="BModifier" value="Modifier">
        <input name="HSaut" type="hidden" id="HSaut" value="<?php echo $debut; ?>"> 
        <input name="HPage" type="hidden" id="HPage"  value="<?php echo $plage; ?>">        </td>
    </tr>
  <tr>
    <td colspan="/*@{ return (count($CHAMPS) + 1); }*/">&nbsp;</td>
  </tr>
</table>
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
	if ($plage > 0) $lien = "'" . '<a href="' . "'" . ' . $page . ' . "'" . '?HSaut=' . "'" . ' . ($plage - ($EnregMax * $NbrePages)) . ' . "'" . '&HPage=' . "'" . ' . ($plage - ($EnregMax * $NbrePages)) .  ' . "'" . '">&amp;lt;&amp;lt;</a> - ' . "'";
		
	for ($i = 0; $i < $NbreMax;$i++) {
		if ($i > 0) $lien .= "'" . ' - ' . "'";
		$lien .= "'" . '<a href="' . "'" . ' . $page . ' . "'" . '?HSaut=' . "'" . ' . $tour . ' . "'" . '&HPage=' . "'" . ' . $plage .  ' . "'" . '">' . "'" . ' . (floor($tour / $EnregMax) + 1) . ' . "'" . '</a>' . "'";
		$tour += $EnregMax;	
	}
	
	// Droite
	if (($plage + ($EnregMax * $NbrePages)) < $NbreEnreg) $lien .= "'" . ' - <a href="' . "'" . ' . $page . ' . "'" . '?HSaut=' . "'" . ' . $tour . ' . "'" . '&HPage=' . "'" . ' . $tour . ' . "'" . '">&amp;gt;&amp;gt;</a>' . "'";
	
	echo $lien;	
	
	$pied = "</td>";
  	$pied .= "</tr>";
	$pied .= "</table>";
		
	echo $pied;
}
?>
</form>