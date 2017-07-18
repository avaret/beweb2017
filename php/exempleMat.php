<?php

include_once 'bdd.php';
require_once 'bdd.php';

	$html = "<html><body><H2>LISTE DES MESSAGES</H2> ";
	$sql = "SELECT nom, prenom, message FROM contact;";
	$sbd = connection();
	$sbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	$result = p_submit_sql($sbd,$sql);

	$html .= "<table><tr><th>Nom</th><th>email</th><th>Message</th></tr>";

	while( $r = $result->fetch(PDO::FETCH_OBJ) ) 
	{
		$html .= "<tr><td>" . $r->nom . "</td><td>" . $r->prenom."</td><td>".$r->message . "</td></tr>";
	}

	$html .= "</table></body></html>";
	echo $html;
?>

