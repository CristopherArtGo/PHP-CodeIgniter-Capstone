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
                <a href="/products/view_product/<?= $product['id'] ?>">
                <img src="/assets/images/<?= $main_image ?>" alt="<?= $product['name'] ?>" /></a>
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
                <button class="edit_product" value="<?= $product['id'] ?>">Edit</button>
                <button class="delete_product">X</button>
            </span>
            <form class="delete_product_form" action="/products/delete/" method="post">
                <p>Are you sure you want to remove this item?</p>
                <button type="button" class="cancel_remove">Cancel</button>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <button type="submit" >Remove</button>
            </form>
        </td>
    </tr>
<?php
    }
?>
