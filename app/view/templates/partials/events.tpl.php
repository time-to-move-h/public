<?php
declare(strict_types=1);
//$home_link = '/';
//if (isset($sessionUser) && $sessionUser->isValid()) {
//    $home_link = '/' . $lang . '/results';
//}
$q = '';
if (empty($params['q']) === false) {
    $q = urlencode($params['q']);
}
?>
<div class="container-home">
<div class="events-wrapper">
<?php if (1===2) { ?>
<div class="events-filter-wrapper">
    <div class="">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <!--        <label>Where</label>-->
                    <select id="search-where" class="edd-select" style="display: none">
                        <option value="brussels" selected><?=$this->e($info->_e('search_city_brussels'));?></option>
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <!--        <label>When</label>-->
                    <select id="search-when" class="edd-select" style="display: none">
                        <option value=""><?=$this->e($info->_e('period_all'));?></option>
                        <option value="today"><?=$this->e($info->_e('period_today'));?></option>
                        <option value="tomorrow"><?=$this->e($info->_e('period_tomorrow'));?></option>
                        <option value="weekend"><?=$this->e($info->_e('period_weekend'));?></option>
                        <option value="week"><?=$this->e($info->_e('period_week'));?></option>
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <!--        <label>Interested by</label>-->
                    <select id="search-tags" class="edd-select" name="TAGS[]" style="display: none">
                        <option value="" selected="selected"><?=$this->e($info->_e('form_field_tags_select_placeholder'));?></option>
                    </select>
                </div>
            </div>

            <!--<a href="results?q=--><?//=$q?><!--"></a>&nbsp;-&nbsp;-->
            <!--<a href="results?q=--><?//=$q?><!--&p="></a>&nbsp;-&nbsp;-->
            <!--<a href="results?q=--><?//=$q?><!--&p="></a>&nbsp;-&nbsp;-->
            <!--<a href="results?q=--><?//=$q?><!--&p="></a>&nbsp;-&nbsp;-->
            <!--<a href="results?q=--><?//=$q?><!--&p="></a>-->

        </div>
    </div>
</div>
<?php } ?>
<div id="grid-data" class="grid-evt"></div>
<div class="loadmore-wrapper-container text-center">
<div class="loadmore-wrapper">
<button id="btn_loadmore_events" class="btn btn-sm btn-secondary" type="button" style="visibility: hidden;"><?=$this->e($info->_e('btn_loadmore'));?></button>
</div>
</div>
<div class="jumbotron" id="msg_nodata" style="display: none">
<h1><?=$this->e($info->_e('form_nodata_title'));?></h1>
<hr class="m-y-2">
<p><?=$this->e($info->_e('form_nodata_content'));?></p><br>
</div></div></div>
<?php $this->insert('modules::tpl_events',['sessionUser' => $sessionUser]); ?>