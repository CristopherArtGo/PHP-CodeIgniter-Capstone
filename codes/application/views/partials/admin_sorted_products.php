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
        <td><span><?= $product['category'] ?></span></td>
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
