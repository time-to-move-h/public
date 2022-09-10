<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// DataClass Users_google
namespace Moviao\Data\Rad;

class UsersGoogleData {     
private $UGG_ID = null;
private $UGG_MAIL = null;
private $UGG_NAME = null;
private $UGG_FNAME = null;
private $UGG_MNAME = null;
private $UGG_LNAME = null;
private $UGG_LINK = null;
private $UGG_BIRTHDAY = null;
private $UGG_COVER = null;
private $UGG_PICTURE = null;
private $UGG_DATEINS = null;
private $UGG_DATEMOD = null;   

public function __construct() {}
// Getters 
public function get_ID() {return $this->UGG_ID;}
public function get_MAIL() {return $this->UGG_MAIL;}
public function get_NAME() {return $this->UGG_NAME;}
public function get_FNAME() {return $this->UGG_FNAME;}
public function get_MNAME() {return $this->UGG_MNAME;}
public function get_LNAME() {return $this->UGG_LNAME;}
public function get_LINK() {return $this->UGG_LINK;}
public function get_BIRTHDAY() {return $this->UGG_BIRTHDAY;}
public function get_COVER() {return $this->UGG_COVER;}
public function get_PICTURE() {return $this->UGG_PICTURE;}
public function get_DATEINS() {return $this->UGG_DATEINS;}
public function get_DATEMOD() {return $this->UGG_DATEMOD;}
   

public function set_ID(?string $UGG_ID) {$this->UGG_ID=$UGG_ID;}
public function set_MAIL(?string $UGG_MAIL) {$this->UGG_MAIL=$UGG_MAIL;}
public function set_NAME(?string $UGG_NAME) {$this->UGG_NAME=$UGG_NAME;}
public function set_FNAME(?string $UGG_FNAME) {$this->UGG_FNAME=$UGG_FNAME;}
public function set_MNAME(?string $UGG_MNAME) {$this->UGG_MNAME=$UGG_MNAME;}
public function set_LNAME(?string $UGG_LNAME) {$this->UGG_LNAME=$UGG_LNAME;}
public function set_LINK(?string $UGG_LINK) {$this->UGG_LINK=$UGG_LINK;}
public function set_BIRTHDAY($UGG_BIRTHDAY) {$this->UGG_BIRTHDAY=$UGG_BIRTHDAY;}
public function set_COVER(?string $UGG_COVER) {$this->UGG_COVER=$UGG_COVER;}
public function set_PICTURE(?string $UGG_PICTURE) {$this->UGG_PICTURE=$UGG_PICTURE;}
public function set_DATEINS($UGG_DATEINS) {$this->UGG_DATEINS=$UGG_DATEINS;}
public function set_DATEMOD($UGG_DATEMOD) {$this->UGG_DATEMOD=$UGG_DATEMOD;}
}