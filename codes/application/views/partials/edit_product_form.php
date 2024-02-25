<!-- <div class="modal-dialog">
    <div class="modal-content">
        <button data-dismiss="modal" aria-label="Close" class="close_modal"></button>
        <form class="add_product_form" action="/admins/edit_product" method="post" enctype="multipart/form-data">
            <h2>Add a Product</h2>
            <p class="errors"></p> -->
            <!-- <ul> -->
                <li>
                    <input type="text" name="product_name" value="<?= $product['name'] ?>" required/>
                    <label>Product Name</label>
                </li>
                <li>
                    <textarea name="description" required><?= $product['description'] ?></textarea>
                    <label>Description</label>
                </li>
                <li>
                    <label>Category</label>
                    <select class="selectpicker" name="category" autocomplete="off">
<?php
    foreach($categories as $category)
    {
        if ($category['category_id'] == $product['category_id'])
        {
?>
                        <option value="<?= $category['category_id'] ?>" selected ><?= $category['category'] ?></option>
<?php
        }
        else
        {
?>
                        <option value="<?= $category['category_id'] ?>" ><?= $category['category'] ?></option>
<?php
        }
    }
?>
                    </select>
                </li>
                <li>
                    <input type="text" name="price" value="<?= $product['price'] ?>" required/>
                    <label>Price</label>
                </li>
                <li>
                    <input type="number" name="inventory" value="<?= $product['stock'] ?>" required />
                    <label>Inventory</label>
                </li>
                <li>
                    <label class="image_label">Upload Images (4 Max)</label>
                    <ul>
                        <li><button type="button" class="upload_image"></button></li>
                    </ul>
                    <ul class="image_preview_list">
<?php 
    $count = 0;
    foreach($images as $image)
    {   
?>
                        <li>
                            <button class="delete_image" data-image-index="<?= $count ?>"></button>
                            <img src="/assets/images/uploads/<?= $image ?>" alt="<?= $image ?>">
<?php
        if ($image == json_decode($product['images'], TRUE)[1])
        {
?>
                            <label for="main_image"><input type="checkbox" class="main_checkbox" name="main_image" checked value="<?= $image ?>">Mark As Main</label>
<?php
        }
        else 
        {
?>
                            <label for="main_image"><input type="checkbox" class="main_checkbox" name="main_image" value="<?= $image ?>" >Mark As Main</label>
<?php
        }
?>
                        </li>
<?php
        $count++;
    }
?>
                    </ul>
                    <input class="image_input" type="file" name="image[]" accept="image/*"  multiple />
                    <input type="hidden" class="form_data_action" name="action" value="edit_product">
                    <input type="hidden" name="image_index" value="">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                </li>
            <!-- </ul> -->
            <!-- <button type="button" data-dismiss="modal" aria-label="Close" >Cancel</button>
            <button type="submit">Save</button>
        </form>
    </div>
</div> -->