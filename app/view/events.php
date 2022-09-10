<?php
declare(strict_types=1);
$user = null;
try {
    // Session
    //$server = new \Moviao\Http\ServerInfo();
    //$suffix = $server->getServerSuffix();
    //$haystack = ["ES"];
    //if (! in_array($suffix, $haystack)) {

    $sessionUser->startSession();

    //var_dump($sessionUser->getIDUSER());
    //exit(0);

    $sessionUser->checkAuth();

    $user = new \Moviao\Data\UsersCommon();
    $user->iniDatabase();
    $user->setSession($sessionUser);

    // Pick A City
    if ($user->getDBConn()->connectDBA()) {        
        // TODO: Reactivate when you will use a location
        // $user_utils = new \Moviao\Data\Util\UsersUtils($user);
        // $isUserPreferenceConfigured = $user_utils->isUserPreferenceConfigured($sessionUser->getIDUSER());
        // if (($isUserPreferenceConfigured == false) && (empty($search_loc) && empty($search_lat) && empty($search_lon) && empty($search_rad))) {
        //     if (null !== $user) {
        //         $user->disconnect();
        //         unset($user);
        //     }
        //     header('Location: /pickacity');
        //     exit();
        // }
    }

    //
    //}
    // Translation -------------------------------
    $lang_user = $sessionUser->getLanguage();
    if (! empty($lang_user)) {
        $lang = $lang_user;
    }
    $t = new \JsonI18n\Translate($lang);
    $t->addResource('app/view/templates/trans/events.json');
    //--------------------------------------------

    $array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser,'params' => $params];
    $this->layout('tpl::template', $array);
    $this->start('scripts');
    //echo '<link type="text/css" rel="stylesheet" href="/dist/c/easydropdown/css/beanstalk.css">';
    echo '<script data-main="/ctrl/events" src="/dist/js/require.js"></script>';
    $this->stop();
    echo $this->insert('partials::events.tpl', $array);
    
} catch (Error $e) {
    error_log('events.php : ' . $e);
} finally {
    if (null !== $user) {
        $user->disconnect();
        unset($user);
    }
}