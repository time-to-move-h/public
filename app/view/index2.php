<?php 
declare(strict_types=1);
$server = new \Moviao\Http\ServerInfo();
$suffix = $server->getServerSuffix();    

// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/index2.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser ];
$this->layout('tpl::template', $array);
$this->start('scripts');
//echo '<script data-main="/ctrl/index" src="/dist/js/require.js"></script>';
//<script type="text/javascript" src="/dist/js/jquery-3.1.1.min.js"></script>
//<script type="text/javascript" src="/ctrl/modules/ComingSoon.js"></script>
//echo '<script type="text/javascript" src="/dist/js/parsley.min.js"></script>';
//echo '<script type="text/javascript" src="/dist/js/parsley/es.js"></script>';
//echo '<script type="text/javascript" src="/dist/js/jqsobject.js"></script>';
$this->stop();
echo $this->insert('partials::index2.tpl', ['info' => $t,'lang' => $lang]);