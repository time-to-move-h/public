<?php
declare(strict_types=1);
namespace Moviao\Data;
class CommonData {
protected $data;
protected $util;
protected $request;
protected $return_errors = array(); // array to hold validation errors
protected $return_data = array(); // array to pass back data    
protected $sessionUser;
function GetContents() : void {
    $postdata = file_get_contents("php://input");   
    $this->request = json_decode($postdata);       
}
public function getRequest() : string {
    return $this->request;
}
public function getError() : int {
    if (isset($this->return_errors['error']) && ($this->return_errors['error'] != null)) {
        return $this->return_errors['error'];
    } else {
        return -1;
    }
}
public function setError(int $err) : void {
    $this->return_errors['error'] = $err;
}
public function getData() : string {
    return $this->return_data;
}
public function setData(string $rdata) : void {
    $this->return_data = $rdata;
}
public function iniDatabase() : void {
    $this->data = new \Moviao\Database\DBApplication();
}
public function setSession(\Moviao\Session\SessionUser $sessionUser) {
    $this->sessionUser = $sessionUser;            
}
public function getSession() : \Moviao\Session\SessionUser {
    return $this->sessionUser;
}
public function disconnect() : void  {
    if (! empty($this->data)) {
        $this->data->disconnect();
    }
}
public function getDBConn() : \Moviao\Database\DBApplication {
    return $this->data;
}
public function getDBUtil() : \Moviao\Database\DataUtils {
    return $this->util;
}}