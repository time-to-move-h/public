<div class="container">
<div id="moviao-central">    
    <div class="row">                
        <div class="col-md-3" style="padding-right: 10px;padding-bottom: 10px; min-width: 16rem;">            
            <div class="card text-center" style="min-width: 13rem;max-width: 504px">
                <div class="card-body">
                  <div class="card-title">                                            
                    <div class="">                    
                        <a data-toggle="lightbox" <?php if ($admin) echo 'class="flow-browse-image"'; ?> data-gallery="" href="#" data-footer='<button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>'>
                            <img class="rounded-circle" id="user-profile-image" src="<?php echo $picture; ?>" alt="User Profile" style="width: 64px; height: 64px;"/>
                        </a>            
                    </div>                                    
                  </div>
                  <p class="card-text">                                    
                    <span style="font-weight: 700"><?php echo htmlentities(strip_tags($dataView['NDISP'])); ?></span>
                    <small><?php echo htmlentities(strip_tags($dataView['NNAME'])); ?></small>
                  </p>                  
                  
                </div>
            </div> 

            <div style="padding-top: 5px">
            <div class="card" style="min-width: 13rem;max-width: 504px">
                <a class="text-muted" href="#" data-toggle="modal" data-target="#wcontacts">    
                <div style="position: relative;height: 8rem;">
                    <div style="position: absolute;top: 50%;left: 40%;transform: translateY(-50%);" data-toggle="tooltip" data-placement="bottom" title="<?=$this->e($info->_e('btn_search'));?>">
                        <i class="fa fa-5x fa-plus" aria-hidden="true"></i>
                    </div>    
                </div>
                </a>
            </div> 
            </div>            
        </div>     
    
<div class="col-md-6 bg-white pad-container">
    
        <div class="">
            <span class="text-center"><h3><?=$this->e($info->_e('page_subtitle'));?></h3></span>    
        </div>
        <div class="d-flex flex-wrap"><div id="contacts_list"></div></div>    
        <!--No Contact -->
        <div id="panel_no_contact" class="jumbotron bg-white text-center" style="display:none">    
            <p class="lead"><?=$this->e($info->_e('info_nocontact'));?></p>
            <hr class="m-y-2">  
            <i class="fa fa-5x fa-long-arrow-left" aria-hidden="true"></i>
            <p><?=$this->e($info->_e('info_btnsearch'));?><p>
        </div>
        <!--/.No Contact -->         

</div>   
        
<div class="col-md-3">
<div class="card text-center" style="min-width: 13rem;max-width: 504px">
<div class="card-body"><div class="card-title">Suggestions</div></div></div><div></div>
</div>
</div>
    
    
<!-- Modal -->
<div class="modal fade" id="wcontacts" tabindex="-1" role="dialog" aria-labelledby="wcontacts" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">        
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">
        <span class="text-xs-center"><h4><?=$this->e($info->_e('page_subtitle'));?></h4></span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">                  
        <div class="col-md-12" style="padding: 10px">
            <div style="padding-bottom: 5px">
                <form id="onProfileSearch">
                    <!--Body-->
                    <div class="form-inline">                    
                        <input type="text" id="form2" class="form-control" placeholder="<?=$this->e($info->_e('search_input_placeholder'));?>" data-bind="value : searchData">                
                        <button type="submit" class="btn btn-primary btm-sm btn-margin-left">
                            <i class="fa fa-search prefix"></i>
                        </button>
                    </div>
                </form>
            </div>                     
            <ul class="list-group">  
              <div id="profiles_list"></div>
            </ul>            
        </div>                
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>          
        </div>
      </div>
    </div>
  </div>  
    
    
    
</div>
</div>    

<div id="tpl_profile" style="display: none">
<li class="list-group-item">
    <a href="{{=it.prolnk}}" target="_blank">
      <img src="{{=it.proimg}}" style="width: 32px;" class="rounded-circle">&nbsp;
      <span class="text-muted">{{=it.ndisp}}</span>
    </a>       
    <div class="w-100">
    <button name="btnfollow" class="btn {{? it.sub == '0'|| it.sub === '1' }}btn-success{{??}}btn-secondary{{?}} btn-sm pull-right" data-x-sub="{{=it.sub}}" data-x-uuid="{{=it.uuid}}">        
        {{? it.sub == '0'}}
            <i class="fa fa-user" aria-hidden="true"></i><i class="fa fa-question" aria-hidden="true"></i>            
            {{?? it.sub === '1' }}            
            <i class="fa fa-user" aria-hidden="true"></i><i class="fa fa-check" aria-hidden="true"></i>
            {{??}}             
            <i class="fa fa-user-plus" aria-hidden="true"></i>           
        {{?}}        
    </button>
    </div>
</li> 
</div>

<div id="tpl_contact" style="display: none">
<div class="d-inline-flex p-2">      
    <div class="card" style="min-width: 250px;max-width: 250px;min-height: 169.5px">
      <div class="card-body">
          <div class="card-title"><a href="{{=it.prolnk}}" target="_blank"><span class="text-muted">{{=it.ndisp}}</span></a></div>
        <p class="card-text">                
            <a href="{{=it.prolnk}}" target="_blank">
                <img src="{{=it.proimg}}" class="img-fluid rounded-circle" width="64px" height="64px" alt="">
            </a>                
        </p>        
        {{? it.sub == '0'}}
            <button name="btncancel" class="btn btn-margin-left btn-danger btn-primary btn-sm pull-right" data-x-sub="{{=it.sub}}" data-x-uuid="{{=it.uuid}}">Cancel</button>                                                       
            {{? it.req == '0' }}
                <button name="btnconfirm" class="btn btn-success btn-sm pull-right" data-x-sub="{{=it.sub}}" data-x-uuid="{{=it.uuid}}">Confirm</button>                        
            {{?}}            
        {{?}}
      </div>
    </div>      
</div>    
</div>