<?php
//declare(strict_types=1);
//header("Access-Control-Allow-Origin: *");
//
//$search_query = "";
//$loc_lat = "";
//$loc_long = "";
//$offset = "0";
//$tag = "";
//
//if(isset($params['search_query']) && (! is_null($params['search_query']))) {
//    $search_query = filter_var($params['search_query'], FILTER_SANITIZE_STRING);
//}
//if(isset($params['loc_lat']) && (! is_null($params['loc_lat']))) {
//    $loc_lat = filter_var($params['loc_lat'], FILTER_SANITIZE_STRING);
//}
//if(isset($params['loc_long']) && (! is_null($params['loc_long']))) {
//    $loc_long = filter_var($params['loc_long'], FILTER_SANITIZE_STRING);
//}
//if(isset($params['o']) && (! is_null($params['o']))) {
//    $offset = filter_var($params['o'], FILTER_SANITIZE_NUMBER_INT);
//}
//if(isset($params['tag']) && (! is_null($params['tag']))) {
//    $tag = filter_var($params['tag'], FILTER_SANITIZE_NUMBER_INT);
//}
//
//$form = new stdClass();
//$form->q = $search_query;
//$form->lat = $loc_lat;
//$form->long = $loc_long;
//$form->o = $offset;
//$form->p = $tag;
//
//$result = array();
//$eventsObj = null;
//
//try {
//    $eventsObj = new \Moviao\Data\EventsCommon();
//    $eventsObj->iniDatabase();
//    $eventsObj->setSession($sessionUser);
//    $result = $eventsObj->showLatest($form);
//} catch (Exception $ex) {
//
//} finally {
//    if ($eventsObj != null) {
//        $eventsObj->disconnect();
//        $eventsObj = null;
//    }
//}
//
//echo json_encode($result);
//exit();