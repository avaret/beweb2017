<?php

DEFINE("SERV","localhost");
DEFINE("LOGIN","root");
DEFINE("MDP","");
DEFINE("NOM_BD","beweb_2017");

/* connexion: FR, connection: EN */

function connection()
{
    try
    {
        $connStr="mysql:host=".SERV.";dbname=".NOM_BD;
        $dbh=new PDO($connStr, LOGIN, MDP);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo 'Connection failed: '.$e->getMessage();
        return "fail";
    }
    return $dbh;
}

function submit_sql_to_sgbd($sql, $auto_close=true)
{
    $dbh=connexion();
    $sth=$dbh->prepare($sql);
    $sth->execute();
    if($auto_close)
    {
        $sth->closeCursor();
        $dbh = NULL;
    }
    else
        return $sth;
}

function testAuth($email='', $mdp=''){
    $dbh=connexion();
    $sql ="SELECT * FROM user WHERE login = :login AND passwd = :passwrd limit 1";
    $sth=$dbh->prepare($sql);
    $sth->bindParam(":login", $email, PDO::PARAM_STR);
    $sth->bindParam(":passwrd", $mdp, PDO::PARAM_STR);
    $sth->execute();
    if($result=$sth->fetch(PDO::FETCH_OBJ))
    {
        $etat=1;
        $_SESSION['login']=$row->login;
        $_SESSION['admin']=$row->isAdmin;
    }
    else
    {
        $etat=0;
        session_unset();
        session_destroy();
    }
    $sth->closeCursor();
return $etat;
}

