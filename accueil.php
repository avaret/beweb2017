<?php
require_once('template.php');

$html=entete('accueil');
$html.=navbar();
$html.=filler();
$html.=footer();

echo $html;
?>