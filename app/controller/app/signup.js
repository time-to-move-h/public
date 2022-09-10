"use strict";
requirejs(['jquery','parsley','parsley/fr','jqsobject','c/datepicker/js/datepicker','toastr','app/modules/Utils','app/modules/Profile','c/tel/js/tel','c/tel/js/utils'], function($,parsley,parsley_lang,jqsobject,datepicker,toastr,Utils,Profile,tel,intlTelInputUtils) {

var signup = {
    form_name: '#fsignup',
    btn_link_validation : '#btn_link_validation',
    telInput: $("#mphone"),
    lang : $('html').closest('[lang]').attr('lang') || 'en',
    init: function() {
        $(document).on('submit', signup.form_name, signup.onSubmitForm);
        $(document).on('click', signup.btn_link_validation, signup.redirect_validation);
        this.initTel();

        // requirejs(['c/datepicker/js/i18n/datepicker.' + signup.lang], function (datepicker_lang) {
        //     signup.initBirthDate();
        // });
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

    initBirthDate: function() {

        return $('#bdate').datepicker({
            timepicker: false,
            language: 'en',
            startView: 2,
            minViewMode: 1,
            maxViewMode: 2,
            altFieldDateFormat: 'yyyy-mm-dd',
            clearButton: true,
            autoClose: true
        }).data('datepicker');

    },

    onSubmitForm: function(e) {
        try {
            signup.hideError();

            // Phone validation
            if ($.trim(signup.telInput.val())) {
                var isValid = signup.telInput.intlTelInput("isValidNumber");
                //console.log(isValid);
                if (isValid) {
                    signup.telInput.removeClass('parsley-error');
                    signup.telInput.addClass('parsley-success');
                } else {
                    signup.telInput.removeClass('parsley-error');
                    signup.telInput.removeClass('parsley-success');
                    signup.telInput.addClass('parsley-error');
                    return;
                }
            } else {
                signup.telInput.removeClass('parsley-error');
            }

            var instance = $(signup.form_name).parsley();
            if (!instance.isValid()) return;
            var jsonObj = $(signup.form_name).serializeJSON();
            if (Object.keys(jsonObj).length === 0) return;

            signup.disableBtn();

            if ($.trim(signup.telInput.val())) {
                var intlNumber = signup.telInput.intlTelInput("getNumber");
                jsonObj["MPHONE"] = intlNumber;
            }

            var profile = new Profile();
            profile.signup(this, function (self, model) {

                signup.enableBtn();

                if (model !== null && model.result === true) {
                    jQuery('#panel_form').css("display", "none");
                    jQuery('#panel_success').css("display", "block");
                    signup.signup_redirect();
                } else {
                    if (model != null && (model.code === 56237620 || model.code === 71610534)) {
                        signup.showError(model.code);
                    } else {
                        signup.showError(null);
                    }
                }
            }, jsonObj);
        } catch (e) {
            //console.log(e);
            signup.enableBtn();
        } finally {
            e.preventDefault();
        }
    },

    signup_redirect: function() {
        var surl = "/home";
        //setTimeout(function () {window.location.href = surl;}, 5000);
    },

    redirect_validation: function() {
        var surl = "/signup/validation?a=" + document.getElementById("email").value;
        window.location.href = surl;
    },

    disableBtn: function() {
        $("#bsubmit").prop("disabled", true);
        $("#bsubmit > span").show();
    },

    enableBtn: function() {
        $("#bsubmit").prop("disabled", false);
        $("#bsubmit > span").hide();
    },

    showError: function(code) {

        if (code === 56237620 || code === 71610534) {
            $('#error_acc_exist').css("display", "block");
            $('#error_acc_exist').css("visibility", "visible");
        } else {
            $('#error_unknown').css("display", "block");
            $('#error_unknown').css("visibility", "visible");
        }

    },

    hideError: function () {
        //document.getElementById(this.$error_unknown).style.visibility = 'hidden';
        //document.getElementById(this.$error_unknown).style.display = 'none';
        $('#error_msg').css("display", "none");
        $('#error_msg').css("visibility", "hidden");
        $('#error_unknown').css("display", "none");
        $('#error_unknown').css("visibility", "hidden");
    }

};

signup.init();

});