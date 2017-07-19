<?php
    session_start();
    require_once("bdd.php");
    require_once("receptlog.php");

    $login=$_POST['login'];
    $passwd=$_POST['passwd'];
echo 'le login est' $login 'le password est' $passwd;
    $etat=testAuth($login, $passwd);
    if ($etat==1) {header("location:index.php");}
    else {header("location:index.php?err");}
?>