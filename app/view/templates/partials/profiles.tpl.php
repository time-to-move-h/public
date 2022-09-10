<div class="container">    
<div id="moviao-central">
    
    <div style="padding-top: 20px;padding-bottom: 20px;padding-left: 15px">
        <span class="text-xs-center"><h4><?=$this->e($info->_e('page_subtitle'));?></h4></span>    
    </div>
    
    
    
    <div class="">
        <form data-bind="submit: onProfileSearch">
            <!--Body-->
            <div class="">
                <i class="fa fa-search prefix"></i>
                <input type="text" id="form2" class="" data-bind="value : searchData">                
            </div>
        </form>
    </div>  
        
    <div data-bind="foreach: profiles">                      
        <div class="grid-contact">                
            <div class="grid-contact-item">                    
                <div class="grid-contact-img">                                      
                    <a data-bind="attr: { href: url, title: details }"><img data-bind="attr: { src: PICTURE }" class="img-fluid" alt=""></a>              
                </div>                        
                <div class="grid-contact-block">
                    <a data-bind="attr: { href: url, title: details }"><span data-bind="text: NDISP"></span></a>
                </div>
            </div>                    
        </div>                     
    </div>
        
        
        
<!--    <div data-bind="foreach: profiles">        
       <div class="col-md-2">                       
            <div class="">                
                <div id="" class="">                    
                    <div>                        
                        <div class="">
                            <a data-bind="attr: { href: url, title: details }"><img data-bind="attr: { src: PICTURE }" class="img-fluid" alt=""></a>
                        </div>                        
                        <div class="avatar"><img src="/img/user-default.png" class="img-circle img-responsive">
                        </div>                        
                        <div class="card-text text-xs-center">
                            <a data-bind="attr: { href: url, title: details }"><span data-bind="text: NDISP"></span></a>
                        </div>
                    </div>                    
                </div>                
            </div>        
        </div> -->
        

</div>        
     
</div>
<script src="/ctrl/modules/UsersCommon.js"></script> 
<script>ko.applyBindings(new UsersListViewModel(),document.getElementById("moviao-central"));</script>