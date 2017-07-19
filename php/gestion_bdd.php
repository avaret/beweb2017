<?php

include_once 'bdd.php';
require_once 'bdd.php';

/* Fonction de (ré)initialisation de la base de données */
function createOrResetBdd()
{
//FIXME file_get_contents marche pas
	/* Récupération des commandes SQL */
	$filename = "/beweb2017/be_v3.sql";
	$sql = file_get_contents($filename); 

//FIXME ne peut se connecter à une base inexistante !
	/* Envoi à la bdd */
	submit_sql_to_sgbd($sql);
}

/* Fonction d'effacement des traces de la BD */
function dropBdd()
{
	/* Envoi à la bdd */
	submit_sql_to_sgbd('DROP DATABASE beweb_2017');
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

