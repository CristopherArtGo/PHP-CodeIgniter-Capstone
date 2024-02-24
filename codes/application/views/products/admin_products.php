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
                <form action="/products/admin_sort_category" method="post" class="search_form">
                    <input type="text" name="search" placeholder="Search Products" id="search_bar" />
                </form>
                <button class="add_product" data-toggle="modal" data-target="#add_product_modal">Add Product</button>
                <form action="/products/admin_sort_category" method="post" class="status_form">
                    <h3>Categories</h3>
                    <ul>
                        <li>
                            <button id="all_products" type="submit" class="active category_button" name="category" value="All">
                                <span><?= $total_products ?></span><img src="/assets/images/all_products.svg" alt="#" />
                                <h4>All Products</h4>
                            </button>
                        </li>
<?php
    foreach($categories as $category)
    {
?>
                        <li>
                            <button class="category_button" type="submit" name="category" value="<?= $category['category'] ?>">
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
                        </tbody>
                    </table>
                </div>
            </section>
            <div class="modal fade form_modal" id="add_product_modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <button data-dismiss="modal" aria-label="Close" class="close_modal"></button>
                        <form class="add_product_form" action="/products/add_product" method="post" enctype="multipart/form-data">
                            <h2>Add a Product</h2>
                            <p class="errors"></p>
                            <ul>
                                <li>
                                    <input type="text" name="product_name" required />
                                    <label>Product Name</label>
                                </li>
                                <li>
                                    <textarea name="description" required></textarea>
                                    <label>Description</label>
                                </li>
                                <li>
                                    <label>Category</label>
                                    <select class="selectpicker" name="category">
<?php
    foreach($categories as $category)
    {
?>
                                        <option value="<?= $category['category_id'] ?>" ><?= $category['category'] ?></option>

<?php
    }
?>
                                    </select>
                                </li>
                                <li>
                                    <input type="text" name="price" value="1" required/>
                                    <label>Price</label>
                                </li>
                                <li>
                                    <input type="number" name="inventory" value="1" required />
                                    <label>Inventory</label>
                                </li>
                                <li>
                                    <label class="image_label">Upload Images (4 Max)</label>
                                    <ul>
                                        <li><button type="button" class="upload_image"></button></li>
                                    </ul>
                                    <ul class="image_preview_list">
                                    </ul>
                                    <input class="image_input" type="file" required name="image[]" accept="image/*"  multiple />
                                    <input type="hidden" class="form_data_action" name="action" value="reset_form">
                                    <input type="hidden" name="image_index" value="">
                                </li>
                            </ul>
                            <button type="button" data-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit">Save</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade form_modal" id="edit_product_modal" tabindex="-1" aria-hidden="true">
            </div>
        </div>
        <div class="popover_overlay"></div>
    </body>
</html>
