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
        <script src="/assets/js/global/admin_products.js"></script>
    </head>
    <script>
        $(document).ready(function () {
            // $("form").submit(function (event) {
            //     event.preventDefault();
            //     return false;
            // });
            // /* prototype add */
            // $(".switch").click(function () {
            //     window.location.href = "products_dashboard.html";
            // });
        });
    </script>
    <body>
        <div class="wrapper">
            <header>
                <h1>Let’s provide fresh items for everyone.</h1>
                <h2>Products</h2>
                <div>
                    <a class="switch" href="/products">Switch to Shop View</a>
                    <button class="profile">
                        <img src="/assets/images/profile.png" alt="#" />
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
                <a href="#"><img src="/assets/images/main_logo_white.svg" alt="Mady Bakehouse" /></a>
                <ul>
                    <li><a href="admin_orders.html">Orders</a></li>
                    <li class="active"><a href="#">Products</a></li>
                </ul>
            </aside>
            <section>
                <form action="process.php" method="post" class="search_form">
                    <input type="text" name="search" placeholder="Search Products" />
                </form>
                <button class="add_product" data-toggle="modal" data-target="#add_product_modal">Add Product</button>
                <form action="/products/admin_sort" method="post" class="status_form">
                    <h3>Categories</h3>
                    <ul>
                        <li>
                            <button type="submit" class="active">
                                <span><?= $total_products ?></span><img src="/assets/images/all_products.svg" alt="#" />
                                <h4>All Products</h4>
                            </button>
                        </li>
<?php
    foreach($categories as $category)
    {
?>
                        <li>
                            <button type="submit" name="category" value="<?= $category['category'] ?>">
                                <span><?= $category['product_count'] ?></span><img src="/assets/images/<?= $category['category'] ?>.svg" alt="<?= $category['category'] ?>" />
                                <h4><?= $category['category'] ?></h4>
                            </button>
                        </li>
<?php
    }
?>
                    </ul>
                </form>
                <div>
                    <table class="products_table">
                        <thead>
                            <tr>
                                <th><h3>Products(<?= count($products) ?>)</h3></th>
                                <th>ID #</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Inventory</th>
                                <th>Sold</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
<?php
    foreach($products as $product)
    {
        $main_image = 'short_logo.png';
        if ($product['images'])
        {
            $main_image = "products/".$product['id']."/".json_decode($product['images'], true)['1'];
        }
?>
                            <tr>
                                <td>
                                    <span>
                                        <img src="/assets/images/<?= $main_image ?>" alt="#" />
                                        <?= $product['name'] ?>
                                    </span>
                                </td>
                                <td><span><?= $product['id'] ?></span></td>
                                <td><span>$ <?= $product['price'] ?></span></td>
                                <td><span></span></td>
                                <td><span><?= $product['stock'] ?></span></td>
                                <td><span><?= $product['sold'] ?></span></td>
                                <td>
                                    <span>
                                        <button class="edit_product">Edit</button>
                                        <button class="delete_product">X</button>
                                    </span>
                                    <form class="delete_product_form" action="process.php" method="post">
                                        <p>Are you sure you want to remove this item?</p>
                                        <button type="button" class="cancel_remove">Cancel</button>
                                        <button type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
<?php
    }
?>
                        </tbody>
                    </table>
                </div>
            </section>
            <div class="modal fade form_modal" id="add_product_modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <button data-dismiss="modal" aria-label="Close" class="close_modal"></button>
                        <form class="delete_product_form" action="process.php" method="post">
                            <h2>Add a Product</h2>
                            <ul>
                                <li>
                                    <input type="text" name="prouct_name" required />
                                    <label>Product Name</label>
                                </li>
                                <li>
                                    <textarea name="description" required></textarea>
                                    <label>Description</label>
                                </li>
                                <li>
                                    <label>Category</label>
                                    <select class="selectpicker">
                                        <option>Cookie</option>
                                        <option>Brownie</option>
                                        <option>Bread</option>
                                        <option>Cake</option>
                                        <option>Pastry</option>
                                    </select>
                                </li>
                                <li>
                                    <input type="number" name="price" value="1" required />
                                    <label>Price</label>
                                </li>
                                <li>
                                    <input type="number" name="inventory" value="1" required />
                                    <label>Inventory</label>
                                </li>
                                <li>
                                    <label>Upload Images (5 Max)</label>
                                    <ul>
                                        <li><button type="button" class="upload_image"></button></li>
                                    </ul>
                                    <input type="file" name="image" accept="image/*" />
                                </li>
                            </ul>
                            <button type="button" data-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="popover_overlay"></div>
    </body>
</html>
