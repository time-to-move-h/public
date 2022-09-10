<?php
$authenticated = isset($sessionUser) && $sessionUser->isValid();
//$admin_role = ($authenticated === true && $sessionUser->getIDUSER() === $dataView['USR']);
?>
<div class="container">
<div class="row">
<div class="mx-auto w-100 createevent-box padding-box">

<?php if ($isBusinessConfirmed) {  ?>

<form id="formEvent" class="step-form" data-parsley-validate>  
<!--Step 1-->
<div class="form-section current">
    <fieldset>
    <legend><?=$this->e($info->_e('page_subtitle'));?></legend>
    <?php if ($sessionUser->getAccountType() === 99) { ?>
    <div class="form-group">
        <label for="evttype">Event Type (Admin Only)</label>
        <select class="form-control" id="evttype" name="EVTTYP">
            <option value="2">Template</option>
            <option value="1">Normal</option>
        </select>
    </div>
    <?php } ?>
    <div class="form-group">
        <label for="title"><?=$this->e($info->_e('form_field_title'));?>  </label>
        <input id="title" name="TITLE" class="form-control" placeholder="<?=$this->e($info->_e('form_field_title_placeholder'));?>" minlength="3" maxlength="150" type="text" required="">
    </div>
    <div class="form-group">
        <label for="EVTDESC"><?=$this->e($info->_e('form_field_descl'));?></label> 
        <div style="background-color: #ffffff">
            <div id="EVTDESC" name="DESCL" style="height: 150px" ></div>
        </div>
    </div>
    
    <!-- <div class="form-group">
        <label for="url"><?=$this->e($info->_e('form_field_url'));?></label>
        <input id="url" name="URL" class="form-control" placeholder="<?=$this->e($info->_e('form_field_url_placeholder'));?>" maxlength="150" type="text">
    </div> -->

    </fieldset>
</div>        


<!--Step 2-->
<!--<div class="form-section">-->
<!--    <fieldset>-->
<!--    <legend>--><?//=$this->e($info->_e('page_legend_location'));?><!--</legend>    -->
<!--    --><?php //$this->insert('modules::form_places_map',['info' => $info]); ?>
<!--    <br/>-->
<!--    --><?php //$this->insert('modules::form_places',['info' => $info,'lang' => $lang]); ?>
<!--    </fieldset>           -->
<!--</div>-->

<div class="form-section">
    <fieldset>

        <legend><?=$this->e($info->_e('page_legend_location'));?></legend>

        <div class="form-check">
            <input id="isonline" name="ISONLINE" type="radio" value="1" class="form-check-input" required checked>
            <label for="isonline" class="form-check-label">Online Event</label>
        </div>

        <!-- <div class="form-check">
            <input id="isvenue" name="ISONLINE" type="radio" value="0" class="form-check-input" disabled>
            <label for="isvenue" class="form-check-label">Venue (Soon)</label>
        </div> -->

        <br/><br/>

    </fieldset>
</div>



<!--Step 3-->  
<div class="form-section">
    <fieldset>
    <legend><?=$this->e($info->_e('page_legend_datetime'));?></legend>        
    <div class="form-group form-inline">
        <label for="allday" class="form-check-label">
        <input id="allday" name="ALLDAY" type="checkbox" class="form-check-input" value="1">
        <?=$this->e($info->_e('form_field_allday'));?>               
        </label>
    </div>
    <div class="form-group">
        <label for="start_date"><?=$this->e($info->_e('form_field_datbeg'));?></label>
        <input id="start_date_formatted" type="hidden" name="DATBEG">
        <input id="start_date" type="text" placeholder="<?=$this->e($info->_e('form_field_datbeg_placeholder'));?>" class="form-control" readonly required>
    </div>
    <div class="form-group" style="display: none">
        <label for="timezonebeg"><?=$this->e($info->_e('form_field_zoneidbeg'));?></label>
        <select class="form-control" id="timezonebeg" name="ZONEIDBEG_ID">
        <option value="52" selected>Europe/Amsterdam</option>
        </select>                            
    </div> 
    <div id="datendwrapper" class="form-group">
        <label for="end_date"><?=$this->e($info->_e('form_field_datend'));?></label>
        <input id="end_date_formatted" type="hidden" name="DATEND">
        <input id="end_date" type="text" placeholder="<?=$this->e($info->_e('form_field_datend_placeholder'));?>" class="form-control" readonly>
    </div>
    <div class="form-group" style="display: none">
        <label for="timezoneend"><?=$this->e($info->_e('form_field_zoneidend'));?></label>
        <select class="form-control" id="timezoneend" name="ZONEIDEND_ID">
        <option value="52" selected>Europe/Amsterdam</option>
        </select>                            
    </div> 
    </fieldset>
</div>
<!--Step 4-->
<div class="form-section">
<fieldset>
<legend><?=$this->e($info->_e('page_legend_params'));?></legend>
<div class="form-group">
    <label for="channels"><?=$this->e($info->_e('form_field_channel'));?></label>
    <select class="form-control" id="channels" name="CHANNEL" required>
        <option value="" selected="selected"><?=$this->e($info->_e('form_field_channel_select_placeholder'));?></option>
    </select>
    <input type="hidden" id="selchannel" value="<?php if (isset($params["c"])) echo htmlentities(strip_tags($params["c"])); ?>">
</div>
<div class="form-group">
    <label for="tags"><?=$this->e($info->_e('form_field_tags'));?></label>
    <select class="form-control" id="tags" name="TAGS[]" required="">
        <option value="" selected="selected"><?=$this->e($info->_e('form_field_tags_select_placeholder'));?></option>
    </select>
</div>
<div class="form-group">
    <div id="error_unknown" class="card card-error" style="visibility: hidden;display: none">
        <div class="card-body" style="color: #FF0000;">
            <?=$this->e($info->_e('error_unknown'));?>
        </div>
    </div>
</div>








<div class="form-group">
    <label for="maxuse"><?=$this->e($info->_e('form_field_maxusers'));?></label>
    <input id="maxuse" type="number" name="MAXUSE" step="5" value="20" max="1000" required>
</div>



</fieldset>
</div>
<!--Step 5--> 
<!--<div class="form-section">-->
<!--    <fieldset>-->
<!--    <legend>--><?//=$this->e($info->_e('form_field_tags'));?><!--</legend>-->
<!--    </fieldset>-->
<!--</div>-->

<div class="form-navigation" style="padding-left: 15px;">
    <?php
    $csrf = new \Moviao\Security\CSRF_Protect();
    echo $csrf->echoInputField();
    ?>
    <button type="button" class="previous btn btn-primary" style="display: none">Back</button>
    <button type="button" class="next btn btn-primary pull-right" style="display: none">Next</button>
    <button id="btnPublish" type="submit" class="btn btn-primary pull-right" style="display: none"><?=$this->e($info->_e('form_btn_publish'));?></button>
    <span class="clearfix"></span>
</div>

</form>


<?php


} else {
    //var_dump($isBusinessConfirmed);
    //echo 'Wait For Validation ...';
?>

<form id="formEventBusiness" class="step-form" data-parsley-validate>
<fieldset>
<legend><?=$this->e($info->_e('page_subtitle_business'));?></legend>

    <div class="form-group">
        <label for="vat" class=""><?=$this->e($info->_e('form_field_vat'));?></label>
        <input type="text" id="vat" name="VAT" class="form-control" minlength="9" maxlength="15" required="">
    </div>

    <div class="form-group">
        <label for="company_name" class=""><?=$this->e($info->_e('form_field_company_name'));?></label>
        <input type="text" id="company_name" name="COMPANY_NAME" class="form-control" minlength="1" maxlength="200" required="">
    </div>


    <div class="form-group">
        <div id="error_vat_notfound" class="card card-error" style="visibility: hidden;display: none">
            <div class="card-body" style="color: #FF0000;">
                <?=$this->e($info->_e('error_vat_notfound'));?>
            </div>
        </div>
    </div>


    <div class="form-group">
        <div id="error_unknown" class="card card-error" style="visibility: hidden;display: none">
            <div class="card-body" style="color: #FF0000;">
                <?=$this->e($info->_e('error_unknown'));?>
            </div>
        </div>
    </div>

    <button id="btnSave" type="submit" class="btn btn-primary pull-right">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
        <?=$this->e($info->_e('form_btn_save'));?>
    </button>

</fieldset>
</form>

<?php } ?>

</div></div></div>