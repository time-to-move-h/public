<?php
declare(strict_types=1);

namespace Moviao\Database;

use Moviao\Database\Exception\DBException;

class DBApplication extends DatabaseCore {

public function __construct() {
    parent::__construct();
}

public function connectDBA() {

    $server = $_SERVER["HTTP_HOST"];

    $config['db'] = array(
        'host'      => 'moviao-db',
        'username'  => 'moviao',
        'password'  => 'DC3hv32qF5ig5P',
        'dbname'    => 'moviao'
    );

    return parent::connect($config['db']);

  //  if ($server <> "localhost" && $server <> "127.0.0.1" && $server <> "localhost:8888" && $server <> "moviao") {
        //"localhost","moviao","moviao","moviao"
 //      return parent::connect($config['db']);
//   } else {
//        $config['db'] = array(
//          'host'      => 'mariadb',
//          'username'  => 'root',
//          'password'  => 'Hyjbe10vv5*',
//          'dbname'    => 'moviao'
//        );
//        $config['db'] = array(
//            'host'      => 'mariadb',
//            'username'  => 'moviao',
//            'password'  => 'DC3hv32qF5ig5P',
//            'dbname'    => 'moviao'
//        );
        //"localhost","moviao","moviao","moviao"
//        return parent::connect($config['db']);
//    }

}

public function readColumn(string $strSql, array $params) {

    $stmt = parent::prepare($strSql);

    if (empty($stmt)) {
        $error = 'Prepare SQL Error : ' . $strSql;
        error_log($error);
        throw new DBException($error);
    }

    foreach ($params as $t) {    
        if (!  parent::bindParam($t["parameter"],$t["value"],$t["type"])) {
            $error = 'Bind Parameter Failed : ' . var_export($stmt->errorInfo(), true);
            error_log($error);
            throw new DBException($error);
        }       
    }

    if (! parent::execute()) {        
        $error = 'Execute query Failed : ' . var_export($stmt->errorInfo(), true);
        error_log($error);
        throw new DBException($error);
    }

    $row = parent::fetchColumn();

    if ($row === FALSE) {
        return FALSE;
    } else {
        return $row;
    }

}

public function readLine(string $strSql, array $params) {

    $stmt = parent::prepare($strSql);

    if (empty($stmt)) {
        $error = 'Prepare SQL Error : ' . var_export($stmt->errorInfo(), true);
        error_log($error);
        throw new DBException($error);
    }

    foreach ($params as $t) {    
        if (!  parent::bindParam($t["parameter"],$t["value"],$t["type"])) {
            $error = 'Bind Parameter Failed : ' . var_export($stmt->errorInfo(), true);
            error_log($error);
            throw new DBException($error); 
        }       
    }

    if (! parent::execute()) {        
        $error = 'Execute query Failed : ' . var_export($stmt->errorInfo(), true);
        error_log($error);
        throw new DBException($error);
    }

    $row = parent::fetchAssoc();

    if ($row === false) {
        return FALSE;
    } else {
        return $row;
    }

}

public function readLineObject(string $strSql, array $params) {

    $stmt = parent::prepare($strSql);

    if (empty($stmt)) {
        $error = 'Prepare SQL Error : ' . var_export($stmt->errorInfo(), true);
        error_log($error);
        throw new DBException($error);
    }

    foreach ($params as $t) {    
        if (!  parent::bindParam($t["parameter"],$t["value"],$t["type"])) {
            $error = 'Bind Parameter Failed !';
            error_log($error);
            throw new DBException($error); 
        }       
    }

    if (! parent::execute()) {        
        $error = 'Execute query Failed : ' . var_export($stmt->errorInfo(), true);
        error_log($error);
        throw new DBException($error);
    }

    $row = parent::fetchObject();

    if ($row === false) {
        return FALSE;
    } else {
        return $row;
    }

}

public function readAllObject(string $strSql, array $params) : array {

    $stmt = parent::prepare($strSql);
    if (empty($stmt)) {
        $error = 'Prepare SQL Error : ' . var_export($stmt->errorInfo(), true);
        error_log($error);
        throw new DBException($error);
    }

    foreach ($params as $t) {    
        if (!  parent::bindParam($t["parameter"],$t["value"],$t["type"])) {
            $error = 'Bind Parameter Failed : ' . var_export($stmt->errorInfo(), true);
            error_log($error);
            throw new DBException($error);
        }       
    }

    if (! parent::execute()) {
        $error = 'Execute query Failed : ' . var_export($stmt->errorInfo(), true);
        error_log($error);
        throw new DBException($error);
    }

    $rows = array(); 
    while ($obj = parent::fetchObject($stmt)) {
        $rows[] = $obj;
    }

    return $rows;        
}

public function executeNonQuery(string $strSql, array $params) : bool {

    $stmt = parent::prepare($strSql);
    if (empty($stmt)) {
        $error = 'Prepare SQL Error : ' . var_export($stmt->errorInfo(), true);
        error_log($error);
        throw new DBException($error);        
    }

    foreach ($params as $t) {    
        if (!  parent::bindParam($t["parameter"],$t["value"],$t["type"])) {
            $error = 'Bind Parameter Failed : ' . var_export($stmt->errorInfo(), true);
            error_log($error);
            throw new DBException($error);        
        }       
    }

    if (! parent::execute()) {
        $error = 'Execute query Failed : ' . var_export($stmt->errorInfo(), true);
        error_log($error);
        throw new DBException($error); 
    }

    if (parent::rowCount() > 0) {
        return true;
    }

    return false;     
}
}