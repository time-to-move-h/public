<?php 
declare(strict_types=1);
$sessionUser->checkAuth();
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/profiles.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
$this->layout('tpl::template', $array);
echo $this->insert('partials::profiles.tpl', $array);