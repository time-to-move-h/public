<div class="container">                      
<div class="row">  
<!--Form-->
<div class="col-lg-8 offset-lg-2 col-md-8">
<div class="card-body">
<div>    
<div id="sub-params" <?php if ($subscribed) echo 'style="display: none"'; ?>>     
<div class="card">
<div class="card-body">
<!--Header-->
<div class="text-xs-center">
    <h3><?=$this->e($info->_e('form_title'));?></h3>
    <hr class="m-t-2 m-b-2">
</div>   
<form id="fsignup_validation" data-parsley-validate>
    <!--Body-->
    <div class="form-group">             
        <label class="active" for="account"><?=$this->e($info->_e('form_field_email'));?></label>
        <input id="account" name="a" type="text" placeholder="<?=$this->e($info->_e('form_field_email_placeholder'));?>" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$" value="<?php if (isset($params) && isset($params["a"])) echo filter_var($params['a'], FILTER_SANITIZE_EMAIL); ?>" class="form-control" maxlength="255" required="">
    </div>
    <div class="form-group">
        <label for="passcode"><?=$this->e($info->_e('form_field_code'));?></label>
        <input id="passcode" name="c" type="text" pattern="[0-9]{6,}" class="form-control" minlength="6" maxlength="6" required="">
    </div>
    <div class="text-xs-center">
        <button id="bsubmit" class="btn btn-primary" type="submit"><?=$this->e($info->_e('form_submit'));?></button>
    </div>    
</form>
</div>
</div>
</div>     
<!-- Success-->
<div class="jumbotron" id="sub_success" <?php if (!$subscribed) echo 'style="display: none"'; ?>>
    <h1 class="h1-responsive green-text"><?=$this->e($info->_e('form_success'));?></h1>
   <p class="lead"><?=$this->e($info->_e('form_congratulation'));?></p>
   <hr class="m-y-2">
   <p><?=$this->e($info->_e('form_confirm'));?></p><br>
   <p class="lead">
       <a href="/login" class="btn btn-primary" role="button"><?=$this->e($info->_e('form_link_connexion'));?></a>
   </p>
</div>
<!-- Success-->        
</div></div></div></div></div>