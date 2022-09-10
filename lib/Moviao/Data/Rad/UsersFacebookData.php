<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// DataClass Users_facebook
namespace Moviao\Data\Rad;

class UsersFacebookData {     
private $UFB_ID;
private $UFB_MAIL;
private $UFB_NAME;
private $UFB_FNAME;
private $UFB_MNAME;
private $UFB_LNAME;
private $UFB_LINK;
private $UFB_BIRTHDAY;
private $UFB_COVER;
private $UFB_PICTURE;
private $UFB_DATEINS;
private $UFB_DATEMOD;   

public function __construct() {}
// Getters 
public function get_ID() {return $this->UFB_ID;}
public function get_MAIL() {return $this->UFB_MAIL;}
public function get_NAME() {return $this->UFB_NAME;}
public function get_FNAME() {return $this->UFB_FNAME;}
public function get_MNAME() {return $this->UFB_MNAME;}
public function get_LNAME() {return $this->UFB_LNAME;}
public function get_LINK() {return $this->UFB_LINK;}
public function get_BIRTHDAY() {return $this->UFB_BIRTHDAY;}
public function get_COVER() {return $this->UFB_COVER;}
public function get_PICTURE() {return $this->UFB_PICTURE;}
public function get_DATEINS() {return $this->UFB_DATEINS;}
public function get_DATEMOD() {return $this->UFB_DATEMOD;}
   

public function set_ID($UFB_ID) {$this->UFB_ID=$UFB_ID;}
public function set_MAIL($UFB_MAIL) {$this->UFB_MAIL=$UFB_MAIL;}
public function set_NAME($UFB_NAME) {$this->UFB_NAME=$UFB_NAME;}
public function set_FNAME($UFB_FNAME) {$this->UFB_FNAME=$UFB_FNAME;}
public function set_MNAME($UFB_MNAME) {$this->UFB_MNAME=$UFB_MNAME;}
public function set_LNAME($UFB_LNAME) {$this->UFB_LNAME=$UFB_LNAME;}
public function set_LINK($UFB_LINK) {$this->UFB_LINK=$UFB_LINK;}
public function set_BIRTHDAY($UFB_BIRTHDAY) {$this->UFB_BIRTHDAY=$UFB_BIRTHDAY;}
public function set_COVER($UFB_COVER) {$this->UFB_COVER=$UFB_COVER;}
public function set_PICTURE($UFB_PICTURE) {$this->UFB_PICTURE=$UFB_PICTURE;}
public function set_DATEINS($UFB_DATEINS) {$this->UFB_DATEINS=$UFB_DATEINS;}
public function set_DATEMOD($UFB_DATEMOD) {$this->UFB_DATEMOD=$UFB_DATEMOD;}
}