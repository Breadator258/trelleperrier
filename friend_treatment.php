<?php

include "connect_db.php";
session_start();

$username = htmlspecialchars($_SESSION["username"]);
$friend_info = htmlspecialchars($_REQUEST["friend_info"]);

if (!$username){
    header('Location: index.php');
}

if(isset($_REQUEST["get_permissions"])){
    $get_permissions = true;
}else{$get_permissions = "";}

if(isset($_REQUEST["list_name"])){
    $list_name = htmlspecialchars($_REQUEST["list_name"]);
}else{$list_name = "";}

if(isset($_REQUEST["modify_permissions"])){
    $modify_permissions = htmlspecialchars($_REQUEST["modify_permissions"]);
}else{$modify_permissions = "";}

if(isset($_REQUEST["share"])){
    $share = htmlspecialchars($_REQUEST["share"]);
}else{$share = "";}

if(isset($_REQUEST["remove"])){
    $remove = htmlspecialchars($_REQUEST["remove"]);
}else{$remove = "";}

if(isset($_REQUEST["add_friend"])){
    $add_friend = htmlspecialchars($_REQUEST["add_friend"]);
}else{$add_friend = "";}

if(isset($_REQUEST["accept_request"])){
    $accept_request = htmlspecialchars($_REQUEST["accept_request"]);
}else{$accept_request = "";}

$is_user_list_admin = $conn->prepare('SELECT username from todo_lists_list WHERE username = :username AND list_name=:list_name');
$is_user_list_admin->bindParam(':username', $username);
$is_user_list_admin->bindParam(':list_name', $list_name);
$is_user_list_admin->execute();
$is_user_list_admin_result = $is_user_list_admin->fetch(PDO::FETCH_ASSOC)["username"];
$is_user_list_admin->closeCursor();

if($is_user_list_admin_result){
    $is_list_admin = true;
}else{ $is_list_admin = false;}

$get_user_mail = $conn->prepare("SELECT email FROM accounts_infos WHERE username=:username");
$get_user_mail->bindParam(':username', $username);
$get_user_mail->execute();
$get_user_mail_result = $get_user_mail->fetch(PDO::FETCH_ASSOC)["email"];
$get_user_mail->closeCursor();

if($friend_info === htmlspecialchars($_SESSION["username"]) or $friend_info === $get_user_mail_result){
    echo "Vous n'avez pas d'ami à ce point ?";
    exit();
}

// RECUPERER LES PERMISSIONS DU MEMBRE
if($get_permissions === true){

    if(!$is_list_admin){
        exit();
    }

    $get_member_permissions = $conn->prepare('SELECT can_write from todo_lists_friends WHERE user_friend = :friend_info AND list_name=:list_name');
    $get_member_permissions->bindParam(':friend_info', $friend_info);
    $get_member_permissions->bindParam(':list_name', $list_name);
    $get_member_permissions->execute();
    $get_member_permissions_result = $get_member_permissions->fetch(PDO::FETCH_ASSOC)["can_write"];
    $get_member_permissions->closeCursor();

    echo $get_member_permissions_result;
    exit();
}

// CHANGER LES PERMISSIONS DU MEMBRE
if($modify_permissions !== ""){

    if(!$is_list_admin){
        exit();
    }

    if($modify_permissions === "read") {
        $can_write = "no";
        $modify_permissions_mail_msg = "Lecture uniquement";
    }else{$can_write = "yes"; $modify_permissions_mail_msg = "Lecture et écriture";}
    $modify_member_permissions = $conn->prepare('UPDATE todo_lists_friends SET can_write =:can_write WHERE list_name=:list_name AND user_friend=:friend_info');
    $modify_member_permissions->bindParam(':friend_info', $friend_info);
    $modify_member_permissions->bindParam(':list_name', $list_name);
    $modify_member_permissions->bindParam(':can_write', $can_write);
    $modify_member_permissions->execute();
    $modify_member_permissions->closeCursor();

    $get_member_mail = $conn->prepare("SELECT email FROM accounts_infos WHERE username=:friend_info");
    $get_member_mail->bindParam(':friend_info', $friend_info);
    $get_member_mail->execute();
    $get_member_mail_result = $get_member_mail->fetch(PDO::FETCH_ASSOC)["email"];
    $get_member_mail->closeCursor();

    //ENVOI DU MAIL
    $to  = $get_member_mail_result;

    $subject = 'Permissions modifiées';

    // message
    $message = "
             <html>
              <head>
               <h2>Vos permissions concernant la liste '$list_name' ont été changées ! :O</h2>
              </head>
              <body>
               <p>Vous avez désormais les permissions '$modify_permissions_mail_msg'.</p><br><br>
               <p>Cordialement,</p>
               <p>L'équipe Trelleperrier <3</p>
              </body>
             </html>
             ";
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';

    // Envoi
    mail($to, $subject, $message, implode("\r\n", $headers));

    exit();
}

// AJOUTER AMI A UNE LISTE
if($share === "true"){

    $is_user_list_admin = $conn->prepare('SELECT username from todo_lists_list WHERE username = :username AND list_name=:list_name');
    $is_user_list_admin->bindParam(':username', $username);
    $is_user_list_admin->bindParam(':list_name', $list_name);
    $is_user_list_admin->execute();
    $is_user_list_admin_result = $is_user_list_admin->fetch(PDO::FETCH_ASSOC)["username"];
    $is_user_list_admin->closeCursor();

    if(!$is_user_list_admin_result){
        echo "Vous n'êtes pas le propriétaire de la liste.";
        exit();
    }

    $check_if_friend_exist = $conn->prepare("SELECT username, email FROM accounts_infos WHERE username=:friend_info OR email=:friend_info");
    $check_if_friend_exist->bindParam(':friend_info', $friend_info);
    $check_if_friend_exist->execute();
    $check_if_friend_exist_result = $check_if_friend_exist->fetchAll(PDO::FETCH_ASSOC);
    $check_if_friend_exist->closeCursor();

    if(!$check_if_friend_exist_result){
        echo "Entrez un ami qui existe.";
        exit();
    }

    foreach ($check_if_friend_exist_result as $mails) {
        $friend_mail = $mails["email"];
        $friend_info = $mails["username"];
    }

    $check_if_user_already_in_list = $conn->prepare("SELECT user_friend FROM todo_lists_friends WHERE user_friend=:friend_info AND list_name=:list_name");
    $check_if_user_already_in_list->bindParam(':friend_info', $friend_info);
    $check_if_user_already_in_list->bindParam(':list_name', $list_name);
    $check_if_user_already_in_list->execute();
    $check_if_user_already_in_list_result = $check_if_user_already_in_list->fetch(PDO::FETCH_ASSOC)["user_friend"];
    $check_if_user_already_in_list->closeCursor();

    if($check_if_user_already_in_list_result){
        echo "Vous partagez déjà cette liste avec " . $friend_info . ".";
        exit();
    }

    $change_list_status_to_shared = $conn->prepare("UPDATE todo_lists_list SET shared = 'yes' WHERE list_name=:list_name AND username=:username");
    $change_list_status_to_shared->bindParam(':list_name', $list_name);
    $change_list_status_to_shared->bindParam(':username', $username);
    $change_list_status_to_shared->execute();
    $change_list_status_to_shared->closeCursor();

    if($_REQUEST["permission_add_user"] === "read"){
        $can_write = "no";
        $can_write_mail_msg = "mais vous n'avez pas encore la permission en écriture. " . $username . " doit vous l'accorder.";
    }else{$can_write = "yes"; $can_write_mail_msg = "et vous avez également la permission en écriture.";}

    $add_friend_to_list_users = $conn->prepare("INSERT INTO todo_lists_friends (list_name, user_friend, can_write, viewed_notif) VALUES (:list_name, :user_friend, :can_write,default)");
    $add_friend_to_list_users->bindParam(':list_name', $list_name);
    $add_friend_to_list_users->bindParam(':user_friend', $friend_info);
    $add_friend_to_list_users->bindParam(':can_write', $can_write);

    $add_friend_to_list_users->execute();

    echo $friend_info;

    //ENVOI DU MAIL
    $to  = $friend_mail;

    $subject = 'Vous êtes membre d\'une nouvelle liste !';

    // message
    $message = "
             <html>
              <head>
               <p>Cher $friend_info.</p>
              </head>
              <body>
               <p>Nous avons l'honneur, et ce délicat et onctueux plaisir de vous annoncer avec bonheur que l'utilisateur $username vous a ajouté à sa liste '$list_name'.</p>
               <p>Vous avez désormais accès à son contenu, $can_write_mail_msg</p><br><br>
               <p>Cordialement,</p>
               <p>L'équipe Trelleperrier <3</p>
              </body>
             </html>
             ";
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';

    // Envoi
    mail($to, $subject, $message, implode("\r\n", $headers));

    exit();
}

// RETIRER/DECLINER UN UTILISATEUR
if($remove === "true"){
    $delete_friend_or_request = $conn->prepare("DELETE FROM users_friends WHERE (friend_username=:username AND username=:friend_info) OR (friend_username=:friend_info AND username=:username)");
    $delete_friend_or_request->bindParam(':username', $username);
    $delete_friend_or_request->bindParam(':friend_info', $friend_info);
    $delete_friend_or_request->execute();
    $delete_friend_or_request->closeCursor();
    exit();
}

// ACCEPTER DEMANDE D'AMI
if($accept_request === "true"){
    $accept_friend_request = $conn->prepare("UPDATE users_friends SET request_accepted = 'yes' WHERE username=:friend_info");
    $accept_friend_request->bindParam(':friend_info', $friend_info);
    $accept_friend_request->execute();
    $accept_friend_request->closeCursor();

    // RECUPERE LE MAIL DE L'UTILISATEUR ACCEPTÉ
    $get_friend_mail = $conn->prepare("SELECT email FROM accounts_infos WHERE username=:friend_info");
    $get_friend_mail->bindParam(':friend_info', $friend_info);
    $get_friend_mail->execute();
    $get_friend_mail_result = $get_friend_mail->fetch(PDO::FETCH_ASSOC)["email"];
    $get_friend_mail->closeCursor();

    //ENVOI DU MAIL
    $to  = $get_friend_mail_result;

    $subject = 'Demande d\'ami acceptée !';

    // message
    $message = "
             <html>
              <head>
               <h2>Un utilisateur a accepté votre demande d'ami ! :D</h2>
              </head>
              <body>
               <p>Il s'agit de $username.</p>
               <p>Bonheur à vous deux ! <3</p><br><br>
               <p>Cordialement,</p>
               <p>L'équipe Trelleperrier <3</p>
              </body>
             </html>
             ";
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';

    // Envoi
    mail($to, $subject, $message, implode("\r\n", $headers));

    exit();
}

// DEMANDER EN AMI
if($add_friend === "true"){

    // VERIFIE SI L'UTILISATEUR A AJOUTER EXISTE
    $check_if_friend_exist = $conn->prepare("SELECT username, email FROM accounts_infos WHERE username=:friend_info OR email=:friend_info");
    $check_if_friend_exist->bindParam(':friend_info', $friend_info);
    $check_if_friend_exist->execute();
    $check_if_friend_exist_result = $check_if_friend_exist->fetchAll(PDO::FETCH_ASSOC);
    $check_if_friend_exist->closeCursor();

    if(!$check_if_friend_exist_result){
        echo "Entrez un ami qui existe.";
        exit();
    }

    foreach ($check_if_friend_exist_result as $mails) {
        $friend_mail = $mails["email"];
        $friend_username = $mails["username"];
    }

    // VERIFIE SI UNE DEMANDE A DEJA ETE ENVOYEE OU SI L'UTILISATEUR EST DEJA AMI
    $check_if_already_asked_or_friends = $conn->prepare("SELECT request_accepted FROM users_friends WHERE username=:username AND friend_username=:friend_username");
    $check_if_already_asked_or_friends->bindParam(':username', $username);
    $check_if_already_asked_or_friends->bindParam(':friend_username', $friend_username);
    $check_if_already_asked_or_friends->execute();
    $check_if_already_asked_or_friends_result = $check_if_already_asked_or_friends->fetch(PDO::FETCH_ASSOC)["request_accepted"];
    $check_if_already_asked_or_friends->closeCursor();

    if(!$check_if_already_asked_or_friends_result){
        $stmt = $conn->prepare("INSERT INTO users_friends (username, friend_username, request_accepted) VALUES (:username, :friend_username, default)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':friend_username', $friend_username);

        $stmt->execute();

        //ENVOI DU MAIL
        $to  = $friend_mail;

        $subject = 'Vous avez une demande d\'ami !';

        // message
        $message = "
             <html>
              <head>
               <h2>Un utilisateur vous demande en ami ! :D</h2>
              </head>
              <body>
               <p>Il s'agit de $username.</p>
               <p>Rendez-vous sur votre espace pour visualiser cette demande.</p><br><br>
               <p>Cordialement,</p>
               <p>L'équipe Trelleperrier <3</p>
              </body>
             </html>
             ";
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';

        // Envoi
        mail($to, $subject, $message, implode("\r\n", $headers));

        echo $friend_username;
        exit();
    }else{
        if($check_if_already_asked_or_friends_result == "no"){
            echo "Vous avez déjà demandé en ami cet utilisateur !";
            exit();
        }else{
            echo "Non, vous ne pouvez pas dupliquer vos amis ! :c";
            exit();
        }
    }
}
