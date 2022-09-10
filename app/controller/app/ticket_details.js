"use strict";
requirejs(['jquery','parsley','parsley/fr','jqsobject','app/modules/Ticket','c/tingle/tingle.min'], function($,parsley,parsley_lang,jqsobject,Ticket,tingle) {

var ticket_details = {
    form_name: 'fticket_details',
    $btn_submit: 'btn_ticket_details',
    ticket: null,

    init: function () {
        this.ticket = new Ticket();
        document.getElementById(this.form_name).onsubmit = function() {
            try {
                ticket_details.onSubmitForm();
            } catch (e) {
                console.log(e);
            } finally {
                return false;
            }
        };




        $(document).on('click', ".qrcode", ticket_details.openViewToken);

        // Modal View Tickets
        ticket_details.modal_token = new tingle.modal({
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
        ticket_details.modal_token.open();
        ticket_details.modal_token.setContent('<img class="qrcode" src="/util/qrcode?s=qrl&amp;w=500&amp;h=500&amp;d=' + $(this).data('token') + '">');
    },

    onSubmitDetail: function(jsonObj) {
        try {
            this.disableBtn();
            this.ticket.updateTicketDetails(this,function(self,model) {
                if (model !== null && model.result === true) {
                    //self.wall_redirect();
                    alert("ok");
                } else {
                    alert("pas ok");
                    //document.getElementById(self.$error_unknown).style.visibility = 'visible';
                    //document.getElementById(self.$error_unknown).style.display = 'block';
                }
                self.enableBtn();
            },jsonObj);
        } catch (e) {
            console.log(e);
        }
    },

    wall_redirect: function() {
        var surl = "/results";
        window.location.href = surl;
    },

    onSubmitForm: function() {
        try {
            //document.getElementById(this.$error_unknown).style.visibility = 'hidden';
            //document.getElementById(this.$error_unknown).style.display = 'none';
            var instance = $('#' + this.form_name).parsley();
            if (! instance.isValid()) {
                return;
            }
            var jsonObj = $('#' + this.form_name).serializeJSON();

            if (jsonObj) {
                //console.log(jsonObj);
                this.onSubmitDetail(jsonObj);
            }
        } catch (e) {
            console.log(e);
        }
    },

    disableBtn: function() {
        document.getElementById(this.$btn_submit).disabled = true;
    },

    enableBtn: function() {
        document.getElementById(this.$btn_submit).disabled = false;
    }

};

ticket_details.init();

});