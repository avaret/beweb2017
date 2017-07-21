<?php

include_once 'bdd.php';
require_once 'bdd.php';

/* Fonction de (ré)initialisation de la base de données */
function createOrResetBdd()
{
	/* Récupération des commandes SQL */
	$filename = "/var/www/html/beweb2017/be_v4.sql";
	$sql = file_get_contents($filename); 

	/* Envoi à la bdd */
	$db = connection(false);
	$db->query( $sql )->closeCursor(); // TODO Ne fonctionne pas !
	$db = NULL;
}

/* Fonction d'effacement des traces de la BD */
function dropBdd()
{
	/* Envoi à la bdd */
	submit_sql_to_sgbd('DROP DATABASE `beweb_2017`');
}

if(isset($_GET["do"]) && ($_GET["do"] == "reset") )
{
	createOrResetBdd();
	echo time() . ": The Database has been recreated.";
} else {
	dropBdd();
	echo time() . ": The Database has been DROP.";
}

?>

