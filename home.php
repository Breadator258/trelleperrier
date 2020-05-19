<?php

session_start();
include "connect_db.php";
$username = $_SESSION["username"];

if (!$username){
    header('Location: index.php');
}

include "navbar.php";

?>

<!DOCTYPE html>
<html>
<head>
    <link href="ressources/css/all.css" rel="stylesheet">
    <link href="ressources/css/home.css" rel="stylesheet">
    <title>Trelleperrier</title>
</head>

<body>

    <div id="main_modal" class="modal">
        <div class="modal-content" style="background-color: var(--form-bg-color);">
            <?php include "modal_navbar_and_forms.php"; ?>
            <div id="add_list_form" hidden>
                <label for="new_list_name">Titre de la nouvelle liste (70 caractères maximum) :</label><br><br>
                <input type="text" placeholder="Ma super liste !" id="new_list_name" name="new_list_name"><br><br>
                <input id="add_list_button" type="button" value="Valider"><br><br>
                <span id="add_list_reponse"></span>
            </div>
            <div id="remove_list_form" hidden>
                <label>Voulez-vous supprimer la liste :</label><br><br>
                <label id="list_name_to_remove"></label><br><br>
                <input type="button" value="Supprimer" id="remove_list_validate">
            </div>
            <div id="leave_list_form" hidden>
                <label>Voulez-vous quitter la liste :</label><br><br>
                <label id="list_name_to_leave"></label><br><br>
                <input type="button" value="Quitter :'(" id="leave_list_validate">
            </div>
        </div>
    </div>

    <form id="selected_list_form" action="show_list.php" method="post" hidden>
        <input id="selected_list" type="hidden" name="selected_list" value="">
    </form>
    <br>
    <br>
    <div class='list add_list' id="add_todo_list"><span>Ajouter une liste</span><span class='list-i-container'><i class="far fa-times-circle"></i></span></div>
    <div id="lists">
        <?php
            // ON RECUPERE LES LISTES DE L'UTILISATEUR
            $get_user_lists = $conn->prepare('SELECT list_name, shared from todo_lists_list WHERE username = :username');
            $get_user_lists->bindParam(':username', $username);
            $get_user_lists->execute();
            $get_user_lists_result = $get_user_lists->fetchAll(PDO::FETCH_ASSOC);
            foreach ($get_user_lists_result as $list) {
                $shared_icon = "";
                if($list["shared"] === "yes"){ $shared_icon = "<i class=\"fas fa-user-friends\" title='Liste partagée'></i>"; }else{ $shared_icon = "<i class=\"fas fa-user\" title='Liste non partagée'></i>";}
                echo "<div class='list'><span><span class='list-i-container'><i class=\"fas fa-crown\" title='Vous êtes le propriétaire de cette liste'></i></span><span class='access_list'>". $list['list_name'] ."</span></span><span class='list-i-container'>". $shared_icon ."<i class=\"far fa-times-circle delete_list\" title='Supprimer la liste'></i></span></div>";
            }

            // ON RECUPERE LES LISTES PARTAGEES AVEC L'UTILISATEUR
            $get_user_lists = $conn->prepare('SELECT list_name from todo_lists_friends WHERE user_friend = :username');
            $get_user_lists->bindParam(':username', $username);
            $get_user_lists->execute();
            $get_user_lists_result = $get_user_lists->fetchAll(PDO::FETCH_ASSOC);
            foreach ($get_user_lists_result as $list) {
                echo "<div class='list'><span class='access_list'>". $list['list_name'] ."</span><span class='list-i-container'><i class=\"fas fa-user-friends\" title='Liste partagée'></i><i class=\"far fa-times-circle leave_list\" title='Quitter la liste'></i></span></div>";
            }
        ?></div>

    <script src="ressources/js/jquery-3.4.1.js" type="text/javascript"></script>
    <script src="ressources/js/home.js" type="text/javascript"></script>
</body>

</html>
