<?php
require '../_base.php';
auth('Admin');

// 1. Get the product_id from the URL
$id = req('id');

// 2. Fetch the current data from the PRODUCT table
$stm = $_db->prepare("SELECT * FROM product WHERE product_id = ?");
$stm->execute([$id]);
$p = $stm->fetch();

if ($p) {
    // This creates the variable we will use in the <img> tag later
    $image_path = "/images/" . ($p->photo ?? 'no_image.png');
} else {
    // Fallback if the product isn't found
    temp('error', 'Product not found');
    redirect('product_list.php');
}

// Populate $_POST with database values if it's a GET request
// This allows html_text('field') to show existing data automatically
if (is_get()) {
    $_POST = (array)$p;
}

if (is_post()) {
    $product_name = post('product_name');
    $price        = post('price');
    $colour       = post('colour');
    $size         = post('size');
    $f            = get_file('photo');

    // Validation
    if (!$product_name) $_err['product_name'] = 'Required';
    if (!$price)        $_err['price']        = 'Required';
    else if (!is_numeric($price)) $_err['price'] = 'Must be a number';

    if (!$_err) {
        $photo = $p->photo; 
        if ($f) {
            // Remove old photo file if it exists
            if ($photo) @unlink("../uploads/$photo");
            // Save new photo
            $photo = save_photo($f, '../uploads');
        }

        // 3. Update the PRODUCT record
        $stm = $_db->prepare("UPDATE product SET product_name = ?, price = ?, description = ?, photo = ? , cat_id = ? WHERE product_id = ?");
        $stm->execute([$product_name, $price, $colour, $size, $photo, $id]);

        temp('info', 'Product updated successfully');
        redirect('product_list.php');
    }
}

$_title = 'Edit Product';
include '../_head.php';
?>

<form method="post" enctype="multipart/form-data">
    <table class="table" style="width: 40%; background-color: #fff; border-radius: 8px; margin-top: 10px;">

        <tr>
            <th style="width: 150px;">Product ID</th>
            <td><input type="text" value="<?= $p->product_id ?>" disabled style="background: #f9f9f9; border: 1px solid #ddd; padding: 8px;"></td>
        </tr>

        <tr>
            <th>Product Name</th>
            <td>
                <?php html_text('product_name'); ?>
                <div style="font-size: 0.8em; color: #888; margin-top: 4px;">Current: <?= $p->product_name ?></div>
                <?php err('product_name') ?>
            </td>
        </tr>

        <tr>
            <th>Desccription</th>
            <td>
                <?php html_text('description'); ?>
                <div style="font-size: 0.8em; color: #888; margin-top: 4px;">Current: <?= $p->description ?></div>
                <?php err('description') ?>
            </td>
        </tr>

        <tr>
            <th>Category</th>
            <td>
                <?php html_text('category'); ?>
                <div style="font-size: 0.8em; color: #888; margin-top: 4px;">Current: <?= $p->cat_id ?><?= $p->cat_name ?></div>
            </td>
        </tr>

        <tr>
            <th>Price (RM)</th>
            <td>
                <?php html_text('price'); ?>
                <div style="font-size: 0.8em; color: #888; margin-top: 4px;">Current: RM <?= number_format($p->price, 2) ?></div>
                <?php err('price') ?>
            </td>
        </tr>
        
        <tr>
            <th>Current Photo</th>
            <td>
                <img src="../images/<?= $p->photo ?>" width="100" style="border: 1px solid #ccc; border-radius: 4px;">
            </td>
        </tr>
        <tr>
            <th>Upload New Photo</th>
            <td>
                <p style="margin: 0 0 5px 0; font-size: 0.85em; color: #ca5959;">*Leave blank to keep current</p>
                <?php html_file('photo', 'image/*'); ?>
            </td>
        </tr>
        <tr>
            <th>Actions</th>
            <td>
                <button type="submit" class="btn-update">Update Product</button>
                <a href="product_list.php" class="back-link" style="background-color: #666; margin-left: 10px;">Cancel</a>
            </td>
        </tr>
    </table>
</form>
