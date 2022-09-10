<?php
namespace Moviao\Data;
use Moviao\Data\CommonData;
use Moviao\Data\Util\EmailUtils;
use PDO;
use stdClass;
//use function GuzzleHttp\Psr7\str;

$result = false;
$data = null;

if (isset($_POST) && ! empty($_POST) && isset($_POST['message']) && ! empty($_POST['message'])) {

    try {
        //exit(var_dump($_POST['message']));

        $msg = filter_var(mb_substr($_POST['message'], 0, 8000), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $msg = htmlspecialchars($msg);

        // Get User Info
        $commonData = new \Moviao\Data\CommonData();
        $commonData->iniDatabase();
        $data = $commonData->getDBConn();
        // Execute Transaction
        $data->connectDBA();
        $user_utils = new \Moviao\Data\Util\UsersUtils($commonData);
        $user_info = $user_utils->getUserSessionInfo($sessionUser->getIDUSER());

        if (! empty($user_info)) {
            $email_utils = new EmailUtils();
            $params = [];
            $params['NAME'] = htmlspecialchars($user_info['NDISP']);
            $params['EMAIL'] = filter_var($user_info['EMAIL'], FILTER_SANITIZE_EMAIL);
            $params['MESSAGE'] = $msg . '<br>ID : ' . $sessionUser->getIDUSER() . '<br>Ip Address : ' . $_SERVER['REMOTE_ADDR'];
            $address = "info@moviao.com";
            $template = "./app/view/mailing/contact_form.xml";
            $result = $email_utils->sendEmail($address, $template, $params);
         }

    } catch (\Error $e) {
        error_log('contactus : ' . $e);
    } finally {
        if (! empty($data)) {
            $data->disconnect();
        }
    }
    //echo 'mail sent ' . var_dump($result);
}
?>
<div class="container">
<h3><?=$this->e($info->_e('form_title'));?></h3>
<hr>

<?php if ($result) { ?>

    <!-- Success-->
    <div id="sub_success">
        <p>&nbsp;</p>
        <h1 class="h1-responsive green-text"><?=$this->e($info->_e('form_congratulation'));?></h1>
        <p class="lead"><?=$this->e($info->_e('form_success'));?></p>
        <hr class="m-y-2">
        <p><?=$this->e($info->_e('form_confirm'));?></p><br>
        <p class="lead">
            <a href="/home" class="btn btn-primary btn-block" role="button"><?=$this->e($info->_e('form_link_continue'));?></a>
        </p>
    </div>

<?php } else { ?>

    <form id="contact-form" method="post" action="/contactus">
    <div class="messages"></div>
    <div class="controls">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="form_message"><?=$this->e($info->_e('form_field_message'));?></label>
                    <textarea id="form_message" name="message" class="form-control" placeholder="" rows="4" required="required" data-error="Please, leave us a message." maxlength="8000"></textarea>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-12">
                <input type="submit" class="btn btn-success btn-send" value="<?=$this->e($info->_e('form_submit'));?>" >
<!--                onclick='this.disabled = true;'-->
            </div>
        </div>
    </div>
</form>

<?php } ?>

</div>