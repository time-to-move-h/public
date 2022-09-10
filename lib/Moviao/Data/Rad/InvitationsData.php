<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// DataClass Invitations
namespace Moviao\Data\Rad;

class InvitationsData {     
private $IDINVIT;
private $INV_ACCOUNT;
private $INV_CODE;
private $INV_DATINS;   

public function __construct() {}
// Getters 
public function get_IDINVIT() {return $this->IDINVIT;}
public function get_ACCOUNT() {return $this->INV_ACCOUNT;}
public function get_CODE() {return $this->INV_CODE;}
public function get_DATINS() {return $this->INV_DATINS;}
   

public function set_IDINVIT($IDINVIT) {$this->IDINVIT=$IDINVIT;}
public function set_ACCOUNT($INV_ACCOUNT) {$this->INV_ACCOUNT=$INV_ACCOUNT;}
public function set_CODE($INV_CODE) {$this->INV_CODE=$INV_CODE;}
public function set_DATINS($INV_DATINS) {$this->INV_DATINS=$INV_DATINS;}
}