"use strict";

define (["./callrpc"],function(callrpc) {
    return function() {                                
        function Channel () {}
        Channel.getChannels = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/channel','getChannels');
        }
        Channel.getChannelsCombo = function(self,callback,jsonObj) {                        
            callrpc([],self,callback,'/backend/channel','getChannelsCombo');
        }
        Channel.createChannel = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/channel','create');
        }
        Channel.modifyChannelDesc = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/channel','modify_desc');
        }
        Channel.publish = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/channel','publish');
        }
        Channel.subscribe = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/channel','subscribe');
        }
        Channel.unsubscribe = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/channel','unsubscribe');
        }
        Channel.showLatest = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/channel','showLatest');
        }
        return Channel;
    }
});