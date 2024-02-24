<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Products</title>

        <link rel="shortcut icon" href="/assets/images/organic_shop_fav.ico" type="image/x-icon" />

        <script src="/assets/js/vendor/jquery.min.js"></script>
        <script src="/assets/js/vendor/popper.min.js"></script>
        <script src="/assets/js/vendor/bootstrap.min.js"></script>
        <script src="/assets/js/vendor/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="/assets/css/vendor/bootstrap.min.css" />
        <link rel="stylesheet" href="/assets/css/vendor/bootstrap-select.min.css" />

        <link rel="stylesheet" href="/assets/css/custom/global.css" />
        <link rel="stylesheet" href="/assets/css/custom/product_dashboard.css" />
        <script src="/assets/js/global/dashboard.js"></script>
    </head>
    <body>
        <div class="wrapper">
            <header>
                <h1>Let’s order fresh items for you.</h1>
<?php
    if ($userdata)
    {
?>
                <div>
<?php
        if($userdata['is_admin'])
        {
?>
                    <a class="switch" href="/admins/products">Switch to Admin View</a>
<?php
        }
?>
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
                <a href=""><img src="/assets/images/short_logo.png" alt="Mady Bakehouse" /></a>
                <!-- <ul>
                <li class="active"><a href="#"></a></li>
                <li><a href="#"></a></li>
            </ul> -->
            </aside>
            <section>
                <form action="/products/sort_category" method="post" class="search_form">
                    <input type="text" name="search" placeholder="Search Products" id="search_bar" value="<?= $search ?>"/>
                </form>
                <a class="show_cart" href="/products/cart">Cart (<?= count($cart_list) ?>)</a>
                <form action="/products/sort_category" method="post" class="categories_form">
                    <h3>Categories</h3>
                    <ul>
                        <li>
                            <button id="all_products" type="submit" class="active category_button" name="category" value="All">
                                <span><?= $total_products; ?></span><img src="/assets/images/all_products.svg" alt="#" />
                                <h4>All Products</h4>
                            </button>
                        </li>
<?php
    foreach($categories as $category)
    {
?>
                        <li>
                            <button type="submit" class="category_button" name="category" value="<?= $category['category'] ?>" >
                            <!-- <button class="category_button" data_id="<?= $category['category'] ?>" > -->
                                <span><?= $category['product_count'] ?></span><img src="/assets/images/<?= $category['category'] ?>.svg" alt="<?= $category['category'] ?>" />
                                <h4><?= $category['category'] ?></h4>
                            </button>
                        </li>
<?php
    }
?>
                        
                    </ul>
                </form>
                <div id="product_list">
                </div>
            </section>
        </div>
    </body>
</html>
