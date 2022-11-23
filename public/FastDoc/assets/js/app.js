$(document).ready(function() {
    $(".endpoint-header").click(function () {
        let body = $(this).next();
        (body.css("display") === "none") ? body.css("display", "flex") : body.hide();
    })
});