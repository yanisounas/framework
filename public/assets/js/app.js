$(document).ready(function() {
    let active = $(".home");

    $(".menu_list__item").click(function () {
        $(".active").toggleClass("active");
        $(this).toggleClass("active");

        active.css("display", "none");
        active = $("." + $(this).attr("id"));
        active.css("display", "block");

        document.title = "URLShortener | " + $(this).text();
    });
});