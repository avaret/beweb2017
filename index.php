<?php

require_once('php/template.php');

$html=entete('Accueil');
$html.=navbar();
echo $html;
?>

<div class="remplissage"  style="background-color: cyan;"><h1>La Coupe Breitling 100/24</h1></div>
<div class="remplissage">

<p>L’objectif  du Défi 100/24 est d’effectuer en moins de 24 heures 100 posés-décollés sur 100 aérodromes différents répartis sur l’Hexagone, tout en respectant des contraintes géographiques.

Les organisateurs souhaitent mettre en valeur le maillage aéroportuaire français, mais aussi démontrer la parfaite collaboration et la solidarité existant entre tous les acteurs du monde aéronautique, civils ou militaires. L’Armée de l’Air et la Direction Générale de l’Aviation Civile sont deux partenaires indispensables à la bonne organisation de la coupe 100/24.</p>
<div class="photo"><img src="image/cb2.jpg" alt="photo" title="photo de TB20" width="90%"/></div>
<p>L’équipage gagnante sera celle qui aura parcouru la distance la plus faible, et éventuellement, en cas d’égalité, celle qui aura consommé le moins de carburant.</p>

<p>Ce défi est avant tout une aventure humaine, celle de jeunes pilotes professionnels, tous passionnés par les multiples facettes de l’aviation, 

Le relever implique une organisation solide, basée sur un tracé optimisé au maximum, en tenant compte des limitations imposées aux équipages, de la météo, des espaces aériens, de la nuit, mais aussi des imprévus.</p>

</div>
<?php

$html=footer();

echo $html;
?>