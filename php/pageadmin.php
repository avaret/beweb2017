<?php

require_once('template.php');
require_once('bdd.php');

echo entete('PageAdmin');
echo navbar();

?>

<div class="remplissage"> <h2> Page admin</h2> </div>

<div class="remplissage">
<table class="table table-inverse" data-toggle="table" data-search="true" data-pagination="true" data-page-size="3">
  <thead>
    <tr>
      <th data-field="col1" data-sortable="true">login</th>
      <th data-field="col2" data-sortable="true">isAdmin</th>
      
    </tr>
  </thead>
  <tbody>

<?php
	// Récupérer les logins
	$dbh = connection();
	$sql="SELECT login, isAdmin FROM user";
	$sth=$dbh->query($sql);
	while($result=$sth->fetch(PDO::FETCH_OBJ))
	{
		/* Afficher sous la forme : <tr>
		   <th scope="row">1</th>
		   <td>Mark</td>
		   <td>Otto</td>
		   <td>@mdo</td>
		   </tr> */
		
		echo '<tr> <th scope="row">login</th>';
		echo '<td>' . $result->login . '</td>';
		echo '<td>' . $result->isAdmin . '</td>';
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