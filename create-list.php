<?php

include "connect_db.php";
session_start();

if(isset($_POST["new_list_name"])){
    $username = $_SESSION["username"];
    $title = $_POST["new_list_name"];

    // On récupère le nombre de listes de l'utilisateur et son id
    $get_username = $conn->prepare('SELECT username from accounts_infos WHERE username = :username');
    $get_username->bindParam(':username', $username);
    $get_username->execute();
    $get_username_result = $get_username->fetch(PDO::FETCH_ASSOC)["username"];

    // On ajoute la ligne de création de TODO_list dans la table correspondante
    $stmt = $conn->prepare("INSERT INTO todo_lists_list (username, list_name) VALUES (:username, :list_name)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':list_name', $title);

    $stmt->execute();

}
