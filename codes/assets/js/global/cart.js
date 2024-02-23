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
        // $(this).closest("li.confirm_delete").remove();
        // $(".popover_overlay").fadeOut();
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
            $(".total_cost").text("$ " + (+$(".order_cost").text().substring(2) + +$(".shipping_cost").text().substring(2)).toFixed(2));
            $(".billing_info").hide();
        });
        return false;
    });

    $(".total_cost").text("$ " + (+$(".order_cost").text().substring(2) + +$(".shipping_cost").text().substring(2)).toFixed(2));

    $(document).on("click", "input[type=checkbox]", function () {
        $(".billing_info").toggle();
    });

    $(".billing_info").hide();

    // $("body").on("submit", ".checkout_form", function () {
    //     let form = $(this);
    //     $.post(form.attr("action"), form.serialize(), function (res) {
    //         $(".wrapper > section").html(res);
    //         $("#card_details_modal").modal("show");
    //     });
    //     return false;
    // });
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
