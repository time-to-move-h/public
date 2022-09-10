<?php
declare(strict_types=1);
$subscribers_counter = 0; // Channel Subscribers
$csrf = new \Moviao\Security\CSRF_Protect();
$csrf_token = $csrf->echoInputField();
$authenticated = (isset($sessionUser) && $sessionUser->isValid());

//echo var_dump($dataViewAttendees);
//echo var_dump($dataView['IS_TICKET']);

//echo "role : " . var_dump($authenticated);
$admin_role = ($authenticated === true && $sessionUser->getUSER_UUID() === strval($dataView['USR_UUID']) && $dataView['ROLE'] === 1);
if ($admin_role === true) {
    echo '<input type="hidden" id="emode" value="1">';    
}

//echo var_dump($dataView);
//echo "       -         ";
//echo var_dump($dateNow);
?>
<input type="hidden" id="UID" name="UID"  value="<?php echo $urllink;?>">
<input type="hidden" id="DATBEG" name="DATBEG"  value="<?php echo $date_start->format('c');?>">
<input type="hidden" name="subscribed" id="subscribed" value="<?php echo $subscription; ?>">
<div class="padding-box">
<div class="container bg-white event_wrapper" style="height: 100%">
<!-- Online/Offline Status  -->
<?php if (($admin_role === true) && ($dataView['ONLINE'] === 0)) { ?>
<div id="panel-pub" style="width: 100%; border: 2px solid red; margin-bottom: 25px; padding: 5px">
    <span class="text-center"><h4><?=$this->e($info->_e('info_publish'));?></h4></span>
    <?php if ($dataView['CHA_ONLINE'] === 1 || strval($dataView['CHA_ONLINE']) === '') { ?>
    <div class="text-center">
        <button class="btn btn-primary btn-lg" id="publish" name="publish" type="submit"><?=$this->e($info->_e('btn_publish'));?></button>                                
    </div>
    <?php } ?>
</div>
<?php } ?>     

<!-- Title -->
<div class="private-event-head inline-items">  <!--pad-bottom-->
<div class="previous-button">
    <a href="/home" target="_self"><img src="/img/icons/left-arrow.svg"  alt="back" height="15px" width="15px" /></a>
</div>
</div>

<!-- Picture -->    
<div class="clearfix">    
    <div id="back_img_wrapper">
        <img id="back_img" class="event_img_background img-fluid" width="851px" height="351px" src="<?php echo $dataView['PICTURE'];?>" onerror="this.onerror=null;this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';" alt="">
    </div> 
</div>

<!--    style="border: 1px solid red"-->
<div>

<div>
<ul class="event-menu">

<?php
$checkVars = array(99); // Sellers and Special Team
// Show only this part for nomal event
if (isset($dataView['EVTTYP']) && $dataView['EVTTYP'] === 1) {
    if ($authenticated === true && $date_start > $dateNow) {
        if (isset($dataView['MAXUSE']) && $dataView['MAXUSE'] > 0) {       
            if ($dataView['MAXUSE'] <= $dataView['COUNTER_ATTENDEES'] && $subscription === -1) {                
?>
<li><button id="btnfullbooked" class="btn btn-sm btn-secondary float-right" type="button"><?=$this->e($info->_e('btn_fullybooked'));?></button></li>
<?php } else if ($dataView['MAXUSE'] <= $dataView['COUNTER_ATTENDEES'] && $subscription === 1) { ?>
    <li><button id="btndisinterested" class="btn btn-sm btn-secondary float-right" type="button" <?php if ($subscription === -1) echo 'style="display: none;"'; ?>><?=$this->e($info->_e('btn_unattend'));?></button></li>
<?php } else { ?>
    <!-- Button Attend -->
    <li><button id="btninterested" class="btn btn-sm btn-primary float-right" type="button" <?php if ($subscription === 1) echo 'style="display: none;"'; ?>><?=$this->e($info->_e('btn_attend'));?></button></li>
    <li><button id="btndisinterested" class="btn btn-sm btn-secondary float-right" type="button" <?php if ($subscription === -1) echo 'style="display: none;"'; ?>><?=$this->e($info->_e('btn_unattend'));?></button></li>
<?php }}}} ?>

<?php
// $admin_role === true && $sessionUser->getAccountType() === 99 &&
//echo var_dump($dataView['IS_TICKET']);

if (isset($dataView['IS_TICKET']) && $dataView['IS_TICKET'] === true && $date_start > $dateNow) {
?>
<!-- Button Ticket -->
<li><button id="btnticket" class="btn btn-sm btn-primary float-right" type="button"><?=$this->e($info->_e('btn_ticket'));?></button></li>
<?php } ?>

    <li>
        <!-- Title -->
        <div class="private-event-head inline-items">  <!--pad-bottom-->

            <?php
            if ($admin_role === true && ($date_start >= $dateNow || ($date_end != null && $dateNow <= $date_end))) {
                ?>
                <div class="more">
                    <img src="/img/icons/icons8-settings.svg" border="0" width="24px" height="24px">
                    <ul class="more-dropdown">
                        <!-- <li><a href="#">Edit Event Settings</a></li> -->
                        <li><a id="btn_edit_img" href="#"><?=$this->e($info->_e('btn_editbackground'));?></a></li>
                        <li><a id="btn_edit_dates" href="#"><?=$this->e($info->_e('btn_edit_dates_event'));?></a></li>
                        <li><a id="btn_edit_desc" href="#" data-toggle="modal" data-target="#modal_edit_desc"><?=$this->e($info->_e('btn_edit_desc_event'));?></a></li>
                        <li><a id="btn_edit_tags" href="#" data-toggle="modal" data-target="#modal_edit_tags"><?=$this->e($info->_e('btn_edit_tags_event'));?></a></li>

                        <?php if ($dataView['ISONLINE'] !== 1) {  ?>
                        <li><a id="btn_edit_venue" href="#" data-toggle="modal" data-target="#wvenue"><?=$this->e($info->_e('btn_edit_venue_event'));?></a></li>
                        <?php } ?>

                        <?php                         
                            // Show only this part for nomal event
                            if (isset($dataView['EVTTYP']) && $dataView['EVTTYP'] === 1) {                        
                                // || $sessionUser->getAccountType() === 2
                                if ($sessionUser->getAccountType() === 99) { 
                        ?>
                        <li><a id="btn_manage_ticket" href="#">Manage Tickets</a></li>
                        <?php }} ?>

                        <li><a id="btncancel" href="#"><?=$this->e($info->_e('btn_cancel_event'));?></a></li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </li>

</ul>
</div>

<?php //echo var_dump($dataView); ?>

<!-- Title -->
<div class="private-event-head inline-items pad-bottom pad-top">
    <div class="author-date break-word">
<!--        <a class="event-title" href="#">-->
            <div id="field_view_desc_title" class="h4"><?php             
            $filtered_title = strip_tags($dataView['TITLE']);
            echo $filtered_title;
            ?></div>
<!--        </a>-->

        <!-- Date Format -->
        <div class="event-date">
            <time class="published" datetime="">
                <?php echo htmlspecialchars($dataView['DATFORMATTED']); ?>
            </time>
        </div>

    <!-- Location -->
    <div class="ev-view-location-wrapper">

        <!-- <h6 class="font-weight-bold"><?//=$this->e($info->_e('title_location'));?></h6> -->

            <!-- <?php //if ($admin_role === true && $sessionUser->getAccountType() === 99) { ?>
                <div class="ev-view-actions" style="margin-left: -25px;position: absolute;">
                    <a id="btn_edit_venue" href="#" data-toggle="modal" data-target="#wvenue">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 16 16">
                        <path fill="#999EBE" d="M13.5 0c1.381 0 2.5 1.119 2.5 2.5 0 0.563-0.186 1.082-0.5 1.5l-1 1-3.5-3.5 1-1c0.418-0.314 0.937-0.5 1.5-0.5zM1 11.5l-1 4.5 4.5-1 9.25-9.25-3.5-3.5-9.25 9.25zM11.181 5.681l-7 7-0.862-0.862 7-7 0.862 0.862z"></path>
                        </svg>
                    </a>
                </div>
            <?php //} ?> -->



            <?php if ($dataView['ISONLINE'] === 1) { ?>
                <div id="panel_view_venue">
                    <div class="event-view-venue break-word"><?=$this->e($info->_e('text_location_onlineevent'));?></div>
                </div>
            <?php } else if ($sessionUser->getAccountType() === 99) { ?>

            <div id="panel_view_venue">
                <div class="event-view-venue break-word">
                    <?php             
                    $venue = ! empty($dataView['VENUE']) ? htmlentities(strip_tags($dataView['VENUE'])) : '';
                    $street = ! empty($dataView['STREET']) ? htmlentities(strip_tags($dataView['STREET'])) : '';
                    $street2 = ! empty($dataView['STREET2']) ? htmlentities(strip_tags($dataView['STREET2'])) : '';
                    //$streetn = htmlentities(strip_tags($dataView['STREETN']));
                    $pcode = ! empty($dataView['PCODE']) ? htmlentities(strip_tags($dataView['PCODE'])) : '';

                    $city = ! empty($dataView['CITY']) ? htmlentities(strip_tags($dataView['CITY'])) : '';
                    $state = ! empty($dataView['STATE']) ? htmlentities(strip_tags($dataView['STATE'])) : '';
                    $country = ! empty($dataView['COUNTRY']) ? htmlentities(strip_tags($dataView['COUNTRY'])) : '';
                    //echo (var_dump($state));                
                    $location_desc = '';

                    if (! empty($venue)) {
                        $location_desc = '<span>' . $venue . "</span><br>"; 
                    }

                    if (! empty($street)) {
                        $location_desc .= '<span>' . $street;                     
                        if (! empty($streetn)) {
                            $location_desc .= ' , ' . $streetn;
                        }
                        $location_desc .= "</span><br>"; 
                        //--------------------------------                    
                        if ( !empty($street2)) {
                            $location_desc .= '<span>' . $street2 . "</span><br>";                                                                    
                        }
                    }

                    if (! empty($city)) {

                        if (! empty($pcode)) {
                            $location_desc .= '<span>' . $pcode . '&nbsp;</span>';
                        }

                        $location_desc .= '<span>' . $city;                     
                        if (! empty($state)) {
                            $location_desc .= ' , ' . $state;
                        }
                        $location_desc .= "</span><br>"; 
                    }                
                    if (! empty($country)) {
                        $location_desc .= '<span>' . $country . "</span><br>"; 
                    }                
                    echo $location_desc;
                ?>                     
                </div>                 
            </div>

            <?php } ?>

    </div>
    <!-- End Location -->

        <?php if ($admin_role === true && $sessionUser->getAccountType() === 99 ) { ?>
            <a target="_blank" href="/calendar/ics/<?php echo $urllink;?>" class="btn btn-sm btn-outline-primary"><?=$this->e($info->_e('add_calendar'));?></a>
        <?php } ?>
    </div>
</div>
<!-- End Title -->


<!-- Edit DESC  -->
<div class="ev-view-desc-wrapper">
<?php
$url = null;
// if (! empty($dataView['URL'])) {
//     $url = $dataView['URL'];   
// }
?>

<?php //if ($dataView['ROLE'] === 1) { ?>
<!-- <div class="ev-view-actions" style="margin-left: -25px;position: absolute;">
<a id="btn_edit_desc" href="#" data-toggle="modal" data-target="#modal_edit_desc">
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 16 16">
    <path fill="#999EBE" d="M13.5 0c1.381 0 2.5 1.119 2.5 2.5 0 0.563-0.186 1.082-0.5 1.5l-1 1-3.5-3.5 1-1c0.418-0.314 0.937-0.5 1.5-0.5zM1 11.5l-1 4.5 4.5-1 9.25-9.25-3.5-3.5-9.25 9.25zM11.181 5.681l-7 7-0.862-0.862 7-7 0.862 0.862z"></path>
    </svg>
</a>
</div> -->
<?php //} ?>

<!-- DESC + URL -->
<div id="panel_view_desc">       
    <div id="field_view_desc_descl" class="break-word">
    <h6 class="font-weight-bold"><?=$this->e($info->_e('text_about'));?></h6>

    <?php 
    function sanitizeDescription($descl_unsafe) {        
        $strip_html = '<h1><h2><h3><h4><h5><h6><strong><p><u><em><br>';
        $strip_html_array = array('h1','h2','h3','h4','h5','h6','p','br','u','em','strong');
            $filtered_descl = null; 
            if (! empty($descl_unsafe)) {
                $descl_unsafe = strip_tags($descl_unsafe, $strip_html);
                // Filter html xss description
                $filter = new \Moviao\Http\HTML_Sanitizer;
                //$allowed_protocols = array('http');
                $allowed_tags = $strip_html_array;
                //$filter->addAllowedProtocols($allowed_protocols);
                $filter->addAllowedTags($allowed_tags);        
                $filtered_descl = $filter->xss($descl_unsafe);
            }
        return $filtered_descl;
    }
    
    $filtered_desc =  sanitizeDescription($dataView['DESCL']); // strip_tags($dataView['DESCL'], '<h1><h2><h3><h4><strong><i><p><u><ul><li><span><blockquote>');
    echo $filtered_desc;        
    ?>
    
    </div>
    <?php if (! empty($url)) { ?>
    <br/><span id="field_view_desc_url"><a target="_blank" href="<?=$url;?>"><? //=strip_tags($url); ?></a></span>
    <?php } ?>
</div>
</div>

<!-- Tags  -->
<div class="ev-view-tags-wrapper">
    
    <!-- <?php //if ($dataView['ROLE'] === 1) { ?>
        <div class="ev-view-actions" style="margin-left: -25px;position: absolute;">
            <div>
                <a id="btn_edit_tags" href="#" data-toggle="modal" data-target="#modal_edit_tags">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 16 16">
                        <path fill="#999EBE" d="M13.5 0c1.381 0 2.5 1.119 2.5 2.5 0 0.563-0.186 1.082-0.5 1.5l-1 1-3.5-3.5 1-1c0.418-0.314 0.937-0.5 1.5-0.5zM1 11.5l-1 4.5 4.5-1 9.25-9.25-3.5-3.5-9.25 9.25zM11.181 5.681l-7 7-0.862-0.862 7-7 0.862 0.862z"></path>
                    </svg>
                </a>
            </div>
        </div>
    <?php //} ?> -->

    <div class="ev-view-tags" id="panel_view_tags"><div id="tags_list"></div></div>
</div>
<!-- End Tags  -->

<!-- Channel -->
<?php
//if ($authenticated === true && $sessionUser->getAccountType() === 99) {
    if (isset($dataView['CHA_TITLE'])) {
        $title = filter_var($dataView['CHA_TITLE'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $name = filter_var($dataView['CHA_NAME'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $channel_picture_rnd = filter_var($dataView['CHA_PICTURE_RND'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (! empty($dataView['CHA_COUNTER_FOLLOWERS'])) {
            $subscribers_counter = filter_var($dataView['CHA_COUNTER_FOLLOWERS'], FILTER_SANITIZE_NUMBER_INT);
        }
    }
//}
?>
<hr>
<div class="d-flex flex-row">
  <div class="">
  <h6 class="font-weight-bold">Channel</h6> 
  <br>
    <a href="/channel/<? echo $name; ?>">
        <div>
            <div class="float-left">
                <img class="rounded-circle" width="100px" height="100px" src="<? echo $channel_picture_rnd; ?>">
            </div>
            <div class="float-left" style="padding-left: 15px">
                <strong><? echo $title; ?></strong>
                <br> <?php echo \Moviao\Text\TextFormatter::thousandsCurrencyFormat($subscribers_counter) . ' ' . $info->__('text_subscribers_counter')  ; ?>
            </div>
        </div>
    </a>
</div>
</div>
<!-- End Channel -->

<!-- Hosts -->
<?php       
    //echo var_dump($sessionUser->getAccountType());
    // Show only this part for nomal event
    if (isset($dataView['EVTTYP']) && $dataView['EVTTYP'] === 1) {
        if (isset($dataView['MAXUSE']) && $dataView['MAXUSE'] > 0) {

        if (isset($dataView['USR_NDISP'])) {
            $host_ndisp = filter_var($dataView['USR_NDISP'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $host_uuid = filter_var($dataView['USR_UUID'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $host_avatar = filter_var($dataView['USR_AVATAR'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
?>
<hr>
<div class="d-flex flex-row">
  <div class="">   
  <h6 class="font-weight-bold">Hosts</h6> 
  <br>
    <a href="/profile_public/<? echo strtolower($host_uuid); ?>">
        <div>
            <div class="float-left">
                <img class="rounded-circle" width="100px" height="100px" src="<? echo $host_avatar; ?>">
            </div>
            <div class="float-left" style="padding-left: 15px">
                <strong><? echo $host_ndisp; ?></strong>           
            </div>
        </div>
    </a>
</div>
</div>
<?php }} ?>
<!-- End Hosts -->

<!-- Attendees -->
<?php
    // Show only this part for nomal event
    if (isset($dataView['EVTTYP']) && $dataView['EVTTYP'] === 1) {
        if (isset($dataView['MAXUSE']) && $dataView['MAXUSE'] > 0) {
?>
<hr>
<div class="d-flex flex-row">
  <div class="">
    <article>
    <h6 class="font-weight-bold"><?=$this->e($info->_e('text_attendees'));?>&nbsp;<?php if (isset($dataView['MAXUSE'])  && isset($dataView['COUNTER_ATTENDEES'])) echo '(' . $dataView['COUNTER_ATTENDEES'] . '/' . $dataView['MAXUSE'] . ')' ?></h6> 
    <div class="c-profile__list">
        <?php 
            if (! is_null($dataViewAttendees)) {
                foreach ($dataViewAttendees as $value) {
                    //$value = $value * 2;
                    echo '<a class="c-profile" username="' . htmlspecialchars(strip_tags($value['NDISP'])) . '" style="background-image: url(' . htmlspecialchars(strip_tags($value['PICTURE'])) . ');"></a>';
                }
            }
        ?>
    </div>
    </article>
</div>
</div>
<?php }} ?>
<!-- End Attendees -->

<!-- Sharingbutton -->
<?php
$share_self_page = urlencode('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$share_title = strip_tags($dataView['TITLE']);
//echo $share_title;
?>
<div class="event-view-sharebuttons" style="padding-top: 20px; clear: both;">
    <!-- Sharingbutton Facebook -->
    <a class="resp-sharing-button__link" href="https://facebook.com/sharer/sharer.php?u=<?php echo $share_self_page; ?>" target="_blank" aria-label="">
        <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
            </div>
        </div>
    </a>

    <!-- Sharingbutton Twitter -->
    <a class="resp-sharing-button__link" href="https://twitter.com/intent/tweet/?text=<?php echo $share_title; ?>&amp;url=<?php echo $share_self_page; ?>" target="_blank" aria-label="">
        <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>
            </div>
        </div>
    </a>

    <!-- Sharingbutton E-Mail -->
    <a class="resp-sharing-button__link" href="mailto:?subject=<?php echo $share_title; ?>&amp;body=<?php echo $share_self_page; ?>" target="_self" aria-label="">
        <div class="resp-sharing-button resp-sharing-button--email resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22 4H2C.9 4 0 4.9 0 6v12c0 1.1.9 2 2 2h20c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM7.25 14.43l-3.5 2c-.08.05-.17.07-.25.07-.17 0-.34-.1-.43-.25-.14-.24-.06-.55.18-.68l3.5-2c.24-.14.55-.06.68.18.14.24.06.55-.18.68zm4.75.07c-.1 0-.2-.03-.27-.08l-8.5-5.5c-.23-.15-.3-.46-.15-.7.15-.22.46-.3.7-.14L12 13.4l8.23-5.32c.23-.15.54-.08.7.15.14.23.07.54-.16.7l-8.5 5.5c-.08.04-.17.07-.27.07zm8.93 1.75c-.1.16-.26.25-.43.25-.08 0-.17-.02-.25-.07l-3.5-2c-.24-.13-.32-.44-.18-.68s.44-.32.68-.18l3.5 2c.24.13.32.44.18.68z"/></svg>
            </div>
        </div>
    </a>

    <!-- Sharingbutton LinkedIn -->
    <a class="resp-sharing-button__link" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=https%3A%2F%2Fwww.moviao.be&amp;title=<?php echo $share_title; ?>&amp;summary=<?php echo $share_title; ?>&amp;source=<?php echo $share_self_page; ?>" target="_blank" aria-label="">
        <div class="resp-sharing-button resp-sharing-button--linkedin resp-sharing-button--small"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.5 21.5h-5v-13h5v13zM4 6.5C2.5 6.5 1.5 5.3 1.5 4s1-2.4 2.5-2.4c1.6 0 2.5 1 2.6 2.5 0 1.4-1 2.5-2.6 2.5zm11.5 6c-1 0-2 1-2 2v7h-5v-13h5V10s1.6-1.5 4-1.5c3 0 5 2.2 5 6.3v6.7h-5v-7c0-1-1-2-2-2z"/></svg>
            </div>
        </div>
    </a>

</div>
<!-- End Sharingbutton -->
</div>


<?php if (1===2) { ?>
<!-- Commentaires desactives pour la phase 1 -->
<?php if ($admin_role === true) {  ?>
<!-- Begin Comments -->
<div style="padding-top: 10px">     
<h4 class="font-weight-bold"><?=$this->e($info->_e('tabs_comments'));?></h4>    
<!-- Input Comments -->       
<form class="comment-form inline-items">
<div class="post__author author vcard inline-items">
   <img src="/img/authorpage.jpg" alt="author">
</div>
<div class="form-group with-icon-right is-empty">
    <textarea id="userComment" class="form-control" placeholder="<?=$this->e($info->_e('btn_addcomment_placeholder'));?>" maxlength="250"></textarea>
        <div class="add-options-message">
                <a id="btnAddComment" href="#" class="options-message" data-toggle="modal" data-target="#update-header-photo">
                    <svg class="olymp-computer-icon"><use xlink:href="/img/i/icons.svg#olymp-computer-icon"></use></svg>
                </a>
        </div>
        <span class="material-input"></span><span class="material-input"></span></div>
</form>        

<!-- Comments -->
<div>                
    <div>                                        
        <div class="mCustomScrollbar" data-mcs-theme="dark">
        <ul class="comments-list">        
            <div id="comments_list"></div>
        </ul>
        </div>            
    </div>         
    <a href="#" class="more-comments"><?=$this->e($info->_e('btn_more_comments'));?> <span>+</span></a>    
</div>    

</div> 
<!-- End Comments -->

<?php }} ?>
<div>
</div>

<!-- Modal Window -->
<?php if ($admin_role === true) {  ?>
<div class="modal fade" id="wvenue" tabindex="-1" role="dialog" aria-labelledby="wvenue" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">        
<div class="modal-header">
<h5 class="modal-title">
<span class="text-xs-center"><h4><?=$this->e($info->_e('page_legend_location'));?></h4></span>
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>        
<form id="feventvenue" data-parsley-validate> 
<div class="modal-body">                                                
<fieldset>  
<?php
$this->insert('modules::form_places_map',['info' => $info]);
$this->insert('modules::form_places',['info' => $info,'lang' => $lang]);
?>
</fieldset>            
</div>
<div class="modal-footer">
<button id="btn_save_venue" type="submit" class="btn btn-primary pull-right btn-margin-left5"><?=$this->e($info->_e('btn_save'));?></button>
</div>
</form>
</div></div></div>  
<?php } ?>
</div>
</div>

<input type="hidden" id="text_alert_deleteticket" value="<?=$this->e($info->_e('text_alert_deleteticket'));?>">
<input type="hidden" id="text_alert_cancelevent" value="<?=$this->e($info->_e('text_alert_cancelevent'));?>">

<?php
//$this->insert('modules::tpl_comments',['sessionUser' => $sessionUser]);
$this->insert('modules::tpl_tags',['sessionUser' => $sessionUser]);
$this->insert('modules::tpl_tickets', ['sessionUser' => $sessionUser, 'info' => $info, 'lang' => $lang]);
$this->insert('modules::form_event_tickets',['sessionUser' => $sessionUser, 'urllink' => $urllink, 'dataView' => $dataView, 'csrf_token' => $csrf_token, 'lang' => $lang, 'display' => 'none','action' => '/checkout', 'width' => '90%', 'width' => '40rem']);

if ($admin_role === true) {
    $this->insert('modules::tpl_upload_modal', ['sessionUser' => $sessionUser, 'info' => $info, 'lang' => $lang]);
    $this->insert('modules::form_event_edit_tags', ['sessionUser' => $sessionUser, 'urllink' => $urllink, 'info' => $info]);
    $this->insert('modules::form_event_edit_desc', ['sessionUser' => $sessionUser, 'urllink' => $urllink, 'info' => $info, 'dataView' => $dataView, 'csrf_token' => $csrf_token]);
    $this->insert('modules::form_event_edit_dates', ['sessionUser' => $sessionUser, 'urllink' => $urllink, 'dataView' => $dataView, 'csrf_token' => $csrf_token, 'lang' => $lang]);
    $this->insert('modules::tpl_tickets_edit', ['sessionUser' => $sessionUser, 'info' => $info, 'lang' => $lang]);
    $this->insert('modules::form_event_create_ticket',['sessionUser' => $sessionUser, 'urllink' => $urllink, 'dataView' => $dataView, 'csrf_token' => $csrf_token, 'lang' => $lang]);
}
?>