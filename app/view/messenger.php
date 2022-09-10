<?php
declare(strict_types=1);
try {
    $sessionUser->startSession();
    $sessionUser->checkAuth();

    // Translation -------------------------------
    $t = new \JsonI18n\Translate($lang);
    $t->addResource('app/view/templates/trans/messenger.json');
    //--------------------------------------------
    $array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
    $this->layout('tpl::template', $array);
//    $this->start('scripts');
//    echo '<script data-main="/ctrl/contactus" src="/dist/js/require.js"></script>';
      echo '<link type="text/css" href="/dist/css/messenger/messenger.css" rel="stylesheet">';
      echo '<link type="text/css" href="/dist/css/font-awesome.min.css" rel="stylesheet">';

//    echo '<link type="text/css" href="/dist/css/jquery.loading.css" rel="stylesheet">';
//    $this->stop();
    echo $this->insert('partials::messenger.tpl', ['info' => $t,'lang' => $lang, 'sessionUser' => $sessionUser]);
} catch (Error $e) {
    error_log('messenger.php : ' . $e);
}