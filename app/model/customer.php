<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2020-2021.
// Rpc Entry point for Customer
$methods = null;
$Server = null;
try { 
    $methods = new Moviao\Data\CustomerCommon();
    $methods->iniDatabase();
    $methods->setSession($sessionUser);
    $Server = new JsonRpc\Server($methods);
    $Server->receive();
} catch (Exception $ex) {
    error_log("Model >> customer.php = $ex");
} finally {
  if ($methods != null) {
      $methods->disconnect();
  }
  unset($methods);
  unset($Server);
}