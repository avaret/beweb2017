<?php

include_once 'bdd.php';
require_once 'bdd.php';

/* Fonction de (ré)initialisation de la base de données */
function createOrResetBdd()
{
	/* Récupération des commandes SQL */
	$filename = "/beweb2017/be_v2.sql";
	$sql = file_get_contents($filename); 

	/* Envoi à la bdd */
	submit_sql_to_sgbd($sql);
}

/* Fonction d'effacement des traces de la BD */
function dropBdd()
{
	/* Envoi à la bdd */
	submit_sql_to_sgbd('DROP DATABASE beweb_2017');
}

?>

