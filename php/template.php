<?php

session_start();


function entete($title1, $forMap = false, $forreg = false)
{
	$entete1='
		<!DOCTYPE html>
		<html><head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"><title>'.$title1.'</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="/beweb2017/css/w3.css">
		<link rel="stylesheet" href="/beweb2017/library/font-Lato.css">
		<link rel="stylesheet" href="/beweb2017/library/font-Montserrat.css">
		<link rel="stylesheet" href="/beweb2017/library/font-awesome.css">
		<link rel="stylesheet" href="/beweb2017/css/style.css">';
    if($forreg){
        $entete1.=' 
        <link rel="stylesheet" href="/beweb2017/css/registr.css">';
    }
        $entete1.='
		<link rel="stylesheet" href="/beweb2017/library/bootstrap.min.css">
        <link rel="stylesheet" href="/beweb2017/library/bootstrap-table.min.css">
		<style>
		body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
		.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
		.fa-anchor,.fa-coffee {font-size:200px}';
	
	if($forMap) {
	$entete1.='
	        #map {
	            height: 100%;
	        }
	        /* Optional: Makes the sample page fill the window. */
	        
	        html,
	        body {
	            height: 100%;
	            margin: 0;
	            padding: 0;
	        }
	       #info-box {
            background-color: white;
            border: 1px solid black;
            bottom: 20px;
            height: 35px;
            padding: 10px;
            position: absolute;
            left: 30px;
            }
            
            #butt {
            bottom: 20px;
            height: 30px;
            position: absolute;
            left: 120px;
            }';
	}
	
	$entete1.='</style></head><body> ';
	return $entete1;
}

function mapp(){
$mapp1='
    <div id="map"></div>
    <div id="info-box"></div>
    <div id="butt"> <form ><button onclick="">Click me</button></form></div>  
    <script src="/beweb2017/js/map.php"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5dLzzgVQKLa6Pm1jqiRCfVISkH_J3GeI&libraries=geometry&callback=initMap" async defer></script>
';
    return $mapp1;
}
function entetemap(){
    return entete("Course", true);
}


function navbar($isRegistering = false){
    $navbar1=" \n\n <!--- NAVBAR  --> \n";
        
    if(isset($_GET["err"]))
        {$navbar1 .= '<script>window.alert("Echec d\'authentification")</script>';}

    if(isset($_GET["logout"]))
    {
        $navbar1 .= '<script>window.alert("Merci de votre visite!")</script>';
        session_unset();
        session_destroy();
    }
    
        $navbar1 .=   '
<div class="w3-top">
  <div class="w3-bar w3-blue w3-card-2 w3-large">
    <span class="w3-left-align">
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-blue" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="http://localhost/beweb2017/index.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Accueil</a>
    <a href="http://localhost/beweb2017/php/regles.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Les règles</a>
    <a href="http://localhost/beweb2017/php/map.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Map</a>
    <a href="http://localhost/beweb2017/php/resultats.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Résultats</a>
    <a href="http://localhost/beweb2017/php/contacts.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Contacts</a>';
    if(isset($_SESSION["isAdmin"]))
    {
        if($_SESSION["isAdmin"]==1)
        {
            $navbar1 .= '<a href="http://localhost/beweb2017/php/pageadmin.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">PageAdmin</a>  ';
        }
    }
    $navbar1 .= '
    </span>
    <span class="w3-right-align" style="float:right">
    
    <div id="navthing">';
    
    if(isset($_SESSION["login"]))
    {
        $navbar1 .= '    '.$_SESSION["login"].' <a href="/beweb2017/index.php?logout">Se déconnecter</a>     ';
    }
    else
    {
        $navbar1 .= '
            <a href="#" id="loginform" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Login</a>';

        if(!$isRegistering) 
        {	
            $navbar1 .= '  <a href="http://localhost/beweb2017/php/register.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Register</a>     ';
        }

       
    }
    
    $navbar1 .= '
    </div>
    </span>
   
  </div>

  <!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">
    <a href="http://localhost/beweb2017/index.php" class="w3-bar-item w3-button w3-padding-large">Accueil</a>
    <a href="http://localhost/beweb2017/php/regles.php" class="w3-bar-item w3-button w3-padding-large">Les règles</a>
    <a href="http://localhost/beweb2017/php/map.php" class="w3-bar-item w3-button w3-padding-large">Map</a>
    <a href="http://localhost/beweb2017/php/resultats.php" class="w3-bar-item w3-button w3-padding-large">Résultats</a>
    <a href="http://localhost/beweb2017/php/contacts.php" class="w3-bar-item w3-button w3-padding-large">Contacts</a>
    <a href="#" id="loginform" class="w3-bar-item w3-button w3-padding-large">Login</a>
     ';
    if(!$isRegistering) 
        {
        $navbar1 .= '<a href="http://localhost/beweb2017/php/register.php" class="w3-bar-item w3-button w3-padding-large">Register</a>';
        }
        $navbar1 .= '
  </div>
</div>

    
              <div class="login" style="float:right;">
              <div class="arrow-up"></div>
              <div class="formholder">
              <div class="randompad">
              <form id="auth" method="POST" action="/beweb2017/php/receptlog.php">
                <fieldset>
                    <label name="login">Login</label>
                    <input type="login" name="login" />
                    <label name="password">Password</label>
                    <input type="password" name="passwd" />
                    <input type="submit" value="Login" /> 
                </fieldset>          
                </form>
              </div>
              </div>
              </div>
        ';
    $navbar1.=" \n\n <!--- END OF NAVBAR  --> \n";
    return $navbar1;
}
    
function footer(){
$footer1='
<footer class="w3-container w3-padding-64 w3-center w3-opacity">  
 <p>Powered by <a href="www.enac.fr/" target="_blank">enac</a></p>
</footer>

<script>
// Used to toggle the menu on small screens when clicking on the menu button
function myFunction() {
    var x = document.getElementById("navDemo");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace(" w3-show", "");
    }
}

</script>

<script src="/beweb2017/library/jquery.min.js"></script>
<script src="/beweb2017/library/bootstrap.min.js"></script>
<script src="/beweb2017/library/bootstrap-table.min.js"></script>

    <script src="/beweb2017/js/index.js"></script>


</body></html>
';
return $footer1;
}


function contacto($nom, $prenom, $mail, $photo){
$contc='
<div id="remplissage">
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-twothird">
      <h1>'.$nom.'</h1>
      
      <h2 class="w3-padding-32">'.$prenom.'</h2>

      <p class="w3-text-grey w3-center">'.$mail.'</p>
    </div>

    <div class="w3-third w3-right">
      <img src='.$photo.' alt="photo" title="photoid" height="300"/>
    </div>
  </div>
</div>
<div></div>
</div>
';
return $contc;
}

function filler(){
$fill='
<div id="remplissage"><h2>La Coupe Breitling 100/24</h2></div>
<div id="remplissage">

L’objectif  du Défi 100/24 est d’effectuer en moins de 24 heures 100 posés-décollés sur 100 aérodromes différents répartis sur l’Hexagone, tout en respectant des contraintes géographiques.

Les organisateurs souhaitent mettre en valeur le maillage aéroportuaire français, mais aussi démontrer la parfaite collaboration et la solidarité existant entre tous les acteurs du monde aéronautique, civils ou militaires. L’Armée de l’Air et la Direction Générale de l’Aviation Civile sont deux partenaires indispensables à la bonne organisation de 100/24.

L’équipage gagnante sera celle qui aura parcouru la distance la plus faible, et éventuellement, en cas d’égalité, celle qui aura consommé le moins de carburant.

Ce défi est avant tout une aventure humaine, celle de jeunes pilotes professionnels, tous passionnés par les multiples facettes de l’aviation, 

Relever ce défi implique une organisation solide, basée sur un tracé optimisé au maximum, en tenant compte des limitations imposées aux équipages, de la météo, des espaces aériens, de la nuit, mais aussi des imprévus.

</div>
';
return $fill;
}

function resultat(){
    
    $resultt='
    <div id="remplissage">Les résultats</div>
    <div id="remplissage">
    <table style="width:100%">
  <tr>
    <th>Nom</th>
    <th>Prenom</th>
    <th>Age</th>
  </tr>
  <tr>
    <td>Jill</td>
    <td>Smith</td>
    <td>50</td>
  </tr>
  <tr>
    <td>Eve</td>
    <td>Jackson</td>
    <td>94</td>
  </tr>
</table> 
</div>
    ';
return $resultt;
}


?>
