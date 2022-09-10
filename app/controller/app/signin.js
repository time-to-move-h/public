"use strict";
requirejs(['jquery','parsley','parsley/fr','jqsobject','jquery.loading','app/modules/Profile'], function($,parsley,parsley_lang,jqsobject,jqloading,Profile) {

// and here's the trick (works everywhere)
// function r(f){/in/.test(document.readyState)?setTimeout('r('+f+')',9):f()}
// // use like
// r(function(){
//     //alert('DOM Ready!');
// });

var login = {
    $panel_pwd: 'pwd_panel',
    $input_pwd_type: 'pwd_type',
    $input_pwd: 'pwd',
    $input_account: 'account',
    $error_unknown: 'error_unknown',
    $error_account_disabled: 'error_account_disabled',
    $label_pwd_otp: 'lbl_pwd_otp',
    $label_pwd: 'lbl_pwd',
    profile: null,
    $form_frecover: '#frecover',
    panel_success: 'sub_success',
    panel_failed: 'sub_failed',
    panel_account: 'sub_account',

    init: function () {
        this.profile = new Profile();
        $('#RForm').on('shown.bs.modal', function () { $('#raccount').focus(); });
        document.getElementById('fsignin').onsubmit = function() {
            try {
                login.onSubmitForm();
            } catch (e) {
                //console.log(e);
            } finally {
                return false;
            }
        };

        document.getElementById('btn_fb').onclick = function() {
            try {
                login.onsubmitFB();
            } catch (e) {
                //console.log(e);
            }
        };

        var txt_input = document.getElementById(this.$input_account);
        txt_input.addEventListener('change', function() {
            if (txt_input.value === '') {
                document.getElementById(login.$input_pwd).value = '';
                document.getElementById(login.$panel_pwd).style.visibility = 'hidden';
                document.getElementById(login.$panel_pwd).style.display = 'none';
                login.hideError();
                txt_input.focus();
            }
        });

        // Recover password
        $(document).on('submit', login.$form_frecover, login.onSubmitFormRecover);
    },
    
    initFB: function () {

        // facebook
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '867246163456148',
                cookie     : true,  // enable cookies to allow the server to access the session
                xfbml      : false,  // parse social plugins on this page
                version    : 'v13.0' // use graph api version 13.0
            });
        };
    
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    
    },

    initGG: function() {
        
        try {

            google.accounts.id.initialize({
              client_id: "901321113948-afpee2ivmq2vkejf1b4t59iai6b8vcc8.apps.googleusercontent.com",
              callback: login.onsubmitGG
            });

            google.accounts.id.renderButton(
                document.getElementById("btn_gg"),
                { theme: "filled_black", size: "large", width: "373" }  // customization attributes
            );    

            // one tap
            //google.accounts.id.prompt(); // also display the One Tap dialog
        
        } catch (e) {
            console.log(e);
        }

    },

    onLoginP1: function(jsonObj) {
        try {
            this.disableBtn();
            this.profile.login_p1(this,function(self,model) {
                if (model !== null && model.result === true) {
                    // Password Type
                    document.getElementById(self.$input_pwd_type).value = model.pwd_type;
                    // Block visible
                    if (model.pwd_type === '1') {
                        document.getElementById(self.$label_pwd_otp).style.visibility = 'visible';
                        document.getElementById(self.$label_pwd_otp).style.display = 'block';
                        document.getElementById(self.$label_pwd).style.visibility = 'hidden';
                        document.getElementById(self.$label_pwd).style.display = 'none';
                    } else {
                        document.getElementById(self.$label_pwd_otp).style.visibility = 'hidden';
                        document.getElementById(self.$label_pwd_otp).style.display = 'none';
                        document.getElementById(self.$label_pwd).style.visibility = 'visible';
                        document.getElementById(self.$label_pwd).style.display = 'block';
                    }

                    document.getElementById(self.$input_pwd).disabled = false;
                    document.getElementById(self.$panel_pwd).style.visibility = 'visible';
                    document.getElementById(self.$panel_pwd).style.display = 'block';
                    document.getElementById(self.$input_pwd).focus();
                } else {
                    self.showError();
                }
                self.enableBtn();
            },jsonObj);
        } catch (e) {
            self.enableBtn();
            console.log(e);
        }
    },

    onLoginP2: function(jsonObj) {
        try {
            this.disableBtn();
            this.profile.login_p2(this,function(self,model) {
                if (model !== null && model.result === true) {
                    self.wall_redirect();
                } else if (model.code !== null) {
                    self.enableBtn();
                    self.showError(model.code);
                } else {
                    self.enableBtn();
                    self.showError(null);
                }
            },jsonObj);
        } catch (e) {
            self.enableBtn();
            console.log(e);
        }
    },

    wall_redirect: function() {
        var surl = "/home";
        window.location.href = surl;
    },

    onSubmitForm: function() {
        try {
            this.hideError();
            var instance = $('#fsignin').parsley();
            if (! instance.isValid()) {
                return;
            }
            var jsonObj = $('#fsignin').serializeJSON();
            var step_pwd = document.getElementById(this.$panel_pwd).style.visibility;
            if (step_pwd === 'visible' && jsonObj['PWD']) {
                //var pwd_type = document.getElementById(this.$input_pwd_type).value;
                this.onLoginP2(jsonObj);
            } else {
                this.onLoginP1(jsonObj);
            }
        } catch (e) {
            //console.log(e);
        }
    },

    onSubmitFormRecover: function(e) {
        try {
            e.stopPropagation();
            e.preventDefault();

            var instance = $('#frecover').parsley();
            if (! instance.isValid()) {
                return;
            }

            var jsonObj = $('#frecover').serializeJSON();
            //console.log(jsonObj);

            if (jsonObj['account']) {
                login.onRecover(jsonObj);
            }

        } catch (e) {
            console.log(e);
        }
    },

    onRecover: function(jsonObj) {
        try {

            //this.disableBtn();
            login.profile.recoverPwd(this,function(self,model) {
                if (model !== null && model.result === true) {
                    // recover pwd success
                    document.getElementById(self.panel_failed).style.visibility = 'hidden';
                    document.getElementById(self.panel_failed).style.display = 'none';

                    document.getElementById(self.panel_success).style.visibility = 'visible';
                    document.getElementById(self.panel_success).style.display = 'block';
                    document.getElementById(self.panel_account).style.display = 'none';
                    document.getElementById('btn_frecover').style.display = 'none';

                    //self.hideForm();
                } else {
                    // recover pwd failed
                    document.getElementById(self.panel_failed).style.visibility = 'visible';
                    document.getElementById(self.panel_failed).style.display = 'block';
                    //self.hideForm();
                }
                //self.enableBtn();
            },jsonObj);
        } catch (e) {
            console.log(e);
        }
    },

    disableBtn: function() {
        document.getElementById('btn_fb').disabled = true;
        document.getElementById('bsubmit').disabled = true;
        $("#bsubmit > span").show();
        //$('#signin-wrapper').loading({ overlay: $('#custom-overlay') });
    },

    enableBtn: function() {
        document.getElementById('btn_fb').disabled = false;
        document.getElementById('bsubmit').disabled = false;
        $("#bsubmit > span").hide();
        //$('#signin-wrapper').loading('stop');
    },

    showError: function(code) {

        if (code == '7200550933') {
            document.getElementById(this.$error_account_disabled).style.visibility = 'visible';
            document.getElementById(this.$error_account_disabled).style.display = 'block';
        } else {
            document.getElementById(this.$error_unknown).style.visibility = 'visible';
            document.getElementById(this.$error_unknown).style.display = 'block';
        }

    },

    hideError: function () {
        document.getElementById(this.$error_unknown).style.visibility = 'hidden';
        document.getElementById(this.$error_unknown).style.display = 'none';
    },

    onsubmitFB: function () {
        try{
            this.disableBtn();
            FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                    try {
                        login.loginFacebook();
                    } catch (e) {
                        console.log(e);
                    }
                }
                else {
                    FB.login(function(response) {
                        if (response.authResponse) {
                            try {
                                login.loginFacebook();
                            } catch (e) {
                                console.log(e);
                            }
                        } else {
                            this.enableBtn();
                        }
                    });
                }
            });
    
        } catch (e) {
            //console.log(e);
        } finally {
            setTimeout(enableBtn, 15000);
        }
    }
    ,
    loginFacebook: function () {
        // Login Facebook profile
        try {
            var jsonObj = {};
            this.profile.login_fb(this, function(self, model) {
                if (null !== model && model.result === true) {
                    var surl = "/home";
                    window.location.href = surl;
                } else {
                    self.enableBtn();
                }
            }, jsonObj);
        } catch (e) {
            console.log(e);
        }
    },

    loginGoogle: function (response) {
        // Login Google Identity profile
        try {
            var jsonObj = {"token" : response.credential};
            this.profile.login_gg(this, function(self, model) {
                if (null !== model && model.result === true) {
                    var surl = "/home";
                    window.location.href = surl;
                } else {
                    self.enableBtn();
                }
            }, jsonObj);
        } catch (e) {
            console.log(e);
        }
    },
 
    onsubmitGG: function (response) {
        try {
            //this.disableBtn();          
            //console.log(response);

            login.loginGoogle(response);

            // decodeJwtResponse() is a custom function defined by you
            // to decode the credential response.
            // const responsePayload = this.decodeJwtResponse(response.credential);

            // console.log("ID: " + responsePayload.sub);
            // console.log('Full Name: ' + responsePayload.name);
            // console.log('Given Name: ' + responsePayload.given_name);
            // console.log('Family Name: ' + responsePayload.family_name);
            // console.log("Image URL: " + responsePayload.picture);
            // console.log("Email: " + responsePayload.email);
    
        } catch (e) {
            console.log(e);
        } finally {
            //setTimeout(enableBtn, 15000);
        }
    }


};


login.init();
login.initFB();
login.initGG();

});