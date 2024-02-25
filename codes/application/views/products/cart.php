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
        <link rel="stylesheet" href="/assets/css/custom/cart.css" />
        <script src="/assets/js/global/cart.js"></script>
        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    </head>
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
            <?php if($this->session->flashdata('success')){ ?>
						<div class="alert alert-success text-center">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
							<p><?php echo $this->session->flashdata('success'); ?></p>
						</div>
            <?php } ?>
            <section>
                <form class="search_form" action="/products/search_product_from_view" method="post">
                    <input type="text" name="search" placeholder="Search Products" />
                </form>
                <button class="show_cart">Cart (<?= count($cart_items) ?>)</button>
                <section>
                    <form class="cart_items_form" action="/products/update_cart" method="post">
                        <input type="hidden" value="" name="update_cart_item_id">
                        <input type="hidden" value="" name="update_cart_item_quantity">
                        <input type="hidden" value="" name="remove_cart_item_id">
                        <ul>
<?php
    $disable_button = "";
    if(!count($cart_items))
    {
        $disable_button = "disable_button";
?>
                        <p>No items in cart, click <a href="/products">here</a> to add some </p>
<?php
    }
    $total_amount = 0;
    foreach($cart_items as $cart_item)
    {
        $total_amount += number_format($cart_item['quantity'] * $cart_item['price'], 2);
        $main_image = 'short_logo.png';
        if ($cart_item['images'])
        {
            $main_image = $cart_item['product_id']."/".json_decode($cart_item['images'], true)['1'];
        }
?>
                            <li>
                                <img src="/assets/images/products/<?= $main_image ?>" alt="<?= $cart_item['name'] ?>" />
                                <h3><?= $cart_item['name'] ?></h3>
                                <span>$ <?= $cart_item['price'] ?></span>
                                <ul>
                                    <li class="quantity_element">
                                        <label>Quantity</label>
                                        <input type="number" min-value="1" value="<?= $cart_item['quantity'] ?>"  />
                                        <ul>
                                            <li><button type="button" class="increase_decrease_quantity" data-quantity-ctrl="1" value="<?= $cart_item['product_id'] ?>"></button></li>
                                            <li><button type="button" class="increase_decrease_quantity" data-quantity-ctrl="0" value="<?= $cart_item['product_id'] ?>"></button></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <label>Total Amount</label>
                                        <span class="total_amount">$ <?= number_format($cart_item['quantity'] * $cart_item['price'], 2) ?></span>
                                    </li>
                                    <li>
                                        <button type="button" class="remove_item"></button>
                                    </li>
                                </ul>
                                <div>
                                    <p>Are you sure you want to remove this item?</p>
                                    <button type="button" class="cancel_remove">Cancel</button>
                                    <button type="button" class="remove" name="remove" value="<?= $cart_item['product_id'] ?>">Remove</button>
                                </div>
                            </li>
<?php
    }
?>
                        </ul>
                    </form>
                    <form class="checkout_form" action="/products/validate_shipping_details" method="post" >
                        <h3>Shipping Information</h3>
                        <label class="checkbox_label"><input type="checkbox" name="same_billing_info" checked />Use same details for billing</label>
                        <div class="errors_div"></div>
                        <ul>
                            <li>
                                <input type="text" name="first_name" required />
                                <label>First Name</label>
                            </li>
                            <li>
                                <input type="text" name="last_name" required />
                                <label>Last Name</label>
                            </li>
                            <li>
                                <input type="text" name="address_1" required />
                                <label>Address 1</label>
                            </li>
                            <li>
                                <input type="text" name="address_2" required />
                                <label>Address 2</label>
                            </li>
                            <li>
                                <input type="text" name="city" required />
                                <label>City</label>
                            </li>
                            <li>
                                <input type="text" name="state" required />
                                <label>State</label>
                            </li>
                            <li>
                                <input type="text" name="zip_code" required />
                                <label>Zip Code</label>
                            </li>
                        </ul>
                        <div class="billing_info">
                            <h3>Billing Information</h3>
                            <ul>
                                <li>
                                    <input type="text" name="first_name_billing"  />
                                    <label>First Name</label>
                                </li>
                                <li>
                                    <input type="text" name="last_name_billing"  />
                                    <label>Last Name</label>
                                </li>
                                <li>
                                    <input type="text" name="address_1_billing"  />
                                    <label>Address 1</label>
                                </li>
                                <li>
                                    <input type="text" name="address_2_billing"  />
                                    <label>Address 2</label>
                                </li>
                                <li>
                                    <input type="text" name="city_billing"  />
                                    <label>City</label>
                                </li>
                                <li>
                                    <input type="text" name="state_billing"  />
                                    <label>State</label>
                                </li>
                                <li>
                                    <input type="text" name="zip_code_billing"  />
                                    <label>Zip Code</label>
                                </li>
                            </ul>
                        </div>
                        <h3>Order Summary</h3>
                        <h4>Items <span class="order_cost">$ <?= number_format($total_amount, 2) ?></span></h4>
                        <h4>Shipping Fee <span class="shipping_cost">$ 5.00</span></h4>
                        <h4 >Total Amount <span class="total_cost">$ <?= $cart_total ?></span></h4>
                        <button type="submit" class="btn btn-primary <?= $disable_button ?>">Proceed to Checkout</button>
                    </form>
                </section>
            </section>
            <!-- Button trigger modal -->
            <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#card_details_modal">
            Launch demo modal
        </button> -->
            <div class="modal fade form_modal" id="card_details_modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <button data-dismiss="modal" aria-label="Close" class="close_modal"></button>
                        <form class="pay_form" action="/products/handlepayment" method="post" data-stripe-publishable-key="<?= $this->config->item('stripe_key') ?>" data-cc-on-file="false">
                            <h2>Card Details</h2>
                    
                            <ul>
                                <li>
                                    <input type="text" name="card_name" value="test" required />
                                    <label>Card Name</label>
                                </li>
                                <li>
                                    <input type="number" name="card_number" class="card_number" value="4242424242424242" required />
                                    <label>Card Number</label>
                                </li>
                                <li>
                                    <input type="month" name="expiration"  class="card_expiration" value="2025-12" required />
                                    <label>Exp Date</label>
                                </li>
                                <li>
                                    <input type="number" name="cvc" class="card_cvc" value="456" required />
                                    <label>CVC</label>
                                </li>
                            </ul>
                            <h3>Total Amount <span class="total_cost">$ <?= $cart_total ?></span></h3>
                            <button type="submit">Pay</button>
                            <!-- <div class='form-row row'>
								<div class='col-md-12 error form-group hide'>
									<div class='alert-danger alert'>Error occured while making the payment.</div>
								</div>
							</div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="popover_overlay"></div>
    </body>
</html>
