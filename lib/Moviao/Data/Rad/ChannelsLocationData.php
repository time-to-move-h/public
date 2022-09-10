<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// DataClass Channels_location
namespace Moviao\Data\Rad;

class ChannelsLocationData {     
private $ID_CHA;
private $CHALOC_COUNTRY;
private $CHALOC_COUNTRY_CODE;
private $CHALOC_STATE;
private $CHALOC_PCODE;
private $CHALOC_CITY;
private $CHALOC_STREET;
private $CHALOC_STREETN;
private $CHALOC_STREET2;
private $CHALOC_OSMID;
private $CHALOC_LOCATIONP;
private $CHALOC_VENUE;
private $CHALOC_ACTIVE;
private $CHALOC_DATINS;
private $CHALOC_DATMOD;   

public function __construct() {}
// Getters 
public function get_CHA() {return $this->ID_CHA;}
public function get_COUNTRY() {return $this->CHALOC_COUNTRY;}
public function get_COUNTRY_CODE() {return $this->CHALOC_COUNTRY_CODE;}
public function get_STATE() {return $this->CHALOC_STATE;}
public function get_PCODE() {return $this->CHALOC_PCODE;}
public function get_CITY() {return $this->CHALOC_CITY;}
public function get_STREET() {return $this->CHALOC_STREET;}
public function get_STREETN() {return $this->CHALOC_STREETN;}
public function get_STREET2() {return $this->CHALOC_STREET2;}
public function get_OSMID() {return $this->CHALOC_OSMID;}
public function get_LOCATIONP() {return $this->CHALOC_LOCATIONP;}
public function get_VENUE() {return $this->CHALOC_VENUE;}
public function get_ACTIVE() {return $this->CHALOC_ACTIVE;}
public function get_DATINS() {return $this->CHALOC_DATINS;}
public function get_DATMOD() {return $this->CHALOC_DATMOD;}
   

public function set_CHA($ID_CHA) {$this->ID_CHA=$ID_CHA;}
public function set_COUNTRY($CHALOC_COUNTRY) {$this->CHALOC_COUNTRY=$CHALOC_COUNTRY;}
public function set_COUNTRY_CODE($CHALOC_COUNTRY_CODE) {$this->CHALOC_COUNTRY_CODE=$CHALOC_COUNTRY_CODE;}
public function set_STATE($CHALOC_STATE) {$this->CHALOC_STATE=$CHALOC_STATE;}
public function set_PCODE($CHALOC_PCODE) {$this->CHALOC_PCODE=$CHALOC_PCODE;}
public function set_CITY($CHALOC_CITY) {$this->CHALOC_CITY=$CHALOC_CITY;}
public function set_STREET($CHALOC_STREET) {$this->CHALOC_STREET=$CHALOC_STREET;}
public function set_STREETN($CHALOC_STREETN) {$this->CHALOC_STREETN=$CHALOC_STREETN;}
public function set_STREET2($CHALOC_STREET2) {$this->CHALOC_STREET2=$CHALOC_STREET2;}
public function set_OSMID($CHALOC_OSMID) {$this->CHALOC_OSMID=$CHALOC_OSMID;}
public function set_LOCATIONP($CHALOC_LOCATIONP) {$this->CHALOC_LOCATIONP=$CHALOC_LOCATIONP;}
public function set_VENUE($CHALOC_VENUE) {$this->CHALOC_VENUE=$CHALOC_VENUE;}
public function set_ACTIVE($CHALOC_ACTIVE) {$this->CHALOC_ACTIVE=$CHALOC_ACTIVE;}
public function set_DATINS($CHALOC_DATINS) {$this->CHALOC_DATINS=$CHALOC_DATINS;}
public function set_DATMOD($CHALOC_DATMOD) {$this->CHALOC_DATMOD=$CHALOC_DATMOD;}
}