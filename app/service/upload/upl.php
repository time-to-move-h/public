<?php
declare(strict_types=1);
// @author Moviao Inc. 
// All rights reserved 2017-2018.

//-- Controle Acces --------------
//if (!defined('__ROOT__')) define('__ROOT__', dirname(__FILE__));
//require_once(__ROOT__.'/../../../bootstrap.php');

//$session_mov = new \Moviao\Session\SessionClass("moviao");
//$session_mov->startSession();
//if (! isset($_SESSION["IDUSER"])) {	        
//    header('HTTP/1.0 401 Unauthorized');
//    exit();
//}
//exit(var_dump($sessionUser));
$sessionUser->startSession();
if ((! isset($sessionUser)) || (empty($sessionUser)) || ($sessionUser->isValid() === false)) {
    header('HTTP/1.0 401 Unauthorized', true,401);
    exit();
}
$iduser = $sessionUser->getIDUSER();
$upload_type = 0;
$newWidth = 851;
$newHeight = 315;
// Miniature
$newWidth_mini = 258;
$newHeight_mini = 110;
$picture_loc_mini = null;
$picture_dir = '';
$bresult = false;

try {
$dir_loc = __DIR__;
//Path to autoload.php from current location 
//require_once 'Flow/Autoloader.php';
\Flow\Autoloader::register();
//Path to autoload.php from current location 
$config = new \Flow\Config();
$config->setTempDir($dir_loc . '/chunks_temp_folder');
$file = new \Flow\File($config);

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($file->checkChunk()) {
        header("HTTP/1.1 200 Ok",200);
        exit();
    } else {
        header("HTTP/1.1 204 No Content",204);
        exit();
    }
} else {
  if ($file->validateChunk()) {      
      // Upload Type
      if (isset($_POST['upload_token'])) {
          $upload_type = (int)($_POST['upload_token']);
      }      
      $file->saveChunk();
  } else {
      // error, invalid chunk upload request, retry
      header("HTTP/1.1 400 Bad Request",400);
      exit();
  }
}

function generateUID() : string {
    $t = microtime(true);
    $micro = sprintf("%06d",($t - floor($t)) * 1000000);
    $d = new DateTime(date('Y-m-d H:i:s.'.$micro, (int)($t)));
    return $d->format("YmdHisu");     
}

function generateFile(string $dir) : string {
    $dir_created = false;
    while(true) {
        //$IDUSRIMG = rand(1000,PHP_INT_MAX); // Generation ID Image 
        $uid = generateUID();
        $f = "820828{$uid}.jpg";
        if (! file_exists("$dir$f")) break;
    }
    if (!file_exists($dir)) {
        if (mkdir($dir,0777) && !is_dir($dir)) {
            $dir_created = false;
        }
    }
    return $f;
}

function compressImage(string $dir, string $f,string $f_new = null,int $newWidth,int $newHeight, bool $compress) : bool {
    $result = false;

    try {
        $src = "$dir$f";
        $img = new Imagick();
        $img->setBackgroundColor(new ImagickPixel('transparent'));
        $img->pingImage($src);
        $img->readImage($src);
        $image_info = $img->identifyImage();
        if (!empty($image_info)) {
            $format = $image_info['mimetype'];
            $format_accepted = array('image/jpeg', 'image/png', 'image/gif');

            if ($compress === true && in_array($format, $format_accepted)) {
                if ($format === 'image/jpeg') {
                    $img->setImageCompression(imagick::COMPRESSION_JPEG); // COMPRESSION_JPEG
                } else {
                    $img->setImageCompression(imagick::COMPRESSION_UNDEFINED);
                }
                $img->setImageCompressionQuality(95);
            }
            $img->setImageFormat("jpg");

            // Strips an image of all profiles and comments
            /*
                StripImage also delete ICC image profile by default.
                The resulting images seem to lose a lot of color information and look "flat" compared to their non-stripped versions.

                Consider keeping the ICC profile (which causes richer colors) while removing all other EXIF data:

                1. Extract the ICC profile
                2. Strip EXIF data and image profile
                3. Add the ICC profile back
            */

            $profiles = $img->getImageProfiles("icc", true);
            $img->stripImage();
            if(!empty($profiles)){
                $img->profileImage("icc", $profiles['icc']);
            }

            $img->resizeImage($newWidth, $newHeight, imagick::FILTER_TRIANGLE, 0.9, false); //FILTER_LANCZOS
            $img->cropImage($newWidth, $newHeight, 0, 0);

            if (null !== $f_new) {
                $src = "$dir$f_new";
            }

            $result = $img->writeImage($src);

            $img->clear();
            $img->destroy();
        }
    } catch (\ImagickException $e) {
        error_log("ImagickException Compress : " . $e);
    }

    return $result;
}

//-- Folder Control --------------------------------------------------------
if ($upload_type === 8247 || $upload_type === 9787) {
    // Users
    $dir = './img/users/' . $iduser . '/'; // $dir_loc
    // Generation FILE ---------------------------------------------------------         
    $f = generateFile($dir);        
    //--------------------------------------------------------------------------
    $picture_dir = '/img/u/' . $iduser .'/';
    $picture_loc = 'https://' .  $_SERVER['HTTP_HOST'] . $picture_dir . $f;
    if ($upload_type === 8247) {
        $newWidth = 851;
        $newHeight = 315;
    } else if ($upload_type === 9787) {
        $newWidth = 128;
        $newHeight = 128;
    }
} elseif ($upload_type === 5471 || $upload_type === 6356) {
    // Channels ----------------------------------------------------------------
    $channel_name = "";
    if (isset($_POST['uid'])) {
        $str = filter_var($_POST['uid'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH);
        $channel_name = preg_replace("/[^A-Za-z0-9?![:space:]]/","", $str); // Filter az
    }
    if (mb_strlen($channel_name) <= 0) {
        exit(json_encode(['success' => false ]));
    }
    $dir = './img/channels/' . $iduser . '/';
    // Generation FILE ---------------------------------------------------------
    $f = generateFile($dir);
    //--------------------------------------------------------------------------
    $picture_dir = '/img/c/' . $iduser .'/';
    $picture_loc = 'https://' .  $_SERVER['HTTP_HOST'] . $picture_dir . $f;
    if ($upload_type === 5471) {
        $newWidth = 851;
        $newHeight = 315;
    } else if ($upload_type === 6356) {
        $newWidth = 128;
        $newHeight = 128;
    }
} elseif ($upload_type === 6684) {
    // Events ------------------------------------------------------------------
    $event_name = "";
    if (isset($_POST['uid'])) {        
        $event_name = filter_var($_POST['uid'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH); //preg_replace("/[^A-Za-z0-9?![:space:]]/","", $str); // Filter az                  
    }      
    if (empty($event_name)) {
        exit(json_encode(['success' => false ]));
    }        
    $dir = './img/events/' . $iduser . '/'; // $dir_loc
    // Generation FILE ---------------------------------------------------------         
    $f = generateFile($dir);
    //--------------------------------------------------------------------------
    $picture_dir = '/img/e/' . $iduser .'/';
    $picture_loc = 'https://' .  $_SERVER['HTTP_HOST'] . $picture_dir . $f;
    if ($upload_type === 6684) {
        $newWidth = 851;
        $newHeight = 315;
    }
} elseif ($upload_type === 9966) {
    // Feed Post Picture     
    //    $dir = './img/feeds/' . $IDUSER . '/'; //$dir_loc
    //    // Generation FILE ---------------------------------------------------------
    //    $f = generateFile($dir);
    //    //--------------------------------------------------------------------------
    //    $picture_loc = '/img/f/' . $IDUSER .'/' . $f;
    //    $bresult = true;
} else {
    header('HTTP/1.0 400 Bad Request',400);
    exit();
}
//--------------------------------------------------------------------------

// File upload was completed
if ($file->validateFile() && $file->save("$dir$f")) {

    $data = NULL;    
    //exit("$dir$f");
    try {
        // Compress image and remove metadata
        compressImage($dir, $f,null,$newWidth,$newHeight, true);
        // Create a miniature image
        $f_mini = null;
        if ($upload_type !== 9787) {
            $f_mini = "{$newWidth_mini}_{$newHeight_mini}_" . $f;
            $picture_loc_mini = 'https://' .  $_SERVER['HTTP_HOST'] . $picture_dir . $f_mini;
            //exit(var_dump($f_new));
            compressImage($dir, $f,$f_mini,$newWidth_mini,$newHeight_mini, false);
        }

        if ($upload_type !== 9966) {
            // Instanciation
            $commonData = new \Moviao\Data\CommonData();
            $commonData->iniDatabase();
            $data = $commonData->getDBConn();
            // Execute Transaction
            $data->connectDBA(); 
            $data->startTransaction();
            if ($upload_type === 8247) {
                // Image Background User
                $user_utils = new \Moviao\Data\Util\UsersUtils($commonData);
                $bresult = $user_utils->updateBackgroundImageProfile($iduser,$picture_loc,$picture_loc_mini);
            } elseif ($upload_type === 9787) {
                // Image Profile User
                $user_utils = new \Moviao\Data\Util\UsersUtils($commonData);
                $bresult = $user_utils->updateImageProfile($iduser,$picture_loc);
            } elseif ($upload_type === 5471) {
                 // Image Background Channel
                $channel_utils = new \Moviao\Data\Util\ChannelsUtils($commonData);
                $bresult = $channel_utils->updateBackgroundImage($channel_name,$iduser,$picture_loc,$picture_loc_mini);
            } elseif ($upload_type === 6356) {
                // Image Avatar Channel
                $channel_utils = new \Moviao\Data\Util\ChannelsUtils($commonData);
                $bresult = $channel_utils->updateAvatarImage($channel_name,$iduser,$picture_loc);
            } elseif ($upload_type === 6684) {
                 // Image Background Event
                $event_utils = new \Moviao\Data\Util\EventsUtils($commonData);                
                $bresult = $event_utils->updateBackgroundImage($event_name,$iduser,$picture_loc,$picture_loc_mini);

//                ob_start();
//                var_dump($bresult);
//                $value = ob_get_clean();
//                error_log("upload_type result : $event_name, $iduser, $picture_loc, $picture_loc_mini");
            } 

        }
    } catch (\Exception $e) {
        error_log("Exception internal : $e");
    } finally {

        // End Transaction ---------
        if ($bresult === true) {
            if (null !== $data) {
                $data->commitTransaction();
            }
        } else {
            if (null !== $data) {
                $data->rollbackTransaction();
            }
            unlink($dir.$f);
        }

        if (null !== $data) {
           $data->disconnect();
       }
    }
    //-------------------------        
    echo json_encode([
        'success' => $bresult,
        //'files' => $_FILES,
        //'get' => $_GET,
        'post' => ["upload_token" => $_POST["upload_token"]],
        //optional
        //'flowTotalSize' => isset($_FILES['file']) ? $_FILES['file']['size'] : $_GET['flowTotalSize'],
        'flowIdentifier' => $picture_loc // isset($_FILES['file']) ? $_FILES['file']['name'] . '-' . $_FILES['file']['size'] : $_GET['flowIdentifier'],
        //'flowFilename' => isset($_FILES['file']) ? $_FILES['file']['name'] : $_GET['flowFilename'],
        //'flowRelativePath' => isset($_FILES['file']) ? $_FILES['file']['tmp_name'] : $_GET['flowRelativePath']
    ]);
}
//else {
// This is not a final chunk, continue to upload
//}
} catch (\Exception $e) {
    error_log("upl.php : $e");
}