<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2020-2021.
// DataClass Users_validation
namespace Moviao\Data\Rad;

class UsersValidationData {     
private $ID_USRVAL = null;
private $UVA_EMAIL = null;
private $UVA_MPHONE = null;
private $UVA_FNAME = null;
private $UVA_LNAME = null;
private $UVA_CODE = null;
private $UVA_DATINS = null;
private $UVA_CNT = null;
private $UVA_ACTIVE = null;
private $UVA_LASTIP = null;
private $UVA_LOCKED = null;
private $UVA_TIMEZONE = null;
private $UVA_PWD = null;
private $ID_ACCTYP = null;
private $UVA_SEX = null;
private $UVA_EMAIL_CONFIRMED = null;
private $UVA_MPHONE_CONFIRMED = null;   

public function __construct() {}
// Getters 
public function get_USRVAL() {return $this->ID_USRVAL;}
public function get_EMAIL() {return $this->UVA_EMAIL;}
public function get_MPHONE() {return $this->UVA_MPHONE;}
public function get_FNAME() {return $this->UVA_FNAME;}
public function get_LNAME() {return $this->UVA_LNAME;}
public function get_CODE() {return $this->UVA_CODE;}
public function get_DATINS() {return $this->UVA_DATINS;}
public function get_CNT() {return $this->UVA_CNT;}
public function get_ACTIVE() {return $this->UVA_ACTIVE;}
public function get_LASTIP() {return $this->UVA_LASTIP;}
public function get_LOCKED() {return $this->UVA_LOCKED;}
public function get_TIMEZONE() {return $this->UVA_TIMEZONE;}
public function get_PWD() {return $this->UVA_PWD;}
public function get_ACCTYP() {return $this->ID_ACCTYP;}
public function get_SEX() {return $this->UVA_SEX;}
public function get_EMAIL_CONFIRMED() {return $this->UVA_EMAIL_CONFIRMED;}
public function get_MPHONE_CONFIRMED() {return $this->UVA_MPHONE_CONFIRMED;}
   

public function set_USRVAL($ID_USRVAL) {$this->ID_USRVAL=$ID_USRVAL;}
public function set_EMAIL(?string $UVA_EMAIL) {$this->UVA_EMAIL=$UVA_EMAIL;}
public function set_MPHONE(?string $UVA_MPHONE) {$this->UVA_MPHONE=$UVA_MPHONE;}
public function set_FNAME(?string $UVA_FNAME) {$this->UVA_FNAME=$UVA_FNAME;}
public function set_LNAME(?string $UVA_LNAME) {$this->UVA_LNAME=$UVA_LNAME;}
public function set_CODE(?string $UVA_CODE) {$this->UVA_CODE=$UVA_CODE;}
public function set_DATINS($UVA_DATINS) {$this->UVA_DATINS=$UVA_DATINS;}
public function set_CNT(?string $UVA_CNT) {$this->UVA_CNT=$UVA_CNT;}
public function set_ACTIVE($UVA_ACTIVE) {$this->UVA_ACTIVE=$UVA_ACTIVE;}
public function set_LASTIP(?string $UVA_LASTIP) {$this->UVA_LASTIP=$UVA_LASTIP;}
public function set_LOCKED($UVA_LOCKED) {$this->UVA_LOCKED=$UVA_LOCKED;}
public function set_TIMEZONE(?string $UVA_TIMEZONE) {$this->UVA_TIMEZONE=$UVA_TIMEZONE;}
public function set_PWD(?string $UVA_PWD) {$this->UVA_PWD=$UVA_PWD;}
public function set_ACCTYP($ID_ACCTYP) {$this->ID_ACCTYP=$ID_ACCTYP;}
public function set_SEX($UVA_SEX) {$this->UVA_SEX=$UVA_SEX;}
public function set_EMAIL_CONFIRMED($UVA_EMAIL_CONFIRMED) {$this->UVA_EMAIL_CONFIRMED=$UVA_EMAIL_CONFIRMED;}
public function set_MPHONE_CONFIRMED($UVA_MPHONE_CONFIRMED) {$this->UVA_MPHONE_CONFIRMED=$UVA_MPHONE_CONFIRMED;}
}