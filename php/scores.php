<?php

require_once('template.php');
require_once('bdd.php');

echo entete('Scores');
echo navbar();

?>

<div id="remplissage"> <h2> R&eacute;sultats de notre comp&eacute;tition ! </h2> </div>

<div>
<table class="table table-inverse" data-toggle="table" data-search="true" data-pagination="true" data-page-size="3">
  <thead>
    <tr>
      <th data-field="col1" data-sortable="true"># SCORE FIXME</th>
      <th data-field="col2" data-sortable="true"> Flight Id </th>
      <th data-field="col3" data-sortable="true"> Team name </th>
      <th data-field="col4" data-sortable="true"> Aircraft Identifier </th>
      <th data-field="col5" data-sortable="true"> Total Distance </th>
      <th data-field="col6" data-sortable="true"> Zones reached </th>
      <th data-field="col7" data-sortable="true"> Bonuses </th>
      <th data-field="col8" data-sortable="true"> #Aerodromes </th>
      <th data-field="col9" data-sortable="true"> first TakeOff </th>
      <th data-field="cola" data-sortable="true"> last Landing </th>
      <th data-field="colb" data-sortable="true"> (user) </th>
    </tr>
  </thead>
  <tbody>

<?php
	// Récupérer les scores	
	$dbh = connection();
	$sql="SELECT idFlight, nameTeam, AircraftNumber, loginUser, nbConstraintSatisfied, nbBonuses, totalDistance, nbAerodromes, beginFlight, endFlight FROM FLIGHT_ENRICHED";
	$sth=$dbh->query($sql);
	while($result=$sth->fetch(PDO::FETCH_OBJ))
	{
		/* Afficher sous la forme : <tr>
		   <th scope="row">1</th>
		   <td>Mark</td>
		   <td>Otto</td>
		   <td>@mdo</td>
		   </tr> */
		$score = -1;
		echo '<tr> <th scope="row">' . $score . '</th>';
		echo '<td>' . $result->idFlight . '</td>';
		echo '<td>' . $result->nameTeam . '</td>';
		echo '<td>' . $result->AircraftNumber . '</td>';
		echo '<td>' . $result->totalDistance . '</td>';
		echo '<td>' . $result->nbConstraintSatisfied . '</td>';
		echo '<td>' . $result->nbBonuses . '</td>';
		echo '<td>' . $result->nbAerodromes . '</td>';
		echo '<td>' . $result->beginFlight . '</td>';
		echo '<td>' . $result->endFlight . '</td>';
		echo '<td>' . $result->loginUser . '</td>';
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
