<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Listes

// Inclusion de bibliothèque
require('../../lib/IO/DatabaseCoreClass.php');
require('DBApplicationClass.php');
require('../../lib/IO/DataUtilsClass.php');
require('../../lib/IO/DatabaseInfoClass.php');
require('../../lib/IO/SQL/SqlGeneratorClass.php');

// Instanciation
$data = new DBApplication();
$util = new DataUtils();
$info = new DatabaseInfo();

// Connexion Mysql
$data->connecterDB();
//$info->setConnexion($data->getConnexion());
//$info->connecter("localhost:3306","root","", "generateur");

// Initialisation des Champs			
$IDAPP = "";
$IDLST = "";
$LIBELLE = "";
$DESCRIPTION = "";
$REQUETE = "";	
									
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

$DATABASE_NAME = (isset($_POST["LDatabases"])) ? $_POST["LDatabases"] : '';
$TABLE_NAME = (isset($_POST["LTables"])) ? $_POST["LTables"] : '';

$IDAPP = trim(isset($_GET['EIDAPP']) ? $_GET['EIDAPP'] : '');
if (strlen($IDAPP) <=0) $IDAPP = trim(isset($_POST['EIDAPP']) ? $_POST['EIDAPP'] : '');

$LIBELLE = trim(isset($_POST['ELIBELLE']) ? $_POST['ELIBELLE'] : '');
$DESCRIPTION = trim(isset($_POST['EDESCRIPTION']) ? $_POST['EDESCRIPTION'] : '');
if (isset($_POST['EDESCRIPTION']) && (strlen($DESCRIPTION) <= 0))  $DESCRIPTION = 'null';
$REQUETE = trim(isset($_POST['EREQUETE']) ? $_POST['EREQUETE'] : '');
if (isset($_POST['EREQUETE']) && (strlen($REQUETE) <= 0))  $REQUETE = 'null';


// Ajout
function ajouterListes($IDAPP, $DATABASE_NAME, $TABLE_NAME, $LIBELLE, $DESCRIPTION, $REQUETE) {
	global $data, $info, $message;
	
	// Insert List
	$strSql = "INSERT INTO maquettes (IDAPP, IDMAQ, LIBELLE, DESCRIPTION, REQUETE) VALUES ('$IDAPP', '$TABLE_NAME', '$LIBELLE', '$DESCRIPTION', '$REQUETE');";
	$data->executeQuery($strSql);
	
	// Insert Fields	
	$tableau = $info->getFields($DATABASE_NAME, $TABLE_NAME);				
	for ($i=0;$i < count($tableau);$i++) {
		$reponse = $tableau[$i];		
//		for ($j=0;$j < count($reponse) / 2;$j++) {
//			echo "Info : " . $reponse[$j] . "<br>";
//		}		
		//exit();
		$E_IDCHP = $reponse[0];
		$E_LIBELLE = $reponse[8];
		$E_DESCRIPTION = 'null';
		$E_REQUETE = 'null';	
		$E_NUMSEQ = $i;
		$E_TYPEDONNEE = $reponse[1];
		if ($reponse[3] == 'NO') { $E_NUL = 0; } else { $E_NUL = 1; }
		if ($reponse[4] == 'PRI') { $E_PRIMAIRE = 1; } else { $E_PRIMAIRE = 0; }
		$E_VISIBLE	= 1;
		if ($reponse[6] == 'auto_increment') { $E_AI = 1; } else { $E_AI = 0; }
		$E_TAILLE = 0;						
		
		// Insert Sous Liste
		$strSql = "INSERT INTO sousmaquettes (IDAPP, IDMAQ, IDCHP, LIBELLE, DESCRIPTION, REQUETE, NUMSEQ, TYPEDONNEE, PRIMAIRE, VISIBLE, TAILLE, AI, NUL) VALUES ('$IDAPP', '$TABLE_NAME', '$E_IDCHP', '$E_LIBELLE', $E_DESCRIPTION, '$REQUETE', $E_NUMSEQ, '$E_TYPEDONNEE', $E_PRIMAIRE, $E_VISIBLE, $E_TAILLE,$E_AI,$E_NUL);";
		//echo $strSql;
		$data->executeQuery($strSql);
		//break;
		//echo '<option value="' . $reponse[0] . '">' . $reponse[0] . '</option>';						
	}	
	
	$message = "Importation OK";
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {
					
	if (ajouterListes($IDAPP, $DATABASE_NAME, $TABLE_NAME, $LIBELLE, $DESCRIPTION, $REQUETE)) {
		$message = "Insertion OK";	
	} 

	//$IDAPP = "";
	//$IDLST = "";
	//$LIBELLE = "";
	//$DESCRIPTION = "";
	//$REQUETE = "";	
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Document sans titre</title>
<style type="text/css">
<!--
body {
	font: 100%/1.4 Verdana, Arial, Helvetica, sans-serif;
	background-color: #42413C;
	margin: 0;
	padding: 0;
	color: #000;
}
/* ~~ Sélecteurs d'éléments/balises ~~ */
ul, ol, dl { /* En raison des variations entre les navigateurs, il est conseillé d'attribuer une valeur de zéro aux marges intérieures et aux marges des listes. A des fins de cohérence, vous pouvez définir les valeurs désirées dans cette zone ou dans les éléments de liste (LI, DT, DD) qu'elle contient. N'oubliez pas que les paramètres que vous définissez ici se répercuteront sur la liste .nav, sauf si vous rédigez un sélecteur plus spécifique. */
	padding: 0;
	margin: 0;
}
h1, h2, h3, h4, h5, h6, p {
	margin-top: 0;	 /* la suppression de la marge supérieure résout un problème où les marges sortent de leur bloc conteneur. La marge inférieure restante l'éloignera de tout élément qui suit. */
	padding-right: 15px;
	padding-left: 15px; /* l'ajout de la marge intérieure aux côtés des éléments à l'intérieur des blocs, et non aux éléments de bloc proprement dit, évite le recours à des calculs de modèle de boîte. Une autre méthode consiste à employer un bloc imbriqué avec marge intérieure latérale. */
}
a img { /* ce sélecteur élimine la bordure bleue par défaut affichée dans certains navigateurs autour d'une image lorsque celle-ci est entourée d'un lien. */
	border: none;
}
/* ~~ La définition du style des liens de votre site doit respecter cet ordre, y compris le groupe de sélecteurs qui créent l'effet de survol. ~~ */
a:link {
	color: #42413C;
	text-decoration: underline; /* à moins que vous ne définissiez un style particulièrement exclusif pour vos liens, mieux vaut prévoir un soulignement, qui garantit une identification visuelle rapide. */
}
a:visited {
	color: #6E6C64;
	text-decoration: underline;
}
a:hover, a:active, a:focus { /* ce groupe de sélecteurs offrira à un navigateur au clavier la même expérience de survol que celle d'une personne employant la souris. */
	text-decoration: none;
}
/* ~~ Ce conteneur à largeur fixe entoure tous les autres blocs ~~ */
.container {
	width: 960px;
	background-color: #FFFFFF;
	margin: 0 auto; /* la valeur automatique sur les côtés, associée à la largeur, permet de centrer la mise en page */
}
/* ~~ Aucune largeur n'est attribuée à l'en-tête. Il occupera toute la largeur de votre mise en page. ~~ */
header {
	background-color: #ADB96E;
}
/* ~~ Colonnes pour la mise en page. ~~ 

1) La marge intérieure n'est placée qu'en haut et/ou en bas des éléments de bloc. Les éléments à l'intérieur de ces blocs posséderont une marge intérieure sur les côtés. Vous évitez ainsi de devoir recourir à des « calculs de modèle de boîte ». N'oubliez pas que si vous ajoutez une marge intérieure latérale ou une bordure au bloc proprement dit, elle sera ajoutée à la largeur que vous définissez pour créer la largeur totale. Vous pouvez également supprimer la marge intérieure de l'élément dans l'élément de bloc et placer un second élément de bloc à l'intérieur, sans largeur et possédant une marge intérieure appropriée pour votre concept.

2) Toutes les colonnes étant flottantes, aucune marge ne leur a été attribuée. Si vous devez ajouter une marge, évitez de la placer du côté vers lequel vous effectuez le flottement (par exemple, une marge droite sur un bloc configuré pour flotter vers la droite). Dans de nombreux cas, vous pouvez plutôt employer une marge intérieure. Pour les blocs où il est impossible de respecter cette règle, ajoutez une déclaration «  » à la règle de l'élément de bloc afin de contourner un bogue qui amène certaines versions d'Internet Explorer à doubler la marge.

3) Comme des classes peuvent être employées à plusieurs reprises dans un document (et que plusieurs classes peuvent aussi être attribuées à un élément), les colonnes ont reçu des noms de classes au lieu d'ID. Par exemple, deux blocs de barre latérale peuvent être empilés si nécessaire. Elles peuvent être très facilement remplacées par des ID si vous le souhaitez, pour autant que vous ne les utilisiez qu'une fois par document.

4) Si vous préférez que la navigation se trouve à gauche et pas à droite, faites flotter ces colonnes en sens opposé (toutes vers la gauche au lieu de vers la droite). Leur rendu s'effectuera dans l'ordre inverse. Il n'est pas nécessaire de déplacer les blocs dans le code HTML source.

*/
.sidebar1 {
	float: right;
	width: 180px;
	background-color: #EADCAE;
	padding-bottom: 10px;
}
.content {
	padding: 10px 0;
	width: 780px;
	float: right;
}

/* ~~ Ce sélecteur groupé donne de l'espace aux listes dans la zone de contenu ~~ */
.content ul, .content ol {
	padding: 0 15px 15px 40px; /*  cette marge intérieure reflète la marge intérieure droite dans les en-têtes et la règle de paragraphe ci-dessus. Une marge intérieure a été placée en bas, afin d'assurer un espace entre les autres éléments des listes, et à gauche pour créer le retrait. Vous pouvez les régler comme bon vous semble. */
}

/* ~~ Styles de liste de navigation (peuvent être supprimés si vous optez pour un menu de survol prédéfini tel que Spry) ~~ */
ul.nav {
	list-style: none; /*  entraîne la suppression du marqueur de liste */
	border-top: 1px solid #666; /*  crée la bordure supérieure des liens ; les autres sont placées à l'aide d'une bordure inférieure sur la balise LI */
	margin-bottom: 15px; /*  crée l'espace entre la navigation et le contenu en dessous */
}
ul.nav li {
	border-bottom: 1px solid #666; /*  crée la séparation des boutons */
}
ul.nav a, ul.nav a:visited { /*  le regroupement de ces sélecteurs garantit que vos liens conservent leur apparence de bouton, même après avoir été activés */
	padding: 5px 5px 5px 15px;
	display: block; /*  attribue au bloc de liens des propriétés qui lui font remplir toute la balise LI qui le contient. Force la zone entière à réagir à un clic de souris. */
	width: 160px;  /*cette largeur permet de cliquer sur le bouton entier dans IE6. Si la compatibilité avec IE6 n'est pas nécessaire, vous pouvez la supprimer. Pour calculer la largeur approprié, soustrayez la marge intérieure de ce lien de la largeur du conteneur de barre latérale. */
	text-decoration: none;
	background-color: #C6D580;
}
ul.nav a:hover, ul.nav a:active, ul.nav a:focus { /*  modifie la couleur de l'arrière-plan et du texte pour les navigateurs à la souris et au clavier. */
	background-color: #ADB96E;
	color: #FFF;
}

/* ~~ Pied de page ~~ */
footer {
	padding: 10px 0;
	background-color: #CCC49F;
	position: relative;/* donne hasLayout à IE6 de façon à permettre un effacement correct */
	clear: both; /* cette propriété d'effacement force le .container à comprendre où se terminent les colonnes et à les contenir */
}

/*Prise en charge de HTML5 - Définit les nouvelles balises HTML5 sur display:block afin que les navigateurs sachent comment effectuer un rendu correct des balises. */
header, section, footer, aside, article, figure {
	display: block;
}
-->
</style><!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]--></head>

<body>

<div class="container">
  <header>&nbsp;</header>
  
<?php require_once('menu.inc.php'); ?>

  <article class="content">
    <h1>Loan System</h1>
    <section>
     <h2>Import Liste</h2>
      <p><form action="<?php echo $page; ?>" method="post" name="FListes" id="FListes">

<table width="500" border="1" align="center">
  
	<tr>
	  <td height="12" colspan="2"><div align="center">Listes</div></td>
	  </tr>
	<tr>
<td>IDAPP</td>
<td><input type="text" name="EIDAPP" id="EIDAPP" value="<?php echo $IDAPP; ?>"></td>
</tr>
	<tr>
	  <td>LIBELLE</td>
	  <td><input type="text" name="ELIBELLE" id="ELIBELLE" value="<?php echo $LIBELLE; ?>"></td>
	  </tr>
	<tr>
	  <td>DESCRIPTION</td>
	  <td><input type="text" name="EDESCRIPTION" id="EDESCRIPTION" value="<?php echo $DESCRIPTION; ?>"></td>
	  </tr>
	<tr>
	  <td>REQUETE</td>
	  <td><input type="text" name="EREQUETE" id="EREQUETE" value="<?php echo $REQUETE; ?>"></td>
	  </tr>
<tr>
  <td colspan="2"><select name="LDatabases" id="LDatabases">
    <?php
	  	$tableau = $info->getDatabases();
		echo count($tableau);
		for ($i=0;$i < count($tableau);$i++) {
			$selection = '';
			if ($DATABASE_NAME == $tableau[$i]) $selection = 'selected';
			echo '<option value="' . $tableau[$i] . '" ' . $selection .'>' . $tableau[$i] . '</option>';	  
		}
	  ?>
    </select>
    <input name="SelDB" type="submit" id="SelDB" value="Selection"></td>
</tr>
<tr>
  <td colspan="2"><select name="LTables" id="LTables">
    <?php
	  	if (! empty($DATABASE_NAME)) {
			$database_name = $DATABASE_NAME;
				$tableau = $info->getTables($database_name);
				for ($i=0;$i < count($tableau);$i++) {
					$selection = '';
					if ($TABLE_NAME == $tableau[$i]) $selection = 'selected';
					echo '<option value="' . $tableau[$i] . '" ' . $selection . '>' . $tableau[$i] . '</option>';		  				
				}
		}	  
		
		// Deconnexion
		$data->deconnecter();
	  ?>
  </select></td>
</tr>


						
	  <tr>
      <td colspan="2"><input name="BEnregistrer" type="submit" id="BEnregistrer" value="Importer"></td>
      </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?></td>
    </tr>
	</table>
</form>
</p>
    </section>
    <!-- end .content --></article>
  <footer>
    <p>HD CONCEPT SYSTEM </p>
  </footer>
  <!-- end .container --></div>
</body>
</html>