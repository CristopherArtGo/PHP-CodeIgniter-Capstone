<h3>All Products(<?= count($products) ?>)</h3>
    <ul>
<?php
    foreach($products as $product)
    {
        $main_image = 'short_logo.png';
        if ($product['images'])
        {
            $main_image = $product['id']."/".json_decode($product['images'], true)['1'];
        }
?>
        <li>
            <a href="/products/view_product/<?= $product['id'] ?>">
                <img src="/assets/images/products/<?= $main_image ?>" alt="<?= $product['name'] ?>" />
                <h3><?= $product['name'] ?></h3>
                <ul class="rating">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
                <span>36 Rating</span>
                <span class="price">$ <?= $product['price'] ?></span>
            </a>
        </li>
<?php
    }
?>
    </ul>