"use strict";
define (["./callrpc"],function(callrpc) {
    return function(self,callback,jsonObj) {
        callrpc([jsonObj],self,callback,'/backend/comingsoon','signup_invite');
    }
});