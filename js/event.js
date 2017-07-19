$(document).ready(function(){
    $("form#form").on("submit", function( event){
        event.preventDefault();
        $.ajax({
            url : '/beweb2017/php/bdd.php/insert',
            type : 'POST',
            data: $( this ).serialize(),
            dataType: 'html',
            success : function(resultat)
                {$('div#resultat').html("user inséré");},
            error : function(donnee, statut, erreur){alert('erreur');},
        });
    });
});