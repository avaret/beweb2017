<?php
require_once('template.php');

$html=entete('Contactez-nous');
$html.=navbar();

$html.=contacto('VARET', 'Antoine', 'avaret@gmail.com', '/beweb2017/image/AntoineVaret.jpg' );
$html.=contacto('SCHMITT', 'Matthieu', 'm@fr', '/beweb2017/image/ms.png' );
$html.=contacto('ROLLAND', 'Mathieu', 'mathieu.rolland.isesa@gmail.com', '/beweb2017/image/bob.jpg' );


/*
Originaire du Loir-et-Cher, j’ai commencé en 2005 à Toulouse mes études d'ingénieur à l'Institut National des
	Sciences Appliquées (INSA) puis j’ai continué en thèse sur un projet de recherche à l'Ecole Nationale de
	l'Aviation Civile (ENAC), doctorat soutenu le 1er octobre 2013 sur le sujet de la conception d'un routeur
	embarqué pour l'avionique. Après quelques années passées à travailler dans une entreprise privée sur des
	systèmes logiciels et matériels de validation des systèmes de communication Datalink, j’ai intégré la fonction
	publique dans le corps des Ingénieurs Électroniciens des Systèmes de la Sécurité Aérienne (IESSA) de la
	Direction Générale de l’Aviation Civile (DGAC) en septembre 2016.
*/


$html.=footer();

echo $html;
?>
