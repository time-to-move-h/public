<?php
//$common = null;
//try {
//    $common = new \Moviao\Data\CommonData();
//    $common->iniDatabase();
//    $data = $common->getDBConn();
//    $data->connectDBA();
//    $util = new \Moviao\Data\Util\GenericUtils($common);
//    echo json_encode($util->getTags("es-ES"));
//} catch (Exception $ex) {
//    error_log("tags.json.php : $ex");
//} finally {
//    if ($common !== null) $common->disconnect();
//}