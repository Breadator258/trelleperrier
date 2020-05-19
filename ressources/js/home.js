$(document).on('click', ".delete_list", function () {
    $("#main_modal").show();
    $("#leave_list_form").hide();
    $("#remove_list_form").show();
    $("#modal-navbar").children("li").hide();
    $("#list_name_to_remove").text($(this).parent().parent().text());
});

$(document).on('click', ".leave_list", function () {
    $("#main_modal").show();
    $("#leave_list_form").show();
    $("#remove_list_form").hide();
    $("#modal-navbar").children("li").hide();
    $("#list_name_to_leave").text($(this).parent().parent().text());
});

// SUPPRIMER LISTE
$("#remove_list_validate").click(function () {
    var list_name = $("#list_name_to_remove").text().replace(/ /g, "%20");

    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            $(".list:contains('" + list_name.replace(/%20/g, ' ') + "')").filter(function() { return $(this).text() === list_name.replace(/%20/g, ' ');}).remove();
            $(".close").click();
        }
    };
    xhttp.open("POST", "list_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("list_name=" + list_name + "&remove=true");
});

// QUITTER LISTE
$("#leave_list_validate").click(function () {
    var list_name = $("#list_name_to_leave").text().replace(/ /g, "%20");

    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            $(".list:contains('" + list_name.replace(/%20/g, ' ') + "')").filter(function() { return $(this).text() === list_name.replace(/%20/g, ' ');}).remove();
            alert(this.responseText);
            $(".close").click();
        }
    };
    xhttp.open("POST", "list_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("list_name=" + list_name + "&leave=true");
});

// ACCEDER A LA LISTE
$(document).on("click", ".access_list", function (e) {
    var txt =  $(e.target).text();
    $("#selected_list").val(txt);
    $("#selected_list_form").submit();
});

// FORM AJOUTER LISTE
$("#add_todo_list").click(function () {
    $("#main_modal").show();
    $("#modal-navbar").children("li").hide();
    $("#add_list_form").show();
});

// CREER LISTE
$("#add_list_button").click(function () {
    if (!$("#new_list_name").val()) {
        alert("Really ?");
        return;
    }else if($("#new_list_name").val().length > 70){
        alert("Le titre de la liste dépasse les 70 caractères autorisés.")
        return;
    }else{
        var original_list_name = $("#new_list_name").val();
        var list_name = $("#new_list_name").val().replace(/ /g, '%20');
        $("#new_list_name").val("");
    }
    if (window.XMLHttpRequest) {
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange=function() {
        if (this.readyState===4 && this.status===200) {
            if(this.responseText.length === 0){
                $("#lists").append("<div class='list'><span><span class='list-i-container'><i class=\"fas fa-crown\" title='Vous êtes le propriétaire de cette liste'></i></span><span class='access_list' >"+ original_list_name +"</span></span><span class='list-i-container'><i class=\"fas fa-user\" title='Liste non partagée'></i><i class=\"far fa-times-circle delete_list\" title='Supprimer la liste'></i></span></div>");
                $("#add_list_reponse").text("La liste " + original_list_name + " a été créée." );
            }else{
                $("#add_list_reponse").text(this.responseText);
            }
        }
    };
    xhttp.open("POST", "list_treatment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("list_name=" + list_name + "&is_new_list=true");
});