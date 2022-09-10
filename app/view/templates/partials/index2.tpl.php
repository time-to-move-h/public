<div class="container">
<div class="row">
<div class="mx-auto w-90 p-3 text-center index-box">
<img src="/img/city.png"><div class="content__teaser"><?=$this->e($info->_e('msg'));?>
<div class="content__form">
    
    
<form id="fsubscribe" class="form-inline" data-parsley-validate><div class=form-group">
<input type="text" id="email" name="MAIL" class="form-control" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$" placeholder="<?=$this->e($info->_e('email_placeholder'));?>" maxlength="255" required="">
<button class="btn btn-outline-primary" type="submit"><?=$this->e($info->_e('btn_submit'));?></button>
</div></form>



</div></div></div></div></div>