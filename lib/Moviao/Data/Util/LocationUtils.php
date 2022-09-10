<?php
declare(strict_types=1);
namespace Moviao\Data\Util;
use PDO;

class LocationUtils extends BaseUtils {  
    
//function locate($latitude,$longitude) {
//    $IDUSER = parent::GetcommonData()->getIDUSER();
//    $strSql = "INSERT INTO users_location (ID_USR,ULO_DATLOC,ULO_LAT,ULO_LON) VALUES ($IDUSER,UTC_TIMESTAMP(),'$latitude','$longitude');";
//    $res  = parent::GetcommonData()->getDBConn()->executeQuery($strSql);
//    return (parent::GetcommonData()->getDBUtil()->RowsAffected(parent::GetcommonData()->getDBConn()->getConnexion()) > 0) ? TRUE : FALSE;    
//}

function detectCountry() {
    $code_country = "";
    // GeoLocation ---------
    $ip = $_SERVER["REMOTE_ADDR"];
    $geoloc = new \GeoIP\GeoLocation();
    $geoloc->initDB();
    $code_country = $geoloc->getCountryFromIP($ip);
    // TODO:  To change
    if (strlen($code_country) <= 0) {
        $code_country = "ES";
    }
    //----------------------
    return $code_country;
}

}?>