<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2018-2019.
// DataClass Users_account
namespace Moviao\Data\Rad;

class UsersAccountData {     
private $ID_USR;
private $UAC_ACCOUNT;
private $ID_TYPACO;
private $UAC_ACTIVE;
private $UAC_PWD;
private $UAC_PWD_OTP;
private $UAC_LOCKED;
private $UAC_DATEINS;
private $UAC_DATEMOD;   

public function __construct() {}
// Getters 
public function get_USR() {return $this->ID_USR;}
public function get_ACCOUNT() {return $this->UAC_ACCOUNT;}
public function get_TYPACO() {return $this->ID_TYPACO;}
public function get_ACTIVE() {return $this->UAC_ACTIVE;}
public function get_PWD() {return $this->UAC_PWD;}
public function get_PWD_OTP() {return $this->UAC_PWD_OTP;}
public function get_LOCKED() {return $this->UAC_LOCKED;}
public function get_DATEINS() {return $this->UAC_DATEINS;}
public function get_DATEMOD() {return $this->UAC_DATEMOD;}
   

public function set_USR($ID_USR) {$this->ID_USR=$ID_USR;}
public function set_ACCOUNT($UAC_ACCOUNT) {$this->UAC_ACCOUNT=$UAC_ACCOUNT;}
public function set_TYPACO($ID_TYPACO) {$this->ID_TYPACO=$ID_TYPACO;}
public function set_ACTIVE($UAC_ACTIVE) {$this->UAC_ACTIVE=$UAC_ACTIVE;}
public function set_PWD($UAC_PWD) {$this->UAC_PWD=$UAC_PWD;}
public function set_PWD_OTP($UAC_PWD_OTP) {$this->UAC_PWD_OTP=$UAC_PWD_OTP;}
public function set_LOCKED($UAC_LOCKED) {$this->UAC_LOCKED=$UAC_LOCKED;}
public function set_DATEINS($UAC_DATEINS) {$this->UAC_DATEINS=$UAC_DATEINS;}
public function set_DATEMOD($UAC_DATEMOD) {$this->UAC_DATEMOD=$UAC_DATEMOD;}
}