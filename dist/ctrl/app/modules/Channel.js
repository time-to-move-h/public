"use strict";define(["./callrpc"],function(a){return function(){function n(){}return n.getChannels=function(n,e,c){a([c],n,e,"/backend/channel","getChannels")},n.getChannelsCombo=function(n,e,c){a([],n,e,"/backend/channel","getChannelsCombo")},n.createChannel=function(n,e,c){a([c],n,e,"/backend/channel","create")},n.modifyChannelDesc=function(n,e,c){a([c],n,e,"/backend/channel","modify_desc")},n.publish=function(n,e,c){a([c],n,e,"/backend/channel","publish")},n.subscribe=function(n,e,c){a([c],n,e,"/backend/channel","subscribe")},n.unsubscribe=function(n,e,c){a([c],n,e,"/backend/channel","unsubscribe")},n.showLatest=function(n,e,c){a([c],n,e,"/backend/channel","showLatest")},n}});