requirejs(['jquery','app/modules/Utils','app/modules/Channel','packery','scroll','doT','moment','moment/fr'], function($,Utils,Channel,Packery,InfiniteScroll,doT,Moment,M_Lang) {

var utils = new Utils();
var channels = new Channel();

var chans = {
    offset: 0,
    firstLoad:  true,
    msnry: null,
    q: null,
    p: null,
    init: function() {
        this.q = utils.getUrlParameter("q");
        if (this.q === undefined || this.q === null) this.q = '';
        this.p = utils.getUrlParameter("p");
        if (this.p === undefined || this.p === null) this.p = '';
        this.msnry = new Packery('.grid-cha', {itemSelector: '.grid-cha-item',columnWidth: 1});

        // Scroll
        var infScroll = new InfiniteScroll( '.grid-cha', {
            path: function() {
                //var pageNumber = ( this.loadCount + 1 ) * 10;
                return '/articles/P'; // + pageNumber;
            },
            loadOnScroll: false
        });
        infScroll.on( 'scrollThreshold', function() {
            chans.loadmore();
        });
        this.placeholder();
        channels.showLatest(this,this.onLoadChannels,{"o": this.offset,"q": this.q,"p": this.p});
    },

    placeholder: function() {
        var tmpl = $('#tpl_channel').html();
        var tempFn = doT.template(tmpl);
        var i;
        $('#grid-data').find('.grid-cha-item').remove();
        for (i = 0; i < 12; i++) {
            var PICTURE_MIN = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";
            var resultText = tempFn({
                NAME: "",
                TITLE: "",
                DESCL: "",
                PICTURE_MIN: PICTURE_MIN,
                CHAURL: ""
            });
            $('#grid-data').append(resultText);
            this.msnry.appended( resultText );
        }
        this.msnry.layout();
    },

    onLoadChannels: function(self, model) {
        if (model !== null && model.result === true) {
            if (model.data.length > 0) {

                if (self.firstLoad === true) {
                    $('#grid-data').find('.grid-cha-item').remove();
                }
                var surl = window.location.pathname;
                var lang = utils.getUserLanguage();
                var tmpl = $('#tpl_channel').html();
                var tempFn = doT.template(tmpl);

                $.each(model.data, function() {
                    var chaUrl = lang + '/channel/' + this.NAME;
                    var resultText = tempFn({
                        NAME: this.NAME,
                        TITLE: this.TITLE,
                        DESCL: this.DESCL,
                        PICTURE_MIN: this.PICTURE_MIN,
                        CHAURL: chaUrl
                    });

                    $('#grid-data').append(resultText);
                    self.msnry.appended( resultText );
                    self.msnry.layout();
                });

                $(".channel_img_background").on('error',function () {
                    //$(this).unbind("error").attr("src", "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7");
                });

                $('#msg_nodata').css("display","none");

            } else {

                if (self.firstLoad === true) {
                    $('#grid-data').find('.grid-cha-item').remove();
                }

                var chacount = $('#grid-data').text().length;

                if (chacount === null) chacount = 0;
                if (chacount < 1) {
                    $('#grid-data').find('.grid-cha-item').remove();
                    $('#msg_nodata').css("display","block");
                }
                self.resetOffset(self);
            }

        } else {
            $('#grid-data').find('.grid-cha-item').remove();
            $('#msg_nodata').css("display","block");
            self.resetOffset(self);
        }
    },

    resetOffset: function(self) {
        self.offset--;
    },

    loadmore: function() {
        this.firstLoad = false;
        this.offset++;
        channels.showLatest(this,this.onLoadChannels,{"o": this.offset,"q": this.q,"p": this.p});
    }
};

chans.init();

});