<?php

DEFINE("SERV","localhost");
DEFINE("LOGIN","root");
DEFINE("MDP","");
DEFINE("NOM_BD","beweb_2017");

/* connexion: FR, connection: EN */

function connexion()
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

function testAuth($login='', $passwd=''){
    $dbh=connexion();
    $sql ="SELECT login, isAdmin FROM user WHERE login = :login AND passwd = :passwd limit 1";
    $sth=$dbh->prepare($sql);
    $sth->bindParam(":login", $login, PDO::PARAM_STR);
    $sth->bindParam(":passwd", $passwd, PDO::PARAM_STR);
    $sth->execute();
    if($result=$sth->fetch(PDO::FETCH_OBJ))
    {
        $etat=1;
        $_SESSION['login']=$result->login;
        $_SESSION['admin']=$result->isAdmin;
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

function addUser($login, $passwd,  $isAdmin = 0)
{
    submit_sql_to_sgbd("INSERT INTO USER VALUES ('".$login."','".$passwd."',".$isAdmin.")");
}