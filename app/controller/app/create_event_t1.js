"use strict";
requirejs(['jquery','parsley','jqsobject','c/datepicker/js/datepicker','places','app/modules/Generic','app/modules/Event','app/modules/Channel', 'app/modules/Profile','toastr','app/modules/textEditor','app/modules/formSection'], function($,parsley,jqsobject,datepicker,places,Generic,Event,Channel,Profile,toastr,textEditor,formSection) {

// ,'app/modules/geolocation_form_map'     ,geolocation

var self = this;
var generic = new Generic();
var event = new Event();
var channel = new Channel();
var profile = new Profile();
//var geo = new geolocation();
var section = new formSection();
var editor = new textEditor();

var create_evt = {
    $select_channels:  $('#channels'),
    $select_tags: $('#tags'),
    selchannel: 'selchannel',
    datepicker_start: null,
    datepicker_end: null,
    lang : $('html').closest('[lang]').attr('lang') || 'en',

    init: function () {

        requirejs(['parsley/' + create_evt.lang], function (parsley_lang) {

            // Form Business Information
            $(document).on('submit', "#formEventBusiness", function (e) {
                try {
                    $('#error_unknown').css("display", "none");
                    $('#error_unknown').css("visibility", "hidden");
                    var instance = $("#formEventBusiness").parsley();
                    if (!instance.isValid()) return;
                    var jsonObj = $("#formEventBusiness").serializeJSON();

                    //console.log(jsonObj);
                    //e.preventDefault();
                    //if (true) return;
                    if (Object.keys(jsonObj).length === 0) return;
                    $("#btnSave").prop("disabled", true);
                    $("#btnSave > span").show();
                    profile.saveBusinessInformation(self, create_evt.onSaveBusinessInformation, jsonObj);
                } finally {
                    e.preventDefault();
                }
            });

        });

        //var startToday =  moment().format("YYYY-MM-DD");

        if (document.getElementById('formEvent')) {

            // Form Event
            $(document).on('submit', "#formEvent", function (e) {
                try {
                    $('#error_unknown').css("display", "none");
                    $('#error_unknown').css("visibility", "hidden");
                    var instance = $("#formEvent").parsley();
                    if (!instance.isValid()) return;
                    var jsonObj = $("#formEvent").serializeJSON();
                    var data_descl = $(".ql-editor").html();
                    jsonObj['DESCL'] = data_descl;

                    //console.log(jsonObj);
                    //e.preventDefault();
                    //if (true) return;
                    if (Object.keys(jsonObj).length === 0) return;
                    $("#btnPublish").prop("disabled", true);
                    event.createEvent(self, create_evt.onCreateEvent, jsonObj);
                } finally {
                    e.preventDefault();
                }
            });

            requirejs(['c/datepicker/js/i18n/datepicker.' + create_evt.lang], function (datepicker_lang) {
                create_evt.datepicker_start = create_evt.initStartCalendar(false);
                create_evt.datepicker_end = create_evt.initEndCalendar(false);

                // All Day
                $("#allday").change(function () {
                    var c = this.checked ? true : false;
                    create_evt.datepicker_start.destroy();
                    create_evt.datepicker_end.destroy();
                    create_evt.datepicker_start = create_evt.initStartCalendar(c);
                    create_evt.datepicker_end = create_evt.initEndCalendar(c);
                });
            });

            // Quill
            $(function () {
                section.init(create_evt.before_navigate, create_evt.after_navigate);
                section.navigateTo(0);
                editor.init('#EVTDESC');

                //if (! $select_channels.val()) {
                channel.getChannelsCombo(self, create_evt.onLoadChannelsCombo, null);
                //}
                // Event type
                //if (! $select_tags.val()) {

                var jsonObj = {"L": create_evt.lang};
                generic.loadAllTags(self, function (self, model) {
                    if (model !== null && model.result === true) {
                        //$select_tags.find('option').remove();
                        $.each(model.data, function () {
                            create_evt.$select_tags.append('<option value="' + this.value + '">' + this.text + '</option>');
                        });
                    }
                }, jsonObj);
                //}

            });

        }

    },

    getDateNowPicker: function() {
        var now = new Date();
        now.setHours(now.getHours() + 2);
        now.setMinutes(0);
        now.setSeconds(0);
        now.setMilliseconds(0);
        return now;
    },

    initStartCalendar: function(allday) {

        return $('#start_date').datepicker({
            timepicker: ! allday,
            language: create_evt.lang,
            startDate: create_evt.getDateNowPicker(),
            minDate: create_evt.getDateNowPicker(),
            minutesStep: 5,
            altField: '#start_date_formatted',
            altFieldDateFormat: 'yyyy-mm-dd hh:ii',
            clearButton: true,
            autoClose: false,
            onSelect: function onSelect(fd, date) {
                create_evt.datepicker_end.update('minDate', date);
            }
        }).data('datepicker');

    },

    initEndCalendar: function(allday) {

        return $('#end_date').datepicker({
            timepicker: ! allday,
            language: create_evt.lang,
            startDate: create_evt.getDateNowPicker(),
            minDate: create_evt.getDateNowPicker(),
            minutesStep: 5,
            altField: '#end_date_formatted',
            altFieldDateFormat: 'yyyy-mm-dd hh:ii',
            clearButton: true,
            autoClose: false,
            onSelect: function onSelect(fd, date) {
                create_evt.datepicker_start.update('maxDate', date);
            }
        }).data('datepicker');

    },

    onLoadChannelsCombo: function(self, model) {
        if (model !== null && model.result === true) {
            //$select_channels.find('option').remove();
            //$select_channels.append('<option value="0">&nbsp;</option>');
            var selectedChannel = document.getElementById(create_evt.selchannel).value;
            $.each(model.data, function () {
                if (selectedChannel == this.NAME) {
                    create_evt.$select_channels.append('<option value="' + this.NAME + '" selected>' + this.TITLE + '</option>');
                } else {
                    create_evt.$select_channels.append('<option value="' + this.NAME + '">' + this.TITLE + '</option>');
                }
            });
        }
    },

    onLoadTags: function (self, model) {
        if (model !== null && model.result === true) {
            // Tags List
            $('#tags_list').empty();
            var tmpl = $('#tpl_tags').html();
            var tempFn = doT.template(tmpl);
            $.each(model.data, function () {
                var resultText = tempFn({value: this.value, text: this.text});
                $('#tags_list').append(resultText);
            });
            var $select = $('#tags');
            $select.find('option').remove();
            $.each(model.data, function () {
                $select.append('<option value="' + this.value + '">' + this.text + '</option>');
            });
        }
    },

    event_redirect: function (urllink) {
        var url = "/event/";
        setTimeout(function () {
            window.location.href = url + urllink;
        }, 500);
    },

    onCreateEvent: function (self, model) {
        if (model !== null && model.result === true) {
            create_evt.event_redirect(model.urllink);
        } else {
            $("#btnPublish").prop("disabled", false);
            $('#error_unknown').css("display", "block");
            $('#error_unknown').css("visibility", "visible");
        }
    },

    onSaveBusinessInformation: function (self, model) {
        if (model !== null && model.result === true) {
            location.reload();
        } else if (model !== null && model.result === false && model.code === 93847584) {
            $("#btnSave").prop("disabled", false);
            $("#btnSave > span").hide();
            $('#error_vat_notfound').css("display", "block");
            $('#error_vat_notfound').css("visibility", "visible");
        } else {
            $("#btnSave").prop("disabled", false);
            $("#btnSave > span").hide();
            $('#error_unknown').css("display", "block");
            $('#error_unknown').css("visibility", "visible");
        }
    },

    before_navigate: function (index) {
        if (index === 0) {
            if ($(".ql-editor").html() === '<p><br></p>') {
                return;
            }
        }
    },

    after_navigate: function (index) {
        if (index === 1) {
            //geo.init();
        } else if (index === 3) {
        }
    }
};

create_evt.init();

});