<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2020-2021.
// DataClass Channels
namespace Moviao\Data\Rad;

class ChannelsData {     
private $ID_CHA = null;
private $CHA_NAME = null;
private $CHA_TITLE = null;
private $CHA_PICTURE = null;
private $CHA_PICTURE_MIN = null;
private $CHA_PICTURE_RND = null;
private $CHA_DESCL = null;
private $CHA_ABOUT = null;
private $CHA_ORG = null;
private $ID_CHAVIS = null;
private $CHA_ONLINE = null;
private $CHA_OFFICIAL = null;
private $CHA_ACTIVE = null;
private $CHA_CONFIRM = null;
private $CHA_DATINS = null;
private $CHA_DATMOD = null;   

public function __construct() {}
// Getters 
public function get_CHA() {return $this->ID_CHA;}
public function get_NAME() {return $this->CHA_NAME;}
public function get_TITLE() {return $this->CHA_TITLE;}
public function get_PICTURE() {return $this->CHA_PICTURE;}
public function get_PICTURE_MIN() {return $this->CHA_PICTURE_MIN;}
public function get_PICTURE_RND() {return $this->CHA_PICTURE_RND;}
public function get_DESCL() {return $this->CHA_DESCL;}
public function get_ABOUT() {return $this->CHA_ABOUT;}
public function get_ORG() {return $this->CHA_ORG;}
public function get_CHAVIS() {return $this->ID_CHAVIS;}
public function get_ONLINE() {return $this->CHA_ONLINE;}
public function get_OFFICIAL() {return $this->CHA_OFFICIAL;}
public function get_ACTIVE() {return $this->CHA_ACTIVE;}
public function get_CONFIRM() {return $this->CHA_CONFIRM;}
public function get_DATINS() {return $this->CHA_DATINS;}
public function get_DATMOD() {return $this->CHA_DATMOD;}
   

public function set_CHA($ID_CHA) {$this->ID_CHA=$ID_CHA;}
public function set_NAME(?string $CHA_NAME) {$this->CHA_NAME=$CHA_NAME;}
public function set_TITLE(?string $CHA_TITLE) {$this->CHA_TITLE=$CHA_TITLE;}
public function set_PICTURE(?string $CHA_PICTURE) {$this->CHA_PICTURE=$CHA_PICTURE;}
public function set_PICTURE_MIN(?string $CHA_PICTURE_MIN) {$this->CHA_PICTURE_MIN=$CHA_PICTURE_MIN;}
public function set_PICTURE_RND(?string $CHA_PICTURE_RND) {$this->CHA_PICTURE_RND=$CHA_PICTURE_RND;}
public function set_DESCL(?string $CHA_DESCL) {$this->CHA_DESCL=$CHA_DESCL;}
public function set_ABOUT(?string $CHA_ABOUT) {$this->CHA_ABOUT=$CHA_ABOUT;}
public function set_ORG(?string $CHA_ORG) {$this->CHA_ORG=$CHA_ORG;}
public function set_CHAVIS($ID_CHAVIS) {$this->ID_CHAVIS=$ID_CHAVIS;}
public function set_ONLINE($CHA_ONLINE) {$this->CHA_ONLINE=$CHA_ONLINE;}
public function set_OFFICIAL($CHA_OFFICIAL) {$this->CHA_OFFICIAL=$CHA_OFFICIAL;}
public function set_ACTIVE($CHA_ACTIVE) {$this->CHA_ACTIVE=$CHA_ACTIVE;}
public function set_CONFIRM($CHA_CONFIRM) {$this->CHA_CONFIRM=$CHA_CONFIRM;}
public function set_DATINS($CHA_DATINS) {$this->CHA_DATINS=$CHA_DATINS;}
public function set_DATMOD($CHA_DATMOD) {$this->CHA_DATMOD=$CHA_DATMOD;}
}