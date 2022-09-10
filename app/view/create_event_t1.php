<?php
declare(strict_types=1);
try {
    $sessionUser->startSession();
    $sessionUser->checkAuth();

    $isBusinessConfirmed = false;

    // Check Channel if exist before ---------------------------
    $common = new \Moviao\Data\CommonData();
    $common->iniDatabase();
    $common->setSession($sessionUser);

    // Check channel
    if ($common->getDBConn()->connectDBA()) {
        $channel_utils = new \Moviao\Data\Util\ChannelsUtils($common);
        $isChannelExist = $channel_utils->isChannelAdminPresent($sessionUser->getIDUSER());

        if ($isChannelExist == false) {
            if (null !== $common) {
                $common->disconnect();
                unset($common);
            }
            header('Location: /create_channel?new=1');
            exit();
        }

        // Business Auth
        $users_utils = new \Moviao\Data\Util\UsersUtils($common);
        $user_data = $users_utils->getUserSessionInfo($sessionUser->getIDUSER());
        if (! empty($user_data) && strval($user_data["BUSINESS_CONFIRMED"]) === '1') {
            $isBusinessConfirmed = true;
        }
    }
    // ---------------------------------------------------------

    // Translation -------------------------------
    $lang_user = $sessionUser->getLanguage();
    if (! empty($lang_user)) {
        $lang = $lang_user;
    }
    $t = new \JsonI18n\Translate($lang);
    $t->addResource('app/view/templates/trans/create_event_t1.json');
    //--------------------------------------------
    $array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser];
    $this->layout('tpl::template', $array);
    $this->start('scripts');
    echo '<link type="text/css" rel="stylesheet" href="/dist/css/quill.css">';
    //echo '<link type="text/css" rel="stylesheet" href="/dist/css/b/bootstrap-datetimepicker.min.css">';
    echo '<link type="text/css" rel="stylesheet" href="/dist/c/datepicker/css/datepicker.min.css">';
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/leaflet/1/leaflet.css" />';
    echo '<script src="https://cdn.jsdelivr.net/leaflet/1/leaflet.js"></script>';
    echo '<script data-main="/ctrl/create_event_t1" src="/dist/js/require.js"></script>';
    $this->stop();
    echo $this->insert('partials::create_event_t1.tpl', ['info' => $t, 'params' => $params, 'sessionUser' => $sessionUser,'lang' => $lang, 'isBusinessConfirmed' => $isBusinessConfirmed]);
} catch (Error $e) {
    error_log('create_event_t1.php >> ' . $e);
}