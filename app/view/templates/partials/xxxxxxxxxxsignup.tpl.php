<div class="container"> 
<div class="row">     
<div class="mx-auto w-50 signup-box">
<div class="">
<div class="">
<div id="panel_form">    
<div class="text-xs-center">
    <h3><?=$this->e($info->_e('form_title'));?></h3>
    <hr class="m-t-2 m-b-2">
</div>
<br>    
<form id="fsignup" data-parsley-validate>
<div class="form-group">            
    <label for="name"><?=$this->e($info->_e('form_field_name'));?></label>
    <input type="text" id="name" name="FNAME" class="form-control" minlength="1" maxlength="100" required="">
</div>
<div class="form-group">            
    <label for="lastname"><?=$this->e($info->_e('form_field_lastname'));?></label>
    <input id="lastname" name="LNAME" type="text" class="form-control" minlength="1" maxlength="100" required="">
</div>                       
<div class="form-group">           
    <label for="account"><?=$this->e($info->_e('form_field_account'));?></label>
    <input id="account" name="ACCOUNT" type="text" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$" class="form-control" minlength="1" maxlength="255" required="">
</div>
<input id="country" name="CNT" type="hidden" value="BE">
<?php if (1==2) { ?>
<!-- TODO: Change to country selection -->
<!--<div class="form-group">    
    <label for="country">=$this->e($info->_e('form_field_country'));</label>
    <select id="country" name="CNT" class="form-control" required="">                              
        <option value="BE" php //if ($suffix == 'BE') echo 'selected'  selected="true">Belgique</option>
    </select>                  
</div>    -->
<?php } ?>
<div class="form-group">             
    <label class="active" for="pwd1"><?=$this->e($info->_e('form_field_pwd1'));?></label>
<!--    ^(?=.*[\d])(?=.*[A-Z])(?=.*[a-z])[\w\d!@#$%_*]{6,50}$-->
    <input id="PWD" name="PWD" type="password" pattern="^(?=.*\d)(?=.*[a-zA-Z])(?!.*\s).*$" minlength="6" maxlength="50" class="form-control" required="">        
</div>
<div class="form-group">
    <label for="pwd2"><?=$this->e($info->_e('form_field_pwd2'));?></label>
    <input id="PWD2" name="PWD2" type="password" pattern="^(?=.*\d)(?=.*[a-zA-Z])(?!.*\s).*$" minlength="6" maxlength="50" class="form-control" required="">        
</div>    
<div class="form-inline">
    <input type="checkbox" id="agree" required="">
    <label for="agree">&nbsp;&nbsp;<?=$this->e($info->_e('form_agree_1'));?>&nbsp;<a target="_blank" href="/<?=$lang?>/termsofuse"><u><?=$this->e($info->_e('form_agree_2'));?></u></a></label>
</div>    
<div class="form-group">
    <p>&nbsp;</p>
</div>    
<div class="text-xs-center">
    <button id="bsubmit" class="btn btn-lg btn-block btn-primary" type="submit"><?=$this->e($info->_e('form_signup'));?></button>
</div>      
</form>
</div>    
<div class="jumbotron" id="panel_success" style="display: none">
   <h1 class="h1-responsive"><?=$this->e($info->_e('form_success'));?></h1>
   <p class="lead"><?=$this->e($info->_e('form_congratulation'));?></p>
   <hr class="m-y-2">
   <p><?=$this->e($info->_e('form_confirm'));?><p><br>
   <p class="lead">
       <a href="/<?php echo $lang;?>/signup/validation" class="btn btn-primary btn-lg" role="button"><?=$this->e($info->_e('form_link_connexion'));?></a>
   </p>
</div></div></div></div></div></div>