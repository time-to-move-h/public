<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.
// Rpc Entry point for ComingSoon
$methods = null;
$Server = null;
try {
    $methods = new Moviao\Data\ComingSoonCommon();
    $methods->iniDatabase();
    $methods->setSession($sessionUser);
    $Server = new JsonRpc\Server($methods);
    $Server->receive();
} catch (Exception $ex) {
    error_log("Model >> comingsoon.php = $ex");
} finally {
  if (null !== $methods) $methods->disconnect();
  unset($methods);
  unset($Server);
}
?>