<?php

session_start();
include "connect_db.php";

$username = htmlspecialchars($_SESSION["username"]);
$list_name = htmlspecialchars($_REQUEST["list_name"]);

if(isset($_REQUEST["modify"])){
    htmlspecialchars($new_task_name_to_modify = $_REQUEST["modify"]);
}else{$new_task_name_to_modify = "";}

if(isset($_REQUEST["task_name"])){
    $task_name = htmlspecialchars($_REQUEST["task_name"]);
}else{$task_name = "";}

if(isset($_REQUEST["is_new_task"])){
    $is_new_task = htmlspecialchars($_REQUEST["is_new_task"]);
}else{$is_new_task = "";}

if(isset($_REQUEST["remove"])){
    $remove = htmlspecialchars($_REQUEST["remove"]);
}else{$remove = "";}

if(isset($_REQUEST["finished"])){
    $finished = htmlspecialchars($_REQUEST["finished"]);
}else{$finished = "";}

if(isset($_REQUEST["delete_finished_tasks"])){
    $delete_finished_tasks = htmlspecialchars($_REQUEST["delete_finished_tasks"]);
}else{$delete_finished_tasks = "";}

if(isset($_REQUEST["finish_all_current_tasks"])){
    $finish_all_current_tasks = htmlspecialchars($_REQUEST["finish_all_current_tasks"]);
}else{$finish_all_current_tasks = "";}

if (!$username){
    header('Location: index.php');
}

$get_list_id = $conn->prepare('SELECT id from todo_lists_list WHERE list_name = :list_name');
$get_list_id->bindParam(':list_name', $list_name);
$get_list_id->execute();
$get_list_id_result = $get_list_id->fetch(PDO::FETCH_ASSOC)["id"];
$get_list_id->closeCursor();

$is_user_list_admin = $conn->prepare('SELECT username from todo_lists_list WHERE username = :username AND list_name=:list_name');
$is_user_list_admin->bindParam(':username', $username);
$is_user_list_admin->bindParam(':list_name', $list_name);
$is_user_list_admin->execute();
$is_user_list_admin_result = $is_user_list_admin->fetch(PDO::FETCH_ASSOC)["username"];
$is_user_list_admin->closeCursor();

if(!$is_user_list_admin_result){
    $is_friend_of_list_and_can_write = $conn->prepare('SELECT can_write from todo_lists_friends WHERE user_friend = :username AND list_name=:list_name');
    $is_friend_of_list_and_can_write->bindParam(':username', $username);
    $is_friend_of_list_and_can_write->bindParam(':list_name', $list_name);
    $is_friend_of_list_and_can_write->execute();
    $is_friend_of_list_and_can_write_result = $is_friend_of_list_and_can_write->fetch(PDO::FETCH_ASSOC)["can_write"];
    $is_friend_of_list_and_can_write->closeCursor();

    if($is_friend_of_list_and_can_write_result === "no"){
        echo "Vous n'avez pas les permissions en écriture. Contactez le propriétaire de la liste. :)";
        exit();
    }
}

if($delete_finished_tasks === "true"){
    $stmt = $conn->prepare("DELETE FROM lists_tasks WHERE list_name =:list_name AND finished='yes'");
    $stmt->bindParam(':list_name', $list_name);

    $stmt->execute();
    exit();
}

if($finish_all_current_tasks === "true"){
    $stmt = $conn->prepare("UPDATE lists_tasks SET finished = 'yes' WHERE list_name = :list_name AND finished='no'");
    $stmt->bindParam(':list_name', $list_name);

    $stmt->execute();
    exit();
}

if($is_new_task === "true"){

    // On vérifie si une tâche du même nom existe déjà
    $check_if_task_already_exist = $conn->prepare('SELECT task_name from lists_tasks WHERE list_name = :list_name AND task_name=:task_name');
    $check_if_task_already_exist->bindParam(':task_name', $task_name);
    $check_if_task_already_exist->bindParam(':list_name', $list_name);
    $check_if_task_already_exist->execute();
    $check_if_task_already_exist_result = $check_if_task_already_exist->fetch(PDO::FETCH_ASSOC)["task_name"];
    $check_if_task_already_exist->closeCursor();

    if($check_if_task_already_exist_result){
        echo "Cette tâche existe déjà";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO lists_tasks (list_id, list_name, task_name, finished) VALUES (:list_id, :list_name, :task_name, 'no')");
    $stmt->bindParam(':list_id', $get_list_id_result);
    $stmt->bindParam(':list_name', $list_name);
    $stmt->bindParam(':task_name', $task_name);

    $stmt->execute();
    exit();
}

if($remove === "true"){
    $stmt = $conn->prepare("DELETE FROM lists_tasks WHERE task_name = :task_name");
    $stmt->bindParam(':task_name', $task_name);

    $stmt->execute();
    exit();
}

if($finished === "true"){
    $stmt = $conn->prepare("UPDATE lists_tasks SET finished = 'yes' WHERE task_name = :task_name");
    $stmt->bindParam(':task_name', $task_name);

    $stmt->execute();
    exit();
}

if($new_task_name_to_modify !== ""){

    // On vérifie si une tâche du même nom existe déjà
    $check_if_task_already_exist = $conn->prepare('SELECT task_name from lists_tasks WHERE list_name = :list_name AND task_name=:task_name');
    $check_if_task_already_exist->bindParam(':task_name', $new_task_name_to_modify);
    $check_if_task_already_exist->bindParam(':list_name', $list_name);
    $check_if_task_already_exist->execute();
    $check_if_task_already_exist_result = $check_if_task_already_exist->fetch(PDO::FETCH_ASSOC)["task_name"];
    $check_if_task_already_exist->closeCursor();

    if($check_if_task_already_exist_result){
        echo "Une tâche portant le même nom existe déjà.";
        exit();
    }

    $stmt = $conn->prepare("UPDATE lists_tasks SET task_name = :new_task_name_to_modify WHERE task_name = :task_name");
    $stmt->bindParam(':task_name', $task_name);
    $stmt->bindParam(':new_task_name_to_modify', $new_task_name_to_modify);

    $stmt->execute();

    exit();
}

if($finished === "false"){
    $stmt = $conn->prepare("UPDATE lists_tasks SET finished = 'no' WHERE task_name = :task_name");
    $stmt->bindParam(':task_name', $task_name);

    $stmt->execute();
    exit();
}
