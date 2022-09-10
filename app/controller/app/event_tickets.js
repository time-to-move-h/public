"use strict";
requirejs(['jquery','doT','app/modules/Utils','app/modules/Event','app/modules/Generic','app/modules/Ticket','c/tingle/tingle.min'], function($,doT,Utils,Event,Generic,Ticket,tingle) {

var generic = new Generic();
var utils = new Utils();
var event = new Event();
var ticket = new Ticket();

var evt = {
    form_desc: '#feventdesc',
    form_dates: '#feventdates',
    form_tags: '#feventtags',
    form_venue: '#feventvenue',
    form_tickets: '#fcreateticket',
    lang: $('html').closest('[lang]').attr('lang') || 'en',
    uid: utils.getValueByID("uid"),
    tags_selected: [],
    btninterested: $("#btninterested"),
    btndisinterested: $("#btndisinterested"),
    datepicker_start: null,
    datepicker_end: null,
    modal_dates: null,
    modal_img: null,
    modal_admin_tickets: null,
    isTicketsLoaded: false,

    init: function () {
        // $(document).on('click', "#publish", evt.publish);
        // $(document).on('click', "#btninterested", evt.attend);
        // $(document).on('click', "#btndisinterested", evt.unattend);
        // $(document).on('click', "#btnticket", evt.openViewTickets);
         $(document).on('change', 'select[name^="TICKET_QTE"]', evt.onChangeTicketsQte);
        // if (document.getElementById("emode") !== null) {
        //     evt.initEditMode();
        // }
        //this.initTickets();
        //this.loadTagsView();

        this.loadTicketsView();
    },

    initTickets: function() {

        // Modal View Tickets
        evt.modal_view_tickets = new tingle.modal({
            footer: true,
            stickyFooter: false,
            closeMethods: ['overlay', 'button', 'escape'],
            closeLabel: "Close",
            cssClass: ['custom-class-1'],
            onOpen: function() {
                evt.loadTicketsView();
            },
            onClose: function() {
                //console.log('modal closed');
            },
            beforeClose: function() {
                // here's goes some logic
                // e.g. save content before closing the modal
                return true; // close the modal
                return false; // nothing happens
            }
        });

        // set content
        evt.modal_view_tickets.setContent(document.getElementById('panel_view_tickets').innerHTML);

    },


    loadTicketsView: function() {

        if (evt.isTicketsLoaded === false) {

            var jsonObj = {"UID": evt.uid};
            ticket.getAllTickets(this, function (self, model) {
                if (model !== null && model.result === true) {

                    var i = 0;
                    // Tickets List
                    $('#event_tickets_list').empty();
                    var tmpl = $('#tpl_tickets').html();
                    var tempFn = doT.template(tmpl);
                    $.each(model.data, function () {

                        var maxquantity = 10;

                        try {
                            maxquantity = parseInt(this.MAXQTE) + 1;
                        } catch(err) {
                            console.error(err);
                        }

                        if (utils.isEmpty(maxquantity)) {
                            maxquantity = 10;
                        }

                        var selectQteText =  "";
                        for (var tq = 0; tq < maxquantity; tq++) {
                            selectQteText += "<option value=\"" + tq + "\">" + tq + "</option>";
                        }

                        var resultText = tempFn({
                            name: this.NAME,
                            descl: this.DESCL,
                            price: this.PRICE,
                            id: this.ID,
                            select_name: 'TICKET_QTE',
                            maxqte: selectQteText
                        });

                        $('#event_tickets_list').append(resultText);
                        i++;
                    });

                }
            }, jsonObj);
            evt.isTicketsLoaded = true;
        }

    },

    openViewTickets: function() {
        evt.modal_view_tickets.open();
    },

    initEditMode: function() {

        requirejs(['places','moment','parsley','jqsobject','flow','c/datepicker/js/datepicker.min','leaflet','app/modules/geolocation_form_map','app/modules/CropImage','app/modules/textEditor'], function(Places,Moment,Parsley,Jqsobject,Flow,datepicker,Leaflet,Geo,CropImage,TextEditor) {

            $(document).on('click', "#btncancel", evt.onCancel);

            $(document).on('click', "#btn_edit_dates", evt.onOpenModalDates);
            $(document).on('click', "#btn_edit_img", evt.onOpenModalImg);
            $(document).on('click', "#btn_manage_ticket", evt.onOpenModalAdminTickets);

            $(document).on('submit', "#feventdesc", evt.onSubmitDesc);
            $(document).on('submit', "#feventdates", evt.onSubmitDates);
            $(document).on('submit', "#feventvenue", evt.onSubmitVenue);
            $(document).on('submit', "#feventtags", evt.onSubmitTags);

            $(document).on('submit', "#fcreateticket", evt.onSubmitTickets);
            $(document).on('click', "#btn_fcreateticket_create", evt.onCreateTicket);
            $(document).on('click', ".fcreateticket_delete", evt.onDeleteTickets);
            $(document).on('click', ".fcreateticket_edit", evt.onEditTicket);
            $(document).on('click', "#btn_fcreateticket_cancel", evt.showListTicket);







            //panel_edit_img
            // instanciate new modal edit dates
            evt.modal_img = new tingle.modal({
                footer: true,
                stickyFooter: false,
                closeMethods: ['overlay', 'button', 'escape'],
                closeLabel: "Close",
                cssClass: ['custom-class-1', 'custom-class-2'],
                onOpen: function() {


                    // Image Background
                    var options = {
                        enableExif: true,
                        enableZoom: true,
                        showZoomer: false,
                        viewport: {
                            width: 851,
                            height: 315
                        },
                        boundary: {
                            width: 851,
                            height: 315
                        }
                    };

                    var upload_token = "6684";
                    var cropimage = new CropImage();
                    cropimage.setOptions(options);
                    cropimage.setUid(evt.uid);
                    cropimage.setUploadToken(upload_token);
                    cropimage.setUploadCropZone($('#upload-demo'));
                    $('#upload').on('change', function () {
                        var result = cropimage.readFile(this);
                        if (result === true) {
                            $('#upload_btn_save').css("visibility", "visible");
                        }
                    });

                    cropimage.setCallBack(function(message) {
                        //console.log(message);
                        var msg = JSON.parse(message);
                        if (msg.success === true) {
                            document.getElementById('back_img').src = msg.flowIdentifier;
                            $('#upload_btn_save').css("visibility", "hidden");
                            evt.modal_img.close();
                        }
                    });

                    $('.upload-result').on('click', function (e) {
                        cropimage.crop();
                    });

                    $('#upload_btn_save').css("visibility", "hidden");
                    cropimage.setUploadCropZone($('#upload-demo'));


                },
                onClose: function() {
                    //console.log('modal closed');
                },
                beforeClose: function() {
                    // here's goes some logic
                    // e.g. save content before closing the modal
                    return true; // close the modal
                    return false; // nothing happens
                }
            });
            // set content
            evt.modal_img.setContent(document.getElementById('panel_edit_img').innerHTML);
            // add a button
            // evt.modal_img.addFooterBtn(document.getElementById('btn_close_img_label').value , 'btn btn-secondary', function() {
            //     evt.modal_img.close();
            // });


            // Text Editor
            var editor = new TextEditor();
            editor.init('#EVTDESC');

            // Tags Combo
            evt.loadTagsCombo();

            // Maps
            var geo = new Geo();
            geo.init();



            // instanciate new modal edit dates
            evt.modal_dates = new tingle.modal({
                footer: true,
                stickyFooter: false,
                closeMethods: ['overlay', 'button', 'escape'],
                closeLabel: "Close",
                cssClass: ['custom-class-1', 'custom-class-2'],
                onOpen: function() {
                    requirejs(['c/datepicker/js/i18n/datepicker.' + evt.lang], function(datepicker_lang) {
                        var allday = $('#allday').is(':checked');
                        evt.datepicker_start = evt.initStartCalendar(allday);
                        evt.datepicker_end = evt.initEndCalendar(allday);
                        if (evt.getDateEnd() !== null) {
                            evt.datepicker_end.selectDate(evt.getDateEnd());
                        }
                        if (evt.getDateBegin() !== null) {
                            evt.datepicker_start.selectDate(evt.getDateBegin());
                        }
                    });

                    // All Day
                    $("#allday").change(function () {
                        var c = this.checked ? true : false;
                        evt.datepicker_start.destroy();
                        evt.datepicker_end.destroy();
                        evt.datepicker_start = evt.initStartCalendar(c);
                        evt.datepicker_end = evt.initEndCalendar(c);
                    });
                },
                onClose: function() {
                    //console.log('modal closed');
                },
                beforeClose: function() {
                    // here's goes some logic
                    // e.g. save content before closing the modal
                    return true; // close the modal
                    return false; // nothing happens
                }
            });

            // set content
            evt.modal_dates.setContent(document.getElementById('panel_edit_dates').innerHTML);













            // instanciate new modal edit tickets
            evt.modal_admin_tickets = new tingle.modal({
                footer: true,
                stickyFooter: false,
                closeMethods: ['overlay', 'button', 'escape'],
                closeLabel: "Close",
                cssClass: ['custom-class-1', 'custom-class-2'],
                onOpen: function(e) {
                    evt.loadTicketsCreateView(e);
                    evt.showListTicket(e);
                },
                onClose: function() {
                    //console.log('modal closed');
                },
                beforeClose: function() {
                    // here's goes some logic
                    // e.g. save content before closing the modal
                    return true; // close the modal
                    return false; // nothing happens
                }
            });

            // set content
            evt.modal_admin_tickets.setContent(document.getElementById('panel_edit_admin_tickets').innerHTML);


        });
    },

    onOpenModalImg: function() {
        evt.modal_img.open();
    },

    onOpenModalDates: function() {
        evt.modal_dates.open();
    },

    onOpenModalAdminTickets: function() {
        evt.modal_admin_tickets.open();
    },





    getDateNowPicker: function() {
        var now = new Date();
        now.setHours(now.getHours() + 2);
        now.setMinutes(0);
        now.setSeconds(0);
        now.setMilliseconds(0);
        return now;
    },

    getDateBegin: function() {
        var start_date_timestamp = $('#start_date_formatted').val();
        if (start_date_timestamp) {
            return new Date(start_date_timestamp);
        }
        return null;
    },

    getDateEnd: function() {
        var end_date_timestamp = $('#end_date_formatted').val();
        if (end_date_timestamp) {
            return new Date(end_date_timestamp);
        }
        return null;
    },

    initStartCalendar: function(allday) {

        evt.getDateBegin();

        var firstStart = true;

        return $('#start_date').datepicker({
            timepicker: ! allday,
            language: evt.lang,
            container: '#modal_edit_dates',
            inline: false,
            startDate: evt.getDateNowPicker(),
            selectDate: evt.getDateBegin(),
            minDate: evt.getDateNowPicker(),
            minutesStep: 5,
            altField: '#start_date_formatted',
            altFieldDateFormat: 'yyyy-mm-dd hh:ii',
            clearButton: true,
            autoClose: false,
            onSelect: function onSelect(fd, date,inst) {
                if (! firstStart) {
                    evt.datepicker_end.update({ 'minDate': date });
                }
                firstStart = false;
            }
        }).data('datepicker');

    },

    initEndCalendar: function(allday) {

        evt.getDateEnd();

        var firstStart = true;

        return $('#end_date').datepicker({
            timepicker: ! allday,
            language: evt.lang,
            startDate: evt.getDateNowPicker(),
            minDate: evt.getDateNowPicker(),
            minutesStep: 5,
            altField: '#end_date_formatted',
            altFieldDateFormat: 'yyyy-mm-dd hh:ii',
            clearButton: true,
            autoClose: false,
            position: "top left",
            onSelect: function onSelect(fd, date, inst) {
                if (! firstStart) {
                    evt.datepicker_start.update({ 'maxDate': date});
                }
                firstStart = false;
            }
        }).data('datepicker');

    },


    loadTicketsCreateView: function() {

        var jsonObj = {"UID": evt.uid};
        ticket.getAllTickets(this,function(self, model) {
            if (model !== null && model.result === true) {

                var i = 0;
                // Tickets List
                $('#event_tickets_edit_list').empty();
                var tmpl = $('#tpl_tickets_edit').html();
                var tempFn = doT.template(tmpl);
                $.each(model.data, function() {
                    var resultText = tempFn({
                        name: this.NAME,
                        descl: this.DESCL,
                        price: this.PRICE,
                        qte: this.QTE,
                        id: this.ID,
                        select_name: 'TICKET_QTE_EDIT'
                    });
                    $('#event_tickets_edit_list').append(resultText);
                    i++;
                });
            }
        },jsonObj);

    },

    onPublishEvent: function(self, model) {
        if (model !== null && model.result === true) {
            $("#panel-pub").hide();
        } else {
            $("#btnPublish").prop("disabled",true);
        }
    },

    publish: function() {
        var jsonObj = {"UID": evt.uid};
        //console.log(jsonObj);
        $("#publish").prop("disabled",true);
        event.publishEvent(this, evt.onPublishEvent, jsonObj);
    },

    attend: function(e) {
        e.stopPropagation();
        e.preventDefault();

        var jsonObj = {"UID": evt.uid};
        evt.btninterested.prop("disabled",true);
        event.attend(this, function(self,model) {
            if (model !== null && model.result === true) {
                evt.btninterested.css("display", "none");
                evt.btndisinterested.css("display", "block");
            }
            evt.btninterested.prop("disabled",false);
        }, jsonObj);

        return false;
    },

    unattend: function(e) {
        e.stopPropagation();
        e.preventDefault();

        var jsonObj = {"UID": evt.uid};
        evt.btndisinterested.prop("disabled",true);
        event.unAttend(this, function(self,model) {
            if (model !== null && model.result === true) {
                evt.btndisinterested.css("display", "none");
                evt.btninterested.css("display", "block");
            }
            evt.btndisinterested.prop("disabled",false);
        }, jsonObj);

        return false;
    },

    loadTagsView: function() {
        var jsonObj = {"UID": this.uid};
        event.getTags(this, function(self,model) {
            if (model !== null && model.result === true) {
                var i = 0;
                // Tags List
                $('#tags_list').empty();
                var tmpl = $('#tpl_tags').html();
                var tempFn = doT.template(tmpl);
                $.each(model.data, function() {
                    var resultText = tempFn({value: this.TAG,text: this.DESC});
                    $('#tags_list').append(resultText);
                    self.tags_selected[i] = this.TAG;
                    i++;
                });
            }
        }, jsonObj);
    },

    loadTagsCombo: function() {
        // Load All Tags
        if (document.getElementById("tags") != null) {
            var jsonObjLang = {"L":"fr"};
            generic.loadAllTags(this, function(self,model) {
                if (model !== null && model.result === true) {
                    var $select = $('#tags');
                    $select.find('option').remove();
                    $.each(model.data,function()
                    {
                        var strSelected = '';
                        if ($.inArray(this.value,self.tags_selected) > -1) {
                            strSelected = 'selected';
                        }
                        $select.append('<option value="' + this.value + '"' + strSelected + '>' + this.text + '</option>');
                    });
                }
            },jsonObjLang);
        }
    },

    onCancel: function() {
        var r = confirm("êtes vous sur de vouloir annuler ?");
        if (r == true) {
            var jsonObj = {'UID' : evt.uid};
            event.cancelEvent(this,function(self, model) {
                if (model !== null && model.result === true) {
                    var url = evt.lang + "/results";
                    setTimeout(function () {window.location.href = url;}, 1000);
                }
            },jsonObj);
        }
    },

    onSubmitDesc: function(e) {
        try {
            var instance = $(evt.form_desc).parsley();
            if (! instance.isValid()) return;
            var jsonObj = $(evt.form_desc).serializeJSON();
            var data_descl = $(".ql-editor").html();
            if(data_descl === '<p><br></p>') {
                e.preventDefault();
                return;
            }
            jsonObj['DESCL'] = data_descl;
            if (Object.keys(jsonObj).length === 0) return;
            event.modifyEventDesc(this,function(self, model) {
                if (model !== null && model.result === true) {
                    $("#modal_edit_desc").modal('hide');
                    window.location.reload(true);
                } else {
                    alert('Erreur, veuillez reessayer !');
                }
            },jsonObj);
        } finally {
            e.preventDefault();
        }
    },













    onSubmitDates: function(e) {
        try {
            var instance = $(evt.form_dates).parsley();
            if (! instance.isValid()) return;
            var jsonObj = $(evt.form_dates).serializeJSON();
            if (Object.keys(jsonObj).length === 0) return;
            event.modifyEventDate(this,function(self, model) {
                if (model !== null && model.result === true) {
                    $("#modal_edit_dates").modal('hide');
                    window.location.reload(true);
                }
            },jsonObj);
        } finally {
            e.preventDefault();
        }
    },

    onSubmitVenue: function(e) {
        try {
            var instance = $(evt.form_venue).parsley();
            if (! instance.isValid()) return;
            var jsonObj = $(evt.form_venue).serializeJSON();
            if (Object.keys(jsonObj).length === 0) return;
            jsonObj['UID'] = evt.uid;
            event.modifyEventVenue(this,function(self, model) {
                if (model !== null && model.result === true) {
                    $('#wvenue').modal('hide');
                    window.location.reload(true);
                }
            },jsonObj);
        } catch (e) {
            console.log(e);
        } finally {
            e.preventDefault();
        }
    },

    onSubmitTags: function(e) {
        try {
            var instance = $(evt.form_tags).parsley();
            if (! instance.isValid()) return;
            var jsonObj = $(evt.form_tags).serializeJSON();
            if (Object.keys(jsonObj).length === 0) return;
            event.modifyEventTags(this,function(self, model) {
                if (model !== null && model.result === true) {
                    evt.loadTagsView();
                    $("#modal_edit_tags").modal('hide');
                }
            },jsonObj);
        } finally {
            e.preventDefault();
        }
    },






    onSubmitTickets: function(e) {
        try {
            e.preventDefault();
            var instance = $(evt.form_tickets).parsley();
            if (! instance.isValid()) return;
            var jsonObj = $(evt.form_tickets).serializeJSON();
            if (Object.keys(jsonObj).length === 0) return;

            console.log(jsonObj);

            if (jsonObj["ID"] === "") {

                ticket.create(this,function(self, model) {
                    if (model !== null && model.result === true) {

                        console.log("submit tic ok");

                        evt.showListTicket(e);

                        //$("#wcreateticket").modal('hide');
                        // $('#fcreateticket_panel_create').css("display", "none");
                        // $('#btn_fcreateticket_save').css("display", "none");
                        // $('#btn_fcreateticket_cancel').css("display", "none");
                        // $('#fcreateticket_panel_view').css("display", "block");
                        // $('#btn_fcreateticket_create').css("display", "block");

                        evt.loadTicketsCreateView();
                    } else {
                        console.log("submit tic pas ok");
                    }
                },jsonObj);

            } else {
                ticket.modify(this,function(self, model) {
                    if (model !== null && model.result === true) {

                        console.log("submit mod tic ok");

                        evt.showListTicket(e);
                        evt.loadTicketsCreateView();
                    } else {

                        console.log("submit mod tic ok");
                    }
                },jsonObj);
            }

        } finally {
            e.preventDefault();
        }
    },

    onCreateTicket: function(e) {
        $('#fcreateticket_id').val('');
        $('#fcreateticket_name').val('');
        $('#fcreateticket_descl').val('');
        $('#fcreateticket_qte').val('');
        $('#fcreateticket_price').val('');
        $('#fcreateticket_minqte').val('1');
        $('#fcreateticket_maxqte').val('10');
        evt.showEditListTicket(e,'');
    },
    onEditTicket: function(e) {
        var id = $(this).data("ticket-id");
        var jsonObj = {"UID": evt.uid, "ID": id};
        ticket.getTicket(this,function(self, model) {
            if (model !== null && model.result === true) {
                $('#fcreateticket_id').val(model.data.ID);
                $('#fcreateticket_name').val(model.data.NAME);
                $('#fcreateticket_descl').val(model.data.DESCL);
                $('#fcreateticket_qte').val(model.data.QTE);
                $('#fcreateticket_price').val(model.data.PRICE);
                $('#fcreateticket_minqte').val(model.data.MINQTE);
                $('#fcreateticket_maxqte').val(model.data.MAXQTE);
                evt.showEditListTicket(e, id);
            }
        },jsonObj);
    },
    showListTicket: function(e) {
        $('#fcreateticket_panel_create').css("display", "none");
        $('#btn_fcreateticket_save').css("display", "none");
        $('#btn_fcreateticket_save').prop('disabled', true);
        $('#btn_fcreateticket_cancel').css("display", "none");
        $('#btn_fcreateticket_cancel').prop('disabled', true);
        $('#fcreateticket_panel_view').css("display", "block");
        $('#btn_fcreateticket_create').css("display", "block");
        $('#btn_fcreateticket_create').prop('disabled', false);
    },
    showEditListTicket: function(e,id) {
        $('#fcreateticket_panel_create').css("display", "block");
        $('#btn_fcreateticket_save').css("display", "block");
        $('#btn_fcreateticket_save').prop('disabled', false);
        $('#btn_fcreateticket_cancel').css("display", "block");
        $('#btn_fcreateticket_cancel').prop('disabled', false);
        $('#fcreateticket_panel_view').css("display", "none");
        $('#btn_fcreateticket_create').css("display", "none");
        $('#fcreateticket_id').val(id);
    },

    onDeleteTickets: function(e) {
        var r = confirm("êtes vous sur de vouloir supprimer le ticket ?");
        if (r == true) {
            var id = $(this).data("ticket-id");
            var jsonObj = {"UID": evt.uid, "ID": id};
            ticket.delete(this, function (self, model) {
                if (model !== null && model.result === true) {
                    evt.loadTicketsCreateView();
                }
            }, jsonObj);
        }
    },

    onChangeTicketsQte: function(e) {
        var total = 0.0;
        var total_qte = 0;

        $('select[name^="TICKET_QTE"]').each(function() {
            var cost = parseFloat($(this).data("ticket-price"));
            var qte  = parseFloat(this.value);
            if (qte > 0) {
                total_qte += qte;
                total += (qte * cost);
            }
        });
        //console.log("total : " + total);
        if (total_qte > 0) {
            $('#btn_checkout_tickets').prop('disabled', false);
        } else {
            $('#btn_checkout_tickets').prop('disabled', true);
        }

        $('#total_qte_tickets').text(total_qte);
        $('#total_price_tickets').text(Number(total).toLocaleString("fr-BE", {minimumFractionDigits: 2}) + ' €');
    }

};

evt.init();

});