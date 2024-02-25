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
    <script>
        // $(document).ready(function () {
        //     $(".profile_dropdown").on("click", function () {
        //         let newTop = $(this).offset().top + $(this).outerHeight();
        //         let newLeft = $(this).offset().left;

        //         $(".admin_dropdown").css({
        //             top: newTop + "px",
        //             left: newLeft + "px",
        //         });
        //     });
        // });
    </script>
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
            <section>
                <form action="/admins/sort_status" method="post" class="search_form">
                    <input id="search_bar" type="text" name="search" placeholder="Search Orders" />
                </form>
                <form action="admins/sort_status" method="post" class="status_form">
                    <h3>Status</h3>
                    <ul>
                        <li>
                            <button class="status_button" type="submit" class="active" name="status" value="All" id="all_orders">
                                <span>36</span><img src="/assets/images/all_orders_icon.svg" alt="#" />
                                <h4>All Orders</h4>
                            </button>
                        </li>
                        <li>
                        <button class="status_button" type="submit" name="status" value="Pending">
                                <span>36</span><img src="/assets/images/pending_icon.svg" alt="#" />
                                <h4>Pending</h4>
                            </button>
                        </li>
                        <li>
                        <button class="status_button" type="submit" name="status" value="On-Process">
                                <span>36</span><img src="/assets/images/on_process_icon.svg" alt="#" />
                                <h4>On-Process</h4>
                            </button>
                        </li>
                        <li>
                        <button class="status_button" type="submit" name="status" value="Shipped">
                                <span>36</span><img src="/assets/images/shipped_icon.svg" alt="#" />
                                <h4>Shipped</h4>
                            </button>
                        </li>
                        <li>
                        <button class="status_button" type="submit" name="status" value="Delivered">
                                <span>36</span><img src="/assets/images/delivered_icon.svg" alt="#" />
                                <h4>Delivered</h4>
                            </button>
                        </li>
                    </ul>
                </form>
                <div >
                    <h3>Orders (<?= count($orders) ?>)</h3>
                    <table class="orders_table">
                    </table>
                </div>
            </section>
        </div>
    </body>
</html>
