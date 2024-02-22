<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Products</title>

        <script src="/assets/js/vendor/jquery.min.js"></script>
        <script src="/assets/js/vendor/popper.min.js"></script>
        <script src="/assets/js/vendor/bootstrap.min.js"></script>
        <script src="/assets/js/vendor/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="/assets/css/vendor/bootstrap.min.css" />
        <link rel="stylesheet" href="/assets/css/vendor/bootstrap-select.min.css" />

        <link rel="stylesheet" href="/assets/css/custom/global.css" />
        <link rel="stylesheet" href="/assets/css/custom/product_view.css" />
    </head>

    <script>
        $(document).ready(function () {
            $("#add_to_cart").click(function () {
                $('#add_to_cart_form').submit();
                $("<span class='added_to_cart'>Added to cart succesfully!</span>")
                    .insertAfter(this)
                    .fadeIn()
                    .delay(1000)
                    .fadeOut(function () {
                        $(this).remove();
                    });
                return false;
            });

            $(document).on('submit', 'form', function() {
                $.post($(this).attr('action'), $(this).serialize(), function(res){
                    $('.show_cart').text(res);
                });
                return false;
            });

            $('.show_image').click(function () {
                $('.show_image').parent().removeClass('active');
                $(this).parent().addClass('active');
                $('#image_shown').attr('src', $(this).find('img').attr('src'));
            });

            $('.change_quantity > li > button').on('click', function() {
                let new_quantity = +$('#quantity').val() + +$(this).attr('data-quantity-ctrl');
                if (new_quantity < 1)
                {
                    new_quantity = 1;
                }
                $('#quantity').val(new_quantity);
                $('.total_amount').text("$ " + (new_quantity * <?= $product['price'] ?>).toFixed(2));
            });

            $('#quantity').on('change', function() {
                $('.total_amount').text("$ " + (+$('#quantity').val() * <?= $product['price'] ?>).toFixed(2));
                if (!$(this).val() || $(this).val() == "")
                {
                    $(this).val(1);
                    $('.total_amount').text("$ " + (+$('#quantity').val() * <?= $product['price'] ?>).toFixed(2));
                }
            });

            $('#quantity').on('keyup', function() {
                $('.total_amount').text("$ " + (+$('#quantity').val() * <?= $product['price'] ?>).toFixed(2));
            });
                
            
        });
    </script>
    <body>
        <div class="wrapper">
            <header>
                <h1>Let’s order fresh items for you.</h1>
<?php
    if ($userdata)
    {
?>
                <div>
                    <button class="profile">
                        <img src="/assets/images/profile.png" alt="<?= $userdata['first_name'] ?>" />
                    </button>
                </div>
                <div class="dropdown show">
                    <a class="btn btn-secondary dropdown-toggle profile_dropdown" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                    <div class="dropdown-menu admin_dropdown" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="/users/logout">Logout</a>
                    </div>
                </div>
<?php
    }
    else
    {
?>
                <div>
                            <a class="signup_btn" href="/users/signup">Signup</a>
                            <a class="login_btn" href="/users">Login</a>
                </div>
<?php
    }
?>
            </header>
            <aside>
                <a href="/products"><img src="/assets/images/short_logo.png" alt="Mady Bakehouse" /></a>
                <!-- <ul>
                <li class="active"><a href="#"></a></li>
                <li><a href="#"></a></li>
            </ul> -->
            </aside>
            <section>
                <form action="/products" method="post" class="search_form">
                    <input type="text" name="search" placeholder="Search Products" />
                </form>
                <a class="show_cart" href="/products/cart">Cart (<?= count($cart_items) ?>)</a>
                <a href="/products">Go Back</a>
                <ul>
                    <li>
<?php
    $main_image = 'short_logo.png';
    if ($product['images'])
    {
        $images = json_decode($product['images'], true);
        $main_image = $product['id']."/".$images['1'];
    }
?>
                        <img id="image_shown" src="/assets/images/products/<?= $main_image ?>" alt="<?= $product['name'] ?>" />
                        <ul>
                            <li class="active">
                                <button class="show_image"><img src="/assets/images/products/<?= $main_image ?>" alt="<?= $product['name'] ?> 1" /></button>
                            </li>

<?php
    if ($product['images'])
    {
        for($i = 2; $i <= count($images); $i++)
        {
?>
                            <li>
                                <button class="show_image"><img src="/assets/images/products/<?= $product['id'] ?>/<?= $images[$i] ?>" alt="<?= $product['name'] ?> <?= $i ?>" /></button>
                            </li>
<?php
        }
    }
?>
                        </ul>
                    </li>
                    <li>
                        <h2><?= $product['name'] ?></h2>
                        <ul class="rating">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                        <span>36 Rating</span>
                        <span class="amount">$ <?= $product['price'] ?></span>
                        <p>Lorem ipsum dolor sit amet consectetur. Eget sit posuere enim facilisi. Pretium orci venenatis habitasse gravida nulla tincidunt iaculis. Aliquet at massa quisque libero viverra ut sed. Est vulputate est rutrum nunc nunc pellentesque ultrices pharetra. Mauris euismod sed vel quisque tincidunt suspendisse sed turpis volutpat.</p>
                        <form action="/products/add_to_cart" method="post" id="add_to_cart_form">
                            <ul>
                                <li>
                                    <label>Quantity</label>
                                    <input id="quantity" name="quantity" type="number" min-value="1" value="1" />
                                    <ul class="change_quantity">
                                        <li><button type="button" class="increase_decrease_quantity" data-quantity-ctrl="1"></button></li>
                                        <li><button type="button" class="increase_decrease_quantity" data-quantity-ctrl="-1"></button></li>
                                    </ul>
                                </li>
                                <li>
                                    <label>Total Amount</label>
                                    <span class="total_amount">$ <?= $product['price'] ?></span>
                                </li>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <li><button type="submit" id="add_to_cart">Add to Cart</button></li>
                            </ul>
                        </form>
                    </li>
                </ul>
                <section>
                    <h3>Similar Items</h3>
                    <ul>
<?php
    foreach($similar_products as $similar_product)
    {
?>
                        <li>
                            <a href="/products/view_product/<?= $similar_product['id'] ?>">
                                <img src="/assets/images/products/<?= $similar_product['id']."/".json_decode($similar_product['images'], true)[1] ?>" alt="<?= $similar_product['name'] ?>" />
                                <h3><?= $similar_product['name'] ?></h3>
                                <ul class="rating">
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                </ul>
                                <span>36 Rating</span>
                                <span class="price">$ <?= $similar_product['price'] ?></span>
                            </a>
                        </li>
<?php
    }
?>
                    </ul>
                </section>
            </section>
        </div>
    </body>
</html>
