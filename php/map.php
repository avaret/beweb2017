<?php

require_once('template.php');
include_once 'db.php';
require_once 'db.php';

function entetemap(){
    return entete("Visualisation de la carte de la course", true);
}

function contentmap(){
    $result = '
    <div id="map"></div>
    <div id="info-box"></div>
    <script>var stop =0;</script>
    <div id="butt"> 
	<button id="myButt" onclick="stop = !stop; this.textContent = (stop ? \'Resume\' : \'Pause\' ) ;" >Pause</button>
	</div>  
    <div id="selectFlightId"> 
    <form id="selectFlightIdMap" method="GET" action="/beweb2017/php/map.php">
	<select name="idFlt" form="selectFlightIdMap" onchange="this.form.submit()">';

        //switch to correct database
	$dbh=connection();

	//session_start();		
	if(isset($_GET["idFlt"]))
	{
		$selected = $_GET["idFlt"];
	}
	else 
	{
		$selected = NULL;
	}
	$_SESSION["idFlt"] = $selected;

	$sql="SELECT DISTINCT idFlight FROM FLIGHT_PATH";
	$sth=$dbh->query($sql);
	$i = 0;
	while($res=$sth->fetch(PDO::FETCH_OBJ))
	{
	   	$result .= "<option ". ($selected == $res->idFlight ? "selected" : "") ." value=\"$res->idFlight\">$res->idFlight</option>";
		$i++;
	}

	if($i == 0)
	{
		// Bdd vide
	   	$result .= "<option value=\"\">(Vous devez ajouter un vol avant de pouvoir utiliser cette carte)</option>";
	}

    $result .= '</select></form></div>  
   
    <script src="/beweb2017/js/map.php"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5dLzzgVQKLa6Pm1jqiRCfVISkH_J3GeI&libraries=geometry&callback=initMap" async defer></script>';
    return $result;
}

$html=entetemap();
$html.=navbar();
$html.=contentmap();
$html.=footer();

echo $html;
?>
