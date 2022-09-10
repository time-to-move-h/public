<?php 
declare(strict_types=1);
try {
    $sessionUser->startSession();
    $sessionUser->checkAuth();

    // Translation -------------------------------
    $t = new \JsonI18n\Translate($lang);
    $t->addResource('app/view/templates/trans/pickacity.json');
    //--------------------------------------------
    $array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
    $this->layout('tpl::template', $array);
    $this->start('scripts');
    echo '<script data-main="/ctrl/pickacity" src="/dist/js/require.js"></script>';
    echo '<link type="text/css" href="/dist/css/styles.css" rel="stylesheet">';

    //echo '<link type="text/css" href="/dist/css/parsley/parsley.css" rel="stylesheet">';
    //echo '<link type="text/css" href="/dist/c/tel/css/intlTelInput.css" rel="stylesheet">';
    //echo '<link type="text/css" rel="stylesheet" href="/dist/c/datepicker/css/datepicker.min.css">';

    $this->stop();
    echo $this->insert('partials::pickacity.tpl', ['info' => $t, 'lang' => $lang ]);
} catch (Error $e) {
    error_log('pickacity.php >> ' . $e);
}