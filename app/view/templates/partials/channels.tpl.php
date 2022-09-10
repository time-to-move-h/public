<?php
declare(strict_types=1);
//$home_link = '/';
//if (isset($sessionUser) && $sessionUser->isValid()) {
//    $home_link = '/' . $lang . '/results';
//}
?>
<div class="container">
    <div class="channels-wrapper">
    <div style="display: block">
        <div id="grid-data" class="grid-cha"></div>        
    </div>
    <div class="jumbotron" id="msg_nodata" style="display: none">
        <h1><?=$this->e($info->_e('form_nodata_title'));?></h1>
        <hr class="m-y-2">
        <p><?=$this->e($info->_e('form_nodata_content'));?></p><br>
    </div>
    </div>
</div>
<?php $this->insert('modules::tpl_channels',['sessionUser' => $sessionUser]); ?>