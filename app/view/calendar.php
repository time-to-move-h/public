<?php
declare(strict_types=1);
$sessionUser->checkAuth();
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/calendar.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser];
$this->layout('tpl::template', $array);
$this->start('scripts');
echo '<script data-main="/ctrl/calendar" src="/dist/js/require.js"></script>';
//echo '<script type="text/javascript" src="/dist/js/moment.js"></script>';
//echo '<script type="text/javascript" src="/dist/js/calendar/fc.js"></script>';
echo '<link type="text/css" rel="stylesheet" href="/dist/css/fullcalendar/fullcalendar.css" />';
//echo '<script type="text/javascript" src="/dist/js/masonry.min.js"></script>';
$this->stop();
echo $this->insert('partials::calendar.tpl', $array);