<?php  
declare(strict_types=1);
$search_query = '';
$locsearch = '';
//$loc_lat = '';
//$loc_lng = '';

// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/header.json');
//--------------------------------------------
if (isset($params,$params['q'])) {
    $search_query = $params['q'];
    //$locsearch = $params['locsearch'];
    //$loc_lat = $params['loc_lat'];
    //$loc_lng = $params['loc_lng'];
}
if (isset($sessionUser) && $sessionUser->isValid()) {        
    // User Information
    $uid = 
    $dataUser = null;
    $user_picture = '/img/u/user-default.png';
    try {
        $user = new \Moviao\Data\UsersCommon();
        $user->iniDatabase();
        $user->setSession($sessionUser);
        $r = $user->show($uid);
        if ($r['result']) {               
            $dataUser = $r['data'];
            //exit(var_dump($dataUser[0]['NDISP']));
            if (isset($dataUser["PICTURE"]) && $dataUser["PICTURE"] !== null) {
                $user_picture = $dataUser["PICTURE"];
            }
        }                        
    } catch (Exception $ex) {
        error_log("header.inc.php : $ex");
    }         
}

$home_link = '/';
if (isset($sessionUser) && $sessionUser->isValid()) { 
    $home_link = '/' . $lang . '/results';
}
?> 
<nav class="navbar sticky-top bg-white nav-bg box-shadow">    
  <div style="width: 100%;">        
  <div class="d-flex justify-content-start">      
      <div class="col-md-2">
          <a href="<?php echo $home_link; ?>">
              <div class="brand-logo"></div>
          </a>          
      </div>   
  <?php if (isset($sessionUser) && $sessionUser->isValid()) {  ?>  
  <div class="p-2 align-self-center hidden-md-down">  
    <form id="searchfrm" name="searchfrm" method="get" class="form-inline float-lg-right" action="/<?=$lang?>/home">
        <div class="form-group form-inline">            
            <label class="wrapper-icon">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input id="search_query" name="q" class="form-control form-control-sm" style="width: 500px" type="search" value="<?php echo $search_query; ?>" placeholder="<?=$this->e($t->_e('search_placeholder'))?>">
            </label>



<!--            <input type="hidden" id="loc_lat" name="loc_lat" value="--><?php //echo $loc_lat;?><!--">-->
<!--            <input type="hidden" id="loc_lng" name="loc_lng" value="--><?php //echo $loc_lng;?><!--">         -->
        </div>      
    </form>


  </div>
<!--<div class="p-2 align-self-center pull-right"><a class="btn btn-sm align-middle btn-outline-primary btn-margin-left" href="/<?//=$lang?>/create_event"><?//=$this->e($t->_e('btn_create_event'))?></a></div> -->
  <div class="ml-auto align-self-center">      
  <a tabindex="0" class="profile-navbar" role="button" data-toggle="popover" data-placement="bottom" data-trigger="focus">            
      <img src="<?php if (isset($dataUser[0]['PICTURE'])) echo $dataUser[0]['PICTURE']; ?>" onerror="hideImage(this);" class="rounded-circle" border="0" width="50px" height="50px">      
  </a>  
  <div id="popmenu" class="" style="display: none">
    <div class="panel panel-default">
        <div class="my-popover-content">              
            <a class="dropdown-item" href="/create_event"><?=$this->e($t->_e('btn_create_event'))?></a>
            <a class="dropdown-item" href="/calendar"><?=$this->e($t->_e('menu_calendar'))?></a>
            <!-- <a class="dropdown-item" href="/<?//=$lang?>/contacts"><?//=$this->e($t->_e('menu_contacts'))?></a>-->
            <a class="dropdown-item" href="/profile/"><?=$this->e($t->_e('menu_profile'))?></a>
            <a class="dropdown-item" href="/logout"><?=$this->e($t->_e('menu_logout'))?></a>            
        </div>
    </div>  
  </div>    
  </div>  
  </div>
  <?php } else { ?> 
  <div class="ml-auto align-self-center" style="padding-right: 20px"><a href="/<?=$lang?>/login" class="btn btn-outline-primary" role="button" aria-disabled="true"><?=$this->e($t->_e('btn_conn'))?></a></div>
  <?php } ?>     
</div>  
</div>    
</nav>