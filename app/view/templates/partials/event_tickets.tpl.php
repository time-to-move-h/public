<?php
declare(strict_types=1);
$csrf_token = null;
$admin_role = false;
//$csrf = new \Moviao\Security\CSRF_Protect();
//$csrf_token = $csrf->echoInputField();
//$authenticated = isset($sessionUser) && $sessionUser->isValid();

//$this->insert('modules::tpl_tickets', ['sessionUser' => $sessionUser, 'info' => $info, 'lang' => $lang]);
//$this->insert('modules::form_event_tickets',['sessionUser' => $sessionUser, 'urllink' => $urllink, 'dataView' => $dataView, 'csrf_token' => $csrf_token, 'lang' => $lang, 'display' => 'block']);
?>

<input type="hidden" id="uid" name="UID"  value="<?php echo $urllink;?>">
<input type="hidden" id="uid" name="DATBEG"  value="<?php echo $datbeg;?>">



<div style="padding: 35px;">
<?php
//$this->insert('modules::tpl_tickets', ['sessionUser' => $sessionUser, 'info' => $info, 'lang' => $lang]);
//$this->insert('modules::form_event_tickets',['sessionUser' => $sessionUser, 'urllink' => $urllink, 'dataView' => $dataView, 'csrf_token' => $csrf_token, 'lang' => $lang, 'display' => 'block']);

$this->insert('modules::tpl_tags',['sessionUser' => $sessionUser]);
$this->insert('modules::tpl_tickets', ['sessionUser' => $sessionUser, 'info' => $info, 'lang' => $lang]);
$this->insert('modules::form_event_tickets',['sessionUser' => $sessionUser, 'urllink' => $urllink, 'datbeg' => $datbeg, 'dataView' => $dataView, 'csrf_token' => $csrf_token, 'lang' => $lang, 'display' => 'block','action' => '/checkout?v=m', 'width' => '90%']);

if ($admin_role === true) {
    $this->insert('modules::tpl_upload_modal', ['sessionUser' => $sessionUser, 'info' => $info, 'lang' => $lang]);
    $this->insert('modules::form_event_edit_tags', ['sessionUser' => $sessionUser, 'urllink' => $urllink, 'info' => $info]);
    $this->insert('modules::form_event_edit_desc', ['sessionUser' => $sessionUser, 'urllink' => $urllink, 'info' => $info, 'dataView' => $dataView, 'csrf_token' => $csrf_token]);
    $this->insert('modules::form_event_edit_dates', ['sessionUser' => $sessionUser, 'urllink' => $urllink, 'dataView' => $dataView, 'csrf_token' => $csrf_token, 'lang' => $lang]);
    $this->insert('modules::tpl_tickets_edit', ['sessionUser' => $sessionUser, 'info' => $info, 'lang' => $lang]);
    $this->insert('modules::form_event_create_ticket',['sessionUser' => $sessionUser, 'urllink' => $urllink, 'dataView' => $dataView, 'csrf_token' => $csrf_token, 'lang' => $lang]);
}
?>
</div>
<script>

 // function codeAddress() {
 //     alert('loading');
 //
 //     require(["./common"],function(e){
 //
 //         console.log(e);
 //
 //         require(["app/event"], function(app) {
 //             console.log("abc");
 //             console.log(app);
 //             //app.evt.loadTicketsView();
 //         });
 //
 //     });
 //
 //
 // }
 //
 // window.onload = codeAddress;

</script>