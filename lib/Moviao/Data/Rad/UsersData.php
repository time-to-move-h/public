<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2018-2019.
// DataClass Users
namespace Moviao\Data\Rad;

class UsersData {     
private $ID_USR = null;
private $USR_UUID = null;
private $USR_SUBJECT = null;
private $USR_ABOUT = null;
private $USR_NNAME = null;
private $USR_NDISP = null;
private $USR_FNAME = null;
private $USR_LNAME = null;
private $USR_MNAME = null;
private $USR_PNAME = null;
private $USR_DBIRTH = null;
private $USR_STREET = null;
private $USR_STREETN = null;
private $USR_BOX = null;
private $USR_PCODE = null;
private $USR_CITY = null;
private $USR_STATE = null;
private $USR_COUNTRY = null;
private $USR_COUNTRY_CODE = null;
private $USR_ADDRESS = null;
private $USR_EMAIL = null;
private $USR_MPHONE = null;
private $USR_SEX = null;
private $USR_MAST = null;
private $USR_CONST = null;
private $ID_ACCTYP = null;
private $USR_PROFILE = null;
private $USR_PICTURE = null;
private $USR_BACKGROUND = null;
private $USR_WEBSITE = null;
private $ID_ZONEID = null;
private $USR_BACKGROUND_MIN = null;
private $USR_LANG = null;
private $USR_ACTIVE = null;
private $USR_LOCKED = null;
private $USR_DATEINS = null;
private $USR_DATEVAL = null;
private $USR_DATEMOD = null;
private $USR_OFFICIAL = null;   

public function __construct() {}
// Getters 
public function get_USR() {return $this->ID_USR;}
public function get_UUID() {return $this->USR_UUID;}
public function get_SUBJECT() {return $this->USR_SUBJECT;}
public function get_ABOUT() {return $this->USR_ABOUT;}
public function get_NNAME() {return $this->USR_NNAME;}
public function get_NDISP() {return $this->USR_NDISP;}
public function get_FNAME() {return $this->USR_FNAME;}
public function get_LNAME() {return $this->USR_LNAME;}
public function get_MNAME() {return $this->USR_MNAME;}
public function get_PNAME() {return $this->USR_PNAME;}
public function get_DBIRTH() {return $this->USR_DBIRTH;}
public function get_STREET() {return $this->USR_STREET;}
public function get_STREETN() {return $this->USR_STREETN;}
public function get_BOX() {return $this->USR_BOX;}
public function get_PCODE() {return $this->USR_PCODE;}
public function get_CITY() {return $this->USR_CITY;}
public function get_STATE() {return $this->USR_STATE;}
public function get_COUNTRY() {return $this->USR_COUNTRY;}
public function get_COUNTRY_CODE() {return $this->USR_COUNTRY_CODE;}
public function get_ADDRESS() {return $this->USR_ADDRESS;}
public function get_EMAIL() {return $this->USR_EMAIL;}
public function get_MPHONE() {return $this->USR_MPHONE;}
public function get_SEX() {return $this->USR_SEX;}
public function get_MAST() {return $this->USR_MAST;}
public function get_CONST() {return $this->USR_CONST;}
public function get_ACCTYP() {return $this->ID_ACCTYP;}
public function get_PROFILE() {return $this->USR_PROFILE;}
public function get_PICTURE() {return $this->USR_PICTURE;}
public function get_BACKGROUND() {return $this->USR_BACKGROUND;}
public function get_WEBSITE() {return $this->USR_WEBSITE;}
public function get_ZONEID() {return $this->ID_ZONEID;}
public function get_BACKGROUND_MIN() {return $this->USR_BACKGROUND_MIN;}
public function get_LANG() {return $this->USR_LANG;}
public function get_ACTIVE() {return $this->USR_ACTIVE;}
public function get_LOCKED() {return $this->USR_LOCKED;}
public function get_DATEINS() {return $this->USR_DATEINS;}
public function get_DATEVAL() {return $this->USR_DATEVAL;}
public function get_DATEMOD() {return $this->USR_DATEMOD;}
public function get_OFFICIAL() {return $this->USR_OFFICIAL;}
   

public function set_USR($ID_USR) {$this->ID_USR=$ID_USR;}
public function set_UUID(?string $USR_UUID) {$this->USR_UUID=$USR_UUID;}
public function set_SUBJECT(?string $USR_SUBJECT) {$this->USR_SUBJECT=$USR_SUBJECT;}
public function set_ABOUT(?string $USR_ABOUT) {$this->USR_ABOUT=$USR_ABOUT;}
public function set_NNAME(?string $USR_NNAME) {$this->USR_NNAME=$USR_NNAME;}
public function set_NDISP(?string $USR_NDISP) {$this->USR_NDISP=$USR_NDISP;}
public function set_FNAME(?string $USR_FNAME) {$this->USR_FNAME=$USR_FNAME;}
public function set_LNAME(?string $USR_LNAME) {$this->USR_LNAME=$USR_LNAME;}
public function set_MNAME(?string $USR_MNAME) {$this->USR_MNAME=$USR_MNAME;}
public function set_PNAME(?string $USR_PNAME) {$this->USR_PNAME=$USR_PNAME;}
public function set_DBIRTH($USR_DBIRTH) {$this->USR_DBIRTH=$USR_DBIRTH;}
public function set_STREET(?string $USR_STREET) {$this->USR_STREET=$USR_STREET;}
public function set_STREETN(?string $USR_STREETN) {$this->USR_STREETN=$USR_STREETN;}
public function set_BOX(?string $USR_BOX) {$this->USR_BOX=$USR_BOX;}
public function set_PCODE(?string $USR_PCODE) {$this->USR_PCODE=$USR_PCODE;}
public function set_CITY(?string $USR_CITY) {$this->USR_CITY=$USR_CITY;}
public function set_STATE(?string $USR_STATE) {$this->USR_STATE=$USR_STATE;}
public function set_COUNTRY(?string $USR_COUNTRY) {$this->USR_COUNTRY=$USR_COUNTRY;}
public function set_COUNTRY_CODE($USR_COUNTRY_CODE) {$this->USR_COUNTRY_CODE=$USR_COUNTRY_CODE;}
public function set_ADDRESS(?string $USR_ADDRESS) {$this->USR_ADDRESS=$USR_ADDRESS;}
public function set_EMAIL(?string $USR_EMAIL) {$this->USR_EMAIL=$USR_EMAIL;}
public function set_MPHONE(?string $USR_MPHONE) {$this->USR_MPHONE=$USR_MPHONE;}
public function set_SEX($USR_SEX) {$this->USR_SEX=$USR_SEX;}
public function set_MAST($USR_MAST) {$this->USR_MAST=$USR_MAST;}
public function set_CONST($USR_CONST) {$this->USR_CONST=$USR_CONST;}
public function set_ACCTYP($ID_ACCTYP) {$this->ID_ACCTYP=$ID_ACCTYP;}
public function set_PROFILE(?string $USR_PROFILE) {$this->USR_PROFILE=$USR_PROFILE;}
public function set_PICTURE(?string $USR_PICTURE) {$this->USR_PICTURE=$USR_PICTURE;}
public function set_BACKGROUND(?string $USR_BACKGROUND) {$this->USR_BACKGROUND=$USR_BACKGROUND;}
public function set_WEBSITE(?string $USR_WEBSITE) {$this->USR_WEBSITE=$USR_WEBSITE;}
public function set_ZONEID($ID_ZONEID) {$this->ID_ZONEID=$ID_ZONEID;}
public function set_BACKGROUND_MIN(?string $USR_BACKGROUND_MIN) {$this->USR_BACKGROUND_MIN=$USR_BACKGROUND_MIN;}
public function set_LANG(?string $USR_LANG) {$this->USR_LANG=$USR_LANG;}
public function set_ACTIVE($USR_ACTIVE) {$this->USR_ACTIVE=$USR_ACTIVE;}
public function set_LOCKED($USR_LOCKED) {$this->USR_LOCKED=$USR_LOCKED;}
public function set_DATEINS($USR_DATEINS) {$this->USR_DATEINS=$USR_DATEINS;}
public function set_DATEVAL($USR_DATEVAL) {$this->USR_DATEVAL=$USR_DATEVAL;}
public function set_DATEMOD($USR_DATEMOD) {$this->USR_DATEMOD=$USR_DATEMOD;}
public function set_OFFICIAL($USR_OFFICIAL) {$this->USR_OFFICIAL=$USR_OFFICIAL;}
}