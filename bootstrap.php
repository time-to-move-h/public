<?php
declare(strict_types=1);
function autoload(string $className) : void {
  if (class_exists($className)) return;    
  if ($className == 'Locale') return;
  //if (! defined('__ROOT__')) define(__ROOT__, dirname(dirname(__FILE__)));
  $rootFolder = __DIR__; //dirname(__FILE__);
  $className = ltrim($className, '\\');
  $fileName  = '';
  //$namespace = '';
  if ($lastNsPos = strripos($className, '\\'))
  {
    $namespace = substr($className, 0, $lastNsPos);
    $className = substr($className, $lastNsPos + 1);
    $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
  }
  $fileName .= $className . '.php';
  // set the path to our source directory, relative to the directory we are in
  $src = $rootFolder . DIRECTORY_SEPARATOR . 'lib';
  $file = $src . DIRECTORY_SEPARATOR . $fileName;
  if (file_exists($file)) {       
      require_once $file;      
  }
//  else {
//      exit("Error bootstrap class Index = " . $file);
//  }
}
spl_autoload_register('autoload');