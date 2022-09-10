<?php
declare(strict_types=1);
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/form_event_edit_dates.json');
//--------------------------------------------
//var_dump($dataView);
// <?php echo $dataView['DATBEG']; 
?>
<div id="panel_edit_dates" style="visibility: hidden;display: none">
<h5><?php echo $this->e($t->_e('page_legend_datetime'));?></h5>
<form id="feventdates" data-parsley-validate>
<fieldset>
<div class="form-group form-inline">
<label for="allday" class="form-check-label">
<input id="allday" name="ALLDAY" type="checkbox" class="form-check-input" <?php if ($dataView['ALLDAY'] === '1') echo 'checked'; ?>>
<?=$this->e($t->_e('form_field_allday'));?>
</label>
</div>
<div class="form-group">
<label for="start_date"><?=$this->e($t->_e('form_field_datbeg'));?></label>
<input id="start_date_formatted" name="DATBEG" type="hidden" value="<?php echo $dataView['DATBEG_ISO8601']; ?>">
<input id="start_date" type="text" placeholder="<?=$this->e($t->_e('form_field_datbeg_placeholder'));?>" class="form-control" readonly required>
</div>
<div class="form-group" style="display: none">
<label for="timezonebeg"><?=$this->e($t->_e('form_field_zoneidbeg'));?></label>
<select class="form-control" id="timezonebeg" name="ZONEIDBEG_ID">
<option value="52" selected>Europe/Brussels</option>
</select>
</div>
<div id="datendwrapper" class="form-group">
<label for="end_date"><?=$this->e($t->_e('form_field_datend'));?></label>
<input id="end_date_formatted" name="DATEND" type="hidden" value="<?php echo $dataView['DATEND_ISO8601']; ?>">
<input id="end_date" type="text" placeholder="<?=$this->e($t->_e('form_field_datend_placeholder'));?>" class="form-control" readonly>
</div>
<div class="form-group" style="display: none">
<label for="timezoneend"><?=$this->e($t->_e('form_field_zoneidend'));?></label>
<select class="form-control" id="timezoneend" name="ZONEIDEND_ID">
<option value="52" selected>Europe/Brussels</option>
</select>
</div>
</fieldset>
<input type="hidden" name="UID" value="<?php echo $urllink;?>">
<?php if (isset($csrf_token)) echo $csrf_token; ?>
<button id="btn_save_dates" type="submit" class="btn btn-primary"><?=$this->e($t->_e('btn_save'));?></button>
</form>
<input id="btn_save_desc_label" type="hidden" value="<?=$this->e($t->_e('btn_save'));?>">
</div>