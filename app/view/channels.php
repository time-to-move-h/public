<?php
declare(strict_types=1);
try {
    //$server = new \Moviao\Http\ServerInfo();
    //$suffix = $server->getServerSuffix();
    //$haystack = ["ES"];
    //if (! in_array($suffix, $haystack)) {
    $sessionUser->startSession();
    $sessionUser->checkAuth();
    //}
    // Translation -------------------------------
    $t = new \JsonI18n\Translate($lang);
    $t->addResource('app/view/templates/trans/channels.json');
    //--------------------------------------------
    $array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser];
    $this->layout('tpl::template', $array);
    $this->start('scripts');
    echo '<script data-main="/ctrl/channels" src="/dist/js/require.js"></script>';
    $this->stop();
    echo $this->insert('partials::channels.tpl', $array);
} catch (Error $e) {
    error_log('channels.php : ' . $e);
}