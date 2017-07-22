$(document).ready(function(){
    $("form#addFlight").on("submit", function( event){
        event.preventDefault();
        $.ajaxSetup({async: true});
        $.ajax({
            url : '/beweb2017/php/calcul.php',
            type : 'POST',
            data: $( this ).serialize(),
            dataType: 'html',
            success : function(resultat_du_php,status)
                {
			if(resultat_du_php)
			{
				// Il y a eu un problème dans les données!
				$('div#erreur').html("<img src=\"/beweb2017/images/warning.jpg\" alt=\"warning\" height=\"48\"> Erreur dans les données entrées : "+resultat_du_php);
			} else {
				// Pas d'erreur => rediriger vers la page des scores
				location.href = "/beweb2017/php/scores.php";
			}

		},

    		error : function(donnee, statut, erreur){
			alert('Erreur durant l\'accès à la db: '+erreur+", statut="+statut+"\ndonnées = "+donnee);},
        });
    });
});
