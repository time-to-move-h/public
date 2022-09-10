<?php
declare(strict_types=1);
try {
    $sessionUser->startSession();
    $sessionUser->checkAuth();

    $server = new \Moviao\Http\ServerInfo();
    $suffix = $server->getServerSuffix();
    $haystack = ["BE"];

    if (! in_array($suffix, $haystack)) {
        $sessionUser->checkAuth();
    }

    $dataView = null;
    $allday = false;
    $event = null;
    
    $dataViewAttendees = null;
    $event_attendees = null;
    $datbeg = null;

    if (! empty($urllink)) {

        $form = new stdClass();
        $form->UID = $urllink;
        $event = new \Moviao\Data\EventsCommon();
        $event->iniDatabase();
        $event->setSession($sessionUser);
        $result = $event->show($form);

        if ($result['result'] === true) {
            $dataView = $result['data'];
            //exit(var_dump($dataView));
        }

        // Event Attendees
        $form_attendees = new stdClass();
        $form_attendees->UID = $urllink;
        $form_attendees->DATBEG = $datbeg;
        $form_attendees->LIMIT = 20;
        
        $event_attendees = new \Moviao\Data\UsersCommon();
        $event_attendees->iniDatabase();
        $event_attendees->setSession($sessionUser);
        $result = $event_attendees->getEventMembers($form_attendees);

        if ($result['result'] === true) {
            $dataViewAttendees = $result['data'];            
        }
    }

    //exit(var_dump($dataView));
    if (empty($dataView)) {
        error_log("event.php >> dataview error");
        //header("Location: /");
    }
    // Date Time --------------------------------------
    $dateNow = new \DateTime('now',new \DateTimeZone($dataView['TIMEZONE_BEG']));
    $dateNow->settime(0,0);

    //$datevent_begin = '';
    //$datbegin = null;
    $date_start = null;
    $date_end = null;

    if ($dataView['DATBEG_TIMESTAMP'] !== null && $dataView['DATBEG_TIMESTAMP'] !== 0) {
        $datbegin = $dataView['DATBEG_TIMESTAMP'] / 1000;
        $date_start = new \DateTime();
        $date_start->setTimestamp($datbegin);
        $date_start->setTimezone(new \DateTimeZone($dataView['TIMEZONE_BEG']));
    }

    if ($dataView['DATEND_TIMESTAMP'] !== null && $dataView['DATEND_TIMESTAMP'] !== 0) {
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
    $this->layout('tpl::template', $array);
    $this->start('scripts');
    echo '<script data-main="/ctrl/event" src="/dist/js/require.js"></script>';

    if (strval($dataView['ROLE']) === '1') {
        echo '<link type="text/css" rel="stylesheet" href="/dist/css/quill.css">';
        //echo '<link type="text/css" href="/dist/css/b/bootstrap-datetimepicker.min.css" rel="stylesheet">';
        echo '<link type="text/css" rel="stylesheet" href="/dist/c/datepicker/css/datepicker.min.css">';
        echo '<link type="text/css" href="/dist/css/croppie.css" rel="stylesheet">';
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/leaflet/1/leaflet.css" />';
    }

    echo '<link type="text/css" rel="stylesheet" href="/dist/c/tingle/tingle.min.css">';

    $this->stop();
    echo $this->insert('partials::event.tpl', ['info' => $t,'lang' => $lang, 'dataView' => $dataView, 'dataViewAttendees' => $dataViewAttendees, 'urllink' => $urllink, 'subscription' => $subscription,'date_start' => $date_start,'date_end' => $date_end, 'dateNow' => $dateNow, 'sessionUser' => $sessionUser, 'allday' => $allday ]);
} catch (Error $e) {
    error_log('event.php >> ' . $e);
} finally {
    if (null !== $event) {
        $event->disconnect();
    }
}