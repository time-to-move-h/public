"use strict";
requirejs(['jquery','parsley','parsley/fr','jqsobject','app/modules/Profile'], function($,parsley,parsley_lang,jqsobject,Profile) {

var recover = {
    fname: 'frecover',
    panel_form: 'sub_form',
    panel_success: 'sub_success',
    panel_failed: 'sub_failed',
    $error_unknown: 'error_unknown',
    $btn_submit: 'bsubmit',
    $p1: 'p1',
    $p2: 'p2',
    profile: null,

    init: function () {
        this.profile = new Profile();
        document.getElementById(this.fname).onsubmit = function() {
            try {
                recover.onSubmitForm();
            } catch (e) {
                //console.log(e);
            } finally {
                return false;
            }
        };
    },

    onRecover: function(jsonObj) {
        try {
            this.disableBtn();
            this.profile.resetPwd(this,function(self,model) {
                if (model !== null && model.result === true) {
                    // recover pwd success
                    document.getElementById(self.panel_success).style.visibility = 'visible';
                    document.getElementById(self.panel_success).style.display = 'block';
                    self.hideForm();
                } else {
                    // recover pwd failed
                    document.getElementById(self.panel_failed).style.visibility = 'visible';
                    document.getElementById(self.panel_failed).style.display = 'block';
                    self.hideForm();
                }
                self.enableBtn();
            },jsonObj);
        } catch (e) {
            console.log(e);
        }
    },

    onSubmitForm: function() {
        try {
            //document.getElementById(this.$error_unknown).style.visibility = 'hidden';
            //document.getElementById(this.$error_unknown).style.display = 'none';
            var instance = $('#'+this.fname).parsley();
            if (! instance.isValid()) {
                return;
            }
            var jsonObj = $('#'+this.fname).serializeJSON();
            if (jsonObj['P1'] && jsonObj['P2']) {
                this.onRecover(jsonObj);
            } else {
                //alert("error !");
            }
        } catch (e) {
            //console.log(e);
        }
    },

    wall_redirect: function() {
        var surl = "/login";
        window.location.href = surl;
    },
    disableBtn: function() {
        document.getElementById(this.$btn_submit).disabled = true;
    },
    enableBtn: function() {
        document.getElementById(this.$btn_submit).disabled = false;
    },
    hideForm: function () {
        document.getElementById(this.panel_form).style.visibility = 'hidden';
        document.getElementById(this.panel_form).style.display = 'none';
    },
    showForm: function () {
        document.getElementById(this.panel_form).style.visibility = 'visible';
        document.getElementById(this.panel_form).style.display = 'block';
    }
};
recover.init();
});