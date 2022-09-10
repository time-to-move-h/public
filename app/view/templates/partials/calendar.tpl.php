<div class="container"> 
    <!-- Nav tabs -->
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#panel1" role="tab"><?=$this->e($info->_e('tab_calendar'));?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#panel2" role="tab"><?=$this->e($info->_e('tab_events'));?></a>
      </li>     
    </ul>
    
    <!-- Tab panels -->
    <div class="tab-content">
        <!--Panel 1-->
        <div class="tab-pane active" id="panel1" role="tabpanel">
        <br>
        <div id='calendar'></div>
    </div>
    <!--/.Panel 1-->   
    
    <!--Panel 2-->
    <div class="tab-pane" id="panel2" role="tabpanel">                    
        <div id="grid-data" class="grid-evt"></div>       
        <!--Jumbotron-->
        <div id="msg_nodata_events" class="jumbotron">
            <h1 class="h1-responsive"><?=$this->e($info->_e('info_jumbo_events_1'));?></h1>
            <p class="lead"><?=$this->e($info->_e('info_jumbo_events_2'));?></p>
            <hr class="m-y-2">
            <p><?=$this->e($info->_e('info_jumbo_events_3'));?><p>
            <p class="lead">
                <a href="/events" class="btn btn-primary btn-lg" role="button"><?=$this->e($info->_e('info_jumbo_events_4'));?></a>
            </p>
        </div>
        <!--/.Jumbotron-->       
    </div>    
</div>
</div>
<?php $this->insert('modules::tpl_events',['sessionUser' => $sessionUser]); ?>