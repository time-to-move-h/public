<?php

try {


 //   $txt = "user id date";
 //   $myfile = file_put_contents('logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);


//     BD901D8E-0A89-4DFC-938C-F7BE0C302929

//     6EF5D18F-A4B5-4573-9C1D-BE3CE1F4CE1F

$postRequest = '{ "items": [ { "channel": "BD901D8E-0A89-4DFC-938C-F7BE0C302929", "formats": { "ws-message": { "content": "test api\n" } } } ] }';
$cURLConnection = curl_init('http://51.254.123.121:5561/publish');
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
//curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYHOST, false);
$apiResponse = curl_exec($cURLConnection);
//if(curl_exec($cURLConnection) === false)
//{
//    echo 'Curl error: ' . curl_error($cURLConnection);
//}
//else
//{
//    echo 'Operation completed without any errors';
//}
curl_close($cURLConnection);
$jsonArrayResponse = json_decode($apiResponse);
//echo $jsonArrayResponse;




$postRequest = '{ "items": [ { "channel": "6EF5D18F-A4B5-4573-9C1D-BE3CE1F4CE1F", "formats": { "ws-message": { "content": "test api\n" } } } ] }';
$cURLConnection = curl_init('http://51.254.123.121:5561/publish');
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
//curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYHOST, false);
$apiResponse = curl_exec($cURLConnection);
//if(curl_exec($cURLConnection) === false)
//{
//    echo 'Curl error: ' . curl_error($cURLConnection);
//}
//else
//{
//    echo 'Operation completed without any errors';
//}
curl_close($cURLConnection);
$jsonArrayResponse = json_decode($apiResponse);
//echo $jsonArrayResponse;



} catch (\Error $e) {
    exit('ahhhhhhhhhh' );
    echo "error";
}