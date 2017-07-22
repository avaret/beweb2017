<?php

require_once('template.php');
require_once('bdd.php');

echo entete('PageAdmin');
echo navbar();

?>

<div class="remplissage"> <h2> Page administrateur</h2> </div>

<div class="remplissage">
  <p> <b> Liste des utilisateurs inscrits dans la base de donnée : </b>
<table class="table table-inverse" data-toggle="table" data-search="true" data-pagination="true" data-page-size="20">
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
	if($dbh != "fail")
	{
		$sql="SELECT login, isAdmin FROM USER";
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
	} else {
		echo "<tr><td> Base de donnée inaccessible. Existe-t-elle ? </td></tr>";
	}
?>
  </tbody>
</table>
</div>

<div class="remplissage">
  <p> <b> Génération du vent : </b>

     <form id="addWind" method="POST" action="/beweb2017/php/calcul.php">
	 <label name="timeToGenerateWind"> Heure à partir de laquelle générer les 24 heures de vent : </label> 
         <input type="datetime" name="timeToGenerateWind" value="<?php echo date('Y-m-d G:i:s', time()); ?>"/> 
         <input type="submit" name="generateWind" value="Générer le vent" />
     </form>

 </p>

  <p> <u> Attention : le bouton suivant efface complètement la base de données. Ne cliquez dessus que si vous savez ce que vous faites ! </u> </p>
  <p>  <br/> <button onclick="if(confirm('Etes-vous sûr de vouloir détruire la base de donnée et tout son contenu ??')) { alert('Destruction à la fermeture de cette fenêtre...'); location.href='/beweb2017/php/gestion_bdd.php'; } "> DROP DATABASE ; </button>
    <!-- br/> <button disabled='true' onclick="if(confirm('Etes-vous sûr de vouloir détruire la base de donnée et tout son contenu ??')) { location.href='/beweb2017/php/gestion_bdd.php?do=reset';} "> (RE)CREATE DATABASE ; </button -->
   </p>
</div>

<div class="remplissage">
  <p> A faire pour l'installation : </p>
  <ul>
    <li> Copier /beweb2017/image/favicon.ico vers la racine (/var/www/html) </li>
    <li> Importer la base de donnée initiale (be_vX.sql) avec phpmyadmin </li>
    <li> Générer du vent à l'aide de cette page (voir ci-dessus) </li>
  </ul>
</div>

<?php
echo footer();

?>
