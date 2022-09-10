<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// DataClass Tickets_order
namespace Moviao\Data\Rad;

class TicketsOrderData {     
private $ID_TICORDER = null;
private $ID_TICORDER_STATUS = null;
private $ID_USR = null;
private $TICORDER_FIRSTNAME = null;
private $TICORDER_LASTNAME = null;
private $TICORDER_MAIL = null;
private $TICORDER_MPHONE = null;
private $TICORDER_STREET = null;
private $TICORDER_STREET2 = null;
private $TICORDER_CITY = null;
private $TICORDER_STATE = null;
private $TICORDER_PCODE = null;
private $TICORDER_COUNTRY = null;
private $TICORDER_ACTIVE = null;
private $TICORDER_DATINS = null;
private $TICORDER_DATMOD = null;   

public function __construct() {}
// Getters 
public function get_TICORDER() {return $this->ID_TICORDER;}
public function get_TICORDER_STATUS() {return $this->ID_TICORDER_STATUS;}
public function get_USR() {return $this->ID_USR;}
public function get_FIRSTNAME() {return $this->TICORDER_FIRSTNAME;}
public function get_LASTNAME() {return $this->TICORDER_LASTNAME;}
public function get_MAIL() {return $this->TICORDER_MAIL;}
public function get_MPHONE() {return $this->TICORDER_MPHONE;}
public function get_STREET() {return $this->TICORDER_STREET;}
public function get_STREET2() {return $this->TICORDER_STREET2;}
public function get_CITY() {return $this->TICORDER_CITY;}
public function get_STATE() {return $this->TICORDER_STATE;}
public function get_PCODE() {return $this->TICORDER_PCODE;}
public function get_COUNTRY() {return $this->TICORDER_COUNTRY;}
public function get_ACTIVE() {return $this->TICORDER_ACTIVE;}
public function get_DATINS() {return $this->TICORDER_DATINS;}
public function get_DATMOD() {return $this->TICORDER_DATMOD;}
   

public function set_TICORDER($ID_TICORDER) {$this->ID_TICORDER=$ID_TICORDER;}
public function set_TICORDER_STATUS(?string $ID_TICORDER_STATUS) {$this->ID_TICORDER_STATUS=$ID_TICORDER_STATUS;}
public function set_USR($ID_USR) {$this->ID_USR=$ID_USR;}
public function set_FIRSTNAME(?string $TICORDER_FIRSTNAME) {$this->TICORDER_FIRSTNAME=$TICORDER_FIRSTNAME;}
public function set_LASTNAME(?string $TICORDER_LASTNAME) {$this->TICORDER_LASTNAME=$TICORDER_LASTNAME;}
public function set_MAIL(?string $TICORDER_MAIL) {$this->TICORDER_MAIL=$TICORDER_MAIL;}
public function set_MPHONE(?string $TICORDER_MPHONE) {$this->TICORDER_MPHONE=$TICORDER_MPHONE;}
public function set_STREET(?string $TICORDER_STREET) {$this->TICORDER_STREET=$TICORDER_STREET;}
public function set_STREET2(?string $TICORDER_STREET2) {$this->TICORDER_STREET2=$TICORDER_STREET2;}
public function set_CITY(?string $TICORDER_CITY) {$this->TICORDER_CITY=$TICORDER_CITY;}
public function set_STATE(?string $TICORDER_STATE) {$this->TICORDER_STATE=$TICORDER_STATE;}
public function set_PCODE(?string $TICORDER_PCODE) {$this->TICORDER_PCODE=$TICORDER_PCODE;}
public function set_COUNTRY($TICORDER_COUNTRY) {$this->TICORDER_COUNTRY=$TICORDER_COUNTRY;}
public function set_ACTIVE($TICORDER_ACTIVE) {$this->TICORDER_ACTIVE=$TICORDER_ACTIVE;}
public function set_DATINS($TICORDER_DATINS) {$this->TICORDER_DATINS=$TICORDER_DATINS;}
public function set_DATMOD($TICORDER_DATMOD) {$this->TICORDER_DATMOD=$TICORDER_DATMOD;}
}