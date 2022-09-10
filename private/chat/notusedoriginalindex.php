<?php
session_start();
$body = @file_get_contents('php://input');
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

$user = $_GET['param']; //$body; //$data; //'xxx'; // json_decode($data);
print 'TEXT ' . dechex(strlen($user)) . "\r\n" . $user . "\r\n";

if ($sub) {
    $msg = 'c:{"type": "subscribe", "channel": "'.$user.'"}';
    print 'TEXT ' . dechex(strlen($msg)) . "\r\n" . $msg . "\r\n";
}
?>