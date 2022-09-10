<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// DataClass Tickets_type
namespace Moviao\Data\Rad;

class TicketsTypeData {     
private $ID_TICKETTYPE = null;
private $TICTYPE_NAME = null;
private $TICTYPE_DESCL = null;
private $TICTYPE_QTE = null;
private $TICTYPE_MINQTE = null;
private $TICTYPE_MAXQTE = null;
private $TICTYPE_PRICE = null;
private $TICTYPE_PRICEHT = null;
private $TICTYPE_SALE_START = null;
private $TICTYPE_SALE_END = null;
private $TICTYPE_TVA = null;
private $TICTYPE_ACTIVE = null;
private $TICTYPE_DATINS = null;
private $TIC_CURRENCY_ID = null;
private $TICTYPE_DATMOD = null;
private $TICTYPE_ONLINE = null;   

public function __construct() {}
// Getters 
public function get_TICKETTYPE() {return $this->ID_TICKETTYPE;}
public function get_NAME() {return $this->TICTYPE_NAME;}
public function get_DESCL() {return $this->TICTYPE_DESCL;}
public function get_QTE() {return $this->TICTYPE_QTE;}
public function get_MINQTE() {return $this->TICTYPE_MINQTE;}
public function get_MAXQTE() {return $this->TICTYPE_MAXQTE;}
public function get_PRICE() {return $this->TICTYPE_PRICE;}
public function get_PRICEHT() {return $this->TICTYPE_PRICEHT;}
public function get_SALE_START() {return $this->TICTYPE_SALE_START;}
public function get_SALE_END() {return $this->TICTYPE_SALE_END;}
public function get_TVA() {return $this->TICTYPE_TVA;}
public function get_ACTIVE() {return $this->TICTYPE_ACTIVE;}
public function get_DATINS() {return $this->TICTYPE_DATINS;}
public function get_CURRENCY_ID() {return $this->TIC_CURRENCY_ID;}
public function get_DATMOD() {return $this->TICTYPE_DATMOD;}
public function get_ONLINE() {return $this->TICTYPE_ONLINE;}
   

public function set_TICKETTYPE($ID_TICKETTYPE) {$this->ID_TICKETTYPE=$ID_TICKETTYPE;}
public function set_NAME(?string $TICTYPE_NAME) {$this->TICTYPE_NAME=$TICTYPE_NAME;}
public function set_DESCL(?string $TICTYPE_DESCL) {$this->TICTYPE_DESCL=$TICTYPE_DESCL;}
public function set_QTE($TICTYPE_QTE) {$this->TICTYPE_QTE=$TICTYPE_QTE;}
public function set_MINQTE($TICTYPE_MINQTE) {$this->TICTYPE_MINQTE=$TICTYPE_MINQTE;}
public function set_MAXQTE($TICTYPE_MAXQTE) {$this->TICTYPE_MAXQTE=$TICTYPE_MAXQTE;}
public function set_PRICE($TICTYPE_PRICE) {$this->TICTYPE_PRICE=$TICTYPE_PRICE;}
public function set_PRICEHT($TICTYPE_PRICEHT) {$this->TICTYPE_PRICEHT=$TICTYPE_PRICEHT;}
public function set_SALE_START($TICTYPE_SALE_START) {$this->TICTYPE_SALE_START=$TICTYPE_SALE_START;}
public function set_SALE_END($TICTYPE_SALE_END) {$this->TICTYPE_SALE_END=$TICTYPE_SALE_END;}
public function set_TVA($TICTYPE_TVA) {$this->TICTYPE_TVA=$TICTYPE_TVA;}
public function set_ACTIVE($TICTYPE_ACTIVE) {$this->TICTYPE_ACTIVE=$TICTYPE_ACTIVE;}
public function set_DATINS($TICTYPE_DATINS) {$this->TICTYPE_DATINS=$TICTYPE_DATINS;}
public function set_CURRENCY_ID(?string $TIC_CURRENCY_ID) {$this->TIC_CURRENCY_ID=$TIC_CURRENCY_ID;}
public function set_DATMOD($TICTYPE_DATMOD) {$this->TICTYPE_DATMOD=$TICTYPE_DATMOD;}
public function set_ONLINE($TICTYPE_ONLINE) {$this->TICTYPE_ONLINE=$TICTYPE_ONLINE;}
}