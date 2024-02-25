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
    <form class="checkout_form">
        <h3>Shipping Information</h3>
        <label class="checkbox_label"><input type="checkbox" name="same_billing_info" checked />Use same details for billing</label>
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
        </div>
        <h3>Order Summary</h3>
        <h4>Items <span class="order_cost">$ <?= number_format($total_amount, 2) ?></span></h4>
        <h4>Shipping Fee <span class="shipping_cost">$ 5.00</span></h4>
        <h4 >Total Amount <span class="total_cost">$ <?= $total ?></span></h4>
        <button type="button" class="btn btn-primary <?= $disable_button ?>" data-toggle="modal" data-target="#card_details_modal">Proceed to Checkout</button>
    </form>
</section>