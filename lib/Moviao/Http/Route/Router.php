<?php
declare(strict_types=1);
namespace Moviao\Http\Route;
class Router {	
    private $routes = array();    
    private $routes_error = array();
    private const ERROR_404 = 404;
    public function __construct() {}
    private function __clone() {}	    
    public function route(string $pattern, $callback) : void {
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
	    $this->routes[$pattern] = $callback;
    }        
    public function setErrorPage(string $errorcode, $callback) : void {
	    $this->routes_error[$errorcode] = $callback;
    }        
    public function execute(string $url) {
        $url_path = parse_url($url, PHP_URL_PATH);
        if ($url_path === null) $url_path = '';
        $url_get = parse_url($url, PHP_URL_QUERY);
        $parameters = array();
        if (null !== $url_get && is_string($url_get)) {
            parse_str($url_get, $parameters);
        }
        foreach ($this->routes as $pattern => $callback) {
            if (preg_match($pattern, $url_path, $attributes)) {
                array_shift($attributes); // Delete first url element      
                //if (!isset($parameters)) $parameters = array();
                $params = array(2);
                $params[0] = new Request($attributes,$parameters);
                $params[1] = new Response();                                                  
                return call_user_func_array($callback, $params); //array_values($params)                
            }
        }               
        foreach ($this->routes_error as $errorcode => $callback) {            
            if ($errorcode === self::ERROR_404) {                                    
                //if (!isset($parameters)) $parameters = array();
                array_shift($attributes); 
                $params = array(2);
                $params[0] = new Request($attributes,$parameters);
                $params[1] = new Response(); 
                return call_user_func_array($callback, $params);                
            }
        }
    }  
}