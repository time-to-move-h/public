<?php 
declare(strict_types=1);
try {
    $sessionUser->startSession();
    $sessionUser->checkAuth();

    // Translation -------------------------------
    $t = new \JsonI18n\Translate($lang);
    $t->addResource('app/view/templates/trans/dashboard.json');
    //--------------------------------------------
    $array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
    $this->layout('tpl::template', $array);
    $this->start('scripts');

    echo '<link type="text/css" href="/dist/css/dashboard.css" rel="stylesheet">';
    echo '<script data-main="/ctrl/dashboard" src="/dist/js/require.js"></script>';
    echo '<link type="text/css" rel="stylesheet" href="/dist/c/tingle/tingle.min.css">';
    //echo '<link type="text/css" href="/dist/css/parsley/parsley.css" rel="stylesheet">';

    $this->stop();
    echo $this->insert('partials::dashboard.tpl', ['info' => $t, 'lang' => $lang, 'sessionUser' => $sessionUser ]);
} catch (Error $e) {
    error_log('dashboard.php >> ' . $e);
}