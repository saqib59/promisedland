var geocoder;
var map;
var address = $("#map_canvas").attr("data-address");

function initialize() {

    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(-34.397, 150.644);
    var myOptions = {
        zoom: 12,
        center: latlng,
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
        },
        navigationControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    if (geocoder) {
        geocoder.geocode({
            'address': address
        }, function (results, status) {
            //console.log(status);
            if (status == google.maps.GeocoderStatus.OK) {

                /*************************************/
                /* Normal Embed Maps */
                /*************************************/

                //if (status != 'ZERO_RESULTS') {
                    map.setCenter(results[0].geometry.location);

                    var infowindow = new google.maps.InfoWindow({
                        content: '<b>' + address + '</b>',
                        size: new google.maps.Size(150, 50)
                    });

                    var marker = new google.maps.Marker({
                        position: results[0].geometry.location,
                        map: map,
                        title: address
                    });
                    google.maps.event.addListener(marker, 'click', function () {
                        infowindow.open(map, marker);
                    });

                //} else {
                //    $("#map_tab").html('<div class="alert alert-info">Map View Not Available</div>');
                //}

                /*************************************/
                /* Streetview */
                /*************************************/

                var latitude = results[0].geometry.location.lat();
                var longitude = results[0].geometry.location.lng();

                //console.log(latitude + " " + longitude);

                var svService = new google.maps.StreetViewService();
                var panoRequest = {
                    location: results[0].geometry.location,
                    preference: google.maps.StreetViewPreference.NEAREST,
                    radius: 50,
                    source: google.maps.StreetViewSource.OUTDOOR
                };

                var panorama;
                var findPanorama = function (radius) {
                    panoRequest.radius = radius;
                    svService.getPanorama(panoRequest, function (panoData, status) {
                        if (status === google.maps.StreetViewStatus.OK) {
                            var panorama = new google.maps.StreetViewPanorama(
                                document.getElementById('streetview'),
                                {
                                    pano: panoData.location.pano,
                                });
                        } else {
                            //Handle other statuses here
                            if (radius > 200) {
                                $("#street_tab").html('<div class="alert alert-info">Street View Not Available</div>');
                                //alert("Street View is not available");
                            } else {
                                findPanorama(radius + 5);
                            }
                        }
                    });
                };

                findPanorama(50);

            } else {
                $("#street_tab").html('<div class="alert alert-info">Street View Not Available</div>');
                $("#map_tab").html('<div class="alert alert-info">Map View Not Available</div>');
                //alert("Geocode was not successful for the following reason: " + status);
            }
        });
    }

    /* const fenway = { lat: 42.345573, lng: -71.098326 };
    var panoOptions = {
        position: fenway,
        pov: {
            heading: 34,
            pitch: 10,
        },
    };
    new google.maps.StreetViewPanorama(document.getElementById("streetview"), panoOptions); */
    //streetview.setStreetView(panorama);

}

if ($("#map_canvas").length > 0) {
    google.maps.event.addDomListener(window, 'load', initialize);
}
