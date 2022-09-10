<?php 
declare(strict_types=1);
$sessionUser->startSession();
$sessionUser->checkAuth();
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/ticket_view.json');
//--------------------------------------------
$array = ['info' => $t, 'lang' => $lang ];
//$this->layout('tpl::template', $array);
//echo $this->insert('partials::ticket_details.tpl', ['info' => $t, 'lang' => $lang, 'dataView' => $dataView, 'sessionUser' => $sessionUser, 'ticket_order_id' => $orderid ]);
if ((empty($params['orderid']) && ctype_digit($params['orderid'])) || (empty($params['ticketid']) && ctype_digit($params['ticketid']))) {
    exit();
}
$ticket_order_id = filter_var($params['orderid'], FILTER_SANITIZE_NUMBER_INT);
$ticketid = filter_var($params['ticketid'], FILTER_SANITIZE_NUMBER_INT);
$apiPdfServiceUrl = "http://messenger.moviao.com:8080/convert?auth=u24bRRfjHyDSWvMXZu8x&url=";
$apiParameters = "http://messenger.moviao.com/order/ticket/output?orderid=" . $ticket_order_id . "&ticketid=" . $ticketid;
$urlpdf = $apiPdfServiceUrl . urlencode($apiParameters);
$cURLConnection = curl_init($urlpdf);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, 1 );
$apiResponse = curl_exec($cURLConnection);

if(curl_errno($cURLConnection)){
    echo 'Curl error: ' . curl_error($cURLConnection);
}

curl_close($cURLConnection);
//file_put_contents('ticket.pdf', $apiResponse);
header('Cache-Control: public');
header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="ticket.pdf"');
header('Content-Length: '. strlen($apiResponse));
echo $apiResponse;
exit();