<?php
declare(strict_types=1);
namespace Moviao\Http\Route;
class Response {
    private $img_types = ['GIF'=> 'image/gif','PNG'=> 'image/png','JPEG'=> 'image/jpeg','JPG'=> 'image/jpeg', 'ICO' => 'image/x-icon'];

    const IMAGE_GIF = 'Content-type: image/gif';
    const IMAGE_PNG = 'Content-type: image/png';
    const IMAGE_JPG = 'Content-type: image/jpeg';
    const IMAGE_ICO = 'Content-type: image/x-icon';
    const IMAGE_ANY = 'Content-type: image';
    const TEXT_PLAIN = 'Content-type: text/plain';

    const APPLICATION_JS = 'Content-type: application/javascript; charset=utf-8';
    const TEXT_CSS = 'Content-type: text/css';
    const FONT_WOFF2 = 'Content-type: font/woff2';
    const APPLICATION_JSON = 'Content-type: application/json';

    /**
     * Render php page
     * @param string $file
     * @param array|null $array
     */
    public function render(string $file,array $array=null,string $header=null) : void {
        if (file_exists($file)) {
            if (! empty($array)) {
                extract($array, EXTR_SKIP);
            }
            if (! is_null($header)) {
                header($header);
            }
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            //header("Cache-Control: max-age=30480");
            //header("Cache-Control: max-age=2592000");
            require_once($file);
        } else {
            header("HTTP/1.0 404 Not Found");
        }
        exit();
    }

    /**
     * Render any file
     * @param string $file
     */
    public function renderFile(string $file,string $header=null) : void {
        if (file_exists($file)) {
            if (! is_null($header)) {
                header($header);
            }
            readfile($file);
        } else {
            header("HTTP/1.0 404 Not Found");
        }
        exit();
    }

    public function renderData(string $data) : void {
       exit($data);
    }

//    public function renderImage(string $file) : void {
//        $ext = explode('.', $file); // Extension Extract
//        $content_type = $this->getContentType($ext[1]);
//
//        if (file_exists($file)) {
//            //exit($content_type);
//            header("Content-type: " . $content_type);
//        }
//        $this->renderFile($file);
//    }
//
//    public function renderVideo(string $file) : void {
//        if (file_exists($file)) header("Content-type: video/mp4");
//        $this->renderFile($file);
//    }
//
//    public function renderCSS(string $file) : void {
//        if (file_exists($file)) header("Content-type: text/css");
//        $this->renderFile($file);
//    }
//
//    public function renderWoff2(string $file) : void {
//        if (file_exists($file)) header("Content-type: font/woff2");
//        $this->renderFile($file);
//    }
//
//    public function renderEot(string $file) : void {
//        if (file_exists($file)) header("Content-type: application/vnd.ms-fontobject");
//        $this->renderFile($file);
//    }
//
//    public function renderTTF(string $file) : void {
//        if (file_exists($file)) header("Content-type: application/font-sfnt");
//        $this->renderFile($file);
//    }
//
//    public function renderJS(string $file,array $array=null) : void {
//        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
//        header("Cache-Control: post-check=0, pre-check=0", false);
//        header("Pragma: no-cache");
//        //header("Cache-Control: max-age=604800");
//        if (file_exists($file)) header("Content-type: application/javascript; charset=utf-8");
//        $this->renderFile($file,$array);
//    }
//
//    public function renderJson(string $file,array $array=null) : void {
//        if (file_exists($file)) header("Content-type: application/json");
//        $this->render($file,$array);
//    }
        
    public function getImageContentType(string $type) : string {
        $content_type = self::IMAGE_ANY;
        foreach ($this->img_types as $key => $value) {            
            if ($key == strtoupper($type)) {
                switch ($value) {
                    case "GIF":
                        $content_type = self::IMAGE_GIF;
                        break;
                    case "PNG":
                        $content_type = self::IMAGE_PNG;
                        break;
                    case "JPG":
                        $content_type = self::IMAGE_JPG;
                        break;
                    case "JPEG":
                        $content_type = self::IMAGE_JPG;
                        break;
                    case "ICO":
                        $content_type = self::IMAGE_ICO;
                        break;
                    default:
                        $content_type = self::IMAGE_ANY;
                        break;
                }
                break;
            }            
        }
        return $content_type;            
    }

}