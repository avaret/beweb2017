<?php

require_once('template.php');
require_once('db.php');

echo entete('Scores');
echo navbar();

?>

<div class="remplissage"  style="background-color: cyan;"> <h1> R&eacute;sultats de notre comp&eacute;tition ! </h1> </div>

<div class="remplissage">
<table class="table table-inverse" data-toggle="table" data-search="true" data-pagination="true" data-page-size="30">
  <thead>
    <tr>
      <th data-field="col1" data-sortable="true"> &nbsp; </th>
      <th data-field="col2" data-sortable="true">Rang</th>
      <th data-field="col3" data-sortable="true">A&eacute;rodromes</th>
      <th data-field="col4" data-sortable="true">Score</th>
      <th data-field="col5" data-sortable="true">Flight Id</th>
      <th data-field="col6" data-sortable="true">Nom d'équipe</th>
      <th data-field="col7" data-sortable="true">Avion</th>
      <th data-field="col8" data-sortable="true">Distance</th>
      <th data-field="col9" data-sortable="true">Nb Zones</th>
      <th data-field="cola" data-sortable="true">Bonus</th>
      <th data-field="colb" data-sortable="true">Départ</th>
      <th data-field="colc" data-sortable="true">Arrivée</th>
      <th data-field="cold" data-sortable="true">(util.)</th>
    </tr>
  </thead>
  <tbody>

<?php
	// Récupérer les scores	
	$dbh = connection();
	$sql = "SELECT idFlight, nameTeam, AircraftNumber, loginUser, nbConstraintSatisfied, nbBonuses, totalDistance, nbAerodromes, beginFlight, endFlight, canBeRanked, scoreSecondary 
		FROM FLIGHT_ENRICHED 
		ORDER BY canBeRanked DESC, nbAerodromes DESC, scoreSecondary DESC;";

	$sth=$dbh->query($sql);
	$rang = 0;
	while($result=$sth->fetch(PDO::FETCH_OBJ))
	{
		/* Afficher sous la forme : <tr>
		   <th scope="row">1</th>
		   <td>Mark</td>
		   <td>Otto</td>
		   <td>@mdo</td>
		   </tr> */

		if(!$result->canBeRanked) {
			$rang = " &nbsp; ";
			$logo = "";
		} else {
			$rang++;
			switch($rang) {
			case 1: $logo = "medaille-or.jpg"; break;
			case 2: $logo = "medaille-argent.jpg"; break;
			case 3: $logo = "medaille-bronze.png"; break;
			default: $logo = "medaille-classe.jpg"; break;
			}
			if($logo)
				$logo = "<img src=\"/beweb2017/images/$logo\" width=\"36\" alt=\"$logo\">";

		}

		if( $result->nbConstraintSatisfied == 6)
			$coche = "coche_ok.jpg";
		else
			$coche = "coche_ko.png";
		$coche = "<img src=\"/beweb2017/images/$coche\" width=\"36\" alt=\"$result->nbConstraintSatisfied zones traversées\">";

		$buttons = "<a href=\"/beweb2017/php/map.php?idFlt=$result->idFlight\"> <img src=\"/beweb2017/images/b_search.png\" alt='Visualiser'> </a> ";
		$isAdmin = isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"];
		$currentUser = (isset($_SESSION["login"]) ? $_SESSION["login"] : NULL);
		if($isAdmin || $currentUser == $result->loginUser)
			$buttons .= "<a href=\"/beweb2017/php/calcul.php?removeFlight=$result->idFlight\"> <img src=\"/beweb2017/images/b_remove.png\" alt='Retirer le vol'> </a> ";

		echo '<tr> <td scope="row">' . $logo . '</td>';
		echo '<td><b><center>' . $rang . '</center></b></td>';
		echo '<td>' . $result->nbAerodromes . '</td>';
		echo '<td>' . round($result->scoreSecondary) . '</td>';
		echo '<td>' . $result->idFlight . '</td>';
		echo '<td>' . $result->nameTeam . '</td>';
		echo '<td>' . $result->AircraftNumber . '</td>';
		echo '<td>' . round($result->totalDistance) . ' km </td>';
		echo '<td>' . $result->nbConstraintSatisfied . " &nbsp; " . $coche . '</td>';
		echo '<td>' . $result->nbBonuses . '</td>';
		echo '<td>' . $result->beginFlight . '</td>';
		echo '<td>' . $result->endFlight . '</td>';
		echo '<td>' . $result->loginUser . $buttons . '</td>';
		echo "</tr>\n";
	}
	$sth->closeCursor();

?>
  </tbody>
</table>
</div>

<?php
echo footer();

?>
