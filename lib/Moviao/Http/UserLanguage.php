<?php
namespace Moviao\Http; 

/**
 * Description of UserLanguage
 *
 * @author MoviaoOne
 */
class UserLanguage {
    
    private $lang_default = null;
    private $lang_arr = null; 
    
    public function __construct(string $lang_default, array $lang_arr) {
        $this->lang_default = $lang_default;
        $this->lang_arr = $lang_arr;
    }
    
    function prefered_language($http_accept_language) : array {
            $langs = array();            
            //exit(var_dump($http_accept_language));            
            preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($http_accept_language), $matches, PREG_SET_ORDER);            
            //exit(var_dump($matches));            
            foreach($matches as $match) {                
                //exit(var_dump($match));                
                list($a, $b) = explode('-', $match[1]) + array('', '');
                $value = isset($match[2]) ? (float) $match[2] : 1.0;
                $langs[$match[1]] = $value;
                $langs[$a] = $value - 0.1;           
            }            
            //exit(var_dump($langs));            
            //arsort($langs);
            return $langs;
    }
    
    public function getUserLanguage() : array {
        $result = array();
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {            
            $result = $this->prefered_language($_SERVER["HTTP_ACCEPT_LANGUAGE"]);           
        }        
        return $result;
    }
    
    public function parseLang(string $lang = null) : string {                
        // Language Selection                
        $lang_iso = $this->lang_default; // Default Language                 
        if (is_null($lang)) { 
            $lang_detected =  $this->getUserLanguage(); // Detect Language    
            
            //exit(var_dump($lang_detected));
            
            foreach($lang_detected as $key => $value)
            {                                 
                $lang_part = substr($key, 0,2);
                $matches = preg_grep("/" . $lang_part . "/", $this->lang_arr);
                if (count($matches) > 0) {               
                    $lang_iso = reset($matches);
                }                                
                break;                
            }                        
        } else if (in_array($lang, $this->lang_arr)) {                                
            $lang_iso = $lang;           
        }        
        return $lang_iso;
    }   
}