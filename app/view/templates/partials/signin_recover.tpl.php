<div class="container">                      
<div class="row">  
<!--Form-->
<div class="col-lg-6 offset-lg-3 col-md-8">
<div class="card-body">
<div>
<?php if (isset($valid) && $valid === true) { ?>
<div id="sub_form">     
<!--Header-->
<div class="text-xs-center">
    <h3><?=$this->e($info->_e('form_title'));?></h3>
    <hr class="m-t-2 m-b-2">
</div>   
<form id="frecover" data-parsley-validate>
    <input name="acc" type="hidden" value="<?php if (isset($a)) echo $a;?>">
    <input name="auth" type="hidden" value="<?php if (isset($b)) echo $b;?>">
    <!--Body-->
    <div class="form-group">             
        <label class="active" for="pwd1"><?=$this->e($info->_e('form_field_pwd1'));?></label>
        <input id="p1" name="P1" type="password" class="form-control" required="">
    </div>
    <div class="form-group">
        <label for="pwd2"><?=$this->e($info->_e('form_field_pwd2'));?></label>
        <input id="p2" name="P2" type="password" class="form-control" required="">
<!--        pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"-->
    </div>
    <div class="form-group">
        <div id="error_unknown" class="card card-error" style="visibility: hidden;display: none">
            <div class="card-body" style="color: #FF0000;">
                <?php //=$this->e($info->_e('error_unknown'));?>
            </div>
        </div>
    </div>


    <div class="form-group">
        <div class="text-xs-center">
            <button id="bsubmit" class="btn btn-lg btn-primary btn-block" type="submit"><?=$this->e($info->_e('form_submit'));?></button>
        </div>
    </div>

    <div class="form-group">
        <a href="/profile" id="bcancel" class="btn btn-secondary btn-block text-white">
            <?=$this->e($info->_e('form_btn_cancel'));?>
        </a>
    </div>


</form>
</div>  
<?php } ?>     
<!-- Success-->
<div id="sub_success" style="display: none">
    <h1 class="h1-responsive green-text"><?=$this->e($info->_e('form_success'));?></h1>
   <p class="lead"><?=$this->e($info->_e('form_congratulation'));?></p>
   <hr class="m-y-2">
   <?php if (isset($sessionUser) && null !== $sessionUser && $sessionUser->isValid() === true) { ?>
       <p class="lead">
           <a href="/profile" class="btn btn-primary btn-block" role="button"><?=$this->e($info->_e('form_link_continue'));?></a>
       </p>
   <?php } else { ?>
       <p><?=$this->e($info->_e('form_confirm'));?></p><br>
       <p class="lead">
           <a href="/login" class="btn btn-primary btn-block" role="button"><?=$this->e($info->_e('form_link_connexion'));?></a>
       </p>
   <?php } ?>
</div>
<!-- Success -->
<!-- Failed -->
<div id="sub_failed" <?php  if (isset($valid) && $valid === true) echo 'style="display: none"';?>>
    <h1 class="h1-responsive green-text"><?=$this->e($info->_e('form_failed'));?></h1>
   <p class="lead"><?=$this->e($info->_e('form_failed_subtitle'));?></p>
   <hr class="m-y-2">
   <?php if (isset($sessionUser) && null !== $sessionUser && $sessionUser->isValid() === true) { ?>
       <p class="lead">
           <a href="/login/recover" class="btn btn-primary btn-block" role="button"><?=$this->e($info->_e('form_link_retry'));?></a>
       </p>
   <?php } else { ?>
       <p><?=$this->e($info->_e('form_failed_msg'));?></p><br>
       <p class="lead">
           <a href="/login" class="btn btn-primary btn-block" role="button"><?=$this->e($info->_e('form_link_connexion'));?></a>
       </p>
   <?php } ?>
</div>
<!-- Failed --> 
</div></div></div></div></div>