"use strict";
define (["places","app/modules/Utils"],function(places,Utils) {
    return function() {             
        
        if (typeof(Storage) !== "undefined") {
            //console.log( localStorage.getItem("loc") );                        
            document.getElementById('gps_lat').value = localStorage.getItem("lat");
            document.getElementById('gps_lng').value = localStorage.getItem("lng");
            //document.getElementById('locsearch').value = localStorage.getItem("loc");            
        } 

        //document.getElementById('loc_lat').value = getCookie("lat_c");
        //document.getElementById('loc_lng').value = getCookie("lng_c");
        //document.getElementById('locsearch').value = getCookie("loc_c"); 

        var placesAutocomplete = places({
          container: document.querySelector('#locsearch')
        });     
        
        var $loc_lat = document.querySelector('#loc_lat');
        var $loc_lng = document.querySelector('#loc_lng');
        placesAutocomplete.on('change', function(e) {          
            var lat = e.suggestion.latlng.lat;
            var lng = e.suggestion.latlng.lng;
            $loc_lat.value = lat;
            $loc_lng.value = lng;                    
            if (typeof(Storage) !== "undefined") {
                localStorage.setItem("loc", e.suggestion.value);
                localStorage.setItem("lat", lat);
                localStorage.setItem("lng", lng);
            }             
            var utils = new Utils();            
            utils.setCookie("loc_c", e.suggestion.value, 1000);                                    
            utils.setCookie("lat_c", lat, 1000);
            utils.setCookie("lng_c", lng, 1000);

            //searchBox('/en-GB/results?');
      });
      placesAutocomplete.on('clear', function() {        
        $loc_lat.value = '';
        $loc_lng.value = '';        
        if (typeof(Storage) !== "undefined") {
            localStorage.removeItem("lat");
            localStorage.removeItem("lng");
        }        
      });       
        
    }
});