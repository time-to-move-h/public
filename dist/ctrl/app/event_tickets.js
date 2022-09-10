"use strict";requirejs(["jquery","doT","app/modules/Utils","app/modules/Event","app/modules/Generic","app/modules/Ticket","c/tingle/tingle.min"],function(r,o,e,t,i,n,u){var a=new i,s=new e,c=new t,l=new n,f={form_desc:"#feventdesc",form_dates:"#feventdates",form_tags:"#feventtags",form_venue:"#feventvenue",form_tickets:"#fcreateticket",lang:r("html").closest("[lang]").attr("lang")||"en",uid:s.getValueByID("uid"),tags_selected:[],btninterested:r("#btninterested"),btndisinterested:r("#btndisinterested"),datepicker_start:null,datepicker_end:null,modal_dates:null,modal_img:null,modal_admin_tickets:null,isTicketsLoaded:!1,init:function(){r(document).on("change",'select[name^="TICKET_QTE"]',f.onChangeTicketsQte),this.loadTicketsView()},initTickets:function(){f.modal_view_tickets=new u.modal({footer:!0,stickyFooter:!1,closeMethods:["overlay","button","escape"],closeLabel:"Close",cssClass:["custom-class-1"],onOpen:function(){f.loadTicketsView()},onClose:function(){},beforeClose:function(){return!0}}),f.modal_view_tickets.setContent(document.getElementById("panel_view_tickets").innerHTML)},loadTicketsView:function(){if(!1===f.isTicketsLoaded){var e={UID:f.uid};l.getAllTickets(this,function(e,t){if(null!==t&&!0===t.result){r("#event_tickets_list").empty();var i=r("#tpl_tickets").html(),a=o.template(i);r.each(t.data,function(){var e=10;try{e=parseInt(this.MAXQTE)+1}catch(e){console.error(e)}s.isEmpty(e)&&(e=10);for(var t="",i=0;i<e;i++)t+='<option value="'+i+'">'+i+"</option>";var n=a({name:this.NAME,descl:this.DESCL,price:this.PRICE,id:this.ID,select_name:"TICKET_QTE",maxqte:t});r("#event_tickets_list").append(n),0})}},e),f.isTicketsLoaded=!0}},openViewTickets:function(){f.modal_view_tickets.open()},initEditMode:function(){requirejs(["places","moment","parsley","jqsobject","flow","c/datepicker/js/datepicker.min","leaflet","app/modules/geolocation_form_map","app/modules/CropImage","app/modules/textEditor"],function(e,t,i,n,a,o,s,c,l,d){r(document).on("click","#btncancel",f.onCancel),r(document).on("click","#btn_edit_dates",f.onOpenModalDates),r(document).on("click","#btn_edit_img",f.onOpenModalImg),r(document).on("click","#btn_manage_ticket",f.onOpenModalAdminTickets),r(document).on("submit","#feventdesc",f.onSubmitDesc),r(document).on("submit","#feventdates",f.onSubmitDates),r(document).on("submit","#feventvenue",f.onSubmitVenue),r(document).on("submit","#feventtags",f.onSubmitTags),r(document).on("submit","#fcreateticket",f.onSubmitTickets),r(document).on("click","#btn_fcreateticket_create",f.onCreateTicket),r(document).on("click",".fcreateticket_delete",f.onDeleteTickets),r(document).on("click",".fcreateticket_edit",f.onEditTicket),r(document).on("click","#btn_fcreateticket_cancel",f.showListTicket),f.modal_img=new u.modal({footer:!0,stickyFooter:!1,closeMethods:["overlay","button","escape"],closeLabel:"Close",cssClass:["custom-class-1","custom-class-2"],onOpen:function(){var t=new l;t.setOptions({enableExif:!0,enableZoom:!0,showZoomer:!1,viewport:{width:851,height:315},boundary:{width:851,height:315}}),t.setUid(f.uid),t.setUploadToken("6684"),t.setUploadCropZone(r("#upload-demo")),r("#upload").on("change",function(){!0===t.readFile(this)&&r("#upload_btn_save").css("visibility","visible")}),t.setCallBack(function(e){var t=JSON.parse(e);!0===t.success&&(document.getElementById("back_img").src=t.flowIdentifier,r("#upload_btn_save").css("visibility","hidden"),f.modal_img.close())}),r(".upload-result").on("click",function(e){t.crop()}),r("#upload_btn_save").css("visibility","hidden"),t.setUploadCropZone(r("#upload-demo"))},onClose:function(){},beforeClose:function(){return!0}}),f.modal_img.setContent(document.getElementById("panel_edit_img").innerHTML),(new d).init("#EVTDESC"),f.loadTagsCombo(),(new c).init(),f.modal_dates=new u.modal({footer:!0,stickyFooter:!1,closeMethods:["overlay","button","escape"],closeLabel:"Close",cssClass:["custom-class-1","custom-class-2"],onOpen:function(){requirejs(["c/datepicker/js/i18n/datepicker."+f.lang],function(e){var t=r("#allday").is(":checked");f.datepicker_start=f.initStartCalendar(t),f.datepicker_end=f.initEndCalendar(t),null!==f.getDateEnd()&&f.datepicker_end.selectDate(f.getDateEnd()),null!==f.getDateBegin()&&f.datepicker_start.selectDate(f.getDateBegin())}),r("#allday").change(function(){var e=!!this.checked;f.datepicker_start.destroy(),f.datepicker_end.destroy(),f.datepicker_start=f.initStartCalendar(e),f.datepicker_end=f.initEndCalendar(e)})},onClose:function(){},beforeClose:function(){return!0}}),f.modal_dates.setContent(document.getElementById("panel_edit_dates").innerHTML),f.modal_admin_tickets=new u.modal({footer:!0,stickyFooter:!1,closeMethods:["overlay","button","escape"],closeLabel:"Close",cssClass:["custom-class-1","custom-class-2"],onOpen:function(e){f.loadTicketsCreateView(e),f.showListTicket(e)},onClose:function(){},beforeClose:function(){return!0}}),f.modal_admin_tickets.setContent(document.getElementById("panel_edit_admin_tickets").innerHTML)})},onOpenModalImg:function(){f.modal_img.open()},onOpenModalDates:function(){f.modal_dates.open()},onOpenModalAdminTickets:function(){f.modal_admin_tickets.open()},getDateNowPicker:function(){var e=new Date;return e.setHours(e.getHours()+2),e.setMinutes(0),e.setSeconds(0),e.setMilliseconds(0),e},getDateBegin:function(){var e=r("#start_date_formatted").val();return e?new Date(e):null},getDateEnd:function(){var e=r("#end_date_formatted").val();return e?new Date(e):null},initStartCalendar:function(e){f.getDateBegin();var n=!0;return r("#start_date").datepicker({timepicker:!e,language:f.lang,container:"#modal_edit_dates",inline:!1,startDate:f.getDateNowPicker(),selectDate:f.getDateBegin(),minDate:f.getDateNowPicker(),minutesStep:5,altField:"#start_date_formatted",altFieldDateFormat:"yyyy-mm-dd hh:ii",clearButton:!0,autoClose:!1,onSelect:function(e,t,i){n||f.datepicker_end.update({minDate:t}),n=!1}}).data("datepicker")},initEndCalendar:function(e){f.getDateEnd();var n=!0;return r("#end_date").datepicker({timepicker:!e,language:f.lang,startDate:f.getDateNowPicker(),minDate:f.getDateNowPicker(),minutesStep:5,altField:"#end_date_formatted",altFieldDateFormat:"yyyy-mm-dd hh:ii",clearButton:!0,autoClose:!1,position:"top left",onSelect:function(e,t,i){n||f.datepicker_start.update({maxDate:t}),n=!1}}).data("datepicker")},loadTicketsCreateView:function(){var e={UID:f.uid};l.getAllTickets(this,function(e,t){if(null!==t&&!0===t.result){r("#event_tickets_edit_list").empty();var i=r("#tpl_tickets_edit").html(),n=o.template(i);r.each(t.data,function(){var e=n({name:this.NAME,descl:this.DESCL,price:this.PRICE,qte:this.QTE,id:this.ID,select_name:"TICKET_QTE_EDIT"});r("#event_tickets_edit_list").append(e),0})}},e)},onPublishEvent:function(e,t){null!==t&&!0===t.result?r("#panel-pub").hide():r("#btnPublish").prop("disabled",!0)},publish:function(){var e={UID:f.uid};r("#publish").prop("disabled",!0),c.publishEvent(this,f.onPublishEvent,e)},attend:function(e){e.stopPropagation(),e.preventDefault();var t={UID:f.uid};return f.btninterested.prop("disabled",!0),c.attend(this,function(e,t){null!==t&&!0===t.result&&(f.btninterested.css("display","none"),f.btndisinterested.css("display","block")),f.btninterested.prop("disabled",!1)},t),!1},unattend:function(e){e.stopPropagation(),e.preventDefault();var t={UID:f.uid};return f.btndisinterested.prop("disabled",!0),c.unAttend(this,function(e,t){null!==t&&!0===t.result&&(f.btndisinterested.css("display","none"),f.btninterested.css("display","block")),f.btndisinterested.prop("disabled",!1)},t),!1},loadTagsView:function(){var e={UID:this.uid};c.getTags(this,function(t,e){if(null!==e&&!0===e.result){var i=0;r("#tags_list").empty();var n=r("#tpl_tags").html(),a=o.template(n);r.each(e.data,function(){var e=a({value:this.TAG,text:this.DESC});r("#tags_list").append(e),t.tags_selected[i]=this.TAG,i++})}},e)},loadTagsCombo:function(){if(null!=document.getElementById("tags")){a.loadAllTags(this,function(t,e){if(null!==e&&!0===e.result){var i=r("#tags");i.find("option").remove(),r.each(e.data,function(){var e="";-1<r.inArray(this.value,t.tags_selected)&&(e="selected"),i.append('<option value="'+this.value+'"'+e+">"+this.text+"</option>")})}},{L:"fr"})}},onCancel:function(){if(1==confirm("êtes vous sur de vouloir annuler ?")){var e={UID:f.uid};c.cancelEvent(this,function(e,t){if(null!==t&&!0===t.result){var i=f.lang+"/results";setTimeout(function(){window.location.href=i},1e3)}},e)}},onSubmitDesc:function(e){try{if(!r(f.form_desc).parsley().isValid())return;var t=r(f.form_desc).serializeJSON(),i=r(".ql-editor").html();if("<p><br></p>"===i)return void e.preventDefault();if(t.DESCL=i,0===Object.keys(t).length)return;c.modifyEventDesc(this,function(e,t){null!==t&&!0===t.result?(r("#modal_edit_desc").modal("hide"),window.location.reload(!0)):alert("Erreur, veuillez reessayer !")},t)}finally{e.preventDefault()}},onSubmitDates:function(e){try{if(!r(f.form_dates).parsley().isValid())return;var t=r(f.form_dates).serializeJSON();if(0===Object.keys(t).length)return;c.modifyEventDate(this,function(e,t){null!==t&&!0===t.result&&(r("#modal_edit_dates").modal("hide"),window.location.reload(!0))},t)}finally{e.preventDefault()}},onSubmitVenue:function(e){try{if(!r(f.form_venue).parsley().isValid())return;var t=r(f.form_venue).serializeJSON();if(0===Object.keys(t).length)return;t.UID=f.uid,c.modifyEventVenue(this,function(e,t){null!==t&&!0===t.result&&(r("#wvenue").modal("hide"),window.location.reload(!0))},t)}catch(e){console.log(e)}finally{e.preventDefault()}},onSubmitTags:function(e){try{if(!r(f.form_tags).parsley().isValid())return;var t=r(f.form_tags).serializeJSON();if(0===Object.keys(t).length)return;c.modifyEventTags(this,function(e,t){null!==t&&!0===t.result&&(f.loadTagsView(),r("#modal_edit_tags").modal("hide"))},t)}finally{e.preventDefault()}},onSubmitTickets:function(i){try{if(i.preventDefault(),!r(f.form_tickets).parsley().isValid())return;var e=r(f.form_tickets).serializeJSON();if(0===Object.keys(e).length)return;console.log(e),""===e.ID?l.create(this,function(e,t){null!==t&&!0===t.result?(console.log("submit tic ok"),f.showListTicket(i),f.loadTicketsCreateView()):console.log("submit tic pas ok")},e):l.modify(this,function(e,t){null!==t&&!0===t.result?(console.log("submit mod tic ok"),f.showListTicket(i),f.loadTicketsCreateView()):console.log("submit mod tic ok")},e)}finally{i.preventDefault()}},onCreateTicket:function(e){r("#fcreateticket_id").val(""),r("#fcreateticket_name").val(""),r("#fcreateticket_descl").val(""),r("#fcreateticket_qte").val(""),r("#fcreateticket_price").val(""),r("#fcreateticket_minqte").val("1"),r("#fcreateticket_maxqte").val("10"),f.showEditListTicket(e,"")},onEditTicket:function(i){var n=r(this).data("ticket-id"),e={UID:f.uid,ID:n};l.getTicket(this,function(e,t){null!==t&&!0===t.result&&(r("#fcreateticket_id").val(t.data.ID),r("#fcreateticket_name").val(t.data.NAME),r("#fcreateticket_descl").val(t.data.DESCL),r("#fcreateticket_qte").val(t.data.QTE),r("#fcreateticket_price").val(t.data.PRICE),r("#fcreateticket_minqte").val(t.data.MINQTE),r("#fcreateticket_maxqte").val(t.data.MAXQTE),f.showEditListTicket(i,n))},e)},showListTicket:function(e){r("#fcreateticket_panel_create").css("display","none"),r("#btn_fcreateticket_save").css("display","none"),r("#btn_fcreateticket_save").prop("disabled",!0),r("#btn_fcreateticket_cancel").css("display","none"),r("#btn_fcreateticket_cancel").prop("disabled",!0),r("#fcreateticket_panel_view").css("display","block"),r("#btn_fcreateticket_create").css("display","block"),r("#btn_fcreateticket_create").prop("disabled",!1)},showEditListTicket:function(e,t){r("#fcreateticket_panel_create").css("display","block"),r("#btn_fcreateticket_save").css("display","block"),r("#btn_fcreateticket_save").prop("disabled",!1),r("#btn_fcreateticket_cancel").css("display","block"),r("#btn_fcreateticket_cancel").prop("disabled",!1),r("#fcreateticket_panel_view").css("display","none"),r("#btn_fcreateticket_create").css("display","none"),r("#fcreateticket_id").val(t)},onDeleteTickets:function(e){if(1==confirm("êtes vous sur de vouloir supprimer le ticket ?")){var t=r(this).data("ticket-id"),i={UID:f.uid,ID:t};l.delete(this,function(e,t){null!==t&&!0===t.result&&f.loadTicketsCreateView()},i)}},onChangeTicketsQte:function(e){var i=0,n=0;r('select[name^="TICKET_QTE"]').each(function(){var e=parseFloat(r(this).data("ticket-price")),t=parseFloat(this.value);0<t&&(n+=t,i+=t*e)}),0<n?r("#btn_checkout_tickets").prop("disabled",!1):r("#btn_checkout_tickets").prop("disabled",!0),r("#total_qte_tickets").text(n),r("#total_price_tickets").text(Number(i).toLocaleString("fr-BE",{minimumFractionDigits:2})+" €")}};f.init()});