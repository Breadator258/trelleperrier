if($("#notif_counter").text() !== "0"){
    if(parseInt($("#notif_counter").text()) > 1){ var notifs = " nouvelles notifications" }else{notifs = " nouvelle notification"}
    $("#notifications").replaceWith("<i id=\"notifications\" class=\"fas fa-bell\" title='Vous avez " + $("#notif_counter").text() + notifs + "'></i>");
}

$("#notifications").click(function () {
    $("#modal-navbar").children("li").hide();
    $("#main_modal").show();
    $("#notifications-div").show();
    $(".list_name_notif").each(function () {
        var list_name = $(this).text().replace(/ /g, '%20');;

        if (window.XMLHttpRequest) {
            xhttp=new XMLHttpRequest();
        }else{
            xhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xhttp.onreadystatechange=function() {
            if (this.readyState===4 && this.status===200) {}
        };
        xhttp.open("POST", "list_treatment.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("list_name=" + list_name + "&is_new_list=false&remove=false&viewed_notif=true");
    });
});

// TOUT FERMER
$(".close").click(function () {
    $("#main_modal").hide();
    $("#add_friend_form").hide();
    $("#add_list_form").hide();
    $("#add_friend_response").text("");
    $("#add_friend_input").val("");
    $("#add_list_reponse").text("");
    $("#friends_list_main").hide();
    $("#sent_requests_main").hide();
    $("#pending_requests_main").hide();
    $("#notifications-div").hide();
    $("#remove_list_form").hide();
});

// Afficher liste d'amis
$("#friends_list_menu").click(function () {
    $("#main_modal").show();
    $("#modal-navbar").children("li").show();
    $("#modal-navbar").show();
    $("#add_friend").click();
    $("#friends_list_div").show();
});

$("#add_friend").click(function () {
    $(this).css("color", "#CFFFDF");
    $(this).css("background-color", "#23272A");
    $("#add_friend_form").show();
    $("#friends_list_main").hide();
    $("#my_friends").css("color", "black");
    $("#my_friends").css("background-color", "#CFFFDF");
    $("#sent_requests_main").hide();
    $("#asked_friends").css("color", "black");
    $("#asked_friends").css("background-color", "#CFFFDF");
    $("#pending_requests_main").hide();
    $("#pending_friends").css("color", "black");
    $("#pending_friends").css("background-color", "#CFFFDF");
    $("#add_friend_response").text("");
});

$("#my_friends").click(function () {
    $(this).css("color", "#CFFFDF");
    $(this).css("background-color", "#23272A");
    $("#friends_list_main").show();
    $("#add_friend_form").hide();
    $("#add_friend").css("color", "black");
    $("#add_friend").css("background-color", "#CFFFDF");
    $("#sent_requests_main").hide();
    $("#asked_friends").css("color", "black");
    $("#asked_friends").css("background-color", "#CFFFDF");
    $("#pending_requests_main").hide();
    $("#pending_friends").css("color", "black");
    $("#pending_friends").css("background-color", "#CFFFDF");
    $("#add_friend_response").text("");
});

$("#asked_friends").click(function () {
    $(this).css("color", "#CFFFDF");
    $(this).css("background-color", "#23272A");
    $("#sent_requests_main").show();
    $("#add_friend_form").hide();
    $("#add_friend").css("color", "black");
    $("#add_friend").css("background-color", "#CFFFDF");
    $("#friends_list_main").hide();
    $("#my_friends").css("color", "black");
    $("#my_friends").css("background-color", "#CFFFDF");
    $("#pending_requests_main").hide();
    $("#pending_friends").css("color", "black");
    $("#pending_friends").css("background-color", "#CFFFDF");
    $("#add_friend_response").text("");

});

$("#pending_friends").click(function () {
    $(this).css("color", "#CFFFDF");
    $(this).css("background-color", "#23272A");
    $("#pending_requests_main").show();
    $("#add_friend_form").hide();
    $("#add_friend").css("color", "black");
    $("#add_friend").css("background-color", "#CFFFDF");
    $("#friends_list_main").hide();
    $("#my_friends").css("color", "black");
    $("#my_friends").css("background-color", "#CFFFDF");
    $("#sent_requests_main").hide();
    $("#asked_friends").css("color", "black");
    $("#asked_friends").css("background-color", "#CFFFDF");
    $("#add_friend_response").text("");
});

// ENVOYER DEMANDE D'AMI
$("#add_friend_send_request").click(function () {
    var friend_info = $("#add_friend_input").val();

    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            if(this.responseText === "Vous avez déjà demandé en ami cet utilisateur !" || this.responseText === "Non, vous ne pouvez pas dupliquer vos amis ! :c" || this.responseText === "Vous n'avez pas d'ami à ce point ?" || this.responseText === "Entrez un ami qui existe."){
                $("#add_friend_response").text(this.responseText);
            }else {
                $("#add_friend_response").text("Une demande d'ami a été envoyée à " + this.responseText);
                $("#sent_requests").append("<div class='friend_requested'><span>" + this.responseText + "</span></div>");
            }
        }
    };
    xhttp.open("POST", "friend_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("friend_info=" + friend_info + "&add_friend=true");
});

// ACCEPTER/DECLINER UNE DEMANDE/SUPPRIMER UN AMI
$(document).on("click", "i", function () {

    var accept_request = "false";
    var remove = "false";
    if($(this).hasClass("friend_request_deny") || $(this).hasClass("friend_request_accept") || $(this).hasClass("delete_friend")){
        if($(this).hasClass("friend_request_deny") || $(this).hasClass("delete_friend")){
            if($(this).hasClass("delete_friend")){
                var friend_info = $(this).parent().children("span").text();
            }else{
                friend_info = $(this).parent().parent().children("span").text();
            }
            var remove = "true";
            if (window.XMLHttpRequest) {
                xhttp=new XMLHttpRequest();
            }else{
                xhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xhttp.onreadystatechange=function() {
                if (this.readyState===4 && this.status===200) {
                    $("." + friend_info).remove();
                }
            };
        }else if($(this).hasClass("friend_request_accept")){
            friend_info = $(this).parent().parent().children("span").text();
            var accept_request = "true";

            if (window.XMLHttpRequest) {
                xhttp=new XMLHttpRequest();
            }else{
                xhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xhttp.onreadystatechange=function() {
                if (this.readyState===4 && this.status===200) {
                    $("." + friend_info).remove();
                    $("#friends_list").append("<div class='friend " + friend_info +"'><span>" + friend_info + "</span><i class=\"far fa-times-circle delete_friend\"></i></div>");
                }
            };
        }
        xhttp.open("POST", "friend_treatment.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("friend_info=" + friend_info + "&remove=" + remove + "&accept_request=" + accept_request);
    }
});