<?php

DEFINE("SGBD_SERVER","localhost");
DEFINE("LOGIN","root");
DEFINE("PASSWORD","mysql");
DEFINE("DATABASE_NAME","beweb_2017");

/* connexion: FR, connection: EN */

function connection()
{
    try
    {
        $connStr="mysql:host=".SGBD_SERVER.";dbname=".DATABASE_NAME;
        $dbh=new PDO($connStr, LOGIN, PASSWORD);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo 'Connection failed to the SGBD: '.$e->getMessage();
        return "fail";
    }
    return $dbh;
}

function submit_sql_to_sgbd($sql, $auto_close=true)
{
    $dbh=connection();
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

