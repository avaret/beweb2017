
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
    
    var infoWindow = new google.maps.InfoWindow;
    
    var flightPlanCoordinates = //Donne les coordonné des points de passages
    [
//      {lng: 5.33778, lat: 45.9797},
//      {lng: -0.85028, lat: 48.1772},
//      {lng: 6.26833, lat: 46.1919},
//      {lng: 0.09944, lat: 48.1953}
        
        <?php
        include_once '../php/bdd.php';
        require_once '../php/bdd.php';

        //switch to correct database
        $dbh=connection();

        //Query the user for start and ending location. Store locations in variables
        $sql="SELECT navpoint.codeOACI, lat, lon  FROM `navpoint`,aerodrome WHERE navpoint.codeOACI=aerodrome.codeOACI;";
        $sth=$dbh->prepare($sql);
        $sth->execute();
        
       while($result=$sth->fetch(PDO::FETCH_OBJ)){
            $lat = $result->lat;
            $lon = $result->lon;
            echo 'new google.maps.LatLng('.$lat.', '.$lon.'),';
        }

        ?>
    ];
    
    var flightPath = new google.maps.Polyline( //définie le style de la trajectoire
    {
      path: flightPlanCoordinates,
      geodesic: true,
      strokeColor: '#FF0000',
      strokeOpacity: 1.0,
      strokeWeight: 2
    });

    flightPath.setMap(map);
    
    downloadUrl('http://localhost/beweb2017/php/generation_map.php', function(data) 
    {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName('marker');
        Array.prototype.forEach.call(markers, function(markerElem) 
        {
            var codeOACI = markerElem.getAttribute('codeOACI');
            var description = markerElem.getAttribute('description');
            var type = markerElem.getAttribute('type');
            var point = new google.maps.LatLng
            (
              parseFloat(markerElem.getAttribute('lat')),
              parseFloat(markerElem.getAttribute('lng'))
            );

            var infowincontent = document.createElement('div');
            var strong = document.createElement('strong');
            strong.textContent = codeOACI
            infowincontent.appendChild(strong);
            infowincontent.appendChild(document.createElement('br'));

            var text = document.createElement('text');
            text.textContent = description
            infowincontent.appendChild(text);
            //var icon = customLabel[type] || {};
            var marker = new google.maps.Marker
            ({
                map: map,
                position: point,
                icon: '/beweb2017/image/airport2.ico'
            });
            marker.addListener('click', function() 
            {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
            });
        });
    });
}

function downloadUrl(url, callback) 
{
    var request = window.ActiveXObject ?
        new ActiveXObject('Microsoft.XMLHTTP') :
        new XMLHttpRequest;

    request.onreadystatechange = function() {
      if (request.readyState == 4) {
        request.onreadystatechange = doNothing;
        callback(request, request.status);
      }
    };

    request.open('GET', url, true);
    request.send(null);
}

function doNothing() {}

