<?php
declare(strict_types=1);
namespace Moviao\Data\Util;
use PDO;
use PHPUnit\Framework\Error\Error;
use stdClass;

class ChannelsUtils extends BaseUtils {

// Create Channel
public function create(\stdClass $form) : \stdClass {

    $resultObj = new \stdClass();
    $resultObj->lastid = -1;
    $resultObj->result = false;

    $channel = new \Moviao\Data\Rad\Channels($this->commonData);
    $fdata = $channel->filterForm($form);

    $fdata->set_ACTIVE(1);
    $fdata->set_ONLINE(0);
    $fdata->set_CONFIRM(0);
    $fdata->set_OFFICIAL(0);
    $fdata->set_CHAVIS(1);
    $fdata->set_DATINS(date('Y-m-d H:i:s'));

    $fdata->set_ABOUT(null);
    $fdata->set_ORG(null);
    $fdata->set_PICTURE(null);
    $fdata->set_PICTURE_MIN(null);
    $fdata->set_PICTURE_RND(null);

    // Create Channel
    $resultObj->result = $channel->create($fdata);

    if ($resultObj->result === true) {
        $resultObj->lastid = $this->getData()->getDBConn()->lastInsertId();
    }

    return $resultObj;
}

/**
 * Create Channel Admin
 * @param stdClass $form
 * @return bool
 */
public function create_channel_admin(\stdClass $form) : bool {
    $bresult = false;
    $admin = new \Moviao\Data\Rad\ChannelsAdmin($this->commonData);
    $fdata = new \Moviao\Data\Rad\ChannelsAdminData();
    $fdata->set_CHA($form->idcha);
    $fdata->set_USR($form->iduser);
    $fdata->set_ACTIVE(1);
    $fdata->set_DATINS(date('Y-m-d H:i:s') );
    $bresult = $admin->create($fdata);
    return $bresult;
}

private function isSubscribed(\Moviao\Data\Rad\ChannelsListData $fdata) : int {
    $strSql = 'SELECT CHALST_CONFIRM FROM channels_list WHERE ID_CHA=? AND ID_USR=? AND CHALST_ACTIVE=1;';
    $params = [[ 'parameter' => 1, 'value' => $fdata->get_CHA(), 'type' => PDO::PARAM_STR],[ 'parameter' => 2, 'value' => $fdata->get_USR(), 'type' => PDO::PARAM_STR]];
    $row = $this->data->readColumn($strSql, $params);
    $iSubscribed = -1; // Non Exist
    if ($row !== false) {
        if ($row === '1') {
            $iSubscribed = 1; // Enabled
        } else if ($row === '0')  {
            $iSubscribed = 0; // Enabled but not confirmed   
        } 
    }
    return $iSubscribed;    
}

private function isChannelListExist(\Moviao\Data\Rad\ChannelsListData $fdata) : int {
    $iSubscribed = -1; // Non Exist
    $strSql = 'SELECT CHALST_CONFIRM FROM channels_list WHERE ID_CHA=? AND ID_USR=? LIMIT 1;';
    $params = [
        [ 'parameter' => 1, 'value' => $fdata->get_CHA(), 'type' => PDO::PARAM_STR],
        [ 'parameter' => 2, 'value' => $fdata->get_USR(), 'type' => PDO::PARAM_STR]];

    $row = $this->data->readColumn($strSql, $params);

    if ($row !== false) {
        if ($row === '1') {
            $iSubscribed = 1; // Enabled
        } else if ($row === '0')  {
            $iSubscribed = 0; // Enabled but not confirmed
        }
    }
    return $iSubscribed;
}


private function isGroupConfirm(\Moviao\Data\Rad\ChannelsListData $fdata) : bool {
    $strSql = 'SELECT CHA_CONFIRM FROM channels WHERE ID_CHA=? LIMIT 1;';
    $params = [[ 'parameter' => 1, 'value' => $fdata->get_CHA(), 'type' => PDO::PARAM_STR]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row !== false) {
        if ($row === '1') {
            return true;
        }
    }
    return false;
}

private function getMaxChannelListID(\Moviao\Data\Rad\ChannelsListData $fdata) : int {
    $strSql = 'SELECT MAX(ID_CHALST) FROM channels_list WHERE ID_CHA=? AND ID_USR=? FOR UPDATE;';
    $params = [
        [ 'parameter' => 1, 'value' => $fdata->get_CHA(), 'type' => PDO::PARAM_STR],
        [ 'parameter' => 2, 'value' => $fdata->get_USR(), 'type' => PDO::PARAM_STR]];

    $row = $this->data->readColumn($strSql, $params);
    $max = 1;    
    if ($row !== false) {
        $max = (int)($row);
        if (empty($max) || (! is_numeric($max))) {
            $max = 1;
        } else {
            $max += 1;
        }            
    }
    return $max;    
}

private function isOrganizer(\Moviao\Data\Rad\ChannelsListData $fdata) : bool {
    $strSql = 'SELECT 1 FROM channels_admin WHERE ID_CHA=? AND ID_USR=? AND CHAADM_ACTIVE=1 LIMIT 1;';
    $params = [
        [ 'parameter' => 1, 'value' => $fdata->get_CHA(), 'type' => PDO::PARAM_STR],
        [ 'parameter' => 2, 'value' => $fdata->get_USR(), 'type' => PDO::PARAM_STR]];

    $row = $this->data->readColumn($strSql, $params);
    if ($row !== false) {
        if ($row === '1') {
            return true;
        }
    }
    return false;
}

public function getChannelID(string $form) : int {
    $strSql = 'SELECT ID_CHA FROM channels WHERE UPPER(CHA_NAME)=UPPER(?);';        
    $params = [[ 'parameter' => 1, 'value' => $form, 'type' => PDO::PARAM_STR]];    
    $row = $this->data->readColumn($strSql, $params);
    return ($row === FALSE) ? 0 : (int)$row; // TODO verificate bigint problem
}

public function subscribe(\Moviao\Data\Rad\ChannelsListData $fdata) : bool {
    $result = false;
    $iSubscribed = $this->isChannelListExist($fdata);
    $confirmation = ($this->isGroupConfirm($fdata)) ? 0 : 1;
    $organizer = ($this->isOrganizer($fdata)) ? 1 : 2;

    if ($iSubscribed === -1) {                 
        $fdata->set_CONFIRM($confirmation);
        $fdata->set_ACTIVE(1);
        $fdata->set_CHAROLE($organizer);
        $max = $this->getMaxChannelListID($fdata);                       
        $fdata->set_CHALST($max);                
        $channel_list = new \Moviao\Data\Rad\ChannelsList($this->commonData);                               
        $result = $channel_list->create($fdata);
    } else {
        $result = $this->updateChannelList($fdata, 1,0);
        //exit((var_export($result, true)));
    }

    return $result;
}

public function updateChannelList(\Moviao\Data\Rad\ChannelsListData $fdata, int $new_active, int $old_active) : bool {
    $strSql = 'UPDATE channels_list SET CHALST_ACTIVE=?,CHALST_DATMOD=UTC_TIMESTAMP() WHERE ID_CHA=? AND ID_USR=? AND CHALST_ACTIVE=? LIMIT 1;';
    $params = [
        [ 'parameter' => 1, 'value' => $new_active, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => 2, 'value' => $fdata->get_CHA(), 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 3, 'value' => $fdata->get_USR(), 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 4, 'value' => $old_active, 'type' => PDO::PARAM_INT ]
        ];
    return $this->data->executeNonQuery($strSql, $params);
}

public function updateSubscribersCounter(int $channelID) : bool {
    $strSql = 'UPDATE channels SET CHA_COUNTER_FOLLOWERS=(SELECT COUNT(*) FROM channels_list WHERE ID_CHA=:idcha AND CHALST_ACTIVE=1),CHA_DATMOD=UTC_TIMESTAMP() WHERE ID_CHA=:idcha AND CHA_ACTIVE=1 LIMIT 1;';
    $params = [[ 'parameter' => 'idcha', 'value' => $channelID, 'type' => PDO::PARAM_INT ]];
    return $this->data->executeNonQuery($strSql, $params);
}

public function publish(\stdClass $formObj) : bool {
    $strSql = 'UPDATE channels SET CHA_ONLINE=1,CHA_DATMOD=UTC_TIMESTAMP() WHERE ID_CHA=:idchannel AND CHA_ONLINE=0 AND CHA_ACTIVE=1 AND 1=(SELECT 1 FROM channels_admin WHERE ID_CHA=:idchannel AND CHAADM_ACTIVE=1 AND ID_USR=:iduser LIMIT 1) LIMIT 1;';
    $params = [   
    [ 'parameter' => ':idchannel', 'value' => $formObj->IDCHA, 'type' => PDO::PARAM_STR ],
    [ 'parameter' => ':iduser', 'value' => $formObj->IDUSER, 'type' => PDO::PARAM_STR ]];
    return $this->data->executeNonQuery($strSql, $params);     
}

//private function publish_location(\stdClass $formObj) : bool {
//    $strSql = 'UPDATE channels_location SET CHALOC_ACTIVE=1,CHALOC_DATMOD=UTC_TIMESTAMP() WHERE ID_CHA=:idchannel AND CHALOC_ACTIVE=0 AND 1=(SELECT 1 FROM channels_list WHERE ID_CHA=:idchannel AND CHALST_ACTIVE=1 AND ID_USR=:iduser AND ID_CHAROLE=1 LIMIT 1) LIMIT 1;';
//    $params = [
//    [ 'parameter' => ':idchannel', 'value' => $formObj->IDCHA, 'type' => PDO::PARAM_STR ],
//    [ 'parameter' => ':iduser', 'value' => $formObj->IDUSER, 'type' => PDO::PARAM_STR ]];
//    return $this->data->executeNonQuery($strSql, $params);
//}

// Modify Channel decription
public function modify_channel_desc(\stdClass $form) : bool {
    $strSql = 'UPDATE channels SET CHA_TITLE=:title,CHA_DESCL=:descl,CHA_DATMOD=UTC_TIMESTAMP() WHERE ID_CHA=:idchannel AND ID_CHA IN (SELECT ID_CHA FROM channels_list WHERE ID_CHA=:idchannel AND ID_USR=:iduser AND ID_CHAROLE=1 AND CHALST_ACTIVE=1) LIMIT 1;';    
    $params = [
        [ 'parameter' => ':title', 'value' => $form->title, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':descl', 'value' => $form->descl, 'type' => PDO::PARAM_STR ],        
        [ 'parameter' => ':idchannel', 'value' => $form->idchannel, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_STR ]];
    return $this->data->executeNonQuery($strSql, $params); 
}

// Modify Channel Location
private function modify_channel_Venue($form) : bool {
    $locationp = 'POINT(' . $form->lat . ' ' . $form->lon . ')';
    $strSql = 'UPDATE channels_location SET CHALOC_CITY=:city,CHALOC_COUNTRY=:country,CHALOC_COUNTRY_CODE=:country_code,CHALOC_LOCATIONP=ST_PointFromText(:locationp) ,CHALOC_OSMID=:osmid ,CHALOC_PCODE=:pcode ,CHALOC_STATE=:state ,CHALOC_STREET=:street ,CHALOC_STREET2=:street2 ,CHALOC_STREETN=:streetn ,CHALOC_VENUE=:venue,CHALOC_DATMOD=UTC_TIMESTAMP() WHERE ID_CHA=:idchannel AND ID_CHA IN (SELECT ID_CHA FROM channels_list WHERE ID_CHA=:idchannel AND ID_USR=:iduser AND ID_CHAROLE=1 AND CHALST_ACTIVE=1) LIMIT 1;';
    $params = [
        [ 'parameter' => ':city', 'value' => $form->city, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':country', 'value' => $form->country, 'type' => PDO::PARAM_STR ],        
        [ 'parameter' => ':country_code', 'value' => $form->country_code, 'type' => PDO::PARAM_INT ],        
        [ 'parameter' => ':locationp', 'value' => $locationp, 'type' => PDO::PARAM_STR ],                
        [ 'parameter' => ':osmid', 'value' => $form->osmid, 'type' => PDO::PARAM_INT ],        
        [ 'parameter' => ':pcode', 'value' => $form->pcode, 'type' => PDO::PARAM_STR ],        
        [ 'parameter' => ':state', 'value' => $form->state, 'type' => PDO::PARAM_STR ],        
        [ 'parameter' => ':street', 'value' => $form->street, 'type' => PDO::PARAM_STR ],        
        [ 'parameter' => ':street2', 'value' => $form->street2, 'type' => PDO::PARAM_STR ],        
        [ 'parameter' => ':streetn', 'value' => $form->streetn, 'type' => PDO::PARAM_STR ],        
        [ 'parameter' => ':venue', 'value' => $form->venue, 'type' => PDO::PARAM_STR ],                        
        [ 'parameter' => ':idchannel', 'value' => $form->idchannel, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_STR ]];
    return $this->data->executeNonQuery($strSql, $params); 
}

public function display() : array {
    $return_data = array(); 
    try { 
        // Clause Where
        $where = [];        
        $where[] = ['name' => 'CHA_ACTIVE', 'value' => 1, 'type' => 1];
        $where[] = ['name' => 'ID_CHAVIS', 'value' => 1, 'type' => 1];
        $where[] = ['name' => 'CHA_CONFIRM', 'value' => 0, 'type' => 1];
        $where[] = ['name' => 'CHA_ONLINE', 'value' => 1, 'type' => 1];
        $orderby = 'ID_CHA DESC';
        $limit = 6;               
        $channels = new \Moviao\Data\Rad\Channels(parent::getData());
        $return_data = $channels->show($where, $orderby, $limit);        
        if (count($return_data) > 0) {
            //$subscription = $event_utils->isSubscribed($fdata);           
            //$return_data['data']['SUBSCRIPTION'] = $subscription;        
        }              
    } catch (Exception $ex) {
        exit('error');
    } 
    
    return $return_data;
}

public function show(\Moviao\Data\Rad\ChannelsListData $fdata) : array {
    $return_data = array();
    $strSql = 'SELECT c.*, IFNULL((SELECT CHALST_CONFIRM FROM channels_list WHERE ID_CHA=c.ID_CHA AND ID_USR=:iduser AND CHALST_ACTIVE=1), -1) SUBSCRIPTION,(SELECT 1 FROM channels_admin WHERE ID_CHA=c.ID_CHA AND ID_USR=:iduser AND CHAADM_ACTIVE=1 LIMIT 1) ROLE, (SELECT ID_USR FROM channels_admin WHERE ID_CHA=c.ID_CHA AND ID_USR=:iduser AND CHAADM_ACTIVE=1 LIMIT 1) USR FROM channels c WHERE ID_CHA=:idchannel AND CHA_ACTIVE=1;';
    $idcha = $fdata->get_CHA();
    $iduser = $fdata->get_USR();
    $params = [
        [ 'parameter' => 'idchannel', 'value' => $idcha, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 'iduser', 'value' => $iduser, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readLine($strSql, $params);
    if ($row === null || $row === false) {
        return $return_data;
    } else {
        $return_data['NAME'] = $row['CHA_NAME'];
        $return_data['TITLE'] = $row['CHA_TITLE'];
        $return_data['PICTURE'] = $row['CHA_PICTURE'];
        $return_data['PICTURE_MIN'] = $row['CHA_PICTURE_MIN'];
        $return_data['PICTURE_RND'] = $row['CHA_PICTURE_RND'];
        $return_data['DESCL'] = $row['CHA_DESCL'];
        $return_data['USR'] = $row['USR'];
        $return_data['ROLE'] = $row['ROLE'];
        $return_data['ONLINE'] = $row['CHA_ONLINE'];
        $return_data['SUBSCRIPTION'] = $row['SUBSCRIPTION'];
        $return_data['OFFICIAL'] = $row['CHA_OFFICIAL'];
        $return_data['COUNTER_FOLLOWERS'] = $row['CHA_COUNTER_FOLLOWERS'];
    }
    return $return_data;    
}

// Channels Roles -------------------------------------------------
private function assign_role(\stdClass $form) : bool {
    $result = false;
    try {
        $channel = new \Moviao\Data\Rad\ChannelsList(parent::getData());
        $fdata = $channel->filterForm($form);
        $fdata->set_ACTIVE(1);
        // Max ID
        $ID_CHALST = $this->getMaxChannelListID($fdata);
        $fdata->set_CHALST($ID_CHALST);
        $result = $channel->create($fdata);
    } catch (\Error $e) {
        exit('error assign_role ' . $e);
    }
    return $result;
}

public function getChannels(\stdClass $form) : array {
    $return_data = array();
    //$strSql = 'SELECT DISTINCT c.* FROM channels c, channels_list cl WHERE c.ID_CHA=cl.ID_CHA AND cl.CHALST_ACTIVE=1 AND c.CHA_ACTIVE=1 AND cl.ID_USR=:iduser AND (c.CHA_ONLINE=1 OR (cl.ID_CHAROLE=1 AND c.CHA_ONLINE=0)) order by c.CHA_NAME ASC LIMIT :limit OFFSET :offset;';
    $strSql = 'SELECT DISTINCT c.* FROM channels c '
            . 'INNER JOIN channels_list cl ON cl.ID_CHA=c.ID_CHA AND cl.CHALST_ACTIVE=1 AND cl.ID_USR=:iduser '
            . 'WHERE c.CHA_ACTIVE=1 '
            . 'UNION SELECT DISTINCT c.* FROM channels c '
            . 'INNER JOIN channels_admin ca ON ca.ID_CHA=c.ID_CHA AND ca.CHAADM_ACTIVE=1 AND ca.ID_USR=:iduser  '
            . 'WHERE c.CHA_ACTIVE=1 '
            . 'order by CHA_NAME ASC LIMIT :limit OFFSET :offset;';

    $params = [
        [ 'parameter' => 'iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 'limit', 'value' => $form->limit, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => 'offset', 'value' => $form->offset, 'type' => PDO::PARAM_INT ]
    ];
    $rows = $this->data->readAllObject($strSql, $params);
    if (count($rows) < 1) {
        return $return_data;
    }
    foreach ($rows as $obj) {
        if (empty($obj)) break;
        $row['data']['DESCL'] =  $obj->CHA_DESCL;
        $row['data']['PICTURE'] =  $obj->CHA_PICTURE;
        $row['data']['NAME'] =  $obj->CHA_NAME;
        $row['data']['TITLE'] =  $obj->CHA_TITLE;
        $row['data']['ONLINE'] =  $obj->CHA_ONLINE;
        $return_data[]  = $row['data'];
    }
    return $return_data;
}

public function getChannelsCombo(string $iduser) : array {
    $return_data = array();
    $strSql = 'SELECT DISTINCT c.CHA_NAME,c.CHA_TITLE FROM channels c, channels_admin ca WHERE c.ID_CHA=ca.ID_CHA AND ca.CHAADM_ACTIVE=1 AND c.CHA_ACTIVE=1 AND ca.ID_USR=? order by c.CHA_TITLE LIMIT 50';

    $params = [[ 'parameter' => 1, 'value' => $iduser, 'type' => PDO::PARAM_STR ]];
    $rows = $this->data->readAllObject($strSql, $params);
    if (count($rows) < 1) return $return_data;
    foreach ($rows as $obj) {
        if (empty($obj)) break;
        $row['data']['NAME'] =  $obj->CHA_NAME;
        $row['data']['TITLE'] =  $obj->CHA_TITLE;
        $return_data[]  = $row['data'];
    }
    return $return_data;
}

//public function getChannelsCombo($IDUSER) : array {
//    $return_data = array();
//
//    try {
//        $strSql = 'SELECT DISTINCT c.CHA_NAME,c.CHA_TITLE FROM channels c, channels_list cl WHERE c.ID_CHA=cl.ID_CHA AND cl.CHALST_ACTIVE=1 AND cl.ID_USR=? order by c.ID_CHA DESC LIMIT 50';
//        $stmt = $this->data->prepare($strSql);
//        if ($stmt === false) return $return_data;
//        if (! $this->data->bindParam(1,$IDUSER,PDO::PARAM_INT)) return $return_data;
//        if (! $this->data->execute()) return $return_data;
//        while ($obj = $this->data->fetchObject($stmt)) {
//            if (is_null($obj)) continue;
//            $row['data']['NAME'] =  $obj->CHA_NAME;
//            $row['data']['TITLE'] =  $obj->CHA_TITLE;
//            $return_data[]  = $row['data'];
//        }
//    } catch (\Error $e) {
//        error_log('ChannelsUtils >> getChannelsCombo : $e');
//    }
//
//    return $return_data;
//}


/**
 * show Channels (public)
 *
 * @param stdClass $form
 * @return array
 * @throws \Moviao\Database\Exception\DBException
 */
public function showChannels(\stdClass $form) : array {
    $return_data = array();

    $params = [
        [ 'parameter' => ':iduser', 'value' => $form->IDUSER, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':limit', 'value' => $form->limit, 'type' => PDO::PARAM_INT ],
        [ 'parameter' => ':offset', 'value' => $form->offset, 'type' => PDO::PARAM_INT ]];

    $strSql  = 'SELECT c.*,(SELECT CHALST_CONFIRM FROM channels_list WHERE ID_CHA=c.ID_CHA AND ID_USR=:iduser AND CHALST_ACTIVE=1) SUBSCRIPTION FROM channels c WHERE c.CHA_ONLINE=1 AND c.CHA_ACTIVE=1 AND c.ID_CHAVIS=1 ';

    // Query
    if (! empty($form->query)) {
        $params[] = [ 'parameter' => ':q', 'value' => $form->query . '*', 'type' => PDO::PARAM_STR ];
        $strSql .= 'AND (MATCH(c.CHA_TITLE,c.CHA_DESCL) AGAINST(:q IN BOOLEAN MODE)) ';
    }

        
    //$strSql  = 'SELECT c.*,cl.CHALOC_COUNTRY,cl.CHALOC_STATE,cl.CHALOC_CITY ,(SELECT CHALST_CONFIRM FROM channels_list WHERE ID_CHA=c.ID_CHA AND ID_USR=:iduser AND CHALST_ACTIVE=1) SUBSCRIPTION FROM channels c, channels_location cl WHERE ';
    //$strSql .= 'c.CHA_ONLINE=1 AND c.CHA_ACTIVE=1 AND c.ID_CHAVIS=1 AND c.ID_CHA=cl.ID_CHA ';

    // TAGS
//    if (isset($form->tag)) {
//        array_push($params, [ 'parameter' => ':idtag', 'value' => $form->tag, 'type' => PDO::PARAM_INT ] );
//        $strSql .= 'AND c.ID_CHA IN (SELECT t.ID_CHA FROM channels_tags t WHERE t.ID_CHA=c.ID_CHA AND t.ID_TAG=:idtag AND t.CHATAG_ACTIVE=1) ';
//    }

    $strSql .= 'ORDER BY c.ID_CHA DESC LIMIT :limit OFFSET :offset;';
            
    $row = $this->data->readAllObject($strSql, $params);      
    if ($row !== false) {
        foreach ($row as $obj) {
            if (empty($obj)) {
                break;
            }
            $datarow = array();
            $datarow['NAME'] =  $obj->CHA_NAME;
            $datarow['TITLE'] = $obj->CHA_TITLE;
            $datarow['DESCL'] = mb_substr(strip_tags($obj->CHA_DESCL),0,55) . '...';
            $datarow['SUBSCRIPTION'] = $obj->SUBSCRIPTION;
            $datarow['PICTURE'] =  $obj->CHA_PICTURE;
            $datarow['PICTURE_MIN'] =  $obj->CHA_PICTURE_MIN;
            $return_data[]  = $datarow;
        }       
    }

    return $return_data;
}

public function updateBackgroundImage(string $channel_name, string $iduser, string $picture_loc, ?string $picture_loc_mini) : bool {
    $strSql = 'UPDATE channels SET CHA_PICTURE=?,CHA_PICTURE_MIN=?,CHA_DATMOD=UTC_TIMESTAMP() WHERE UPPER(CHA_NAME)=UPPER(?) AND ID_CHA IN (SELECT ID_CHA FROM channels_admin WHERE ID_CHA=channels.ID_CHA AND ID_USR=? AND CHAADM_ACTIVE=1);';
    $params = [        
        [ 'parameter' => 1, 'value' => $picture_loc, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 2, 'value' => $picture_loc_mini, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 3, 'value' => $channel_name, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 4, 'value' => $iduser, 'type' => PDO::PARAM_STR ]];
    return $this->data->executeNonQuery($strSql, $params);   
}

public function updateAvatarImage(string $channel_name, string $iduser, string $picture_loc) : bool {
    $strSql = 'UPDATE channels SET CHA_PICTURE_RND=?,CHA_DATMOD=UTC_TIMESTAMP() WHERE UPPER(CHA_NAME)=UPPER(?) AND ID_CHA IN (SELECT ID_CHA FROM channels_admin WHERE ID_CHA=channels.ID_CHA AND ID_USR=? AND CHAADM_ACTIVE=1);';
    $params = [
        [ 'parameter' => 1, 'value' => $picture_loc, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 2, 'value' => $channel_name, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => 3, 'value' => $iduser, 'type' => PDO::PARAM_STR ]];
    return $this->data->executeNonQuery($strSql, $params);
}

// Get Tags in relation with a channel
public function getTags(int $channelID) : array {
    $return_data = array();        
    $strSql = 'SELECT t.ID_TAG, t.TAG_DESC_ES FROM tags t,channels_tags c WHERE t.ID_TAG=c.ID_TAG AND c.ID_CHA=:idchannel AND c.CHATAG_ACTIVE=1;';    
    $params = [[ 'parameter' => ':idchannel', 'value' => $channelID, 'type' => PDO::PARAM_STR ]];
    $rows = $this->data->readAllObject($strSql, $params);    
    if (count($rows) < 1) return $return_data;                        
    foreach ($rows as $obj) {
        if (is_null($obj)) continue;           
        $row['data']['TAG'] = $obj->ID_TAG;
        $row['data']['DESC'] = $obj->TAG_DESC_ES;                        
        $return_data[]  = $row['data'];                   
    } 
    return $return_data;
}

public function create_channel_tags(\stdClass $form) : bool {
    $bresult = false;
    $tags_split = $form->tags;                    
    $channel_tags = new \Moviao\Data\Rad\ChannelsTags($this->commonData);
    foreach ($tags_split as $tag_value) {
        if (ctype_digit($tag_value)) {
            $ID_TAG = (int)($tag_value);
            if ($ID_TAG > 0) {                            
                $fdata_tags = new \Moviao\Data\Rad\ChannelsTagsData();
                $fdata_tags->set_CHA($form->idchannel);
                $fdata_tags->set_TAG($ID_TAG);
                $fdata_tags->set_ACTIVE(1);
                $fdata_tags->set_DATCRE(date('Y-m-d H:i:s'));
                $bresult = $channel_tags->create($fdata_tags);
                if (! $bresult) {
                    $this->getData()->setError(4646445412121547);
                    break;
                }
                $fdata_tags = null;
            }
        }        
    } 
    return $bresult;
}

// Desactivate Channel Tags
public function desactivate_channel_tags(\stdClass $form) : bool {
    $strSql = 'UPDATE channels_tags SET CHATAG_ACTIVE=0,CHATAG_DATMOD=UTC_TIMESTAMP() WHERE ID_CHA=:idchannel AND ID_TAG=:idtag AND CHATAG_ACTIVE=1 AND ID_CHA IN (SELECT ID_CHA FROM channels_list WHERE ID_CHA=:idchannel AND ID_USR=:iduser AND ID_CHAROLE=1 AND CHALST_ACTIVE=1) LIMIT 1;';    
    $params = [        
        [ 'parameter' => ':idtag', 'value' => $form->idtag, 'type' => PDO::PARAM_INT ],        
        [ 'parameter' => ':idchannel', 'value' => $form->idchannel, 'type' => PDO::PARAM_STR ],
        [ 'parameter' => ':iduser', 'value' => $form->iduser, 'type' => PDO::PARAM_INT ]];    
    return $this->data->executeNonQuery($strSql, $params); 
}

public function getTagsChannel(\stdClass $form) : array {
    $return_data = array();         
    $strSql = 'SELECT ID_TAG FROM channels_tags WHERE ID_CHA=:idchannel AND CHATAG_ACTIVE=1 order by ID_TAG';    
    $params = [[ 'parameter' => ':idchannel', 'value' => $form->idchannel, 'type' => PDO::PARAM_STR ]];
    $rows = $this->data->readAllObject($strSql, $params);    
    if (count($rows) < 1) return $return_data;                        
    //exit(var_dump($rows));        
    foreach ($rows as $obj) {
        if (is_null($obj)) continue;   
        $return_data[]  = (int)($obj->ID_TAG);
    } 
    return $return_data;
}

public function generateToken(int $tokenLength) : string
{
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return 'chan'.substr(str_shuffle($permitted_chars), 0, $tokenLength);;
}

// verify if user have a channel
public function isChannelAdminPresent(string $iduser) : bool {
    $strSql = 'SELECT 1 FROM DUAL WHERE (SELECT COUNT(*) FROM channels_admin WHERE ID_USR=? AND CHAADM_ACTIVE=1) > 0';
    $params = [[ 'parameter' => 1, 'value' => $iduser, 'type' => PDO::PARAM_STR ]];
    $row = $this->data->readColumn($strSql, $params);
    if ($row === false) {
        return false;
    } else {
        return true;
    }
}

}