<?php

require_once("db.php");
require_once("template.php");
require_once("calcul.php");


$login=$_POST['login'];
$passwd=$_POST['passwd'];

$login = sanitize($login, 'sql');
$passwd = sanitize($passwd, 'sql');

$hashedpassword = hashmypassword($passwd);

if(getUser($login)==1)
{
	echo 'Echec!<script>window.alert("Echec : le login est déjà pris")</script>';
}
else
{
	echo 'Succès!<script>window.alert("Bienvenue ! Merci de vous connecter désormais en utilisant le menu en haut à droite pour confirmer le mot de passe.")</script>';
	echo '<br> Vous pouvez vous logger en haut à droite pour terminer l\'insertion.';
	$membres=addUser($login, $hashedpassword);
}


?>
