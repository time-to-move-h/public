<?php
 
// Inclusion de bibliotheque
require('../../../lib/Moviao/Database/DatabaseCore.php');
require('DBApplicationClass.php');
require('../../../lib/Moviao/Database/SQL/SqlGenerator.php');

// Instanciation
$data = new \Moviao\Database\DBApplication();
//$util = new DataUtils();
$sql = new SqlGenerator();

// Connexion Mysql
$data->connecterDB();
// Initialisation
$result = '';

// Test Engine
//$IDAPP = "loansystem";
//$IDAPPDAT = "category";
//$IDMAQ = "category";
//'MODELES\FrmEdit.php'

$IDAPP = '';
$IDAPPDAT = '';
$TAB = '';
$IDENG = 0;

foreach($_GET as $key => $value)
{
    // Debuggage
    //echo 'Key = ' . $key . '<br />';
    //echo 'Value= ' . $value;
      
   $t = preg_split("/[#]+/", $value);
   if (count($t) > 0) {
    $IDAPP = $t[0]; // Apps
    $IDAPPDAT = $t[1]; // Data
     $strSql = "SELECT b.*,a.tab,a.FILE FROM apps_data a, engine_config b WHERE a.IDAPP='$IDAPP' AND a.IDAPPDAT='$IDAPPDAT' AND a.IDENG=b.IDENG;";
     $res = $data->executeQuery($strSql);
     $enreg = $data->fetchObject();
     $test = generate($enreg->FILESOU);	
     if (strlen($enreg->FILE)>0) {
        //$s = str_replace('$tab',$enreg->tab, $enreg->FILEDES);
        //echo "file : " . $s;
        saveFile($test, $enreg->FILE);
     } else {
        $s = str_replace('$tab',$enreg->tab, $enreg->FILEDES);        
        saveFile($test,$s);       
     }     
    }
}

$data->disconnect();

header ("Refresh: 4;URL=FrmView_apps_data.php?EIDAPP=".$IDAPP);
echo "Page Generated ... OK - Redirection in 2 seconds ...";

// Functions
function isDate(string $chaine) : bool {	
    if (0 === strpos($chaine, 'datetime')) return TRUE;     
    if (0 === strpos($chaine, 'date')) return TRUE; 
    if (0 === strpos($chaine, 'time')) return TRUE; 
    if (0 === strpos($chaine, 'timestamp')) return TRUE; 
    return FALSE;	
}

function isNumber(string $chaine) : bool {
    if (0 === strpos($chaine, 'bit')) return TRUE;    
    if (0 === strpos($chaine, 'int')) return TRUE;
    if (0 === strpos($chaine, 'float')) return TRUE;
    if (0 === strpos($chaine, 'double')) return TRUE;
    if (0 === strpos($chaine, 'tinyint')) return TRUE;
    if (0 === strpos($chaine, 'bigint')) return TRUE;
    if (0 === strpos($chaine, 'smallint')) return TRUE;
    if (0 === strpos($chaine, 'mediumint')) return TRUE;
    if (0 === strpos($chaine, 'decimal')) return TRUE;
    return FALSE;	
}

function isInteger(string $chaine) : bool {	    
    if (0 === strpos($chaine, 'bit')) return TRUE;
    if (0 === strpos($chaine, 'int')) return TRUE;
    if (0 === strpos($chaine, 'tinyint')) return TRUE;
    if (0 === strpos($chaine, 'bigint')) return TRUE;
    if (0 === strpos($chaine, 'smallint')) return TRUE;
    if (0 === strpos($chaine, 'mediumint')) return TRUE;	
    return FALSE;	
}

function isFloat(string $chaine) : bool {		
    if (0 === strpos($chaine, 'float')) return TRUE;
    if (0 === strpos($chaine, 'double')) return TRUE;
    if (0 === strpos($chaine, 'decimal')) return TRUE;
    return FALSE;	
}

function isString(string $chaine) : bool {		
    if (0 === strpos($chaine, 'varchar')) return TRUE;
    if (0 === strpos($chaine, 'char')) return TRUE;    
    return FALSE;	
}

function convertToPhp(string $chaine) : string {
    if (isInteger($chaine)) return 'int';
    if (isString($chaine)) return  'string';
    return '';   
}

function evaluer($code) {    
    global $IDAPP, $IDAPPDAT, $IDTYPDAT, $data, $result, $util;
    $reponse = '';
    try {		
        //echo $code[1];       
        $reponse = eval($code[1]);	
        //echo "CODE = " . $code[0] . "<br><br>";
    } catch (Exception $e) {        
        $result = "Une Erreur s'est produite :" . $e;
    }

    if (is_null($reponse)) return '';
    else return $reponse;
}

function generate($file_name) {
    //var_dump($file_name);    
    $file = '';
    $file = file_get_contents ($file_name);
    //|/\*[[:blank:]]*[\r\n]*[[:blank:]]*@{([^\*]*)}[[:blank:]]*[\r\n]*[[:blank:]]*\*/|    
    try {
        //$result = preg_replace_callback('|\/\*\@{([[:blank:]]*[\r\n]*[[:blank:]]*[^\*]*[[:blank:]]*[\r\n]*[[:blank:]]*)}\*\/|', "evaluer", $file);		
        $result = preg_replace_callback('|\/\*\@{([[:blank:]]*[\r\n]*[[:blank:]]*[^\*]*[[:blank:]]*[\r\n]*[[:blank:]]*)}\*\/|', "evaluer", $file);		      
    } catch (Exception $e) {
        $result = "Une Erreur s'est produite :" . $e;
    }	

    return $result;
}

function saveFile($result, $file_name) {
    //$file_name = str_replace('$TABLE_NAME', $TABLE_NAME , $file_name);    	 
    //$file_name = str_replace('$DATABASE_NAME', $DATABASE_NAME , $file_name);    	 	 	 
    //echo $file_name;
    //if (! file_exists($file_name)) exit("Directory not found : " . + $file_name);    
    //echo var_dump($result);
        
    $fp = fopen ($file_name, "w");        
    //fseek ($fp, 0);    
    fputs ($fp, $result);    
    fclose ($fp);   	
}
	
?>