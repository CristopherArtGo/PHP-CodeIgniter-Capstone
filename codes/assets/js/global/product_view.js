$(document).ready(function () {
    $("#add_to_cart").click(function () {
        $("#add_to_cart_form").submit();
        $("<span class='added_to_cart'>Added to cart succesfully!</span>")
            .insertAfter(this)
            .fadeIn()
            .delay(200)
            .fadeOut(function () {
                $(this).remove();
            });
        return false;
    });

    $(document).on("submit", "#add_to_cart_form", function () {
        $.post($(this).attr("action"), $(this).serialize(), function (res) {
            $(".show_cart").text(res);
        });
        return false;
    });

    $(".show_image").click(function () {
        $(".show_image").parent().removeClass("active");
        $(this).parent().addClass("active");
        $("#image_shown").attr("src", $(this).find("img").attr("src"));
    });

    $(".change_quantity > li > button").on("click", function () {
        let new_quantity = +$("#quantity").val() + +$(this).attr("data-quantity-ctrl");
        if (new_quantity < 1) {
            new_quantity = 1;
        }
        $("#quantity").val(new_quantity);
        $(".total_amount").text("$ " + (new_quantity * +$(".amount").text().substring(2)).toFixed(2));
    });

    $("#quantity").on("change", function () {
        $(".total_amount").text("$ " + (+$("#quantity").val() * +$(".amount").text().substring(2)).toFixed(2));
        if (!$(this).val() || $(this).val() == "" || $(this).val() < 1) {
            $(this).val(1);
            $(".total_amount").text("$ " + (+$("#quantity").val() * +$(".amount").text().substring(2)).toFixed(2));
        }
    });

    $("#quantity").on("keyup", function () {
        $(".total_amount").text("$ " + (+$("#quantity").val() * +$(".amount").text().substring(2)).toFixed(2));
    });
});
