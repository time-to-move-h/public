<?php
//$common = null;
//try {
//    $sessionUser->checkAuth();
//    if ((! isset($params)) || (!isset($params['start'])) || (!isset($params['end']))) {
//        exit();
//    }
//    //exit($params);
//    $iduser = $sessionUser->getIDUSER();
//    $parameters = [ 'start' => $params['start'], 'end' => $params['end'], 'iduser' => $iduser ];
//    $common = new \Moviao\Data\CommonData();
//    $common->iniDatabase();
//    $data = $common->getDBConn();
//    $data->connectDBA();
//    $util = new \Moviao\Data\Util\EventsUtils($common);
//    $feed = $util->getCalendarFeed($parameters);
//    //exit(var_dump(json_encode($feed)));
//    echo json_encode($feed);
//} catch (Exception $ex) {
//    error_log("eventscalfeed.php : $ex");
//} finally {
//    if ($common !== null) $common->disconnect();
//}