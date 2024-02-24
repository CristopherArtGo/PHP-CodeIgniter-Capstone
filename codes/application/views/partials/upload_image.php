<?php 
    $count = 0;
    foreach($images as $image)
    {   
?>
<li>
    <button class="delete_image" data-image-index="<?= $count ?>"></button>
    <img src="/assets/images/uploads/<?= $image ?>" alt="<?= $image ?>">
<?php
        if ($count == 0)
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