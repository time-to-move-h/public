"use strict";
requirejs(['jquery','parsley','parsley/fr','jqsobject','toastr','app/modules/Utils','app/modules/Timer','app/modules/Ticket','c/tel/js/tel','c/tel/js/utils'], function($,parsley,parsley_lang,jqsobject,toastr,Utils,Timer, Ticket,tel,intlTelInputUtils) {

var utils = new Utils();
var timer = new Timer();
var ticket = new Ticket();

var checkout = {
    form_name: '#fcheckouttickets',
    telInput: null,
    isSubmitting: false,
    init: function() {

        if (document.getElementById('fcheckouttickets') != null) {

            this.ticket = new Ticket();

            var myEvent = window.attachEvent || window.addEventListener;
            var chkevent = window.attachEvent ? 'onbeforeunload' : 'beforeunload'; /// make IE7, IE8 compatible

            myEvent(chkevent, function (e) { // For >=IE7, Chrome, Firefox
                var confirmationMessage = null;
                if (checkout.isSubmitting == false) {
                    confirmationMessage = 'Are you sure to leave the page?';
                    (e || window.event).returnValue = confirmationMessage;
                }
                return confirmationMessage;
            });

            window.addEventListener('unload', function (event) {
                if (checkout.isSubmitting == false) {
                    checkout.unlockTicket();
                }
            });

            $(document).on('submit', checkout.form_name, checkout.onSubmitForm);

            document.getElementById('btn_cancel').onclick = function() {
                try {
                    checkout.isSubmitting = true;
                    checkout.unlockTicket();
                    window.history.go(-1);
                    return false;
                } catch (e) {
                    console.log(e);
                }
            };

            this.telInput = $("#mphone");
            this.initTel();

            var minutes_remaining = null;
            if (document.getElementById("timeout") != null) {
                minutes_remaining = document.getElementById("timeout").value;
            }

            //var end = new Date(timestamp * 1000);
            //console.log(end);
            // var now = new Date();
            // var nowUTC = new Date(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), now.getUTCHours(), now.getUTCMinutes(), now.getUTCSeconds());
            // var distance = (end.getTime() - nowUTC.getTime());
            // console.log(nowUTC);

            var msg_timeout = null;
            var page_timeout = null;

            if (document.getElementById("timeout_msg") != null) {
                msg_timeout = document.getElementById("timeout_msg").value;
            }

            if (document.getElementById("urllink") != null) {
                page_timeout = document.getElementById("urllink").value;
            }

            //window.onload = function () {
            //var countdown = 60 * 10;
            if (minutes_remaining != null) {
                var display = document.querySelector('#time');
                timer.startTimer(minutes_remaining, display, page_timeout, msg_timeout);
            }
            //};

        }
    },

    unlockTicket: function() {
        var jsonObj = {};
        ticket.unlockTicket(this, function (self, model) {
            if (model !== null && model.result === true) {}
        }, jsonObj);

    },

    initTel: function() {
        // Phone
        this.telInput.intlTelInput({
            //utilsScript: "/dist/c/tel/js/utils",
            initialCountry: "nl",
            allowDropdown: true,
            onlyCountries: ["be","fr","bg","nl","de","lu","es","uk"],
            getNumber: 0
        }).done(function() {});
    },

    onSubmitForm: function(e) {

        checkout.isSubmitting = true;
        var mphone_value = '';
        // Phone validation
        if ($.trim(checkout.telInput.val())) {
            var isValid = checkout.telInput.intlTelInput("isValidNumber");
            //console.log(isValid);
            if (isValid) {
                mphone_value = checkout.telInput.intlTelInput("getNumber");
                checkout.telInput.removeClass('parsley-error');
                checkout.telInput.addClass('parsley-success');
            } else {
                checkout.telInput.removeClass('parsley-error');
                checkout.telInput.removeClass('parsley-success');
                checkout.telInput.addClass('parsley-error');
                return false;
            }
        } else {
            checkout.telInput.removeClass('parsley-error');
        }
        utils.setValueByID("mphone_result",mphone_value);
    }
};

checkout.init();

});