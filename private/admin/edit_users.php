<?php

// @author HD CONCEPT SPRL !
// Tous Droits réservés 2014.
// Fiche Modifier 
// Table : Users

// Inclusion de bibliothèque
require('../CLASS/IO/DatabaseCoreClass.php');
require('../CLASS/IO/DataUtilsClass.php');
require('DBApplicationClass.php');

// Instanciation
$data = new DBApplication();
$util = new DataUtils();

// Connexion Mysql
$data->connecterDB();

// Initialisation des Champs			

$ACTIVE = '';
$ACTIVE_ori = '';
$CITY = '';
$CITY_ori = '';
$DATEINS = '';
$DATEINS_ori = '';
$DATELAST = '';
$DATELAST_ori = '';
$DATEMOD = '';
$DATEMOD_ori = '';
$DBIRTH = '';
$DBIRTH_ori = '';
$FNAME = '';
$FNAME_ori = '';
$IDACCTYP = '';
$IDACCTYP_ori = '';
$IDCOUNTRY = '';
$IDCOUNTRY_ori = '';
$IDSEX = '';
$IDSEX_ori = '';
$IDSTCON = '';
$IDSTCON_ori = '';
$IDSTMA = '';
$IDSTMA_ori = '';
$IDUSER = '';
$IDUSER_ori = '';
$LNAME = '';
$LNAME_ori = '';
$sMail = '';
$MAIL_ori = '';
$NNAME = '';
$NNAME_ori = '';
$PCODE = '';
$PCODE_ori = '';
$PWD = '';
$PWD_ori = '';
$STREET = '';
$STREET_ori = '';
					
									
$mode = 0;
$message = "";
$page = $_SERVER["PHP_SELF"];
$serveur = $_SERVER["SERVER_ADDR"];

// Ajout
function ajouterUsers($IDUSER,$NNAME,$FNAME,$LNAME,$DBIRTH,$STREET,$PCODE,$CITY,$IDCOUNTRY,$MAIL,$PWD,$IDSEX,$IDSTMA,$IDSTCON,$IDACCTYP,$ACTIVE,$DATEINS,$DATEMOD,$DATELAST) {
	global $data;			
		
	$NNAME = addslashes($NNAME);
	$FNAME = addslashes($FNAME);
	$LNAME = addslashes($LNAME);
	$DBIRTH = addslashes($DBIRTH);
	$STREET = addslashes($STREET);
	$PCODE = addslashes($PCODE);
	$CITY = addslashes($CITY);
	$MAIL = addslashes($MAIL);
	$PWD = addslashes($PWD);
	$ACTIVE = addslashes($ACTIVE);
	$DATEINS = addslashes($DATEINS);
	$DATEMOD = addslashes($DATEMOD);
	$DATELAST = addslashes($DATELAST);
			
	$strSql = "INSERT INTO users (ACTIVE, CITY, DATEINS, DATELAST, DATEMOD, DBIRTH, FNAME, IDACCTYP, IDCOUNTRY, IDSEX, IDSTCON, IDSTMA, IDUSER, LNAME, MAIL, NNAME, PCODE, PWD, STREET) VALUES (";
	$strSql .= ((strlen($ACTIVE) <= 0) ? "0" :  $ACTIVE);
	$strSql .= ", " . ((strlen($CITY) <= 0) ? "NULL" :  "'$CITY'");
	$strSql .= ", " . ((strlen($DATEINS) <= 0) ? "''" :  "'$DATEINS'");
	$strSql .= ", " . ((strlen($DATELAST) <= 0) ? "NULL" :  "'$DATELAST'");
	$strSql .= ", " . ((strlen($DATEMOD) <= 0) ? "NULL" :  "'$DATEMOD'");
	$strSql .= ", " . ((strlen($DBIRTH) <= 0) ? "NULL" :  "'$DBIRTH'");
	$strSql .= ", " . ((strlen($FNAME) <= 0) ? "NULL" :  "'$FNAME'");
	$strSql .= ", " . ((strlen($IDACCTYP) <= 0) ? "0" :  $IDACCTYP);
	$strSql .= ", " . ((strlen($IDCOUNTRY) <= 0) ? "NULL" :  $IDCOUNTRY);
	$strSql .= ", " . ((strlen($IDSEX) <= 0) ? "NULL" :  $IDSEX);
	$strSql .= ", " . ((strlen($IDSTCON) <= 0) ? "NULL" :  $IDSTCON);
	$strSql .= ", " . ((strlen($IDSTMA) <= 0) ? "NULL" :  $IDSTMA);
	$strSql .= ", " . ((strlen($IDUSER) <= 0) ? "0" :  $IDUSER);
	$strSql .= ", " . ((strlen($LNAME) <= 0) ? "NULL" :  "'$LNAME'");
	$strSql .= ", " . ((strlen($MAIL) <= 0) ? "''" :  "'$MAIL'");
	$strSql .= ", " . ((strlen($NNAME) <= 0) ? "NULL" :  "'$NNAME'");
	$strSql .= ", " . ((strlen($PCODE) <= 0) ? "NULL" :  "'$PCODE'");
	$strSql .= ", " . ((strlen($PWD) <= 0) ? "''" :  "'$PWD'");
	$strSql .= ", " . ((strlen($STREET) <= 0) ? "NULL" :  "'$STREET'");
	$strSql .= ")";		
	return $data->executeQuery($strSql);
}

// Modification
function modifierUsers($ACTIVE, $CITY, $DATEINS, $DATELAST, $DATEMOD, $DBIRTH, $FNAME, $IDACCTYP, $IDCOUNTRY, $IDSEX, $IDSTCON, $IDSTMA, $IDUSER, $LNAME, $MAIL, $NNAME, $PCODE, $PWD, $STREET) {
	global $data, $util, $ACTIVE_ori, $CITY_ori, $DATEINS_ori, $DATELAST_ori, $DATEMOD_ori, $DBIRTH_ori, $FNAME_ori, $IDACCTYP_ori, $IDCOUNTRY_ori, $IDSEX_ori, $IDSTCON_ori, $IDSTMA_ori, $IDUSER_ori, $LNAME_ori, $MAIL_ori, $NNAME_ori, $PCODE_ori, $PWD_ori, $STREET_ori;
	$strSql = 'UPDATE users SET ';
	$strSql_Req = '';		

	if (testUsers($IDUSER_ori)) {			
				
	$result = getSingleRowUsers(	$IDUSER_ori);
	$ligne = $util->getFirstLineObject($result);	
	// Récupération d'un tableau d'enregistrement	
	if (count($ligne) > 0) {
	
		
		$ACTIVE_ori = $ligne->ACTIVE;
		$CITY_ori = $ligne->CITY;
		$DATEINS_ori = $ligne->DATEINS;
		$DATELAST_ori = $ligne->DATELAST;
		$DATEMOD_ori = $ligne->DATEMOD;
		$DBIRTH_ori = $ligne->DBIRTH;
		$FNAME_ori = $ligne->FNAME;
		$IDACCTYP_ori = $ligne->IDACCTYP;
		$IDCOUNTRY_ori = $ligne->IDCOUNTRY;
		$IDSEX_ori = $ligne->IDSEX;
		$IDSTCON_ori = $ligne->IDSTCON;
		$IDSTMA_ori = $ligne->IDSTMA;
		$IDUSER_ori = $ligne->IDUSER;
		$LNAME_ori = $ligne->LNAME;
		$MAIL_ori = $ligne->MAIL;
		$NNAME_ori = $ligne->NNAME;
		$PCODE_ori = $ligne->PCODE;
		$PWD_ori = $ligne->PWD;
		$STREET_ori = $ligne->STREET;
	
	}}
	
	$NNAME = addslashes($NNAME);
	$FNAME = addslashes($FNAME);
	$LNAME = addslashes($LNAME);
	$DBIRTH = addslashes($DBIRTH);
	$STREET = addslashes($STREET);
	$PCODE = addslashes($PCODE);
	$CITY = addslashes($CITY);
	$MAIL = addslashes($MAIL);
	$PWD = addslashes($PWD);
	$ACTIVE = addslashes($ACTIVE);
	$DATEINS = addslashes($DATEINS);
	$DATEMOD = addslashes($DATEMOD);
	$DATELAST = addslashes($DATELAST);
	
		
	if ($ACTIVE != addslashes($ACTIVE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "ACTIVE = '$ACTIVE'";
	if ($CITY != addslashes($CITY_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "CITY = '$CITY'";
	if ($DATEINS != addslashes($DATEINS_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "DATEINS = '$DATEINS'";
	if ($DATELAST != addslashes($DATELAST_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "DATELAST = '$DATELAST'";
	if ($DATEMOD != addslashes($DATEMOD_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "DATEMOD = '$DATEMOD'";
	if ($DBIRTH != addslashes($DBIRTH_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "DBIRTH = '$DBIRTH'";
	if ($FNAME != addslashes($FNAME_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "FNAME = '$FNAME'";
	if ($IDACCTYP != addslashes($IDACCTYP_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDACCTYP = '$IDACCTYP'";
	if ($IDCOUNTRY != addslashes($IDCOUNTRY_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDCOUNTRY = '$IDCOUNTRY'";
	if ($IDSEX != addslashes($IDSEX_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDSEX = '$IDSEX'";
	if ($IDSTCON != addslashes($IDSTCON_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDSTCON = '$IDSTCON'";
	if ($IDSTMA != addslashes($IDSTMA_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDSTMA = '$IDSTMA'";
	if ($IDUSER != addslashes($IDUSER_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "IDUSER = '$IDUSER'";
	if ($LNAME != addslashes($LNAME_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "LNAME = '$LNAME'";
	if ($MAIL != addslashes($MAIL_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "MAIL = '$MAIL'";
	if ($NNAME != addslashes($NNAME_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "NNAME = '$NNAME'";
	if ($PCODE != addslashes($PCODE_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "PCODE = '$PCODE'";
	if ($PWD != addslashes($PWD_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "PWD = '$PWD'";
	if ($STREET != addslashes($STREET_ori)) $strSql_Req .= ((strlen($strSql_Req) > 0) ? " , " : "") . "STREET = '$STREET'";
		

	$strSql .= $strSql_Req . " WHERE IDUSER = $IDUSER_ori";	
	//echo $strSql;
	return $data->executeQuery($strSql);
}

// Test d'existence
function testUsers($IDUSER) {
	global $data;
	$strSql = "SELECT 1 FROM users WHERE  IDUSER = $IDUSER ;";
	$res = $data->executeQuery($strSql);
	return $data->testCommun($res);
}

// Récupération enregistrement
function getSingleRowUsers($IDUSER) {
	global $data;
	$strSql = "SELECT * FROM users WHERE IDUSER = $IDUSER;";
	return $data->executeQuery($strSql);
}


// Récupération du formulaire
if (isset($_POST["BEnregistrer"])) {

$ACTIVE = trim(isset($_POST['EACTIVE']) ? $_POST['EACTIVE'] : '');
$CITY = trim(isset($_POST['ECITY']) ? $_POST['ECITY'] : '');
$DATEINS = trim(isset($_POST['EDATEINS']) ? $_POST['EDATEINS'] : '');
$DATELAST = trim(isset($_POST['EDATELAST']) ? $_POST['EDATELAST'] : '');
$DATEMOD = trim(isset($_POST['EDATEMOD']) ? $_POST['EDATEMOD'] : '');
$DBIRTH = trim(isset($_POST['EDBIRTH']) ? $_POST['EDBIRTH'] : '');
$FNAME = trim(isset($_POST['EFNAME']) ? $_POST['EFNAME'] : '');
$IDACCTYP = trim(isset($_POST['EIDACCTYP']) ? $_POST['EIDACCTYP'] : '');
$IDCOUNTRY = trim(isset($_POST['EIDCOUNTRY']) ? $_POST['EIDCOUNTRY'] : '');
$IDSEX = trim(isset($_POST['EIDSEX']) ? $_POST['EIDSEX'] : '');
$IDSTCON = trim(isset($_POST['EIDSTCON']) ? $_POST['EIDSTCON'] : '');
$IDSTMA = trim(isset($_POST['EIDSTMA']) ? $_POST['EIDSTMA'] : '');
$IDUSER = trim(isset($_POST['EIDUSER']) ? $_POST['EIDUSER'] : '');
$LNAME = trim(isset($_POST['ELNAME']) ? $_POST['ELNAME'] : '');
$sMail = trim(isset($_POST['EMAIL']) ? $_POST['EMAIL'] : '');
$NNAME = trim(isset($_POST['ENNAME']) ? $_POST['ENNAME'] : '');
$PCODE = trim(isset($_POST['EPCODE']) ? $_POST['EPCODE'] : '');
$PWD = trim(isset($_POST['EPWD']) ? $_POST['EPWD'] : '');
$STREET = trim(isset($_POST['ESTREET']) ? $_POST['ESTREET'] : '');
		

$IDUSER_ori = trim(isset($_POST['EIDUSER_ori']) ? $_POST['EIDUSER_ori'] : '');
	
	
$mode = $_POST["HMode"];
switch ($mode) {
	case 0 :	
if (! testUsers($IDUSER)) {
					
if (ajouterUsers($IDUSER,$NNAME,$FNAME,$LNAME,$DBIRTH,$STREET,$PCODE,$CITY,$IDCOUNTRY,$sMail,$PWD,$IDSEX,$IDSTMA,$IDSTCON,$IDACCTYP,$ACTIVE,$DATEINS,$DATEMOD,$DATELAST)) {
		$message = "Insertion OK";	
	} 
}
	break;
	
	case 1 :			

	
if (testUsers($IDUSER)) {
		
if (modifierUsers($ACTIVE, $CITY, $DATEINS, $DATELAST, $DATEMOD, $DBIRTH, $FNAME, $IDACCTYP, $IDCOUNTRY, $IDSEX, $IDSTCON, $IDSTMA, $IDUSER, $LNAME, $sMail, $NNAME, $PCODE, $PWD, $STREET)) {				
		$message = "Modification OK";	
		} 
	}
}

					
}
					
// ----------------------------------- LECTURE
if (isset($_GET["EIDUSER"])) {

$IDUSER= $_GET["EIDUSER"]; 
				
if (testUsers($IDUSER)) {			
				
$result = getSingleRowUsers($IDUSER);
$ligne = $util->getFirstLineObject($result);	
// Récupération d'un tableau d'enregistrement	
if (count($ligne) > 0) {
	$ACTIVE = $ACTIVE_ori = $ligne->ACTIVE;
	$CITY = $CITY_ori = $ligne->CITY;
	$DATEINS = $DATEINS_ori = $ligne->DATEINS;
	$DATELAST = $DATELAST_ori = $ligne->DATELAST;
	$DATEMOD = $DATEMOD_ori = $ligne->DATEMOD;
	$DBIRTH = $DBIRTH_ori = $ligne->DBIRTH;
	$FNAME = $FNAME_ori = $ligne->FNAME;
	$IDACCTYP = $IDACCTYP_ori = $ligne->IDACCTYP;
	$IDCOUNTRY = $IDCOUNTRY_ori = $ligne->IDCOUNTRY;
	$IDSEX = $IDSEX_ori = $ligne->IDSEX;
	$IDSTCON = $IDSTCON_ori = $ligne->IDSTCON;
	$IDSTMA = $IDSTMA_ori = $ligne->IDSTMA;
	$IDUSER = $IDUSER_ori = $ligne->IDUSER;
	$LNAME = $LNAME_ori = $ligne->LNAME;
	$sMail = $MAIL_ori = $ligne->MAIL;
	$NNAME = $NNAME_ori = $ligne->NNAME;
	$PCODE = $PCODE_ori = $ligne->PCODE;
	$PWD = $PWD_ori = $ligne->PWD;
	$STREET = $STREET_ori = $ligne->STREET;
	
}			
$mode = 1;
}}
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
                            Dashboard <small>Statistics Overview</small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->



<form action="<?php echo $page; ?>" method="post" name="FUsers" id="FUsers">
<div class="CSSTableGenerator">
<table width="500" border="1" align="center">  
        <tr>
          <td height="12" colspan="2"><div align="center">Users</div></td>
          </tr>
        <tr>
<td>User</td>
<td><input type="text" size="30" name="EIDUSER" id="EIDUSER" value="<?php echo htmlentities($IDUSER, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Nick Name</td>
<td><input type="text" size="30" name="ENNAME" id="ENNAME" value="<?php echo htmlentities($NNAME, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>First Name</td>
<td><input type="text" size="30" name="EFNAME" id="EFNAME" value="<?php echo htmlentities($FNAME, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Last Name</td>
<td><input type="text" size="30" name="ELNAME" id="ELNAME" value="<?php echo htmlentities($LNAME, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Birthay</td>
<td><input type="text" size="30" name="EDBIRTH" id="EDBIRTH" value="<?php echo htmlentities($DBIRTH, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Street</td>
<td><input type="text" size="30" name="ESTREET" id="ESTREET" value="<?php echo htmlentities($STREET, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Postal Code</td>
<td><input type="text" size="30" name="EPCODE" id="EPCODE" value="<?php echo htmlentities($PCODE, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>City</td>
<td><input type="text" size="30" name="ECITY" id="ECITY" value="<?php echo htmlentities($CITY, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Country</td>
<td><input type="text" size="30" name="EIDCOUNTRY" id="EIDCOUNTRY" value="<?php echo htmlentities($IDCOUNTRY, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Mail</td>
<td><input type="text" size="30" name="EMAIL" id="EMAIL" value="<?php echo htmlentities($sMail, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Passcode</td>
<td><input type="text" size="30" name="EPWD" id="EPWD" value="<?php echo htmlentities($PWD, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Gender</td>
<td><input type="text" size="30" name="EIDSEX" id="EIDSEX" value="<?php echo htmlentities($IDSEX, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Marital Status</td>
<td><input type="text" size="30" name="EIDSTMA" id="EIDSTMA" value="<?php echo htmlentities($IDSTMA, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Statut Connexion</td>
<td><input type="text" size="30" name="EIDSTCON" id="EIDSTCON" value="<?php echo htmlentities($IDSTCON, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Account Type</td>
<td><input type="text" size="30" name="EIDACCTYP" id="EIDACCTYP" value="<?php echo htmlentities($IDACCTYP, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Actif</td>
<td><input type="text" size="30" name="EACTIVE" id="EACTIVE" value="<?php echo htmlentities($ACTIVE, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Creation Date</td>
<td><input type="text" size="30" name="EDATEINS" id="EDATEINS" value="<?php echo htmlentities($DATEINS, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Modification Date</td>
<td><input type="text" size="30" name="EDATEMOD" id="EDATEMOD" value="<?php echo htmlentities($DATEMOD, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>
<tr>
<td>Last Access Date</td>
<td><input type="text" size="30" name="EDATELAST" id="EDATELAST" value="<?php echo htmlentities($DATELAST, ENT_COMPAT,'UTF-8', true); ?>"></td>
</tr>

      						
	  <tr>
      <td><input name="HMode" type="hidden" id="HMode" value="<?php echo $mode; ?>"></td>
      <td>&nbsp;</td>
    </tr>
		  <tr>
	    <td colspan="2">&nbsp;<?php echo $message; ?>
		
		<input id="EIDUSER_ori" name="EIDUSER_ori" type="hidden"  value="<?php echo $IDUSER_ori; ?>">
		
		</td>
    </tr>
	</table>
  </div>
</form>
<?php $data->deconnecter(); ?>

  <input name="BEnregistrer" type="submit" id="BEnregistrer" value="Enregistrer">
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
