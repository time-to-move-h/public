<?php 
declare(strict_types=1);
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/404.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
$this->layout('tpl::template', $array);
//$this->start('scripts');
//echo '<script type="text/javascript" src="/dist/js/parsley.min.js"></script>';
//$this->stop();
echo $this->insert('partials::404.tpl', ['info' => $t,'lang' => $lang]);