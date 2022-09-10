<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// DataClass Feeds
namespace Moviao\Data\Rad;

class FeedsData {     
private $FDS_IDFDS;
private $FDS_IDLNK;
private $FDS_IDLNKTYP;
private $ID_USR;
private $FDS_MSG;
private $FDS_IMG;
private $FDS_ACTIVE;
private $FDS_DATCRE;   

public function __construct() {}
// Getters 
public function get_IDFDS() {return $this->FDS_IDFDS;}
public function get_IDLNK() {return $this->FDS_IDLNK;}
public function get_IDLNKTYP() {return $this->FDS_IDLNKTYP;}
public function get_USR() {return $this->ID_USR;}
public function get_MSG() {return $this->FDS_MSG;}
public function get_IMG() {return $this->FDS_IMG;}
public function get_ACTIVE() {return $this->FDS_ACTIVE;}
public function get_DATCRE() {return $this->FDS_DATCRE;}
   

public function set_IDFDS($FDS_IDFDS) {$this->FDS_IDFDS=$FDS_IDFDS;}
public function set_IDLNK($FDS_IDLNK) {$this->FDS_IDLNK=$FDS_IDLNK;}
public function set_IDLNKTYP($FDS_IDLNKTYP) {$this->FDS_IDLNKTYP=$FDS_IDLNKTYP;}
public function set_USR($ID_USR) {$this->ID_USR=$ID_USR;}
public function set_MSG($FDS_MSG) {$this->FDS_MSG=$FDS_MSG;}
public function set_IMG($FDS_IMG) {$this->FDS_IMG=$FDS_IMG;}
public function set_ACTIVE($FDS_ACTIVE) {$this->FDS_ACTIVE=$FDS_ACTIVE;}
public function set_DATCRE($FDS_DATCRE) {$this->FDS_DATCRE=$FDS_DATCRE;}
}