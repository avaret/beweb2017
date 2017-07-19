<?php
function entete($title1){
$entete1='
<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"><title>'.$title1.'</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/beweb2017/css/w3.css">
<link rel="stylesheet" href="/beweb2017/css/css_002.css">
<link rel="stylesheet" href="/beweb2017/css/css.css">
<link rel="stylesheet" href="/beweb2017/css/font-awesome.css">
<link rel="stylesheet" href="/beweb2017/css/style.css">
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
.fa-anchor,.fa-coffee {font-size:200px}
</style>
</head><body> 
';
return $entete1;
}

function mapp(){
$mapp1='
    <div id="map"></div>
    <div id="info-box"></div>
    <script src="/beweb2017/js/map.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5dLzzgVQKLa6Pm1jqiRCfVISkH_J3GeI&callback=initMap" async defer></script>
';
    return $mapp1;
}
function entetemap(){
    $entetemap='
<!DOCTYPE html>
    <html><head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"><title>Course</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/beweb2017/css/w3.css">
    <link rel="stylesheet" href="/beweb2017/css/css_002.css">
    <link rel="stylesheet" href="/beweb2017/css/css.css">
    <link rel="stylesheet" href="/beweb2017/css/font-awesome.css">
    <link rel="stylesheet" href="/beweb2017/css/style.css">
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
.fa-anchor,.fa-coffee {font-size:200px}
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
            bottom: 30px;
            height: 20px;
            padding: 10px;
            position: absolute;
            left: 30px;
        }

    </style>
</head><body>
    ';
    return $entetemap;
}

function navbar(){
$navbar1='  ';
        
    if(isset($_GET["logout"]))
    {
        echo '<div class="echec">Merci de votre visite</div>';
    }
    if(isset($_SESSION["login"]))
    {
        echo '<div>Bonjour ".$_SESSION["login"]." <div><a href="index.php?logout">Se déconnecter</a></div></div>';
    }
    else
    {
        
        echo   '
<div class="w3-top">
  <div class="w3-bar w3-blue w3-card-2 w3-large">
    <div class="w3-left-align">
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-blue" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="http://localhost/beweb2017/index.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Accueil</a>
    <a href="http://localhost/beweb2017/php/regles.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Les règles</a>
    <a href="http://localhost/beweb2017/php/map.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Map</a>
    <a href="http://localhost/beweb2017/php/resultats.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Résultats</a>
    <a href="http://localhost/beweb2017/php/contacts.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Contacts</a>
    </div>
    <div class="w3-right-align">
    <form id="auth" method="POST" action="/beweb2017/php/receptlog.php">
    <div id="navthing">
      <h2><a href="#" id="loginform">Login</a> | <a href="http://localhost/beweb2017/php/register.php">Register</a></h2>
    <div class="login" style="float:right;">
      <div class="arrow-up"></div>
      <div class="formholder">
        <div class="randompad">
        <fieldset>
             <label name="login">Login</label>
             <input type="login" />
             <label name="password">Password</label>
             <input type="password" />
             <input type="submit" value="Login" /> 
        </fieldset>          
        </div>
    </div>
    </div>
    </div>
    </form>
    </div>
   
  </div>

  <!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">
    <a href="http://localhost/beweb2017/index.php" class="w3-bar-item w3-button w3-padding-large">Accueil</a>
    <a href="http://localhost/beweb2017/php/regles.php" class="w3-bar-item w3-button w3-padding-large">Les règles</a>
    <a href="http://localhost/beweb2017/php/map.php" class="w3-bar-item w3-button w3-padding-large">Map</a>
    <a href="http://localhost/beweb2017/php/resultats.php" class="w3-bar-item w3-button w3-padding-large">Résultats</a>
    <a href="http://localhost/beweb2017/php/contacts.php" class="w3-bar-item w3-button w3-padding-large">Contacts</a>
  </div>
</div>
     ';
    }
    if(isset($_GET["err"]))
        {echo '<div class="echec">Echec d\'authentification</div>';}
    if(isset($_GET["logout"]))
        {
        session_unset();
        session_destroy();
        };'
';
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

 <script src="/beweb2017/js/jquery.min.js"></script>

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
<div id="remplissage">La Coupe Breitling 100/24</div>
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

function registrform(){
    
    $rgstr='
    <!DOCTYPE html>
    <html><head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"><title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/beweb2017/css/w3.css">
    <link rel="stylesheet" href="/beweb2017/css/css_002.css">
    <link rel="stylesheet" href="/beweb2017/css/css.css">
    <link rel="stylesheet" href="/beweb2017/css/font-awesome.css">
    <link rel="stylesheet" href="/beweb2017/css/registr.css">
    <style>
    body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
    .w3-bar,h1,button {font-family: "Montserrat", sans-serif}
    .fa-anchor,.fa-coffee {font-size:200px}
    </style>
    </head><body> 
    
    <div class="wutwut">
  <div class="w3-bar w3-blue w3-card-2 w3-large">
    <div class="w3-left-align">
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-blue" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="http://localhost/beweb2017/index.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Accueil</a>
    <a href="http://localhost/beweb2017/php/regles.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Les règles</a>
    <a href="http://localhost/beweb2017/php/map.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Map</a>
    <a href="http://localhost/beweb2017/php/resultats.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Résultats</a>
    <a href="http://localhost/beweb2017/php/contacts.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Contacts</a>
    </div>
    <div class="w3-right-align">
    <div id="navthing">
      <h2><a href="#" id="loginform">Login</a></h2>
    <div class="login" style="float:right;">
      <div class="arrow-up"></div>
      <div class="formholder">
        <div class="randompad">
           <fieldset>
             <label name="email">Email</label>
             <input type="email" value="example@example.com" />
             <label name="password">Password</label>
             <input type="password" />
             <input type="submit" value="Login" />
 
           </fieldset>
        </div>
      </div>
    </div>
    </div>
    </div>
  </div>

  <!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">
    <a href="http://localhost/beweb2017/index.php" class="w3-bar-item w3-button w3-padding-large">Accueil</a>
    <a href="http://localhost/beweb2017/php/regles.php" class="w3-bar-item w3-button w3-padding-large">Les règles</a>
    <a href="http://localhost/beweb2017/php/map.php" class="w3-bar-item w3-button w3-padding-large">Map</a>
    <a href="http://localhost/beweb2017/php/resultats.php" class="w3-bar-item w3-button w3-padding-large">Résultats</a>
    <a href="http://localhost/beweb2017/php/contacts.php" class="w3-bar-item w3-button w3-padding-large">Contacts</a>
  </div>
</div>
    
    <div class="user">
    <header class="user__header">
        <img src="/beweb2017/image/avion.ico" alt="icone" width="25%"/>
        <h1 class="user__title">S\'inscrire en trois clics</h1>
    </header>
    
    <form class="form">
        <div class="form__group">
            <input type="text" placeholder="login" class="form__input" />
        </div>
    
        
        <div class="form__group">
            <input type="password" placeholder="Password" class="form__input" />
        </div>
        
        <button class="btn" type="button">Register</button>
    </form>
</div>
    ';
return $rgstr;
}
?>
