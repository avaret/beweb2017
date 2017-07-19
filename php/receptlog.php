<?php
    session_start();
    require_once("/beweb2017/php/bdd.php");

    $email=$_POST['login'];
    $mdp=$_POST['passwd'];
    $etat=testAuth($email, $mdp);
    if ($etat==1) {header("location:index.php");}
    else {header("location:index.php?err");}
?>