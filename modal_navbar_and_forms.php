<?php ?>

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 35vh;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 0;
        border: 1px solid #888;
        width: 40%;
        height: 30vh;
        overflow: auto;
    }

    #modal-navbar{
        overflow: hidden;
        font-weight: bold;
        font-family: Roboto, sans-serif;
        height: 5vh;
        background-color: #CFFFDF;
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    li{
        float: left;
    }

    li span{
        text-align: center;
        margin: auto;
        cursor: pointer;
        display: block;
        padding: 14px 16px;
    }

    li span:hover {
        background-color: #23272A;
        color: #CFFFDF;
    }

    #friends_list_div{
        padding: 1%;
    }

    .close {
        height: 5vh;
        line-height: 150%;
        padding-right: 2.5vh;
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    #friends_list, #sent_requests, #pending_requests{
        overflow: auto;
        height: 77%;
    }

    .friend, .pending_friend{
        margin: 0 35% 0 35%;
        display: flex;
        justify-content: space-between;
    }

    #add_friend_response, .friend_requested{
        text-align: center;
    }

    #friends_sent_pending{
        display: flex;
        justify-content: space-around;
    }

    #add_friend_form, #add_list_form, #add_task_form{
        text-align: center;
    }

    #add_friend_send_request, #add_list_button, #add_task_validate{
        height: 30px;
    }

    #add_friend_input, #new_list_name, #new_task_name{
        width: 50%;
        line-height: 1.5em;
    }

    #notifications-div{
        text-align: center;
    }

</style>

<ul id="modal-navbar">
    <li><span id="add_friend">Ajouter un ami</span></li>
    <li><span id="my_friends">Mes amis</span></li>
    <li><span id="asked_friends">Demandes envoyées</span></li>
    <li><span id="pending_friends">Demandes reçues</span></li>
    <span class="close">&times;</span>
</ul>
<div id="friends_list_div" hidden>
    <form id="add_friend_form" hidden><br>
        <input type="text" name="add_friend_input" id="add_friend_input" placeholder="Nom d'utilisateur ou E-Mail"><br><br>
        <input type="button" name="add_friend_send_request" id="add_friend_send_request" value="Envoyer la demande">
    </form>
    <div id="add_friend_response"></div>
    <div id="friends_list_main" hidden>
        <div id="friends_list">
            <?php
            $get_friends_list = $conn->prepare('SELECT username, friend_username FROM users_friends WHERE (username=:username OR friend_username=:username) AND request_accepted=\'yes\'');
            $get_friends_list->bindParam(':username', $username);
            $get_friends_list->execute();
            $get_friends_list_result = $get_friends_list->fetchAll(PDO::FETCH_ASSOC);

            foreach ($get_friends_list_result as $friend) {
                if($friend["username"] === $username){
                    echo "<div class='friend " . $friend["friend_username"] . "'><span>". $friend['friend_username'] ."</span><i class=\"far fa-times-circle delete_friend\"></i></div>";
                }else{
                    echo "<div class='friend " . $friend["username"] . "'><span>". $friend['username'] ."</span><i class=\"far fa-times-circle delete_friend\"></i></div>";
                }
            }?>
        </div>
    </div>
    <div id="sent_requests_main" hidden>
        <div id="sent_requests">
            <?php
            $get_sent_requests = $conn->prepare('SELECT friend_username FROM users_friends WHERE username=:username AND request_accepted=\'no\'');
            $get_sent_requests->bindParam(':username', $username);
            $get_sent_requests->execute();
            $get_sent_requests_result = $get_sent_requests->fetchAll(PDO::FETCH_ASSOC);

            foreach ($get_sent_requests_result as $friend) {
                echo "<div class='friend_requested'><span>". $friend['friend_username'] ."</span></div>";
            }?>
        </div>
    </div>
    <div id="pending_requests_main" hidden>
        <div id="pending_requests">
            <?php
            $get_pending_requests = $conn->prepare('SELECT username FROM users_friends WHERE friend_username=:username AND request_accepted=\'no\'');
            $get_pending_requests->bindParam(':username', $username);
            $get_pending_requests->execute();
            $get_pending_requests_result = $get_pending_requests->fetchAll(PDO::FETCH_ASSOC);

            foreach ($get_pending_requests_result as $friend) {
                echo "<div class='pending_friend " . $friend["username"] . "'><span>". $friend['username'] ."</span><div><i class=\"far fa-times-circle friend_request_deny\"></i><i class=\"far fa-check-circle friend_request_accept\"></i></div></div>";
            }?>
        </div>
    </div>
</div>
<div id="notifications-div" hidden>
    <div id="new_notifs" style="font-weight: bold">Nouvelles notifications</div>
    <?php
    $get_notifications = $conn->prepare('SELECT list_name, viewed_notif FROM todo_lists_friends WHERE user_friend=:username AND viewed_notif = "no"');
    $get_notifications->bindParam(':username', $username);
    $get_notifications->execute();
    $get_notifications_results = $get_notifications->fetchAll(PDO::FETCH_ASSOC);

    $notif_counter = 0;

    foreach ($get_notifications_results as $notif) {
            $notif_counter += 1;
            echo "<div class='notif " . $notif['list_name'] . "' style='font-weight: bold'><span>Vous avez été ajouté à la liste <span class='list_name_notif'>". $notif['list_name'] ."</span>.</span></div>";
    }
    echo "<span id='notif_counter' style='position: absolute' hidden>" . $notif_counter . "</span>"?>
    <br><div id="notifs_historic">Historique des notifications</div>
    <?php
    $get_notifications = $conn->prepare('SELECT list_name, viewed_notif FROM todo_lists_friends WHERE user_friend=:username AND viewed_notif = "yes"');
    $get_notifications->bindParam(':username', $username);
    $get_notifications->execute();
    $get_notifications_results = $get_notifications->fetchAll(PDO::FETCH_ASSOC);

    foreach ($get_notifications_results as $notif) {
        echo "<div class='notif " . $notif['list_name'] . "'><span>Vous avez été ajouté à la liste <span class='list_name_notif'>". $notif['list_name'] ."</span>.</span></div>";
    }?>
</div>

<script src="ressources/js/jquery-3.4.1.js" type="text/javascript"></script>
<script src="ressources/js/modal_navbar_and_forms.js" type="text/javascript"></script>
