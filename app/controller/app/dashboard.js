"use strict";
requirejs(['jquery','parsley','parsley/fr','jqsobject','jquery.loading','app/modules/Profile','c/tingle/tingle.min'], function($,parsley,parsley_lang,jqsobject,jqloading,Profile,tingle) {

var dashboard = {

    profile: null,
    panel_success: 'sub_success',
    panel_failed: 'sub_failed',
    modal_token: null,

    init: function () {

        //this.profile = new Profile();

        // document.getElementById('fpickacity').onsubmit = function() {
        //     try {
        //         pickacity.onSubmitForm();
        //     } catch (e) {
        //         console.log(e);
        //     } finally {
        //         return false;
        //     }
        // };

        $(document).on('click', ".qrcode", dashboard.openViewToken);

        // Modal View Tickets
        dashboard.modal_token = new tingle.modal({
            footer: false,
            stickyFooter: false,
            closeMethods: ['overlay', 'button', 'escape'],
            closeLabel: "Close",
            cssClass: ['custom-class-1'],
            onOpen: function() {
                console.log('modal open');
            },
            onClose: function() {

            },
            beforeClose: function() {
                return true; // close the modal
            }
        });



    },

    openViewToken: function() {
        // set content
        //alert($(this).data('token'));
        dashboard.modal_token.open();
        dashboard.modal_token.setContent('<img class="qrcode" src="/util/qrcode?s=qrl&amp;w=500&amp;h=500&amp;d=' + $(this).data('token') + '">');
    },

    wall_redirect: function() {
        var surl = "/home";
        window.location.href = surl;
    },

    onSubmitForm: function() {
        // try {
        //     this.hideError();
        //     var instance = $('#fpickacity').parsley();
        //     if (! instance.isValid()) {
        //         return;
        //     }
        //     var jsonObj = $('#fpickacity').serializeJSON();
        //     if (jsonObj['CITY']) {
        //         this.onSaveSearchPreference(jsonObj);
        //     } else {
        //     }
        // } catch (e) {
        //     //console.log(e);
        // }
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
dashboard.init();
});