<?php
require_once('template.php');

function registrform(){

    $registring = '
     <script src="/beweb2017/library/jquery.min.js"></script> 
     <script src="/beweb2017/js/adduser.js" type="text/javascript"></script>

    <div class="user">
    <header class="user__header">
        <img src="/beweb2017/images/avion.ico" alt="icone" width="25%"/>
        <h1 class="user__title">S\'inscrire en trois clics</h1>
    </header>

    <form class="form" id="ins" method="POST" action="/beweb2017/php/adduser.php">
        <div class="form__group">
            <input type="login" name="login" placeholder="login" class="form__input" />
        </div>


        <div class="form__group">
            <input type="password" name="passwd" placeholder="Password" class="form__input" />
        </div>

        <button class="btn" type="submit">Register</button>

    </form>
    <div id="resultat"></div>
</div>


    ';
    return $registring;
}

$html=entete("S'enregistrer", false, true );
$html.=navbar(true);
$html.=registrform();
$html.=footer();

echo $html;
?>
