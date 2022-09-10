<div class="container">      
<div class="row"> 
<div class="mx-auto w-100 signin-box padding-box">
<div id="signin-wrapper" class="">
<div class="">
    <div class="text-xs-center font-weight-bold">
        <h3><?=$this->e($info->_e('form_title'));?></h3>
        <hr class="m-t-2 m-b-2">
    </div>
    <form id="fsignin" data-parsley-validate>
    <div class="form-group">
        <label for="account"><?=$this->e($info->_e('form_field_account'));?></label>
        <input type="text" id="account" name="ACCOUNT" class="form-control" placeholder="<?=$this->e($info->_e('form_field_account_placeholder'));?>" minlength="1" maxlength="255" value="<?php if (isset($params['account'])) echo filter_var($params['account'],FILTER_SANITIZE_EMAIL); ?>" required="">
    </div>
    <div id="pwd_panel" class="form-group" style="visibility: hidden; display: none">
        <label id="lbl_pwd_otp" for="pwd" style="visibility: hidden;display: none"><?=$this->e($info->_e('form_field_password_otp'));?></label>
        <label id="lbl_pwd" for="pwd" style="visibility: hidden;display: none"><?=$this->e($info->_e('form_field_password'));?></label>
        <input type="password" id="pwd" name="PWD" class="form-control" minlength="6" maxlength="255" disabled="">
    </div>
    <div class="form-group">
        <div id="error_unknown" class="card card-error" style="visibility: hidden;display: none">
            <div class="card-body" style="color: #FF0000;">
                <?=$this->e($info->_e('error_unknown'));?>
            </div>
        </div>
        <div id="error_account_disabled" class="card card-error" style="visibility: hidden;display: none">
            <div class="card-body" style="color: #FF0000;">
                <?=$this->e($info->_e('error_account_disabled'));?>
            </div>
        </div>
    </div>
    <div class="form-group text-xs-center">
        <?php         
            $csrf = new \Moviao\Security\CSRF_Protect(); 
            echo $csrf->echoInputField();
        ?>
        <input type="hidden" id="pwd_type" name="PWD_TYPE" value="1" />
        <button id="bsubmit" class="btn btn-primary btn-block" type="submit">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
            <?=$this->e($info->_e('form_submit'));?>
        </button>
        <div style="padding-top: 5px"><p><a href="#" data-toggle="modal" data-target="#RForm"><?=$this->e($info->_e('form_forgot'));?></a></p></div>
    </div>
    
    <hr class="m-t-2 m-b-2">
    
    
    <?php
        // TODO: Alpha phase : disable signup link + facebook signup
        //if ($suffix === '') {
    ?>


    <!-- Facebook Login -->
    <div id="btn_fb" class="fb-login-button" data-width="" data-size="large" data-button-type="login_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="false"></div>

<!-- 
    <button  id="btn_fb" class="btn btn-sm text-white" type="button" style="background-color:#4267B2">
    <svg class="btn-fb-icon _5h0m" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 216 216" width="16px" height="16px" color="#ffffff"><path fill="#ffffff" d="
      M204.1 0H11.9C5.3 0 0 5.3 0 11.9v192.2c0 6.6 5.3 11.9 11.9
      11.9h103.5v-83.6H87.2V99.8h28.1v-24c0-27.9 17-43.1 41.9-43.1
      11.9 0 22.2.9 25.2 1.3v29.2h-17.3c-13.5 0-16.2 6.4-16.2
      15.9v20.8h32.3l-4.2 32.6h-28V216h55c6.6 0 11.9-5.3
      11.9-11.9V11.9C216 5.3 210.7 0 204.1 0z"></path></svg>facebook
    </button> -->

    <?php //} ?>


    <br>


    


    <!-- Google Login -->
    <div id="btn_gg"></div> 
    

    </form>   
    <hr class="m-t-2 m-b-2">
    <?php
        // TODO: Alpha phase : disable signup link + facebook signup
        if ($suffix === '') {
    ?>
    <!-- Footer -->    
    <div class="text-xs-center">
        <a href="/<?=$lang?>/signup?key=be1389d42dafda61794f45e38dbe4e68"><span><?=$this->e($info->_e('form_not_member'));?></span></a>
    </div>
    <?php } ?>
</div></div></div></div></div><div id="custom-overlay" class="loading-none"><div class="loader"></div></div>
<?php $this->insert('modules::form_recover',['lang' => $lang, 'info' => $info]); ?>