<?php
declare(strict_types=1);

ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

//try {
//    $cart = new \Moviao\Cart\Cart(['cartMaxItem' => 0,'itemMaxQuantity' => 99,'useCookie' => false]);
//    if (isset($_POST) && ! empty($_POST) && ! $cart->isEmpty()) {
//
//        require_once "package/Mollie/vendor/autoload.php";
//        $mollie = new \Mollie\Api\MollieApiClient();
//        $mollie->setApiKey("test_b7vWwkWkRsbE5PqK6R8CKuHb5m5G6g");
//        $orderId = time();
//        $protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
//        $hostname = $_SERVER['HTTP_HOST'];
//        $path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);
//
//        /*
//         * Payment parameters:
//         *   amount        Amount in EUROs. This example creates a € 10,- payment.
//         *   description   Description of the payment.
//         *   redirectUrl   Redirect location. The customer will be redirected there after the payment.
//         *   webhookUrl    Webhook location, used to report when the payment changes state.
//         *   metadata      Custom metadata that is stored with the payment.
//         */
//        $payment = $mollie->payments->create([
//            "amount" => [
//                "currency" => "EUR",
//                "value" => "15.00" // You must send the correct number of decimals, thus we enforce the use of strings
//            ],
//            "description" => "Order #{$orderId}",
//            "redirectUrl" => "{$protocol}://{$hostname}{$path}payment/order?order_id={$orderId}",
//            "webhookUrl" => "{$protocol}://{$hostname}{$path}payment/webhook",
//            "metadata" => [
//                "order_id" => $orderId,
//            ],
//        ]);
//
//        /*
//         * In this example we store the order with its payment status in a database.
//         */
//        //database_write($orderId, $payment->status);
//
//        /*
//         * Send the customer off to complete the payment.
//         * This request should always be a GET, thus we enforce 303 http response code
//         */
//        //header("Location: " . $payment->getCheckoutUrl(), true, 303);
//        header( "Refresh:3; url=" . $payment->getCheckoutUrl(), true, 303);
//        echo "Redirect in progress ...";
//    } else {
//        echo "no payment request found !";
//    }
//} catch (\Mollie\Api\Exceptions\ApiException $e) {
//    echo "API call failed: " . htmlspecialchars($e->getMessage());
//}

//if (true) exit();


$sessionUser->startSession();
$sessionid = session_id();

define('DEFAULT_CURRENCY','EUR');

//$form = new \Moviao\Form\FormUtils();
//$form->debugFormPost();
//echo var_dump($_POST);
//$total_price = 0.0;

//echo var_dump($_SESSION);
// Initialize Cart object
$cart = new \Moviao\Cart\Cart(['cartMaxItem' => 0,'itemMaxQuantity' => 99,'useCookie' => false]);
// Redirect if cart empty  (payment stage)
if ($cart->isEmpty()) {
    //header('Location: /');
    //exit(0);
}

//$cart->clear();
//echo 'There are '. $cart->getTotalItem().' items in the cart.' . "<br>";
//foreach ($cart->getItems() as $item) {
//    echo var_dump($item) . "<br>";
//}
//
//exit();

//$form = new \Moviao\Form\FormUtils();
//$form->debugFormPost();
//exit();
$bresult = false;
$payment_processed = true;
$order_id = "";
$isQuantityAvailable = true;
$data = null;
$urllink = null;
$quantity_available = 0;

if (isset($_POST) && ! empty($_POST) && ! $cart->isEmpty()) {

    if (isset($_POST["URLLINK"])) {
        $urllink = filter_var($_POST["URLLINK"], FILTER_SANITIZE_URL);
    }

    $bresult = false;
    $bform_valid = false;

    // Get the form
    $form_ticket_order = new \stdClass();
    $form_ticket_order->FIRSTNAME = isset($_POST['FIRSTNAME']) && is_string($_POST['FIRSTNAME']) ? trim(mb_substr($_POST['FIRSTNAME'],0,250)) : null;
    $form_ticket_order->LASTNAME = isset($_POST['LASTNAME']) && is_string($_POST['LASTNAME']) ? trim(mb_substr($_POST['LASTNAME'],0,250)) : null;
    $form_ticket_order->MAIL = isset($_POST['MAIL']) && is_string($_POST['MAIL']) ? trim(mb_substr($_POST['MAIL'],0,250)) : null;
    $form_ticket_order->MPHONE = isset($_POST['MPHONE']) && is_string($_POST['MPHONE']) ? trim(mb_substr($_POST['MPHONE'],0,50)) : null;

//    $form_ticket_order->CCNAME = isset($_POST['CCNAME']) && is_string($_POST['CCNAME']) ? trim(mb_substr($_POST['CCNAME'],0,250)) : null;
//    $form_ticket_order->CCNUMBER = isset($_POST['CCNUMBER']) && is_numeric($_POST['CCNUMBER']) ?  trim(mb_substr($_POST['CCNUMBER'],0,19)) : null;
//    $form_ticket_order->CCEXPIRATION_MONTH = isset($_POST['CCEXPIRATION-MONTH']) && is_numeric($_POST['CCEXPIRATION-MONTH']) ? trim(mb_substr($_POST['CCEXPIRATION-MONTH'],0,2)) : null;
//    $form_ticket_order->CCEXPIRATION_YEAR = isset($_POST['CCEXPIRATION-YEAR']) && is_numeric($_POST['CCEXPIRATION-YEAR']) ? trim(mb_substr($_POST['CCEXPIRATION-YEAR'],0,4)) : null;
//    $form_ticket_order->CCCVV = isset($_POST['CCCVV']) && is_numeric($_POST['CCCVV']) ? trim(mb_substr($_POST['CCCVV'],0,4)) : null;

    // Verify the form
    if ( ! (isset($sessionUser) && $sessionUser->isValid())) {
        // User Not authenticated
        $bform_valid = ! empty($form_ticket_order->FIRSTNAME);
        if ($bform_valid === true) $bform_valid = ! empty($form_ticket_order->LASTNAME);
        if ($bform_valid === true) $bform_valid = ! empty($form_ticket_order->MAIL);
        //if ($bform_valid === true) $bform_valid = ! empty($form_ticket_order->MPHONE);
    } else {
        $bform_valid = true;
    }

//    if ($bform_valid === true) $bform_valid = ! empty($form_ticket_order->CCNAME);
//    if ($bform_valid === true) $bform_valid = ! empty($form_ticket_order->CCNUMBER);
//    if ($bform_valid === true) $bform_valid = ! empty($form_ticket_order->CCEXPIRATION_MONTH);
//    if ($bform_valid === true) $bform_valid = ! empty($form_ticket_order->CCEXPIRATION_YEAR);
//    if ($bform_valid === true) $bform_valid = ! empty($form_ticket_order->CCCVV);

    //echo var_dump($form_ticket_order);
    //echo var_dump($bform_valid);
    //return;

    try {

    $commonData = new \Moviao\Data\CommonData();
    $commonData->iniDatabase();
    $commonData->setSession($sessionUser);
    $data = $commonData->getDBConn();

    // Execute Transaction
    $data->connectDBA();
    $data->startTransaction();

    $ticket_utils = new \Moviao\Data\Util\TicketsUtils($commonData);

    // ### Check Quantity ###
    $y = 0;
    foreach ($cart->getItems() as $item) {
        $tickettypeId = $item[$y]['id'];
        $quantity_wanted = (int) $item[$y]['quantity'];
        //$quantity_configured = $ticket_utils->getTicketTypeQte($tickettypeId);
        //$quantity_ordered = $ticket_utils->getTicketOrderQteOrdered($tickettypeId);
        //var_dump($item);
        //var_dump($quantity_ordered);
        //$quantity_available = $quantity_configured - $quantity_ordered;

        $quantity_available = $ticket_utils->getTicketOrderQteNoOrdered($tickettypeId);
        if ($quantity_wanted <= $quantity_available) {
            $isQuantityAvailable = true;
        } else {
            $isQuantityAvailable = false;
            break;
        }
        $y++;
    }

//    echo $isQuantityAvailable;
//    if (true) {
//        var_dump($quantity_available);
//        exit();
//    }

    // ### Process Payment ###
    if ($bform_valid === true && $isQuantityAvailable === true && ! $cart->isEmpty()) {

        $form_ticket_order->TICORDER_STATUS = 'PROCESSING_PROGRESS';
        $form_ticket_order->USR = $commonData->getSession()->getIDUSER();

        if ($form_ticket_order->USR === 0) {
            $form_ticket_order->USR = null;
        }

        // Create Order
        $ticket_order_result = $ticket_utils->create_order($form_ticket_order);
        //echo 'result : ' . var_dump($form_ticket_order);

        if ($ticket_order_result->result === false) {
            error_log('create order error : ' . var_export($form_ticket_order, true));
        } else {
            $order_id = $ticket_order_result->lastid;
        }

        if ($ticket_order_result->result === true) {

            //$bresult = true;
            //echo var_dump($cart->getItems());

            $x = 0;
            foreach ($cart->getItems() as $item) {

                // Create Ticket Item
                $form_ticket_order_item = new \stdClass();
                $form_ticket_order_item->TICORDER = $ticket_order_result->lastid;
                $form_ticket_order_item->TICKETTYPE = $item[$x]['id'];
                $form_ticket_order_item->ITEM_QTE = $item[$x]['quantity'];
                $form_ticket_order_item->ITEM_PRICE = isset($item[$x]['attributes']['price']) ? $item[$x]['attributes']['price'] : 0.0;
                $form_ticket_order_item->ITEM_PRICEHT = isset($item[$x]['attributes']['price_ht']) ? $item[$x]['attributes']['price_ht'] : 0.0;
                $form_ticket_order_item->ITEM_TVA = isset($item[$x]['attributes']['tva']) ? $item[$x]['attributes']['tva'] : 0.0;
                $form_ticket_order_item->CURRENCY_ID = isset($item[$x]['attributes']['currency']) ? $item[$x]['attributes']['currency'] : DEFAULT_CURRENCY;
                $form_ticket_order_item->ITEM_SERVICEFEE = isset($item[$x]['attributes']['servicefee']) ? $item[$x]['attributes']['servicefee'] : 0.0;
                $form_ticket_order_item->ITEM_PROCESSINGFEE = isset($item[$x]['attributes']['processingfee']) ? $item[$x]['attributes']['processingfee'] : 0.0;
                $form_ticket_order_item->DATBEG = isset($item[$x]['attributes']['datbeg']) ? $item[$x]['attributes']['datbeg'] : null;
                $form_ticket_order_item->DATEND = isset($item[$x]['attributes']['datend']) ? $item[$x]['attributes']['datend'] : null;
                $form_ticket_order_item->ZONEIDBEG = isset($item[$x]['attributes']['zoneidbeg']) ? $item[$x]['attributes']['zoneidbeg'] : null;
                $form_ticket_order_item->ZONEIDEND = isset($item[$x]['attributes']['zoneidend']) ? $item[$x]['attributes']['zoneidend'] : null;

                $bresult = $ticket_utils->create_order_item($form_ticket_order_item);

                if ($bresult === false) {
                    error_log('create order item error : ticket id = ' . $item[$x]['id']);
                    break;
                }

                // Create Ticket Detail
                $quantity = (int) $item[$x]['quantity'];
                //echo $quantity;

                for ($i=0;$i < $quantity; $i++) {

                    $form_ticket_order_detail = new \stdClass();
                    $form_ticket_order_detail->TICORDER = $ticket_order_result->lastid;
                    $form_ticket_order_detail->TICKETTYPE = $item[$x]['id'];
                    $form_ticket_order_detail->TICKETDET = ($i+1);
                    $form_ticket_order_detail->LOCKED = '1';

                    while(true) {
                        $a = random_int(3453, PHP_INT_MAX);
                        $b = random_int(94838445, PHP_INT_MAX);
                        $c = random_int(453459349, PHP_INT_MAX);
                        $code = substr($a . $b . $c, 0, 13);
                        $form_ticket_order_detail->CODE = $code;

                        $isCodeExist = $ticket_utils->isOrderDetailExist($code);
                        if (! $isCodeExist) {
                            break;
                        }
                    }

                    // Populate with the first buyer
                    if ($i == 0) {
                        $form_ticket_order_detail->FNAME = $form_ticket_order->FIRSTNAME;
                        $form_ticket_order_detail->LNAME = $form_ticket_order->LASTNAME;
                        $form_ticket_order_detail->EMAIL = $form_ticket_order->MAIL;
                    }

                    $bresult = $ticket_utils->create_order_detail($form_ticket_order_detail);

                    if ($bresult === false) {
                        error_log('create order detail error : ticket id = ' . $item[$x]['id'] . ' - i = ' . $i);
                        break;
                    }
                }

                // Delete all locks from user session and ticket type
                if ($bresult === true) {
                    $form_ticket_lock = new stdClass();
                    $form_ticket_lock->SESSION = $sessionid;
                    $form_ticket_lock->TICKETTYPE = $item[$x]['id'];
                    $ticket_utils->delete_ticket_lock($form_ticket_lock);
                }

                $x++;
            }
        }

    }


} catch (\Error $err) {
    $bresult = false;
    error_log('payment: (error) ' . $err->getMessage());
} catch (\Exception $e) {
    $bresult = false;
    error_log('payment: (exception) ' . $e->getMessage());
} finally {

    if ($bresult === true) {

        if (! empty($data)) {
            $data->commitTransaction();
        }

        if (! empty($cart)) {
            $cart->destroy();
        }

    } else {

        if (! empty($data)) {
            $data->rollbackTransaction();
        }

        error_log('payment: (false) ' . $data->errorCode());
    }

    if (! empty($data)) {
        $data->disconnect();
    }
}

}
?>
<div class="jumbotron jumbotron-fluid">
    <div class="container">

        <?php if ($payment_processed === true) {   ?>

            <div class="jumbotron">
                <h1 class="display-5"><?=$this->e($info->_e('transaction_status'));?></h1>
                <p class="lead text-success"><?=$this->e($info->_e('transaction_success'));?>&nbsp;<img style="margin-top: -10px" src="/img/icons/checked.svg" width="30px" height="30px" border="0"></p>
                <hr class="my-4">
                <p><?=$this->e($info->_e('transaction_message'));?></p>

                <?php if ($sessionUser->isValid()) { ?>
                <a class="btn btn-primary btn-lg" href="/profile/?a=tickets" role="button"><?=$this->e($info->_e('btn_continue'));?></a>
                <?php } else { ?>
                <a class="btn btn-primary btn-lg" href="/home" role="button"><?=$this->e($info->_e('btn_continue'));?></a>
                <?php } ?>

            </div>

        <?php } else if ($isQuantityAvailable === false) { ?>

            <div class="jumbotron">
                <h1 class="display-5"><?=$this->e($info->_e('transaction_status'));?></h1>
                <p class="lead text-danger"><?=$this->e($info->_e('transaction_failed_quantity'));?>&nbsp;<?php echo $quantity_available; ?></p>
                <hr class="my-4">
                <p>&nbsp;</p>
                <a class="btn btn-primary btn-lg" href="/event/<?php echo $urllink; ?>" role="button"><?=$this->e($info->_e('btn_back'));?></a>
            </div>

        <?php } else { ?>

            <div class="jumbotron">
                <h1 class="display-5"><?=$this->e($info->_e('transaction_status'));?></h1>
                <p class="lead text-danger"><?=$this->e($info->_e('transaction_failed_paymentrefused'));?></p>
                <hr class="my-4">
                <p>&nbsp;</p>
                <a class="btn btn-primary btn-lg" href="/event/<?php echo $urllink; ?>" role="button"><?=$this->e($info->_e('btn_back'));?></a>
            </div>

        <?php } ?>

    </div>
    </div>
</div>