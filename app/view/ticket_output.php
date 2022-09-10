<?php 
declare(strict_types=1);
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/ticket_view.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang ];

$ip = $_SERVER['REMOTE_ADDR'];

if ((empty($params['orderid']) && ctype_digit($params['orderid'])) || (empty($params['ticketid']) && ctype_digit($params['ticketid']))) {
    exit();
}

//var_dump($params);
//exit();
$ticket_order_id = filter_var($params['orderid'], FILTER_SANITIZE_NUMBER_INT);
$ticketid = filter_var($params['ticketid'], FILTER_SANITIZE_NUMBER_INT);
$data = null;
$result = array();

try {
    $commonData = new \Moviao\Data\CommonData();
    $commonData->iniDatabase();
    $data = $commonData->getDBConn();
    $data->connectDBA();

    $ticket_order = new \Moviao\Data\Util\TicketsUtils($commonData);
    $order_data = $ticket_order->getOrderDetails($ticket_order_id);

    $form = new \stdClass();
    $form->idorder = $ticket_order_id;
    $form->idticket = $ticketid;
    $result = $ticket_order->getTicketPDF($form);

} catch (\Error $e) {
    error_log('ticket_output: (error) ' . $e->getMessage());
} catch (\Exception $e) {
    error_log('ticket_output: (exception) ' . $e->getMessage());
} finally {
    if (isset($data) && null !== $data) {
        $data->disconnect();
    }
}

//exit('xxx');
//require_once './package/html2pdf/vendor/autoload.php';
//
//use Spipu\Html2Pdf\Html2Pdf;
//use Spipu\Html2Pdf\Exception\Html2PdfException;
//use Spipu\Html2Pdf\Exception\ExceptionFormatter;
//
//try {
//    ob_start();
//    include 'templates/tickets/default.php';
//    $content = ob_get_clean();
//    $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 0);
//    $html2pdf->pdf->SetDisplayMode('fullpage');
//    $html2pdf->writeHTML($content);
//    $html2pdf->output('ticket.pdf');
//} catch (Html2PdfException $e) {
//    $html2pdf->clean();
//    $formatter = new ExceptionFormatter($e);
//    echo $formatter->getHtmlMessage();
//}
include 'templates/tickets/default.php';