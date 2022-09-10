<?php
declare(strict_types=1);
try {
    $sessionUser->startSession();
    $server = new \Moviao\Http\ServerInfo();
    $suffix = $server->getServerSuffix();
    $haystack = ["BE"];

    if (! in_array($suffix, $haystack)) {
        $sessionUser->checkAuth();
    }

    $dataView = null;
    $allday = false;
    $event = null;

    $urllink = null;
    $datbeg_event = null;

    //exit(var_dump($params));

    if (! isset($params) || ! isset($params['u']) || ! isset($params['d'])) {
        error_log("event_ticket.php >> parameters incorrect");
        header("Location: /home");
        exit();
    }

    if (isset($params)) {
        $urllink = mb_substr(filter_var($params['u'],FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,60);
        $datbeg_event = mb_substr(filter_var($params['d'],FILTER_SANITIZE_FULL_SPECIAL_CHARS),0,19);        
    }

    if (! empty($urllink)) {
        $form = new stdClass();
        $form->UID = $urllink;
        $form->DATBEG = $datbeg_event;

        $event = new \Moviao\Data\EventsCommon();
        $event->iniDatabase();
        $event->setSession($sessionUser);
        $result = $event->show($form);
        if ($result['result'] === true) {
            $dataView = $result['data'];
        }
    }

    //exit(var_dump($dataView));
    if (empty($dataView)) {
        error_log("event_ticket.php >> dataview error");
        header("Location: /home");
        exit();
    }

    // Date Time --------------------------------------
    $dateNow = new \DateTime('now',new \DateTimeZone($dataView['TIMEZONE_BEG']));
    $dateNow->settime(0,0);

    //$datevent_begin = '';
    //$datbegin = null;
    $date_start = null;
    $date_end = null;

    if ($dataView['DATBEG_TIMESTAMP'] !== null) {
        $datbegin = $dataView['DATBEG_TIMESTAMP'] / 1000;
        $date_start = new \DateTime();
        $date_start->setTimestamp($datbegin);
        $date_start->setTimezone(new \DateTimeZone($dataView['TIMEZONE_BEG']));
    }

    if ($dataView['DATEND_TIMESTAMP'] !== null) {
        $datend = $dataView['DATEND_TIMESTAMP'] / 1000;
        $date_end = new \DateTime();
        $date_end->setTimestamp($datend);
        $date_end->setTimezone(new \DateTimeZone($dataView['TIMEZONE_END']));
    }

    //-------------------------------------------------
    $subscription = is_numeric($dataView['SUBSCRIPTION']) ? (int) $dataView['SUBSCRIPTION'] : -1;
    //echo $subscription;
    if (empty($dataView['PICTURE'])) {
        $dataView['PICTURE'] = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    }
    // Translation -------------------------------
    $lang_user = $sessionUser->getLanguage();
    if (! empty($lang_user)) {
        $lang = $lang_user;
    }
    $t = new \JsonI18n\Translate($lang);
    $t->addResource('app/view/templates/trans/event.json');
    //--------------------------------------------

    $page_title =  mb_substr(strip_tags($dataView['TITLE']),0,100) . ' - moviao event';
    $array = ['info' => $t, 'lang' => $lang , 'sessionUser' => $sessionUser, 'page_title' => $page_title ];
    $this->layout('tpl::template_empty', $array);
    $this->start('scripts');
    echo '<script data-main="/ctrl/event_tickets" src="/dist/js/require.js"></script>';
    //if ($dataView['ROLE'] === '1') {
        //echo '<link type="text/css" rel="stylesheet" href="/dist/css/quill.css">';
        //echo '<link type="text/css" href="/dist/css/b/bootstrap-datetimepicker.min.css" rel="stylesheet">';
        //echo '<link type="text/css" rel="stylesheet" href="/dist/c/datepicker/css/datepicker.min.css">';
        //echo '<link type="text/css" href="/dist/css/croppie.css" rel="stylesheet">';
        //echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/leaflet/1/leaflet.css" />';
    //}
    echo '<link type="text/css" rel="stylesheet" href="/dist/c/tingle/tingle.min.css">';
    $this->stop();

    echo $this->insert('partials::event_tickets.tpl', ['info' => $t,'lang' => $lang, 'dataView' => $dataView, 'urllink' => $urllink, 'datbeg' => $datbeg_event, 'subscription' => $subscription,'date_start' => $date_start,'date_end' => $date_end, 'dateNow' => $dateNow, 'sessionUser' => $sessionUser, 'allday' => $allday ]);
} catch (Error $e) {
    error_log('event_ticket.php >> ' . $e);
} finally {
    if (null !== $event) {
        $event->disconnect();
    }
}