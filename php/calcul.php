<?php

include_once 'bdd.php';
require_once 'bdd.php';

class Aerodrome
{
	public $code;
	public $lat;
	public $long;
	public $zone; //7 is corse, 0 no zone
	public function __construct($code, $lat, $long, $zone) 
	{
		$this->code = $code;
		$this->lat = $lat;
		$this->long = $long;
		$this->zone = $zone;
	}
}

/*
 * Récupère la liste des aérodromes et la retourne à l'appelant
 */
function getAerodromes($dbh)
{
	$sql="SELECT codeOACI, lon, lat, no_zone FROM AERODROME;";
	$sth=$dbh->prepare($sql);
	$sth->execute();
	$listAerodrome=array();
	$id=0;
	while($result=$sth->fetch(PDO::FETCH_OBJ))
	{
		$listAerodrome[$id] = new Aerodrome($result->codeOACI,$result->lon,$result->lat, $result->no_zone);
		$id++;
	}
	$sth->closeCursor();
	/*FOR TEST*/ //print_r($listAerodrome); /*FOR TEST*/
	return $listAerodrome;
}

/*
 *  generate_trajectory génère un vol associé à une trajectoire complète avec 100 aérodromes.
 *
 * @param idFlight	Le FlightId à utiliser pour le vol à créer
 *
 * @param firstAerodrome Nom de l'Aérodrome utilisé pour démarrer la trajectoire (=décollage initial)
 *
 */
function generate_trajectory($idFlight, $firstAerodrome)
{
	// 1. Initialiser le sgbd
	$dbh=connection();

	// 2. Récup la liste des aérodromes
	$listAerodrome=getAerodromes($dbh);
	$key=searchC($listAerodrome,$firstAerodrome);
	$listN[0]=$listAerodrome[$key];

	// 2w. Récup le vent
	$cacheWindInfos = getMetars($dbh);

	// 3. Créer le vol
	createFlight($dbh, $idFlight,"Some team","F-THIS","71r3d");

	// 4. Ajouter l'aérodrome de départ
	$aerodrome = $listAerodrome[$key];
	$t = appendAerodrome( $dbh, $idFlight, /*datetime_sec*/ 0, $aerodrome, $aerodrome, $cacheWindInfos);
	array_splice($listAerodrome, $key, 1); //remove this airport from the list

	// 5. Ajouter jusqu'à 100 aérodromes suivants
	for($i=1; $i<=99; $i++)
	{
		$aerodrome_previous = $aerodrome;

		$id=rand(0,count($listAerodrome)-1);
		$listN[$i]=$listAerodrome[$id];
		$aerodrome =$listAerodrome[$id];

		$t += appendAerodrome( $dbh, $idFlight, /*datetime_sec*/ $t, $aerodrome, $aerodrome_previous, $cacheWindInfos);
		array_splice($listAerodrome, $id, 1);
		if($t >= 86400) // S'arrêter quand on atteind les 24 heures
			break;

	}
	return $listN;
}

function searchC($list, $what)
{
	foreach ($list as $key => $value)
	{
		if ($value->code==$what)
			$k=$key;
	}
	return $k;            
}

function dist($ad1,$ad2)
{
	$r = 6366;
	$lat1 = deg2rad($ad1->lat);
	$lon1 = deg2rad($ad1->long);
	$lat2 = deg2rad($ad2->lat);
	$lon2 = deg2rad($ad2->long);

	$dp= 2 * asin(sqrt(pow(sin(($lat1-$lat2)/2), 2)+cos($lat1)*cos($lat2)* pow(sin(($lon1-$lon2)/2), 2)));
	$distance = $dp * $r;
	return $distance;
}

/* createFlight se connecte à la DB, ajoute un vol et retourne la connexion à la SGBD */
function createFlight($dbh, $idFlight,$nameTeam,$aircraftNumber,$user)
{
	$sql="INSERT INTO `FLIGHT` VALUES ('".$idFlight."', '".$nameTeam."', '".$aircraftNumber."', '".$user."');";
	$sth=$dbh->prepare($sql);
	$sth->execute();
	$sth->closeCursor();
	return $dbh;
}

/* appendAerodrome ajoute un NavPoint à la DB.
 *  @param	$dbh			Objet de connexion au SGBD
 *  @param	$idFlight 		(explicite)
 *  @param	$takeofftime	Moment du décollage (exprimé en seconde)
 *  @param	$newAerodrome		(explicite)
 *  @param	$previousAerodrome 	(explicite)
 *  @param	$windInformations 	(explicite)
 *
 *  @return	La durée calculée du vol (en seconde)
 */
function appendAerodrome($dbh, $idFlight, $takeofftime, $newAerodrome, $previousAerodrome, $windInformations = NULL)
{
	//return appendAerodromeWithWind($dbh, $idFlight, $takeofftime, $newAerodrome, $previousAerodrome, $windInformations);
	return appendAerodromeWithoutWind($dbh, $idFlight, $takeofftime, $newAerodrome, $previousAerodrome);
}

function appendAerodromeWithoutWind($dbh, $idFlight, $takeofftime, $newAerodrome, $previousAerodrome)
{
	appendAerodrome_helper($dbh, $idFlight, $takeofftime, $newAerodrome, $previousAerodrome, 0);
}

function appendAerodromeWithWindSimpleModel($dbh, $idFlight, $takeofftime, $newAerodrome, $previousAerodrome, $windInformations)
{
	// 1. Lire le METAR correspondant à l'ancien aérodrome au moment du décollage
	$wind = extractInfosWindFromMetars($windInformations, $newAerodrome->no_zone, $takeofftime);

	// 2. Calculer la pénalité temporelle
	// Rq: windSpeed € 0..40 NM, windDirection € 0..360°
	$windPenality = 0.2 * $wind->windSpeed * ($wind->windDirection/360); // TODO Quelle formule ?

	// 3. Ajouter le NavPoint
	appendAerodrome_helper($dbh, $idFlight, $takeofftime, $newAerodrome, $previousAerodrome, $windPenality);
}

function appendAerodrome_helper($dbh, $idFlight, $takeofftime, $newAerodrome, $previousAerodrome, $windPenality = 0)
{
	$dureeVol = 0;
	$distanceVol = 0.0;

	// 1: on calcule les infos de vol
	if($newAerodrome != $previousAerodrome)
	{
		$distanceVol = dist($newAerodrome, $previousAerodrome);
		$dureeVol = $distanceVol * 18; // TODO Check it
	}

	$dureeVol = $dureeVol + $windPenality;

	// On ne redécolle pas si on va dépasser les 24 heures
	if($dureeVol > 86400)
		return $dureeVol;

	// 2: ajouter le Navpoint
	$zeroTime = time(); // TODO Changer time()...
	$datetime = date('Y-m-d G:i:s',$zeroTime+$takeofftime+$dureeVol); 
	$sql="INSERT INTO `NAVPOINT` (codeOACI, idFlight, datetimePoint, distanceToPreviousNavPoint) VALUES ('" 
		. $newAerodrome->code . "', '" . $idFlight . "', '" . $datetime . "', '" . $dureeVol . "');";

	$sth=$dbh->prepare($sql);
	$sth->execute();
	$sth->closeCursor();

	// 3: fin, on retourne la durée en seconde du vol
	return $dureeVol;
}


function deleteNav($idFlight)
{
	$dbh=connection();
	$sql="DELETE FROM FLIGHT WHERE idFlight = '".$idFlight."';";

	$sth=$dbh->prepare($sql);
	$sth->execute();
	$sth->closeCursor();
}

/* createWindInfo génère les METAR pour le vent, à partir de la date $temps */
function createWindInfos($temps)
{
	$dbh = connection();
	$dbh->query("DELETE FROM METAR; "); // Purger la table METAR au cas où...

	for($zone=0; $zone<=7; $zone++) // pour toutes les zones
	{
		for($i=0; $i<12; $i++) // par pas de 2 heures 
		{
			$windDirection = rand(0, 360);
			$windSpeed = rand(0, 40);
			$tm = $temps + 2*3600*$i;

			$sql = "INSERT INTO METAR (no_zone, datetimeMetar, windSpeed, windDirection) VALUES (";
			$sql .=$zone.", '".date('Y-m-d G:i:s', $tm)."', ".$windSpeed.", ".$windDirection.");";
			$dbh->query($sql);
		}
	}
	$dbh = NULL;
}

/* Retourne tous les METAR d'un coup, pour le mettre en cache */
function getMetars($dbh)
{
	$sql="SELECT no_zone, datetimeMetar, windSpeed, windDirection FROM METAR";
	$sth=$dbh->query($sql);
	$listMetar=array();
	for($zone=0; $zone<=7; $zone++) // pour toutes les zones
		$listMetar[$zone] = array();

	$id=0;
	while($result=$sth->fetch(PDO::FETCH_OBJ))
	{
		$listMetar[$result->no_zone][strtotime($result->datetimeMetar)] = 
			array("Speed" => $result->windSpeed,
					"Direction" => $result->windDirection);
	}
	$sth->closeCursor();
	/*FOR TEST*/ // print_r($listMetar); /*FOR TEST*/
	return $listMetar;
}

/* Extrait du cache contenant l'ensemble des metar celui le plus adéquat,
 * i.e. dans la bonne zone et le METAR le plus récent et antérieur à $temps.
 */
function extractInfosWindFromMetars($METARinCache, $no_zone, $temps)
{
	$MetarZone = $METARinCache[$no_zone]; // Extraire LA zone concernée

	// Parcourir les temps
	$previous_timing = 0;
	foreach ($MetarZone as $timing => $wind)
	{
		if (($timing > $previous_timing) && ($timing <= $temps))
		{
			$previous_timing = $timing;
			$windInfo = $wind;
		}
	}

	/*echo "Recherche pour zone=".$no_zone." et temps=".$temps." : ";
	  print_r($windInfo);*/

	return $windInfo;
}


function test_me()
{
	echo '<pre>';
	echo "Create/recreate Wind Infos... ";
	createWindInfos(time());

	/*echo " test getMetars: ";
	$cache = getMetars(connection());

	extractInfosWindFromMetars($cache, 1, time());  // now !!
	extractInfosWindFromMetars($cache, 1, time()+3600); // +1h
	extractInfosWindFromMetars($cache, 1, time()+7200); // +2h
	extractInfosWindFromMetars($cache, 1, time()+10400);// +3h
	extractInfosWindFromMetars($cache, 4, time()+10400);// +3h
	extractInfosWindFromMetars($cache, 0, time()+86420);// +24h20s
	extractInfosWindFromMetars($cache, 7, time()+86420);// +24h20s*/

	$idFl = "F-TEST";
	echo "Delete Nav... ";
	deleteNav($idFl);

	echo "Creating Trajectories () ... ";
	generate_trajectory($idFl, "LFGA");

	echo "Done !";
	echo '</pre>';
}

/* Programme principal */
test_me();

?>
