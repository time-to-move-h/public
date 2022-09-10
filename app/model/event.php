<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Rpc Entry point for Events
//error_reporting(E_ALL);
$methods = null;
$Server = null;
try {        
    $methods = new Moviao\Data\EventsCommon();
    $methods->iniDatabase();
    $methods->setSession($sessionUser);
    $Server = new JsonRpc\Server($methods);
    $Server->receive();
} catch (\Error $ex) {
    error_log("Model >> event.php = $ex");
} finally {
  if (null !== $methods) $methods->disconnect();
  unset($methods);
  unset($Server);
  $methods = null;
  $Server = null;
}