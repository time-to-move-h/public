<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

$phone = $_POST['to'];
$text = $_POST['message'];

$api_key = '5f8dd809';
  if (isset($enviar)) {

      $url = 'http://192.168.2.46:8082';

      $data = array(
          'to' => $phone,
          'message' => $text,
      );

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
      curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
              "Authorization: " . $api_key
          )
      );
  }

$response = curl_exec($curl);
$obj = json_decode($response, true);
var_dump($response);

}
?>


<html>
<body>

<form method="post" action="http://192.168.2.46:8082">
    To: <input id="to" name="to" type="text"><br />
    Message: <input id="message" name="message" type="text"><br />
    <input type="submit">
</form>

</body>
</html>