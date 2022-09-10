"use strict";
requirejs(['jquery','parsley','jqsobject','moment','app/modules/Generic','app/modules/Channel','app/modules/formSection','app/modules/textEditor'], function($,parsley,jqsobject,moment,Generic,Channel,formSection,textEditor) {

var create_cha = {

    generic: null,
    channel: null,
    section: null,
    editor: null,
    panel_success: 'sub_success',

    init: function () {
        this.generic = new Generic();
        this.channel = new Channel();
        this.section = new formSection();
        this.editor = new textEditor();
        this.section.init(null,null);
        this.section.navigateTo(0);
        this.editor.init('#CHADESC');
        $(document).on('submit', '#formChannel', create_cha.onSubmitForm);
    },

    redirect_event: function() {
        var surl = "/create_event";
        window.location.href = surl;
    },

    onSubmitForm: function(e) {

        var instance = $("#formChannel").parsley();
        try {
            create_cha.hideError();
            //$('#btn_publish').prop('disabled', true);
            if (! instance.isValid()) return;
            var jsonObj = $("#formChannel").serializeJSON();
            var data_descl = $(".ql-editor").html();
            if($(".ql-editor").html() === '<p><br></p>') {
                e.preventDefault();
                return;
            }
            jsonObj['DESCL'] = data_descl;
            //console.log(jsonObj);
            if (Object.keys(jsonObj).length === 0) return;
            create_cha.createChannel(jsonObj);
        } finally {
            e.preventDefault();
        }

    },

    createChannel: function(jsonObj) {
        try {
            create_cha.disableBtn();

            create_cha.channel.createChannel(self,function(self, model) {
                if (model !== null && model.result === true) {
                    create_cha.showSuccess();
                } else {
                    $('#btn_publish').prop('disabled', false);
                    if (model !== null && model.code == 888) {
                        create_cha.enableBtn();
                        // $('#error_name_used').css("display", "block");
                        // $('#error_name_used').css("visibility", "visible");
                        // $('#name').removeClass("parsley-success");
                        // $('#name').addClass("parsley-error");
                    } else {
                        create_cha.enableBtn();
                        create_cha.showError();
                    }
                }
            },jsonObj);

        } catch (e) {
            create_cha.enableBtn();
            console.log(e);
        }
    },

    disableBtn: function() {
        document.getElementById('btn_publish').disabled = true;
        $("#bsubmit > span").show();
    },

    enableBtn: function() {
        document.getElementById('btn_publish').disabled = false;
        $("#bsubmit > span").hide();
    },

    showSuccess: function() {
        document.getElementById(create_cha.panel_success).style.visibility = 'visible';
        document.getElementById(create_cha.panel_success).style.display = 'block';

        document.getElementById('formChannel').style.visibility = 'hidden';
        document.getElementById('formChannel').style.display = 'none';
    },

    showError: function(code) {

        // if (code == '7200550933') {
        //     document.getElementById(this.$error_account_disabled).style.visibility = 'visible';
        //     document.getElementById(this.$error_account_disabled).style.display = 'block';
        // } else {
        //     document.getElementById(this.$error_unknown).style.visibility = 'visible';
        //     document.getElementById(this.$error_unknown).style.display = 'block';
        // }

        $('#error_unknown').css("display", "block");
        $('#error_unknown').css("visibility", "visible");

    },

    hideError: function () {
        // document.getElementById(this.$error_unknown).style.visibility = 'hidden';
        // document.getElementById(this.$error_unknown).style.display = 'none';

        // $('#error_name_used').css("display", "none");
        // $('#error_name_used').css("visibility", "hidden");
        $('#error_unknown').css("display", "none");
        $('#error_unknown').css("visibility", "hidden");
    }

};

create_cha.init();
});