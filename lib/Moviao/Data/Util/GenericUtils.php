<?php
declare(strict_types=1);
namespace Moviao\Data\Util;
use \Moviao\Data\Rad\Comments;
use PDO;

class GenericUtils extends BaseUtils {

public function getTags(string $lang) : array {
    $return_data = array();

    try {
        $sField = "TAG_DESC_EN";
        if ('fr' === $lang) {
            $sField = "TAG_DESC_FR";
        } else if ('es' === $lang) {
            $sField = "TAG_DESC_ES";
        }
        $strSql = "SELECT ID_TAG,$sField DES FROM tags order by $sField;";
        $stmt = $this->data->prepare($strSql);
        if ($stmt === false) { return $return_data; }
        if (! $this->data->execute()) {
            return $return_data;
        }
        while ($obj = $this->data->fetchObject($stmt)) {
            if (is_null($obj)) break;
            $row['data']['value'] =  $obj->ID_TAG;
            $row['data']['text'] = $obj->DES;
            $return_data[]  = $row['data'];
        }
    } catch (\Error $ex) {
        error_log("GenericUtils >> getTags = $ex");
    }
    return $return_data;
}

public function searchTags(string $lang, string $query) : array {
    $return_data = array();
    $strSql = "SELECT TAG_DESC_FR DES FROM tags WHERE (MATCH(TAG_DESC_FR) AGAINST(? IN BOOLEAN MODE)) order by TAG_DESC_FR;";
    try {
        $params = [[ "parameter" => 1, "value" => $query . '*', "type" => PDO::PARAM_STR ]];
        $rows = $this->data->readAllObject($strSql, $params);
        if (count($rows) < 1) return $return_data;
        foreach ($rows as $obj) {
            if (empty($obj)) break;
            $row['value'] = $obj->DES;
            $return_data[]  = $row;
        }
    } catch (\Error $ex) {
        error_log("GenericUtils >> searchTags = $ex");
    }
    return $return_data;
}

public function getCountryID(string $country_code) : ?string {
    $row = null;
    $strSql = "SELECT id FROM countries WHERE UPPER(code)=UPPER(?) LIMIT 1;";
    try {
        $params = [[ "parameter" => 1, "value" => $country_code, "type" => PDO::PARAM_STR ]];
        $row = $this->data->readColumn($strSql, $params);
    } catch (\Error $e) {
        error_log('GenericUtils >> searchTags : ' . $e);
    }
    return $row;
}






//public function loadCountries() : array {
//    $return_data = array();
//
//    try {
//        $strSql = "SELECT id,name FROM countries WHERE active=1 order by name;";
//        $stmt = $this->data->prepare($strSql);
//        if ($stmt === false) { return $return_data; }
//        if (! $this->data->execute()) { return $return_data; }
//
//        $i=0;
//        while ($obj = $this->data->fetchObject($stmt)) {
//            if (is_null($obj)) break;
//            $row['data']['ID'] =  $obj->id;
//            $row['data']['NAME'] =  $obj->name;
//            $return_data[]  = $row['data'];
//            $i++;
//        }
//    } catch (\Error $ex) {
//        error_log("GenericUtils >> loadCountries = $ex");
//    }
//    return $return_data;
//}
//
//public function loadStates($country_id) : array {
//    $return_data = array();
//    try {
//        $strSql = "SELECT id,name FROM states WHERE country_id=? order by name;";
//        $stmt = $this->data->prepare($strSql);
//        if ($stmt === false) { return $return_data; }
//        if (! $this->data->bindParam(1,$country_id,PDO::PARAM_INT)) {
//            return $return_data;
//        }
//        if (! $this->data->execute()) { return $return_data; }
//        $i=0;
//        while ($obj = $this->data->fetchObject($stmt)) {
//            if (is_null($obj)) break;
//            $row['data']['ID'] =  $obj->id;
//            $row['data']['NAME'] =  $obj->name;
//            $return_data[]  = $row['data'];
//            $i++;
//        }
//    } catch (\Error $ex) {}
//    return $return_data;
//}
//
//public function loadCities($country_id,$state_id) : array {
//    $return_data = array();
//    try {
//        $strSql = "SELECT id,name FROM cities WHERE country_id=? AND region_id=? order by name;";
//        $stmt = $this->data->prepare($strSql);
//        if ($stmt === false) { return $return_data; }
//        if (! $this->data->bindParam(1,$country_id,PDO::PARAM_INT)) {
//            return $return_data;
//        }
//        if (! $this->data->bindParam(2,$state_id,PDO::PARAM_INT)) {
//            return $return_data;
//        }
//        if (! $this->data->execute()) { return $return_data; }
//        $i=0;
//        while ($obj = $this->data->fetchObject($stmt)) {
//            if (is_null($obj)) break;
//            $row['data']['ID'] =  $obj->id;
//            $row['data']['NAME'] = $obj->name;
//            $return_data[]  = $row['data'];
//            $i++;
//        }
//    } catch (\Error $ex) {}
//    return $return_data;
//}
//
//public function searchCity($term, $country_id) : array {
//    $return_data = array();
//    try {
//        $strSql = "(SELECT 0 idcity, '' cityname, id idstate, name d FROM states WHERE country_id=:country AND UPPER(name)  LIKE UPPER(:term) LIMIT 10) ";
//        $strSql .= "UNION ";
//        $strSql .= "(SELECT b.id idcity, b.name cityname, a.id idstate, a.name d FROM states a, cities b WHERE b.country_id=:country AND a.id=b.region_id AND UPPER(b.name) LIKE UPPER(:term) LIMIT 10)";
//        $stmt = $this->data->prepare($strSql);
//        if ($stmt === false) { return $return_data; }
//        if (! $this->data->bindParam(":country",$country_id,PDO::PARAM_INT)) {
//            return $return_data;
//        }
//        if (! $this->data->bindParam(":term",($term . "%"),PDO::PARAM_STR)) {
//            return $return_data;
//        }
//        if (! $this->data->execute()) { return $return_data; }
//        while ($obj = $this->data->fetchObject($stmt)) {
//            if (is_null($obj)) continue;
//            // Label
//            $label = $obj->cityname;
//            if ((strlen($obj->cityname) > 0) && ((strlen($obj->d) > 0))) $label .= ', ';
//            $label .= $obj->d;
//            //$arr = array('id' => "{$obj->a}#{$obj->c}", 'label' => $label, 'value' => $label);
//            $arr = array('state_id' => $obj->state_id,'city_id' => $obj->city_id,'state_name' => $obj->state_name,'city_name' => $obj->city_name, 'post_code' => $obj->post_code, 'label' => $label, 'value' => $label);
//
//
//            $row['data'] = $arr;
//            $return_data[] = $row['data'];
//        }
//    } catch (\Error $ex) {
//        error_log("GenericUtils >> searchCity : $ex");
//        $return_data = array();
//    }
//    return $return_data;
//}

//public function createComment(\stdClass $form) : bool {
//    $result = false;
//    try {
//        $comObj = new \Moviao\Data\Rad\Comments(parent::GetData());
//        $fdata = $comObj->filterForm($form);
//        $result = $comObj->create($fdata);
//    } catch (\Error $ex) {
//        error_log("GenericUtils >> createComment = $ex");
//    }
//    return $result;
//}

//public function loadComments($IDCOMLNK,$IDCOMLNKTYP, $index = null) : array {
//    $return_data = array();
//
//    try {
//        $strSql = "SELECT comments.*, (SELECT USR_NDISP FROM users WHERE ID_USR=comments.ID_USR) USR FROM comments WHERE COM_ACTIVE=1 AND COM_IDCOMLNK=? AND COM_IDCOMLNKTYP=? ORDER BY COM_DATCRE DESC LIMIT 20";
//        $params = [
//            [ "parameter" => 1, "value" => $IDCOMLNK, "type" => PDO::PARAM_INT ],
//            [ "parameter" => 2, "value" => $IDCOMLNKTYP, "type" => PDO::PARAM_INT ]
//        ];
//        $rows = $this->data->readAllObject($strSql, $params);
//        if (count($rows) < 1) {
//            return $return_data;
//        }
//        foreach ($rows as $obj) {
//            if (is_null($obj)) continue;
//            $row['data']['USR'] =  $obj->USR;
//            $row['data']['DATCRE'] =  $obj->COM_DATCRE;
//            $row['data']['DESC'] =  $obj->COM_DESC;
//            $return_data[]  = $row['data'];
//        }
//
//    } catch (\Error $ex) {
//       error_log("GenericUtils >> loadComments = $ex");
//    }
//    return $return_data;
//}

//public function createFeed(\stdClass $form) : bool {
//    $result = false;
//    try {
//        $comObj = new \Moviao\Data\Rad\Feeds(parent::GetData());
//        $fdata = $comObj->filterForm($form);
//        $result = $comObj->create($fdata);
//    } catch (\Error $ex) {
//        error_log("GenericUtils >> createFeed = $ex");
//    }
//    return $result;
//}
//
//public function readFeeds(\stdClass $form, int $index = null) : array {
//    $return_data = array();
//    try {
//        $isUserOnly = '';
//        if ($form->restrictUserOnly === FALSE) {
//            $isUserOnly = ' OR ID_USR IN (SELECT DISTINCT ID_USR2 FROM users_list WHERE ID_USR=:iduser AND USR_ACTIVE=1 AND USR_CONFIRM=1 AND USR_IGNORE=0)';
//        }
//        $strSql = "SELECT FDS_IDFDS,FDS_MSG,FDS_IMG,FDS_DATCRE,(SELECT USR_NDISP FROM users WHERE ID_USR=feeds.ID_USR) USR, (SELECT USR_PICTURE FROM users WHERE ID_USR=feeds.ID_USR) USR_PIC  FROM feeds WHERE FDS_ACTIVE=1 AND (ID_USR=:iduser $isUserOnly) AND FDS_IDLNKTYP=1 ORDER BY FDS_DATCRE DESC LIMIT 20";
//        $params = [[ "parameter" => ':iduser', "value" => $form->iduser, "type" => PDO::PARAM_INT ]];
//        $rows = $this->data->readAllObject($strSql, $params);
//        if (count($rows) < 1) {
//            return $return_data;
//        }
//        foreach ($rows as $obj) {
//            if (is_null($obj)) continue;
//            $row['data']['ID'] =  $obj->FDS_IDFDS;
//            $row['data']['USR'] =  $obj->USR;
//            $row['data']['USR_PIC'] =  $obj->USR_PIC;
//            $row['data']['FEED_PIC'] =  $obj->FDS_IMG;
//            $row['data']['DATCRE'] =  $obj->FDS_DATCRE;
//            $row['data']['MSG'] =  $obj->FDS_MSG;
//            $return_data[]  = $row['data'];
//        }
//
//    } catch (\Error $ex) {
//       error_log("GenericUtils >> readFeeds = $ex");
//    }
//    return $return_data;
//}

}?>