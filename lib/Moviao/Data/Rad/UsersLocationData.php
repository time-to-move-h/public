<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// DataClass Users_location
namespace Moviao\Data\Rad;

class UsersLocationData {     
private $ID_USR;
private $ULO_DATLOC;
private $ULO_LOC;   

public function __construct() {}
// Getters 
public function get_USR() {return $this->ID_USR;}
public function get_DATLOC() {return $this->ULO_DATLOC;}
public function get_LOC() {return $this->ULO_LOC;}
   

public function set_USR($ID_USR) {$this->ID_USR=$ID_USR;}
public function set_DATLOC($ULO_DATLOC) {$this->ULO_DATLOC=$ULO_DATLOC;}
public function set_LOC($ULO_LOC) {$this->ULO_LOC=$ULO_LOC;}
}