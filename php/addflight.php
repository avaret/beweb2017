<?php

require_once('template.php');
require_once('db.php');

echo entete('Ajouter un vol');
echo navbar();

echo '<div class="remplissage titre1"> <h2> Ajout d\'un vol :</h2> </div>';

if(isset($_GET["idAerodrome"]))
	$idAerodr = $_GET["idAerodrome"];
else
	$idAerodr = "LFBK";

if(isset($_SESSION["login"]))
{

?>


<div id="erreur"> </div>

<!-- Modal -->
<div id="calculencours" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- button type="button" class="close" data-dismiss="modal">&times;</button -->
        <h4 class="modal-title">Calculs en cours...</h4>
      </div>
      <div class="modal-body photo">
        <p>Calculs en cours... Veuillez patienter ! </p>
	<br />
	<img src="/beweb2017/images/loading.gif" alt="image chargement en cours" >
      </div>
      <div class="modal-footer">
        <!-- button type="button" class="btn btn-default" data-dismiss="modal">Close</button -->
      </div>
    </div>

  </div>
</div>


<div class="remplissage">

   <script src="/beweb2017/library/jquery.min.js"></script> 
   <script src="/beweb2017/js/addvol.js" type="text/javascript"></script>

     <form id="addFlight" method="POST">
       <fieldset>
       	<table>
	<tr><td><label for="idFlight"> Identification du Vol (par exemple AF1234) : </label></td><td>   		<input type="text" name="idFlight" value="AF1234" /> </td></tr> <br>
           <tr><td><label for="firstAerodrome">Aérodrome de départ (par exemple LFKB) : </label></td><td>   		<input type="text" name="firstAerodrome" value="<?php echo $idAerodr; ?>" /> </td></tr> <br>
           <tr><td><label for="teamName"> Nom de l'équipe (par exemple "Mon équipe") : </label></td><td>   		<input type="text" name="teamName" value="Notre team" /> </td></tr> <br>
           <tr><td><label for="aircraftNumber"> Identification de l'avion (par exemple F-TSTA) : </label></td><td>   	<input type="text" name="aircraftNumber" value="F-TGAT" /> </td></tr> <br>
   <tr><td>&nbsp;</td></tr>
   <tr><td><i> Paramètres avancés </i> </td></tr>
           <tr><td><label for="repeatCount"> Nombre de fois où répéter l'ajout de vol (=nombre de vols à ajouter) : </label></td><td>   		<input type="text" name="repeatCount" value="1" /> </td></tr> <br>
	   <tr><td><label for="startTime"> Heure du décollage initial : </label></td><td>   				<input type="datetime" name="startTime" value="<?php echo date('Y-m-d G:i:s', time()); ?>"/> </td></tr> <br>
           <tr><td><label for="useWind"> Doit-on prendre en compte l'effet du vent ?  </label></td><td>   		<input type="checkbox" name="useWind" checked="true" /> </td></tr> <br>
       	</table>
           <input type="submit" value="Ajouter le(s) vol(s)" /> <br />
       </fieldset>          
     </form>

     <p> &nbsp; </p>
     <p> <u> Remarque importante :</u> En ajoutant un vol, une trajectoire aléatoire complète sur 24 heures parcourant un maximum d'aérodromes français va être générée.</p><p> Ce processus prend (un peu) de temps et vous renverra sur la page des résultats pour voir quel score a effectué votre nouveau vol ! </p>
</div>

<?php

} else {
	echo "<div  class='remplissage'><p> Vous devez être enregistré et connecté pour pouvoir ajouter un vol. Utilisez les boutons Login et Register en haut à droite de la page.</p></div>";
}

echo footer();

?>
