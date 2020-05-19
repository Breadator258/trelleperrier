<?php

session_start();
include "connect_db.php";

$list_name = $_POST["selected_list"];
$username = $_SESSION["username"];

if (!$username){
    header('Location: index.php');
}

$can_write = false;
$is_list_admin = false;

include "navbar.php";

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
        $can_write = false;
    }else{ $can_write = true; }
}else{ $can_write = true; $is_list_admin = true;}

?>

<!DOCTYPE html>
<html>
<head>
    <link href="ressources/css/all.css" rel="stylesheet">
    <link href="ressources/css/show_list.css" rel="stylesheet">
    <title>Trelleperrier</title>
</head>

<body>

<h2 id="selected_list_name" style="text-align: center"><?php echo $list_name;?></h2>
<div id="main_modal" class="modal">
    <div class="modal-content" style="background-color: var(--form-bg-color);">
        <?php include "modal_navbar_and_forms.php"; ?>
        <div id="add_task_form" class="form" hidden style="margin-top: 3vh">
            <label for="new_task_name">Titre de la nouvelle tâche :</label><br>
            <div style="display: flex; align-items: center; justify-content: center">
                <textarea rows="6" maxlength="255" placeholder="Ma super tâche !" id="new_task_name" name="new_task_name" style="resize: none; border: 1px solid"></textarea>
                <input type="button" value="Valider" id="add_task_validate">
            </div>
        </div>
        <div id="remove_all_finished_tasks" class="form" style="margin-top: 3vh; text-align: center" hidden>
            <label>Supprimer toutes les tâches terminées ?</label><br>
            <input type="button" value="Confirmer" id="remove_all_finished_tasks_validate">
        </div>
        <div id="finish_all_current_tasks" class="form" style="margin-top: 3vh; text-align: center" hidden>
            <label>Terminer toutes les tâches en cours ?</label><br>
            <input type="button" value="Confirmer" id="finish_all_current_tasks_validate">
        </div>
        <div id="modify_task_form" class="form" hidden style="text-align: center; margin-top: 3vh;">
            <label for="new_task_name_to_modify">Nouveau titre de la tâche "<span id="task_name_to_modify"></span>" :</label><br>
            <div style="display: flex; align-items: center; justify-content: center">
                <textarea rows="6" maxlength="255" id="new_task_name_to_modify" name="new_task_name_to_modify" style="resize: none; border: 1px solid"></textarea>
                <input type="button" value="Valider" id="modify_task_validate">
            </div>
        </div>
        <div id="remove_task_form" class="form" hidden>
            <label>Voulez-vous supprimer la tâche :</label><br>
            <label id="task_name_to_remove"></label><br>
            <input type="button" value="Supprimer" id="remove_task_validate">
        </div>
        <div id="complete_task_form" class="form" hidden>
            <label>Tâche à terminer :</label><br>
            <label id="task_name_to_complete"></label><br>
            <input type="button" value="Terminer la tâche" id="complete_task_validate">
        </div>
        <div id="add_friend_to_list_form" class="form" hidden style="margin-top: 3vh">
            <div hidden>
                <label>Ajouter un ami :</label><br>
                <select id="friends_select">
                    <option value="" selected disabled>- Ami à ajouter -</option>
                    <?php
                    $get_friends_list = $conn->prepare('SELECT username, friend_username FROM users_friends WHERE (username=:username OR friend_username=:username) AND request_accepted=\'yes\'');
                    $get_friends_list->bindParam(':username', $username);
                    $get_friends_list->execute();
                    $get_friends_list_result = $get_friends_list->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($get_friends_list_result as $friend) {
                        if($friend["username"] === $username){
                            echo "<option class='friend' value='" . $friend['friend_username'] . "'><span>". $friend['friend_username'] ."</span></option>";
                        }else{
                            echo "<option class='friend' value='" . $friend['username'] . "'><span>". $friend['username'] ."</span></option>";
                        }
                    } ?>
                </select>
                <br><br><input type="button" value="Ajouter l'utilisateur" id="add_user_via_friends_list_validate"><br><br>
            </div>
            <div hidden>
                <label>Ajouter quelqu'un d'autre :</label><br>
                <input type="text" name="add_user_input" id="add_user_input" placeholder="Nom d'utilisateur ou E-Mail"><br><br>
                <input type="button" value="Ajouter l'utilisateur" id="add_user_validate">
            </div>
        </div>
        <div id="add_friend_to_list_response" class="form" hidden>&nbsp;</div>
        <div id="select_permission_form" class="form" hidden>
            <span>Permissions de l'utilisateur à ajouter (modifiable plus tard) :</span><br>
            <input type="radio" id="cant_write_radio" checked onclick="$('#can_write_radio').prop('checked', false);"><label>Lecture uniquement</label><br>
            <input type="radio" id="can_write_radio" onclick="$('#cant_write_radio').prop('checked', false);"><label>Lecture et écriture</label>&nbsp;&nbsp;&nbsp;
        </div>
        <div id="manage_permissions" class="form" hidden>
            <p>Gérer les permissions de :</p>
            <select id="manage_permissions_select">
                <option value="" selected disabled>- Selectionnez un membre -</option>
                <?php
                if($is_list_admin){
                    $get_members_list = $conn->prepare('SELECT user_friend from todo_lists_friends WHERE list_name=:list_name');
                    $get_members_list->bindParam(':list_name', $list_name);
                    $get_members_list->execute();
                    $get_members_list_result = $get_members_list->fetchAll(PDO::FETCH_ASSOC);
                    $get_members_list->closeCursor();

                    foreach ($get_members_list_result as $member){
                        echo "<option class='member' value='" . $member["user_friend"] . "'>" . $member["user_friend"] . "</option>";
                    }
                }
                ?>
            </select><br><br>
            <span>Permissions de l'utilisateur :</span><br>
            <input type="radio" id="new_permissions_cant_write_radio" onclick="$('#new_permissions_can_write_radio').prop('checked', false);"><label>Lecture uniquement</label><br>
            <input type="radio" id="new_permissions_can_write_radio" onclick="$('#new_permissions_cant_write_radio').prop('checked', false);"><label>Lecture et écriture</label>&nbsp;&nbsp;&nbsp;<br>
            <span id="modify_member_permissions_response">&nbsp;</span><br>
            <input type="button" value="Modifier les permissions" id="modify_member_permissions_validate"><br><br>
            <input type="button" id="remove_member" value="Retirer le membre de la liste">
        </div>
        <div id="members_list" hidden>
            <h2>Membres de la liste :</h2><br>
            <div id="members">
            <?php
                $get_list_admin = $conn->prepare('SELECT username from todo_lists_list WHERE list_name=:list_name');
                $get_list_admin->bindParam(':list_name', $list_name);
                $get_list_admin->execute();
                $get_list_admin_result = $get_list_admin->fetch(PDO::FETCH_ASSOC)["username"];
                $get_list_admin->closeCursor();

                echo "<div class='member'><span>". $get_list_admin_result . "</span><i class='fas fa-crown' title='Propriétaire de la liste'></i></div>";

                $get_members_list = $conn->prepare('SELECT user_friend, can_write from todo_lists_friends WHERE list_name=:list_name');
                $get_members_list->bindParam(':list_name', $list_name);
                $get_members_list->execute();
                $get_members_list_result = $get_members_list->fetchAll(PDO::FETCH_ASSOC);
                $get_members_list->closeCursor();

                foreach ($get_members_list_result as $member){
                    if($member["can_write"] === "yes"){
                        echo "<div class='member'><span>". $member["user_friend"] . "</span><i class='fas fa-pencil-alt' title='Peut lire et écrire'></i></div>";
                    }else{
                        echo "<div class='member'><span>". $member["user_friend"] . "</span><i class='fa fa-eye' title='Peut lire uniquement'></i></div>";
                    }
                }
            ?>
            </div>
        </div>
    </div>

</div>

<div id="tasks_list">
    <div id="tasks_in_progress">
        <div id="tasks_in_progress_h2_i"><h2>Tâches en cours :</h2><?php if($can_write === true){echo "<span id=\"add_todo_task\"><i class=\"far fa-times-circle\" title='Ajouter une nouvelle tâche'></i></span>";} ?></div>

        <span id="tasks_in_progress_list">
            <?php
                $get_in_progress_tasks = $conn->prepare('SELECT task_name from lists_tasks WHERE list_name = :list_name AND finished = "no"');
                $get_in_progress_tasks->bindParam(':list_name', $list_name);
                $get_in_progress_tasks->execute();
                $get_in_progress_tasks_result = $get_in_progress_tasks->fetchAll(PDO::FETCH_ASSOC);
                if($can_write === true){
                    $icons = "<br><i class=\"far fa-check-circle finish_task\" title='Terminer la tâche'></i><i class=\"fas fa-pencil-alt modify\" title='Modifier le nom de la tâche'></i><i class=\"far fa-times-circle delete_task\" title='Supprimer la tâche'></i>";
                }else{ $icons = ""; }
                foreach ($get_in_progress_tasks_result as $task) {
                    echo "<div class='task'>". $task['task_name'] . $icons . "</div><br>";
                }
            ?>
        </span>
    </div>

    <div id="finished_tasks">
        <h2>Tâches terminées :</h2>
        <span id="tasks_finished_list">
            <?php
                $get_finished_tasks = $conn->prepare('SELECT task_name from lists_tasks WHERE list_name = :list_name AND finished = "yes"');
                $get_finished_tasks->bindParam(':list_name', $list_name);
                $get_finished_tasks->execute();
                $get_finished_tasks_result = $get_finished_tasks->fetchAll(PDO::FETCH_ASSOC);
                if($can_write === true){
                    $icons = "<br><i class='far fa-arrow-alt-circle-up' title='Repasser en tâche en cours'></i><i class=\"fas fa-pencil-alt modify\" title='Modifier le nom de la tâche'></i><i class=\"far fa-times-circle delete_task\" title='Supprimer la tâche'></i>";
                }else{ $icons = ""; }
                foreach ($get_finished_tasks_result as $task) {
                    echo "<div class='task'>". $task['task_name'] . $icons . "</div><br>";
                }
            ?>
        </span>
    </div>
</div>

<div id="bottom_action_bar"><i class="fas fa-users" id="show_members" title="Afficher les membres de la liste"></i><?php if($is_list_admin === true){echo "<i id=\"manage_members_permissions\" class=\"fas fa-user-lock\" title='Gérer les permissions des membres'></i>";} ?><?php if($can_write === true){echo "<i id=\"add_friend_to_list\" class=\"fas fa-share-alt\" title='Partager la liste'></i><i class=\"fas fa-tasks\" title='Terminer toutes les tâches'></i><i class=\"fas fa-trash-alt\" title='Supprimer toutes les tâches terminées'></i>";} ?><i class="far fa-circle" title="Toutes les tâches sont affichées" id="show_all_tasks"></i></div>




<script src="ressources/js/jquery-3.4.1.js" type="text/javascript"></script>
<script src="ressources/js/show_list.js" type="text/javascript"></script>

</body>

</html>
