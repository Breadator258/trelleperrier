<?php

session_start();

if($_SESSION) {
    session_unset();
    session_destroy();
    header('Location: home.php');
}

if (isset($_GET["pass"])) {
    echo "<h3>Couple d'identifiants invalide.</h3>";
}

?>

<form action="connect_final.php" method="post">
    <h3>Se connecter</h3>
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username"><br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password"><br>
    <input type="submit" value="Se connecter :O">
</form>