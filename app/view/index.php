<?php
declare(strict_types=1);
$server = new \Moviao\Http\ServerInfo();
$suffix = $server->getServerSuffix();
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/index.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
$this->layout('tpl::template_home', $array);
$this->start('scripts');
echo '<script data-main="/ctrl/index" src="/dist/js/require.js"></script>';
echo '<link href="/dist/c/jqvmap/jqvmap.min.css" media="screen" rel="stylesheet" type="text/css">';
//echo '<script type="text/javascript" src="/dist/js/jquery.js"></script>';
//echo '<script type="text/javascript" src="/dist/c/jqvmap/jquery.vmap.js"></script>';
//echo '<script type="text/javascript" src="/dist/c/jqvmap/maps/jquery.vmap.world.js" charset="utf-8"></script>';
//echo '<script type="text/javascript" src="/dist/c/jqvmap/maps/jquery.vmap.europe.js" charset="utf-8"></script>';
$this->stop();
echo $this->insert('partials::index.tpl', ['info' => $t,'lang' => $lang]);