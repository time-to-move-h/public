<?php

// @author Moviao Inc
// Tous Droits réservés 2015.
// Fiche Modifier 
// Table : Listes

// Inclusion de bibliothèque
require('../../../lib/Moviao/Database/DatabaseCore.php');
require('DBApplicationClass.php');
//require('../../class/IO/DataUtilsClass.php');
require('../../../lib/Moviao/Database/DatabaseInfo.php');
require('../../../lib/Moviao/Database//SQL/SqlGenerator.php');

// Instanciation
$data = new \Moviao\Database\DBApplication();
$info = new \Moviao\Database\DatabaseInfo();

// Connexion Mysql
$data->connecterDB();
$info->setConnexion($data->getConnexion());

// Initialisation des Champs			
$IDAPP = "";
$IDAPPDAT = "";
$IDENG=7;
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


$IDAPPDAT = trim(isset($_GET['EIDAPPDAT']) ? $_GET['EIDAPPDAT'] : '');
if (strlen($IDAPPDAT) <=0) $IDAPPDAT = trim(isset($_POST['EIDAPPDAT']) ? $_POST['EIDAPPDAT'] : '');

$IDENG = trim(isset($_POST['EENGINE']) ? $_POST['EENGINE'] : 7);
$LIBELLE = trim(isset($_POST['ELIBELLE']) ? $_POST['ELIBELLE'] : '');
$DESCRIPTION = trim(isset($_POST['EDESCRIPTION']) ? $_POST['EDESCRIPTION'] : '');
if (isset($_POST['EDESCRIPTION']) && (strlen($DESCRIPTION) <= 0))  $DESCRIPTION = '';
$REQUETE = trim(isset($_POST['EREQUETE']) ? $_POST['EREQUETE'] : '');
if (isset($_POST['EREQUETE']) && (strlen($REQUETE) <= 0))  $REQUETE = '';








// Ajout
function ajouterListes($IDAPP, $DATABASE_NAME, $TABLE_NAME, $LIBELLE, $DESCRIPTION, $REQUETE,$IDENG) {
	global $data,$info,$message;	
	// Insert List
	$strSql = "INSERT INTO apps_data (IDAPP, TAB, LIBELLE, DESCRIPTION, REQUETE, IDENG) VALUES ('$IDAPP', '$TABLE_NAME', '$LIBELLE', '$DESCRIPTION', '$REQUETE',$IDENG);";
	$stmt = $data->executeQuery($strSql);
        $IDAPPDAT = $data->lastInsertId();        	
	// Insert Fields	
	$tableau = $info->getFields($DATABASE_NAME, $TABLE_NAME);
        //var_dump($tableau);        
	for ($i=0;$i < count($tableau);$i++) {
		$reponse = $tableau[$i];		
//		for ($j=0;$j < count($reponse) / 2;$j++) {
//			echo "Info $i : " . ($reponse->Field) . "<br>";
//		}		
		//exit();
		$E_IDCHP = $reponse->Field;
		$E_LIBELLE = addslashes($reponse->Comment);
		$E_DESCRIPTION = 'NULL';
		$E_REQUETE = 'NULL';	
		$E_NUMSEQ = $i;
		$E_TYPEDONNEE = $reponse->Type;
		if ($reponse->Null == 'NO') { $E_NUL = 0; } else { $E_NUL = 1; }
		if ($reponse->Key == 'PRI') { $E_PRIMAIRE = 1; } else { $E_PRIMAIRE = 0; }
		$E_VISIBLE	= 1;
		if ($reponse->Extra == 'auto_increment') { $E_AI = 1; } else { $E_AI = 0; }
		$E_TAILLE = 0;						
		
		// Insert Sous Liste
		$strSql = "INSERT INTO apps_subdata (IDAPP, IDAPPDAT, IDCHP, LIBELLE, DESCRIPTION, REQUETE, NUMSEQ, TYPEDONNEE, PRIMAIRE, VISIBLE, TAILLE,AI,NUL) VALUES ('$IDAPP', $IDAPPDAT,'$E_IDCHP', '$E_LIBELLE', $E_DESCRIPTION, $E_REQUETE, $E_NUMSEQ, '$E_TYPEDONNEE', $E_PRIMAIRE, $E_VISIBLE, $E_TAILLE,$E_AI,$E_NUL);";
		//echo $strSql;
		$data->executeQuery($strSql);	                
            }		
                $message = "Importation OK";
}

// Update Field
function update($IDAPP, $IDAPPDAT, $DATABASE_NAME, $TABLE_NAME) {
	global $data,$info,$message;		                	
	// Insert Fields	
	$tableau = $info->getFields($DATABASE_NAME, $TABLE_NAME);				
	for ($i=0;$i < count($tableau);$i++) {
                
                //echo var_dump($tableau) . "<br><br>";
            
                $reponse = $tableau[$i];		
                $bfound = false;                
                $strSql = "SELECT * FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;";
                $res = $data->executeQuery($strSql);
                if (!$res) return;                                
                while ($obj = $data->fetchObject()) {
                    if ($obj->IDCHP == $reponse->Field) {
                        $bfound = true;
                        break;
                    }       
                }
                
                if (!$bfound) {                                       
                   
                    $E_IDCHP = $reponse->Field;
                    $E_LIBELLE = addslashes($reponse->Comment);
                    $E_DESCRIPTION = 'null';
                    $E_REQUETE = 'NULL';	
                    $E_NUMSEQ = $i;
                    $E_TYPEDONNEE = $reponse->Type;
                    if ($reponse->Null == 'NO') { $E_NUL = 0; } else { $E_NUL = 1; }
                    if ($reponse->Key == 'PRI') { $E_PRIMAIRE = 1; } else { $E_PRIMAIRE = 0; }
                    $E_VISIBLE	= 1;
                    if ($reponse->Extra == 'auto_increment') { $E_AI = 1; } else { $E_AI = 0; }
                    $E_TAILLE = 0;								
                    // Insert Sous Liste
                    $strSql = "INSERT INTO apps_subdata (IDAPP, IDAPPDAT, IDCHP, LIBELLE, DESCRIPTION, REQUETE, NUMSEQ, TYPEDONNEE, PRIMAIRE, VISIBLE, TAILLE,AI,NUL) VALUES ('$IDAPP', $IDAPPDAT,'$E_IDCHP', '$E_LIBELLE', $E_DESCRIPTION, $E_REQUETE, $E_NUMSEQ, '$E_TYPEDONNEE', $E_PRIMAIRE, $E_VISIBLE, $E_TAILLE,$E_AI,$E_NUL);";
                    echo $strSql . '<br>';
                    $data->executeQuery($strSql);
                                        
                } else {
                    $E_IDCHP = $reponse->Field;
                    $E_LIBELLE = $reponse->Comment;
                    $E_DESCRIPTION = 'null';
                    $E_REQUETE = 'NULL';	
                    $E_NUMSEQ = $i;
                    $E_TYPEDONNEE = $reponse->Type;
                    if ($reponse->Null == 'NO') { $E_NUL = 0; } else { $E_NUL = 1; }
                    if ($reponse->Key == 'PRI') { $E_PRIMAIRE = 1; } else { $E_PRIMAIRE = 0; }
                    $E_VISIBLE	= 1;
                    if ($reponse->Extra == 'auto_increment') { $E_AI = 1; } else { $E_AI = 0; }
                    $E_TAILLE = 0;								
                    // Insert Sous Liste
                    $strSql = "UPDATE apps_subdata SET LIBELLE='$E_LIBELLE',NUL=$E_NUL WHERE IDAPP='$IDAPP' AND $IDAPPDAT=$IDAPPDAT AND IDCHP='$E_IDCHP';";
                    //echo $strSql;
                    $data->executeQuery($strSql);                    
                }
                
        }	
        
        
        // Phase Numero 2 : Supprimer les champs non existants                                
        $strSql = "SELECT * FROM apps_subdata WHERE IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT;";
        $res = $data->executeQuery($strSql);
        if (!$res) return;                                
        while ($obj = $data->fetchObject()) {
            $bfound = false;
            for ($i=0;$i < count($tableau);$i++) {
                $reponse = $tableau[$i];
                if ($obj->IDCHP == $reponse->Field) {
                    $bfound = true;
                    break;
                }                 
            }
            if (!$bfound) {     
                 //echo "test " . $obj->IDCHP . " = " . $obj->REQUETE . "<br>";
                if (strlen($obj->REQUETE) <=0 || $obj->REQUETE == 'null') {       
                    //echo "delete " . $obj->IDCHP . " = " . $reponse[0] . "<br>";
                    $strSql = "DELETE FROM apps_subdata WHERE IDAPP='$IDAPP' AND $IDAPPDAT=$IDAPPDAT AND IDCHP='{$obj->IDCHP}';";
                }
                //echo $strSql;
                $data->executeQuery($strSql); 
            }
                    
        }
        // -----------------------------------------------------
        $message = "Update OK";
}

// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {					
    if (ajouterListes($IDAPP, $DATABASE_NAME, $TABLE_NAME, $LIBELLE, $DESCRIPTION, $REQUETE,$IDENG)) {
        $message = "Insertion OK";	
    } 
}

// Récupération du formulaire
if (isset($_POST["BUpdate"])) {       
    if (update($IDAPP,$IDAPPDAT,$DATABASE_NAME, $TABLE_NAME)) {
        $message = "Update OK";	
    } 
}


// Lecture 

// EIDAPP=MOVIAO&EIDAPPDAT=71
$table_selected = '';
if (strlen($IDAPP) >0 && strlen($IDAPPDAT)>0) {
    
    
    // Accès Data
    $strSql = "Select * From apps_data Where IDAPP='$IDAPP' AND IDAPPDAT=$IDAPPDAT";        
    $res = $data->executeQuery($strSql);
    
    if ($enreg = $data->fetchObject()) { 
        $IDENG = $enreg->IDENG;
        $table_selected = $enreg->TAB;
        
         //exit(var_dump($table_selected));
    }  
    
    
}



?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Import Table</title>
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

<form action="<?php echo $page; ?>" method="post" name="FListes" id="FListes">

<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Template</div></td>
        </tr>
        <tr>
          <td>IDAPP</td>
          <td><input type="text" name="EIDAPP" id="EIDAPP" value="<?php echo $IDAPP; ?>"></td>
        </tr>
        <tr>
          <td>IDAPPDATA</td>
          <td><input type="text" name="EIDAPPDAT" id="EIDAPPDAT" value="<?php echo $IDAPPDAT; ?>"></td>
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
          <td>Engine</td>
          <td><input type="text" name="EENGINE" id="EENGINE" value="<?php echo $IDENG; ?>"></td>
        </tr>
        <tr>
          <td colspan="2"><select name="LDatabases" id="LDatabases">
            <?php
	  	$tableau = $info->getDatabases();
		echo count($tableau);
		for ($i=0;$i < count($tableau);$i++) {
			$selection = '';
			if ($DATABASE_NAME == $tableau[$i] || $IDAPP == strtoupper($tableau[$i]) ) {
                            //$DATABASE_NAME = $IDAPP;
                            $selection = 'selected';
                        }
			echo '<option value="' . $tableau[$i] . '" ' . $selection .'>' . $tableau[$i] . '</option>';	  
		}
	  ?>
          </select>
            <input name="SelDB" type="submit" id="SelDB" value="Selection"></td>
        </tr>
        <tr>
          <td colspan="2">
              <?php //$DATABASE_NAME = "moviao"; ?>
              
              <select name="LTables" id="LTables">
            <?php
                if (empty($DATABASE_NAME)) $DATABASE_NAME = "moviao";
	  	if (! empty($DATABASE_NAME)) {
                    $database_name = $DATABASE_NAME;
                    $tableau = $info->getTables($database_name);
                    for ($i=0;$i < count($tableau);$i++) {
                            $selection = '';
                            if ($TABLE_NAME == $tableau[$i] || strtoupper($table_selected) == strtoupper($tableau[$i])) $selection = 'selected';
                            echo '<option value="' . $tableau[$i] . '" ' . $selection . '>' . $tableau[$i] . '</option>';		  				
                    }
		}	  
		
		// Deconnexion
		//$data->disconnect();
	  ?>
          </select></td>
        </tr>
        <tr>
          <td colspan="2">              
              <input name="BEnregistrer" type="submit" id="BEnregistrer" value="Importer">
              <input name="BUpdate" type="submit" id="BUpdate" value="Update">          
          </td>
        </tr>
        

        
        
        <tr>
          <td colspan="2">&nbsp;<?php echo $message; ?></td>
        </tr>
      </table>
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
<!-- Free template distributed by http://freehtml5templates.com -->
</body>
</html>