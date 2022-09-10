<?php
declare(strict_types=1);

namespace Moviao\Database;
class DatabaseInfo extends DatabaseCore {
    public function __construct() {}
    public function getDatabases() {
        $query = "SHOW DATABASES";
        $stmt = parent::executeQuery($query);						
        
        while ($ligne = parent::fetchAssoc()) {           
            $bases[] = $ligne["Database"];
        }		
        return ($bases);
    }
    public function getTables($database_name) {
        $tbs = parent::executeQuery("SHOW TABLES FROM " . $database_name);         
        $tables = parent::fetchAll();         
        return ($tables);	
    }
    public function getFields($database_name, $table_name) {
        $chs = parent::executeQuery("SHOW FULL FIELDS FROM $database_name.$table_name;");
        if (! $chs) return(NULL); 
        while($row = parent::fetchObject()) {
            $champs[] = $row;
        }            
        return ($champs);		
    }
}
?>