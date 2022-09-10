<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2021-2022.
// Rpc Entry point for Profiles
$methods = null;
$Server = null;
try {        
    $methods = new Moviao\Data\UsersCommon();
    $methods->iniDatabase();
    $methods->setSession($sessionUser);    
    $Server = new JsonRpc\Server($methods);
    $Server->setLogger("true");
    $Server->receive();    
} catch (Exception $ex) {    
    error_log('Model >> profile.php = ' . $ex);
} finally {
  if (null !== $methods) {
      $methods->disconnect();
  }
  unset($methods);
  unset($Server);
}