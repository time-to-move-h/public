"use strict";define(function(){return function(){function e(){}return e.setCookie=function(e,t,n){var r=new Date;r.setTime(r.getTime()+24*n*60*60*1e3);var u="expires="+r.toUTCString();document.cookie=e+"="+t+";"+u+";path=/"},e.getCookie=function(e){for(var t=e+"=",n=document.cookie.split(";"),r=0;r<n.length;r++){for(var u=n[r];" "==u.charAt(0);)u=u.substring(1);if(0==u.indexOf(t))return u.substring(t.length,u.length)}return""},e.isNumber=function(e){return!isNaN(parseFloat(e))&&isFinite(e)},e.setValueByID=function(e,t){return null!=document.getElementById(e)&&(document.getElementById(e).value=t,!0)},e.setValueHtmlByID=function(e,t){return null!=document.getElementById(e)&&(document.getElementById(e).innerHTML=t,!0)},e.getValueByID=function(e){return null!=document.getElementById(e)?document.getElementById(e).value:null},e.getUserLanguage=function(){var e=window.location.pathname.match(/[a-z]{2}\-[A-Z]{2}/);return e=null!==e?"/"+e:""},e.getUrlParameter=function(e){for(var t=window.location.search.substring(1).split("&"),n=0;n<t.length;n++){var r=t[n].split("=");if(r[0]==e)return r[1]}},e.isEmpty=function(e){if("number"==typeof e||"boolean"==typeof e)return!1;if(null==e)return!0;if(void 0!==e.length)return 0==e.length;var t=0;for(var n in e)e.hasOwnProperty(n)&&t++;return 0==t},e}});