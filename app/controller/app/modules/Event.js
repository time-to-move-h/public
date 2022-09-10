define (["./callrpc"],function(callrpc) {    
    return function() {                                
        function Event () {}                            
        Event.showLatest = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/event','showLatest');       
        }       
        Event.getCalendar = function(self,callback,jsonObj) {                        
            callrpc([jsonObj],self,callback,'/backend/event','getCalendar');
        }
        Event.attend = function(self,callback,jsonObj) {   
            callrpc([jsonObj],self,callback,'/backend/event','attend');
        }
        Event.unAttend = function(self,callback,jsonObj) {  
            callrpc([jsonObj],self,callback,'/backend/event','unattend');      
        }
        Event.publishEvent = function(self,callback,jsonObj) {       
            callrpc([jsonObj],self,callback,'/backend/event','publish');   
        }
        Event.publishComment = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/event','publishComment');  
        }
        Event.loadComments = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/event','loadComments');   
        }
        Event.getTags = function(self,callback,jsonObj) {        
            callrpc([jsonObj],self,callback,'/backend/event','getTags');       
        }
        Event.cancelEvent = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/event','cancel_event');  
        }
        Event.modifyEventDesc = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/event','modify_event_desc');  
        }
        Event.modifyEventDate = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/event','modify_event_date');  
        }
        Event.modifyEventVenue = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/event','modify_event_venue');  
        }
        Event.modifyEventTags = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/event','modify_event_tags');  
        }                
        Event.createEvent = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/event','create_event_t1');  
        }
        Event.filter = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/event','filter');
        }
        return Event;        
    }
});