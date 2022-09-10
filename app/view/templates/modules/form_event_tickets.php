<?php
declare(strict_types=1);
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/form_event_tickets.json');
//--------------------------------------------
?>
<div id="panel_view_tickets" style="display: <?php echo $display;?>">
<div style="width: <?php echo $width; ?>;">
<h5><?php echo $this->e($t->_e('page_legend'));?></h5>
    <hr/>
<form id="feventtickets" action="<?php echo $action; ?>" method="post" data-parsley-validate>
   

    <div>
        <fieldset>
            <div id="event_tickets_list"></div>
        </fieldset>
    </div>
    
<!--    <div style="display: flex;height: 40px;">-->
<!--        <div style="display: flex;width: 90%;justify-content: flex-start">-->
<!--            --><?php //echo $this->e($t->_e('label_qte'));?><!--&nbsp;<div id="total_qte_tickets">0</div>&nbsp;-->
<!--            <div id="total_price_tickets" style="padding-left: 50px">0.00 €</div>-->
<!--        </div>-->
<!--        <div style="display: flex;width: 80%;justify-content: flex-end">-->
<!--            <button id="btn_checkout_tickets" type="submit" class="btn btn-primary" disabled="true">--><?php //echo $this->e($t->_e('btn_order'));?><!--</button>-->
<!--        </div>-->
<!--    </div>-->


    <div class="form-group row">
        <label class="col">
            <div style="display: flex;width: 100%;justify-content: flex-start">
            <?php echo $this->e($t->_e('label_qte'));?>&nbsp;<div id="total_qte_tickets">0</div>
            </div>
        </label>
        <div class="col">
            <div>
                <div id="total_price_tickets">0.00 €</div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col">
            <button id="btn_checkout_tickets" type="submit" class="btn btn-primary" disabled="true"><?php echo $this->e($t->_e('btn_order'));?></button>
        </div>
    </div>

    <input type="hidden" id="uid" name="UID" value="<?php echo $urllink;?>">
    <input type="hidden" id="datbeg" name="DATBEG"  value="<?php if (isset($datbeg)) echo $datbeg;?>">
</form>
</div>
</div>