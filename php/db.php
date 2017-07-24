<?php

DEFINE("SGBD_SERVER","localhost");
DEFINE("LOGIN","root");
DEFINE("PASSWORD","mysql");
DEFINE("DATABASE_NAME","beweb_2017");

/* connexion: FR, connection: EN */

function connection($donotusedb = false)
{
	try
	{
		if($donotusedb)
			$connStr="mysql:host=".SGBD_SERVER;
		else
			$connStr="mysql:host=".SGBD_SERVER.";dbname=".DATABASE_NAME;

		$dbh=new PDO($connStr, LOGIN, PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e)
	{
		// Avant d'abandonner, essayer de se connecter SANS mot de passe/avec un mot de passe vide '
		try
		{
			$dbh=new PDO($connStr, LOGIN, "");
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			echo 'Connection failed to the SGBD: '.$e->getMessage();
			return "fail";
		}
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

function hashmypassword($passwd)
{
	return crypt($passwd, 42 /*sel*/);
}

function testAuth($login='', $passwd=''){
	$dbh=connection();
	$sql ="SELECT login, isAdmin FROM `USER` WHERE login = :login AND passwdHash = :passwd limit 1";
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

function addUser($login='', $passwd='',  $isAdmin = 0)
{
	$sql ="INSERT INTO USER VALUES ('".$login."','".$passwd."',".$isAdmin.")";
	submit_sql_to_sgbd($sql);

}

function getUser($login='')
{
	$dbh=connection();
	$sql ="SELECT login FROM `USER` WHERE login = :login";
	$sth=$dbh->prepare($sql);
	$sth->bindParam(":login", $login, PDO::PARAM_STR);
	$sth->execute();
	if($result=$sth->fetch(PDO::FETCH_OBJ))
	{
		$etat=1;
	}
	else
	{
		$etat=0;
	}
	$sth->closeCursor();
	return $etat;
}    

