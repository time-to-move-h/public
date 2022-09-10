<?php
declare(strict_types=1);
$user_picture = '/img/user-default.png';
$subscribers_counter = 0;

if (! empty($dataView['COUNTER_FOLLOWERS'])) {
    $subscribers_counter = filter_var($dataView['COUNTER_FOLLOWERS'], FILTER_SANITIZE_NUMBER_INT);
}

// Avatar User
if (! empty($dataView['PICTURE_RND'])) {
    $user_picture = $dataView['PICTURE_RND'];
}
//exit(var_dump($dataView));
// Background cover
$user_background = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
if (! empty($dataView["PICTURE"])) {
    $user_background = $dataView["PICTURE"];
}
$csrf = null;
$admin_role = ((isset($sessionUser) && $sessionUser->isValid()) && $sessionUser->getIDUSER() === strval($dataView['USR']) && $dataView['ROLE'] === 1);
if ($admin_role === true) {
    $csrf = new \Moviao\Security\CSRF_Protect();
    $csrf_token = $csrf->echoInputField();
}
//exit(var_dump($admin_role))
?>
<input type="hidden" id="UID" value="<?php echo $uid; ?>">
<!--<input type="hidden" name="subscribed" id="subscribed" value="--><?php ////echo $subscription; ?><!--">-->
<!-- Top Header -->
<div class="container">
    <div class="padding-box">

        <!-- Online/Offline Status  -->
        <?php 
        
        //exit(var_dump($dataView['ROLE']));
        
        if (($dataView['ROLE'] === 1) && ($dataView['ONLINE'] === 0)) { ?>
            <div id="panel-pub" style="width: 100%; border: 2px solid red; margin-bottom: 25px; padding: 5px">
                <span class="text-center"><h4><?=$this->e($info->_e('info_publish'));?></h4></span>
                <div class="text-center">
                    <button class="btn btn-primary btn-lg" id="publish" name="publish" type="submit"><?=$this->e($info->_e('btn_publish'));?></button>
                </div>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div>
                    <div class="top-header">
                        <div class="top-header-thumb">
                            <img id="back_img" class="event_img_background img-fluid" src="<?php echo $user_background; ?>" onerror="this.onerror=null;this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';" alt="Background Image">
                        </div>
                        <?php //if ($admin_role === false) { ?>
                            <!-- Button Participate -->
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <ul class="profile2-menu">
                                        <li><button id="btn_event_view_panel" class="btn btn-sm btn-outline-primary active" type="button"><?=$this->e($info->_e('menu_events'));?></button></li>
                                        <li><button id="btn_descl_view_panel" class="btn btn-sm btn-outline-primary" type="button"><?=$this->e($info->_e('info_title_descl'));?></button></li>
                                        <li><button id="btnsubscribe" class="btn btn-sm btn-primary float-right" type="button" <?php if ($subscription !== -1) echo 'style="display: none;"'; ?>><?=$this->e($info->_e('btn_subscribe'));?></button></li>
<!--                                        <li><button id="btnunsubscribe" class="btn btn-sm btn-secondary float-right" type="button" <?php //if ($subscription !== 1) echo 'style="display: none;"'; ?><?//=$this->e($info->_e('btn_unsubscribe'));?> </button></li>-->
                                        <li><button id="btnwaitconfirm" class="btn btn-sm btn-secondary float-right" type="button" <?php if ($subscription !== 0) echo 'style="display: none;"'; ?>><?=$this->e($info->_e('btn_waitsubscribe'));?></button></li>
                                        <li>
                                            <div class="private-event-head inline-items pad-bottom">
                                                <div class="more">
                                                    <img src="/img/icons/icons8-settings.svg" border="0" width="24px" height="24px">
<!--                                                    <svg class="olymp-three-dots-icon"><use xlink:href="/img/i/icons.svg#olymp-three-dots-icon"></use></svg>-->
                                                    <ul class="more-dropdown more-with-triangle">
                                                        <?php if ($admin_role === true) { ?>
                                                            <li><a id="btn_edit_background" href="#" data-token="5471"><?=$this->e($info->_e('menu_img_background'));?></a></li>
                                                            <li><a id="btn_edit_img_round" href="#" data-token="6356"><?=$this->e($info->_e('menu_img_round'));?></a></li>
                                                            <li><a id="btn_edit_desc" href="#"><?=$this->e($info->_e('menu_edit_desc'));?></a></li>
                                                            <li><a href="/create_event?c=<?php echo urlencode($dataView['NAME']); ?>"><?=$this->e($info->_e('menu_create_event'));?></a></li>
                                                        <?php } ?>
                                                        <li><a id="btnunsubscribe" href="#" <?php if ($subscription !== 1) echo 'style="display: none;"'; ?>><?=$this->e($info->_e('btn_unsubscribe'));?></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php //} ?>
                        <div class="profile-sectionxxx">
                        <div class="top-header-author">
                            <a href="#" class="author-thumb">
                                <img id="user_img" src="<?php echo $user_picture;?>" onerror="this.src='/img/user128.png'" alt="author">
                            </a>
                        </div>
                </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ... end Top Header -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="author-content" style="margin-bottom: 15px">
                    <a href="#" class="author-name">
                        <h3><strong>
                        <?php
                        echo htmlspecialchars(strip_tags($dataView['TITLE']));
                        if ($dataView['OFFICIAL'] === 1) {
                            $s = $info->__('certified_account');
                            echo '&nbsp;<img src="/img/icons-verified-account.svg" class="certified-account" width="17px" height="17px" border="0" data-tippy="' . $s . '" data-tippy-placement="right">';
                        }
                        ?>
                            </strong></h3>
                    </a>

                    <br/> <?php echo \Moviao\Text\TextFormatter::thousandsCurrencyFormat($subscribers_counter) . ' ' . $info->__('text_subscribers_counter')  ; ?>

                </div>
                <div id="event_view_panel" class="ui-block">
                    <!-- Present Events -->
                    <div class="ui-block-title ui-block-title-small">
                        <h6 class="title"><?=$this->e($info->_e('info_title_events_upcoming'));?></h6>
                    </div>
                    <div id="event_view_panel_empty" class="no-past-events" style="display: none;">
                        <svg class="olymp-month-calendar-icon"><use xlink:href="icons/icons.svg#olymp-month-calendar-icon"></use></svg>
                        <span><?=$this->e($info->_e('info_title_events_upcoming_empty'));?></span>
                    </div>
                    <table class="event-item-table">
                        <tbody>
                        <div id="list_upcoming_events"></div>
                        </tbody>
                    </table>

                    <div class="form-row text-center">
                        <div class="col-12">
                            <button id="btn_loadmore_upcoming_events" class="btn btn-primary" style="visibility: hidden" type="button"><?=$this->e($info->_e('btn_more_events'));?></button>
                        </div>
                    </div>

                    <!-- Past Events -->
                    <div class="ui-block-title ui-block-title-small">
                        <h6 class="title"><?=$this->e($info->_e('info_title_events_past'));?></h6>
                    </div>

                    <div id="event_past_view_panel_empty" class="no-past-events">
                        <svg class="olymp-month-calendar-icon"><use xlink:href="icons/icons.svg#olymp-month-calendar-icon"></use></svg>
                        <span><?=$this->e($info->_e('info_title_events_past_empty'));?></span>
                    </div>

                    <table class="event-item-table">
                        <tbody>
                        <div id="list_upcoming_past_events"></div>
                        </tbody>
                    </table>

                    <div class="form-row text-center">
                        <div class="col-12">
                            <button id="btn_loadmore_upcoming_past_events" class="btn btn-primary" style="visibility: hidden" type="button"><?=$this->e($info->_e('btn_more_past_events'));?></button>
                        </div>
                    </div>
                </div>

                <div id="descl_view_panel" class="ui-block" style="display: none">
                    <!-- Description -->
                    <div class="ui-block-title ui-block-title-small">
                        <h6 class="title"><?=$this->e($info->_e('info_title_descl'));?></h6>
                    </div>
                    <div class="no-past-events">
                        <span class="break-word"><?php echo $dataView['DESCL']; ?></span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php
echo $this->insert('modules::tpl_events_calendar');
if ($admin_role === true) {
    echo $this->insert('modules::tpl_upload_modal',['sessionUser' => $sessionUser, 'lang' => $lang]);
    $this->insert('modules::form_channel_edit_desc', ['sessionUser' => $sessionUser, 'uid' => $uid, 'info' => $info, 'dataView' => $dataView, 'csrf_token' => $csrf_token]);
}
?>