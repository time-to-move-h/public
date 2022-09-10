<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// DataClass Tags
namespace Moviao\Data\Rad;

class TagsData {     
private $ID_TAG;
private $TAG_IDLINK;
private $TAG_DESC_FR;
private $TAG_DESC_ES;
private $TAG_DESC_EN;   

public function __construct() {}
// Getters 
public function get_TAG() {return $this->ID_TAG;}
public function get_IDLINK() {return $this->TAG_IDLINK;}
public function get_DESC_FR() {return $this->TAG_DESC_FR;}
public function get_DESC_ES() {return $this->TAG_DESC_ES;}
public function get_DESC_EN() {return $this->TAG_DESC_EN;}
   

public function set_TAG($ID_TAG) {$this->ID_TAG=$ID_TAG;}
public function set_IDLINK($TAG_IDLINK) {$this->TAG_IDLINK=$TAG_IDLINK;}
public function set_DESC_FR($TAG_DESC_FR) {$this->TAG_DESC_FR=$TAG_DESC_FR;}
public function set_DESC_ES($TAG_DESC_ES) {$this->TAG_DESC_ES=$TAG_DESC_ES;}
public function set_DESC_EN($TAG_DESC_EN) {$this->TAG_DESC_EN=$TAG_DESC_EN;}
}