<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// DataClass Tickets_order_details
namespace Moviao\Data\Rad;

class TicketsOrderDetailsData {     
private $ID_TICORDER = null;
private $ID_TICKETTYPE = null;
private $ID_TICKETDET = null;
private $TICDET_FNAME = null;
private $TICDET_LNAME = null;
private $TICDET_EMAIL = null;
private $TICDET_INFO = null;
private $TICDET_CODE = null;
private $TICDET_DATINS = null;
private $TICDET_LOCKED = null;
private $TICDET_DATMOD = null;   

public function __construct() {}
// Getters 
public function get_TICORDER() {return $this->ID_TICORDER;}
public function get_TICKETTYPE() {return $this->ID_TICKETTYPE;}
public function get_TICKETDET() {return $this->ID_TICKETDET;}
public function get_FNAME() {return $this->TICDET_FNAME;}
public function get_LNAME() {return $this->TICDET_LNAME;}
public function get_EMAIL() {return $this->TICDET_EMAIL;}
public function get_INFO() {return $this->TICDET_INFO;}
public function get_CODE() {return $this->TICDET_CODE;}
public function get_DATINS() {return $this->TICDET_DATINS;}
public function get_LOCKED() {return $this->TICDET_LOCKED;}
public function get_DATMOD() {return $this->TICDET_DATMOD;}
   

public function set_TICORDER($ID_TICORDER) {$this->ID_TICORDER=$ID_TICORDER;}
public function set_TICKETTYPE($ID_TICKETTYPE) {$this->ID_TICKETTYPE=$ID_TICKETTYPE;}
public function set_TICKETDET($ID_TICKETDET) {$this->ID_TICKETDET=$ID_TICKETDET;}
public function set_FNAME(?string $TICDET_FNAME) {$this->TICDET_FNAME=$TICDET_FNAME;}
public function set_LNAME(?string $TICDET_LNAME) {$this->TICDET_LNAME=$TICDET_LNAME;}
public function set_EMAIL(?string $TICDET_EMAIL) {$this->TICDET_EMAIL=$TICDET_EMAIL;}
public function set_INFO(?string $TICDET_INFO) {$this->TICDET_INFO=$TICDET_INFO;}
public function set_CODE(?string $TICDET_CODE) {$this->TICDET_CODE=$TICDET_CODE;}
public function set_DATINS($TICDET_DATINS) {$this->TICDET_DATINS=$TICDET_DATINS;}
public function set_LOCKED($TICDET_LOCKED) {$this->TICDET_LOCKED=$TICDET_LOCKED;}
public function set_DATMOD($TICDET_DATMOD) {$this->TICDET_DATMOD=$TICDET_DATMOD;}
}