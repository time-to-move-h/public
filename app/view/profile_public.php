<?php
declare(strict_types=1);

$IDUSER = null;
$sessionUser->startSession();
//$sessionUser->checkAuth();
if (! is_null($sessionUser)) {
    $IDUSER = $sessionUser->getIDUSER();
}

$dataView = null;
$admin = false;

//exit(var_dump($params["a"]));

if ((! isset($uid)) || strlen($uid) <=0) {
    header('Location: /home');
    exit();
}

try {         
    $user = new \Moviao\Data\UsersCommon();       
    $user->iniDatabase();
    $user->setSession($sessionUser);
    $r = $user->show($uid);            
    if ($r['result']) {       
        $dataView = $r['data'][0];    
    }
} catch (Exception $ex) {
    error_log("Profile_public.php : $ex");    
}

if (empty($dataView)) {
    error_log("Profile_public.php : empty data view $uid"); 
    header('Location: /home');
    exit();
}

$subscription = (is_numeric($dataView['SUBSCRIPTION'])) ? intval($dataView['SUBSCRIPTION']) : 0;
$picture = (isset($dataView['PICTURE'])) ? $dataView['PICTURE'] : null;

if ($picture == null || strlen($picture) <= 0) {
    $picture = '/img/user-sun.jpg';
}

$background_picture = (isset($dataView['BACKGROUND'])) ? $dataView['BACKGROUND'] : null;

if ($background_picture == null || strlen($background_picture) <= 0) {
    $background_picture = '/img/user-sun.jpg';
}

if ($IDUSER === intval($dataView['USR'])) {
    $admin = true;
}

// Translation -------------------------------
$lang_user = $sessionUser->getLanguage();
if (! empty($lang_user)) {
    $lang = $lang_user;
}

$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/profile.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser];
$this->layout('tpl::template', $array);
$this->start('scripts');
echo '<script data-main="/ctrl/profile_public" src="/dist/js/require.js"></script>';
//echo '<link type="text/css" href="/dist/css/croppie.css" rel="stylesheet">';
//echo '<link type="text/css" rel="stylesheet" href="/dist/c/tingle/tingle.min.css">';
//echo '<script type="text/javascript" src="/dist/js/moment.js"></script>';
//echo '<script type="text/javascript" src="/dist/js/doT.js"></script>';
//echo '<script type="text/javascript" src="/dist/js/jquery.socialfeed.js"></script>';
//echo '<link type="text/css" href="/dist/css/jquery.socialfeed.css" rel="stylesheet">';
//echo '<script type="text/javascript" src="/dist/js/flow.min.js"></script>';
$this->stop();
echo $this->insert('partials::profile_public.tpl', ['info' => $t, 'dataView' => $dataView , 'background_picture' => $background_picture, 'picture' => $picture, 'subscription' => $subscription, 'UUID' => $uid, 'admin' => $admin, 'lang' => $lang, 'sessionUser' => $sessionUser, 'params' => $params]);