<?php
declare(strict_types=1);
$usercommon = null;
// redirect if already connected
$sessionUser->startSession();

if ($sessionUser->isValid()) {
    header('Location: /home');
    exit(0);
}

if (! empty($params) && ! empty($params['account']) && ! empty($params['otp'])) {
    //echo var_dump($params);
    try {
        $form = new stdClass();
        $form->ACCOUNT = $params['account'] ?? null;
        $form->PWD_OTP = $params['otp'] ?? null;
        $form->_csrf = $params['_csrf'] ?? null;
        $usercommon = new \Moviao\Data\UsersCommon();
        $usercommon->iniDatabase();
        $usercommon->setSession($sessionUser);
        $result = $usercommon->login_otp_p2($form);
        if ($result['result'] === true) {
            if (null !== $usercommon) {
                $usercommon->disconnect();
            }
            header('Location: /home');
            exit();
        }
    } catch (\Error $e) {
        error_log('signin.php >> ' . $e);
    } finally {
        if (null !== $usercommon) {
            $usercommon->disconnect();
        }
    }
}
//if (null !== $sessionUser && $sessionUser->isValid() === true) {
    // TODO: wait for more information
    //header('Location: /');
    //exit();
//}
$server = new \Moviao\Http\ServerInfo();
$suffix = $server->getServerSuffix();
//echo var_dump($suffix);
if ($suffix !== 'LOCALHOST') {
    // https redirection
    if ((! Moviao\Http\ServerInfo::isSecure()) || $suffix !== 'COM')
    {
        //header('Location: https://www.moviao.com' . $_SERVER['REQUEST_URI']);
        //exit();
    }
}
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/signin.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
$this->layout('tpl::template', $array);
$this->start('scripts');
// Library Google
echo '<script src="https://accounts.google.com/gsi/client"></script>';
echo '<script data-main="/ctrl/signin" src="/dist/js/require.js"></script>';
echo '<link type="text/css" href="/dist/css/parsley/parsley.css" rel="stylesheet">';
echo '<link type="text/css" href="/dist/css/jquery.loading.css" rel="stylesheet">';
$this->stop();
echo $this->insert('partials::signin.tpl', ['info' => $t,'lang' => $lang,'suffix' => $suffix,'params' => $params]);