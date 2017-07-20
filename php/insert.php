<?php

require_once("bdd.php");
require_once("template.php");


$login=$_POST['login'];
$passwd=$_POST['passwd'];


$membres=addUser($login, $passwd);
echo $login;
?>