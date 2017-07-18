
var map;
var zone1 = [{
        lat: 51.0,
        lng: 1.65
    }, // north west
    {
        lat: 49.75,
        lng: 0
    }, //south west
    {
        lat: 49.75,
        lng: 5.7
    }, // south east
    {
        lat: 51.2,
        lng: 2.75
    } // north east
];

var zone2 = [{
        lat: 48.8,
        lng: -5.2
    }, // north west
    {
        lat: 47.9,
        lng: -5.2
    }, //south west
    {
        lat: 46.3,
        lng: -1.667
    }, // south east
    {
        lat: 49,
        lng: -1.667
    } // north east
];

var zone3 = [{
        lat: 44.5,
        lng: -1.5
    }, // north west
    {
        lat: 43,
        lng: -2
    }, //south west
    {
        lat: 42.2,
        lng: 2
    }, // south east
    {
        lat: 44.5,
        lng: 2
    } // north east
];

var zone4 = [{
        lat: 44.5,
        lng: 5
    }, // north west
    {
        lat: 43.25,
        lng: 5
    }, //south west
    {
        lat: 42.9,
        lng: 6.27
    }, // south middle
    {
        lat: 43.7,
        lng: 7.8
    }, // south east
    {
        lat: 44.5,
        lng: 7.8
    } // north east
];

var zone5 = [{
        lat: 49.61,
        lng: 6
    }, // north west
    {
        lat: 46.5,
        lng: 6
    }, //south west
    {
        lat: 46.5,
        lng: 6.11
    }, //south middle
    {
        lat: 47.55,
        lng: 7.7
    }, // south east
    {
        lat: 48.98,
        lng: 8.25
    } // north east
];

var zone6 = [{
        lat: 48,
        lng: 1
    }, // north west
    {
        lat: 45.5,
        lng: 1
    }, //south west
    {
        lat: 45.5,
        lng: 4
    }, // south east
    {
        lat: 48,
        lng: 4
    } // north east
];

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: 46.606110,
            lng: 1.875280
            /*lat: 43.5,
            lng: 6*/
        },
        zoom: 6
    });
    map.data.add({
        geometry: new google.maps.Data.Polygon([zone1, zone2, zone3, zone4, zone5, zone6])
    });
    map.data.loadGeoJson('/beweb2017/airport.geojson');
    map.data.setStyle({
        clickable: 'true',
        icon: '/beweb2017/image/airport2.ico'
    });
    map.data.addListener('mouseover', function(event) {
        document.getElementById('info-box').textContent = event.feature.getProperty('Comment');
    });
    
    var flightPlanCoordinates = [
      {lng: 5.33778, lat: 45.9797},
      {lng: -0.85028, lat: 48.1772},
      {lng: 6.26833, lat: 46.1919},
      {lng: 0.09944, lat: 48.1953}
    ];
    var flightPath = new google.maps.Polyline({
      path: flightPlanCoordinates,
      geodesic: true,
      strokeColor: '#FF0000',
      strokeOpacity: 1.0,
      strokeWeight: 2
    });

    flightPath.setMap(map);

}
