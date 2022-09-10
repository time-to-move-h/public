<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2020-2021.
// Rpc Entry point for Messenger
//error_reporting(E_ALL);
$methods = null;
$Server = null;
try {        
    $methods = new Moviao\Data\MessengerCommon();
    $methods->iniDatabase();
    $methods->setSession($sessionUser);
    $Server = new JsonRpc\Server($methods);
    $Server->receive();
} catch (\Error $e) {
    error_log("Model >> messenger.php = $e");
} finally {
  if (null !== $methods) {
      $methods->disconnect();
  }
  unset($methods);
  unset($Server);
  $methods = null;
  $Server = null;
}