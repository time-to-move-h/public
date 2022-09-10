<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// DataClass Events_tags
namespace Moviao\Data\Rad;

class EventsTagsData {     
private $ID_EVT;
private $ID_TAG;
private $EVTTAG_ACTIVE;
private $EVTTAG_DATCRE;
private $EVTTAG_DATMOD;   

public function __construct() {}
// Getters 
public function get_EVT() {return $this->ID_EVT;}
public function get_TAG() {return $this->ID_TAG;}
public function get_ACTIVE() {return $this->EVTTAG_ACTIVE;}
public function get_DATCRE() {return $this->EVTTAG_DATCRE;}
public function get_DATMOD() {return $this->EVTTAG_DATMOD;}
   

public function set_EVT($ID_EVT) {$this->ID_EVT=$ID_EVT;}
public function set_TAG($ID_TAG) {$this->ID_TAG=$ID_TAG;}
public function set_ACTIVE($EVTTAG_ACTIVE) {$this->EVTTAG_ACTIVE=$EVTTAG_ACTIVE;}
public function set_DATCRE($EVTTAG_DATCRE) {$this->EVTTAG_DATCRE=$EVTTAG_DATCRE;}
public function set_DATMOD($EVTTAG_DATMOD) {$this->EVTTAG_DATMOD=$EVTTAG_DATMOD;}
}