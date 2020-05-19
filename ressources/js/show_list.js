$(document).on('click', ".delete_task", function () {
    $("#main_modal").show();
    $("#remove_task_form").show();
    $("#modal-navbar").children("li").hide();
    $("#task_name_to_remove").text($(this).parent().text());
});

$(document).on('click', ".modify", function () {
    $("#main_modal").show();
    $("#modal-navbar").children("li").hide();
    $("#modify_task_form").show();
    $("#task_name_to_modify").text($(this).parent().text());
    $("#new_task_name_to_modify").val($(this).parent().text());
});

$(document).on('click', "#show_all_tasks", function () {
    $(this).replaceWith("<i class='fas fa-check-circle' id='show_finished_tasks' title='Les tâches terminées sont affichées'></i>");
    $("#finished_tasks").css("width", "100%");
    $("#tasks_in_progress").hide();
});

$(document).on('click', "#show_finished_tasks", function () {
    $(this).replaceWith("<i class='fas fa-times-circle' id='show_current_tasks' title='Les tâches en cours sont affichées'></i>");
    $("#tasks_in_progress_h2_i").css("width", "74%");
    $("#finished_tasks").css("width", "65%");
    $("#finished_tasks").hide();
    $("#tasks_in_progress").css("width", "100%");
    $("#tasks_in_progress").show();
});

$(document).on('click', "#show_current_tasks", function () {
    $(this).replaceWith("<i class='far fa-circle' id='show_all_tasks' title='Toutes les tâches sont affichées'></i>");
    $("#tasks_in_progress_h2_i").css("width", "34%");
    $("#finished_tasks").show();
    $("#tasks_in_progress").css("width", "65%");
});

$("#show_members").click(function () {
    $("#modal-navbar").children("li").hide();
    $("#main_modal").show();
    $("#members_list").show();
});

$("#add_todo_task").click(function () {
    $("#modal-navbar").children("li").hide();
    $("#main_modal").show();
    $("#add_task_form").show();
});

$("#add_friend_to_list").click(function () {
    $("#modal-navbar").children("li").hide();
    $("#main_modal").show();
    $("#add_friend_to_list_form").show();
    $("#add_friend_to_list_form").children().show();
    $("#add_friend_to_list_response").show();
    $("#select_permission_form").show();
});

$(".fa-trash-alt").click(function () {
    $("#modal-navbar").children("li").hide();
    $("#main_modal").show();
    $("#remove_all_finished_tasks").show();
});

$(".fa-tasks").click(function () {
    $("#modal-navbar").children("li").hide();
    $("#main_modal").show();
    $("#finish_all_current_tasks").show();
});

$("#manage_members_permissions").click(function () {
    $("#modal-navbar").children("li").hide();
    $("#main_modal").show();
    $("#manage_permissions").show();
});

$(".close").click(function () {
    $("#main_modal").hide();
    $("#remove_task_form").hide();
    $("#complete_task_form").hide();
    $("#add_task_form").hide();
    $("#add_friend_to_list_form").hide();
    $("#modify_task_form").hide();
    $("#add_friend_to_list_response").hide();
    $("#select_permission_form").hide();
    $("#new_task_name_to_modify").val("");
    $("#manage_permissions").hide();
    $("#remove_all_finished_tasks").hide();
    $("#finish_all_current_tasks").hide();
    $("#members_list").hide();
});

// SUPPRIMER TOUTES LES TACHES TERMINEES
$("#remove_all_finished_tasks_validate").click(function () {
    var list_name = $("#selected_list_name").text();
    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            $("#tasks_finished_list").empty();
            $(".close").click();
        }
    };
    xhttp.open("POST", "task_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("list_name=" + list_name + "&delete_finished_tasks=true");
});

// TERMINER TOUTES LES TACHES EN COURS
$("#finish_all_current_tasks_validate").click(function () {

    var list_name = $("#selected_list_name").text();
    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            $("#tasks_in_progress_list .task").each(function (e, element) {

                $(element).children(".fa-check-circle").replaceWith("<i class='far fa-arrow-alt-circle-up' title='Repasser en tâche en cours'></i>");
                $(element).appendTo($("#tasks_finished_list"));
                $("<br>").appendTo($("#tasks_finished_list"));
            });
            $("#tasks_in_progress_list").empty();
            $(".close").click();
        }
    };
    xhttp.open("POST", "task_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("list_name=" + list_name + "&finish_all_current_tasks=true");
});

// RECUPERER LA PERMISSION ACTUELLE DU MEMBRE A GERER
$("#manage_permissions_select").change(function () {
    var friend_info = $(this).val();
    var list_name = $("#selected_list_name").text();
    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            if(this.responseText === "yes"){
                $("#new_permissions_can_write_radio").click();
            }else{
                $("#new_permissions_cant_write_radio").click();
            }
        }
    };
    xhttp.open("POST", "friend_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("friend_info=" + friend_info + "&list_name=" + list_name + "&get_permissions=true");
});

// RETIRER UN MEMBRE DE LA LISTE
$("#remove_member").click(function () {
    if($("#manage_permissions_select").children("option:selected").val() === ""){
        alert("Veuillez selectionner un utilisateur.");
        return;
    }

    var friend_info = $("#manage_permissions_select").children("option:selected").val();
    var list_name = $("#selected_list_name").text();

    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            $("#modify_member_permissions_response").text(friend_info + " ne fait plus partie de la liste. 'And I see you again. WOOOHOHOOOOOOHO'");
            $("#manage_permissions_select option[value='" + friend_info + "']").remove();
            $("#manage_permissions_select option:eq(0)").prop('selected', true);
            $(".member:contains('" + friend_info + "')").filter(function() { return $(this).text() === friend_info;}).remove();
        }
    };
    xhttp.open("POST", "list_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("friend_info=" + friend_info + "&list_name=" + list_name + "&remove_member=true" );
});

// GERER LES PERMISSIONS D'UN UTILISATEUR
$("#modify_member_permissions_validate").click(function () {
    if($("#manage_permissions_select").children("option:selected").val() === ""){
        alert("Veuillez selectionner un utilisateur.");
        return;
    }else if(!$("#new_permissions_cant_write_radio").is(':checked') && !$("#new_permissions_can_write_radio").is(':checked')){
        alert("Veuillez selectionner une permission.");
        return;
    }
    if($("#new_permissions_cant_write_radio").is(':checked')){
        var new_permission = "read";
    }else{ var new_permission = "write";}

    var friend_info = $("#manage_permissions_select").children("option:selected").val();
    var list_name = $("#selected_list_name").text();

    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            if(new_permission === "read"){
                $("#modify_member_permissions_response").text("Le membre " + friend_info + " ne peut plus écrire. CHEH.");
                $(".member:contains('" + friend_info + "')").filter(function() { return $(this).text() === friend_info;}).children("i").replaceWith("<i class='fa fa-eye' title='Peut lire uniquement'></i>");
            }else{
                $("#modify_member_permissions_response").text("Le membre " + friend_info + " peut désormais écrire. Quel BG !");
                $(".member:contains('" + friend_info + "')").filter(function() { return $(this).text() === friend_info;}).children("i").replaceWith("<i class='fa fa-pencil-alt' title='Peut lire et écrire'></i>");
            }
        }
    };
    xhttp.open("POST", "friend_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("friend_info=" + friend_info + "&list_name=" + list_name + "&modify_permissions=" + new_permission );

});

// AJOUTER TACHE
$("#add_task_validate").click(function () {
    $(".close").click();
    $("#add_task_form").hide();

    if (!$("#new_task_name").val()) {
        alert("Really ?");
        return;
    }else{
        var original_task_name = $("#new_task_name").val();
        var task_name = original_task_name.replace(/ /g, '%20');
        var list_name = $("#selected_list_name").text();
        $("#new_task_name").val("");
    }
    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            if(this.responseText.length === 0){
                $("#tasks_in_progress_list").append("<div class='task'>"+ task_name.replace(/%20/g, ' ') +"<br><i class='far fa-check-circle finish_task' title='Terminer la tâche'></i><i class=\"fas fa-pencil-alt modify\" title='Modifier le nom de la tâche'></i><i class=\"far fa-times-circle delete_task\" title='Supprimer la tâche'></i></div><br>")
            }else{
                alert(this.responseText);
            }
        }
    };
    xhttp.open("POST", "task_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("task_name=" + task_name + "&list_name=" + list_name + "&is_new_task=true&finished=false&remove=false&modify=false");
});

// SUPPRIMER TACHE
$("#remove_task_validate").click(function () {

    $("#remove_task_form").hide();
    var original_task_name = $("#task_name_to_remove").text();
    var task_name = original_task_name.replace(/ /g, '%20');
    var list_name = $("#selected_list_name").text();

    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            if(this.responseText.length === 0){
                $(".task:contains('" + original_task_name + "')").filter(function() { return $(this).text() === original_task_name;}).next().remove();
                $(".task:contains('" + original_task_name + "')").filter(function() { return $(this).text() === original_task_name;}).remove();
                $(".close").click();
            }else{
                alert(this.responseText);
            }
        }
    };
    xhttp.open("POST", "task_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("task_name=" + task_name + "&list_name=" + list_name + "&remove=true");
});

// TERMINER TACHE
$(document).on('click', ".finish_task", function () {
    var original_task_name = $(this).parent().text();
    var task_name = original_task_name.replace(/ /g, '%20');
    var list_name = $("#selected_list_name").text();

    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            if(this.responseText.length === 0){
                $(".task:contains('" + original_task_name + "')").filter(function() { return $(this).text() === original_task_name;}).next().remove();
                $(".task:contains('" + original_task_name + "')").filter(function() { return $(this).text() === original_task_name;}).remove();
                $("#tasks_finished_list").append("<div class='task'>"+ original_task_name +"<br><i class='far fa-arrow-alt-circle-up' title='Repasser en tâche en cours'></i><i class=\"fas fa-pencil-alt modify\" title='Modifier le nom de la tâche'></i><i class=\"far fa-times-circle delete_task\" title='Supprimer la tâche'></i></div><br>")
            }else{
                alert(this.responseText);
            }
        }
    };
    xhttp.open("POST", "task_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("task_name=" + task_name + "&list_name=" + list_name + "&finished=true");
});

// UNDO
$(document).on('click', ".fa-arrow-alt-circle-up", function () {

    var original_task_name = $(this).parent().text();
    var task_name = original_task_name.replace(/ /g, '%20');
    var list_name = $("#selected_list_name").text();

    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            if(this.responseText.length === 0){
                $(".task:contains('" + original_task_name + "')").filter(function() { return $(this).text() === original_task_name;}).next().remove();
                $(".task:contains('" + original_task_name + "')").filter(function() { return $(this).text() === original_task_name;}).remove();
                $("#tasks_in_progress_list").append("<div class='task'>"+ original_task_name +"<br><i class='far fa-check-circle finish_task' title='Terminer la tâche'></i><i class=\"fas fa-pencil-alt modify\" title='Modifier le nom de la tâche'></i><i class=\"far fa-times-circle delete_task\" title='Supprimer la tâche'></i></div><br>")
            }else{
                alert(this.responseText);
            }
        }
    };
    xhttp.open("POST", "task_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("task_name=" + task_name + "&list_name=" + list_name + "&finished=false");
});

// MODIFIER NOM TACHE
$("#modify_task_validate").click(function () {
    var original_task_name = $("#task_name_to_modify").text();
    var task_name = original_task_name.replace(/ /g, '%20');
    var new_task_name = $("#new_task_name_to_modify").val();
    var list_name = $("#selected_list_name").text();
    $(".close").click();

    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            if(this.responseText.length !== 0){
                alert(this.responseText);
            }else{
                if($(".task:contains('" + original_task_name + "')").filter(function() { return $(this).text() === original_task_name;}).parent().attr('id') === "tasks_in_progress_list"){
                    $(".task:contains('" + original_task_name + "')").filter(function() { return $(this).text() === original_task_name;}).replaceWith("<div class='task'>" + new_task_name + "<br><i class='far fa-check-circle finish_task' title='Terminer la tâche'></i><i class='fas fa-pencil-alt modify' title='Modifier le nom de la tâche'></i><i class='far fa-times-circle delete_task' title='Supprimer la tâche'></i></div>");
                }else{
                    $(".task:contains('" + original_task_name + "')").filter(function() { return $(this).text() === original_task_name;}).replaceWith("<div class='task'>" + new_task_name + "<br><i class='far fa-arrow-alt-circle-up' title='Repasser en tâche en cours'></i><i class='fas fa-pencil-alt modify' title='Modifier le nom de la tâche'></i><i class='far fa-times-circle delete_task' title='Supprimer la tâche'></i></div>");
                }
            }
        }
    };
    xhttp.open("POST", "task_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("task_name=" + task_name + "&list_name=" + list_name + "&modify=" + new_task_name);
});

// PARTAGER AVEC UN UTILISATEUR A LA LISTE VIA LISTE D'AMI
$("#add_user_via_friends_list_validate").click(function () {

    var friend_info = $(this).parent().children("select").children("option:selected").val();
    var list_name = $("#selected_list_name").text();
    if($("#can_write_radio").is(':checked')){
        var permission = "write";
    }else{permission = "read"};

    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            if(this.responseText.substr(this.responseText.length - 1) !== "."){
                $('#manage_permissions_select').append('<option value="' + friend_info +'">' + friend_info + '</option>');
                if(permission === "read"){
                    $("#add_friend_to_list_response").text("L'utilisateur " + this.responseText + " est maintenant membre de la liste. :D");
                    $("#members").append('<div class=\'member\'><span>' + friend_info + '</span><i class=\'fas fa-eye\' title=\'Peut lire uniquement\'></i></div>')
                }else{
                    $("#add_friend_to_list_response").text("L'utilisateur " + this.responseText + " est maintenant membre de la liste. :D");
                    $("#members").append('<div class=\'member\'><span>' + friend_info + '</span><i class=\'fas fa-pencil-alt\' title=\'Peut lire et écrire\'></i></div>')
                }
            }else{
                $("#add_friend_to_list_response").text(this.responseText);
            }
        }
    };
    xhttp.open("POST", "friend_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("friend_info=" + friend_info + "&share=true&list_name=" + list_name + "&permission_add_user=" + permission);
});

// PARTAGER AVEC UN UTILISATEUR PAS DANS LA LISTE D'AMIS (bien qu'ajouter un directement depuis ici fonctionne également)
$("#add_user_validate").click(function () {

    var friend_info = $("#add_user_input").val();
    var list_name = $("#selected_list_name").text();

    if($("#can_write_radio").is(':checked')){
        var permission = "write";
    }else{permission = "read"};

    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            if(this.responseText.substr(this.responseText.length - 1) !== "."){
                $('#manage_permissions_select').append('<option value="' + this.responseText +'">' + this.responseText + '</option>');
                if(permission === "read"){
                    $("#add_friend_to_list_response").text("L'utilisateur " + this.responseText + " est maintenant membre de la liste. :D");
                    $("#members").append('<div class=\'member\'><span>' + this.responseText + '</span><i class=\'fas fa-eye\' title=\'Peut lire uniquement\'></i></div>')
                }else{
                    $("#add_friend_to_list_response").text("L'utilisateur " + this.responseText + " est maintenant membre de la liste. :D");
                    $("#members").append('<div class=\'member\'><span>' + this.responseText + '</span><i class=\'fas fa-pencil-alt\' title=\'Peut lire et écrire\'></i></div>')
                }
            }else{
                $("#add_friend_to_list_response").text(this.responseText);
            }
        }
    };
    xhttp.open("POST", "friend_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("friend_info=" + friend_info + "&share=true&list_name=" + list_name +"&permission_add_user=" + permission);
})