<?php

include "connect_db.php";
session_start();

$username = htmlspecialchars($_SESSION["username"]);
$list_name = htmlspecialchars($_REQUEST["list_name"]);

if(isset($_REQUEST["remove"])){
    $remove = htmlspecialchars($_REQUEST["remove"]);
}else{$remove = "";}

if(isset($_REQUEST["viewed_notif"])){
    $viewed_notif = htmlspecialchars($_REQUEST["viewed_notif"]);
}else{$viewed_notif = "";}

if(isset($_REQUEST["is_new_list"])){
    $is_new_list = htmlspecialchars($_REQUEST["is_new_list"]);
}else{$is_new_list = "";}

if(isset($_REQUEST["leave"])){
    $leave = htmlspecialchars($_REQUEST["leave"]);
}else{$leave = "";}

if(isset($_REQUEST["friend_info"])){
    $friend_info = htmlspecialchars($_REQUEST["friend_info"]);
}else{$friend_info = "";}

if (!$username){
    header('Location: index.php');
}

if(isset($_REQUEST["remove_member"])){
    $remove_member = $_REQUEST["remove_member"];
}else{$remove_member = "";};

if($remove_member !== ""){

    $is_user_list_admin = $conn->prepare('SELECT username from todo_lists_list WHERE username = :username AND list_name=:list_name');
    $is_user_list_admin->bindParam(':username', $username);
    $is_user_list_admin->bindParam(':list_name', $list_name);
    $is_user_list_admin->execute();
    $is_user_list_admin_result = $is_user_list_admin->fetch(PDO::FETCH_ASSOC)["username"];
    $is_user_list_admin->closeCursor();

    if(!$is_user_list_admin_result){
        echo "Touche pas à ça. >:(";
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM todo_lists_friends WHERE user_friend = :user_friend AND list_name = :list_name");
    $stmt->bindParam(':list_name', $list_name);
    $stmt->bindParam(':user_friend', $friend_info);
    $stmt->execute();

    exit();
}

if($remove === "true"){
    $stmt = $conn->prepare("DELETE FROM todo_lists_list WHERE list_name = :list_name AND username = :username");
    $stmt->bindParam(':list_name', $list_name);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM lists_tasks WHERE list_name = :list_name");
    $stmt->bindParam(':list_name', $list_name);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM todo_lists_friends WHERE list_name = :list_name");
    $stmt->bindParam(':list_name', $list_name);
    $stmt->execute();
    exit();
}

if($leave === "true"){

    $is_user_list_admin = $conn->prepare('SELECT username from todo_lists_list WHERE username = :username AND list_name=:list_name');
    $is_user_list_admin->bindParam(':username', $username);
    $is_user_list_admin->bindParam(':list_name', $list_name);
    $is_user_list_admin->execute();
    $is_user_list_admin_result = $is_user_list_admin->fetch(PDO::FETCH_ASSOC)["username"];
    $is_user_list_admin->closeCursor();

    if($is_user_list_admin_result){
        echo "Touche pas à ça. >:(";
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM todo_lists_friends WHERE list_name = :list_name AND user_friend = :username");
    $stmt->bindParam(':list_name', $list_name);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $check_if_list_has_members_left = $conn->prepare('SELECT list_name from todo_lists_friends WHERE list_name=:list_name');
    $check_if_list_has_members_left->bindParam(':list_name', $list_name);
    $check_if_list_has_members_left->execute();
    $check_if_list_has_members_left_result = $check_if_list_has_members_left->fetchAll(PDO::FETCH_ASSOC);
    $check_if_list_has_members_left->closeCursor();

    if(!$check_if_list_has_members_left_result){
        $stmt = $conn->prepare("UPDATE todo_lists_list SET shared = 'no' WHERE list_name = :list_name");
        $stmt->bindParam(':list_name', $list_name);

        $stmt->execute();
    }

    echo "Vous ne faites plus partie de la liste " . $list_name . " :o";
    exit();
}

if($is_new_list === "true"){

    // On vérifie que la liste n'existe pas déjà
    $check_if_list_already_exist = $conn->prepare('SELECT list_name from todo_lists_list WHERE list_name = :list_name');
    $check_if_list_already_exist->bindParam(':list_name', $list_name);
    $check_if_list_already_exist->execute();
    $check_if_list_already_exist_result = $check_if_list_already_exist->fetch(PDO::FETCH_ASSOC)["list_name"];

    if($check_if_list_already_exist_result){
        echo "Une liste portant le même nom existe déjà.";
        exit();
    }

    // On récupère le nombre de listes de l'utilisateur et son id
    $get_username = $conn->prepare('SELECT username from accounts_infos WHERE username = :username');
    $get_username->bindParam(':username', $username);
    $get_username->execute();
    $get_username_result = $get_username->fetch(PDO::FETCH_ASSOC)["username"];

    // On ajoute la ligne de création de TODO_list dans la table correspondante
    $stmt = $conn->prepare("INSERT INTO todo_lists_list (username, list_name, shared) VALUES (:username, :list_name, default )");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':list_name', $list_name);

    $stmt->execute();
    exit();
}

if($viewed_notif === "true"){
    $stmt = $conn->prepare("UPDATE todo_lists_friends SET viewed_notif = 'yes' WHERE list_name = :list_name");
    $stmt->bindParam(':list_name', $list_name);

    $stmt->execute();
    exit();
}
