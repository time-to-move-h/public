<?php 
declare(strict_types=1);
$sessionUser->startSession();
$server = new \Moviao\Http\ServerInfo();
$suffix = $server->getServerSuffix();    
if ($suffix !== 'LOCALHOST') {
    // https redirection
    if(! Moviao\Http\ServerInfo::isSecure())
    {
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        exit();
    }
}

$dataView = NULL;
//try {
//
//    if (isset($_POST["UID"])) {
//        $form = new stdClass();
//        $form->URLLINK = $_POST["UID"];
//        $uid = mb_substr(filter_var($form->URLLINK,FILTER_SANITIZE_STRING),0,60);
//
//        $commonData = new \Moviao\Data\CommonData();
//        $commonData->iniDatabase();
//        $commonData->setSession($sessionUser);
//        $data = $commonData->getDBConn();
//        // Execute Transaction
//        $data->connectDBA();
//
//        // Event
//        $event_utils = new \Moviao\Data\Util\EventsUtils($commonData);
//        $ticket_utils = new \Moviao\Data\Util\TicketsUtils($commonData);
//        $result_event = $event_utils->show($form);
//
//        //echo var_dump($result_event);
//        if (is_array($result_event)) {
//            $dataView = $result_event;
//        }
//        // ------------------------------------------------
//        // Get Event ID
//        $eventID = $event_utils->getEventID($uid);
//        //echo var_dump($uid);
//        if ($eventID <= 0) {
//            error_log("checkout.php >> eventID not exists !");
//            $error = 'eventID not exists';
//            throw new Exception($error);
//        }
//
//        //echo var_dump($_POST["ID"]);
//        if (isset($_POST["ID"]) && is_array($_POST["ID"])) {
//            foreach($_POST["ID"] as $key=>$val) {
//                //echo $key.'=>'. $val.  ' - ' . $_POST["TICKET_QTE"][$key] .'<br>';
//                $form_ticket = new stdClass();
//                $form_ticket->idevent = $eventID;
//                $form_ticket->idticket = $_POST["ID"][$key];
//                $ticket_detail = $ticket_utils->getTicket($form_ticket);
//
//                //echo var_dump($form_ticket);
//
//                $ticket_detail['TICKET_QTE'] = $_POST["TICKET_QTE"][$key];
//                $cart_view [] = $ticket_detail;
//
//                $total_qte += (int)$_POST["TICKET_QTE"][$key];
//            }
//        }
//        //echo var_dump($cart_view);
//    }
//} catch (\Error $err) {
//    error_log("checkout.php >> " . $err->getMessage());
//} catch (\Exception $e) {
//    error_log("Caught exception: payment.php >> " . $e->getMessage());
//} finally {
//    if (isset($data) && null !== $data) $data->disconnect();
//}

// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/payment.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
$this->layout('tpl::template', $array);
$this->start('scripts');
//echo '<script data-main="/dist/ctrl/payment" src="/dist/js/require.js"></script>';
$this->stop();
echo $this->insert('partials::payment.tpl', ['info' => $t, 'lang' => $lang, 'dataView' => $dataView, 'sessionUser' => $sessionUser ]);