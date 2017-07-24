$(document).ready(function(){
    $("form#ins").on("submit", function( event){
        event.preventDefault();
        $.ajaxSetup({async: false});
        $.ajax({
            url : '/beweb2017/php/adduser.php',
            type : 'POST',
            data: $( this ).serialize(),
            dataType: 'html',
            success : function(resultat_du_php,status)
                {$('div#resultat').html("Resultat de la requête d'insertion : "+resultat_du_php);},
            error : function(donnee, statut, erreur){alert('Erreur durant l\'accès à la db');},
        });
    });
});
