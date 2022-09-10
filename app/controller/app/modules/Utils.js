"use strict";
define (function() {
    return function() {        
        function Utils () {}            
        // Set cookies
        Utils.setCookie = function(cname, cvalue, exdays) {            
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }        
        // Get cookies
        Utils.getCookie = function(cname) {            
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }        
        Utils.isNumber = function(n) {         
            return !isNaN(parseFloat(n)) && isFinite(n);
        }
        Utils.setValueByID = function(id,value) {        
            if (document.getElementById(id) != null) {
                document.getElementById(id).value = value;
                return true
            } else {
                return false;
            }
        }
        Utils.setValueHtmlByID = function (id,value) {
            if (document.getElementById(id) != null) {
                document.getElementById(id).innerHTML = value;
                return true
            } else {
                return false;
            }
        }
        Utils.getValueByID = function (id) {
            if (document.getElementById(id) != null) {
                return document.getElementById(id).value;
            } else {
                return null;
            }
        }        
        Utils.getUserLanguage = function() {
            var surl = window.location.pathname; 
            var lang = surl.match(/[a-z]{2}\-[A-Z]{2}/);         
            //console.log(lang);    
            if (lang !== null) {
                lang = "/" + lang;
            } else {
                lang = "";
            }
            return lang;
        }                
        Utils.getUrlParameter = function(sParam) {
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');
            for (var i = 0; i < sURLVariables.length; i++)
            {
                var sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] == sParam)
                {
                    return sParameterName[1];
                }
            }
        }
        Utils.isEmpty = function(data) {
            if(typeof(data) == 'number' || typeof(data) == 'boolean')
            {
                return false;
            }
            if(typeof(data) == 'undefined' || data === null)
            {
                return true;
            }
            if(typeof(data.length) != 'undefined')
            {
                return data.length == 0;
            }
            var count = 0;
            for(var i in data)
            {
                if(data.hasOwnProperty(i))
                {
                    count ++;
                }
            }
            return count == 0;
        }

        return Utils;
    }
});