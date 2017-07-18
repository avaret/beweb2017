<?php

DEFINE("SERV","locahost");
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

