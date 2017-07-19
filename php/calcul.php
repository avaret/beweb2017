<?php

include_once 'bdd.php';
require_once 'bdd.php';

class Aerodrome
{
    public $code;
    public $lat;
    public $long;
    public $zone; //7 is corse, 0 no zone
    //public $distP; //Use for navpoint to get the distance from the previous one
    public function __construct($code, $lat, $long, $zone) 
    {
            $this->code = $code;
            $this->lat = $lat;
            $this->long = $long;
            $this->zone = $zone;
    }
}


function getAerodrome($dbh)
{
    $sql="SELECT codeOACI, lon, lat, no_zone FROM AERODROME;";
    $sth=$dbh->prepare($sql);
    $sth->execute();
    $listAerodrome=array();
    $id=0;
    //$html="test <br\>";
    //echo $html;
    while($result=$sth->fetch(PDO::FETCH_OBJ))
    {
        $listAerodrome[$id] = new Aerodrome($result->codeOACI,$result->lon,$result->lat, $result->no_zone);
        $id++;
    }
    //echo $html;
    $sth->closeCursor();
    /*FOR TEST*/ //print_r($listAerodrome); /*FOR TEST*/
    return $listAerodrome;
}

/*
 *  traj génère un vol associé à une trajectoire complète avec 100 aérodromes.
 *
 * @param idFlight	Le FlightId à utiliser pour le vol à créer
 *
 * @param firstAerodrome Nom de l'Aérodrome utilisé pour démarrer la trajectoire (=décollage initial)
 *
 */
function traj($idFlight, $firstAerodrome)
{
    // 1. Initialiser le sgbd
    $dbh=connection();

    // 2. Récup la liste des aérodromes
    $listAerodrome=getAerodrome($dbh);
    $key=searchC($listAerodrome,$firstAerodrome);
    $listN[0]=$listAerodrome[$key];

    // 3. Créer le vol
    createFlight($dbh, $idFlight,"Some team","F-THIS","71r3d");
   
    // 4. Ajouter l'aérodrome de départ
    $aerodrome = $listAerodrome[$key];
    $t = appendAerodrome( $dbh, $idFlight, /*datetime_sec*/ 0, $aerodrome, $aerodrome);

/*    $sql="INSERT INTO `NAVPOINT` VALUES ('".$listAerodrome[$key]->code."', '".$idFlight."', '".date('Y-m-d G:i:s',time())."');";
    $sth=$dbh->prepare($sql);
    $sth->execute();*/
    array_splice($listAerodrome, $key, 1); //remove this airport from the list
    
    // 5. Ajouter jusqu'à 100 aérodromes suivants
    for($i=1; $i<=99; $i++)
    {
        $aerodrome_previous = $aerodrome;

        $id=rand(0,count($listAerodrome)-1);
        $listN[$i]=$listAerodrome[$id];
        $aerodrome =$listAerodrome[$id];

        $t += appendAerodrome( $dbh, $idFlight, /*datetime_sec*/ $t, $aerodrome, $aerodrome_previous);
        array_splice($listAerodrome, $id, 1);
	if($t > 86400)
		break;
//TODO S'arrêter si on dépasse les 24 heures == ($t >86400), AVANT d'ajouter le point !!

/*        $listN[$i]->distP=dist($listN[$i-1],$listN[$i]);
        $t+=($listN[$i]->distP/200)*3600;
        
        $sql="INSERT INTO `NAVPOINT` VALUES ('".$listAerodrome[$id]->code."', '".$idFlight."', '".date('Y-m-d G:i:s',time()+$t)."');";
        $sth=$dbh->prepare($sql);
        $sth->execute();*/
    }
    //$sth->closeCursor();
    //print_r($listN);
    //echo date('Y-m-d G:i:s',time());
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
 *  @param	$dbh		Objet de connexion au SGBD
 *  @param	$idFlight 	(explicite)
 *  @param	$datetime_takeoff_sec	Moment du décollage (exprimé en seconde)
 *  @param	$newAerodrome	(explicite)
 *  @param	$previousAerodrome 	(explicite)
 *  @return	La durée calculée du vol (en seconde)
 */
function appendAerodrome($dbh, $idFlight, $datetime_takeoff_sec, $newAerodrome, $previousAerodrome)
{
    return appendAerodromeWithoutWind($dbh, $idFlight, $datetime_takeoff_sec, $newAerodrome, $previousAerodrome);
}

function appendAerodromeWithoutWind($dbh, $idFlight, $datetime_takeoff_sec, $newAerodrome, $previousAerodrome)
{
    appendAerodrome_helper($dbh, $idFlight, $datetime_takeoff_sec, $newAerodrome, $previousAerodrome, 0);
}

function appendAerodromeWithWindSimpleModel($dbh, $idFlight, $datetime_takeoff_sec, $newAerodrome, $previousAerodrome)
{
	// TODO Faire le modèle simplifié
    $windPenality = 50; // FIXME

	// 1. Lire le METAR correspondant à l'ancien aérodrome au moment du décollage

	// 2. Calculer la pénalité temporelle

	// 3. Ajouter le NavPoint
    appendAerodrome_helper($dbh, $idFlight, $datetime_takeoff_sec, $newAerodrome, $previousAerodrome, $windPenality);
}

function appendAerodrome_helper($dbh, $idFlight, $datetime_takeoff_sec, $newAerodrome, $previousAerodrome, $windPenality)
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

    // 2: ajouter le Navpoint
    $zeroTime = time(); // TODO Changer time()...
    $datetime = date('Y-m-d G:i:s',$zeroTime+$datetime_takeoff_sec+$dureeVol); 
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
//    $sql="DELETE FROM NAVPOINT WHERE idFlight = '".$idFlight."';";
    $sql="DELETE FROM FLIGHT WHERE idFlight = '".$idFlight."';"; // TODO Check que la CASCADE ON DELETE fonctionne.

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

function test_me()
{
    echo "Create/recreate Wind Infos... ";
    createWindInfos(time());

    $idFl = "F-TEST";
    echo "Delete Nav... ";
    deleteNav($idFl);
    echo "Creating Traj() ... ";
    traj($idFl, "LFGA");
    echo "Done !";
}

/* Programme principal */
test_me();

?>
