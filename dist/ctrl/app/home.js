"use strict";requirejs(["jquery","app/modules/Utils","app/modules/Event","app/modules/Generic","packery","scroll","doT","moment","moment/fr"],(function($,Utils,Event,Generic,Packery,InfiniteScroll,doT,Moment,M_Lang){var utils=new Utils,events=new Event,generic=new Generic,evts={offset:0,firstLoad:!0,msnry:null,q:null,p:null,t:null,lat:null,lon:null,rad:null,$btn_loadmore_id:"btn_loadmore_events",$select_where:$("#search-where"),$select_when:$("#search-when"),$select_tags:$("#search-tags"),init:function(){this.q=utils.getUrlParameter("q"),void 0!==this.q&&null!==this.q||(this.q=""),this.p=utils.getUrlParameter("p"),void 0!==this.p&&null!==this.p||(this.p=""),this.t=utils.getUrlParameter("t"),void 0!==this.t&&null!==this.t||(this.t=""),this.lat=utils.getUrlParameter("lat"),void 0!==this.lat&&null!==this.lat||(this.lat=""),this.lon=utils.getUrlParameter("lon"),void 0!==this.lon&&null!==this.lon||(this.lon=""),this.rad=utils.getUrlParameter("rad"),void 0!==this.rad&&null!==this.rad||(this.rad="");var lang=$("html").closest("[lang]").attr("lang")||"en";this.msnry=new Packery(".grid-evt",{itemSelector:".grid-evt-item",columnWidth:1});var args={O:this.offset,Q:this.q,P:this.p,T:this.t,LAT:this.lat,LON:this.lon,RAD:this.rad};events.showLatest(this,this.onLoadEvents,args),document.getElementById("btn_loadmore_events").onclick=function(){try{evts.loadmore()}catch(e){console.log(e)}};var jsonObj={L:lang};generic.loadAllTags(self,(function(self,model){null!==model&&!0===model.result&&($.each(model.data,(function(){evts.$select_tags.append('<option value="'+this.value+'">'+this.text+"</option>")})),evts.t&&$("#search-tags option[value="+evts.t+"]").prop("selected",!0))}),jsonObj),$("#search-when").change((function(){evts.p=$("#search-when option:selected").val(),evts.redirectPage()})),this.p&&$("#search-when option[value="+this.p+"]").prop("selected",!0),$("#search-tags").change((function(){evts.t=$("#search-tags option:selected").val(),evts.redirectPage()}))},redirectPage:function(){window.location.href="/home?q="+evts.q+"&lat="+evts.lat+"&lon="+evts.lon+"&rad="+evts.rad+"&p="+evts.p+"&t="+evts.t},onLoadEvents:function(self,model){if(null!==model&&!0===model.result)if(model.data.length>0){evts.showBtnLoad(),!0===self.firstLoad&&($("#grid-data").find(".grid-evt-item").remove(),model.data.length<=9&&evts.hideBtnLoad());var surl=window.location.pathname,tmpl=$("#tpl_evt").html(),tempFn=doT.template(tmpl);$.each(model.data,(function(){var evtUrl="/event/"+this.URLLINK,PICTURE_MIN=this.PICTURE_MIN,SUBSCRIBED=!1;null!==this.SUBSCRIPTION&&-1!==this.SUBSCRIPTION||(SUBSCRIBED=!0);var resultText=tempFn({TITLE:this.TITLE,DATFORMATTED:this.DATFORMATTED,SUBSCRIPTION:this.SUBSCRIPTION,URLLINK:this.URLLINK,PICTURE:PICTURE_MIN,EVTURL:evtUrl,SUBSCRIBED:SUBSCRIBED,LOCATION:this.CITY});$("#grid-data").append(resultText),self.msnry.appended(resultText),self.msnry.layout()})),$(".event_img_background").on("error",(function(){})),$("#msg_nodata").css("display","none")}else{!0===self.firstLoad&&$("#grid-data").find(".grid-evt-item").remove();var evtcount=$("#grid-data").text().length;null===evtcount&&(evtcount=0),evtcount<1&&($("#grid-data").find(".grid-evt-item").remove(),$("#msg_nodata").css("display","block"),evts.hideBtnLoad()),self.resetOffset(self)}else $("#grid-data").find(".grid-evt-item").remove(),$("#msg_nodata").css("display","block"),evts.hideBtnLoad(),self.resetOffset(self)},resetOffset:function(self){self.offset--},loadmore:function(){this.firstLoad=!1,this.offset++;var args={O:this.offset,Q:this.q,P:this.p,LAT:this.lat,LON:this.lon,RAD:this.rad};events.showLatest(this,this.onLoadEvents,args)},hideBtnLoad:function(){document.getElementById(evts.$btn_loadmore_id).style.visibility="hidden"},showBtnLoad:function(){document.getElementById(evts.$btn_loadmore_id).style.visibility="visible"}};evts.init()}));