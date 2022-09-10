<?php 
declare(strict_types=1);
//$sessionUser->startSession();
// Check if the is GET Argument (automated validation)
$subscribed = false;
// Parameters from url for automatic Validation
if (isset($params) && isset($params["a"]) && isset($params["c"])) {
    $a = substr(filter_var($params["a"], FILTER_SANITIZE_EMAIL), 0,255);
    $c = filter_var($params["c"], FILTER_SANITIZE_NUMBER_INT);    
    if (filter_var($a, FILTER_VALIDATE_EMAIL) && (!filter_var($c, FILTER_VALIDATE_INT) === false)) {
        $form = new stdClass();
        $form->ACCOUNT = $a;
        $form->CODE = $c;        
        // Request validation new account
        try {             
            //require('bootstrap.php');
            $user = new \Moviao\Data\UsersCommon();            
            $user->iniDatabase();
            $user->setSession($sessionUser);
            $array = $user->validate($form); 
            //exit(var_dump($array));
            if (is_array($array)) {
                if ($array['result'] == true) {
                    $subscribed = true;
                }
            }            
        } catch (Exception $ex) {
            error_log('signup_validation >> validate : ' . $ex);
        }        
    }      
}
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/signup_validation.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
$this->layout('tpl::template', $array);
$this->start('scripts');
echo '<script data-main="/ctrl/signup_validation" src="/dist/js/require.js"></script>';
//echo '<script type="text/javascript" src="/dist/js/parsley.min.js"></script>';
//echo '<script type="text/javascript" src="/dist/js/parsley/es.js"></script>';
//echo '<script type="text/javascript" src="/dist/js/jqsobject.js"></script>';
$this->stop();
echo $this->insert('partials::signup_validation.tpl', ['info' => $t, 'subscribed' => $subscribed, 'lang' => $lang, 'params' => $params]);