<?php
declare(strict_types=1);
// @author Moviao Inc.
// All rights reserved 2018-2019.
// Rpc Entry point for Tickets
//error_reporting(E_ALL);
$methods = null;
$Server = null;
try {
    $methods = new Moviao\Data\TicketsCommon();
    $methods->iniDatabase();
    $methods->setSession($sessionUser);
    $Server = new JsonRpc\Server($methods);
    $Server->receive();
} catch (\Error $ex) {
    error_log("Model >> ticket.php = $ex");
} finally {
    if (null !== $methods) $methods->disconnect();
    unset($methods);
    unset($Server);
    $methods = null;
    $Server = null;
}