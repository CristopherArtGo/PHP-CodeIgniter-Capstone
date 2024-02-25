$(document).ready(function () {
    $(document).on("click", ".status_button", function () {
        $(".status_button").removeClass("active");
        $(this).addClass("active");

        let status = $(this).attr("value");
        let search = $("#search_bar").val();
        let post = $(this).serializeArray();
        post.push({ name: "status", value: status }, { name: "search", value: search });

        sort_status(post);
        return false;
    });

    // auto submits when user is typing in search bar
    $("#search_bar").on("keyup", function () {
        $(".status_button.active").click();
    });

    //auto clicking all orders for first page load
    $("#all_orders").click();

    function sort_status(post) {
        $.post("/admins/sort_status", post, function (res) {
            $(".orders_table").html(res);
            $(".selectpicker").selectpicker("refresh");
        });
    }

    $(document).on("change", "select", function () {
        $(this).closest("form").submit();
    });

    $(document).on("submit", ".status_update_form", function () {
        $.post($(this).attr("action"), $(this).serialize(), function (res) {
            // console.log(res);
        });
        // $(".status_button.active").click();
        return false;
    });

    // $("body").on("change", ".status_selectpicker", function() {
    //     $(this).closest("form").find("input[name=status_id]").val($(this).val());
    //     $(this).closest("form").trigger("submit");
    // });

    // $("body").on("submit", ".update_status_form", function() {
    //     let form = $(this);
    //     $.post(form.attr("action"), form.serialize(), function(res) {
    //         $(".wrapper > section").html(res);
    //         $(".selectpicker").selectpicker("refresh");
    //     });

    //     return false;
    // });

    // $("body").on("click", ".status_form button", function() {
    //     let button = $(this);
    //     $(".status_form").find("input[name=status_id]").val(button.val());
    //     $(".status_form").find(".active").removeClass("active");
    //     button.addClass("active");

    // })

    // $("body").on("submit", ".status_form", function() {
    //     let form = $(this);
    //     $.post(form.attr("action"), form.serialize(), function(res) {
    //         $("tbody").html(res);
    //         $(".selectpicker").selectpicker("refresh");
    //     });
    //     return false;
    // });
});
