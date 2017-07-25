<?php

require_once('template.php');
require_once('db.php');

echo entete('Scores');
echo navbar();

?>

<div class="remplissage titre1"> <h1> <img src="/beweb2017/images/lauriers.gif" width="64" alt="lauriers"> R&eacute;sultats de notre comp&eacute;tition ! <img src="/beweb2017/images/lauriers.gif" width="64" alt="lauriers"> </h1> </div>

<div class="remplissage">
<table id="tableScores" class="table table-inverse" data-toggle="table" data-search="true" data-pagination="true" data-page-size="30">
  <thead>
    <tr>
      <th data-field="colmedaille" data-sortable="true"> &nbsp; </th>
      <th data-field="colrang" data-sortable="true">Rang</th>
      <th data-field="colaerodrom" data-sortable="true">A&eacute;rodromes</th>
      <th data-field="colscore" data-sortable="true">Score</th>
      <th data-field="colflightid" data-visible="false" data-sortable="true">Flight Id</th>
      <th data-field="colteam" data-sortable="true">Nom d'équipe</th>
      <th data-field="colavion" data-sortable="true">Avion</th>
      <th data-field="coldist" data-sortable="true">Distance</th>
      <th data-field="colzones" data-visible="false" data-sortable="true">Nb Zones</th>
      <th data-field="colbonus" data-sortable="true">Bonus</th>
      <th data-field="colstart" data-visible="false" data-sortable="true">Départ</th>
      <th data-field="colend" data-visible="false" data-sortable="true">Arrivée</th>
      <th data-field="coluser" data-visible="false" data-sortable="true">(util.)</th>
      <th data-field="colicons" data-sortable="true"> &nbsp; </th>
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
			case 1: $logo = "medaille-or.ico"; break;
			case 2: $logo = "medaille-argent.ico"; break;
			case 3: $logo = "medaille-bronze.ico"; break;
			default: $logo = "medaille-classe.ico"; break;
			}
			if($logo)
				$logo = "<img src=\"/beweb2017/images/$logo\" width=\"36\" alt=\"$logo\">";

		}

		if( $result->nbConstraintSatisfied == 6)
			$coche = "coche_ok.ico";
		else
			$coche = "coche_ko.png";
		$coche = "<img src=\"/beweb2017/images/$coche\" width=\"30\" alt=\"$result->nbConstraintSatisfied zones traversées\">";

		$buttons = "<a href=\"/beweb2017/php/map.php?idFlt=$result->idFlight\"> <img src=\"/beweb2017/images/b_search.png\" alt='Visualiser'> </a> ";
		$isAdmin = isset($_SESSION["admin"]) && $_SESSION["admin"];
		$currentUser = (isset($_SESSION["login"]) ? $_SESSION["login"] : NULL);
		if($isAdmin || ($currentUser == $result->loginUser))
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
		echo '<td>' . $result->loginUser . '</td>';
		echo '<td>' . $buttons . '</td>';
		echo "</tr>\n";
	}
	$sth->closeCursor();

?>
  </tbody>
</table>
<br/>
<script>
	var expand = 1; 
	var previous_text; 
	function chgColumns(status) {
		$(function(){
			var $table = $('#tableScores');
			var newstatus = ( status ? "hideColumn" : "showColumn" );

			$table.bootstrapTable(newstatus, 'colflightid');
			$table.bootstrapTable(newstatus, 'colzones');
			$table.bootstrapTable(newstatus, 'colstart');
			$table.bootstrapTable(newstatus, 'colend');
			$table.bootstrapTable(newstatus, 'coluser');
		});
	}

</script>
<button id="invertcolumns" onclick="expand = !expand; if(!expand) { previous_text = this.textContent; this.textContent = 'Cacher des colonnes'; } else { this.textContent = previous_text; } chgColumns(expand); " >Afficher toutes les colonnes</button>

</div>

<?php
echo footer();

?>

