<?php
declare(strict_types=1);
namespace Moviao\Data;
use Moviao\Data\CommonData;
use Moviao\Data\Util\EmailUtils;
use PDO;
use stdClass;

$result = false;

try {

    include '../bootstrap.php';

    $email_utils = new \Moviao\Data\Util\EmailUtils();
    $params = [];
    $params['EMAIL'] = 'djamil.hammouche@gmail.com';
    $address = "djamil.hammouche@gmail.com";
    $template = "./app/view/mailing/contact_form.xml";
    //$result = $email_utils->sendEmail($address, $template, $params);

    $result = $email_utils->sendEmail2("info@moviao.com", "djamil.hammouche@gmail.com","subject messenger test", "hahaha");

} catch (\Error $e) {
    error_log('testmail : ' . $e);
}

//$result = mail ("djamil.hammouche@gmail.com", "werwerw","message","Content-type:text/plain; charset = UTF-8\r\nFrom:info@moviao.com");

echo 'mail sent ' . var_dump($result);