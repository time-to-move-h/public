<?php
//header("Access-Control-Allow-Origin: *");
//
//$uid = NULL;
//if(isset($params['uid'])) {
//    $uid = filter_var($params['uid'], FILTER_SANITIZE_STRING);
//    if (strlen($uid) < 1) $uid = NULL;
//}
//$form = new stdClass();
//$form->uid = $uid;
//
////$date = new DateTime();
////$fecha = $date->format(DateTime::ISO8601);
//$eventsObj = new \Moviao\Data\GenericCommon();
//$eventsObj->iniDatabase();
//$eventsObj->setSession($sessionUser);
//$result = $eventsObj->readFeeds($form);
//$eventsObj->disconnect();
//$eventsObj = NULL;
////echo (var_dump($result));
//$feed_data = array();
//if ($result['result'] == true) {
//    foreach ($result['data'] as $value) {
//        $feed_line['id'] = $value['ID'];
//        $feed_line['published'] = $value['DATCRE'];
//        $feed_line['title'] = $value['MSG'];
//        $feed_line['url'] = '';
//
//        $feed_actor = array();
//        $feed_actor['displayName'] = $value['USR'];
//        $feed_actor['imageurl'] = $value['USR_PIC'];
//
//        $feed_attachment = array();
//        $feed_attachment['fullImage'] = [ 'url' => $value['FEED_PIC']];
//
//        $feed_object = array();
//        $feed_object['attachments'] = [$feed_attachment];
//
//        $feed_line['actor'] = $feed_actor;
//        $feed_line['object'] = $feed_object;
//
//        $feed_data[] = $feed_line;
//    }
//}
//
//$eventsObj = ['items' => $feed_data];
//echo json_encode($eventsObj);