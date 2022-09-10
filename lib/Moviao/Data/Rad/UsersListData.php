<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// DataClass Users_list
namespace Moviao\Data\Rad;

class UsersListData {     
private $ID_USR;
private $ID_USR2;
private $USR_REQ;
private $USR_IGNORE;
private $USR_ACTIVE;
private $USR_CONFIRM;
private $USR_DATCONF;
private $USR_DATINS;
private $USR_DATMOD;   

public function __construct() {}
// Getters 
public function get_USR() {return $this->ID_USR;}
public function get_USR2() {return $this->ID_USR2;}
public function get_REQ() {return $this->USR_REQ;}
public function get_IGNORE() {return $this->USR_IGNORE;}
public function get_ACTIVE() {return $this->USR_ACTIVE;}
public function get_CONFIRM() {return $this->USR_CONFIRM;}
public function get_DATCONF() {return $this->USR_DATCONF;}
public function get_DATINS() {return $this->USR_DATINS;}
public function get_DATMOD() {return $this->USR_DATMOD;}
   

public function set_USR($ID_USR) {$this->ID_USR=$ID_USR;}
public function set_USR2($ID_USR2) {$this->ID_USR2=$ID_USR2;}
public function set_REQ($USR_REQ) {$this->USR_REQ=$USR_REQ;}
public function set_IGNORE($USR_IGNORE) {$this->USR_IGNORE=$USR_IGNORE;}
public function set_ACTIVE($USR_ACTIVE) {$this->USR_ACTIVE=$USR_ACTIVE;}
public function set_CONFIRM($USR_CONFIRM) {$this->USR_CONFIRM=$USR_CONFIRM;}
public function set_DATCONF($USR_DATCONF) {$this->USR_DATCONF=$USR_DATCONF;}
public function set_DATINS($USR_DATINS) {$this->USR_DATINS=$USR_DATINS;}
public function set_DATMOD($USR_DATMOD) {$this->USR_DATMOD=$USR_DATMOD;}
}