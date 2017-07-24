<?php

require_once('template.php');
require_once('db.php');

echo entete('PageAdmin');
echo navbar();

?>

<div class="remplissage titre1"> <h1> Page administrateur</h1> </div>

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
        <input type="datetime" name="timeToGenerateWind" value="<?php echo date('Y-m-d G:i:s', time()); ?>"/> <br/>
        <label name="nbMetar"> Nombre de METAR de 2 heures à générer : </label> 
        <input type="datetime" name="nbMetar" value="12"/> <br />
        <input type="submit" name="generateWind" value="Générer le vent" />
    </form>

    </p>

<p> <u> Attention : le bouton suivant efface complètement la base de données. Ne cliquez dessus que si vous savez ce que vous faites ! </u> </p>
<p>  <br/> <button onclick="if(confirm('Etes-vous sûr de vouloir détruire la base de donnée et tout son contenu ??')) { alert('Destruction à la fermeture de cette fenêtre...'); location.href='/beweb2017/php/mgmt_db.php'; } "> DROP DATABASE ; </button>
    <!-- br/> <button disabled='true' onclick="if(confirm('Etes-vous sûr de vouloir détruire la base de donnée et tout son contenu ??')) { location.href='/beweb2017/php/mgmt_db.php?do=reset';} "> (RE)CREATE DATABASE ; </button -->
</p>
</div>

<div class="remplissage">
    <p> A faire pour l'installation : </p>
    <ul>
        <li> Copier /beweb2017/images/favicon.ico vers la racine (/var/www/html) </li>
        <li> Importer la base de donnée initiale (be_vX.sql) avec phpmyadmin </li>
        <li> Générer du vent à l'aide de cette page (voir ci-dessus) </li>
    </ul>
</div>

<div class="remplissage">
    <h3> Notes de conception </h3>
    <p> Voici la liste des fonctionnalités implémentées dans notre site web :
    <ul>
	<li>Visualisation des aéroports sur la carte</li>
	<li>Visualisation de l'avion et des trajectoires de tous les avions</li>
	<li>Animation de l'avion qui vole entre aéroports, avec possibilité de pause</li>
	<li>Page de score, avec tri des colonnes</li>
	<li>Gestion des utilisateurs, authentification + ajout d'utilisateurs</li>
	<li>Mot de passe hashé dans la base de donné (il n'est PAS stocké en clair!)</li>
	<li>Page de score avec boutons pour supprimer un vol, ou pour le visualiser</li>
	<li>Accès à la création d'un vol depuis la carte (cliquer sur l'aérodrome puis sur l'icône représentant un avion qui décolle)</li>
	<li>Page de contact avec mini-bio et photos</li>
	<li>Page d'administration listant les utilisateurs et quelques autres fonctions</li>
	<li>Gestion du vent (stocké dans la db) : créer manuellement depuis la page d'admin puis c'est automatiquement pris en compte pour tous les vols créés ultérieurement</li>
	<li>Possibilité d'ajouter de nombreux vols rapidement (pour peupler la base et faire des tests)</li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
    </ul>
    <p> Ci-dessous le schémas UML de la base de donnée. </p>
    <img src="/beweb2017/images/conception.png" >
</div>


<?php
echo footer();

?>
