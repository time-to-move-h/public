<?php 
declare(strict_types=1);
$sessionUser->startSession();
$sessionid = session_id();

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

$total_qte = 0;
$dataView = NULL;
$cart_view = array();
$iduser = null;
$user_data = null;
$urllink = null;
$bresult = false;
$data = null;

try {
    $iduser = $sessionUser->getIDUSER();

    if (isset($_POST["UID"])) {

        //echo exit(var_dump($_POST));

        $urllink = strip_tags($_POST["UID"]);
        $form = new stdClass();
        $form->URLLINK = $urllink;
        $uid = mb_substr(strip_tags($form->URLLINK, null),0,60);

        $commonData = new \Moviao\Data\CommonData();
        $commonData->iniDatabase();
        $commonData->setSession($sessionUser);
        $data = $commonData->getDBConn();
        //$form->LANG = $commonData->getSession()->getLanguage();

        // Execute Transaction
        if (! $data->connectDBA()) {
            error_log("checkout.php >> connection db impossible !");
            $error = 'connection to the db impossible !';
            throw new Exception($error);
        }

        // Event
        $event_utils = new \Moviao\Data\Util\EventsUtils($commonData);
        $ticket_utils = new \Moviao\Data\Util\TicketsUtils($commonData);
        $result_event = $event_utils->show($form);

        //echo var_dump($result_event);
        if (is_array($result_event)) {
            $dataView = $result_event;
        }
        // ------------------------------------------------
        // Get Event ID
        $eventID = $event_utils->getEventID($uid);
        //echo var_dump($uid);
        if ($eventID <= 0) {
            error_log("checkout.php >> eventID not exists !");
            $error = 'eventID not exists';
            throw new Exception($error);
        }

        //echo var_dump($_POST["ID"]);
        if (isset($_POST["ID"]) && is_array($_POST["ID"])) {

            // Execute Transaction
            $data->startTransaction();

            // Delete outdated records
            $ticket_utils->delete_ticket_lock_outdated();

            foreach($_POST["ID"] as $key=>$val) {
                //echo $key.'=>'. $val.  ' - ' . $_POST["TICKET_QTE"][$key] .'<br>';
                $form_ticket = new stdClass();
                $form_ticket->idevent = $eventID;
                $form_ticket->idticket = filter_var($_POST["ID"][$key], FILTER_SANITIZE_NUMBER_INT);
                $ticket_detail = $ticket_utils->getTicket($form_ticket);
                //echo var_dump($form_ticket);
                $ticket_detail['TICKET_QTE'] = (int) $_POST["TICKET_QTE"][$key];

                $ticket_qte = $ticket_detail['TICKET_QTE'];
                $ticket_id = $form_ticket->idticket;

                if ($ticket_qte <= 0) {
                    continue;
                }

                $isQuantityAvailable = false;

                // Calculate Quantity Available
                $quantity_available = $ticket_utils->getTicketOrderQteAvailable($ticket_id);


                //echo exit(var_dump($quantity_available));


                if ($ticket_qte <= $quantity_available) {
                    $isQuantityAvailable = true;
                } else {
                    $isQuantityAvailable = false;
                }

                if ($isQuantityAvailable === true) {
                    // Reservation Qte by ticket type
                    $form_ticket_lock = new stdClass();
                    $form_ticket_lock->QTE = $ticket_qte;
                    $form_ticket_lock->SESSION = $sessionid;
                    $form_ticket_lock->TICKETTYPE = $ticket_id;
                    $bresult = $ticket_utils->create_ticket_lock($form_ticket_lock);

                    if ($bresult === false) {
                        error_log("checkout.php >> failed create ticket lock !");
                        break;
                    }

                    $cart_view [] = $ticket_detail;
                    $total_qte += (int) $_POST["TICKET_QTE"][$key];
                    //echo var_dump($total_qte);
                }
            }
        }

        //echo var_dump($cart_view);
        if (! empty($iduser)) {
            $user_utils = new \Moviao\Data\Util\UsersUtils($commonData);
            $uid = $user_utils->getUserUUID($iduser);
            $user_data = $user_utils->show($uid);
            //echo var_dump($user_data);
        }
    }
} catch (\Error $e) {
    $bresult = false;
    error_log("checkout.php >> " . $e->getMessage());
} catch (\Exception $e) {
    $bresult = false;
    error_log("Caught exception: checkout.php >> " . $e->getMessage());
} finally {

    if ($bresult === true) {
        if (isset($data) && $data !== null) {
            $data->commitTransaction();
        }
    } else {
        $error = '';
        if (isset($data) && null !== $data) {
            $data->rollbackTransaction();
            $error = $data->errorCode();
        }
        error_log('checkout transaction: (false) ' . $error);
    }

    if (isset($data) && null !== $data) {
        $data->disconnect();
    }
}

// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/checkout.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];

//var_dump($params["v"]);

if (isset($params) && isset($params["v"]) && $params["v"] === 'm') {
    $this->layout('tpl::template_empty', $array); // template
} else {
    $this->layout('tpl::template', $array); // template
}

$this->start('scripts');
echo '<link type="text/css" href="/dist/css/parsley/parsley.css" rel="stylesheet">';
echo '<link type="text/css" href="/dist/c/tel/css/intlTelInput.css" rel="stylesheet">';
echo '<script data-main="/dist/ctrl/checkout" src="/dist/js/require.js"></script>';
$this->stop();
echo $this->insert('partials::checkout.tpl', ['info' => $t, 'lang' => $lang, 'dataView' => $dataView, 'total_qte' => $total_qte, 'cart_view' => $cart_view, 'sessionUser' => $sessionUser, 'user_data' => $user_data, 'urllink' => $urllink, 'isQuantityAvailable' => $isQuantityAvailable ]);