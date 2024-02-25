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

    $(document).on("click", ".view_order", function () {
        $("#order_details").modal("show");
        let order_id = $(this).text();
        let post = $(this).serializeArray();
        post.push({ name: "order_id", value: order_id });

        $.post("/admins/get_order_items", post, function (res) {
            $(".order_items").html(res);
        });        
    });

});
