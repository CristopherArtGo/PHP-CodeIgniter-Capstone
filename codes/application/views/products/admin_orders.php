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

        <link rel="stylesheet" href="/assets/css/custom/admin_global.css" />
        <script src="/assets/js/global/admin_orders.js"></script>
    </head>
    <body>
        <div class="wrapper">
            <header>
                <h1>Let’s provide fresh items for everyone.</h1>
                <h2>Orders</h2>
                <div>
                    <a class="switch" href="/products">Switch to Shop View</a>
                    <button class="profile">
                        <img src="/assets/images/profile.png" alt="<?= $userdata['first_name'] ?>" />
                    </button>
                </div>
                <div class="dropdown show">
                    <a class="btn btn-secondary dropdown-toggle profile_dropdown" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                    <div class="dropdown-menu admin_dropdown" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="login.html">Logout</a>
                    </div>
                </div>
            </header>
            <aside>
                <a href=""><img src="/assets/images/main_logo_white.svg" alt="Mady Bakehouse" /></a>
                <ul>
                    <li class="active"><a href="">Orders</a></li>
                    <li><a href="/admins/products">Products</a></li>
                </ul>
            </aside>
            <section class="main">
                <form action="/admins/sort_status" method="post" class="search_form">
                    <input id="search_bar" type="text" name="search" placeholder="Search Orders" />
                </form>
                <form action="admins/sort_status" method="post" class="status_form">
                    <h3>Status</h3>
                    <ul>
                        <li>
<?php
        $order_count = 0;
        foreach($statuses as $status)
        {
            $order_count += $status['order_count'];
        }
?>  
                            <button class="status_button" type="submit" class="active" name="status" value="All" id="all_orders">
                                <span><?= $order_count ?></span><img src="/assets/images/all_orders_icon.svg" alt="#" />
                                <h4>All Orders</h4>
                            </button>
                        </li>
<?php
    foreach($statuses as $status)
    {
?>      
                        <li>
                        <button class="status_button" type="submit" name="status" value="<?= $status['status_id'] ?>>">
                                <span><?= $status['order_count'] ?></span><img src="/assets/images/<?= $status['status'] ?>_icon.svg" alt="<?= $status['status'] ?>" />
                                <h4><?= $status['status'] ?></h4>
                            </button>
                        </li>
<?php
    }
?>
                    </ul>
                </form>
                <div>
                    <h3>Orders (<?= count($orders) ?>)</h3>
                    <table class="orders_table">
                    </table>
                </div>
            </section>
        </div>
    </body>
</html>
