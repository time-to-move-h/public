<?php
declare(strict_types=1);
$search_query = null;
$search_loc = null;
$search_lat = null; //  52.3680
$search_lon = null; // 4.9036
$search_rad = null; // 30
$location = null; // Pick a City

if (isset($params)) {
    if (isset($params['q'])) {
        $search_query = filter_var($params['q'], FILTER_UNSAFE_RAW);
    }
    if (isset($params['loc'])) {
        $search_loc = filter_var($params['loc'], FILTER_UNSAFE_RAW);
    }
    if (isset($params['lat'])) {
        $search_lat = filter_var($params['lat'], FILTER_UNSAFE_RAW);
    }
    if (isset($params['lon'])) {
        $search_lon = filter_var($params['lon'], FILTER_UNSAFE_RAW);
    }
    if (isset($params['rad'])) {
        $search_rad = filter_var($params['rad'], FILTER_SANITIZE_NUMBER_INT);
    }
}

//$home_link = '/';
//if (isset($sessionUser) && $sessionUser->isValid()) {
//    $home_link = '/' . $lang . '/results';
//}
$q = '';
if (empty($params['q']) === false) {
    $q = urlencode($params['q']);
}
?>

<!-- <div class="container-home">
<div class="row">
<div class="col-md-12 padding-box"> -->

<div class="advance-search" style="margin-top: 25px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 align-content-center">
                
                <form id="searchfrm" name="searchfrm" method="get" class="" action="/home">
                    <div class="form-row" >

                        <div class="form-group col-xl-8 col-lg-3 col-md-6 align-self-center">
                            <input id="search_query" name="q" type="text" class="form-control my-2 my-lg-1" id="inputtext4" placeholder="What are you looking for" value="<?php if (! empty($search_query)) echo htmlentities(urldecode($search_query)); ?>">
                        </div>
<!-- 
                        <div class="form-group col-lg-3 col-md-6">
                            <select class="w-100 form-control mt-lg-1 mt-md-2">
                                <option>Category</option>
                                <option value="1">Top rated</option>
                                <option value="2">Lowest Price</option>
                                <option value="4">Highest Price</option>
                            </select>
                        </div>

                        <div class="form-group col-lg-3 col-md-6">
                            <input type="text" class="form-control my-2 my-lg-1" id="inputLocation4" placeholder="Location">
                        </div> -->

                        <div class="form-group col-xl-4 col-lg-3 col-md-6 align-self-center">
                            <button type="submit" class="btn btn-primary active w-100">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- <div class="advance-search" style="margin-top: 25px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 align-content-center">
                
                <form>
                    <div class="form-row">

                        <div class="form-group col-xl-4 col-lg-3 col-md-6 align-self-center">
                            <input type="text" class="form-control my-2 my-lg-1" id="inputtext4" placeholder="What are you looking for">
                        </div>

                        <div class="form-group col-lg-3 col-md-6">
                            <select class="w-100 form-control mt-lg-1 mt-md-2">
                                <option>Category</option>
                                <option value="1">Top rated</option>
                                <option value="2">Lowest Price</option>
                                <option value="4">Highest Price</option>
                            </select>
                        </div>

                        <div class="form-group col-lg-3 col-md-6">
                            <input type="text" class="form-control my-2 my-lg-1" id="inputLocation4" placeholder="Location">
                        </div>

                        <div class="form-group col-xl-2 col-lg-3 col-md-6 align-self-center">
                            <button type="submit" class="btn btn-primary active w-100">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->

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
      
<!--    
</div>
</div>
</div> -->