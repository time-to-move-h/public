<?php

$id_token = null;
$access_token = null;

if (isset($_GET['id_token']) && ! empty($_GET['id_token'])) {
    $id_token = $_GET['id_token'];
}

if (isset($_GET['access_token']) && ! empty($_GET['access_token'])) {
    $access_token = $_GET['access_token'];
}

// Test Access Token
//if ($access_token !== '1234') {
//    exit();
//}

$body = @file_get_contents('php://input');

$datetime = date("Y-m-d H:i:s");
$myfile = file_put_contents('logs.txt', $body.PHP_EOL , FILE_APPEND | LOCK_EX);

header('Content-Type: application/websocket-events');
if (substr($body, 0, 6) === "OPEN\r\n") {
    header('Sec-WebSocket-Extensions: grip; message-prefix=""');
    header('Upgrade: websocket');
    header('Connection: Upgrade');
    header('Sec-WebSocket-Accept: s3pPLMBiTxaQ9kYGzzhZRbK+xOo=');

    $sub = True;
    print $body;
} else {
    $sub = False;
}

//$arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
//$data = json_encode($arr);

//$user =  $body; //$data; //'xxx'; // json_decode($data);

if ($sub) {
    $msg = 'c:{"type": "subscribe", "channel": "'.$id_token.'"}';
    print 'TEXT ' . dechex(strlen($msg)) . "\r\n" . $msg . "\r\n";
} else {

    $data = '{"status":"ok"}';
    $input = explode("\n", $body);

    if (count($input)) {

        //$data = $input[1];

        $datajson = json_decode($input[1]);
        if ($datajson) {
            //$data .= $datajson->to;
        try {

            //$datetime = rand(10,100); // date("Y-m-d H:i:s");
            $postRequest = '{ "items": [ { "channel": "' . $datajson->to . '", "formats": { "ws-message": { "content": "'.$datajson->data.'" } } } ] }';
            $cURLConnection = curl_init('http://51.254.123.121:5561/publish');
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
            //curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            $apiResponse = curl_exec($cURLConnection);
            curl_close($cURLConnection);
            // $apiResponse - available data from the API request
            //$jsonArrayResponse - json_decode($apiResponse);

        } catch (Error $e) {
            $error = "error reply";
            $myfile = file_put_contents('error.txt', $error.PHP_EOL , FILE_APPEND | LOCK_EX);
        }

        }
    }

    //$data =  $body;

    print 'TEXT ' . dechex(strlen($data)) . "\r\n" . $data . "\r\n";
    //print $data;
    // Treat the message
    //root@vps213727:~# curl -d '{ "items": [ { "channel": "BD901D8E-0A89-4DFC-938C-F7BE0C302929", "formats": { "ws-message": { "content": "yoo mec tu mentends\n" } } } ] }'  http://localhost:5561/publish

}