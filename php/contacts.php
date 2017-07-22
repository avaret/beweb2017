<?php
require_once('template.php');

$html=entete('Contactez-nous');
$html.=navbar();
$html.=contacto('VARET', 'Antoine', 'a@fr', '/beweb2017/image/bob.jpg' );
$html.=contacto('SCHMITT', 'Matthieu', 'm@fr', '/beweb2017/image/ms.png' );
$html.=contacto('ROLLAND', 'Mathieu', 'mathieu.rolland.isesa@gmail.com', '/beweb2017/image/bob.jpg' );
$html.=footer();

echo $html;
?>