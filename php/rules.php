<?php

require_once('template.php');

$html=entete('Les règles');
$html.=navbar();
echo $html;
?>

<div class="remplissage titre1"><h1>Le règlement de la Coupe Breitling 100/24</h1></div>
<div class="remplissage titre2"><h2>règlement général :</h2></div>
<div class="remplissage">

    <p> La coupe Breitling est une compétition aérienne dans laquelle chaque équipage doit tenter d'effectuer, en parcourant une distance sol minimale, un posé-décollé sur 98 aérodromes français à code ICAO en 24 heures, suivant les règles de vol VFR, auxquels s'ajoutent le décollage de l'aérodrome de départ et l'atterrissage sur celui d'arrivée (soit 100 aérodromes visités en 24 heures).</p>

    <p>Le règlement stipule les points suivants : <ul  TYPE="square">
    <li>Un même aérodrome ne peut faire l'objet que d'un seul posé-décollé.</li>
    <li>l'aire de la compétition est le territoire métropolitain et la Corse, assortie de la contrainte d'inclure dans le parcours :
        <ul>
            <li>au moins un aérodrome situé au nord du parallèle 49°75'N (zone 1),</li>
            <li>au moins un aérodrome situé à l'ouest du méridien 1°40'W (zone 2),</li>
            <li>au moins un aérodrome situé simultanément au sud du parallèle 44°30'N et à l'ouest du méridien 2°E (zone 3),</li>
            <li>au moins un aérodrome situé simultanément au sud du parallèle 44°30'N et à l'est du méridien 5°E (zone 4),</li>
            <li>au moins un aérodrome situé simultanément au nord du parallèle 46°30N et à l'est du méridien 6°E (zone 5)</li>
            <li>au moins un aérodrome situé simultanément au sud du parallèle 48°N, au nord du parallèle 45°30'N, à l'ouest du méridien 4°E et à l'est du méridien 1°E (zone 6).</li>
        </ul> En cas de non-respect d'une zone de passage obligatoire, l'équipage quitte le classement. Ces zones sont visibles sur la page <a href="http://localhost/beweb2017/php/map.php" style="text-decoration: underline;">Map</a>.</li>
    <li>Le passage sur les aérodromes suivants :
        <ul>
            <li>LFRQ (Quimper),</li>
            <li>LFAT (Le Touquet),</li>
            <li>LFTH (Toulon-Hyères) ou LFTF (Cuers),</li>
            <li>LFBZ (Biarritz),</li>
            <li>LFLB (Chambéry-Savoie),</li>
        </ul> donera un bonus de 80NM par aérodrome à l'équipage y ayant réalisé un posé-décollé. Ce bonus sera retranché de la distance totale parcourue ; le maximum de bonus atteignable pour ces 5 aérodromes continentaux est donc de 400NM.</li>
    <li>La Corse bénéficie d'un statut de "continuité territoriale" : la distance de 208NM sera déduite de la distance totale parcourue si l'équipage effectue un posé-décollé en Corse. Par ailleurs, un bonus de 80NM sera attribué aux équipages qui effectueront des posés-décollés sur au moins deux des aérodromes LFKx.</li>
    </ul>
    </p>
</div>

<div class="remplissage titre2"><h2>Classement des équipages :</h2></div>
<div class="remplissage">
    <ul  TYPE="square">
        <li>L'équipage vainqueur sera celui qui aura effectué 100 posés-décollés en suivant les règles précédentes dans un laps de temps d'au plus 24h à partir de son premier décollage et jusqu'à son dernier atterrissage sur l'aérodrome d'arrivée. Si aucun équipage n'atteint les 100 posés-décollés, l'équipage vainqueur sera celui ayant effectué le plus de posés-décollés dans le temps imparti.</li>
        <li>En cas d'ex æquo sur le critère précédent, les équipages seront départagés par la plus petite distance parcourue après déduction des bonus.</li>
    </ul>
</div>
<?php

$html=footer();

echo $html;
?>
