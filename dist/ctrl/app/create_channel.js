"use strict";requirejs(["jquery","parsley","jqsobject","moment","app/modules/Generic","app/modules/Channel","app/modules/formSection","app/modules/textEditor"],function(l,e,n,t,i,s,o,r){var c={generic:null,channel:null,section:null,editor:null,panel_success:"sub_success",init:function(){this.generic=new i,this.channel=new s,this.section=new o,this.editor=new r,this.section.init(null,null),this.section.navigateTo(0),this.editor.init("#CHADESC"),l(document).on("submit","#formChannel",c.onSubmitForm)},redirect_event:function(){window.location.href="/create_event"},onSubmitForm:function(e){var n=l("#formChannel").parsley();try{if(c.hideError(),!n.isValid())return;var t=l("#formChannel").serializeJSON(),i=l(".ql-editor").html();if("<p><br></p>"===l(".ql-editor").html())return void e.preventDefault();if(t.DESCL=i,0===Object.keys(t).length)return;c.createChannel(t)}finally{e.preventDefault()}},createChannel:function(e){try{c.disableBtn(),c.channel.createChannel(self,function(e,n){null!==n&&!0===n.result?c.showSuccess():(l("#btn_publish").prop("disabled",!1),null!==n&&888==n.code?c.enableBtn():(c.enableBtn(),c.showError()))},e)}catch(e){c.enableBtn(),console.log(e)}},disableBtn:function(){document.getElementById("btn_publish").disabled=!0,l("#bsubmit > span").show()},enableBtn:function(){document.getElementById("btn_publish").disabled=!1,l("#bsubmit > span").hide()},showSuccess:function(){document.getElementById(c.panel_success).style.visibility="visible",document.getElementById(c.panel_success).style.display="block",document.getElementById("formChannel").style.visibility="hidden",document.getElementById("formChannel").style.display="none"},showError:function(e){l("#error_unknown").css("display","block"),l("#error_unknown").css("visibility","visible")},hideError:function(){l("#error_unknown").css("display","none"),l("#error_unknown").css("visibility","hidden")}};c.init()});