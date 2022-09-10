<?php
declare(strict_types=1);
// @author Moviao Ltd.
// All rights reserved 2022-2023.
// DataClass Events
namespace Moviao\Data\Rad;

class EventsData {     
private $ID_EVT = null;
private $EVT_URLLINK = null;
private $ID_EVTTYP = null;
private $ID_CHA = null;
private $ID_EVTLINK = null;
private $ID_EVTACT = null;
private $ID_EVTPOI = null;
private $ID_EVTVIS = null;
private $EVT_TITLE = null;
private $EVT_DESCL = null;
private $EVT_FREE = null;
private $EVT_MAXUSE = null;
private $EVT_MULTI = null;
private $EVT_STREET = null;
private $EVT_STREETN = null;
private $EVT_VENUE = null;
private $EVT_PCODE = null;
private $EVT_STREET2 = null;
private $EVT_DESCRDV = null;
private $EVT_CITY = null;
private $EVT_ACTIVE = null;
private $EVT_STATE = null;
private $EVT_ONLINE = null;
private $EVT_COUNTRY = null;
private $EVT_CONFIRM = null;
private $EVT_LOCATIONP = null;
private $ID_COUNTRY_CODE = null;
private $EVT_DATINS = null;
private $EVT_PICTURE = null;
private $EVT_OSMID = null;
private $EVT_DATMOD = null;
private $EVT_URL = null;
private $EVT_PICTURE_MIN = null;
private $EVT_ISONLINE = null;
private $EVT_LANG = null;   

public function __construct() {}
// Getters 
public function get_EVT() {return $this->ID_EVT;}
public function get_URLLINK() {return $this->EVT_URLLINK;}
public function get_EVTTYP() {return $this->ID_EVTTYP;}
public function get_CHA() {return $this->ID_CHA;}
public function get_EVTLINK() {return $this->ID_EVTLINK;}
public function get_EVTACT() {return $this->ID_EVTACT;}
public function get_EVTPOI() {return $this->ID_EVTPOI;}
public function get_EVTVIS() {return $this->ID_EVTVIS;}
public function get_TITLE() {return $this->EVT_TITLE;}
public function get_DESCL() {return $this->EVT_DESCL;}
public function get_FREE() {return $this->EVT_FREE;}
public function get_MAXUSE() {return $this->EVT_MAXUSE;}
public function get_MULTI() {return $this->EVT_MULTI;}
public function get_STREET() {return $this->EVT_STREET;}
public function get_STREETN() {return $this->EVT_STREETN;}
public function get_VENUE() {return $this->EVT_VENUE;}
public function get_PCODE() {return $this->EVT_PCODE;}
public function get_STREET2() {return $this->EVT_STREET2;}
public function get_DESCRDV() {return $this->EVT_DESCRDV;}
public function get_CITY() {return $this->EVT_CITY;}
public function get_ACTIVE() {return $this->EVT_ACTIVE;}
public function get_STATE() {return $this->EVT_STATE;}
public function get_ONLINE() {return $this->EVT_ONLINE;}
public function get_COUNTRY() {return $this->EVT_COUNTRY;}
public function get_CONFIRM() {return $this->EVT_CONFIRM;}
public function get_LOCATIONP() {return $this->EVT_LOCATIONP;}
public function get_COUNTRY_CODE() {return $this->ID_COUNTRY_CODE;}
public function get_DATINS() {return $this->EVT_DATINS;}
public function get_PICTURE() {return $this->EVT_PICTURE;}
public function get_OSMID() {return $this->EVT_OSMID;}
public function get_DATMOD() {return $this->EVT_DATMOD;}
public function get_URL() {return $this->EVT_URL;}
public function get_PICTURE_MIN() {return $this->EVT_PICTURE_MIN;}
public function get_ISONLINE() {return $this->EVT_ISONLINE;}
public function get_LANG() {return $this->EVT_LANG;}
   

public function set_EVT($ID_EVT) {$this->ID_EVT=$ID_EVT;}
public function set_URLLINK(?string $EVT_URLLINK) {$this->EVT_URLLINK=$EVT_URLLINK;}
public function set_EVTTYP($ID_EVTTYP) {$this->ID_EVTTYP=$ID_EVTTYP;}
public function set_CHA($ID_CHA) {$this->ID_CHA=$ID_CHA;}
public function set_EVTLINK($ID_EVTLINK) {$this->ID_EVTLINK=$ID_EVTLINK;}
public function set_EVTACT(?string $ID_EVTACT) {$this->ID_EVTACT=$ID_EVTACT;}
public function set_EVTPOI(?string $ID_EVTPOI) {$this->ID_EVTPOI=$ID_EVTPOI;}
public function set_EVTVIS($ID_EVTVIS) {$this->ID_EVTVIS=$ID_EVTVIS;}
public function set_TITLE(?string $EVT_TITLE) {$this->EVT_TITLE=$EVT_TITLE;}
public function set_DESCL(?string $EVT_DESCL) {$this->EVT_DESCL=$EVT_DESCL;}
public function set_FREE($EVT_FREE) {$this->EVT_FREE=$EVT_FREE;}
public function set_MAXUSE($EVT_MAXUSE) {$this->EVT_MAXUSE=$EVT_MAXUSE;}
public function set_MULTI($EVT_MULTI) {$this->EVT_MULTI=$EVT_MULTI;}
public function set_STREET(?string $EVT_STREET) {$this->EVT_STREET=$EVT_STREET;}
public function set_STREETN(?string $EVT_STREETN) {$this->EVT_STREETN=$EVT_STREETN;}
public function set_VENUE(?string $EVT_VENUE) {$this->EVT_VENUE=$EVT_VENUE;}
public function set_PCODE(?string $EVT_PCODE) {$this->EVT_PCODE=$EVT_PCODE;}
public function set_STREET2(?string $EVT_STREET2) {$this->EVT_STREET2=$EVT_STREET2;}
public function set_DESCRDV(?string $EVT_DESCRDV) {$this->EVT_DESCRDV=$EVT_DESCRDV;}
public function set_CITY(?string $EVT_CITY) {$this->EVT_CITY=$EVT_CITY;}
public function set_ACTIVE($EVT_ACTIVE) {$this->EVT_ACTIVE=$EVT_ACTIVE;}
public function set_STATE(?string $EVT_STATE) {$this->EVT_STATE=$EVT_STATE;}
public function set_ONLINE($EVT_ONLINE) {$this->EVT_ONLINE=$EVT_ONLINE;}
public function set_COUNTRY(?string $EVT_COUNTRY) {$this->EVT_COUNTRY=$EVT_COUNTRY;}
public function set_CONFIRM($EVT_CONFIRM) {$this->EVT_CONFIRM=$EVT_CONFIRM;}
public function set_LOCATIONP($EVT_LOCATIONP) {$this->EVT_LOCATIONP=$EVT_LOCATIONP;}
public function set_COUNTRY_CODE($ID_COUNTRY_CODE) {$this->ID_COUNTRY_CODE=$ID_COUNTRY_CODE;}
public function set_DATINS($EVT_DATINS) {$this->EVT_DATINS=$EVT_DATINS;}
public function set_PICTURE(?string $EVT_PICTURE) {$this->EVT_PICTURE=$EVT_PICTURE;}
public function set_OSMID($EVT_OSMID) {$this->EVT_OSMID=$EVT_OSMID;}
public function set_DATMOD($EVT_DATMOD) {$this->EVT_DATMOD=$EVT_DATMOD;}
public function set_URL(?string $EVT_URL) {$this->EVT_URL=$EVT_URL;}
public function set_PICTURE_MIN(?string $EVT_PICTURE_MIN) {$this->EVT_PICTURE_MIN=$EVT_PICTURE_MIN;}
public function set_ISONLINE($EVT_ISONLINE) {$this->EVT_ISONLINE=$EVT_ISONLINE;}
public function set_LANG(?string $EVT_LANG) {$this->EVT_LANG=$EVT_LANG;}
}