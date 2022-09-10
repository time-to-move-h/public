<?php 
namespace Moviao\Database\Exception;
use Exception;
class DBException extends \Exception { 
    private $msg;
    function __construct(string $msg) {
        $this->msg = $msg;
    }
}
?>