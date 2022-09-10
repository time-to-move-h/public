<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Consulter 
// Table : groups

// Inclusion de bibliothèque
require('../CLASS/IO/DatabaseCoreClass.php');
require('../CLASS/IO/DataUtilsClass.php');
require('DBApplicationClass.php');

// Instanciation
$data = new DBApplication();
$util = new DataUtils();
$page = $_SERVER["PHP_SELF"];

// Connexion Mysql
$data->connecterDB();

// Initialisation des Champs primaire			
$IDGRP = '';
	

$IDGRP = trim(isset($_GET['EIDGRP']) ? $_GET['EIDGRP'] : '');
	

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
function supprimerGroups($IDGRP) {
	global $data;
	return $data->executeQuery("DELETE FROM groups WHERE IDGRP = $IDGRP;");
}

/* Suppression */
if (isset($_POST["BSupprimer"])) {
	if (isset($_POST["ID"])) {
		$id = $_POST["ID"];
	    for ($i = 0; $i < count($id); $i++) {
			$tab = preg_split("/[#]+/", $id[$i]);
			if (count($tab > 0)) {
					supprimerGroups(addslashes($tab[0]));					
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
			header("Location: edit_groups.php?" . "EIDGRP=" . addslashes($tab[0]));									
		}	
	}
}


/* Creation */
if (isset($_POST["BCreer"])) {		
	header("Location: edit_groups.php");				
}

// ########## LINK LISTE #########



// ########################################
// Accès Data
$strSql = "Select * From groups";
// Nbre d'enregistrements
$resNb = $data->executeQuery($strSql);
$NbreEnreg = $util->getNbreEnreg($resNb);
// Selection Enregistrements 
$res = $data->executeQuery("$strSql LIMIT $debut , $fin;");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Moviao Admin - Movimiento Ahora !</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Moviao Admin</a>
            </div>
            
            <!-- Top Menu Items -->
			<?php require_once('inc/menu_top.inc.php'); ?>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <?php require_once('inc/menu.inc.php'); ?>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Dashboard <small>TODO: A configurer </small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active"><i class="fa fa-dashboard"></i> TODO: a configurer</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                
                <form method="post" action="<?php echo $page;?>" name="FGroups">
                    <div class="CSSTableGenerator">
                    <table width="100%"  border="1">
                      <tr>
                        <td colspan="9">		
                        </td>
                        </tr>
                      <tr>
                        <td colspan="9"><?php echo $NbreEnreg;?> Groups</td>
                      </tr>
                      <tr>
                        <td width="6%">&nbsp;</td>
                        	<td width="15%">ID</td>
	<td width="15%">Name</td>
	<td width="15%">Title</td>
	<td width="15%">Logo</td>
	<td width="15%">Category</td>
	<td width="15%">Description</td>
	<td width="15%">Country</td>
	<td width="15%">City</td>
</tr>
                    
                      <?php
                        while ($enreg = $util->getLineObject($res)) {  
                            $tab = "  <tr>";
                            $tab .= " <td>";  	
                            $tab .= "<input name=\"ID[]\" type=\"checkbox\" id=\"ID[]\" value=\"" . $enreg->IDGRP . "\"/>";
                            
                            $tab .= "</td>";
                    		$tab .= "<td>" . $enreg->IDGRP . "</td>";
		$tab .= "<td>" . $enreg->GRP_NAME . "</td>";
		$tab .= "<td>" . $enreg->GRP_TITLE . "</td>";
		$tab .= "<td>" . $enreg->GRP_LOGO . "</td>";
		$tab .= "<td>" . $enreg->IDCATGRP . "</td>";
		$tab .= "<td>" . $enreg->GRP_DESC . "</td>";
		$tab .= "<td>" . $enreg->IDCOUNTRY . "</td>";
		$tab .= "<td>" . $enreg->IDCITY . "</td>";

                                
                            $tab .= "</tr>";
                        
                            echo $tab;		
                        }  
                        
                        $data->deconnecter();
                      ?>
                      <tr>
                        <td colspan="9"><input name="BCreer" type="submit" id="BCreer" value="Creer">
                          <input name="BSupprimer" type="submit" id="BSupprimer" value="Supprimer">
                            <input name="BModifier" type="submit" id="BModifier" value="Modifier">        
                                    
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
                        $var_pri = "&EIDGRP=$IDGRP";		
                        
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
                

            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>