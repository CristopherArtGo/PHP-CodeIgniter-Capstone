$(document).ready(function () {
    $("form").submit(function () {
        $.post($(this).attr("action"), $(this).serialize(), function (res) {
            if (res != "success") {
                $(".error_div").html(res);
            } else {
                console.log(res);
                window.location.href = "/products";
            }
        });
        return false;
    });
});
