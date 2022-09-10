<?php 
declare(strict_types=1);
try {
    if ((empty($params) && empty($params['key'])) || $params['key'] !== 'be1389d42dafda61794f45e38dbe4e68') {
        header('Location: /login');
        exit();
    }

    $sessionUser->startSession();
    $server = new \Moviao\Http\ServerInfo();
    $suffix = $server->getServerSuffix();
    if ($suffix !== 'LOCALHOST') {
        // https redirection
        if (! Moviao\Http\ServerInfo::isSecure())
        {
            header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit();
        }
    }
    // Translation -------------------------------
    $t = new \JsonI18n\Translate($lang);
    $t->addResource('app/view/templates/trans/signup.json');
    //--------------------------------------------
    $array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
    $this->layout('tpl::template', $array);
    $this->start('scripts');
    echo '<link type="text/css" href="/dist/css/parsley/parsley.css" rel="stylesheet">';
    echo '<link type="text/css" href="/dist/c/tel/css/intlTelInput.css" rel="stylesheet">';
    echo '<link type="text/css" rel="stylesheet" href="/dist/c/datepicker/css/datepicker.min.css">';
    echo '<script data-main="/dist/ctrl/signup" src="/dist/js/require.js"></script>';
    $this->stop();
    echo $this->insert('partials::signup_otp.tpl', ['info' => $t, 'lang' => $lang, 'suffix' => $suffix ]);
} catch (Error $e) {
    error_log('signup.php >> ' . $e);
}