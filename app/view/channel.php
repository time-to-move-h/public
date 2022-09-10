<?php
declare(strict_types=1);
//$server = new \Moviao\Http\ServerInfo();
//$suffix = $server->getServerSuffix();
//$haystack = ["ES"];
//if (! in_array($suffix, $haystack)) {
$sessionUser->startSession();
$sessionUser->checkAuth();
//}
$dataView = null;
$dataTags = null;
try {
    if (isset($uid)) {
        $form = new stdClass();
        $form->UID = $uid;
        $channel = new \Moviao\Data\ChannelsCommon();
        $channel->iniDatabase();
        $channel->setSession($sessionUser);
        $result = $channel->show($form);
        //exit(var_dump($form));
        if ($result["result"] === true) {
            $dataView = $result["data"];                        
        }
    } 
} catch (\Error $e) {
    $dataView = null;
    error_log("channel.php : " . $e);
} finally {
    if (null !== $channel) {
        $channel->disconnect();
    }
}

if (empty($dataView)) {
    //exit("empty");
    header("Location: /home");
    exit();
}

if ($dataView['ONLINE'] === '0' && $dataView['ROLE'] !== '1') {
    header("Location: /home");
    exit();
}

//exit(var_dump($dataView['ONLINE']));

$subscription = (isset($dataView['SUBSCRIPTION']) && is_numeric($dataView['SUBSCRIPTION'])) ? (int)($dataView['SUBSCRIPTION']) : -1;
//echo $subscription;

if ((isset($dataView['PICTURE']) && strlen($dataView['PICTURE']) <=0) || empty($dataView['PICTURE'])) {
    $dataView['PICTURE'] = '/img/defaultchannel.jpg';
}
// Translation -------------------------------
$lang_user = $sessionUser->getLanguage();
if (! empty($lang_user)) {
    $lang = $lang_user;
}

$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/channel.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser];
$this->layout('tpl::template', $array);
$this->start('scripts');
echo '<script data-main="/ctrl/channel" src="/dist/js/require.js"></script>';
if ($dataView['ROLE'] === 1) {
    echo '<link type="text/css" rel="stylesheet" href="/dist/css/quill.css">';
    echo '<link type="text/css" href="/dist/css/croppie.css" rel="stylesheet">';
    echo '<link type="text/css" rel="stylesheet" href="/dist/c/tingle/tingle.min.css">';
}
$this->stop();
echo $this->insert('partials::channel.tpl', ['info' => $t, 'dataView' => $dataView, 'subscription' => $subscription, 'uid' => $uid,'sessionUser' => $sessionUser, 'lang' => $lang ]);