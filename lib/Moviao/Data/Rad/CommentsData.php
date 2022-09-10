<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// DataClass Comments
namespace Moviao\Data\Rad;

class CommentsData {     
private $COM_IDCOM;
private $COM_IDCOMLNK;
private $COM_IDCOMLNKTYP;
private $ID_USR;
private $COM_DESC;
private $COM_ACTIVE;
private $COM_DATCRE;   

public function __construct() {}
// Getters 
public function get_IDCOM() {return $this->COM_IDCOM;}
public function get_IDCOMLNK() {return $this->COM_IDCOMLNK;}
public function get_IDCOMLNKTYP() {return $this->COM_IDCOMLNKTYP;}
public function get_USR() {return $this->ID_USR;}
public function get_DESC() {return $this->COM_DESC;}
public function get_ACTIVE() {return $this->COM_ACTIVE;}
public function get_DATCRE() {return $this->COM_DATCRE;}
   

public function set_IDCOM($COM_IDCOM) {$this->COM_IDCOM=$COM_IDCOM;}
public function set_IDCOMLNK($COM_IDCOMLNK) {$this->COM_IDCOMLNK=$COM_IDCOMLNK;}
public function set_IDCOMLNKTYP($COM_IDCOMLNKTYP) {$this->COM_IDCOMLNKTYP=$COM_IDCOMLNKTYP;}
public function set_USR($ID_USR) {$this->ID_USR=$ID_USR;}
public function set_DESC($COM_DESC) {$this->COM_DESC=$COM_DESC;}
public function set_ACTIVE($COM_ACTIVE) {$this->COM_ACTIVE=$COM_ACTIVE;}
public function set_DATCRE($COM_DATCRE) {$this->COM_DATCRE=$COM_DATCRE;}
}