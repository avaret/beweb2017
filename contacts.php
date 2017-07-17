<?php
require_once('template.php');

$html=entete('Contactez-nous');
$html.=navbar();
$html.=contacto('VAR', 'ant', 'a@fr', 'image/bob.jpg' );
$html.=contacto('SCH', 'mat', 'm@fr', 'image/bob.jpg' );
$html.=contacto('ROL', 'mat', 'm@fr', 'image/bob.jpg' );
$html.=footer();

echo $html;
?>