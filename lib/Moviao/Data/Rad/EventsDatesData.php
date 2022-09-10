<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2020-2021.
// DataClass Events_dates
namespace Moviao\Data\Rad;

class EventsDatesData {     
private $ID_EVT = null;
private $EVTDAT_DATBEG = null;
private $EVTDAT_DATEND = null;
private $ID_ZONEIDBEG = null;
private $ID_ZONEIDEND = null;
private $EVTDAT_ALLDAY = null;
private $EVTDAT_ONLINE = null;
private $EVTDAT_TOKEN = null;
private $EVTDAT_ACTIVE = null;
private $EVTDAT_DATINS = null;
private $EVTDAT_DATMOD = null;   

public function __construct() {}
// Getters 
public function get_EVT() {return $this->ID_EVT;}
public function get_DATBEG() {return $this->EVTDAT_DATBEG;}
public function get_DATEND() {return $this->EVTDAT_DATEND;}
public function get_ZONEIDBEG() {return $this->ID_ZONEIDBEG;}
public function get_ZONEIDEND() {return $this->ID_ZONEIDEND;}
public function get_ALLDAY() {return $this->EVTDAT_ALLDAY;}
public function get_ONLINE() {return $this->EVTDAT_ONLINE;}
public function get_TOKEN() {return $this->EVTDAT_TOKEN;}
public function get_ACTIVE() {return $this->EVTDAT_ACTIVE;}
public function get_DATINS() {return $this->EVTDAT_DATINS;}
public function get_DATMOD() {return $this->EVTDAT_DATMOD;}
   

public function set_EVT($ID_EVT) {$this->ID_EVT=$ID_EVT;}
public function set_DATBEG($EVTDAT_DATBEG) {$this->EVTDAT_DATBEG=$EVTDAT_DATBEG;}
public function set_DATEND($EVTDAT_DATEND) {$this->EVTDAT_DATEND=$EVTDAT_DATEND;}
public function set_ZONEIDBEG($ID_ZONEIDBEG) {$this->ID_ZONEIDBEG=$ID_ZONEIDBEG;}
public function set_ZONEIDEND($ID_ZONEIDEND) {$this->ID_ZONEIDEND=$ID_ZONEIDEND;}
public function set_ALLDAY($EVTDAT_ALLDAY) {$this->EVTDAT_ALLDAY=$EVTDAT_ALLDAY;}
public function set_ONLINE($EVTDAT_ONLINE) {$this->EVTDAT_ONLINE=$EVTDAT_ONLINE;}
public function set_TOKEN(?string $EVTDAT_TOKEN) {$this->EVTDAT_TOKEN=$EVTDAT_TOKEN;}
public function set_ACTIVE($EVTDAT_ACTIVE) {$this->EVTDAT_ACTIVE=$EVTDAT_ACTIVE;}
public function set_DATINS($EVTDAT_DATINS) {$this->EVTDAT_DATINS=$EVTDAT_DATINS;}
public function set_DATMOD($EVTDAT_DATMOD) {$this->EVTDAT_DATMOD=$EVTDAT_DATMOD;}
}