<?php
require_once('template.php');

$html=entetemap();
$html.=navbar();
$html.=mapp();
$html.=footer();

echo $html;
?>