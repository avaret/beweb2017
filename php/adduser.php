<?php

require_once("db.php");
require_once("template.php");


$login=$_POST['login'];
$passwd=$_POST['passwd'];

if(getUser($login)==1)
{
    echo 'Echec!<script>window.alert("Echec : le login est déjà pris")</script>';
}
else
{
    echo 'Succès!<script>window.alert("Bienvenue!")</script>';
    echo '<br> Vous pouvez vous logger.';
    $membres=addUser($login, $passwd);
}


?>