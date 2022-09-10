<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2018-2019.
// DataClass Channels_list
namespace Moviao\Data\Rad;

class ChannelsListData {     
private $ID_CHA;
private $ID_USR;
private $ID_CHALST;
private $ID_CHAROLE;
private $CHALST_ACTIVE;
private $CHALST_CONFIRM;
private $CHALST_STATUS;
private $CHALST_DATECONF;
private $CHALST_DATEINS;
private $CHALST_DATMOD;   

public function __construct() {}
// Getters 
public function get_CHA() {return $this->ID_CHA;}
public function get_USR() {return $this->ID_USR;}
public function get_CHALST() {return $this->ID_CHALST;}
public function get_CHAROLE() {return $this->ID_CHAROLE;}
public function get_ACTIVE() {return $this->CHALST_ACTIVE;}
public function get_CONFIRM() {return $this->CHALST_CONFIRM;}
public function get_STATUS() {return $this->CHALST_STATUS;}
public function get_DATECONF() {return $this->CHALST_DATECONF;}
public function get_DATEINS() {return $this->CHALST_DATEINS;}
public function get_DATMOD() {return $this->CHALST_DATMOD;}
   

public function set_CHA($ID_CHA) {$this->ID_CHA=$ID_CHA;}
public function set_USR($ID_USR) {$this->ID_USR=$ID_USR;}
public function set_CHALST($ID_CHALST) {$this->ID_CHALST=$ID_CHALST;}
public function set_CHAROLE($ID_CHAROLE) {$this->ID_CHAROLE=$ID_CHAROLE;}
public function set_ACTIVE($CHALST_ACTIVE) {$this->CHALST_ACTIVE=$CHALST_ACTIVE;}
public function set_CONFIRM($CHALST_CONFIRM) {$this->CHALST_CONFIRM=$CHALST_CONFIRM;}
public function set_STATUS($CHALST_STATUS) {$this->CHALST_STATUS=$CHALST_STATUS;}
public function set_DATECONF($CHALST_DATECONF) {$this->CHALST_DATECONF=$CHALST_DATECONF;}
public function set_DATEINS($CHALST_DATEINS) {$this->CHALST_DATEINS=$CHALST_DATEINS;}
public function set_DATMOD($CHALST_DATMOD) {$this->CHALST_DATMOD=$CHALST_DATMOD;}
}