<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// DataClass Channels_tags
namespace Moviao\Data\Rad;

class ChannelsTagsData {     
private $ID_CHA;
private $ID_TAG;
private $CHATAG_ACTIVE;
private $CHATAG_DATCRE;
private $CHATAG_DATMOD;   

public function __construct() {}
// Getters 
public function get_CHA() {return $this->ID_CHA;}
public function get_TAG() {return $this->ID_TAG;}
public function get_ACTIVE() {return $this->CHATAG_ACTIVE;}
public function get_DATCRE() {return $this->CHATAG_DATCRE;}
public function get_DATMOD() {return $this->CHATAG_DATMOD;}
   

public function set_CHA($ID_CHA) {$this->ID_CHA=$ID_CHA;}
public function set_TAG($ID_TAG) {$this->ID_TAG=$ID_TAG;}
public function set_ACTIVE($CHATAG_ACTIVE) {$this->CHATAG_ACTIVE=$CHATAG_ACTIVE;}
public function set_DATCRE($CHATAG_DATCRE) {$this->CHATAG_DATCRE=$CHATAG_DATCRE;}
public function set_DATMOD($CHATAG_DATMOD) {$this->CHATAG_DATMOD=$CHATAG_DATMOD;}
}