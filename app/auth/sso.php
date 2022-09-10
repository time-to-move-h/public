<?php
exit("TODO : SSO Disabled");

//declare(strict_types=1);
//exit(var_dump($params["c"]));

// $server = new \Moviao\Http\ServerInfo();
// $server_suffix = $server->getServerSuffix();
// $server_host = $server->getServerHost();

//if ($server_suffix !== 'LOCALHOST') {
//    $domain = 'www.moviao.com'; // Reference domain for SSO
//} else {
//    $domain = 'localhost';
//}

//if (isset($params["c"]) && ! empty($params["c"])) {
    //setcookie ("MSID", $params["crossdomain"], 0, "/", "www.moviao.be", true);
    //session_regenerate_id();
    // $sessionid = null;
    // $key_b64 = null;
    // $key_dec = null;

//     try {
//         $tokenid = base64_decode($params["c"]);
//         $tokenid = filter_var($tokenid, FILTER_SANITIZE_STRING);
//         $commondata = new \Moviao\Data\CommonData();
//         $commondata->iniDatabase();
//         $user_utils = new \Moviao\Data\Util\UsersUtils($commondata);
//         $commondata->getDBConn()->connectDBA();
//         $return_data = $user_utils->getSessionSSO($tokenid);

//         if (! empty($result_data) && isset($return_data['SESSIONID']) && isset($return_data['KEY'])) {
//             $sessionid = $return_data['SESSIONID'];
//             $key_b64 = $return_data['KEY'];
//             $user_utils->cleanSSO($tokenid);
//         }

//     } catch (\Error $e) {
//         $sessionid = null;
//         error_log('sso error 1 :  ' . $e);
//     } finally {
//         if (null !== $commondata) {
//             $commondata->disconnect();
//         }
//     }

//     if (null !== $sessionid) {

//         session_id($sessionid);
//         session_start();
//         ob_flush();
//         flush();

//         if (isset($params["d"]) && isset($params["r"]) && empty($params["d"]) === false && empty($params["r"]) === false) {

//             $key = base64_decode($key_b64);
//             $domain = filter_var($params["d"], FILTER_SANITIZE_STRING);
//             $uri = filter_var($params["r"], FILTER_SANITIZE_STRING);

//             // Decryption url
//             $crypto = new \Moviao\Security\Crypto();
//             $domain_encrypted = $crypto->safeDecrypt($domain, $key);
//             $uri_encrypted = $crypto->safeDecrypt($uri, $key);

//             //$d = filter_var($d, FILTER_SANITIZE_URL);
//             $uri = filter_var($uri, FILTER_SANITIZE_URL);

//             header('Location: ' . $uri);
//             exit(0);
//         }
//     }

//     $ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_SANITIZE_STRING);
//     error_log('sso session refused : ' . $ip);
//     header('HTTP/1.1 403 Forbidden');
//     exit(403);
// }





// session_start();
// if (isset($_SESSION['IDUSER']) && ! empty($params['IDUSER'])  && isset($params["d"]) && isset($params["r"])) {

//     try {

//         // Domain + URI
//         $domain = base64_decode($params["d"]);
//         $uri = base64_decode($params["r"]);
//         //$suffix = mb_substr(mb_strtolower(\Moviao\Http\ServerInfo::getServerSuffix($domain)),0,3);
//         //    if (empty($suffix)) {
//         //        $suffix = 'com';
//         //    }

//         // Encryption url
//         $crypto = new \Moviao\Security\Crypto();
//         $key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
//         $key_b64 = base64_encode($key);
//         $domain_encrypted = $crypto->safeEncrypt($domain, $key);
//         $uri_encrypted = $crypto->safeEncrypt($uri, $key);

//         // Hash
//         $sessionid = session_id();
//         $h1 = sodium_crypto_generichash(($sessionid . rand(4, 15) . uniqid('',true) .  microtime(true)));
//         $h2 = hash("sha512", $h1);
//         $h3 = base64_encode($h2);

//         $commondata = new \Moviao\Data\CommonData();
//         $commondata->iniDatabase();
//         $user_utils = new \Moviao\Data\Util\UsersUtils($commondata);
//         $commondata->getDBConn()->connectDBA();
//         $user_utils->saveSSO($sessionid, $h2, $key_b64);
//         //$test = $user_utils->getSessionSSO($h2);

//     } catch (\Error $e) {
//       error_log('sso error : $e' . $e);
//     } finally {
//         if (null !== $commondata) {
//             $commondata->disconnect();
//         }
//     }

//     //exit(var_dump($server_host));

//   //  if (substr($server_host, 0, 8) !== "192.168." && $suffix !== 'LOCALHOST') {
//   //      header("Location: https://www.moviao.com/auth/sso?c=" . $h3 . "&d=" . $domain_encrypted . "&r=" . $uri_encrypted); // TODO Verifier la redirection ?! >>> $suffix
//   //      exit(0);
//   //  } else {

//         //if (substr($server_host, 0, 8) === "192.168.") {
//         //    header("Location: http://192.168.0.12/auth/sso?c=" . $h3 . "&d=" . $domain_encrypted . "&r=" . $uri_encrypted);
//         //    exit(0);
//         //} else if ($suffix == 'LOCALHOST') {
//         //    header("Location: http://localhost/auth/sso?c=" . $h3 . "&d=" . $domain_encrypted . "&r=" . $uri_encrypted);
//         //    exit(0);
//         //}
//  //   }

//     header('HTTP/1.1 403 Forbidden');
//     exit(405);

// } else {
//     //echo "pas de session";
//     header("Location: /loginx");
//     exit(0);
// }