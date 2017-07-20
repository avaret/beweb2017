<?php

require_once('php/template.php');

$html=entete('Accueil');
$html.=navbar();
$html.=filler();
$html.=footer();

echo $html;
?>