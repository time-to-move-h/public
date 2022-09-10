<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2020-2021.
// DataClass Channels_admin
namespace Moviao\Data\Rad;

class ChannelsAdminData {     
private $ID_CHA = null;
private $ID_USR = null;
private $CHAADM_ACTIVE = null;
private $CHAADM_DATINS = null;
private $CHAADM_DATMOD = null;   

public function __construct() {}
// Getters 
public function get_CHA() {return $this->ID_CHA;}
public function get_USR() {return $this->ID_USR;}
public function get_ACTIVE() {return $this->CHAADM_ACTIVE;}
public function get_DATINS() {return $this->CHAADM_DATINS;}
public function get_DATMOD() {return $this->CHAADM_DATMOD;}
   

public function set_CHA($ID_CHA) {$this->ID_CHA=$ID_CHA;}
public function set_USR($ID_USR) {$this->ID_USR=$ID_USR;}
public function set_ACTIVE($CHAADM_ACTIVE) {$this->CHAADM_ACTIVE=$CHAADM_ACTIVE;}
public function set_DATINS($CHAADM_DATINS) {$this->CHAADM_DATINS=$CHAADM_DATINS;}
public function set_DATMOD($CHAADM_DATMOD) {$this->CHAADM_DATMOD=$CHAADM_DATMOD;}
}