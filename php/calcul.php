<?php

include_once 'db.php';
require_once 'db.php';

DEFINE("AIRCRAFT_SPEED_KM_H", 260);

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
	$sql="SELECT codeICAO, lon, lat, no_zone FROM AERODROME;";
    $sth=$dbh->prepare($sql);
    $sth->execute();
    $listAerodrome=array();
    $id=0;
    while($result=$sth->fetch(PDO::FETCH_OBJ))
    {
		$listAerodrome[$id] = new Aerodrome($result->codeICAO,$result->lat,$result->lon, $result->no_zone);
        $id++;
    }
    $sth->closeCursor();
    /*FOR TEST*/ //print_r($listAerodrome); /*FOR TEST*/
    return $listAerodrome;
}

/*
 * Détruit un vol et ses points de navigation
 */
function removeFlight($flight)
{
    $sql = "DELETE FROM `FLIGHT` WHERE idFlight = '".$flight."'";
    $dbh = connection();
    $sth = $dbh->query($sql);
}

function sanitize($var, $type = "all")
{
    switch($type) {
        case 'html':
            $safe = htmlspecialchars($var);
            break;
        case 'sql':
            $safe = preg_replace('~[\x00\x0A\x0D\x1A\x22\x25\x27\x5C\x5F]~u', '', $var);
            break;
        case 'file':
            $safe = preg_replace('/(\/|-|_)/','',$var);
            break;
        case 'shell':
            $safe = escapeshellcmd($var);
            break;
        default:
            $safe = sanitize(sanitize(sanitize(sanitize( $var , 'shell') , 'file'), 'html'), 'sql');
    }
    return $safe;
}

/*
 *  generate_trajectory génère un vol associé à une trajectoire complète avec au plus 100 aérodromes, on s'arrête juste avant de dépasser 24 heures.
 *
 * @param idFlight	Le FlightId à utiliser pour le vol à créer
 *
 * @param firstAerodrome Nom de l'Aérodrome utilisé pour démarrer la trajectoire (=décollage initial)
 *
 * @param teamName, aircraftNumber, login, useWind, racebegintime : Explicite
 *
 */
function generate_trajectory($idFlight, $firstAerodrome, $teamName = "Some team", $aircraftNumber = "F-THIS", $login = "71r3d", $useWind = true, $racebegintime = 0)
{
    // 0. Sanitize
    $idFlight = sanitize($idFlight, 'sql');
    $firstAerodrome = sanitize($firstAerodrome, 'sql');
    $teamName = sanitize($teamName, 'sql');
    $aircraftNumber = sanitize($aircraftNumber, 'sql');

    if(strlen($idFlight) > 16)
        return "The Flight Identifier must be shorter than 16 characters.";

    if(strlen($firstAerodrome) != 4 || $firstAerodrome[0] != 'L' || $firstAerodrome[1] != 'F')
        return "The first Aerodrome must be exactly 4 characters and begin by 'LF'.";

    if(strlen($teamName) > 64)
        return "The team name must not exceed 32 characters.";

    if(strlen($aircraftNumber) != 6)
        return "The Aircraft Registration/number must be exactly 6 characters.";


    // 1. Initialiser le sgbd
    $dbh=connection();

    // 2. Récup la liste des aérodromes
    $listAerodrome=getAerodromes($dbh);
    $key=searchC($listAerodrome,$firstAerodrome);
    if(!$key)
        return "Impossible de créer le vol: l'aérodrome de départ n'existe probablement pas dans la base de données.";

    $listN[0]=$listAerodrome[$key];

    // 2w. Récup le vent
    if($useWind)
        $cacheWindInfos = getMetars($dbh);
    else
        $cacheWindInfos = NULL;

    // 3. Créer le vol
    try {
        createFlight($dbh, $idFlight, $teamName, $aircraftNumber, $login);
    } catch (PDOException $e) {
        return "Impossible de créer le vol : l'identifiant du Vol (FlightId) est probablement déjà utilisé.";
    }

    // 4. Ajouter l'aérodrome de départ
    $aerodrome = $listAerodrome[$key];
    $t = appendAerodrome( $dbh, $idFlight, $racebegintime, $racebegintime, $aerodrome, $aerodrome, $cacheWindInfos);

    array_splice($listAerodrome, $key, 1); //remove this airport from the list

    // 5. Ajouter jusqu'à 100 aérodromes suivants
    for($i=1; $i<=99; $i++)
    {
        $aerodrome_previous = $aerodrome;

        // Méthode 1: ajouter aléatoirement n'importe quel aérodrome
        $id=rand(0,count($listAerodrome)-1);

        // Méthode 2: ajouter aléatoirement un aérodrome pas trop loin 3 fois sur 4, un aérodrome loin 1 fois sur 4
        if($id%4==0)
        {
            // Chercher aérodrome loin
            $id=rand(0,count($listAerodrome)-1);
        } else {
            $tentative = 0;
            // Chercher aérodrome proche
            do {
                $id=rand(0,count($listAerodrome)-1);
                $dist_proche = dist($aerodrome_previous, $listAerodrome[$id]) < 200;
                $tentative++;
            } while($tentative<20);
        }

        $listN[$i]=$listAerodrome[$id];
        $aerodrome =$listAerodrome[$id];

        $t = appendAerodrome( $dbh, $idFlight, /*datetime_sec*/ $t, $racebegintime, $aerodrome, $aerodrome_previous, $cacheWindInfos);
        array_splice($listAerodrome, $id, 1);
        if($t == NULL) // S'arrêter quand on atteind les 24 heures
            break;

    }
    return NULL;
}

function generate_smart_trajectory($idFlight, $firstAerodrome, $teamName = "Some team", $aircraftNumber = "F-THIS", $login = "71r3d", $useWind = true, $racebegintime = 0)
{
    // 0. Sanitize
    $idFlight = sanitize($idFlight, 'sql');
    $firstAerodrome = sanitize($firstAerodrome, 'sql');
    $teamName = sanitize($teamName, 'sql');
    $aircraftNumber = sanitize($aircraftNumber, 'sql');

    if(strlen($idFlight) > 16)
        return "The Flight Identifier must be shorter than 16 characters.";

    if(strlen($firstAerodrome) != 4 || $firstAerodrome[0] != 'L' || $firstAerodrome[1] != 'F')
        return "The first Aerodrome must be exactly 4 characters and begin by 'LF'.";

    if(strlen($teamName) > 64)
        return "The team name must not exceed 32 characters.";

    if(strlen($aircraftNumber) != 6)
        return "The Aircraft Registration/number must be exactly 6 characters.";


    // 1. Initialiser le sgbd
    $dbh=connection();

    // 2. Récup la liste des aérodromes
    $listAerodrome=getAerodromes($dbh);
    $key=searchC($listAerodrome,$firstAerodrome);
    if(!$key)
        return "Impossible de créer le vol: l'aérodrome de départ n'existe probablement pas dans la base de données.";

    $listN[0]=$listAerodrome[$key];

    // 2w. Récup le vent
    if($useWind)
        $cacheWindInfos = getMetars($dbh);
    else
        $cacheWindInfos = NULL;

    // 3. Créer le vol
    try {
        createFlight($dbh, $idFlight, $teamName, $aircraftNumber, $login);
    } catch (PDOException $e) {
        return "Impossible de créer le vol : l'identifiant du Vol (FlightId) est probablement déjà utilisé.";
    }

    // 4. Ajouter l'aérodrome de départ
    $aerodrome = $listAerodrome[$key];
    $t = appendAerodrome( $dbh, $idFlight, $racebegintime, $racebegintime, $aerodrome, $aerodrome, $cacheWindInfos);

    array_splice($listAerodrome, $key, 1); //remove this airport from the list
    
    for($c=0; $c<=7; $c++)
    {
        $zoneTable[$c]=1;
    }
    
    // 5. Ajouter jusqu'à 100 aérodromes suivants
    for($i=1; $i<=99; $i++)
    {
        $aerodrome_previous = $aerodrome;
        $lowestDist=dist($listAerodrome[0],$aerodrome_previous)*$zoneTable[$listAerodrome[0]->zone];
        $lowestDistInd=0;
        foreach ($listAerodrome as $key => $value) //calcule la distance par raport à tous les autres terrains
        {
            $allDist[$key]=dist($value,$aerodrome_previous)*$zoneTable[$value->zone];
            if (($lowestDist==null) or ($lowestDist > $allDist[$key]))
                {
                    $SecondLowestInd=$lowestDistInd;
                    $lowestDist=$allDist[$key];
                    $lowestDistInd=$key;
                }
        }
        if (rand(1,10) < 4)
            $choose=$SecondLowestInd;
        else
            $choose=$lowestDistInd;
        
        $listN[$i]=$listAerodrome[$choose];
        $aerodrome =$listAerodrome[$choose];
        $zoneTable[$aerodrome->zone]+=0.1;

        $t = appendAerodrome( $dbh, $idFlight,  $t, $racebegintime, $aerodrome, $aerodrome_previous, $cacheWindInfos);
        array_splice($listAerodrome, $choose, 1);
        if($t == NULL) // S'arrêter quand on atteind les 24 heures
            break;

    }
    return NULL;
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

/* Retourne la distance en km entre 2 aérodromes */
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


function calculAngle($ad1,$ad2)
{
    $lat1 = deg2rad($ad1->lat);
    $lon1 = deg2rad($ad1->long);
    $lat2 = deg2rad($ad2->lat);
    $lon2 = deg2rad($ad2->long);
    $y = sin($lon2-$lon1) * cos($lat2);
    $x = cos($lat1)*sin($lat2)-sin($lat1)*cos($lat2)*cos($lon2-$lon1);
    return $brng = (atan2($y, $x)/3.1415926) * 180;
}

function calculAngle2($ad1, $ad2) 
{

    $lat1 = ($ad1->lat);
    $lon1 = ($ad1->long);
    $lat2 = ($ad2->lat);
    $lon2 = ($ad2->long);

    $dLon = deg2rad($lon2) - deg2rad($lon1);
    $dPhi = log(tan(deg2rad($lat2) / 2 + pi() / 4) / tan(deg2rad($lat1) / 2 + pi() / 4));

    if(abs($dLon) > pi()) {
        if($dLon > 0) {
            $dLon = (2 * pi() - $dLon) * -1;
        }
        else {
            $dLon = 2 * pi() + $dLon;
        }
    }
    //return the angle, normalized
    return (rad2deg(atan2($dLon, $dPhi)) + 360) % 360;
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
 *  @param	$takeofftime		Moment du décollage (exprimé en seconde)
 *  @param	$racebegintime		Moment du déébut de la course (du tout premier décollage, pour limiter à 24h)
 *  @param	$newAerodrome		(explicite)
 *  @param	$previousAerodrome 	(explicite)
 *  @param	$windInformations 	(explicite) mettre NULL pour ignorer l'effet du vent
 *
 *  @return	L'heure d'atterrissage (=La durée calculée du vol (en seconde) + l'heure de décollage) OU NULL si dépassement des 24 heures
 *
 *  Remarque: le vol n'est PAS ajouté si on dépasse les 24 heures !!
 */
function appendAerodrome($dbh, $idFlight, $takeofftime, $racebegintime, $newAerodrome, $previousAerodrome, $windInformations = NULL)
{
    if($windInformations)
    {
        // 1. Lire le METAR correspondant à l'ancien aérodrome au moment du décollage
        $wind = extractInfosWindFromMetars($windInformations, $newAerodrome->zone, $takeofftime, $racebegintime);

        // 2. Calculer la pénalité temporelle
        // Rq: windSpeed € 0..40 NM, windDirection € 0..360°
        $groundSpeed_km_h = groundSpeed(calculAngle2($newAerodrome,$previousAerodrome), $wind["windDirection"], $wind["windSpeed"]);

        // 3. Ajouter le NavPoint
    }
    else
    {
        // Pas de vent !
        $groundSpeed_km_h = AIRCRAFT_SPEED_KM_H;
    }


    $dureeVol = 0;
    $distanceVol = 0.0;

    // 1: on calcule les infos de vol
    if($newAerodrome != $previousAerodrome)
    {
        $distanceVol = dist($newAerodrome, $previousAerodrome);
        $dureeVol = $distanceVol / $groundSpeed_km_h * 3600; /* en seconde */
    }

    // On ne redécolle pas si on va dépasser les 24 heures
    $landingtime = $takeofftime + $dureeVol;
    if($landingtime > $racebegintime + 86400)
        return NULL;

    // 2: ajouter le Navpoint
    $datetime = date('Y-m-d G:i:s',$landingtime); 
	$sql="INSERT INTO `NAVPOINT` (codeICAO, idFlight, datetimePoint, distanceToPreviousNavPoint) VALUES ('" 
        . $newAerodrome->code . "', '" . $idFlight . "', '" . $datetime . "', '" . $distanceVol . "');";

    $sth=$dbh->prepare($sql);
    $sth->execute();
    $sth->closeCursor();

    // 3: fin, on retourne la durée en seconde du vol
    return $landingtime;
}


function deleteNav($idFlight)
{
    $dbh=connection();
    $sql="DELETE FROM FLIGHT WHERE idFlight = '".$idFlight."';";

    $sth=$dbh->prepare($sql);
    $sth->execute();
    $sth->closeCursor();
}

/* createWindInfo génère les METAR pour le vent, à partir de la date $temps. Génère $nombre METAR de 2 heures chacuns */
function createWindInfos($temps, $nombre = 12)
{
    $dbh = connection();
    $dbh->query("DELETE FROM FLIGHT; "); // Purger la table FLIGHT, car les anciens vols seront invalidés par le changement de vents

    $dbh->query("DELETE FROM METAR; "); // Purger la table METAR au cas où...

    for($zone=0; $zone<=7; $zone++) // pour toutes les zones
    {
        for($i=0; $i<$nombre; $i++) // par pas de 2 heures 
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
            array("windSpeed" => $result->windSpeed,
                  "windDirection" => $result->windDirection);
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
    $windInfo = NULL; // Au cas où il n'y aurait aucune info, on retourne NULL.
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

function groundSpeed($angleAvion_deg, $angleVent_deg, $forceVent_km_h) //angleVent=30 le vent vient du cap 30°
{
    return $speed_km_h=AIRCRAFT_SPEED_KM_H - cos(deg2rad($angleVent_deg-$angleAvion_deg)) * $forceVent_km_h;
}

function test_metars()
{
    /*echo " test getMetars: ";
	$cache = getMetars(connection());

	extractInfosWindFromMetars($cache, 1, time());  // now !!
	extractInfosWindFromMetars($cache, 1, time()+3600); // +1h
	extractInfosWindFromMetars($cache, 1, time()+7200); // +2h
	extractInfosWindFromMetars($cache, 1, time()+10400);// +3h
	extractInfosWindFromMetars($cache, 4, time()+10400);// +3h
	extractInfosWindFromMetars($cache, 0, time()+86420);// +24h20s
	extractInfosWindFromMetars($cache, 7, time()+86420);// +24h20s*/

}

function test_groundspeedfunction()
{
    /*
	echo calculAngle2(new Aerodrome('LFGA',48.1036, 7.33019,'3'),new Aerodrome('LFBR',43.4432, 1.27331,'2'));

	echo groundSpeed(222,100,50);
	*/

    echo "EST OUEST";
    $newAerodrome = new Aerodrome("LFAA",45.0,5.0, 1);
    $previousAerodrome= new Aerodrome("LFBA",45.0,25.0, 1);
    for($i=0;$i<36;$i++)
    {
        echo $i . "° >> " . groundSpeed(calculAngle2($newAerodrome,$previousAerodrome),10*$i, 100) . " <br /> ";
    }
    echo "<hr>NORD SUD<br/>";
    $newAerodrome = new Aerodrome("LFAA",45.0,5.0, 1);
    $previousAerodrome= new Aerodrome("LFBA",65.0,5.0, 1);
    for($i=0;$i<36;$i++)
    {
        echo $i . "° >> " . groundSpeed(calculAngle2($newAerodrome,$previousAerodrome),10*$i, 100) . " <br /> ";
    }
}

function test_me()
{
    echo '<pre>';
    echo "Create/recreate Wind Infos... ";
    createWindInfos(time());

    $idFl = "F-TEST";
    echo "Delete Nav... ";
    deleteNav($idFl);

    echo "Creating Trajectories () ... ";
    generate_smart_trajectory($idFl, "LFGA");

    echo "Done !";
    echo '</pre>';
}

function test_compute_dist()
{
	echo "Distances pour notre trajet exemple = ";
	$listAerodrome=getAerodromes( connection() );
	$ae_n = array("LFBO", "LFER", "LFGC", "LFTH", "LFBZ", "LFAB", "LFLB", "LFBK", "LFKB", "LFKC", "LFKF");
	
	$ae_l = array();
	for($i=0;$i<11;$i++)
		$ae_l[$i] = $listAerodrome[searchC($listAerodrome,$ae_n[$i])];

	for($i=1;$i<11;$i++)
	{
		$a = $ae_l[$i-1];
		$b = $ae_l[$i];
		$c = dist( $a, $b ); 
		echo "dist ".$ae_n[$i-1]." to ".$ae_n[$i]." = ". $c ."<br/>\n";
	}
}


/* Programme principal */

if(isset($_POST["timeToGenerateWind"])) {
    // Est-ce le cas "Générer du vent" ?
    createWindInfos(strtotime($_POST["timeToGenerateWind"]), $_POST["nbMetar"]);
    header( "refresh:5;url=/beweb2017/php/pageadmin.php" );
    echo "Les données de vent ont été générées... Vous serez redirigé dans 5 secondes vers la page d'accueil. En cas de problème, <a href='/beweb2017/php/pageadmin.php'> cliquez ici.</a>";

} else if(isset($_GET["removeFlight"])) {
    // Est-ce le cas "Supprimer un vol" ?
    removeFlight($_GET["removeFlight"]);
    header( "refresh:5;url=/beweb2017/php/scores.php" );
    echo "Le vol ".$_GET["removeFlight"]." et ses données de navigations ont été supprimées... Vous serez redirigé dans 5 secondes vers la page précédente. En cas de problème, <a href='/beweb2017/php/scores.php'> cliquez ici.</a>";

} else if(isset($_POST["idFlight"])) {
    // Est-ce le cas "Ajouter Un Vol" ?
    if(strlen($_POST["idFlight"]) == 0)
        echo "Impossible d omettre le Flight Id !";
    else
    {
        session_start(); // Pour récup le login
        // echo $_POST["idFlight"]. $_POST["firstAerodrome"]. $_POST["teamName"]. $_POST["aircraftNumber"]. $_SESSION["login"];
        do {
            // On ajoute toujours au minimum un vol !

            if($_POST["repeatCount"] > 1)
                $idF = $_POST["idFlight"] . $_POST["repeatCount"] ;
            else
                $idF = $_POST["idFlight"] ;

            $useWind = (isset($_POST["useWind"]) && ($_POST["useWind"] == "on") );

            echo generate_smart_trajectory($idF, $_POST["firstAerodrome"], $_POST["teamName"], $_POST["aircraftNumber"], $_SESSION["login"], $useWind, strtotime($_POST["startTime"]));

            $_POST["repeatCount"]--;
        } while($_POST["repeatCount"] > 0);
    }
} else if(isset($_GET["debug"])) {
    // direct call => debug mode
	test_compute_dist();
	echo " hash admin = " . hashmypassword("admin") . " and hash tired = ".hashmypassword("71r3d") . "\n";
	return NULL; 
    test_me();
} else {
    // Ne rien faire : on est inclu par/dans un autre script !!
}

?>
