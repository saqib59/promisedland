function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}


function calcCrow(lat1, lon1, lat2, lon2) {
    var R = 6371; // km
    var dLat = toRad(lat2 - lat1);
    var dLon = toRad(lon2 - lon1);
    var lat1 = toRad(lat1);
    var lat2 = toRad(lat2);

    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.sin(dLon / 2) * Math.sin(dLon / 2) * Math.cos(lat1) * Math.cos(lat2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return d;
}

var db_data;
function show_data() {
    console.log(db_data);
}

// Converts numeric degrees to radians
function toRad(Value) {
    return Value * Math.PI / 180;
}
var siteURL = $("#siteurl").data("url");

var input = document.getElementById('searchInput');



function initMap() {

    let search_lat, search_lng;
    var gmarkers = [];

    function normalIcon() {
        return {
            url: siteURL + '/assets/img/pin.png'
        };
    }
    function highlightedIcon() {
        return {
            url: siteURL + '/assets/img/ping.png'
        };
    }

    function addMarker(location, listing_id) {
        var marker = new google.maps.Marker({
            position: location,
            icon: normalIcon(),
            map: map,
            listing_id: listing_id,
        });
        gmarkers.push(marker);


        marker.addListener("click", () => {
            var focus_listing = marker.get('listing_id');
            var focus_element = $(".search_info__result-loop").find("[data-listing_id=" + focus_listing + "]");
            //var focus_element = $(".favourite_list__item[data-listing_id=" + focus_listing + "]");

            $(".search_info__result-loop").find(".favourite_list__item").removeClass("highlight");
            focus_element.addClass("highlight");

            $(".search_info").animate({
                scrollTop: focus_element.offset().top - focus_element.offsetParent().offset().top + 465
            }, 1000);

            map.setZoom(8);
            map.setCenter(marker.getPosition());
        });

    }

    var listingItem = $(".search_info__result-loop").find(".favourite_list__item");
    listingItem.hover(
        function () {
            var index = listingItem.index(this);
            console.log(index);
            marker[index].setIcon(highlightedIcon());
        },
        function () {
            var index = listingItem.index(this);
            console.log(index);
            marker[index].setIcon(normalIcon());
        }

    );


    function removeMarkers() {
        for (i = 0; i < gmarkers.length; i++) {
            gmarkers[i].setMap(null);
        }
    }
    var map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 51.1657, lng: 10.4515 },
        zoom: 6
    });

    //console.log(input);
    //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var autocomplete = new google.maps.places.Autocomplete(input, {
        componentRestrictions: { country: "de" },
    });
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });


    function get_locations_from_adress(radius, living_space_from, living_space_to, room_count_from, room_count_to, value_limit, category, price_from, price_to) {

        var address = $(".search_info__form-address input").val();
        $.ajax({
            type: "POST",
            url: siteURL + "/ajax/search_location.php",
            /* data: form_data, */
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function (data) {
                $(".search_info__result-loop").html(
                    '<div class="search_info__result-loop--loader">' +
                    '<div class="spinner-border text-dark"></div>' +
                    '</div>')
            },
            success: function (data) {
                $(".search_info__result-loop").html('');
                data = JSON.parse(data);
                db_data = data;
                data.forEach(place => {
                    var listing_id = place['listing_id'];

                    var lat = place['lat']
                    var lng = place['lng']
                    var lst = { 'lat': parseFloat(lat), 'lng': parseFloat(lng) };

                    if (address !== '') {
                        var distance = calcCrow(search_lat, search_lng, lat, lng);
                        //console.log(distance);
                        if (isNaN(distance) == false) {
                            if (radius > distance) {
                                fetchResult(listing_id, lst, place, living_space_from, living_space_to, room_count_from, room_count_to, value_limit, category, price_from, price_to);
                            }
                        } else {
                            fetchResult(listing_id, lst, place, living_space_from, living_space_to, room_count_from, room_count_to, value_limit, category, price_from, price_to);
                        }
                    } else {
                        fetchResult(listing_id, lst, place, living_space_from, living_space_to, room_count_from, room_count_to, value_limit, category, price_from, price_to);
                    }

                });
            },
            error: function (data) {
                console.log(data);
            },
        });
        //console.log(locations);
        //return locations; 
    }

    function fetchResult(listing_id, lst, place, living_space_from, living_space_to, room_count_from, room_count_to, value_limit, category, price_from, price_to) {
        var include = true;
        if (place !== '' || place !== undefined) {

            if (category !== '') {
                if (IsJsonString(place['new_cat'])) {
                    var cates = JSON.parse(place['new_cat']);
                    for (let i = 0; i < cates.length; i++) {
                        if (cates[i] == category) {
                            include = true;
                            break;
                        } else {
                            include = false;
                        }
                    }
                } else {
                    if (place['new_cat'] !== category) {
                        include = false;
                    }
                }

            }

            /* if (living_space_from !== '' && living_space_to !== '' && parseFloat(living_space_from) > parseFloat(place['living_space']) && parseFloat(living_space_to) < parseFloat(place['living_space'])) {
                include = false;
            } */

            if (living_space_from !== '' && living_space_to !== '') {
                if (parseFloat(living_space_from) > parseFloat(place['living_space']) && parseFloat(living_space_to) < parseFloat(place['living_space'])) {
                    include = false;
                }
            } else {
                if (living_space_from !== '' && parseFloat(living_space_from) > parseFloat(place['living_space'])) {
                    include = false;
                }
                if (living_space_to !== '' && parseFloat(living_space_to) < parseFloat(place['living_space'])) {
                    include = false;
                }
            }

            /* if (room_count_from !== '' && room_count_to !== '' && parseFloat(room_count_from) > parseFloat(place['listing_rooms']) && parseFloat(room_count_to) < parseFloat(place['listing_rooms'])) {
                include = false;
            } */
            /* if (room_count_to !== '' && parseFloat(room_count_to) < parseFloat(place['listing_rooms'])) {
                include = false;
            } */

            if (room_count_from !== '' && room_count_to !== '') {
                if (parseFloat(room_count_from) > parseFloat(place['listing_rooms']) && parseFloat(living_space_to) < parseFloat(place['listing_rooms'])) {
                    include = false;
                }
            } else {
                if (room_count_from !== '' && parseFloat(room_count_from) > parseFloat(place['listing_rooms'])) {
                    include = false;
                }
                if (room_count_to !== '' && parseFloat(room_count_to) < parseFloat(place['listing_rooms'])) {
                    include = false;
                }
            }

            if (value_limit !== '' && value_limit !== place['value_limit']) {
                include = false;
            }

            /* if (price_from !== '' && parseFloat(price_from) > parseFloat(place['object_val'])) {
                include = false;
            }
            if (price_to !== '' && parseFloat(price_to) < parseFloat(place['object_val'])) {
                include = false;
            } */

            if (price_from !== '' && price_to !== '') {
                if (parseFloat(price_from) > parseFloat(place['object_val']) && parseFloat(living_space_to) < parseFloat(place['object_val'])) {
                    include = false;
                }
            } else {
                if (price_from !== '' && parseFloat(price_from) > parseFloat(place['object_val'])) {
                    include = false;
                }
                if (price_to !== '' && parseFloat(price_to) < parseFloat(place['object_val'])) {
                    include = false;
                }
            }

        } else {
            include = false;
        }

        if ($("#searchInput").val() == '' && living_space_from == '' && living_space_to == '' && room_count_from == '' && room_count_to == '' && value_limit == '' && category == '' && price_from == '0' && price_to == '1000000') {
            include = false;
        }

        $(".search_info__form-alert").html('');
        if (include == true) {
            addMarker(lst, listing_id);
            showListing(place);
        } else {
            $(".search_info__result-loop").html(
                '<div class="coming_soon">' +
                '<div class="coming_soon__txt">' +
                '<h4>No Results Found!</h4>' +
                '<p>Cras ultricies ligula sed magna dictum porta. Proin eget tortor risus. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus.</p>' +
                '</div>' +
                '</div>');
        }

    }

    function showListing(place) {
        $.ajax({
            type: "POST",
            url: siteURL + "/ajax/search_row.php",
            data: place,
            success: function (data) {
                //console.log(data);
                if ($(".search_info__result-loop").find(".coming_soon, .search_info__result-loop--loader").length) {
                    $(".search_info__result-loop").html("");
                }
                $(".search_info__result-loop").append(data);
            },
            error: function (data) {
                console.log(data);
            },
        });
    }

    function call_ajax() {
        var searchInput = document.querySelector('#searchInput').value;
        var radius = document.querySelector('#radius_cal').value;

        var living_space_from = document.querySelector('#living_space_from').value;
        var living_space_to = document.querySelector('#living_space_to').value;

        var room_count_from = document.querySelector('#room_count_from').value;
        var room_count_to = document.querySelector('#room_count_to').value;

        var value_count = document.querySelector('#value_count').value;
        var category = document.querySelector('#category').value;
        var price_from = document.querySelector('#price_from').value;
        var price_to = document.querySelector('#price_to').value;

        if (searchInput == '' && living_space_from == '' && living_space_to == '' && room_count_from == '' && room_count_to == '' && value_count == '' && category == '' && price_from == '0' && price_to == '1000000') {
            $(".search_info__form-alert").html('<div class="alert alert-danger">Du musst mindestens ein Suchkriterium wählen</div>');
        } else {
            removeMarkers();
            get_locations_from_adress(radius, living_space_from, living_space_to, room_count_from, room_count_to, value_count, category, price_from, price_to);
        }
    }

    $(".search_info__form").on("submit", function (event) {
        event.preventDefault();
        call_ajax();
    })

    var search_status = $("#search_status").data("search");
    if (search_status == '1') {
        $(".radius_range").slider("enable");

        setTimeout(() => {
            google.maps.event.trigger(autocomplete, 'place_changed');
        }, 100);

        //google.maps.event.addDomListener(window, 'load', initMap);

        call_ajax();
    }

    function intializeLocation(place) {
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
            map.setZoom(8);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(6);
        }

        $(".radius_range").slider("enable");

        marker.setIcon(({
            url: place.icon,
            size: new google.maps.Size(35, 35),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }

        //infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        //infowindow.open(map, marker);

        search_lat = place.geometry.location.lat();
        search_lng = place.geometry.location.lng();
    }

    autocomplete.addListener('place_changed', function () {
        infowindow.close();
        marker.setVisible(false);

        var place = autocomplete.getPlace();
        console.log(place);

        if (typeof place == "undefined") {

            var service = new google.maps.places.AutocompleteService();
            service.getPlacePredictions({ input: $("#searchInput").val() }, function (predictions, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {

                    googlePlacesService = new google.maps.places.PlacesService(document.getElementById("searchInput"));
                    googlePlacesService.getDetails({
                        reference: predictions[0].reference
                    }, function (details, status) {
                        if (details) {
                            intializeLocation(details);
                        }
                    });

                }
            });

        } else {
            intializeLocation(place);
        }

    });
}

//initMap();
if ($("#map").length > 0) {
    google.maps.event.addDomListener(window, 'load', initMap);
}