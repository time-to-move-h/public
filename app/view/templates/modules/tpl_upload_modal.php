<?php
declare(strict_types=1);
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/tpl_upload.json');
//--------------------------------------------
?>
<div id="panel_edit_img" style="visibility: hidden;display: none">
<h5><?=$this->e($t->_e('upload_title'));?></h5>
<div class="upload_view_actions">
    <label class="btn btn-primary btn-upload file-btn" for="upload" title="Upload image file">
        <input id="upload" type="file" class="sr-only" accept=".jpg,.jpeg,.gif,.png">
        <span class="docs-tooltip" data-toggle="tooltip" title="Import image with Blob URLs">
          <?=$this->e($t->_e('upload_btn_select'));?>
        </span>
    </label>
    <button id="upload_btn_save" class="btn btn-primary upload-result" style="visibility: hidden"><?=$this->e($t->_e('upload_btn_save'));?></button>
</div>
<div>
    <div class="upload-msg"></div>
    <div class="upload-demo-wrap">
        <div id="upload-demo"></div>
    </div>
</div>
<input id="btn_close_img_label" type="hidden" value="<?=$this->e($t->_e('upload_btn_close'));?>">
</div>