<?php
declare(strict_types=1);
// @author Moviao Inc.
// All rights reserved 2017-2018.
// Rpc Entry point for Channels
$methods = null;
$Server = null;
try {
    $methods = new Moviao\Data\ChannelsCommon();
    $methods->iniDatabase();
    $methods->setSession($sessionUser);
    $Server = new JsonRpc\Server($methods);
    $Server->receive();
} catch (\Error $ex) {
    error_log("Model >> channel.php = $ex");
} finally {
  if (null !== $methods) $methods->disconnect();
  unset($methods);
  unset($Server);
}