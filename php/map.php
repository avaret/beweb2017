<?php

require_once('template.php');

function entetemap(){
    return entete("Visualisation de la carte de la course", true);
}

function mapp(){
    return '
    <div id="map"></div>
    <div id="info-box"></div>
    <script>var stop =0;</script>
    <div id="butt"> <button id="myButt" onclick="stop = !stop;">Stop</button></div>  
    <div id="selectFlightId"> <select id="idFlt" onclick="stop = !stop;">

    <OPTION VALUE="van" SELECTED>Vanilla
    <OPTION VALUE="str">Strawberry
    <OPTION VALUE="rr">Rum and Raisin
    <OPTION VALUE="po">Peach and Orange

    </select></div>  
   
    <script src="/beweb2017/js/map.php"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5dLzzgVQKLa6Pm1jqiRCfVISkH_J3GeI&libraries=geometry&callback=initMap" async defer></script>';
}

$html=entetemap();
$html.=navbar();
$html.=mapp();
$html.=footer();

echo $html;
?>
