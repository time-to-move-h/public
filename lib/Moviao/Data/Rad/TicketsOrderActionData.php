<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2018-2019.
// DataClass Tickets_order_action
namespace Moviao\Data\Rad;

class TicketsOrderActionData {     
private $ID_TICORDER_ACTION = null;
private $ID_TICORDER = null;
private $ID_TICORDER_STATUS = null;
private $TICORDER_ACTION_DATINS = null;   

public function __construct() {}
// Getters 
public function get_TICORDER_ACTION() {return $this->ID_TICORDER_ACTION;}
public function get_TICORDER() {return $this->ID_TICORDER;}
public function get_TICORDER_STATUS() {return $this->ID_TICORDER_STATUS;}
public function get_ACTION_DATINS() {return $this->TICORDER_ACTION_DATINS;}
   

public function set_TICORDER_ACTION($ID_TICORDER_ACTION) {$this->ID_TICORDER_ACTION=$ID_TICORDER_ACTION;}
public function set_TICORDER($ID_TICORDER) {$this->ID_TICORDER=$ID_TICORDER;}
public function set_TICORDER_STATUS(?string $ID_TICORDER_STATUS) {$this->ID_TICORDER_STATUS=$ID_TICORDER_STATUS;}
public function set_ACTION_DATINS($TICORDER_ACTION_DATINS) {$this->TICORDER_ACTION_DATINS=$TICORDER_ACTION_DATINS;}
}