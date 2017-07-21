<?php

require_once('template.php');

function entetemap(){
    return entete("Visualisation de la carte de la course", true);
}

function mapp(){
$mapp1='
    <div id="map"></div>
    <div id="info-box"></div>
    <script>var stop =0;</script>
    <div id="butt"> <button id="myButt" onclick="stop = !stop;">Stop</button></div>  
    <div id="selectFlightId"> <button id="myButt2" onclick="stop = !stop;">Stop2</button></div>  
   
    <script src="/beweb2017/js/map.php"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5dLzzgVQKLa6Pm1jqiRCfVISkH_J3GeI&libraries=geometry&callback=initMap" async defer></script>
';
    return $mapp1;
}

$html=entetemap();
$html.=navbar();
$html.=mapp();
$html.=footer();

echo $html;
?>
