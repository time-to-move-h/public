<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2020-20201.
// ini_set('display_errors', '0');
try {
    $session_mov = new \Moviao\Session\SessionClass("moviao");
    $session_mov->startSession();
    $session_mov->destroySession();
    $session_mov->stopSession();
    unset($session_mov);

    //$return_data = array();
    //$return_data['success'] = true;
    //exit(json_encode($return_data));
    header('Location: /');
} catch (Exception $e){
    error_log('UsersCommon >> logout : ' . $e);
}
exit(0);