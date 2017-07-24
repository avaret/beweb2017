<?php
require_once('template.php');

function contacto($nom, $prenom, $mail, $photo, $bio){
    $contc='
<div class="remplissage">
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-twothird">
      <h1>'.$nom.' '.$prenom.'</h1>
      <br>
      <p class="w3-padding-32" style="text-align:justify;">'.$bio.'</p>
      <br><br><br>
      <p class="w3-text-grey w3-center">'.$mail.'</p>
    </div>

    <div class="w3-third w3-right" style="padding-left:20px;">
      <img src='.$photo.' alt="photo" title="photoid" height="300"/>
    </div>
  </div>
</div>
<div></div>
</div>
';
    return $contc;
}


$html=entete('Contactez-nous');
$html.=navbar();

$html.=contacto('VARET', 'Antoine', 'avaret@gmail.com', '/beweb2017/images/AntoineVaret.jpg', 'Originaire du Loir-et-Cher, j’ai commencé en 2005 à Toulouse mes études d\'ingénieur à l\'Institut National des
Sciences Appliquées (INSA) puis j’ai continué en thèse sur un projet de recherche à l\'Ecole Nationale de
l\'Aviation Civile (ENAC), doctorat soutenu le 1er octobre 2013 sur le sujet de la conception d\'un routeur
embarqué pour l\'avionique. Après quelques années passées à travailler dans une entreprise privée sur des
systèmes logiciels et matériels de validation des systèmes de communication Datalink, j’ai intégré la fonction
publique dans le corps des Ingénieurs Électroniciens des Systèmes de la Sécurité Aérienne (IESSA) de la
Direction Générale de l’Aviation Civile (DGAC) en septembre 2016.' );
$html.=contacto('SCHMITT', 'Matthieu', 'matthieu.schmitt@aviation-civile.gouv.fr', '/beweb2017/images/ms.png', 'Après un baccalauréat S au lycée la Doctrine chrétienne à Strasbourg, j\'ai étudié un an au lycée technique Couffignal en PCSI. N\'étant pas très enthousiasmé par les sciences fondamentales, j\'ai passé le concours TSEEAC en 2010, et ai intégré la promotion TSEEAC 10A. Après ma formation, j\'ai été affecté au siège de la DGAC à Paris ou j\'ai travaillé pendant 4 ans à la Mission Environnement avant de passer le concours IESSA en interne l\'année dernière.');
$html.=contacto('ROLLAND', 'Mathieu', 'mathieu.rolland.isesa@gmail.com', '/beweb2017/images/mr.jpg', 'Originaire de Toulouse, j\'ai obtenu en 2015 ma licence de physique fondamentale à l\'université Paul Sabatier. J\'ai ensuite commencé un master de sciences météorologiques, mais me lassant de cours trop théoriques j\'ai changé d\'orientation pour une formation plus pratique : ISESA à l\'Enac, filière dans laquelle je me retrouve beaucoup plus.');

$html.=footer();

echo $html;
?>
