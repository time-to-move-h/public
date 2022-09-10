<?php
declare(strict_types=1);
$total_unlocked = 0;
//$sessionUser->startSession();
//$ticket_order_id = $orderid;

if (! empty($ticket_order_id)) {

    $bform_valid = true;
    // Get the form
    //$ticket_order_id = isset($_GET['orderid']) && is_numeric($_GET['orderid']) ? $_GET['orderid'] : '-1';
    //echo $ticket_order_id;

    // Verify the form
//    if ( ! (isset($sessionUser) && $sessionUser->isValid())) {
//        // User Not authenticated
//        $bform_valid = !empty($form_ticket_order->FIRSTNAME);
//        if ($bform_valid === true) $bform_valid = !empty($form_ticket_order->LASTNAME);
//        if ($bform_valid === true) $bform_valid = !empty($form_ticket_order->MAIL);
//        //if ($bform_valid === true) $bform_valid = ! empty($form_ticket_order->MPHONE);
//    } else {
//        $bform_valid = true;
//    }
//    if ($bform_valid === true) $bform_valid = ! empty($form_ticket_order->CCNAME);

    //echo var_dump($form_ticket_order);
    //echo var_dump($bform_valid);

    if ($bform_valid === true) {

        try {
            $commonData = new \Moviao\Data\CommonData();
            $commonData->iniDatabase();
            $commonData->setSession($sessionUser);
            $data = $commonData->getDBConn();
            // Execute Transaction
            $data->connectDBA();

//            $form_ticket_order->USR = $commonData->getSession()->getIDUSER();
//            if ($form_ticket_order->USR === 0) {
//                $form_ticket_order->USR = null;
//            }

            $ticket_order = new \Moviao\Data\Util\TicketsUtils($commonData);
            $order_data = $ticket_order->getOrderDetails($ticket_order_id);

            //echo var_dump($order_data['DETAILS']);
            $form = new \stdClass();
            $form->iduser = $commonData->getSession()->getIDUSER();
            $ticket_order->getMyTickets($form);
            //echo var_dump($ticket_order->getMyTickets($form)[1]);

        } catch (\Error $e) {
            error_log('ticket_order: (error) ' . $e->getMessage());
        } catch (\Exception $e) {
            error_log('ticket_order: (exception) ' . $e->getMessage());
        } finally {
            if (isset($data) && null !== $data) {
                $data->disconnect();
            }
        }

    } else {
        echo "invalid";
    }
}

$order_status = "N/A";
$status = htmlspecialchars($order_data['STATUS']);

switch ($status) {
    case "PAYMENT_ACCEPTED":
        $order_status = "Completed";
        break;
    case "PROCESSING_PROGRESS":
        $order_status = "Awaiting Payment";
        break;
    case "CANCELED":
        $order_status = "Cancelled";
        break;
    case "PAYMENT_ERROR":
        $order_status = "Payment Error";
        break;
    case "REFUNDED":
        $order_status = "Refunded";
        break;
}
?>
<div class="w-100">
    <div class="container">
        <div style="padding-top: 25px; padding-bottom: 10px;"><h2><?php echo strip_tags($order_data['TITLE']); ?></h2></div>

        <div style="padding-bottom: 5px;"><?php echo $order_data['DATFORMATTED']; ?></div>

        <div style="padding-bottom: 15px;">Status : <?php echo $order_status; ?></div>



        <form id="fticket_details" class="needs-validation" data-parsley-validate>
            <input type="hidden" name="TICORDER" value="<?php echo $ticket_order_id; ?>">
            <?php
                $rows = $order_data['DETAILS'];
                //echo var_dump($order_data['STATUS']);

                if ($rows != null && $status === 'PAYMENT_ACCEPTED') {

                    $i = 0;
                    
                    foreach ($rows as $obj) {
                        $i++;

                        echo '<div style="margin-left:15px"><a href="/order/ticket?orderid=' . $ticket_order_id . '&ticketid=' . $obj['TICKETDET'] . '" target="_blank"><strong>Ticket No ' . $i . '</strong><br/></a></div>';

                        if ($obj['LOCKED'] === '0') {

                            $input_detail = '<input type="hidden" name="TICKETDET[]" value="' . filter_var($obj['TICKETDET'], FILTER_SANITIZE_NUMBER_INT) . '">';
                            $input_ttype = '<input type="hidden" name="TICKETTYPE[]" value="' . filter_var($obj['TICKETTYPE'], FILTER_SANITIZE_NUMBER_INT) . '">';
                            $input_fname = '<input type="text" name="FNAME[]" class="form-control" value="">';
                            $input_lname = '<input type="text" name="LNAME[]" class="form-control" value="">';
                            $input_email = '<input type="text" name="EMAIL[]" class="form-control" value="">';

                            $img_view = '<img class="qrcode" src="/util/qrcode?s=qrl&amp;d=' . filter_var($obj['TICDET_CODE'], FILTER_SANITIZE_NUMBER_INT) . '" data-token="' . filter_var($obj['TICDET_CODE'], FILTER_SANITIZE_NUMBER_INT) . '">';

                            echo '<div class="row">';

                                echo '<div class="col-sm-2">';
                                    if ($status === 'PAYMENT_ACCEPTED') {
                                        echo $img_view . '<br>';
                                    }

                                echo '</div>';

                                echo '<div class="col-sm-8" style="margin-top: 9px">';

                                    echo $input_detail;
                                    echo $input_ttype;
                                    echo $info->_e('form_fname') . ' : ' . $input_fname . '<br>';
                                    echo $info->_e('form_lname') . ' : ' . $input_lname . '<br>';
                                    echo $info->_e('form_email') . ' : ' . $input_email . '<br>';
                                    //echo $img_view . '<br>';

                                echo '</div>';

                                echo '<div class="col-sm-12">';
                                    echo '<hr>';
                                echo '</div>';

                            echo '</div>';

                            $total_unlocked++;

                        } else if ($obj['LOCKED'] === '1') {

                            $label_fname =  htmlspecialchars(strip_tags($obj['FNAME']));
                            $label_lname =  htmlspecialchars(strip_tags($obj['LNAME']));
                            $label_email = filter_var($obj['EMAIL'], FILTER_SANITIZE_EMAIL);
                            $codeid = filter_var($obj['CODE'], FILTER_SANITIZE_NUMBER_INT);

                            if (is_null($label_fname)) {
                                $label_fname = "";
                            }

                            if (is_null($label_lname)) {
                                $label_lname = "";
                            }

                            if (is_null($label_email)) {
                                $label_email = "";
                            }

                            $img_view = '<img class="qrcode" src="/util/qrcode?s=qrl&amp;d=' . $codeid . '" data-token="' . $codeid . '">';

                            echo '<div class="row">';

                                echo '<div class="col-sm-2">';
                                    if ($status === 'PAYMENT_ACCEPTED') {
                                        echo $img_view . '<br>';
                                    }
                                echo '</div>';

                                echo '<div class="col-sm-8" style="margin-top: 9px">';

                                    if (! empty($label_fname) ||  ! empty($label_lname)) {
                                        echo '<strong>' . $info->__('form_fullname') . ' : </strong>' . htmlspecialchars(strip_tags($label_fname)) . ' ' . htmlspecialchars(strip_tags($label_lname)) . '<br>';
                                    }

                                    if (! empty($label_email)) {
                                        echo '<strong>' . $info->__('form_email') . ' : </strong>' . htmlspecialchars(strip_tags($label_email)) . '<br>';
                                    }

                                echo '</div>';

                                echo '<div class="col-sm-12">';
                                    echo '<hr>';
                                echo '</div>';

                            echo '</div>';
                        }
                        //echo '<hr>';
                    }
                }

                // <?=$this->e($info->_e('page_subtitle'));
                unset($order_data);
            ?>

            <?php if ($total_unlocked > 0) { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <button id="btn_ticket_details" type="submit" class="btn btn-lg btn-primary btn-block"><?=$this->e($info->_e('form_btn_save'));?></button>
                    </div>
                    <div class="form-group">
                        <a href="/profile/?a=tickets" id="bcancel" class="btn btn-secondary btn-block text-white">
                            <?=$this->e($info->_e('form_btn_cancel'));?>
                        </a>
                    </div>
                </div>
            </div>
            <?php } ?>

        </form>
    </div>
</div>