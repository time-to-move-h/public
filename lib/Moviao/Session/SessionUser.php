<?php
declare(strict_types=1);
namespace Moviao\Session;

class SessionUser extends SessionClass {
    
    private const IDUSER_NAME = 'IDUSER';
    private const USER_UUID = 'USR_UUID';
    private const ACCTYP_NAME = 'ACCTYP';
    private const LANG_NAME = 'LANG';
    private const LOC_LAT_NAME = 'LOC_LAT';
    private const LOC_LNG_NAME = 'LOC_LNG';
    private const USERACCTYP_NAME = 'USERACCTYP';


    public function __construct() {
        $name = 'moviao';
        parent::__construct($name);
    }  
    
    public function isValid() : bool {
        return parent::sessionExist(self::IDUSER_NAME);
    }
    
    public function checkAuth() : void {        
        if (! parent::sessionExist(self::IDUSER_NAME)) {

            $server = new \Moviao\Http\ServerInfo();
            $server_suffix = $server->getServerSuffix();
            $server_host = $server->getServerHost();

            //exit(var_dump($server_host));

            // if ($server_suffix <> 'LOCALHOST' && $server_host <> 'localhost' && $server_host <> '127.0.0.1' && $server_host <> 'moviao.local' && substr($server_host, 0, 8) !== "192.168.") {
            //     $host = urlencode($server->getServerHost());
            //     $uri = urlencode($server->getServerURI());
            //     header('Location: https://www.moviao.com/auth/sso?d=' . base64_encode($host) . '&r=' . base64_encode($uri));
            // } else {
            header('Location: /login');
            // }

            exit(0);
        }       
    }
    
    public function Authorize() : void {        
        if (! parent::sessionExist(self::IDUSER_NAME)) {
            header('HTTP/1.1 401 Unauthorized', true, 401);
            exit;            
        }       
    }
    
    public function getIDUSER() : ?string {
        return parent::getData(self::IDUSER_NAME);
    }

    public function setIDUSER(string $value) : void {
        parent::setData(self::IDUSER_NAME, $value);
    }

    public function getUSER_UUID() : ?string {
        return parent::getData(self::USER_UUID);
    }

    public function setUSER_UUID(?string $value) : void {
        parent::setData(self::USER_UUID, $value);
    }

    public function getAccountType() : int {
        return (int) parent::getData(self::ACCTYP_NAME);
    }

    public function setAccountType(int $value) : void  {
        parent::setData(self::ACCTYP_NAME, $value);
    }

    public function getUserAccountType() : int {
        return (int) parent::getData(self::USERACCTYP_NAME);
    }

    public function setUserAccountType(int $value) : void  {
        parent::setData(self::USERACCTYP_NAME, $value);
    }
    
    public function getLanguage() : ?string {
        return parent::getData(self::LANG_NAME);
    }

    public function setLanguage(?string $value) : void {
        parent::setData(self::LANG_NAME,$value);
    }

    public function getLatitude() : ?string {
        return parent::getData(self::LOC_LAT_NAME);
    }
    
    public function getLongitud() : ?string {
        return parent::getData(self::LOC_LNG_NAME);
    }    
    
    public function setLatitude(string $loc_lat) {
        return parent::setData(self::LOC_LAT_NAME,$loc_lat);
    }
    
    public function setLongitud(string $loc_lng) {
        return parent::setData(self::LOC_LNG_NAME, $loc_lng);
    }      
}