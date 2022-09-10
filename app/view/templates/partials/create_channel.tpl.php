<div class="container">
<div class="row">
<div class="mx-auto w-100 createevent-box padding-box">

<form id="formChannel" class="step-form" data-parsley-validate>

<!--Step 1-->
<div class="form-section current">
<fieldset>
<legend><?=$this->e($info->_e('page_subtitle'));?></legend>
<!--    <div class="form-group">-->
<!--        <label for="name">--><?//=$this->e($info->_e('form_field_name'));?><!--</label>-->
<!--        <input type="text" placeholder="--><?//=$this->e($info->_e('form_field_name_placeholder'));?><!--" pattern="^[A-Za-z0-9_]{1,50}$" id="name" name="NAME" class="form-control" minlength="1" maxlength="50" required="">-->
<!--    </div>-->
    <div class="form-group">
        <label for="title" class=""><?=$this->e($info->_e('form_field_title'));?></label>
        <input type="text" id="title" name="TITLE" class="form-control" minlength="1" maxlength="50" required="">
    </div>
    <div class="form-group">
        <label for="ldesc"><?=$this->e($info->_e('form_field_descl'));?></label>
        <div style="background-color: #ffffff"><div id="CHADESC" name="DESCL" style="height: 150px" ></div></div>
    </div>
<!--    <div class="form-group">-->
<!--        <label for="visibility">--><?php //=$this->e($info->_e('form_field_chavis'));?><!--</label>-->
<!--        <select class="form-control" id="visibility" name="CHAVIS" required="">-->
<!--        <option value="1" selected>--><?php //=$this->e($info->_e('form_field_chavis_1'));?><!--</option>-->
<!--        <option value="2">--><?php //=$this->e($info->_e('form_field_chavis_2'));?><!--</option>-->
<!--        </select>                -->
<!--    </div> -->
<!--    <div class="form-group">-->
<!--        <label for="tags">--><?php //=$this->e($info->_e('form_field_tags'));?><!--</label>        -->
<!--        <select class="form-control" id="tags" name="TAGS[]" size="10" multiple="" required=""></select>-->
<!--    </div>     -->
<div class="form-group">
    <div id="error_unknown" class="card card-error" style="visibility: hidden;display: none">
        <div class="card-body" style="color: #FF0000;">
            <?=$this->e($info->_e('error_unknown'));?>
        </div>
    </div>
</div>
<div class="form-group">
    <div id="error_name_used" class="card card-error" style="visibility: hidden;display: none">
        <div class="card-body" style="color: #FF0000;">
            <?=$this->e($info->_e('error_name_used'));?>
        </div>
    </div>
</div>


<!--Step 2-->
<!--<div class="form-section">        -->
<!--<fieldset>-->
<!--    <legend>--><?php ////=$this->e($info->_e('page_legend_location'));?><!--</legend>  -->
<!--        --><?php ////$this->insert('modules::form_places',['info' => $info]); ?>
<!--    </fieldset>           -->
<!--</div>-->
<div class="form-navigation">
    <?php
    $csrf = new \Moviao\Security\CSRF_Protect();
    echo $csrf->echoInputField();
    ?>
    <button type="button" class="previous btn btn-info" style="display: none">Back</button>
    <button type="button" class="next btn btn-info pull-right" style="display: none">Next</button>

    <div class="form-group">
        <button id="btn_publish" type="submit" class="btn btn-lg btn-primary btn-block pull-right" style="display: none">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
            <?=$this->e($info->_e('form_btn_publish'));?>
        </button>
        <span class="clearfix"></span>
    </div>

    <div class="form-group">
        <a href="/profile" id="bcancel" class="btn btn-secondary btn-block text-white">
            <?=$this->e($info->_e('form_btn_cancel'));?>
        </a>
    </div>

</div>
<!--</div>-->




</fieldset>
</div>     

</form>




<!-- Success-->
<div id="sub_success" style="display: none">
    <h1 class="h1-responsive green-text"><?=$this->e($info->_e('form_success'));?></h1>
    <p class="lead"><?=$this->e($info->_e('form_success_description'));?></p>
    <hr class="m-y-2">
    <p class="lead">
        <?php if (isset($params['new'])) { ?>
            <a href="/create_event" class="btn btn-primary btn-block" role="button"><?=$this->e($info->_e('form_link_continue_event'));?></a>
        <?php } else { ?>
            <a href="/home" class="btn btn-primary btn-block" role="button"><?=$this->e($info->_e('form_link_continue'));?></a>
        <?php } ?>
    </p>
</div>
<!-- Success -->










</div></div></div>