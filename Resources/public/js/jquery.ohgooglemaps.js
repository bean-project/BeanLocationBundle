(function ($) {
// $( document ).ready(function() {
    // Handler for .ready() called.
    function GoogleMapType(settings, map_el) {

        var settings = $.extend({
            'locality_field': null,
            'place_id_field': null,
            'address_field': null,
            'country_field': null,
            'first_division_field': null,
            'second_division_field': null,
            'third_division_field': null,
            'fourth_division_field': null,
            'fifth_division_field': null,
            'street_field': null,
            'number_field': null,
            //'country_hidden_el':null,
            'search_input_el': null,
            'search_action_el': null,
            'search_error_el': null,
            'current_position_el': null,
            'default_lat': '1',
            'default_long': '-1',
            'default_zoom': 5,
            'lat_field': null,
            'long_field': null,
            'callback': function (location, gmap) {
            },
            'error_callback': function (status) {
                $this.settings.search_error_el.text(status);
            },
        }, settings);

        this.settings = settings;

        this.map_el = map_el;

        this.geocoder = new google.maps.Geocoder();

        this.infowindow = new google.maps.InfoWindow();

    }

    GoogleMapType.prototype = {
        initMap: function (center) {

            var center = new google.maps.LatLng(this.settings.default_lat, this.settings.default_long);

            var mapOptions = {
                zoom: this.settings.default_zoom,
                center: center,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            var $this = this;

            this.map = new google.maps.Map(this.map_el[0], mapOptions);

            this.addMarker(center);

            this.addAutocomplete(this.settings.address_field.get(0), this.map, this.marker, this.infowindow);

            /**            google.maps.event.addListener(this.marker, "dragend", function(event) {

				var point = $this.marker.getPosition();
				$this.map.panTo(point);
				$this.updateLocation(point);

			});

             google.maps.event.addListener(this.map, 'click', function(event) {
				$this.insertMarker(event.latLng);
			});

             this.settings.search_action_el.click($.proxy(this.searchAddress, $this));

             this.settings.current_position_el.click($.proxy(this.currentPosition, $this));
             */
//			this.geocodeLatLng(this.geocoder, this.map, this.infowindow,this.settings);			
            this.geocodePlaceId(this.geocoder, this.map, this.infowindow, this.settings, this.settings.place_id_field.val());

        },

        searchAddress: function (e) { // deprecated: use Google Place instead
            e.preventDefault();
            var $this = this;
            //var address = this.settings.search_input_el.val();
            var address = this.settings.address_field.val();
            this.geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    $this.map.setCenter(results[0].geometry.location);
                    $this.map.setZoom(16);
                    $this.insertMarker(results[0].geometry.location);
                } else {
                    $this.settings.error_callback(status);
                }
            });
        },

        currentPosition: function (e) {
            e.preventDefault();
            var $this = this;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        var clientPosition = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                        $this.insertMarker(clientPosition);
                        $this.map.setCenter(clientPosition);
                        $this.map.setZoom(16);
                    },
                    function (error) {
                        $this.settings.error_callback(error);
                    }
                );
            } else {
                $this.settings.search_error_el.text('Your broswer does not support geolocation');
            }

        },

        updateLocation: function (location) {
            this.settings.lat_field.val(location.lat());
            this.settings.long_field.val(location.lng());
            this.settings.callback(location, this);
        },

        addAutocomplete: function (input, map, marker, infowindow) {
            this.autocomplete = new google.maps.places.Autocomplete(input);
            this.autocomplete.bindTo('bounds', map);
            var autocomplete = this.autocomplete;
            //var geocoder = geocodeLatLng(this.geocoder, this.map, this.infowindow,this.settings);
            var geocodePlaceId = this.geocodePlaceId;
            var geocoder = this.geocoder;
            var settings = this.settings;
            this.autocomplete.addListener('place_changed', function () {
                infowindow.close();
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }

                // Set the position of the marker using the place ID and location.
                marker.setPlace({
                    placeId: place.place_id,
                    location: place.geometry.location
                });
                marker.setVisible(true);

                infowindow.setContent('<div><strong>' + place.name + '</strong><br>' +
                    'Place ID: ' + place.place_id + '<br>' +
                    place.formatted_address);
                infowindow.open(map, marker);


                geocodePlaceId(geocoder, map, infowindow, settings, place.place_id);
            });
        },

        addMarker: function (center) {
            if (this.marker) {
                this.marker.setMap(this.map);
                this.marker.setPosition(center);
            } else {
                this.marker = new google.maps.Marker({
                    map: this.map,
                    position: center,
                    draggable: true
                });
            }
        },

        insertMarker: function (position) {
            this.removeMarker();

            this.addMarker(position);

            this.updateLocation(position);

        },
        removeMarker: function () {
            if (this.marker != undefined) {
                this.marker.setMap(null);
            }
        },


        geocodePlaceId: function (geocoder, map, infowindow, settings, placeId) {
            if (typeof placeId === 'undefined') {
                return;
            } else if (placeId === '' || placeId === 0) {
                return;
            }
            //var placeId = document.getElementById('place-id').value;
            settings.place_id_field.val(placeId);
            geocoder.geocode({'placeId': placeId}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        map.setZoom(11);
                        map.setCenter(results[0].geometry.location);
                        var marker = new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location
                        });
                        infowindow.setContent(results[0].formatted_address);
                        infowindow.open(map, marker);


//formatted address
                        var number = null;
                        var street = null;
                        var fifthDivision = null;
                        var fourthDivision = null;
                        var thirdDivision = null;
                        var secondDivision = null;
                        var firstDivision = null;
                        var locality = null;

                        //alert(results[0].formatted_address)
                        //find country name
                        for (var i = 0; i < results[0].address_components.length; i++) {
                            for (var b = 0; b < results[0].address_components[i].types.length; b++) {

                                //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate

                                if (results[0].address_components[i].types[b] == "locality") {
                                    locality = results[0].address_components[i].long_name;
                                }

                                if (results[0].address_components[i].types[b] == "country") {
                                    country = results[0].address_components[i].short_name;
                                }

                                if (results[0].address_components[i].types[b] == "administrative_area_level_1") {
                                    //this is the object you are looking for
                                    firstDivision = results[0].address_components[i].short_name;
                                    break;
                                }

                                if (results[0].address_components[i].types[b] == "administrative_area_level_2") {
                                    secondDivision = results[0].address_components[i].short_name;
                                }
                                if (results[0].address_components[i].types[b] == "administrative_area_level_3") {
                                    thirdDivision = results[0].address_components[i].short_name;
                                }
                                if (results[0].address_components[i].types[b] == "administrative_area_level_4") {
                                    fourthDivision = results[0].address_components[i].short_name;
                                }
                                if (results[0].address_components[i].types[b] == "administrative_area_level_5") {
                                    fifthDivision = results[0].address_components[i].short_name;
                                }
                                if (results[0].address_components[i].types[b] == "route") {
                                    street = results[0].address_components[i].long_name;
                                }
                                if (results[0].address_components[i].types[b] == "street_number") {
                                    number = results[0].address_components[i].short_name;
                                }
                            }
                        }
                        //city data
//        alert(" number " +number+ " street " +street+ " 5th " +fifthDivision+ " 4th " +fourthDivision+ " 3rd " + thirdDivision + " 2nd " + secondDivision + " 1st " +city.long_name + " in " + country);

                        settings.country_field.val(country);
                        settings.first_division_field.val(firstDivision);
                        settings.second_division_field.val(secondDivision);
                        settings.third_division_field.val(thirdDivision);
                        settings.fourth_division_field.val(fourthDivision);

                        settings.fifth_division_field.val(fifthDivision);
                        settings.street_field.val(street);
                        settings.number_field.val(number);
                        settings.locality_field.val(locality);

                        // updateLocation
                        settings.lat_field.val(results[0].geometry.location.lat());
                        settings.long_field.val(results[0].geometry.location.lng());


                    } else {
                        // window.alert('No results found');
                        console.log('No results found');
                    }
                } else {
                    // window.alert('Geocoder failed due to: ' + status);
                    console.log('Geocoder failed due to: ' + status);
                }
            });
        },

        geocodeLatLng: function (geocoder, map, infowindow, settings) {
//		  var input = document.getElementById('latlng').value;
//		  var latlngStr = input.split(',', 2);
// latlngStr[0]  latlngStr[1]
            var latlng = {
                lat: parseFloat(this.settings.lat_field.val()),
                lng: parseFloat(this.settings.long_field.val())
            };
            geocoder.geocode({'location': latlng}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
//				map.setZoom(11)
                        var marker = new google.maps.Marker({
                            position: latlng,
                            map: map
                        });
                        infowindow.setContent(results[0].formatted_address);
                        infowindow.open(map, marker);


                    } else {
                        // window.alert('No results found');
                    }
                } else {
                    // window.alert('Geocoder failed due to: ' + status);
                }
            });
        }

    }

    $.fn.ohGoogleMapType = function (settings) {

        settings = $.extend({}, $.fn.ohGoogleMapType.defaultSettings, settings || {});

        return this.each(function () {
            var map_el = $(this);

            map_el.data('map', new GoogleMapType(settings, map_el));

            map_el.data('map').initMap();

        });

    };

    $.fn.ohGoogleMapType.defaultSettings = {
        'place_id_field': null,
        'address_field': null,
        'country_field': null,
        'first_division_field': null,
        'second_division_field': null,
        'third_division_field': null,
        'fourth_division_field': null,
        'fifth_division_field': null,
        'street_field': null,
        'number_field': null,

        'search_input_el': null,
        'search_action_el': null,
        'search_error_el': null,
        'current_position_el': null,
        'default_lat': '1',
        'default_long': '-1',
        'default_zoom': 5,
        'lat_field': null,
        'long_field': null,
        'callback': function (location, gmap) {
        },
        'error_callback': function (status) {
            $this.settings.search_error_el.text(status);
        }
    }

// }); // on document ready 
})(jQuery);