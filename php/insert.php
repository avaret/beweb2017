<?php

require_once("bdd.php");
require_once("template.php");

$login=$_POST['login'];
$passwd=$_POST['passwd'];
$isAdmin=$_POST['isAdmin'];

$membres=addUser($login, $passwd, $isAdmin);
?>