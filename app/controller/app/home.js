"use strict";
requirejs(['jquery','app/modules/Utils','app/modules/Event','app/modules/Generic','packery','scroll','doT','moment','moment/fr'], function($,Utils,Event,Generic,Packery,InfiniteScroll,doT,Moment,M_Lang) {

// ,'c/easydropdown/js/easydropdown'    ,easydropdown

var utils = new Utils();
var events = new Event();
var generic = new Generic();

var evts = {
    offset: 0,
    firstLoad:  true,
    msnry: null,
    q: null,
    p: null,
    t: null,
    lat: null,
    lon: null,
    rad: null,
    $btn_loadmore_id: 'btn_loadmore_events',
    $select_where: $('#search-where'),
    $select_when: $('#search-when'),
    $select_tags: $('#search-tags'),
    init: function() {

        //easydropdown('#search-where');
        //easydropdown('#search-when');

        this.q = utils.getUrlParameter("q");
        if (this.q === undefined || this.q === null) this.q = '';
        this.p = utils.getUrlParameter("p");
        if (this.p === undefined || this.p === null) this.p = '';
        this.t = utils.getUrlParameter("t");
        if (this.t === undefined || this.t === null) this.t = '';
        this.lat = utils.getUrlParameter("lat");
        if (this.lat === undefined || this.lat === null) this.lat = '';
        this.lon = utils.getUrlParameter("lon");
        if (this.lon === undefined || this.lon === null) this.lon = '';
        this.rad = utils.getUrlParameter("rad");
        if (this.rad === undefined || this.rad === null) this.rad = '';

        var lang = $('html').closest('[lang]').attr('lang') || 'en';

        this.msnry = new Packery('.grid-evt', {itemSelector: '.grid-evt-item',columnWidth: 1});

        // Scroll
        // var infScroll = new InfiniteScroll( '.grid-evt', {
        //     path: function() {
        //         //var pageNumber = ( this.loadCount + 1 ) * 10;
        //         return '/articles/P'; // + pageNumber;
        //     },
        //     loadOnScroll: false
        // });

        // infScroll.on( 'scrollThreshold', function() {
        //     evts.loadmore();
        // });

        //this.placeholder();
        var args = {O: this.offset, Q: this.q, P: this.p,T: this.t,LAT: this.lat,LON: this.lon,RAD: this.rad};
        events.showLatest(this,this.onLoadEvents,args);

        // Button loadmore
        document.getElementById('btn_loadmore_events').onclick = function() {
            try {
                evts.loadmore();
            } catch (e) {
                console.log(e);
            }
        };



        //console.log(lang);


        // Tags
        var jsonObj = {"L": lang};
        generic.loadAllTags(self, function (self, model) {
            if (model !== null && model.result === true) {

                //$select_tags.find('option').remove();
                $.each(model.data, function () {
                    evts.$select_tags.append('<option value="' + this.value + '">' + this.text + '</option>');
                });

                if (evts.t) {
                    $('#search-tags option[value=' + evts.t + ']').prop('selected', true);
                }

                //easydropdown('#search-tags');
            }
        }, jsonObj);


        // Search panel --------------------------------------------
        //$('#search-where').change(function() {
            //var value = $("#search-where option:selected").val();
        //});

        $('#search-when').change(function() {
            evts.p  = $("#search-when option:selected").val();
            evts.redirectPage();
        });

        if (this.p) {
            $('#search-when option[value=' + this.p + ']').prop('selected', true);
        }

        $('#search-tags').change(function() {
            evts.t = $("#search-tags option:selected").val();
            evts.redirectPage();
        });
        //-----------------------------------------------------------



    },

    // placeholder: function() {
    //     var tmpl = $('#tpl_evt').html();
    //     var tempFn = doT.template(tmpl);
    //     var i;
    //     //this.msnry.remove($('#grid-data').find('.grid-evt-item'));
    //     $('#grid-data').find('.grid-evt-item').remove();
    //     for (i = 0; i < 12; i++) {
    //         var PICTURE = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";
    //         var resultText = tempFn({
    //             TITLE: "",
    //             DATBEG: "",
    //             SUBSCRIPTION: "",
    //             URLLINK: "",
    //             PICTURE: PICTURE,
    //             EVTURL: "",
    //             SUBSCRIBED:""
    //         });
    //         $('#grid-data').append(resultText);
    //         this.msnry.appended( resultText );
    //     }
    //     this.msnry.layout();
    // },

    redirectPage: function() {
        window.location.href = "/home?q=" +  evts.q + "&lat=" +  evts.lat + "&lon=" +  evts.lon + "&rad=" +  evts.rad + "&p=" + evts.p + "&t=" +  evts.t;
    },

    onLoadEvents: function(self, model) {

        //console.log(model);

        if (model !== null && model.result === true) {
            if (model.data.length > 0) {

                evts.showBtnLoad();

                if (self.firstLoad === true) {
                    $('#grid-data').find('.grid-evt-item').remove();
                    if (model.data.length <= 9) {
                        evts.hideBtnLoad();
                    }
                }

                var surl = window.location.pathname;
                //var lang = utils.getUserLanguage();
                var tmpl = $('#tpl_evt').html();
                var tempFn = doT.template(tmpl);
                $.each(model.data, function() {
                    var evtUrl = '/event/' + this.URLLINK;
                    var PICTURE_MIN = this.PICTURE_MIN;
                    //console.log(PICTURE_MIN);
                    //var PICTURE_DEFAULT = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";
                    //if (PICTURE_MIN === undefined || PICTURE_MIN === null || PICTURE_MIN === '') {
                    //console.log(PICTURE_MIN);
                    //console.log("il entre ...");
                    //PICTURE_MIN = PICTURE_DEFAULT;
                    //}
                    var SUBSCRIBED = false;
                    if (this.SUBSCRIPTION === null || this.SUBSCRIPTION === -1) {
                        SUBSCRIBED = true;
                    }
                    //var a = Moment(this.DATBEG);
                    // var d = '';
                    // if (this.ALLDAY === '0') {
                    //     d = a.format('dddd, DD MMMM YYYY HH:mm');
                    // } else {
                    //     d = a.format('dddd, DD MMMM YYYY');
                    // }

                    //console.log(this.TITLE);

                    var resultText = tempFn({
                        TITLE: this.TITLE,
                        DATFORMATTED: this.DATFORMATTED,
                        SUBSCRIPTION: this.SUBSCRIPTION,
                        URLLINK: this.URLLINK,
                        PICTURE: PICTURE_MIN,
                        EVTURL: evtUrl,
                        SUBSCRIBED:SUBSCRIBED,
                        LOCATION: this.CITY
                    });

                    $('#grid-data').append(resultText);
                    self.msnry.appended( resultText );
                    self.msnry.layout();
                });

                $(".event_img_background").on('error',function () {
                    //$(this).unbind("error").attr("src", "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7");
                });

                $('#msg_nodata').css("display","none");

            } else {

                if (self.firstLoad === true) {
                    $('#grid-data').find('.grid-evt-item').remove();
                }

                var evtcount = $('#grid-data').text().length;
                if (evtcount === null) evtcount = 0;

                if (evtcount < 1) {
                    //self.msnry.remove($('#grid-data').find('.grid-evt-item'));
                    $('#grid-data').find('.grid-evt-item').remove();
                    $('#msg_nodata').css("display","block");
                    evts.hideBtnLoad();
                }
                self.resetOffset(self);
            }

        } else {
            //self.msnry.remove($('#grid-data').find('.grid-evt-item'));
            $('#grid-data').find('.grid-evt-item').remove();
            $('#msg_nodata').css("display","block");
            evts.hideBtnLoad();
            self.resetOffset(self);
        }
    },

    resetOffset: function(self) {
        self.offset--;
    },

    loadmore: function() {
        this.firstLoad = false;
        this.offset++;
        var args = {O: this.offset, Q: this.q, P: this.p, LAT: this.lat, LON: this.lon, RAD: this.rad};
        events.showLatest(this,this.onLoadEvents, args);
    },

    hideBtnLoad: function () {
        document.getElementById(evts.$btn_loadmore_id).style.visibility = 'hidden';
        //document.getElementById(evts.$btn_loadmore_id).style.display = 'none';
    },

    showBtnLoad: function () {
        document.getElementById(evts.$btn_loadmore_id).style.visibility = 'visible';
        //document.getElementById(evts.$btn_loadmore_id).style.display = 'block';
    }
};

evts.init();

});