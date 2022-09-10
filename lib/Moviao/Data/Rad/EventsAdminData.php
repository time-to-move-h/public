<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2020-2021.
// DataClass Events_admin
namespace Moviao\Data\Rad;

class EventsAdminData {     
private $ID_EVT = null;
private $ID_USR = null;
private $EVTADM_ACTIVE = null;
private $EVTADM_DATINS = null;
private $EVTADM_DATMOD = null;   

public function __construct() {}
// Getters 
public function get_EVT() {return $this->ID_EVT;}
public function get_USR() {return $this->ID_USR;}
public function get_ACTIVE() {return $this->EVTADM_ACTIVE;}
public function get_DATINS() {return $this->EVTADM_DATINS;}
public function get_DATMOD() {return $this->EVTADM_DATMOD;}
   

public function set_EVT($ID_EVT) {$this->ID_EVT=$ID_EVT;}
public function set_USR($ID_USR) {$this->ID_USR=$ID_USR;}
public function set_ACTIVE($EVTADM_ACTIVE) {$this->EVTADM_ACTIVE=$EVTADM_ACTIVE;}
public function set_DATINS($EVTADM_DATINS) {$this->EVTADM_DATINS=$EVTADM_DATINS;}
public function set_DATMOD($EVTADM_DATMOD) {$this->EVTADM_DATMOD=$EVTADM_DATMOD;}
}