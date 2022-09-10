requirejs(['jquery','doT','app/modules/Profile'], function($,doT,Profile) {       
        
var profile = new Profile();     
        
function User(data) {
    this.NDISP = ko.observable(data.NDISP);
    this.NNAME = ko.observable(data.NNAME);
    this.ABOUT = ko.observable(data.ABOUT);
    this.UUID = ko.observable(data.UUID);
    this.PICTURE = ko.observable(data.PICTURE);
    this.SUBSCRIPTION = ko.observable(data.SUBSCRIPTION);
    
    var surl = window.location.pathname; 
    var lang = surl.match(/[a-z]{2}\-[A-Z]{2}/);
    
    // group link
    this.url = ko.observable("/"+lang+"/profile/" + this.UUID());
    this.details = ko.observable("View Contact details ... ");

    // Logo
    if (this.PICTURE() == null || this.PICTURE().length <= 0) {        
        this.PICTURE = ko.observable("/img/user-sun.jpg");    
    }
    // Button Attend
    if (this.SUBSCRIPTION() == null || this.SUBSCRIPTION() == -1) {
        this.SUBSCRIBED = ko.observable(true);                 
    } else {
        this.SUBSCRIBED = ko.observable(false);    
    } 
}

function onLoadProfiles(self, model) {    
    //console.log(model);        
    if (model != null && model.result === true) {        
        $('#profiles_list').empty();
        var tmpl = $('#tpl_profile').html();
        var tempFn = doT.template(tmpl); 
        $.each(model.data, function( index, value ) {            
            var resultText = tempFn({
                prolnk: '/profile/' + value.UUID, 
                proimg: value.PICTURE,
                ndisp: value.NDISP,
                uuid: value.UUID,
                sub: value.SUBSCRIPTION,
                btndesc: 'Follow'            
            });
            $('#profiles_list').append(resultText);
        });        
    } 
}

function onLoadContacts(self, model) {
    if (model !== null && model.result === true) {                               
        if (model.data.length <= 0) {
            $('#panel_no_contact').show();            
            return;
        }       
        $('#contacts_list').empty();
        var tmpl = $('#tpl_contact').html();
        var tempFn = doT.template(tmpl); 
        $.each(model.data, function( index, value ) {            
            var resultText = tempFn({
                prolnk: '/profile/' + value.UUID, 
                proimg: value.PICTURE,
                ndisp: value.NDISP,
                uuid: value.UUID,
                sub: value.SUBSCRIPTION,  
                req: value.REQ  
            });
            $('#contacts_list').append(resultText);
        });      
    } 
}

// Contacts List View Model
function ContactsListViewModel() {
    // Data  
    var self = this;
    //self.contacts = ko.observableArray([]);
    //self.isContact = ko.observable(false);
    //self.profiles = ko.observableArray([]);
    //self.searchData = ko.observable();
        
        
    profile.getContacts(self,onLoadContacts,null);  
    profile.search(self,onLoadProfiles,"");
    
    $(function() {
        
        $(document).on('click', "button[name='btnfollow']", function() {  
            var el = $(this);
            var uuid = el.attr('data-x-uuid'); 
            var status = el.attr('data-x-sub');
            
            if (status === 'null' || status === '-1') {
                Follow(self,function(self, model) {
                    //console.log(model);                
                    if (model !== null && model.result === true) {                           
                        console.log(model.status);                        
                        if (model.status === 0) {
                            el.html('<i class="fa fa-user" aria-hidden="true"></i><i class="fa fa-question" aria-hidden="true"></i>');
                        } else if (model.status === 1) {
                            el.html('<i class="fa fa-user" aria-hidden="true"></i><i class="fa fa-check" aria-hidden="true"></i>');
                        }                        
                        el.removeClass("btn-secondary" ).addClass("btn-success");
                        el.attr('data-x-sub',model.status);
                    } else {
                        toastr.error('Error de suscripción!');
                    }                 
                },uuid);     
            } else {
                UnFollow(self,function(self, model) {
                    //console.log(model);                
                    if (model !== null && model.result === true) {                    
                        el.html('<i class="fa fa-user-plus" aria-hidden="true"></i>');
                        el.removeClass("btn-success" ).addClass("btn-secondary");
                        el.attr('data-x-sub',-1);
                    } else {
                        toastr.error('Error de suscripción!');
                    }                 
                },uuid);     
            }           
        });
        
        $(document).on('click', "button[name='btncancel']", function() {
            var el = $(this);            
            var uuid = el.attr('data-x-uuid'); 
            var status = el.attr('data-x-sub');
            
            if (status === '0') {               
                UnFollow(self,function(self, model) {
                    //console.log(model);                
                    if (model !== null && model.result === true) {                    
                        //el.html('<i class="fa fa-user-plus" aria-hidden="true"></i>');
                        //el.removeClass("btn-success" ).addClass("btn-secondary");
                        //el.attr('data-x-sub',-1);
                        el.parent().parent().parent().remove();                        
                    } else {
                        toastr.error('Error de cancelacion !');
                    }                 
                },uuid);     
            }            
        });        
        
        $(document).on('click', "button[name='btnconfirm']", function() {           
           var el = $(this);            
            var uuid = el.attr('data-x-uuid'); 
            var status = el.attr('data-x-sub');
            
            if (status === '0') {               
                ConfirmRelation(self,function(self, model) {
                    //console.log(model);                
                    if (model !== null && model.result === true) {                                            
                        el.hide();      
                        toastr.success('Confirmacion !');
                    } else {
                        toastr.error('Error de confirmacion !');
                    }                 
                },uuid);     
            }            
           
        });
        
        // Form Search
        $("#onProfileSearch").submit(function(event) {
           profile.search(self,onLoadProfiles,self.searchData());           
           event.preventDefault();
        });        
        
    });
}

ContactsListViewModel();
        
});