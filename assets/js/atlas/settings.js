var map = L.map('mymap').setView([51.1642292, 10.4541194], 6);
var geojson;
const attribution =
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
const tileUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
const tiles = L.tileLayer(tileUrl, { attribution });
tiles.addTo(map);

var info = L.control();
info.onAdd = function (map) {
    this._div = L.DomUtil.create("div", "info");
    this.update();
    return this._div;
};
info.update = function (props) {
    this._div.innerHTML =
        (props
            ? "<b>" +
            props.name +
            "</b><br />" +
            props.score +
            "Score"
            : "Hover over a state");
};

info.addTo(map);
function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min) + min); //The maximum is exclusive and the minimum is inclusive
}

function getColor(d) {
    return d > 1000
        ? "#800026"
        : d > 500
            ? "#BD0026"
            : d > 200
                ? "#E31A1C"
                : d > 100
                    ? "#FC4E2A"
                    : d > 50
                        ? "#FD8D3C"
                        : d > 20 ? "#FEB24C" : d > 10 ? "#FED976" : "#FFEDA0";
}

function style(feature) {
    //console.log(feature.properties.zip_code)
    var color = feature.properties.score;
    if (feature.properties.zip_code == undefined) {
        color = getRandomInt(10, 1000)
    } else {
        color = mydata[feature.properties.zip_code]
    }


    return {
        weight: 2,
        opacity: 1,
        color: "white",
        dashArray: "3",
        fillOpacity: 0.7,
        //

        fillColor: getColor(color)

        // fillColor: getColor(94.65)
    };
}
function highlightFeature(e) {
    var layer = e.target;

    layer.setStyle({
        weight: 5,
        color: "#666",
        dashArray: "",
        fillOpacity: 0.7
    });

    if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
        layer.bringToFront();
    }

    info.update(layer.feature.properties);
}
var geoSubArr = []
var gerLowerArr = []
var geojson;
var geoSubJson;
function resetHighlight(e) {
    geojson.resetStyle(e.target);
    info.update();
}
function resetHighlightSub(e) {
    geoSubJson.resetStyle(e.target);
    info.update();
}

function zoomToFeature(e) {
    //console.log(e)
    let subCity = {}
    geojson.clearLayers()
    map.fitBounds(e.target.getBounds());
    for (key in subStatesData) {
        //console.log(subStatesData[key])
        if (subStatesData[key] != "FeatureCollection")
            for (var i = 0; i < subStatesData[key].length + 1; i++) {
                // console.log(subStatesData.features[i])
                if (subStatesData.features[i].properties.ID_1 == (e.target.feature.id + 1)) {
                    //console.log(subStatesData.features[i])
                    geoSubJson = L.geoJson(subStatesData.features[i], {
                        style: style,
                        onEachFeature: onEachSubFeature
                    }).addTo(map);
                    geoSubArr.push(geoSubJson);
                }
            }
    }

}
function zoomLowerFeature(e) {
    var north = e.target.getBounds().getNorthWest()
    var south = e.target.getBounds().getSouthEast()
    bounds = L.latLngBounds(south, north);

    map.fitBounds([[north.lat, north.lng], [south.lat, south.lng]]);

    for (var j = 0; j < geoSubArr.length; j++) {
        geoSubArr[j].clearLayers()
        //console.log(geoSubArr[j])
    }

    for (key in lowerStates) {

        if (lowerStates[key] != "FeatureCollection")
            for (var i = 0; i < lowerStates[key].length + 1; i++) {
                // console.log(lowerStates.features[i])
                if (lowerStates.features[i].properties.ID_2 == (e.target.feature.id + 1)) {

                    geoSubJson = L.geoJson(lowerStates.features[i], {
                        style: style,
                        onEachFeature: onEachLowerFeature
                    }).addTo(map);
                    gerLowerArr.push(geoSubJson);
                }
            }
    }
    geoSubJson.clearLayers()
}

function zoomZipFeature(e) {
    var north = e.target.getBounds().getNorthWest()
    var south = e.target.getBounds().getSouthEast()
    bounds = L.latLngBounds(south, north);
    map.fitBounds([[north.lat, north.lng], [south.lat, south.lng]]);

    for (var j = 0; j < gerLowerArr.length; j++) {
        gerLowerArr[j].clearLayers()
    }

    for (key in zipStaes) {
        for (var i = 0; i < zipStaes[key].length + 1; i++) {
            if (e.target.feature.properties.NAME_3 == zipStaes.features[i].properties.name_2)
                geoSubJson = L.geoJson(zipStaes.features[i], {
                    style: style,
                    onEachFeature: onEachSubFeature
                }).addTo(map);
        }
    }
}


function onEachSubFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlightSub,
        click: zoomLowerFeature,
    });
}

function onEachLowerFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlightSub,
        click: zoomZipFeature,

    });
}

function redirect(e) {
    console.log("lower")
    // window.location.href = "{{route('atles')}}";
}

function onEachFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        click: zoomToFeature
    });

}


geojson = L.geoJson(statesData, {
    style: style,
    onEachFeature: onEachFeature
}).addTo(map);
function onEachSingleFeature(feature, layer) {

    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlightSub,
        click: zoomSingleFeature,
    });

    var north = layer.getBounds().getNorthWest()
    var south = layer.getBounds().getSouthEast()
    bounds = L.latLngBounds(south, north);

    map.fitBounds([[north.lat, north.lng], [south.lat, south.lng]]);
}
function zoomSingleFeature(e) {

}

function getZipCodeLocation() {
    var value = document.getElementById("zip").value;
    geojson.clearLayers()

    for (key in zipStaes) {
        if (key != "type") {
            for (var i = 0; i < zipStaes[key].length + 1; i++) {
                //console.log(key) 
                if (value == zipStaes.features[i].properties.zip_code) {
                    geoSubJson = L.geoJson(zipStaes.features[i], {
                        style: style,
                        onEachFeature: onEachSingleFeature
                    }).addTo(map);

                    break;
                }
            }
        }
    }
}