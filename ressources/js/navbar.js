const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : "light";

var actual_theme = currentTheme;


if(actual_theme === "light"){
    $("#darkmode").replaceWith("<i id=\"darkmode\" class=\"fas fa-moon\" title='Passer en thème sombre'></i>")
}else{
    $("#darkmode").replaceWith("<i id=\"darkmode\" class=\"fas fa-sun\" title='Passer en thème clair'></i>")
}

if (currentTheme) {
    document.documentElement.setAttribute('data-theme', currentTheme);
}

$(document).on("click", "#darkmode", function switchTheme() {
    if(actual_theme === "light"){
        actual_theme = "dark";
        $("#darkmode").replaceWith("<i id=\"darkmode\" class=\"fas fa-sun\" title='Passer en thème clair'></i>")
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
    }else{
        actual_theme = "light";
        $("#darkmode").replaceWith("<i id=\"darkmode\" class=\"fas fa-moon\" title='Passer en thème sombre'></i>")
        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light');
    }
});