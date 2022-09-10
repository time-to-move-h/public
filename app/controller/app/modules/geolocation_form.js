"use strict";
define (["jquery","places"],function($,places) {
    return function() {
        var placesAutocomplete = places({
            container: document.querySelector('#locsearch'),
            language: 'en',
            countries: ['nl'],
            // type: 'brussels',
            aroundLatLngViaIP: false
        });      
        var $loc_lat = document.querySelector('#latitude');
        var $loc_lng = document.querySelector('#longitud');
        placesAutocomplete.on('change', function(e) {                  
            //console.log(e.suggestion);
            $("#country").val(e.suggestion.country);    
            $("#state").val(e.suggestion.administrative);    
            if (e.suggestion.city !== undefined) {
                $("#city").val(e.suggestion.city);
            } else if (e.suggestion.type === 'city') {
                $("#city").val(e.suggestion.name);
            }            
            if (e.suggestion.postcode !== undefined) {
                $("#postcode").val(e.suggestion.postcode);
            } else {
                 $("#postcode").val('');
            }            
            if (e.suggestion.type === 'address') {
                $("#addr1").val(e.suggestion.name);
            } else {
                $("#addr1").val('');
            }            
            //$("#state").val(selected.properties.state);
            //$("#venue").val(venue);
            //$("#osmid").val(selected.properties.osm_id);
            var lat = e.suggestion.latlng.lat;
            var lng = e.suggestion.latlng.lng;
            $loc_lat.value = lat;
            $loc_lng.value = lng;               
      });
      placesAutocomplete.on('clear', function() {
        $("#country").val("");
        $("#state").val("");
        $("#city").val("");
        $("#addr1").val("");
        $("#postcode").val("");
        $loc_lat.value = '';
        $loc_lng.value = '';                  
      });
    }
});