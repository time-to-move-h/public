define(["./callrpc"],function(c){return function(){function e(){}return e.showLatest=function(e,n,t){c([t],e,n,"/backend/event","showLatest")},e.getCalendar=function(e,n,t){c([t],e,n,"/backend/event","getCalendar")},e.attend=function(e,n,t){c([t],e,n,"/backend/event","attend")},e.unAttend=function(e,n,t){c([t],e,n,"/backend/event","unattend")},e.publishEvent=function(e,n,t){c([t],e,n,"/backend/event","publish")},e.publishComment=function(e,n,t){c([t],e,n,"/backend/event","publishComment")},e.loadComments=function(e,n,t){c([t],e,n,"/backend/event","loadComments")},e.getTags=function(e,n,t){c([t],e,n,"/backend/event","getTags")},e.cancelEvent=function(e,n,t){c([t],e,n,"/backend/event","cancel_event")},e.modifyEventDesc=function(e,n,t){c([t],e,n,"/backend/event","modify_event_desc")},e.modifyEventDate=function(e,n,t){c([t],e,n,"/backend/event","modify_event_date")},e.modifyEventVenue=function(e,n,t){c([t],e,n,"/backend/event","modify_event_venue")},e.modifyEventTags=function(e,n,t){c([t],e,n,"/backend/event","modify_event_tags")},e.createEvent=function(e,n,t){c([t],e,n,"/backend/event","create_event_t1")},e.filter=function(e,n,t){c([t],e,n,"/backend/event","filter")},e}});