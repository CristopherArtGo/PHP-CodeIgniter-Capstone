$(document).ready(function () {
    $("body").on("click", ".remove_item", function () {
        $(this).closest("ul").closest("li").addClass("confirm_delete");
        $(".popover_overlay").fadeIn();
        $(".cart_items_form").find("input[name=action]").val("delete_cart_item");
        $(".cart_items_form").find("input[name=update_cart_item_id]").val($(this).val());
    });
    $("body").on("click", ".cancel_remove", function () {
        $(this).closest("li").removeClass("confirm_delete");
        $(".popover_overlay").fadeOut();
        $(".cart_items_form").find("input[name=action]").val("update_cart");
    });
    // /* prototype added delete */
    $("body").on("click", ".remove", function () {
        $("input[name=remove_cart_item_id]").val($(this).val());
        $(".cart_items_form").submit();
    });
    $("body").on("click", ".increase_decrease_quantity", function () {
        let input = $(this).closest(".quantity_element").find("input");
        let input_val = parseInt(input.val());
        if ($(this).attr("data-quantity-ctrl") == 1) {
            input.val(input_val + 1);
        } else {
            if (input_val != 1) {
                input.val(input_val - 1);
            }
        }
        $("input[name=update_cart_item_id]").val($(this).val());
        $("input[name=update_cart_item_quantity]").val(input.val());
        $(".cart_items_form").submit();
    });
    $("body").on("submit", ".cart_items_form", function () {
        let form = $(this);
        $.post(form.attr("action"), form.serialize(), function (res) {
            $(".wrapper > section").html(res);
            $(".popover_overlay").fadeOut();
            $(".billing_info").hide();
        });
        return false;
    });

    $(document).on("click", "input[type=checkbox]", function () {
        $(".billing_info").toggle();
    });

    $(".billing_info").hide();

    $("body").on("submit", ".checkout_form", function () {
        let form = $(this);
        $.post(form.attr("action"), form.serialize(), function (res) {
            if (res == "success") {
                $("#card_details_modal").modal("show");
            } else {
                $(".errors_div").html(res);
            }
        });
        return false;
    });

    var $stripeForm = $(".pay_form");
    $("form.pay_form").bind("submit", function (e) {
        var $stripeForm = $(".pay_form"),
            inputSelector = ["input[type=text]", "input[type=month]", "input[type=number]"].join(", "),
            $inputs = $stripeForm.find(inputSelector),
            $errorMessage = $stripeForm.find("div.error"),
            valid = true;
        $errorMessage.addClass("hide");
        $(".has-error").removeClass("has-error");
        $inputs.each(function (i, el) {
            var $input = $(el);
            if ($input.val() === "") {
                $input.parent().addClass("has-error");
                $errorMessage.removeClass("hide");
                e.preventDefault();
            }
        });
        if (!$stripeForm.data("cc-on-file")) {
            e.preventDefault();
            Stripe.setPublishableKey($stripeForm.data("stripe-publishable-key"));
            Stripe.createToken(
                {
                    number: $(".card_number").val(),
                    cvc: $(".card_cvc").val(),
                    exp_month: $(".card_expiration").val().split("-")[1],
                    exp_year: $(".card_expiration").val().split("-")[0],
                },
                stripeResponseHandler
            );
        }
        return false;
    });
    function stripeResponseHandler(status, res) {
        if (res.error) {
            $(".error").removeClass("hide").find(".alert").text(res.error.message);
        } else {
            var token = res["id"];
            $stripeForm.find("input[type=text]").empty();
            $stripeForm.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $stripeForm.get(0).submit();
        }
    }

    // $("body").on("submit", ".pay_form", function () {
    //     let form = $(this);
    //     $(this).find("button").addClass("loading");
    //     $.post(form.attr("action"), form.serialize(), function (res) {
    //         setTimeout(
    //             function (res) {
    //                 $("#card_details_modal").find("button").removeClass("loading").addClass("success").find("span").text("Payment Successfull!");
    //             },
    //             2000,
    //             res
    //         );
    //         setTimeout(
    //             function (res) {
    //                 $("#card_details_modal").modal("hide");
    //             },
    //             3000,
    //             res
    //         );
    //         setTimeout(
    //             function (res) {
    //                 $(".wrapper > section").html(res);
    //             },
    //             3200,
    //             res
    //         );
    //     });
    //     return false;
    // });
});
