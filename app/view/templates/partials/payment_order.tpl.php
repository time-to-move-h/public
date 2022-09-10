<?php
declare(strict_types=1);

$sessionUser->startSession();
$sessionid = session_id();

$bresult = false;
$payment_processed = false;
$order_id = "";

if (isset($params["order_id"])) {
    $order_id = $params["order_id"];
    $payment_processed = true;
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

<!--        --><?php //} else if ($isQuantityAvailable === false) { ?>
<!---->
<!--            <div class="jumbotron">-->
<!--                <h1 class="display-5">--><?//=$this->e($info->_e('transaction_status'));?><!--</h1>-->
<!--                <p class="lead text-danger">--><?//=$this->e($info->_e('transaction_failed_quantity'));?><!--&nbsp;--><?php //echo $quantity_available; ?><!--</p>-->
<!--                <hr class="my-4">-->
<!--                <p>&nbsp;</p>-->
<!--                <a class="btn btn-primary btn-lg" href="/event/--><?php //echo $urllink; ?><!--" role="button">--><?//=$this->e($info->_e('btn_back'));?><!--</a>-->
<!--            </div>-->

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