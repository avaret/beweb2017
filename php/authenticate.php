<?php

require_once("db.php");
require_once("calcul.php");

session_start();
$login=$_POST['login'];
$passwd=$_POST['passwd'];
$login = sanitize($login, 'sql');
$passwd = sanitize($passwd, 'sql');

$hashedpassword = hashmypassword($passwd);

$etat=testAuth($login, $hashedpassword);
if ($etat==1) 
{
	header("location:/beweb2017/index.php");
} else {
	header("location:/beweb2017/index.php?err");
}

?>
