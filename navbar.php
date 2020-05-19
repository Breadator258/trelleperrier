<?php

    $username = $_SESSION["username"];

?>
<div id="navbar">
    <div style="width: 25%; display: flex; flex-direction: row; align-items: center">
        <a id="home_link" href="home.php"><i class="fas fa-home" id="home_button" title="Page d'accueil"></i></a>
        <p>Bonjour, <span><?php echo $username; ?></span> !</p>
    </div>
    <img id="logo" src="ressources/images/logo.png">
    <div style="width: 25%; display: flex; justify-content: right; flex-direction: row; margin-right: 2%">
        <i id="darkmode" class="fas fa-moon" title="Passer en thème sombre"></i>
        <i id="notifications" class="far fa-bell" title="Pas de nouvelles notifications"></i>
        <i id="friends_list_menu" class="fas fa-users" title="Liste d'amis"></i>
        <i id="deconnexion" onclick="document.location.href='index.php?logout=0'" class="fas fa-sign-out-alt" title="Se déconnecter"></i>
    </div>
</div>
<div id="sub_navbar"></div>
<link href="ressources/css/all.css" rel="stylesheet">

<style>
    body{
        font-family: Roboto, sans-serif;
        margin: 0;
    }
    #navbar{
        height: 8%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: var(--CFFFDF);
        width: 100%;
    }

    #sub_navbar {
        width: 100%;
        height: 8px;
        background-color: #2C8207;
    }

    #logo{
        height: 90%;
        width: auto;
        display: inline-block;
        margin: 0 auto 0 auto;
    }
    p{
        vertical-align: center
    }
    #navbar i{ font-size: 2em;
    margin: 0 5% 0 5%;
    }
    #darkmode:hover, #friends_list_menu:hover, #deconnexion:hover, #notifications:hover, #home_button:hover{
        cursor: pointer;
        color: grey;
    }

    #home_link{
        color: black;
        margin-left: 10%;
        margin-right: 2%;
    }

    input[type=button]{
        height: 30px;
        cursor: pointer;
        border: 2px black solid;
        border-radius: 10px;
        background: #CFFFDF;
        color: black;
    }

</style>

<script src="ressources/js/jquery-3.4.1.js" type="text/javascript"></script>
<script src="ressources/js/navbar.js" type="text/javascript"></script>