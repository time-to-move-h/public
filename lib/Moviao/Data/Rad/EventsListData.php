<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// DataClass Events_list
namespace Moviao\Data\Rad;

class EventsListData {     
private $ID_EVT;
private $ID_USR;
private $ID_EVTLST;
private $EVTLST_N_FRIEND;
private $EVTLST_WAIT;
private $EVTLST_DATBEG;
private $EVTLST_CONFIRM;
private $ID_EVTROLE;
private $EVTLST_STATUS;
private $EVTLST_ACTIVE;
private $EVTLST_DATEINS;
private $EVTLST_DATCONF;
private $EVTLST_DATMOD;   

public function __construct() {}
// Getters 
public function get_EVT() {return $this->ID_EVT;}
public function get_USR() {return $this->ID_USR;}
public function get_EVTLST() {return $this->ID_EVTLST;}
public function get_N_FRIEND() {return $this->EVTLST_N_FRIEND;}
public function get_WAIT() {return $this->EVTLST_WAIT;}
public function get_DATBEG() {return $this->EVTLST_DATBEG;}
public function get_CONFIRM() {return $this->EVTLST_CONFIRM;}
public function get_EVTROLE() {return $this->ID_EVTROLE;}
public function get_STATUS() {return $this->EVTLST_STATUS;}
public function get_ACTIVE() {return $this->EVTLST_ACTIVE;}
public function get_DATEINS() {return $this->EVTLST_DATEINS;}
public function get_DATCONF() {return $this->EVTLST_DATCONF;}
public function get_DATMOD() {return $this->EVTLST_DATMOD;}
   

public function set_EVT($ID_EVT) {$this->ID_EVT=$ID_EVT;}
public function set_USR($ID_USR) {$this->ID_USR=$ID_USR;}
public function set_EVTLST($ID_EVTLST) {$this->ID_EVTLST=$ID_EVTLST;}
public function set_N_FRIEND($EVTLST_N_FRIEND) {$this->EVTLST_N_FRIEND=$EVTLST_N_FRIEND;}
public function set_WAIT($EVTLST_WAIT) {$this->EVTLST_WAIT=$EVTLST_WAIT;}
public function set_DATBEG($EVTLST_DATBEG) {$this->EVTLST_DATBEG=$EVTLST_DATBEG;}
public function set_CONFIRM($EVTLST_CONFIRM) {$this->EVTLST_CONFIRM=$EVTLST_CONFIRM;}
public function set_EVTROLE($ID_EVTROLE) {$this->ID_EVTROLE=$ID_EVTROLE;}
public function set_STATUS($EVTLST_STATUS) {$this->EVTLST_STATUS=$EVTLST_STATUS;}
public function set_ACTIVE($EVTLST_ACTIVE) {$this->EVTLST_ACTIVE=$EVTLST_ACTIVE;}
public function set_DATEINS($EVTLST_DATEINS) {$this->EVTLST_DATEINS=$EVTLST_DATEINS;}
public function set_DATCONF($EVTLST_DATCONF) {$this->EVTLST_DATCONF=$EVTLST_DATCONF;}
public function set_DATMOD($EVTLST_DATMOD) {$this->EVTLST_DATMOD=$EVTLST_DATMOD;}
}