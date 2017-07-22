<?php

session_start();


function entete($title1, $forMap = false, $forreg = false)
{

	$entete1='
		<!DOCTYPE html>
		<html lang="fr"><head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"><title>'.$title1.'</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="icon" href="/favicon.ico" />
                <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->
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
	    }

	    #selectFlightId {
	    bottom: 80px;
	    height: 30px;
	    position: absolute;
	    left: 120px;
	    }';
	}

	$entete1.='</style></head><body> ';
	return $entete1;
}

function navbar($isRegistering = false){
	$pages = array( 
		"Accueil" => 'index.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-border-right"><img src="/beweb2017/image/avion.ico"',
		"Les règles" => 'php/regles.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-border-right"><img src="/beweb2017/image/sifflet.png"',
		"Ajouter un vol" => 'php/ajouter_vol.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-border-right"><img src="/beweb2017/image/drapeau.png"',
		"Map" => 'php/map.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-border-right"><img src="/beweb2017/image/map.png"',
		"Scores" => 'php/scores.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-border-right"><img src="/beweb2017/image/podium.png"',
	);

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
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-blue" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>';

	foreach( $pages as $titre => $chemin)
	{
		$navbar1 .= "\n".'    <a href="http://localhost/beweb2017/' . $chemin . ' width="20" alt="icone">' . $titre . '</a>';
	}

/*    <a href="http://localhost/beweb2017/index.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white w3-border-right">Accueil</a>
    <a href="http://localhost/beweb2017/php/regles.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white w3-border-right">Les règles</a>
    <a href="http://localhost/beweb2017/php/ajouter_vol.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white w3-border-right">Ajouter un vol</a>
    <a href="http://localhost/beweb2017/php/map.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white w3-border-right">Map</a>
<a href="http://localhost/beweb2017/php/scores.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white w3-border-right">Scores</a>';*/

	if(isset($_SESSION["admin"]))
	{
		if($_SESSION["admin"]==1)
		{
			$navbar1 .= '<a href="http://localhost/beweb2017/php/pageadmin.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-border-right"><img src="/beweb2017/image/admin.png" width="20" alt="icone">Administration</a>  ';
		}
	}
	$navbar1 .= '
    <a href="http://localhost/beweb2017/php/contacts.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white"><img src="/beweb2017/image/phone.png" width="20" alt="icone">Contacts</a>
    </span>
    <span class="w3-right-align" style="float:right">

    <div id="navthing">';

	if(isset($_SESSION["login"]))
	{
		$navbar1 .= ''.$_SESSION["login"].'   <a href="/beweb2017/index.php?logout" class="w3-bar-item w3-button w3-hide-small  w3-hover-white">Se déconnecter</a>     ';
	}
	else
	{
		$navbar1 .= '
	    <a href="#" id="loginform" class="w3-bar-item w3-button w3-hide-small w3-hover-white">Login</a>';

	if(!$isRegistering) 
	{	
	    $navbar1 .= '  <a href="http://localhost/beweb2017/php/register.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-border-left">Register</a>     ';
	}


    }

    $navbar1 .= '
    </div>
    </span>

  </div>

  <!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">';

    foreach( $pages as $titre => $chemin )
    {
	    $navbar1 .= "\n   ".'<a href="http://localhost/beweb2017/' .$chemin . '" class="w3-bar-item w3-button w3-padding-large">' . $titre . '</a>';
    }

/*    $navbar1 .= '
    <a href="http://localhost/beweb2017/index.php" class="w3-bar-item w3-button w3-padding-large">Accueil</a>
    <a href="http://localhost/beweb2017/php/regles.php" class="w3-bar-item w3-button w3-padding-large">Les règles</a>
    <a href="http://localhost/beweb2017/php/ajouter_vol.php" class="w3-bar-item w3-button w3-padding-large">Ajouter un vol</a>
    <a href="http://localhost/beweb2017/php/map.php" class="w3-bar-item w3-button w3-padding-large">Map</a>
    <a href="http://localhost/beweb2017/php/scores.php" class="w3-bar-item w3-button w3-padding-large">Scores</a>
';*/

    if(isset($_SESSION["admin"]))
    {
	    if($_SESSION["admin"]==1)
	    {
		    $navbar1 .= '<a href="http://localhost/beweb2017/php/pageadmin.php" class="w3-bar-item w3-button w3-padding-large">PageAdmin</a>  ';
	    }
    }
    $navbar1 .= '
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
 <p>Un projet pourl\'<a href="http://www.enac.fr/" target="_blank"><u>Enac</u> </a>|<a href="http://localhost/beweb2017/php/contacts.php"target="_blank"><u> nous contacter</u></p>
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
<div class="remplissage">
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-twothird">
      <h1>'.$nom.'</h1>
      <br>
      <h2 class="w3-padding-32">'.$prenom.'</h2>
      <br><br><br>
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

?>
