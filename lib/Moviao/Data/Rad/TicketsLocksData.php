<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// DataClass Tickets_locks
namespace Moviao\Data\Rad;

class TicketsLocksData {     
private $ID_TICKETTYPE = null;
private $ID_SESSION = null;
private $TICLOC_QTE = null;
private $TICLOC_DATINS = null;   

public function __construct() {}
// Getters 
public function get_TICKETTYPE() {return $this->ID_TICKETTYPE;}
public function get_SESSION() {return $this->ID_SESSION;}
public function get_QTE() {return $this->TICLOC_QTE;}
public function get_DATINS() {return $this->TICLOC_DATINS;}
   

public function set_TICKETTYPE($ID_TICKETTYPE) {$this->ID_TICKETTYPE=$ID_TICKETTYPE;}
public function set_SESSION(?string $ID_SESSION) {$this->ID_SESSION=$ID_SESSION;}
public function set_QTE($TICLOC_QTE) {$this->TICLOC_QTE=$TICLOC_QTE;}
public function set_DATINS($TICLOC_DATINS) {$this->TICLOC_DATINS=$TICLOC_DATINS;}
}