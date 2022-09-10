<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// DataClass Users_search
namespace Moviao\Data\Rad;

class UsersSearchData {     
private $ID_USR = null;
private $USC_QUERY = null;
private $USC_LOCATION = null;
private $USC_LAT = null;
private $USC_LON = null;
private $USC_RAD = null;
private $USC_DATINS = null;
private $USC_DATMOD = null;   

public function __construct() {}
// Getters 
public function get_USR() {return $this->ID_USR;}
public function get_QUERY() {return $this->USC_QUERY;}
public function get_LOCATION() {return $this->USC_LOCATION;}
public function get_LAT() {return $this->USC_LAT;}
public function get_LON() {return $this->USC_LON;}
public function get_RAD() {return $this->USC_RAD;}
public function get_DATINS() {return $this->USC_DATINS;}
public function get_DATMOD() {return $this->USC_DATMOD;}
   

public function set_USR($ID_USR) {$this->ID_USR=$ID_USR;}
public function set_QUERY(?string $USC_QUERY) {$this->USC_QUERY=$USC_QUERY;}
public function set_LOCATION(?string $USC_LOCATION) {$this->USC_LOCATION=$USC_LOCATION;}
public function set_LAT(?string $USC_LAT) {$this->USC_LAT=$USC_LAT;}
public function set_LON(?string $USC_LON) {$this->USC_LON=$USC_LON;}
public function set_RAD($USC_RAD) {$this->USC_RAD=$USC_RAD;}
public function set_DATINS($USC_DATINS) {$this->USC_DATINS=$USC_DATINS;}
public function set_DATMOD($USC_DATMOD) {$this->USC_DATMOD=$USC_DATMOD;}
}