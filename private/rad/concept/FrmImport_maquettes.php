<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Import Maquette

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
$info->setConnexion($data->getConnexion());

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

<form action="<?php echo $page; ?>" method="post" name="FMaquettes" id="FMaquettes">

<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Import Maquette</div></td>
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
<!-- Free template distributed by http://freehtml5templates.com -->
</body>
</html>