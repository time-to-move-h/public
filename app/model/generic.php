<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Rpc Entry point for Generic Data
$methods = null;
$Server = null;
try {
    $methods = new Moviao\Data\GenericCommon();
    $methods->iniDatabase();
    $methods->setSession($sessionUser);
    $Server = new JsonRpc\Server($methods);
    $Server->receive();  
} catch (Exception $ex) {
    error_log("Model >> generic.php = $ex");
} finally {
  if (null !== $methods) $methods->disconnect();
  unset($methods);
  unset($Server);
}
?>