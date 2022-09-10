<?php
declare(strict_types=1);

header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=calendar.ics');

use Jsvrcek\ICS\Model\Calendar;
use Jsvrcek\ICS\Model\CalendarEvent;
//use Jsvrcek\ICS\Model\Relationship\Attendee;
//use Jsvrcek\ICS\Model\Relationship\Organizer;

use Jsvrcek\ICS\Utility\Formatter;
use Jsvrcek\ICS\CalendarStream;
use Jsvrcek\ICS\CalendarExport;

try {
    $dataView = null;
    //$allday = false;
    $event = null;

    if (isset($urllink)) {
        $form = new stdClass();
        $form->UID = $urllink;
        $event = new \Moviao\Data\EventsCommon();
        $event->iniDatabase();
        $event->setSession($sessionUser);
        $result = $event->show($form);
        if ($result['result'] === true) {
            $dataView = $result['data'];
        }
    }

    //echo var_dump(strval($dataView['TIMEZONE_BEG']));

    if (empty($dataView)) {
        error_log("ics.php >> dataview error");
    }
    // Date Time --------------------------------------
    $tmz = new \DateTimeZone($dataView['TIMEZONE_BEG']);
    $date_start = new DateTime($dataView['DATBEG_ISO8601'], $tmz);

    //if ($dataView['DATBEG'] !== null) {
        //$datbegin = (string) $dataView['DATBEG'] / 1000;
        //$date_start = new DateTime($datbegin,new \DateTimeZone($dataView['TIMEZONE_BEG']));
        //$date_start = new DateTime('now', $tmz);
        //$date_start->setTimestamp($datbegin);
        //echo var_dump($date_start);
        //exit();
        //    $date_end = null;
        //    if ($dataView['DATEND'] !== null) {
        //        $date_end = new DateTime($dataView['DATEND'],new \DateTimeZone($dataView["TIMEZONE_END"]));
        //    }
        //    //echo var_dump($date_end);
        //
        //    $dateformat = new \Moviao\Util\DateTimeFormat();
        //    if ((isset($dataView['ALLDAY'])) && $dataView['ALLDAY'] === '1') $allday = true;
        //    $datevent_begin = $dateformat->formatDate($date_start,$date_end,$lang,$allday);
    //}
    //-------------------------------------------------
    //$subscription = is_numeric($dataView['SUBSCRIPTION']) ? (int) $dataView['SUBSCRIPTION'] : -1;
    //echo $subscription;
//    if (empty($dataView['PICTURE'])) {
//        $dataView['PICTURE'] = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
//    }
    // Translation -------------------------------
//    $lang_user = $sessionUser->getLanguage();
//    if (! empty($lang_user)) {
//        $lang = $lang_user;
//    }
//    $t = new \JsonI18n\Translate($lang);
//    $t->addResource('app/view/templates/trans/event.json');
    //--------------------------------------------


//echo var_dump($dataView);

$summary = $dataView['TITLE'];

//setup an event
$eventOne = new CalendarEvent();
$eventOne->setStart($date_start)
         ->setSummary($summary)
         ->setDescription(" ")
         ->setUid($urllink);

//add an Attendee
//$attendee = new Attendee(new Formatter());
//$attendee->setValue('moe@example.com')
//    ->setName('Moe Smith');
//$eventOne->addAttendee($attendee);

//set the Organizer
//$organizer = new Organizer(new Formatter());
//$organizer->setValue('heidi@example.com')
//    ->setName('Heidi Merkell')
//    ->setLanguage('de');
//$eventOne->setOrganizer($organizer);

//new event
//$eventTwo = new CalendarEvent();
//$eventTwo->setStart(new \DateTime())
//    ->setSummary('Dentist Appointment')
//    ->setUid('event-uid');

//setup calendar
$calendar = new Calendar();
$calendar->setProdId('-//Moviao//Event Calendar//EN')
         ->addEvent($eventOne)
         ->setTimezone($tmz);
//->addEvent($eventTwo)

//setup exporter
$calendarExport = new CalendarExport(new CalendarStream, new Formatter());
$calendarExport->addCalendar($calendar);

//output .ics formatted text
echo $calendarExport->getStream();

} catch (Throwable $e) {
    error_log('ics.php >> ' . $e);
} finally {
    if (null !== $event) {
        $event->disconnect();
        unset($event);
    }
}