<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// DataClass Users_pm
namespace Moviao\Data\Rad;

class UsersPmData {     
private $USRPM_IDPM;
private $USRPM_USR1;
private $USRPM_USR2;
private $USRPM_MSG;
private $USRPM_DATSND;
private $USRPM_READ;   

public function __construct() {}
// Getters 
public function get_IDPM() {return $this->USRPM_IDPM;}
public function get_USR1() {return $this->USRPM_USR1;}
public function get_USR2() {return $this->USRPM_USR2;}
public function get_MSG() {return $this->USRPM_MSG;}
public function get_DATSND() {return $this->USRPM_DATSND;}
public function get_READ() {return $this->USRPM_READ;}
   

public function set_IDPM($USRPM_IDPM) {$this->USRPM_IDPM=$USRPM_IDPM;}
public function set_USR1($USRPM_USR1) {$this->USRPM_USR1=$USRPM_USR1;}
public function set_USR2($USRPM_USR2) {$this->USRPM_USR2=$USRPM_USR2;}
public function set_MSG($USRPM_MSG) {$this->USRPM_MSG=$USRPM_MSG;}
public function set_DATSND($USRPM_DATSND) {$this->USRPM_DATSND=$USRPM_DATSND;}
public function set_READ($USRPM_READ) {$this->USRPM_READ=$USRPM_READ;}
}