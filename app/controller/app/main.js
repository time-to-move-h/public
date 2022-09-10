"use strict";
//requirejs(['popper'], function(popper) {
//window.Popper = popper;
requirejs(['jquery','bstrap','autocomplete'], function($,bstrap,autocomplete) {
//
    //window.Popper = popper;
    $(function () {
        $.fn.serializeFormJSON = function () {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function () {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };
        var popoverOptions = {
            content: function () {
                return $(this).siblings('#popmenu').html();
            },
            html: true,
            trigger: 'hover focus',
            animation: true,
            placement: 'bottom',
            delay: {"show": 0, "hide": 2000}
        };


        $('[data-toggle="popover"]').popover(popoverOptions);



        // AutoComplete
        //"<span class='suggestion-img'><img src='" + suggestion.data.img + "'/>" +
        // var element = document.getElementById('search_query');
        // var options = {
        //     serviceUrl: '/data/suggest',
        //     //lookup: lookup.suggestions,
        //     minChars: 1,
        //     autoSelectFirst: true,
        //     appendTo: element.parentNode,
        //     formatResult: function (suggestion) {
        //         return "<span class='suggestion-wrapper'><span class='suggestion-value'>" + suggestion.value + "</span></span>";
        //     }
        // };
        // var instance = new autocomplete(element, options);
    });
    //--------------------------------------------------------

    // $(document).on('change', "#global-lang", function() {
    //     var surl = window.location.pathname;
    //     var surl2 = surl.split("?");
    //     var slang = $(this).val();
    //     var lang_iso = langSelect(slang);
    //     //alert(surl2[0]);
    //     var url_redirect = surl2[0].replace(/[a-zA-Z]{2}\-[a-zA-Z]{2}/g,lang_iso);
    //     $(location).attr('href', url_redirect);
    // });


    $('.dropdown-toggle').dropdown();

    function hideImage(img) {
        img.style.display = "none";
    }

    function langSelect(lang) {
        var lang_iso = 'en-GB';
        if (lang === 'EN') {
            lang_iso = 'en-GB';
        } else if (lang === 'FR') {
            lang_iso = 'fr-BE';
        } else if (lang === 'ES') {
            lang_iso = 'es-ES';
        }
        return lang_iso;
    }

    function eraseText() {
        document.getElementById("search").value = "";
    }

    function getGlobalLocation() {
        if (typeof(Storage) !== "undefined") {
            // if (localStorage.getItem("loc_start") === undefined || localStorage.getItem("loc_start") === null) {
            // } else {
            //     var now = new Date().getTime();
            //     var t = localStorage.getItem("loc_start");
            //     var tt = now - t;
            //     if (tt < 3600000) {
            //         return;
            //     }
            // }
            //navigator.geolocation.getCurrentPosition(function(location) {
            // console.log(location.coords.latitude);
            // console.log(location.coords.longitude);
            // console.log(location.coords.accuracy);
            // var x = document.getElementById("geoloctest");
            // x.innerHTML = "Latitude: " + location.coords.latitude + ", Longitude: " + location.coords.longitude + ", Accuracy: " + location.coords.accuracy;
            // var test = "Latitude: " + location.coords.latitude + ", Longitude: " + location.coords.longitude + ", Accuracy: " + location.coords.accuracy;
            // var start = new Date().getTime();
            // localStorage.setItem("loc_start",start);
            // localStorage.setItem("lat",location.coords.latitude);
            // localStorage.setItem("long",location.coords.longitude);
            // localStorage.setItem("accuracy",location.coords.accuracy);
            //});
        } else {
            // No Web Storage support..
        }
    }

    $(document).on("click", ".left-menu li a", function () {
        var data = $(this).attr('data-x-value');
        var url = 'results?p=' + data;
        var params = '';
        // if (typeof(Storage) !== "undefined") {
        //     var lat = localStorage.getItem("lat");
        //     var long = localStorage.getItem("lng");
        //     var accuracy = localStorage.getItem("accuracy");
        //     params = '&loc_lat=' + lat +'&loc_lng=' + long + '&accuracy=' + accuracy;
        // }
        window.location.href = url + params;
    });

});
//});