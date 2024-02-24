$(document).ready(function () {
    $(document).on("click", ".category_button", function () {
        $(".category_button").removeClass("active");
        $(this).addClass("active");

        let category = $(this).attr("value");
        let search = $("#search_bar").val();
        let post = $(this).serializeArray();
        post.push({ name: "category", value: category }, { name: "search", value: search });

        sort_products(post);
        return false;
    });

    //AJAX for search form
    $(document).on("submit", ".search_form", function () {
        sort_products($(this).serialize());
        return false;
    });

    // auto submits when user is typing in search bar
    $("#search_bar").on("keyup", function () {
        $(this).parent().submit();
    });

    //auto clicking all products for first page load
    $("#all_products").click();

    function sort_products(post) {
        $.post("/admins/sort_category", post, function (res) {
            $(".products_table").html(res);
        });
    }
    /* To delete a product */
    $("body").on("click", ".delete_product", function () {
        $(this).closest("tr").addClass("show_delete");
        $(".popover_overlay").fadeIn();
        $("body").addClass("show_popover_overlay");
    });

    /* To cancel delete */
    $("body").on("click", ".cancel_remove", function () {
        $(this).closest("tr").removeClass("show_delete");
        $(".popover_overlay").fadeOut();
        $("body").removeClass("show_popover_overlay");
    });

    /* To trigger input file */
    $("body").on("click", ".upload_image", function () {
        $(".image_input").trigger("click");
    });

    /* To trigger image upload */
    $("body").on("change", ".image_input", function () {
        $(".form_data_action").val("upload_image");
        $(".add_product_form").trigger("submit");
        // console.log("images uploaded");
    });

    $("body").on("click", "button[type=submit]", function () {
        $(".form_data_action").val("add_product");
        if ($(".image_preview_list").children().length == 0) {
            $(".image_label").html("Upload Images (4 Max) <span>* Please add an image.</span>");
        }
        // $(".add_product_form").trigger("submit");
    });

    /* To delete an image */
    $("body").on("click", ".delete_image", function () {
        $("input[name=image_index]").val($(this).attr("data-image-index"));
        $(".form_data_action").val("remove_image");
        $(".add_product_form").trigger("submit");
        // console.log("image removed");
    });

    /*  */
    $("body").on("change", "input[name=main_image]", function () {
        $("input[name=image_index]").val($(this).val());
        $(".form_data_action").val("mark_as_main");
        $(".add_product_form").trigger("submit");
        $("input[name=main_image]").prop("checked", false);
        $(this).prop("checked", true);
        // console.log("main image changed");
    });

    $("body").on("hidden.bs.modal", "#add_product_modal", function () {
        $(".form_data_action").val("reset_form");
        $(".add_product_form").trigger("submit");
        $(".add_product_form").attr("data-modal-action", 0);
        // $(".form_data_action").find("textarea").addClass("jhaver");
    });

    $("body").on("submit", ".add_product_form", function () {
        $.ajax({
            url: $(this).attr("action"),
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                let form_data_action = $(".form_data_action").val();

                if (form_data_action == "add_product" || form_data_action == "edit_product") {
                    if (typeof parseInt(res) == "number") {
                        // $(".product_content").html(res);
                        $(".close_modal").click();
                        resetAddProductForm();
                        $(".category_button.active").click();
                        // $("#add_product_modal").modal("hide");
                    } else {
                        $(".errors").html(res);
                        if ($(".image_preview_list").children().length == 0) {
                            $(".image_label").html("Upload Images (4 Max) <span>* Please add an image.</span>");
                        } else {
                            $(".image_label").html("Upload Images (4 Max)");
                        }
                    }
                } else if (form_data_action == "upload_image" || form_data_action == "remove_image") {
                    $(".image_preview_list").html(res);
                } else if (form_data_action == "reset_form") {
                    resetAddProductForm();
                }
                // $(".add_product_form").attr("data-modal-action") == 0 ? $(".form_data_action").val("add_product") : $(".form_data_action").val("edit_product");
                $(".image_preview_list").children().length >= 4 ? $(".upload_image").addClass("hidden") : $(".upload_image").removeClass("hidden");
            },
            error: (error) => {
                console.log(JSON.stringify(error));
            },
        });

        return false;
    });

    $("body").on("submit", ".delete_product_form", function () {
        $.post($(this).attr("action"), $(this).serialize());
        $("body").removeClass("show_popover_overlay");
        $(".popover_overlay").fadeOut();
        $(".category_button.active").click();
        return false;
    });

    $("body").on("click", ".edit_product", function () {
        let product_id = $(this).attr("value");
        let post = $(this).serializeArray();
        post.push({ name: "product_id", value: product_id });

        $.post("/admins/product_details", post, function (res) {
            $("#edit_product_modal").html(res);
            $(".selectpicker").selectpicker("refresh");
        });

        $("input[name=edit_product_id]").val($(this).val());
        $("#edit_product_modal").modal("show");
        $(".form_data_action").val("edit_product");
        // $(".add_product_form").attr("data-modal-action", 1);
        return false;
    });

    $("body").on("submit", ".get_edit_data_form", function () {
        let form = $(this);
        $.post(form.attr("action"), form.serialize(), function (res) {
            $(".add_product_form").find(".form_control").html(res);
            $(".selectpicker").selectpicker("refresh");
        });

        return false;
    });

    function resetAddProductForm() {
        $(".add_product_form").find("textarea, input[name=product_name], input[name=price], input[name=inventory]").attr("value", "").text("");
        $(".add_product_form").find("input[name=price], input[name=inventory]").attr("value", "1");
        $("select[name=categories]").find("option").removeAttr("selected").closest("select").val("1").selectpicker("refresh");
        $(".add_product_form")[0].reset();
        $(".image_label").find("span").remove();
        $(".image_preview_list").children().remove();
        $("#add_product_modal").find("h2").text("Add a Product");
    }
});
