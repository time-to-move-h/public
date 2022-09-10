<?php  
declare(strict_types=1);
$sessionUser->startSession();
$search_query = null;
$search_loc = null;
$search_lat = null; //  52.3680
$search_lon = null; // 4.9036
$search_rad = null; // 30
$location = null; // Pick a City
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/header.json');
//--------------------------------------------
if (isset($params)) {
    if (isset($params['Q'])) {
        $search_query = filter_var($params['Q'], FILTER_SANITIZE_STRING);
    }
    if (isset($params['LOC'])) {
        $search_loc = filter_var($params['LOC'], FILTER_SANITIZE_STRING);
    }
    if (isset($params['LAT'])) {
        $search_lat = filter_var($params['LAT'], FILTER_SANITIZE_STRING);
    }
    if (isset($params['LON'])) {
        $search_lon = filter_var($params['LON'], FILTER_SANITIZE_STRING);
    }
    if (isset($params['RAD'])) {
        $search_rad = filter_var($params['RAD'], FILTER_SANITIZE_NUMBER_INT);
    }
}

if (isset($sessionUser) && $sessionUser->isValid()) {        
    // User Information
    $uid = null;
    $dataUser = null;
    $user_picture = '/img/u/user-default.png';
    $user = null;
    try {

        $user = new \Moviao\Data\UsersCommon();
        $user->iniDatabase();
        $user->setSession($sessionUser);

        $r = $user->show($uid);

        if ($r['result']) {               
            $dataUser = $r['data'];
            //echo var_dump($dataUser[0]['NDISP']);
            if (isset($dataUser['PICTURE']) && $dataUser['PICTURE'] !== null) {
                $user_picture = $dataUser['PICTURE'];
            }
        }

        // ---------------------------------
        $iduser = $sessionUser->getIDUSER();
        $user_utils = new \Moviao\Data\Util\UsersUtils($user);
        $user_prefs = $user_utils->getUserSearchPreference($iduser);
        //exit(var_dump($user_prefs[0]["LAT"]));

        //$lat= $user_prefs[0]["LAT"];
        //$lng = $user_prefs[0]["LON"];

        if ($user_prefs != null) {
            $location = $user_prefs[0]["LOCATION"];
        }

    } catch (\Error $e) {
        error_log('template.php : ' . $e);
    } finally {
        if (null !== $user) {
            $user->disconnect();
            unset($user);
        }
    }
}

$home_link = '/';
if (isset($sessionUser) && $sessionUser->isValid()) { 
    $home_link = '/home';
}
?>
<!DOCTYPE html>
<html lang="<?=$this->e($info->_e('page_lang'));?>">
<head>
<meta charset="utf-8">
<title>
<?
if (! empty($page_title)) {
    echo $page_title;
} else {
    $this->e($info->_e('page_title'));
}
?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="text/javascript" src="/dist/js/pace.js"></script>
<?php $this->insert('inc::styles'); ?>
<?=$this->section('scripts')?>
</head>
<body class="d-flex flex-column">     

<div id="page-content">

<!-- Header -->
<header class="header" id="site-header">
    <div class="page-title">
        <div class="header-top-logo">
            <a href="/home" class="logo">

<!--                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="32px" height="32px" viewBox="0 0 400 440" preserveAspectRatio="xMidYMid meet">-->
<!--                    <g id="layer101" fill="#ffffff" stroke="none">-->
<!--                        <path d="M36 400 c-43 -48 -46 -82 -12 -128 13 -18 33 -59 45 -92 15 -42 39 -77 79 -120 32 -33 62 -60 69 -60 7 0 9 16 6 44 -3 23 2 75 11 114 9 39 14 79 11 89 -3 10 1 5 9 -12 8 -16 15 -51 16 -77 0 -27 4 -48 9 -48 14 0 57 60 66 90 4 14 18 38 31 53 17 20 24 41 24 71 0 36 -6 48 -40 81 -43 42 -56 43 -40 1 13 -36 13 -69 -2 -101 -15 -33 -22 -32 -36 9 -7 19 -22 46 -34 61 l-21 27 -28 -74 c-16 -40 -29 -85 -29 -100 0 -16 -4 -28 -10 -28 -5 0 -10 6 -10 13 0 8 -16 45 -36 82 -29 55 -35 76 -31 107 3 21 2 38 -3 38 -4 0 -24 -18 -44 -40z"/>-->
<!--                    </g>-->
<!--                </svg>-->
                <div class="header-title"></div>
            </a>
        </div>
    </div>
    <div class="header-content-wrapper">
        <?php if (isset($sessionUser) && $sessionUser->isValid()) { ?>

<!--            placeholder="--><?//= //$this->e($t->_e('search_placeholder'))?><!--"-->
<!-- 
        <div>
            <form id="searchfrm" name="searchfrm" method="get" class="" action="/home">

                <div class="form-group with-button moviao-autocomplete">
                <input id="search_query" name="q" class="search_bar" type="search" value="<?php if (! empty($search_query)) echo htmlentities(urldecode($search_query)); ?>" >
                <button class="button_search">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 50 50" version="1.1" width="20px" height="20px">
                        <g id="surface1">
                            <path style=" " d="M 21 3 C 11.601563 3 4 10.601563 4 20 C 4 29.398438 11.601563 37 21 37 C 24.355469 37 27.460938 36.015625 30.09375 34.34375 L 42.375 46.625 L 46.625 42.375 L 34.5 30.28125 C 36.679688 27.421875 38 23.878906 38 20 C 38 10.601563 30.398438 3 21 3 Z M 21 7 C 28.199219 7 34 12.800781 34 20 C 34 27.199219 28.199219 33 21 33 C 13.800781 33 8 27.199219 8 20 C 8 12.800781 13.800781 7 21 7 Z "/>
                        </g>
                    </svg>
                </button>
                </div>

                <input type="hidden" id="search_lat" name="lat" value="<?php echo $search_lat; ?>">
                <input type="hidden" id="search_lon" name="lon" value="<?php echo $search_lon; ?>">
                <input type="hidden" id="search_rad" name="rad" value="<?php echo $search_rad; ?>">
            </form>
        </div> -->




<!--        <div class="location-block">-->
<!--            <div class="location-title">--><?//=$this->e($t->_e('search_city_brussels'))?><!--</div>-->
<!--            <div class="location    -logo"><svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24"><path d="M12 0c-4.198 0-8 3.403-8 7.602 0 4.198 3.469 9.21 8 16.398 4.531-7.188 8-12.2 8-16.398 0-4.199-3.801-7.602-8-7.602zm0 11c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"></path></svg></div>-->
<!--        </div>-->
            <div class="control-block">

            <!-- <a href="/pickacity" class="text-white" target="_self"><?php //echo $location; ?></a> -->

            <div class="author-page author vcard inline-items more">
                    <div class="author-thumb">
                        <img alt="" src="<?php if (isset($dataUser[0]['PICTURE'])) echo $dataUser[0]['PICTURE']; ?>" onerror="this.src='/img/user.png'" class="avatar-header">
                        <span class="icon-status online"></span>
                        <div class="more-dropdown more-with-triangle">
                            <div class="ui-block-title ui-block-title-small">
                                <h6 class="title"><?=$this->e($t->_e('menu_account'))?></h6>
                            </div>
                            <ul class="account-settings">
                                <?php
                                $checkVars = array(2, 99); // Sellers and Special Team
                                if (in_array($sessionUser->getAccountType(), $checkVars)) {
                                ?>

                                <li>
                                    <a href="/create_event">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="26" viewBox="0 0 16 16"><path fill="#999EBE" d="M5 6h2v2h-2zM8 6h2v2h-2zM11 6h2v2h-2zM2 12h2v2h-2zM5 12h2v2h-2zM8 12h2v2h-2zM5 9h2v2h-2zM8 9h2v2h-2zM11 9h2v2h-2zM2 9h2v2h-2zM13 0v1h-2v-1h-7v1h-2v-1h-2v16h15v-16h-2zM14 15h-13v-11h13v11z"></path></svg>
                                        <span><?=$this->e($t->_e('btn_create_event'))?></span>
                                    </a>
                                </li>

                                <li>
                                    <a href="/dashboard/">
                                        <svg version="1.1" enable-background="new 0 0 64 64" viewBox="0 0 64 64" width="20" height="26" xmlns="http://www.w3.org/2000/svg"><g><path d="m60 5h-56c-1.654 0-3 1.346-3 3v48c0 1.654 1.346 3 3 3h56c1.654 0 3-1.346 3-3v-48c0-1.654-1.346-3-3-3zm-56 2h56c.552 0 1 .449 1 1v9h-58v-9c0-.551.448-1 1-1zm15 12h42v12h-42zm-2 12h-14v-12h14zm-14 25v-23h14v24h-13c-.552 0-1-.449-1-1zm57 1h-41v-24h42v23c0 .551-.448 1-1 1z"/><path d="m8 15c1.654 0 3-1.346 3-3s-1.346-3-3-3-3 1.346-3 3 1.346 3 3 3zm0-4c.552 0 1 .449 1 1s-.448 1-1 1-1-.449-1-1 .448-1 1-1z"/><path d="m16 15c1.654 0 3-1.346 3-3s-1.346-3-3-3-3 1.346-3 3 1.346 3 3 3zm0-4c.552 0 1 .449 1 1s-.448 1-1 1-1-.449-1-1 .448-1 1-1z"/><path d="m24 15c1.654 0 3-1.346 3-3s-1.346-3-3-3-3 1.346-3 3 1.346 3 3 3zm0-4c.552 0 1 .449 1 1s-.448 1-1 1-1-.449-1-1 .448-1 1-1z"/><path d="m15 29v-8h-10v8zm-8-6h6v4h-6z"/><path d="m53 21c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4zm0 6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2z"/><path d="m41 21c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4zm0 6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2z"/><path d="m29 21c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4zm0 6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2z"/><path d="m5 37v6h10v-6zm8 4h-6v-2h6z"/><path d="m5 53h10v-6h-10zm2-4h6v2h-6z"/><path d="m37 53h-2v-18h-6v18h-2v-14h-6v16h22v-10h-6zm-12 0h-2v-12h2zm6-16h2v16h-2zm8 10h2v6h-2z"/><path d="m45 43h14v-8h-14zm2-6h10v4h-10z"/><path d="m45 45h2v2h-2z"/><path d="m49 45h10v2h-10z"/><path d="m45 49h2v2h-2z"/><path d="m49 49h10v2h-10z"/><path d="m45 53h2v2h-2z"/><path d="m49 53h10v2h-10z"/></g></svg>
                                        <span><?=$this->e($t->_e('menu_dashboard'))?></span>
                                    </a>
                                </li>

                                <?php } ?>

                                <li>
                                    <a href="/profile/">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="26" viewBox="0 0 16 16"><path fill="#999EBE" d="M9 11.041v-0.825c1.102-0.621 2-2.168 2-3.716 0-2.485 0-4.5-3-4.5s-3 2.015-3 4.5c0 1.548 0.898 3.095 2 3.716v0.825c-3.392 0.277-6 1.944-6 3.959h14c0-2.015-2.608-3.682-6-3.959z"></path></svg>
                                        <span><?=$this->e($t->_e('menu_profile'))?></span>
                                    </a>
                                </li>

                                <li>
                                    <a href="/contactus/">
                                        <svg version="1.1" enable-background="new 0 0 512 512" viewBox="0 0 512 512" width="20" height="26" xmlns="http://www.w3.org/2000/svg"><path d="m0 96v320h512v-320zm277.599 168.999c-12.717 9.974-30.48 9.973-43.197 0l-177.243-138.999h397.682zm-102.439-8.334-145.16 96.372v-210.211zm24.866 19.501 15.862 12.44c11.81 9.262 25.958 13.891 40.112 13.891 14.15 0 28.304-4.631 40.111-13.891l15.863-12.44 165.438 109.834h-442.824zm136.814-19.501 145.16-113.839v210.211z"/></svg>
                                        <span><?=$this->e($t->_e('menu_contactus'))?></span>
                                    </a>
                                </li>

                                <li>
                                    <a href="/logout">
                                        <svg class="olymp-logout-icon"><use xlink:href="/img/i/icons.svg#olymp-logout-icon"></use></svg>
                                        <span><?=$this->e($t->_e('menu_logout'))?></span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <a href="/profile/" class="author-name fn">
                        <div class="author-title">
                            <?php
                            if (! empty($dataUser)) {
                                $ndisp = $dataUser[0]['NDISP'];

                                if (! is_null($sessionUser) && $sessionUser->getAccountType() === 99) {
                                    $ndisp .= ' *';
                                }

                                echo htmlspecialchars(strip_tags(mb_substr($ndisp,0,25)));
                            }
                            ?> <svg class="olymp-dropdown-arrow-icon"><use xlink:href="/img/i/icons.svg#olymp-dropdown-arrow-icon"></use></svg>
                        </div>
                    </a>
                </div>
            </div>
        <?php } ?>
	</div>
</header>
<!-- ... end Header -->
<div class="header-spacer"></div>
<!-- ... end Window for responsive min-width: 768px -->
<div id="panel" class="body-container">
<div id="masthead">    
<?=$this->section('content')?>    
</div>     
</div>
<?php
$server = new \Moviao\Http\ServerInfo();
$suffix = $server->getServerSuffix();
//if ($suffix !== 'LOCALHOST') {
?>
<!-- Statistics -->
<!-- End Statistics -->
<?php //} ?>



<?php include('inc/footer.tpl.php'); ?>

</div>
</body>
</html>