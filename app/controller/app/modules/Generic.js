"use strict";
define (["./callrpc"],function(callrpc) {
    return function(self,callback,jsonObj) {   
        function Generic() {}         
        Generic.loadAllTags = function(self,callback,jsonObj) {                        
            callrpc([jsonObj],self,callback,'/backend/generic','getTags');
        }       
        return Generic; 
    }
});