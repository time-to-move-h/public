"use strict";
requirejs(['jquery','parsley','parsley/fr','jqsobject','jquery.loading','app/modules/Profile'], function($,parsley,parsley_lang,jqsobject,jqloading,Profile) {

var pickacity = {

    profile: null,
    panel_success: 'sub_success',
    panel_failed: 'sub_failed',

    init: function () {

        this.profile = new Profile();

        document.getElementById('fpickacity').onsubmit = function() {
            try {
                pickacity.onSubmitForm();
            } catch (e) {
                console.log(e);
            } finally {
                return false;
            }
        };

    },

    wall_redirect: function() {
        var surl = "/home";
        window.location.href = surl;
    },

    onSubmitForm: function() {
        try {
            this.hideError();
            var instance = $('#fpickacity').parsley();
            if (! instance.isValid()) {
                return;
            }
            var jsonObj = $('#fpickacity').serializeJSON();

            //console.log(jsonObj);

            if (jsonObj['CITY']) {
                this.onSaveSearchPreference(jsonObj);
            } else {

            }
        } catch (e) {
            //console.log(e);
        }
    },

    onSaveSearchPreference: function(jsonObj) {
        try {
            this.disableBtn();
            this.profile.saveSearchPreference(this,function(self,model) {
                
                //console.log(model);

                if (model !== null && model.result === true) {
                    self.wall_redirect();
                } else if (model !== null && model.code !== null) {
                    self.enableBtn();
                    self.showError(model.code);
                } else {
                    self.enableBtn();
                    self.showError(null);
                }

            }, jsonObj);
        } catch (e) {
            self.enableBtn();
            //console.log(e);
        }
    },

    disableBtn: function() {
        document.getElementById('bsubmit').disabled = true;
        $("#bsubmit > span").show();
    },

    enableBtn: function() {
        document.getElementById('bsubmit').disabled = false;
        $("#bsubmit > span").hide();
    },

    showError: function(code) {

        // if (code == '7200550933') {
        //     document.getElementById(this.$error_account_disabled).style.visibility = 'visible';
        //     document.getElementById(this.$error_account_disabled).style.display = 'block';
        // } else {
        //     document.getElementById(this.$error_unknown).style.visibility = 'visible';
        //     document.getElementById(this.$error_unknown).style.display = 'block';
        // }

    },

    hideError: function () {
        // document.getElementById(this.$error_unknown).style.visibility = 'hidden';
        // document.getElementById(this.$error_unknown).style.display = 'none';
    }

};
pickacity.init();
});