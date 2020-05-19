<?php

include "connect_db.php";

if(isset($_POST["username"]) and isset($_POST["password"])){
    $username = $_POST["username"];
    $password = $_POST["password"];


    $response = $conn->prepare('SELECT password from accounts_infos WHERE username = :username');

    $response->bindParam(':username', $username);
    $response->execute();
    $result = $response->fetch(PDO::FETCH_ASSOC)["password"];
    $response->closeCursor();

    if (password_verify($password, $result)) {
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header('Location: home.php');
    } else {
        header('Location: index.php?pass=wrong');
    }

}