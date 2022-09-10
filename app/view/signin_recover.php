<?php
declare(strict_types=1);
$sessionUser->startSession();
$valid = false;
if (null !== $sessionUser && $sessionUser->isValid() === true) {
    $valid = true;
}
// Check if the is GET Argument (automated validation)
$a = '';
$b = '';
// Parameters from url for automatic Validation
if (isset($params['a'],$params['b'])) {
    $a = mb_substr(filter_var($params['a'], FILTER_SANITIZE_EMAIL), 0,255);
    $b = filter_var($params['b'], FILTER_SANITIZE_STRING);
    $valid = true;
}
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/signin_recover.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
$this->layout('tpl::template', $array);
$this->start('scripts');
echo '<script data-main="/ctrl/recover" src="/dist/js/require.js"></script>';
echo '<link type="text/css" href="/dist/css/parsley/parsley.css" rel="stylesheet">';
$this->stop();
echo $this->insert('partials::signin_recover.tpl', ['info' => $t, 'valid' => $valid, 'lang' => $lang, 'a' => $a, 'b' => $b, 'sessionUser' => $sessionUser]);