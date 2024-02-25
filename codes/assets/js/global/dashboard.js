$(document).ready(function () {
    // AJAX for category buttons
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
        $.post("/products/sort_category", post, function (res) {
            $("#product_list").html(res);
        });
    }
});

// $(document).ready(function() {
//     $("body").on("click", ".categories_form button", function() {
//         let button = $(this);
//         let form = button.closest("form");

//         form.find("input[name=category]").val(button.attr("data-category"));
//         form.find("input[name=category_name]").val(button.attr("data-category-name"));
//         button.closest("ul").find(".active").removeClass("active");
//         button.addClass("active");

//         filterProducts(form);

//         return false;
//     });

//     $("body").on("keyup", ".search_form", function() {
//         let form = $(this);
//         filterProducts(form);
//         $(".categories_form").find(".active").removeClass("active");
//         return false;
//     });
// })

// /* Ajax to filter products */
// function filterProducts(form) {
//     $.post(form.attr("action"), form.serialize(), function(res) {
//         $(".products_container").html(res);
//         console.log(res);
//     });
// }
