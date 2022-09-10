<?php
declare(strict_types=1);
namespace Moviao\Database;
use PDO;

class DatabaseCore {

private $db;
private $stmt;
private $config;

public function __construct() {}

public function connect(array $config) {

    try {

        $this->config = $config;
        $attr_pdo = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'];
        $user = $config['username'];
        $password = $config['password'];
        $this->db = new PDO($dsn,$user,$password,$attr_pdo);
        return $this->db;

    } catch(PDOException $e) {
        throw new \Moviao\Database\Exception\DBException($e->getMessage());
    }

    return null;
}

public function disconnect() : void {
    $this->db = null;
    $this->stmt = null;
}

public function executeQuery(string $query) {
    $this->stmt = false;
    if (!($this->stmt = $this->db->query($query))) {         
         return false;
    }
    return $this->stmt;    
}

public function prepare(string $query) {
    $this->stmt = false;
    if (!($this->stmt = $this->db->prepare($query))) {         
         return false;
    }
    return $this->stmt;
}

public function bindParam($i,$value,$data_type) : bool {
    if (! empty($this->stmt)) {
        return $this->stmt->bindParam($i, $value, $data_type);
    } else {
        return false;
    }    
}

public function execute() : bool {
    if (! empty($this->stmt)) {
        return $this->stmt->execute();
    } else {
        return false;
    }
}

public function errorInfo() : ?array {
    if (! empty($this->db)) {
        return $this->db->errorInfo();
    } else {
        return null;
    }
}

public function errorCode() : ?string {
    if (! empty($this->db)) {
        return $this->db->errorCode();
    } else {
        return null;
    }
}

/**
 * @return int
 */
public function rowCount() : int {
    if (! empty($this->stmt)) {
        return $this->stmt->rowCount();
    } else {
        return -1;
    }
}

public function lastInsertId() : ?string {
    if (! empty($this->db)) {
        return $this->db->lastInsertId();
    } else {
        return null;
    }
}

public function fetchAssoc() {
    if (! empty($this->stmt)) {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

public function fetchAll() {
    if (! empty($this->stmt)) {
        return $this->stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    return null;
}

public function fetchObject($stmt_tmp = null) {
    if (empty($stmt_tmp)) {
        if (empty($this->stmt)) {
            return false;
        }
        return $this->stmt->fetch(PDO::FETCH_OBJ);
   } else {
       return $stmt_tmp->fetch(PDO::FETCH_OBJ);
   }
}

public function fetchNum() {
    if (! empty($this->stmt)) {
        return $this->stmt->fetch(PDO::FETCH_NUM);
    }
    return null;
}

public function fetch($fetch_style) {
    if (! empty($this->stmt)) {
        return $this->stmt->fetch($fetch_style);
    } else {
        return false;
    }    
}

public function fetchColumn(int $i = 0) {
    if (! empty($this->stmt)) {
        return $this->stmt->fetchColumn($i);
    } else {
        return false;
    }    
}

public function startTransaction() : bool {
    if (! empty($this->db)) {
        return $this->db->beginTransaction();	
    } else {
        return false;
    }
}

public function commitTransaction() : bool {
    if (! empty($this->db)) {
        return $this->db->commit();
    } else {
        return false;
    }
}

public function rollbackTransaction() : bool {
    if (! empty($this->db)) {
        return $this->db->rollBack();
    } else {
        return false;
    }
}

public function getConnexion() {
    return $this->db;	
}

public function setConnexion($db) : void {
    $this->db = $db;	
}

public function freeResult() : void {
    $this->db = null;
}

}