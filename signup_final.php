<?php

include "connect_db.php";


if(isset($_POST["username"]) and isset($_POST["password"]) and isset($_POST["email"])){
    $username = htmlspecialchars($_POST["username"]);

    if(strlen($username) > 25){
        header('Location: index.php?username_length=0');
        exit();
    }

    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $email = htmlspecialchars($_POST["email"]);

    $check_clone = $conn->prepare("SELECT id FROM accounts_infos WHERE username=:username");
    $check_clone->bindParam(':username', $username);
    $check_clone->execute();
    $result = $check_clone->fetch(PDO::FETCH_ASSOC);
    $check_clone->closeCursor();

    if($result){
        header('Location: index.php?username_clone=0');
        exit();
    }

    $check_clone = $conn->prepare("SELECT id FROM accounts_infos WHERE email=:email");
    $check_clone->bindParam(':email', $email);
    $check_clone->execute();
    $result = $check_clone->fetch(PDO::FETCH_ASSOC);
    $check_clone->closeCursor();

    if($result){
        header('Location: index.php?mail_clone=0');
        exit();
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $stmt = $conn->prepare("INSERT INTO accounts_infos (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        $stmt->execute();

        header('Location: index.php?signedup=0');
    }
}
