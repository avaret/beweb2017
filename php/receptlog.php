<?php
    session_start();
    require_once("bdd.php");
    $login=$_POST['login'];
    $passwd=$_POST['passwd'];
    $etat=testAuth($login, $passwd);
    if ($etat==1) {header("location:/beweb2017/index.php");}
    else {header("location:/beweb2017/index.php?err");}
    
?>