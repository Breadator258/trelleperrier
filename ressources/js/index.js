const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : "light";

if (currentTheme) {
    document.documentElement.setAttribute('data-theme', currentTheme);
}

var actual_theme = currentTheme;
$("#darkmode").click(function switchTheme() {
    if(actual_theme === "light"){
        actual_theme = "dark";
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
    }else{
        actual_theme = "light";
        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light');
    }
});

$(document).ready(function(){
    $( "#span" ).click(function() {
        if($("#span").html() === "Créez en un !"){
            $("h2").stop().animate({opacity: '0'});
            $("#signin-form").stop().animate({opacity: '0'});
            $("#footer-p").stop().animate({opacity: '0'});
            $("#span").stop().animate({opacity: '0'});
            $("button").stop().animate({opacity: '0'});
            function hide(){
                $( "#signin-form" ).hide();
                $("button").html("Inscription").stop().animate({opacity: '1'});
                $( "#footer-p" ).replaceWith("<p id=\"footer-p\" class=\"font\" style=\"display: inline-block\">Déjà inscrit ?&nbsp;</p>").stop().animate({opacity: '1'});
                $( "#span" ).html("Connectez-vous !").stop().animate({opacity: '1'});
                $( "#signup-form" ).show().stop().animate({opacity: '1'});
                $("h2").stop().animate({opacity: '1'}).text("Page d'inscription");
            }
            setTimeout(hide, 750);
        }else{
            $("h2").stop().animate({opacity: '0'});
            $("#signup-form").stop().animate({opacity: '0'});
            $("#footer-p").stop().animate({opacity: '0'});
            $("#span").stop().animate({opacity: '0'});
            $("button").stop().animate({opacity: '0'});
            function hide(){
                $("#signup-form").hide();
                $("button").html("Connexion").stop().animate({opacity: '1'});
                $("#footer-p").replaceWith("<p id=\"footer-p\" class=\"font\" style=\"display: inline-block\">Pas de compte ?&nbsp;</p>").stop().animate({opacity: '1'});
                $("#span").html("Créez en un !").stop().animate({opacity: '1'});
                $("#signin-form").show().stop().animate({opacity: '1'});
                $("h2").stop().animate({opacity: '1'}).text("Page de connexion");
            }
            setTimeout(hide, 750);
        }

    });
    $("button").click(function () {
        if($("button").html() === "Connexion"){
            $("#signin-form").submit();
        }else{
            if($("#signup-mail").val() === "" || $("#signup-password").val() === "" || $("#signup-username").val() === ""){
                alert("Champs incomplets.");
                return;
            }
            $("#signup-form").submit();
        }
    });
});