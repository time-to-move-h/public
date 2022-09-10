"use strict";
requirejs(['jquery','tippy','moment','moment/fr','toastr','doT','flow','app/modules/Event','app/modules/Channel','app/modules/Ticket','app/modules/Utils','app/modules/CropImage','c/tingle/tingle.min'], function($,tippy,Moment,Moment_lang,toastr,doT,Flow,Event,Channel,Ticket,Utils,CropImage,tingle) {

var event = new Event();
var channel = new Channel();
var ticket = new Ticket();
var utils = new Utils();

var profile = {

    btn_edit_background: 'btn_edit_background',
    btn_edit_avatar: 'btn_edit_avatar',

    token: 0,

    btn_channel_view_panel: 'btn_channel_view_panel', // channel panel
    btn_ticket_view_panel: 'btn_ticket_view_panel', // Ticket panel
    btn_event_view_panel: 'btn_event_view_panel', // event panel

    event_view_panel: 'event_view_panel',
    list_upcoming_events_id: 'list_upcoming_events',
    list_upcoming_events: 'list_upcoming_events',
    event_view_panel_empty: 'event_view_panel_empty',

    btn_loadmore_upcoming_events_id: 'btn_loadmore_upcoming_events',
    panel_loadmore_upcoming_events: 'panel_loadmore_upcoming_events',
    btn_loadmore_upcoming_events: 'btn_loadmore_upcoming_events',

    list_upcoming_past_events_id: 'list_upcoming_past_events',
    panel_loadmore_upcoming_past_events: 'panel_loadmore_upcoming_past_events',
    btn_loadmore_upcoming_past_events: 'btn_loadmore_upcoming_past_events',
    event_past_view_panel_empty: 'event_past_view_panel_empty',
    list_upcoming_past_events: 'list_upcoming_past_events',

    channel_view_panel: 'channel_view_panel',
    list_upcoming_channels_id: 'list_upcoming_channels',
    list_upcoming_channels: 'list_upcoming_channels',
    channel_view_panel_empty: 'channel_view_panel_empty',
    panel_loadmore_upcoming_channels: 'panel_loadmore_upcoming_channels',
    btn_loadmore_upcoming_channels: 'btn_loadmore_upcoming_channels',
    tpl_channel_calendar: 'tpl_channel_calendar',

    panel_loadmore_upcoming_tickets: 'panel_loadmore_upcoming_tickets',
    ticket_view_panel: 'ticket_view_panel',
    list_upcoming_tickets_id: 'list_upcoming_tickets',
    list_upcoming_tickets: 'list_upcoming_tickets',
    ticket_view_panel_empty: 'ticket_view_panel_empty',
    btn_loadmore_upcoming_tickets: 'btn_loadmore_upcoming_tickets',

    text_nopublished: document.getElementById("text_nopublished").innerHTML,

    firstLoad_events: true,
    firstLoadPastEvents: true,
    firstLoad_channels: true,
    firstLoad_tickets: true,

    offset_events: 0,
    offsetPastEvents: 0,
    offset_channels: 0,

    lang: $('html').closest('[lang]').attr('lang') || 'en',

    init: function () {

        // load more events
        //$(document).on("click","#" + this.btn_loadmore_upcoming_events_id, profile.loadmore_events);
        //$(document).on("click","#btn_loadmore_upcoming_past_events", profile.loadmore_past_events);
        //$(document).on("click","#btn_loadmore_upcoming_channels", profile.loadmore_channels);
        //$(document).on("click","#btn_channel_view_panel", profile.onClickChannelView); // channel panel
        //$(document).on("click","#btn_ticket_view_panel", profile.onClickTicketView); // Ticket panel
        //$(document).on("click","#btn_event_view_panel", profile.onClickEventView); // event panel

        // document.addEventListener('click', function (event) {
        //     if (event.target.id) {
        //         if (profile.btn_loadmore_upcoming_events_id === event.target.id) {
        //             profile.loadmore_events(event);
        //             return;
        //         }
        //         if (profile.btn_loadmore_upcoming_past_events === event.target.id) {
        //             profile.loadmore_past_events(event);
        //             return;
        //         }
        //         if (profile.btn_loadmore_upcoming_channels === event.target.id) {
        //             profile.loadmore_channels(event);
        //             return;
        //         }
        //         if (profile.btn_channel_view_panel === event.target.id) {
        //             profile.onClickChannelView(event);
        //             return;
        //         }
        //         if (profile.btn_ticket_view_panel === event.target.id) {
        //             profile.onClickTicketView(event);
        //             return;
        //         }
        //         if (profile.btn_event_view_panel === event.target.id) {
        //             profile.onClickEventView(event);
        //             return;
        //         }

        //         //console.log(event.target);

        //         if (profile.btn_edit_background === event.target.id) {
        //             profile.onOpenModalEditImage(event);
        //             return;
        //         }
        //         if (profile.btn_edit_avatar === event.target.id) {
        //             profile.onOpenModalEditImage(event);
        //             return;
        //         }
        //     }

            // Log the clicked element in the console

        //}, false);

        // First Load Events
        //event.getCalendar(this,this.onLoadEvents,{"O": this.offset_events,"d":0});
        //event.getCalendar(this,this.onLoadPastEvents,{"O": this.offsetPastEvents,"d":1});
        channel.getChannels(this, this.onLoadChannels, {"O": this.offset_channels});
        //ticket.getMyTickets(this, profile.onLoadTickets,null);

        // if (document.getElementById("emode") !== null) {
        //     profile.initImage();
        // }
    },

    onOpenModalEditImage: function(e) {
        profile.token = $(e.target).attr("data-token");
        profile.modal_img.open();
        e.stopPropagation();
        e.preventDefault();
    },


    onClickEventView: function(e) {
        e.stopPropagation();
        e.preventDefault();

        //profile.event_view_panel.css('display','block');
        //profile.channel_view_panel.css('display','none');
        //profile.ticket_view_panel.css('display','none');

        document.getElementById(profile.event_view_panel).style.display = "block";
        document.getElementById(profile.channel_view_panel).style.display = "none";
        document.getElementById(profile.ticket_view_panel).style.display = "none";

        $("#" + profile.btn_event_view_panel).addClass('active');
        $("#" + profile.btn_channel_view_panel).removeClass('active');
        $("#" + profile.btn_ticket_view_panel).removeClass('active');

        profile.updateHashTagUrl('events');

        return false;
    },
    onClickChannelView: function(e) {
        e.stopPropagation();
        e.preventDefault();

        //console.log("hidden1");
        //profile.channel_view_panel_empty.css('display','none');
        //profile.channel_view_panel.css('display','block');
        //profile.event_view_panel.css('display','none');
        //profile.ticket_view_panel.css('display','none');

        document.getElementById(profile.channel_view_panel).style.display = "block";
        document.getElementById(profile.event_view_panel).style.display = "none";
        document.getElementById(profile.ticket_view_panel).style.display = "none";

        $("#" + profile.btn_channel_view_panel).addClass('active');
        $("#" + profile.btn_event_view_panel).removeClass('active');
        $("#" + profile.btn_ticket_view_panel).removeClass('active');

        profile.updateHashTagUrl('channels');

        return false;
    },
    onClickTicketView: function(e) {
        e.stopPropagation();
        e.preventDefault();

        //profile.ticket_view_panel_empty.css('display','block');
        // profile.ticket_view_panel.css('display','block');
        // profile.event_view_panel.css('display','none');
        // profile.channel_view_panel.css('display','none');

        document.getElementById(profile.ticket_view_panel).style.display = "block";
        document.getElementById(profile.event_view_panel).style.display = "none";
        document.getElementById(profile.channel_view_panel).style.display = "none";

        $("#" + profile.btn_ticket_view_panel).addClass('active');
        $("#" + profile.btn_channel_view_panel).removeClass('active');
        $("#" + profile.btn_event_view_panel).removeClass('active');

        profile.updateHashTagUrl('tickets');

        return false;
    },

    initImage: function() {

        //panel_edit_img
        // instanciate new modal edit img
        profile.modal_img = new tingle.modal({
            footer: true,
            stickyFooter: false,
            closeMethods: ['overlay', 'button', 'escape'],
            closeLabel: "Close",
            cssClass: ['custom'],
            onOpen: function() {

                // Image Background
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

                var upload_token = "8247";
                var cropimage = new CropImage();
                cropimage.setOptions(options_background);
                cropimage.setUid(profile.getUID());
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
                        //console.log(msg.post.upload_token);
                        var t = msg.post.upload_token;
                        if (t === '8247') {
                            document.getElementById('back_img').src = msg.flowIdentifier;
                        } else if (t === '9787') {
                            document.getElementById('user_img').src = msg.flowIdentifier;
                        }
                        $('#upload_btn_save').css("visibility", "hidden");
                        //$('#modalimg').modal('hide');
                        profile.modal_img.close();
                    }
                });

                $('.upload-result').on('click', function (e) {
                    cropimage.crop();
                });

                //console.log(profile.token);
                //var token = $(e.relatedTarget).attr("data-token");

                if (profile.token === '8247') {
                    cropimage.setOptions(options_background);
                } else if (profile.token === '9787') {
                    cropimage.setOptions(options_avatar);
                }
                $('#upload_btn_save').css("visibility", "hidden");

                cropimage.setUploadCropZone($('#upload-demo'));
                cropimage.setUploadToken(profile.token);
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
        profile.modal_img.setContent(document.getElementById('panel_edit_img').innerHTML);
    },

    getUID: function() {
        return utils.getValueByID('uid');
    },

    isEventData: function() {
        return document.getElementById(profile.list_upcoming_events_id).innerHTML.length;
    },

    isPastEventData: function() {
        return document.getElementById(profile.list_upcoming_past_events_id).innerHTML.length;
    },

    onLoadEvents: function(self,model) {

        //console.log(model);

        if (model !== null && model.result === true) {
            if (model.data.length > 0) {

                if (self.firstLoad_events === true) {
                    if (model.data.length < 9) {
                        profile.hideLoadmoreUpcoming();
                    } else {
                        profile.showLoadmoreUpcoming();
                    }
                }

                //profile.event_view_panel_empty.css('display','none');
                document.getElementById(profile.event_view_panel_empty).style.display = "none";

                var tmpl = document.getElementById("tpl_evt").innerHTML;
                var tempFn = doT.template(tmpl);
                $.each(model.data, function() {
                    var a = Moment(this.DATBEG);
                    a.locale(profile.lang);

                    var t = this.TITLE;
                    if (this.ONLINE === '0') {
                        t += '&nbsp;&nbsp;&nbsp;<span class="tag badge badge-warning">' + profile.text_nopublished + '</span>';
                    }

                    var resultText = tempFn({
                        TITLE: t,
                        DATBEG: a.locale("fr").format('dddd, DD MMMM YYYY HH:mm'),
                        DAY: a.format('DD'),
                        MONTH: a.format('MMMM'),
                        SUBSCRIPTION: this.SUBSCRIPTION,
                        URLLINK: this.URLLINK
                    });
                    $("#" + profile.list_upcoming_events).append(resultText);
                });
            } else {
                profile.hideLoadmoreUpcoming();
                if (profile.isEventData() <= 0) {
                    //profile.event_view_panel_empty.css('display','block');
                    document.getElementById(profile.event_view_panel_empty).style.display = "block";
                }
            }
        }
    },


    hideLoadmoreUpcoming: function() {
        //profile.btn_loadmore_upcoming_events.css('visibility','hidden');
        //document.getElementById(profile.btn_loadmore_upcoming_events).style.display = "none";
        document.getElementById(profile.panel_loadmore_upcoming_events).style.display = "none";
    },

    showLoadmoreUpcoming: function() {
        //profile.btn_loadmore_upcoming_events.css('visibility','visible');
        //document.getElementById(profile.btn_loadmore_upcoming_events).style.display = "block";
        document.getElementById(profile.panel_loadmore_upcoming_events).style.display = "block";
    },



    onLoadPastEvents: function(self,model) {

        if (model !== null && model.result === true) {
            if (model.data.length > 0) {

                if (profile.firstLoadPastEvents === true) {
                    if (model.data.length < 9) {
                        profile.hideLoadmorePastUpcoming();
                    } else {
                       profile.showLoadmorePastUpcoming();
                    }
                }

                //profile.event_past_view_panel_empty.css('display','none');
                document.getElementById(profile.event_past_view_panel_empty).style.display = "none";

                var tmpl = document.getElementById("tpl_evt").innerHTML;
                var tempFn = doT.template(tmpl);

                $.each(model.data, function() {
                    var a = Moment(this.DATBEG);
                    a.locale(profile.lang);

                    var t = this.TITLE;
                    if (this.ONLINE === '0') {
                        t += '&nbsp;&nbsp;&nbsp;<span class="tag badge badge-warning">' + profile.text_nopublished + '</span>';
                    }

                    var resultText = tempFn({
                        TITLE: t,
                        DATBEG: a.locale("fr").format('dddd, DD MMMM YYYY HH:mm'),
                        DAY: a.format('DD'),
                        MONTH: a.format('MMMM'),
                        SUBSCRIPTION: this.SUBSCRIPTION,
                        URLLINK: this.URLLINK
                    });
                    $("#" + profile.list_upcoming_past_events).append(resultText);
                });

                // Scroll to bottom
                if (profile.firstLoadPastEvents === false) {
                    window.scrollTo(0, document.body.scrollHeight);
                }

            } else {
                profile.hideLoadmorePastUpcoming();
                if (profile.isPastEventData() <= 0) {
                    //profile.event_past_view_panel_empty.css('display','block');
                    document.getElementById(profile.event_past_view_panel_empty).style.display = "block";
                }
            }
        }
    },

    hideLoadmorePastUpcoming: function() {
        //profile.btn_loadmore_upcoming_past_events.css('visibility','hidden');
        //document.getElementById(profile.btn_loadmore_upcoming_past_events).style.display = "none";
        document.getElementById(profile.panel_loadmore_upcoming_past_events).style.display = "none";
    },

    showLoadmorePastUpcoming: function() {
        //self.btn_loadmore_upcoming_past_events.css('visibility','visible');
        //document.getElementById(profile.btn_loadmore_upcoming_past_events).style.display = "block";
        document.getElementById(profile.panel_loadmore_upcoming_past_events).style.display = "block";
    },

    isChannelData: function() {
        return document.getElementById(profile.list_upcoming_channels_id).innerHTML.length;
    },

    istTicketData: function() {
        return document.getElementById(profile.list_upcoming_tickets_id).innerHTML.length;
    },

    onLoadChannels: function(self,model) {

        //console.log(model);
        //alert("onloadchannels !!!")

        if (model !== null && model.result === true) {

            if (model.data.length > 0) {

                if (profile.firstLoad_channels === true) {
                    if (model.data.length < 9) {
                        profile.hideLoadmoreChannelUpcoming();
                    } else {
                        profile.showLoadmoreChannelUpcoming();
                    }
                }

                //console.log("hidden");
                //profile.channel_view_panel_empty.css('display', 'none');
                document.getElementById(profile.channel_view_panel_empty).style.display = "none";

                var tmpl = document.getElementById(profile.tpl_channel_calendar).innerHTML;
                var tempFn = doT.template(tmpl);

                $.each(model.data, function() {
                    var t = this.TITLE;
                    if (this.ONLINE === '0') {
                        t += '&nbsp;&nbsp;&nbsp;<span class="tag badge badge-warning">' + profile.text_nopublished + '</span>';
                    }

                    var resultText = tempFn({
                        TITLE: t,
                        NAME: this.NAME,
                        DESCL:this.DESCL
                    });
                    $("#" + profile.list_upcoming_channels).append(resultText);
                });

                // Scroll to bottom
                if (profile.firstLoad_channels === false) {
                    window.scrollTo(0, document.body.scrollHeight);
                }

            } else {
                profile.hideLoadmoreChannelUpcoming();
                if (profile.isChannelData() <= 0) {
                    //profile.channel_view_panel_empty.css('display', 'block');
                    document.getElementById(profile.channel_view_panel_empty).style.display = "block";
                }
            }
        }
    },

    hideLoadmoreChannelUpcoming: function() {
        //profile..css('visibility','hidden');
        //document.getElementById(profile.btn_loadmore_upcoming_past_events).style.display = "none";
        //console.log(document.getElementById(profile.panel_loadmore_upcoming_channels));
        if (document.getElementById(profile.panel_loadmore_upcoming_channels) != null) {
            document.getElementById(profile.panel_loadmore_upcoming_channels).style.display = "none";
        }
    },

    showLoadmoreChannelUpcoming: function() {
        //self.btn_loadmore_upcoming_past_events.css('visibility','visible');
        //document.getElementById(profile.btn_loadmore_upcoming_past_events).style.display = "block";
        if (document.getElementById(profile.panel_loadmore_upcoming_channels) != null) {
            document.getElementById(profile.panel_loadmore_upcoming_channels).style.display = "block";
        }
    },

    onLoadTickets: function(self,model) {

        if (model !== null && model.result === true) {
            if (model.data.length > 0) {

                if (self.firstLoad_tickets === true) {
                    if (model.data.length < 9) {
                        profile.hideLoadmoreTicketUpcoming();
                    } else {
                        profile.showLoadmoreTicketUpcoming();
                    }
                }

                //profile.ticket_view_panel_empty.css('display', 'none');
                document.getElementById(profile.ticket_view_panel_empty).style.display = "none";

                var tmpl = document.getElementById("tpl_mytickets").innerHTML;
                var tempFn = doT.template(tmpl);

                $.each(model.data, function() {
                    var resultText = tempFn({
                        TICORDER: this.TICORDER,
                        TITLE: this.TITLE,
                        DATINS: this.DATINS,
                        DETAILS: this.DETAILS
                    });
                    $("#" + profile.list_upcoming_tickets).append(resultText);
                });
            } else {
                profile.hideLoadmoreTicketUpcoming();
                if (profile.istTicketData() <= 0) {
                    //profile.ticket_view_panel_empty.css('display', 'block');
                    document.getElementById(profile.ticket_view_panel_empty).style.display = "block";
                }
            }
        }

    },


    hideLoadmoreTicketUpcoming: function() {
        //profile..css('visibility','hidden');
        document.getElementById(profile.btn_loadmore_upcoming_tickets).style.display = "none";
        document.getElementById(profile.panel_loadmore_upcoming_tickets).style.display = "none";
    },

    showLoadmoreTicketUpcoming: function() {
        //self.btn_loadmore_upcoming_past_events.css('visibility','visible');
        document.getElementById(profile.btn_loadmore_upcoming_tickets).style.display = "block";
        document.getElementById(profile.panel_loadmore_upcoming_tickets).style.display = "block";
    },

    loadmore_events: function(e) {
        e.stopPropagation();
        e.preventDefault();

        profile.firstLoad_events = false;
        profile.offset_events++;
        event.getCalendar(this, profile.onLoadEvents,{"O": profile.offset_events,"d":0});
    },

    loadmore_past_events: function(e) {
        e.stopPropagation();
        e.preventDefault();

        profile.firstLoadPastEvents = false;
        profile.offsetPastEvents++;
        event.getCalendar(profile, profile.onLoadPastEvents,{"O": profile.offsetPastEvents,"d":1});
    },

    loadmore_channels: function(e) {
        e.stopPropagation();
        e.preventDefault();

        profile.firstLoad_channels = false;
        profile.offset_channels++;
        channel.getChannels(profile, profile.onLoadChannels,{"O": profile.offset_channels});
    },

    updateHashTagUrl: function (id) {

        if(history.pushState) {
            window.history.pushState(null, null, '?a=' + id);
        } else {
            window.location.hash = '?a=' + id;
        }

    }






};

profile.init();

});