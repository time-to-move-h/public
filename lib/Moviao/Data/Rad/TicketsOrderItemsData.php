<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// DataClass Tickets_order_items
namespace Moviao\Data\Rad;

class TicketsOrderItemsData {     
private $ID_TICORDER = null;
private $ID_TICKETTYPE = null;
private $TICORDER_ITEM_QTE = null;
private $TICORDER_ITEM_PRICE = null;
private $TICORDER_ITEM_DATINS = null;
private $TICORDER_ITEM_PRICEHT = null;
private $TICORDER_ITEM_TVA = null;
private $TIC_CURRENCY_ID = null;
private $TICORDER_ITEM_DATMOD = null;
private $TICORDER_ITEM_SERVICEFEE = null;
private $EVTDAT_DATBEG = null;
private $TICORDER_ITEM_PROCESSINGFEE = null;
private $EVTDAT_DATEND = null;
private $ID_ZONEIDBEG = null;
private $ID_ZONEIDEND = null;   

public function __construct() {}
// Getters 
public function get_TICORDER() {return $this->ID_TICORDER;}
public function get_TICKETTYPE() {return $this->ID_TICKETTYPE;}
public function get_ITEM_QTE() {return $this->TICORDER_ITEM_QTE;}
public function get_ITEM_PRICE() {return $this->TICORDER_ITEM_PRICE;}
public function get_ITEM_DATINS() {return $this->TICORDER_ITEM_DATINS;}
public function get_ITEM_PRICEHT() {return $this->TICORDER_ITEM_PRICEHT;}
public function get_ITEM_TVA() {return $this->TICORDER_ITEM_TVA;}
public function get_CURRENCY_ID() {return $this->TIC_CURRENCY_ID;}
public function get_ITEM_DATMOD() {return $this->TICORDER_ITEM_DATMOD;}
public function get_ITEM_SERVICEFEE() {return $this->TICORDER_ITEM_SERVICEFEE;}
public function get_DATBEG() {return $this->EVTDAT_DATBEG;}
public function get_ITEM_PROCESSINGFEE() {return $this->TICORDER_ITEM_PROCESSINGFEE;}
public function get_DATEND() {return $this->EVTDAT_DATEND;}
public function get_ZONEIDBEG() {return $this->ID_ZONEIDBEG;}
public function get_ZONEIDEND() {return $this->ID_ZONEIDEND;}
   

public function set_TICORDER($ID_TICORDER) {$this->ID_TICORDER=$ID_TICORDER;}
public function set_TICKETTYPE($ID_TICKETTYPE) {$this->ID_TICKETTYPE=$ID_TICKETTYPE;}
public function set_ITEM_QTE($TICORDER_ITEM_QTE) {$this->TICORDER_ITEM_QTE=$TICORDER_ITEM_QTE;}
public function set_ITEM_PRICE($TICORDER_ITEM_PRICE) {$this->TICORDER_ITEM_PRICE=$TICORDER_ITEM_PRICE;}
public function set_ITEM_DATINS($TICORDER_ITEM_DATINS) {$this->TICORDER_ITEM_DATINS=$TICORDER_ITEM_DATINS;}
public function set_ITEM_PRICEHT($TICORDER_ITEM_PRICEHT) {$this->TICORDER_ITEM_PRICEHT=$TICORDER_ITEM_PRICEHT;}
public function set_ITEM_TVA($TICORDER_ITEM_TVA) {$this->TICORDER_ITEM_TVA=$TICORDER_ITEM_TVA;}
public function set_CURRENCY_ID(?string $TIC_CURRENCY_ID) {$this->TIC_CURRENCY_ID=$TIC_CURRENCY_ID;}
public function set_ITEM_DATMOD($TICORDER_ITEM_DATMOD) {$this->TICORDER_ITEM_DATMOD=$TICORDER_ITEM_DATMOD;}
public function set_ITEM_SERVICEFEE($TICORDER_ITEM_SERVICEFEE) {$this->TICORDER_ITEM_SERVICEFEE=$TICORDER_ITEM_SERVICEFEE;}
public function set_DATBEG($EVTDAT_DATBEG) {$this->EVTDAT_DATBEG=$EVTDAT_DATBEG;}
public function set_ITEM_PROCESSINGFEE($TICORDER_ITEM_PROCESSINGFEE) {$this->TICORDER_ITEM_PROCESSINGFEE=$TICORDER_ITEM_PROCESSINGFEE;}
public function set_DATEND($EVTDAT_DATEND) {$this->EVTDAT_DATEND=$EVTDAT_DATEND;}
public function set_ZONEIDBEG($ID_ZONEIDBEG) {$this->ID_ZONEIDBEG=$ID_ZONEIDBEG;}
public function set_ZONEIDEND($ID_ZONEIDEND) {$this->ID_ZONEIDEND=$ID_ZONEIDEND;}
}