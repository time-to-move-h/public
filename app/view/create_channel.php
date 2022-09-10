<?php
declare(strict_types=1);
try {
    $sessionUser->startSession();
    $sessionUser->checkAuth();
    // Translation -------------------------------
    $t = new \JsonI18n\Translate($lang);
    $t->addResource('app/view/templates/trans/create_channel.json');
    //--------------------------------------------
    $array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser];
    $this->layout('tpl::template', $array);
    $this->start('scripts');
    echo '<link type="text/css" rel="stylesheet" href="/dist/css/quill.css">';
    echo '<link type="text/css" href="/dist/css/parsley/parsley.css" rel="stylesheet">';
    echo '<script data-main="/ctrl/create_channel" src="/dist/js/require.js"></script>';
    $this->stop();
    echo $this->insert('partials::create_channel.tpl', ['info' => $t, 'params' => $params ]);
} catch (Error $e) {
    error_log('create_channel.php >> ' . $e);
}