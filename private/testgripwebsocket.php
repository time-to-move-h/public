<?php
$body = @file_get_contents('php://input');
header('Content-Type: application/websocket-events');
if (substr($body, 0, 6) === "OPEN\r\n") {
    header('Sec-WebSocket-Extensions: grip; message-prefix=""');

    header('Upgrade: websocket');
    header('Connection: Upgrade');
    header('Sec-WebSocket-Accept: s3pPLMBiTxaQ9kYGzzhZRbK+xOo=');

    $sub = True;
} else {
    $sub = False;
}
print $body;

$msg = 'merci pour ton message';
print 'TEXT ' . dechex(strlen($msg)) . "\r\n" . $msg . "\r\n";

if ($sub) {
    $msg = 'c:{"type": "subscribe", "channel": "test"}';
    print 'TEXT ' . dechex(strlen($msg)) . "\r\n" . $msg . "\r\n";
}
?>