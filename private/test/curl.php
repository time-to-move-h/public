<?php

$data_array =  array(
    "from"        => "info@moviao.com",
    "to"        => "djamil.hammouche@gmail.com",
    "subject"        => "subject of php",
    "message"         => "body of php hahaha"
);

$jsondata = json_encode($data_array);

$ch = curl_init('http://192.168.0.14:9000/kafkaapp/post');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'APIKEY: 111111111111111111111',
    'Content-Type: application/json',
 ));

// execute!
$response = curl_exec($ch);

// close the connection, release resources used
curl_close($ch);

// do anything you want with your response
var_dump($response);






/*
$make_call = callAPI('POST', 'http://localhost:9000/kafkaapp/post', json_encode($data_array));
$response = json_decode($make_call, true);
$errors   = $response['response']['errors'];
$data     = $response['response']['data'][0];

echo "data : " . $response;



function callAPI($method, $url, $data){
    $curl = curl_init();
    switch ($method){
       case "POST":
          curl_setopt($curl, CURLOPT_POST, 1);
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
       case "PUT":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
          break;
       default:
          if ($data)
             $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'APIKEY: 111111111111111111111',
       'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // EXECUTE:
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    return $result;
 }
*/















/*
// set post fields
$post = [
    'to' => 'BD901D8E-0A89-4DFC-938C-F7BE0C302929',
    'password' => 'passuser1',
    'gender'   => 1,
];

$jsondata = json_encode($post);


$ch = curl_init('http://82.169.55.205:8888/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);

// execute!
$response = curl_exec($ch);

// close the connection, release resources used
curl_close($ch);

// do anything you want with your response
var_dump($response);

*/