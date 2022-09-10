<?php
declare(strict_types=1);
namespace Moviao\Data\Util;
abstract class BaseUtils {
protected $commonData;
protected $data;
public function __construct(\Moviao\Data\CommonData $commonData) {
    $this->commonData = $commonData;   
    $this->data = $commonData->getDBConn();
}
public function getData() {return $this->commonData;}}