<?php

include_once '../php/db.php';
require_once '../php/db.php';
require_once '../php/calcul.php';

//switch to correct database
$dbh=connection();

if(isset($_SESSION["idFlt"]))
	$idFlt = $_SESSION["idFlt"];
else 
	$idFlt = NULL;

?>

////////////////////////////  GESTION DES ZONES (pour dessin) /////////////////////////

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



var VolDecollage = 0, VolAtterrissage = 0;

// var lastMarker = 0; // utilisable pour expérimenter avec les marqueurs

var WindZones; // Contiendra pour chacune des 7 zones et chaque bloc de 24 heures la direction et la force du vent

var symbolsarrowwind; // contiendra 7 flèches représentant les 7 directions de vent

var arrowWind = [] ; // Tous les objets gérant du vent









////////////////////////////  FONCTION D'INITIALISATION DE L'OBJET GOOGLE MAP ET DE SON CONTENU /////////////////////////

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


    // Define the symbol, using one of the predefined paths ('CIRCLE')
    // supplied by the Google Maps JavaScript API.
 /*   var symbolarrow = { // un premier exemple
        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
        scale: 5,
        strokeColor: '#777'
      }; */ 
/*    var symbolarrowwind = { // un 2e exemple 
        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
        scale: 5,
	strokeColor: '#777',
	strokeOpacity: 0.5,
	rotation: 0,
	fillColor: '#000000'
      };  */

      var symbolaircraft = {
	  path: 'm105.67322,-78l-124.03125,53.625c-10.38228,-6.92107 -34.20059,-21.27539 -38.90546,-23.44898c-39.44003,-18.22079 -36.9454,14.73107 -20.34924,24.6052c4.53917,2.70065 27.72351,17.17823 43.47345, 26.37502l17.90625,133.93749l22.21875,13.15625l11.53125,-120.93749l71.53125, 36.68749l3.84372, 39.21875l14.53128,8.625l11.09372,-42.40625l0.12503,0.0625l30.8125,-31.53123l-14.875,-8.00001l-35.625, 16.90626l-68.28125,-42.43751l97.6875,-72.18749l-22.6875,-12.25z',
	  scale: 0.2,
          strokeColor: '#777',
	  fillColor: '#000000',
	  fillOpacity: '0.8',
	  rotation: '60'

       };
    
    var infoWindow = new google.maps.InfoWindow;
    

////////////////////////////  GESTION TRAJECTOIRES DE VOL  /////////////////////////

        <?php

	$i_idFlt = 1; // Associe le numero avec le nom du vol sélectionné. A défaut on prend le premier
	$VolDecollage = NULL; // On réinitialise (au cas où...)
	$VolAtterrissage = NULL;
	$i = 1;
	$sql="SELECT DISTINCT idFlight FROM FLIGHT_PATH";
	$sth=$dbh->query($sql);
	while($result=$sth->fetch(PDO::FETCH_OBJ)){
		echo "var flightPlanCoordinates_$i = [ // Flight Plan for $result->idFlight... \n";
		if($result->idFlight == $idFlt || $idFlt == NULL)
		{
			echo "// Vol sélectionné = $result->idFlight \n";
			$i_idFlt = $i; // On l'a trouvé!
			$VolDecollage = NULL; // On réinitialise (au cas où...)
			$VolAtterrissage = NULL;
			$idFlt = $result->idFlight; // Au cas où aucun vol n'est sélectionné
		}

		$sql_in="SELECT codeOACI, lat, lon, datetimePoint  FROM FLIGHT_PATH WHERE idFlight = '$result->idFlight'";
		$sth_in=$dbh->query($sql_in);

		while($result_in=$sth_in->fetch(PDO::FETCH_OBJ)){
			$lat = $result_in->lat;
			$lon = $result_in->lon;
			echo "new google.maps.LatLng(" . $lat . ", " . $lon . "), \n";

			if($result->idFlight == $idFlt)
			{
				// Vol sélectionné => on définit les moments
				$moment = $result_in->datetimePoint;
				if(!$VolDecollage || $VolDecollage > $moment)
					$VolDecollage = $moment;
				if(!$VolAtterrissage || $VolAtterrissage < $moment)
					$VolAtterrissage = $moment;
			}
		}
		$sth_in->closeCursor();
		echo "];\n";
		$i++;
	}
	$sth->closeCursor();

	echo "    VolDecollage = new Date(\"$VolDecollage\"); 
	VolAtterrissage = new Date(\"$VolAtterrissage\");\n";

	for($j=1;$j<$i;$j++)
	{
		echo '

var flightPath_'.$j.' = new google.maps.Polyline( //définit le style de la trajectoire
    {
    path: flightPlanCoordinates_'.$j.',
    geodesic: true, //Use orthodromie
    strokeColor: \'#' . ( $j == $i_idFlt ? "FF0000" : "00FF00" ). '\',
    strokeOpacity: 1.0,
    strokeWeight: 2,
	';
		if($j==$i_idFlt) {
		echo '
    icons:
    [{
        icon: symbolaircraft,
        offset: \'100\%\'
    }],'; 
}
		echo '   map: map
    }); ';
	}

	echo "\nvar flightPath = flightPath_$i_idFlt;\n";


////////////////////////////  GESTION FLECHES VENT /////////////////////////

	// On récupère les infos de vent
	$metars = getMetars($dbh);
	echo " WindZones = [ ";
	
	$hDecol = strtotime($VolDecollage);
	$hAtter = strtotime($VolAtterrissage);

	for($i = 0 ; $i < 20 ; $i++ ) //On prend 20 pos$it$ions de l'av$ion sur sa trajecto$ire et on récupère à chaque fo$is les données de vent des 7 zones
	{
		echo " [ ";
		$t = $hDecol + ($hAtter-$hDecol)*$i/20;
		for($z = 0 ; $z < 7 ; $z++)
		{
			$wind = extractInfosWindFromMetars($metars, $z, $t);
			if($wind == NULL) {
				echo " /// Euh, wind nul pour $z et $t... \n";
				echo " {windSpeed: 0, windDirection: 0}, \n";
			} else {
				//echo "  " . $wind["windSpeed"] . " , // z=$z, t=$t \n";	
				echo " {windSpeed: " . $wind["windSpeed"] . ", windDirection: " . $wind["windDirection"] . "}, // z=$z, t=$t \n";	
			}
		}
		echo " ], ";

	}

	echo " ]; ";


		/* On crée 7 icônes de flèches, une par zone ! */
		echo " symbolsarrowwind = [ ";
		for($i=0;$i<7;$i++)
		{
			echo '
     { 
        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
        scale: 5,
	strokeColor: \'#777\',
	strokeOpacity: 0.5,
	rotation: 0,
	fillColor: \'#000000\'
      }, ';
		}
		echo " ]; ";


		$id=1;
		$scale = 1.4;
		for($lat = 42.2; $lat <= 52.0; $lat += $scale)
		{
			for($lon = -4.1; $lon <= 8.0; $lon += $scale)
			{
				if($lat > 49.75) $zone_id = 1;
				else if($lon < -1.667) $zone_id = 2;
				else if($lat < 44.5 && $lon < 2) $zone_id = 3;
				else if($lat < 44.5 && $lon > 5) $zone_id = 4;
				else if($lon > 6) $zone_id = 5;
				else if($lat < 48 && $lon > 1 && $lat > 45.5 && $lon < 4) $zone_id = 6;
				else $zone_id = 0;

				echo "var polyline_$id = new google.maps.Polyline( { path: [ ";
				echo "new google.maps.LatLng(" . $lat . ", " . $lon . "), new google.maps.LatLng(" . $lat . ", " . ($lon+0.5) . ") ";
				echo "], geodesic: false, strokeOpacity: 0, map: map, icons: [{ icon: symbolsarrowwind[$zone_id], offset: 0 }] } );\n";

				//echo "WindZones[$zone_id].push( polyline_$id ); \n";
				$id++;
			}
		}
		for($i=1;$i<$id;$i++)
		{
			echo "arrowWind.push(polyline_$i);";
		}
		echo "\n";
			
	?>


////////////////////////////  GESTION DESSIN AVION /////////////////////////

    var lengthInMeters = google.maps.geometry.spherical.computeLength(flightPath.getPath());
    //alert("polyline is "+lengthInMeters/1000+" long");
    
    animateCircle(flightPath,lengthInMeters);
    flightPath.setMap(map);
    
////////////////////////////  GESTION DESSIN AEROPORTS /////////////////////////

    downloadUrl('http://localhost/beweb2017/php/generation_map_markers.php', function(data) 
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
		icon: '/beweb2017/images/airport2.ico'
	    });

	    //marker.visible = true;
	    //lastMarker = marker;
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
    

////////////////////////////  GESTION ANIMATION DE L'AVION /////////////////////////



// Use the DOM setInterval() function to change the offset of the symbol
// at fixed intervals.
function animateCircle(line,length) 
{
    var count = 0;
    window.setInterval(function() 
    {
	// Fonction appelée périodiquement
	
        if (stop)
	{
		// Pas la peine de faire quoi que ce soit...
		return;
	}

        count = (count + 1) % 2000;
	// Toutes les 2 secondes on met à jour les flèches de position du vent
	if(count%100==1)
	{

		// lastMarker.setOptions({visible: lastMarker.visible}); // pour afficher ou cacher un marqueur

		no_idx = Math.floor(count / 100); // no_idx va de 0 à 19

		// Pour toutes les zones
		for(zone_nb = 0; zone_nb < 7; zone_nb++)
		{
			var infos = WindZones[no_idx][zone_nb];
			var vitesse = infos.windSpeed;
			var direction = infos.windDirection;
			symbolsarrowwind[zone_nb].rotation = direction;
			symbolsarrowwind[zone_nb].scale /* 0 à 8 */= vitesse /* 0 à 40 */ / 5;
			// alert(" z="+zone_nb+" idx="+no_idx+" spd = "+vitesse+" dir="+direction);
			//symbolsarrowwind[zone_nb].rotation = no_idx*10;
		}

		// Forcer le rafraîchssement de toutes les flèches
		Array.prototype.forEach.call(arrowWind, function(item)
		{
			item.set('icons', item.get('icons'));
		});

	}

	// Définir ce qu'on affiche dans la barre :
	var h = new Date(VolDecollage.getTime() + (VolAtterrissage.getTime()-VolDecollage.getTime()) * (count / 2000));
	hrs=h.getHours();
	min=h.getMinutes();
	sec=h.getSeconds();
	tm=" "+((hrs<10)?"0":"") +hrs+":";
	tm+=((min<10)?"0":"")+min+":";
	tm+=((sec<10)?"0":"")+sec+" "; 

	document.getElementById('info-box').textContent = "Départ le "+VolDecollage.toLocaleString() + "\n";
        document.getElementById('info-box').textContent += Math.round(length*count/2000000) + ' km ('+ tm +')';

	// Mettre à jour la position de l'avion
        var icons = line.get('icons');
        icons[0].offset = (count / 20) + '%';
        line.set('icons', icons);

    }, 20 /* 20 ms = 50 Hz = fréq. double de persistence rétinienne = visuel fluide graphiquement */ );
}

