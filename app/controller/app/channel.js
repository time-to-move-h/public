"use strict";
requirejs(['jquery','tippy','moment','moment/fr','doT','flow','parsley','jqsobject','app/modules/Channel','app/modules/Event','app/modules/Utils','app/modules/CropImage','app/modules/textEditor','c/tingle/tingle.min'], function($,tippy,Moment,M_Lang,doT,Flow,Parsley,jqsobject,Channel,Event,Utils,CropImage,textEditor,tingle) {

var utils = new Utils();
var evt = new Event();
var cha = new Channel();

var channel = {
    uid: null,
    firstLoad_events: true,
    firstLoadPastEvents: true,
    firstLoad_channels: true,
    offset_events: 0,
    offsetPastEvents: 0,
    offset: 0,
    lang: $('html').closest('[lang]').attr('lang') || 'en',

    event_view_panel_empty: $("#event_view_panel_empty"),
    list_upcoming_events: $("#list_upcoming_events"),
    btn_loadmore_upcoming_events: $("#btn_loadmore_upcoming_events"),


    btn_loadmore_upcoming_past_events: $("#btn_loadmore_upcoming_past_events"),
    event_past_view_panel_empty: $("#event_past_view_panel_empty"),
    list_upcoming_past_events: $("#list_upcoming_past_events"),

    form_desc: "#fchanneldesc",

    modal_img: null,
    token: 0,
    modal_desc: null,

    init: function() {
        channel.uid = utils.getValueByID("UID");

        // load more events
        $(document).on("click","#btn_loadmore_upcoming_events",function(e) {
            e.preventDefault();
            channel.loadmore_events();
        });

        $(document).on("click","#btn_loadmore_upcoming_past_events",function(e) {
            e.preventDefault();
            channel.loadmore_past_events();
        });

        // button publish
        $(document).on('click', "#publish", function(e) {
            e.preventDefault();
            channel.publish();
        });

        // description panel
        $(document).on('click', "#btn_descl_view_panel", function(e) {
            e.preventDefault();
            $("#event_view_panel").css('display','none');
            $("#descl_view_panel").css('display','block');
            $("#btn_descl_view_panel").addClass('active');
            $("#btn_event_view_panel").removeClass('active');
        });

        // event panel
        $(document).on("click","#btn_event_view_panel",function(e) {
            e.preventDefault();
            $("#event_view_panel").css('display','block');
            $("#descl_view_panel").css('display','none');
            $("#btn_event_view_panel").addClass('active');
            $("#btn_descl_view_panel").removeClass('active');
        });

        $(document).on('click', "#btnsubscribe", function() {
            channel.subscribe();
        });

        $(document).on('click', "#btnunsubscribe", function() {
            channel.unsubscribe();
        });

        $(document).on('click', "#btnwaitconfirm", function() {
            channel.unsubscribe();
        });

        $(document).on('submit', channel.form_desc, channel.onSubmitDesc);

        evt.filter(this, this.onLoadEvents,{"UID": channel.uid,"O": this.offset_events,"D":0});
        evt.filter(this, this.onLoadPastEvents,{"UID": channel.uid,"O": this.offsetPastEvents,"D":1});

        if (document.getElementById('descl')) {
            var editor = new textEditor();
            editor.init('#descl');
            channel.initImage();
            channel.initEditDesc();
        }
    },

    initImage: function() {

        var cropimage = null;

        var options_background = {
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

        //type: 'circle'
        var options_avatar = {
            enableExif: true,
            enableZoom: true,
            showZoomer: false,
            viewport: {
                width: 128,
                height: 128
            },
            boundary: {
                width: 300,
                height: 300
            }
        };


        $('#btn_edit_background').on('click', function (e) {
            channel.token = $(e.target).attr("data-token");
            channel.modal_img.open();
        });


        $('#btn_edit_img_round').on('click', function (e) {
            channel.token = $(e.target).attr("data-token");
            channel.modal_img.open();
        });



        // instanciate new modal edit img
        channel.modal_img = new tingle.modal({
            footer: true,
            stickyFooter: false,
            closeMethods: ['overlay', 'button', 'escape'],
            closeLabel: "Close",
            cssClass: ['custom'],
            onOpen: function() {

                // Image Background
                if (document.getElementById('fchanneldesc')) {

                    cropimage = new CropImage();
                    //cropimage.setOptions(options);
                    cropimage.setUid(channel.uid);
                    cropimage.setUploadToken(channel.token);
                    cropimage.setUploadCropZone($('#upload-demo'));
                    $('#upload').on('change', function () {
                        var result = cropimage.readFile(this);
                        if (result === true) {
                            $('#upload_btn_save').css("visibility", "visible");
                        }
                    });
                    cropimage.setCallBack(function(message) {
                        var msg = JSON.parse(message);
                        if (msg.success === true) {

                            if (channel.token === '5471') {
                                document.getElementById('back_img').src = msg.flowIdentifier;
                            }

                            $('#upload_btn_save').css("visibility", "hidden");
                            //$('#modalimg').modal('hide');
                            channel.modal_img.close();
                        }
                    });
                    $('.upload-result').on('click', function (e) {
                        cropimage.crop();
                    });


                    if (channel.token === '5471') {
                        cropimage.setOptions(options_background);
                    } else if (channel.token === '6356') {
                        cropimage.setOptions(options_avatar);
                    }

                    $('#upload_btn_save').css("visibility", "hidden");
                    cropimage.setUploadCropZone($('#upload-demo'));


                }

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
        channel.modal_img.setContent(document.getElementById('panel_edit_img').innerHTML);

    },

    initEditDesc: function() {

        $('#btn_edit_desc').on('click', function (e) {
            channel.modal_desc.open();
        });


        // instanciate new modal edit img
        channel.modal_desc = new tingle.modal({
            footer: true,
            stickyFooter: false,
            closeMethods: ['overlay', 'button', 'escape'],
            closeLabel: "Close",
            cssClass: ['custom'],
            onOpen: function() {

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
        channel.modal_desc.setContent(document.getElementById('panel_channel_edit_desc').innerHTML);
    },

    onSubmitDesc: function(e) {
        try {
            var instance = $(channel.form_desc).parsley();
            if (! instance.isValid()) return;
            var jsonObj = $(channel.form_desc).serializeJSON();
            var data_descl = $(".ql-editor").html();
            jsonObj['DESCL'] = data_descl;

            if (Object.keys(jsonObj).length === 0) return;
            cha.modifyChannelDesc(self,function(self, model) {
                if (model !== null && model.result === true) {
                    $("#modal_edit_desc").modal('hide');
                    window.location.reload(true);
                }
            },jsonObj);
        } finally {
            e.preventDefault();
        }
    },

    isEventData: function() {
        return document.getElementById("list_upcoming_events").innerHTML.length;
    },

    isPastEventData: function() {
        return document.getElementById("list_upcoming_past_events").innerHTML.length;
    },

    onLoadEvents: function(self, model) {
        if (model !== null && model.result === true) {
            if (model.data.length > 0) {
                if (self.firstLoad_events === true) {
                    if (model.data.length < 9) {
                        self.btn_loadmore_upcoming_events.css('display','none');
                    } else {
                        self.btn_loadmore_upcoming_events.css('display','block');
                    }
                }

                channel.event_view_panel_empty.css('display','none');
                var surl = window.location.pathname;
                var lang = utils.getUserLanguage();
                var tmpl = document.getElementById("tpl_evt").innerHTML;
                var tempFn = doT.template(tmpl);
                $.each(model.data, function() {
                    var evtUrl = lang+"/event/" + this.URLLINK;
                    var LOGO = this.PICTURE;
                    var PICTURE = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";
                    if (LOGO !== undefined && LOGO !== null && LOGO.length > 0) PICTURE = LOGO;
                    var a = Moment(this.DATBEG);
                    a.locale('fr');
                    var resultText = tempFn({
                        TITLE: this.TITLE,
                        DATBEG: a.locale("fr").format('dddd, DD MMMM YYYY HH:mm'),
                        DAY: a.format('DD'),
                        MONTH: a.format('MMMM'),
                        SUBSCRIPTION: this.SUBSCRIPTION,
                        URLLINK: this.URLLINK,
                        PICTURE: PICTURE,
                        EVTURL: evtUrl
                    });
                    channel.list_upcoming_events.append(resultText);
                });
            } else {
                channel.btn_loadmore_upcoming_events.css('display', 'none');
                if (channel.isEventData() <= 0) {
                    channel.event_view_panel_empty.css('display', 'block');
                }
            }
        }
    },

    onLoadPastEvents: function(self,model) {
        if (model !== null && model.result === true) {
            if (model.data.length > 0) {

                if (self.firstLoadPastEvents === true) {
                    if (model.data.length < 9) {
                        self.btn_loadmore_upcoming_past_events.css('display','none');
                    } else {
                        self.btn_loadmore_upcoming_past_events.css('display','block');
                    }
                }

                self.event_past_view_panel_empty.css('display','none');
                var lang = utils.getUserLanguage();
                var tmpl = document.getElementById("tpl_evt").innerHTML;
                var tempFn = doT.template(tmpl);
                $.each(model.data, function() {
                    var a = Moment(this.DATBEG);
                    a.locale('fr');
                    var resultText = tempFn({
                        TITLE: this.TITLE,
                        DATBEG: a.locale("fr").format('dddd, DD MMMM YYYY HH:mm'),
                        DAY: a.format('DD'),
                        MONTH: a.format('MMMM'),
                        SUBSCRIPTION: this.SUBSCRIPTION,
                        URLLINK: this.URLLINK
                    });
                    self.list_upcoming_past_events.append(resultText);
                });
            } else {
                channel.btn_loadmore_upcoming_past_events.css('display','none');
                if (channel.isPastEventData() <= 0) {
                    channel.event_past_view_panel_empty.css('display','block');
                }
            }
        }
    },

    onPublishChannel: function(self, model,jsonObj) {
        if (model !== null && model.result === true) {
            $("#panel-pub").hide();
        } else {
            $("#btnPublish").prop("disabled",true);
        }
    },

    publish: function() {
        $("#publish").prop("disabled",true);
        cha.publish(self, this.onPublishChannel, {"UID": channel.uid});
    },

    subscribe: function() {
        var jsonObj = {"UID": channel.uid};
        $("#btnsubscribe").prop("disabled",true);
        cha.subscribe(this, function(self,model) {
            if (model !== null && model.result === true) {
                $('#btnsubscribe').css("display", "none");
                $('#btnwaitconfirm').css("display", "none");
                $('#btnunsubscribe').css("display", "block");
            } else {
                //console.log("error");
            }
            $("#btnsubscribe").prop("disabled",false);
        }, jsonObj);
    },

    unsubscribe: function() {

        var r = confirm("are you sure you want to unsubscribe ?");
        if (r == true) {
            var jsonObj = {"UID": channel.uid};
            $("#btnsubscribe").prop("disabled", true);
            cha.unsubscribe(self, function (self, model) {
                if (model !== null && model.result === true) {
                    $('#btnwaitconfirm').css("display", "none");
                    $('#btnunsubscribe').css("display", "none");
                    $('#btnsubscribe').css("display", "block");
                }
                $("#btnsubscribe").prop("disabled", false);
            }, jsonObj);
        }


    },

    loadmore_events: function() {
        channel.firstLoad_events = false;
        channel.offset_events++;
        evt.filter(channel,channel.onLoadEvents,{"UID": channel.uid,"O": this.offset_events,"D":0});
    },

    loadmore_past_events: function() {
        channel.firstLoadPastEvents = false;
        channel.offsetPastEvents++;
        evt.filter(channel,channel.onLoadPastEvents,{"UID": channel.uid,"O": channel.offsetPastEvents,"D":1});
    }
}

channel.init();

});