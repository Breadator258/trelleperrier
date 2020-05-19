<?php

session_start();

$error = "";

if(isset($_GET["username_clone"])){
    $error = "Le nom d'utilisateur est utilisé.";
}
if(isset($_GET["mail_clone"])){
    $error = "Le mail est déjà utilisé.";
}

if(isset($_GET["signedup"])){
    $error = "Vous pouvez vous connecter.";
}

if(isset($_GET["username_length"])){
    $error = "Nom d'utilisateur trop long.";
}

if(isset($_GET["logout"])){
    if($_SESSION) {
        session_unset();
        session_destroy();
        header('Location: index.php');
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <link href="ressources/css/all.css" rel="stylesheet">
    <link href="ressources/css/index.css" rel="stylesheet">
    <title>Trelleperrier</title>
</head>

<body>


    <div id="GIGA-block" style="text-align: center">
        <div id="main-block">
            <div id="main-header" style="height: 30%">
                <img id="logo" src="ressources/images/logo.png"><br>
                <h2 class="font">Page de connexion</h2>

            </div>
            <div id="form-body">
                <form method="post" id="signin-form" action="connect_final.php">
                    <label class="font" for="signin-username">Nom d'utilisateur : </label>
                    <input id="signin-username" name="username" type="text" placeholder="&#xf007; Utilisateur" required><br><br>
                    <label class="font" for="signin-password">Mot de passe : </label>
                    <input id="signin-password" name="password" type="password" placeholder="&#xf023; Mot de passe" required>
                </form>
                <form method="post" id="signup-form" style="opacity: 0" hidden action="signup_final.php">
                    <label class="font" for="signup-username">Nom d'utilisateur (25 caractères maximum) : </label>
                    <input id="signup-username" maxlength="25" name="username" type="text" placeholder="&#xf007; Utilisateur" required><br><br>
                    <label class="font" for="signup-mail">E-Mail : </label>
                    <input id="signup-mail" name="email" type="text" placeholder="&#xf1fa; E-Mail" required><br><br>
                    <label class="font" for="signup-password">Mot de passe : </label>
                    <input id="signup-password" name="password" type="password" placeholder="&#xf023; Mot de passe" required>
                </form>
            </div>
            <div id="main-footer">
                <div style="text-align: center">
                    <button>Connexion</button><br><br>
                    <span><?php if($error !== ""){echo $error;} ?></span><br>
                    <p id="footer-p" class="font" style="display: inline-block">Pas de compte ?&nbsp;</p><span id="span" class="font">Créez en un !</span>
                </div>
            </div>
        </div>
    </div>


    <script src="ressources/js/jquery-3.4.1.js" type="text/javascript"></script>
    <script src="ressources/js/index.js" type="text/javascript"></script>


</body>

</html>
