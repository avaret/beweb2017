<?php
require_once('template.php');

$html=entete('Résultats');
$html.=navbar();
$html.=resultat();
$html.=footer();

echo $html;
?>