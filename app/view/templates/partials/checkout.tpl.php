<?php
declare(strict_types=1);
//$form = new \Moviao\Form\FormUtils();
//$form->debugFormPost();
//echo var_dump($_POST);
$service_fee = 0.99;
$processing_fee = 0.79;

$total_price = 0.0;
$now = \Moviao\Util\DateTimeFormat::currentDateTime();
//echo var_dump($_SESSION);
// Initialize Cart object
$cart = new \Moviao\Cart\Cart(['cartMaxItem' => 0,'itemMaxQuantity' => 99,'useCookie' => false ]);  // 'timeout' => $now
$cart->clear();
if (! $cart->isTimeoutDefined()) {
    $cart->setTimeout($now);
}
$timeout_timestamp = $cart->getTimeout();
$differenceInSeconds = $now->getTimestamp() - $timeout_timestamp->getTimestamp();
//echo var_dump($differenceInSeconds);

if ($differenceInSeconds < 0) {
    $differenceInSeconds = 0;
}

$countdown = 600;
$minutes_remaining = $countdown - $differenceInSeconds;
if ($minutes_remaining <= 0) {
    $cart->destroy();
    $minutes_remaining = $countdown;
    //header('Location: /results');
    //exit();
}
//echo var_dump($user_data);
//echo $isQuantityAvailable;

?>
<div class="container">

<?php if ($isQuantityAvailable) {  ?>

    <div class="row" style="width: 95%">
    <div class="col-xs-12 padding-box">

    <p class="">
        <div class="py-5 text-center break-word">
            <h2><?php
                if (! empty($dataView['TITLE'])) {
                    echo strip_tags($dataView['TITLE']);
                }
                ?>
            </h2>
        </div>

    <div style="width: 100%;"><?php echo strip_tags($dataView['DATFORMATTED']); ?></div>

        <p>
        <div class="card">
            <div class="card-body">
                <input type="hidden" id="timeout" value="<?php echo $minutes_remaining; ?>">
                <input type="hidden" id="timeout_msg" value="<?=$this->e($info->_e('registration_timeout'));?>">
                <div><h3><span id="time"><?php echo gmdate('i:s', $minutes_remaining); ?></span></h3></div>
                <div><?=$this->e($info->_e('registration_complete'));?></div>
            </div>
        </div>
        </p>

        <div class="row">

            <div class="col-md-12 order-md-1 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted"><?=$this->e($info->_e('form_yourcart'));?></span>
                    <span class="badge badge-secondary badge-pill"><?php echo $total_qte; ?></span>
                </h4>
                <ul class="list-group mb-3">
                    <?php
                        foreach ($cart_view as $value) {

                            $ticket_qte = (int) $value['TICKET_QTE'];

                            if ($ticket_qte <= 0) {
                                continue;
                            }

                            $price_ttc = (float) $value['PRICE'];
                            $price_ht = (float) $value['PRICEHT'];
                            $tva = $value['TVA'];
                            $currency = $value['CURRENCY'];
                            $total_price += ((float) $value['PRICE']) * $ticket_qte;
                            $ticket_id  = (int) $value['ID'];

                            $service_fee *= $ticket_qte;
                            $processing_fee *= $ticket_qte;

                            $cart->add((string) $ticket_id, $ticket_qte, ['price' => (string) $price_ttc, 'price_ht' => (string) $price_ht, 'tva' => (string) $tva, 'currency' => $currency, 'servicefee' => $service_fee, 'processingfee' => $processing_fee ]);
                    ?>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div class="break-word">
                            <h6 class="my-0"><?php if (isset($value['TICKET_QTE'])) echo $value['TICKET_QTE']; ?>&nbsp;x&nbsp;<?php if (! empty($value['NAME'])) echo strip_tags($value['NAME']); ?></h6>
                            <small class="text-muted "><?php if (! empty($value['DESCL'])) echo strip_tags($value['DESCL']); ?></small>
                        </div>
                        <span class="text-muted"><?php if (! empty($value['PRICE'])) echo strip_tags($value['PRICE']); ?>&nbsp;€</span>
                    </li>
                    <?php } ?>

                    <!-- Service Fee -->

                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div class="break-word">
                            <h6 class="my-0"><?=$this->e($info->_e('form_servicefee'));?></h6>
                            <small class="text-muted "></small>
                        </div>
                        <span class="text-muted"><?php echo $service_fee; ?> €</span>
                    </li>

                    <!-- Processing Fee -->

                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div class="break-word">
                            <h6 class="my-0"><?=$this->e($info->_e('form_processingfee'));?></h6>
                            <small class="text-muted "></small>
                        </div>
                        <span class="text-muted"><?php echo $processing_fee; ?> €</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <span><?=$this->e($info->_e('form_total'));?> (EUR)</span>
                        <strong><?php

                            $total_price += $service_fee + $processing_fee;
                            echo number_format($total_price, 2, '.', '');

                            ?>&nbsp;€</strong>
                    </li>
                </ul>
            </div>

            <div class="col-md-8 order-md-2">
                <form id="fcheckouttickets" class="needs-validation" action="/payment" method="post" data-parsley-validate>
                    <h4 class="mb-3"><?=$this->e($info->_e('form_buyerticket'));?></h4>
                    <?php //if ($sessionUser->isValid()) {  ?>
<!--                    <div class="mb-3">-->
<!--                        <label for="firstName">--><?php //echo $user_data[0]['FNAME'] . ' ' . $user_data[0]['LNAME']; ?><!--</label>-->
<!--                    </div>-->
                    <?php //} //else { ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName"><?=$this->e($info->_e('form_field_firstname'));?></label>
                            <input class="form-control" id="firstName" name="FIRSTNAME" placeholder="" value="<?php if ($sessionUser->isValid()) { echo strip_tags($user_data[0]['FNAME']); } ?>" maxlength="250" required="" type="text">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName"><?=$this->e($info->_e('form_field_lastname'));?></label>
                            <input class="form-control" id="lastName" name="LASTNAME" placeholder="" value="<?php if ($sessionUser->isValid()) { echo strip_tags($user_data[0]['LNAME']); } ?>" maxlength="250" required="" type="text">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email"><?=$this->e($info->_e('form_field_email'));?></label>
                        <input class="form-control" id="email" name="MAIL" placeholder="<?=$this->e($info->_e('form_field_email_placeholder'));?>" value="<?php if ($sessionUser->isValid()) { echo strip_tags($user_data[0]['EMAIL']); } ?>" maxlength="250" type="email" required="">
                    </div>

<!--                    <div class="mb-3">-->
<!--                        <label for="email">--><?//=$this->e($info->_e('form_field_emailconfirm'));?><!--</label>-->
<!--                        <input class="form-control" id="email" placeholder="--><?//=$this->e($info->_e('form_field_email_placeholder'));?><!--" maxlength="250" type="email" required="">-->
<!--                    </div>-->

                    <div class="mb-3">
                        <label for="mphone" style="width: 100%;"><?=$this->e($info->_e('form_field_mphone'));?></label>
                        <input id="mphone" maxlength="50" class="form-control" autocomplete="off" type="tel" value="<?php if ($sessionUser->isValid() && ! empty($user_data[0]['MPHONE'])) { echo strip_tags($user_data[0]['MPHONE']); } ?>">
                        <input id="mphone_result" type="hidden" name="MPHONE" value="">
                    </div>

                    <?php //} ?>




<!--                    <hr class="mb-4">-->
<!--                    <h4 class="mb-3">--><?//=$this->e($info->_e('form_payment'));?><!--</h4>-->


<!--                    <div class="d-block my-3">-->
<!--                        <div class="custom-control custom-radio">-->
<!--                            <input id="credit" name="paymentMethod" class="custom-control-input" checked="" value="1" required="" type="radio">-->
<!--                            <label class="custom-control-label" for="credit">--><?//=$this->e($info->_e('form_field_credit'));?><!--</label>-->
<!--                        </div>-->
<!--                        <div class="custom-control custom-radio">-->
<!--                            <input id="debit" name="paymentMethod" class="custom-control-input" value="2" required="" type="radio">-->
<!--                            <label class="custom-control-label" for="debit">--><?//=$this->e($info->_e('form_field_debit'));?><!--</label>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="row">-->
<!--                        <div class="col-md-6 mb-3">-->
<!--                            <label for="cc-name">--><?//=$this->e($info->_e('form_field_ccname'));?><!--</label>-->
<!--                            <input class="form-control" id="cc-name" name="CCNAME" placeholder="" required="" type="text" value="">-->
<!--                            <small class="text-muted">--><?//=$this->e($info->_e('form_field_ccname_placeholder'));?><!--</small>-->
<!--                        </div>-->
<!--                        <div class="col-md-6 mb-3">-->
<!--                            <label for="cc-number">--><?//=$this->e($info->_e('form_field_ccnumber'));?><!--</label>-->
<!--                            <input class="form-control" id="cc-number" name="CCNUMBER" pattern="\d*" maxlength="19" placeholder="" required="" type="text" value="">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="row">-->
<!--                        <div class="col-md-3 mb-3">-->
<!--                            <label for="cc-expiration">--><?//=$this->e($info->_e('form_field_expiration_month'));?><!--</label>-->
<!--                            <select class="custom-select" id="cc-expiration-month" name="CCEXPIRATION-MONTH" required="">-->
<!--                                    <option value="" selected>--><?//=$this->e($info->_e('form_field_expiration_month'));?><!--</option>-->
<!--                                    <option value="01">Jan (01)</option>-->
<!--                                    <option value="02">Feb (02)</option>-->
<!--                                    <option value="03">Mar (03)</option>-->
<!--                                    <option value="04">Apr (04)</option>-->
<!--                                    <option value="05">May (05)</option>-->
<!--                                    <option value="06">June (06)</option>-->
<!--                                    <option value="07">July (07)</option>-->
<!--                                    <option value="08">Aug (08)</option>-->
<!--                                    <option value="09">Sep (09)</option>-->
<!--                                    <option value="10">Oct (10)</option>-->
<!--                                    <option value="11">Nov (11)</option>-->
<!--                                    <option value="12">Dec (12)</option>-->
<!--                            </select>-->
<!--                        </div>-->
<!---->
<!--                        <div class="col-md-3 mb-3">-->
<!--                            <label for="cc-expiration">--><?//=$this->e($info->_e('form_field_expiration_year'));?><!--</label>-->
<!--                            <select class="form-control" id="cc-expiration-year" name="CCEXPIRATION-YEAR" required="">-->
<!--                                <option value="">--><?//=$this->e($info->_e('form_field_expiration_year'));?><!--</option>-->
<!--                                <option value="20">2020</option>-->
<!--                                <option value="21">2021</option>-->
<!--                                <option value="22">2022</option>-->
<!--                                <option value="23">2023</option>-->
<!--                                <option value="23">2024</option>-->
<!--                                <option value="23">2025</option>-->
<!--                                <option value="23">2026</option>-->
<!--                                <option value="23">2027</option>-->
<!--                                <option value="23">2028</option>-->
<!--                                <option value="23">2029</option>-->
<!--                                <option value="23">2030</option>-->
<!--                            </select>-->
<!--                        </div>-->
<!---->
<!--                        <div class="col-md-3 mb-3">-->
<!--                            <label for="cc-expiration">--><?//=$this->e($info->_e('form_field_cvv'));?><!--</label>-->
<!--                            <input class="form-control" id="cc-cvv" name="CCCVV" pattern="\d*" maxlength="4" placeholder="" required="" type="text" value="">-->
<!--                        </div>-->
<!--                    </div>-->









                    <input type="hidden" id="urllink" name="URLLINK" value="<?php echo strip_tags($urllink); ?>">

                    <hr class="mb-4">
                    <button class="btn btn-primary btn-lg btn-block" type="submit"  <?php if ($total_qte === 0) echo 'disabled'; ?>><?=$this->e($info->_e('form_submit'));?></button>

                    <button id="btn_cancel" class="btn btn-secondary btn-sm btn-block" type="button"><?=$this->e($info->_e('form_cancel'));?></button>

                    <br/>
                </form>

                <br/><br/><br/>

            </div>
        </div>

    </div></div>

    <?php } else { ?>

    <div class="text-center" style="margin-top: 30%">

        <div id="error_unknown" class="card card-error text-center">
            <div class="card-body" style="color: #FF0000;">
                <?=$this->e($info->_e('error_soldout'));?>
            </div>
        </div>

        <div style="margin-top: 20px">
            <p class="lead">
                <a href="/home" class="btn btn-primary" role="button"><?=$this->e($info->_e('button_continue'));?></a>
            </p>
        </div>

    </div>

    <?php } ?>

</div></div>