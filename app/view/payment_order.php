<?php
declare(strict_types=1);
$sessionUser->startSession();
$server = new \Moviao\Http\ServerInfo();
$suffix = $server->getServerSuffix();
if ($suffix !== 'LOCALHOST') {
    // https redirection
    if(! Moviao\Http\ServerInfo::isSecure())
    {
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        exit();
    }
}

$dataView = NULL;

// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/payment.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
$this->layout('tpl::template', $array);
$this->start('scripts');
//echo '<script data-main="/dist/ctrl/payment" src="/dist/js/require.js"></script>';
$this->stop();
echo $this->insert('partials::payment_order.tpl', ['info' => $t, 'lang' => $lang, 'dataView' => $dataView, 'sessionUser' => $sessionUser, 'params' => $params ]);