<?php 
declare(strict_types=1);
$sessionUser->startSession();
$server = new \Moviao\Http\ServerInfo();
//$suffix = $server->getServerSuffix();
//if ($suffix !== 'LOCALHOST') {
//    // https redirection
//    if(! Moviao\Http\ServerInfo::isSecure())
//    {
//        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
//        exit();
//    }
//}

//echo $orderid;
$dataView = null;
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/ticket_details.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
$this->layout('tpl::template', $array);
$this->start('scripts');
echo '<link type="text/css" rel="stylesheet" href="/dist/c/tingle/tingle.min.css">';
echo '<script data-main="/dist/ctrl/ticket_details" src="/dist/js/require.js"></script>';
$this->stop();
echo $this->insert('partials::ticket_details.tpl', ['info' => $t, 'lang' => $lang, 'dataView' => $dataView, 'sessionUser' => $sessionUser, 'ticket_order_id' => $orderid ]);