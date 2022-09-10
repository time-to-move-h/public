<?php
declare(strict_types=1);
$user_picture = '/img/user-default.png';
// Avatar User
if (! empty($dataView['PICTURE'])) {
    $user_picture = $dataView['PICTURE'];
}
// Background cover
$user_background = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
if (! empty($dataView['BACKGROUND'])) {
    $user_background = $dataView['BACKGROUND'];
}

$admin_role = ((isset($sessionUser) && $sessionUser->isValid()) && $sessionUser->getIDUSER() === strval($dataView['USR']));
//echo var_dump($admin_role);
//echo var_dump($dataView['USR']);
//exit();
if ($admin_role) {
    echo '<input type="hidden" id="emode" value="1">';
} else {
    header('Location: /home');
    exit();
}

// Choose Direct View
$view_selection = null;
if (isset($params["a"])) {
    $view_selection = filter_var($params["a"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (! ($view_selection === 'events' || $view_selection === 'channels' || $view_selection === 'tickets')) {
        $view_selection = 'events';
    }
} else {
    $view_selection = 'events';
}

//var_dump($sessionUser->getAccountType());
?>
<input type="hidden" name="uid" id="uid" value="<?php echo $UUID; ?>">
<!-- Top Header -->
<div class="container">
    <div class="padding-box">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="">

                <div class="top-header">
                    <div class="top-header-thumb">
                        <img id="back_img" class="event_img_background img-fluid" src="<?php echo $user_background; ?>" onerror="this.onerror=null;this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';" alt="Background Image" width="831px" height="351px">
                    </div>

                    <div style="margin-top: 5px">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                            <ul class="profile2-menu">

                            <?php
                                $checkVars = array(1,2,99); // Sellers and Special Team
                                if (in_array($sessionUser->getAccountType(), $checkVars)) {
                            ?>

                            <li><a href="#events" id="btn_event_view_panel" class="btn btn-sm btn-outline-primary <?php if (is_null($view_selection) || $view_selection === 'events') echo 'active' ?>" ><?=$this->e($info->_e('menu_events'));?></a></li>
                            <li>&nbsp;<a href="#channels" id="btn_channel_view_panel" class="btn btn-sm btn-outline-primary <?php if (! is_null($view_selection) && $view_selection === 'channels') echo 'active' ?>" ><?=$this->e($info->_e('menu_channel'));?></a></li>

                            <?php } ?>

                            <?php //if ($admin_role === true && $sessionUser->getAccountType() === 99) { ?>
                                <li>&nbsp;<a href="#tickets" id="btn_ticket_view_panel" class="btn btn-sm btn-outline-primary <?php if (! is_null($view_selection) && $view_selection === 'tickets') echo 'active' ?>" ><?=$this->e($info->_e('menu_tickets'));?></a></li>
                            <?php //} ?>


                                <li>
                                    <div class="private-profile-head inline-items pad-bottom">
                                        <?php if ($admin_role) { ?>
                                            <div class="more">
                                                <img src="/img/icons/icons8-settings.svg" border="0" width="24px" height="24px">
                                                <!--                    <svg class="olymp-three-dots-icon"><use xlink:href="/img/i/icons.svg#olymp-three-dots-icon"></use></svg>-->
                                                <ul class="more-dropdown">
                                                    <li><a id="btn_edit_background" href="#" data-token="8247"><?=$this->e($info->_e('menu_img_background'));?></a></li>
                                                    <li><a id="btn_edit_avatar" href="#" data-token="9787"><?=$this->e($info->_e('menu_img_avatar'));?></a></li>
                                                    
                                                    <?php
                                                        $checkVars = array(2, 99); // Sellers and Special Team
                                                        if (in_array($sessionUser->getAccountType(), $checkVars)) {
                                                    ?>
                                                    <li><a href="/create_channel"><?=$this->e($info->_e('btn_create_channel'));?></a></li>
                                                    <?php } ?>
                                                    
                                                    
                                                    <?php
                                                        $checkUserAccountTypeVars = array(1); // Email
                                                        if (in_array($sessionUser->getUserAccountType(), $checkUserAccountTypeVars)) {
                                                    ?>
                                                    <li><a href="/login/recover"><?=$this->e($info->_e('menu_pwd'));?></a></li>
                                                    <?php } ?>

                                                </ul>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </li>

                            </ul>
                            </div>
                        </div>
                    </div>

                    <div class="profile-sectionxxx">
                        <div class="row">

                            <div class="col-lg-5 col-md-5 ">
                                <!--
                                <ul class="profile-menu">
                                    <li><button id="btn_event_view_panel" class="btn btn-outline-primary active" type="button"><?=$this->e($info->_e('menu_events'));?></button></li>
                                    <li><button id="btn_channel_view_panel" class="btn btn-outline-primary" type="button"><?=$this->e($info->_e('menu_channel'));?></button></li>
                                </ul>
                                -->
                            </div>
<!--
                            <div class="col-lg-5 offset-lg-2 col-md-5 offset-md-2">
                                <ul class="profile-menu">
                                    <?php //if ($admin_role === true && $sessionUser->getAccountType() === 99) { ?>
                                    <li><button id="btn_ticket_view_panel" class="btn btn-outline-primary" type="button"><?=$this->e($info->_e('menu_tickets'));?></button></li>
                                    <?php //} ?>
                                    <li>&nbsp;</li>
                                    <?php //if ($admin_role) { ?>
                                    <li></li>
                                    <?php //} ?>
                                </ul>
                            </div>
                            -->
                        </div>


                        <!--						<div class="control-block-button">
                                                    <a href="35-YourAccount-FriendsRequ" class="btn btn-control bg-blue">
                                                        <svg class="olymp-happy-face-icon"><use xlink:href="/img/i/icons.svg#olymp-happy-face-icon"></use></svg>
                                                    </a>

                                                    <a href="#" class="btn btn-control bg-purple">
                                                        <svg class="olymp-chat---messages-icon"><use xlink:href="/img/i/icons.svg#olymp-chat---messages-icon"></use></svg>
                                                    </a>

                                                    <div class="btn btn-control bg-primary more">
                                                        <svg class="olymp-settings-icon"><use xlink:href="/img/i/icons.svg#olymp-settings-icon"></use></svg>

                                                        <ul class="more-dropdown more-with-triangle triangle-bottom-right">
                                                            <li>
                                                                <a href="#" data-toggle="modal" data-target="#update-header-photo">Update Profile Photo</a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-toggle="modal" data-target="#update-header-photo">Update Header Photo</a>
                                                            </li>
                                                            <li>
                                                                <a href="#">Account Settings</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>-->



                    </div>

                    <div class="top-header-author">
                        <a href="#" class="author-thumb">
                            <img id="user_img" src="<?=$user_picture;?>" onerror="this.src='/img/user128.png'" alt="author">
                        </a>
                        <div class="author-content">
                            <a href="#" class="author-name">
                                <?php
                                    if (isset($dataView) && $dataView !== null && ! empty($dataView['NDISP'])) {
                                        $ndisp = strip_tags($dataView['NDISP']);
                                        $showPoint = mb_strlen($ndisp) > 65;
                                        $ndisp = mb_substr($ndisp,0,65);
                                        echo htmlspecialchars($ndisp,ENT_HTML5);
                                        if ($showPoint) {
                                            echo '...';
                                        }

                                        // Official certificate
                                        if ($dataView['OFFICIAL'] === '1') {
                                            $s = $info->__('certified_account');
                                            echo '&nbsp;<img src="/img/icons-verified-account.svg" class="certified-account" width="17px" height="17px" border="0" data-tippy="' . $s . '" data-tippy-placement="right">';
                                        }
                                    }
                                ?>
                            </a>
<!--                             <div class="country"></div>-->
                        </div>
                    </div>

                </div>
            </div>
        </div>

</div>

<!-- ... end Top Header -->

	<div class="row">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">


            <div id="channel_view_panel" class="ui-block" <?php if (is_null($view_selection) || $view_selection !== 'channels') echo 'style="display: none"' ?>>
                <!-- Channels Subscribed -->
                <div class="ui-block-title ui-block-title-small">
                    <h6 class="title"><?=$this->e($info->_e('info_title_channels_upcoming'));?></h6>
                </div>
                <div id="channel_view_panel_empty" class="no-past-events" style="display: none;">
                    <svg class="olymp-month-calendar-icon"><use xlink:href="icons/icons.svg#olymp-month-calendar-icon"></use></svg>
                    <span><?=$this->e($info->_e('info_title_channels_upcoming_empty'));?></span>
                </div>
                <table class="event-item-table">
                    <tbody>
                    <div id="list_upcoming_channels"></div>
                    </tbody>
                </table>
                <div id="panel_loadmore_upcoming_channels" class="loadmore-wrapper" style="display: none">
                    <div class="text-center">
                    <button id="btn_loadmore_upcoming_channels" class="btn btn-sm btn-secondary" type="button"><?=$this->e($info->_e('btn_more_channels'));?></button>
                    </div>
                </div>
            </div>



			<div id="event_view_panel" class="ui-block" <?php if ($view_selection !== 'events')  echo 'style="display: none"'; ?>>

                <!-- Present Events -->
				<div class="ui-block-title ui-block-title-small">
					<h6 class="title"><?=$this->e($info->_e('info_title_events_upcoming'));?></h6>
				</div>

                <div id="event_view_panel_empty" class="no-past-events">
                    <svg class="olymp-month-calendar-icon"><use xlink:href="icons/icons.svg#olymp-month-calendar-icon"></use></svg>
                    <span><?=$this->e($info->_e('info_title_events_upcoming_empty'));?></span>
                </div>

				<table class="event-item-table">
                    <tbody>
                    <div id="list_upcoming_events"></div>
                    </tbody>
				</table>

                <div id="panel_loadmore_upcoming_events" class="loadmore-wrapper" style="display: none">
                    <div class="text-center">
                    <button id="btn_loadmore_upcoming_events" class="btn btn-sm btn-secondary" type="button"><?=$this->e($info->_e('btn_more_events'));?></button>
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

                <div id="panel_loadmore_upcoming_past_events" class="loadmore-wrapper" style="display: none">
                    <div class="text-center">
                    <button id="btn_loadmore_upcoming_past_events" class="btn btn-sm btn-secondary" type="button"><?=$this->e($info->_e('btn_more_past_events'));?></button>
                    </div>
                </div>

            </div>

            <div id="ticket_view_panel" class="ui-block" <?php if (is_null($view_selection) || $view_selection !== 'tickets') echo 'style="display: none"' ?>>
                <!-- Tickets Bought -->
                <div class="ui-block-title ui-block-title-small">
                    <h6 class="title"><?=$this->e($info->_e('info_title_tickets_upcoming'));?></h6>
                </div>
                <div id="ticket_view_panel_empty" class="no-past-events">
                    <svg class="olymp-month-calendar-icon"><use xlink:href="icons/icons.svg#olymp-month-calendar-icon"></use></svg>
                    <span><?=$this->e($info->_e('info_title_tickets_upcoming_empty'));?></span>
                </div>
                <table class="event-item-table">
                    <tbody>
                    <div id="list_upcoming_tickets"></div>
                    </tbody>
                </table>
                <div id="panel_loadmore_upcoming_tickets" class="loadmore-wrapper" style="display: none">
                    <div class="text-center">
                        <button id="btn_loadmore_upcoming_tickets" class="btn btn-sm btn-secondary" type="button"><?=$this->e($info->_e('btn_more_tickets'));?></button>
                    </div>
                </div>
            </div>

		</div>
	</div>
</div>
</div>
<div id="text_nopublished" style="display: none"><?=$this->e($info->_e('text_nopublished'));?></div>
<?php
$this->insert('modules::tpl_events_calendar');
$this->insert('modules::tpl_channels_calendar');
$this->insert('modules::tpl_upload_modal',['sessionUser' => $sessionUser, 'info' => $info, 'lang' => $lang]);
$this->insert('modules::tpl_mytickets');
?>