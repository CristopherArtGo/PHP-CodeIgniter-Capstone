<?php
    foreach($order_items as $order_item)
    {
?>
<ul>
    <li>Product Name: <?= $order_item['name'] ?></li>
    <li>Quantity: <?= $order_item['quantity'] ?></li>
</ul>
<?php
    }
?>