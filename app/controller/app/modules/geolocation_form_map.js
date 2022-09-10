"use strict";
define (["jquery","places"],function($,places) {
    return function() {
        var form_map = {
            input_map: '#input-map',
            map_container: 'map-container',
            $loc_lat: document.querySelector('#latitude'),
            $loc_lng: document.querySelector('#longitud'),
            map: null,
            osmLayer: null,
            markers: [],
            init: function() {
                var placesAutocomplete = places({
                    container: document.querySelector(form_map.input_map),
                    language: 'en',
                    countries: ['nl'],
                    // type: 'address',
                    aroundLatLngViaIP: false
                });
                form_map.map = L.map(form_map.map_container, {
                    scrollWheelZoom: true,
                    zoomControl: true
                });
                form_map.osmLayer = new L.TileLayer(
                    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        minZoom: 1,
                        /*maxZoom: 13,*/
                        attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
                    }
                );
                form_map.map.setView(new L.LatLng(0, 0), 1);
                form_map.map.addLayer(form_map.osmLayer);
                placesAutocomplete.on('suggestions', this.handleOnSuggestions);
                placesAutocomplete.on('cursorchanged', this.handleOnCursorchanged);
                placesAutocomplete.on('change', this.handleOnChange);
                placesAutocomplete.on('clear', this.handleOnClear);
                return this;
            },
            removeMarker: function(marker) {
                form_map.map.removeLayer(marker);
            },
            handleOnSuggestions: function(e) {
                form_map.markers.forEach(form_map.removeMarker);
                form_map.markers = [];

                if (e.suggestions.length === 0) {
                    form_map.map.setView(new L.LatLng(0, 0), 1);
                    return;
                }

                e.suggestions.forEach(form_map.addMarker);
                form_map.findBestZoom();
            },
            handleOnChange: function(e) {
                form_map.markers
                    .forEach(function(marker, markerIndex) {
                        if (markerIndex === e.suggestionIndex) {
                            form_map.markers = [marker];
                            marker.setOpacity(1);
                            form_map.findBestZoom();
                        } else {
                            form_map.removeMarker(marker);
                        }
                    });

                console.log(e.suggestion);
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
                form_map.$loc_lat.value = lat;
                form_map.$loc_lng.value = lng;
            },
            handleOnClear: function() {
                form_map.map.setView(new L.LatLng(0, 0), 1);
                form_map.markers.forEach(form_map.removeMarker);

                $("#country").val("");
                $("#state").val("");
                $("#city").val("");
                $("#addr1").val("");
                $("#postcode").val("");
                form_map.$loc_lat.value = '';
                form_map.$loc_lng.value = '';
            },
            handleOnCursorchanged: function(e) {
                form_map.markers
                    .forEach(function(marker, markerIndex) {
                        if (markerIndex === e.suggestionIndex) {
                            marker.setOpacity(1);
                            marker.setZIndexOffset(1000);
                        } else {
                            marker.setZIndexOffset(0);
                            marker.setOpacity(0.5);
                        }
                    });
            },
            addMarker: function(suggestion) {
                var marker = L.marker(suggestion.latlng, {opacity: .4});
                marker.addTo(form_map.map);
                form_map.markers.push(marker);
            },
            findBestZoom: function() {
                var featureGroup = L.featureGroup(form_map.markers);
                form_map.map.fitBounds(featureGroup.getBounds().pad(0.5), {animate: false});
            }
    };
    return form_map;
    }
});