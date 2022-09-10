"use strict";
define (["./callrpc"],function(callrpc) {
    return function() {                                
        function Profile () {}            
        // Login phase 1
        Profile.login_p1 = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/profile','login_p1');
        }
        // Login phase 2
        Profile.login_p2 = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/profile','login_p2');
        }
        // Login facebook
        Profile.login_fb = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/profile','login_facebook_v6');
        }
        // Login google
        Profile.login_gg = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/profile','login_google_v1');
        }
        // get Contacts
        Profile.getContacts = function(self,callback,jsonObj) {            
            callrpc([],self,callback,'/backend/profile','getContacts');
        }        
        // 
        Profile.search = function(self,callback,jsonObj) {            
            callrpc([jsonObj],self,callback,'/backend/profile','search');
        }
        // Load Members
        Profile.loadMembers = function(self,callback,jsonObj) {
            //var json = {"URLLINK" : self.urllink};
            callrpc([jsonObj],self,callback,'/backend/profile','getEventMembers');            
        }        
        // Validate Account
        Profile.validateAccount = function(self,callback,jsonObj) {                        
            callrpc([jsonObj],self,callback,'/backend/profile','validate');
        }        
        // Signup
        Profile.signup = function(self,callback,jsonObj) {                                    
            callrpc([jsonObj],self,callback,'/backend/profile','signup');
        }
        // Recover PWD
        Profile.recoverPwd = function(self,callback,jsonObj) {                                                            
            callrpc([jsonObj],self,callback,'/backend/profile','recover');
        }
        // resetPwd
        Profile.resetPwd = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/profile','resetPwd');
        }
        // Save Search Preference
        Profile.saveSearchPreference = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/profile','saveSearchPreference');
        }
        // Save User Business Information
        Profile.saveBusinessInformation = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/profile','saveBusinessInformation');
        }
        return Profile;        
    }
});