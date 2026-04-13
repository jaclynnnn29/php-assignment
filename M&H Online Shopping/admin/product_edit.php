<?php
require '../_base.php';
auth('Admin');

$id = req('id');

// Fetch product details, category name, and the price from the item table
$stm = $_db->prepare("
    SELECT p.*, c.cat_name, i.price 
    FROM product p
    JOIN categories c ON p.cat_id = c.cat_id
    LEFT JOIN item i ON p.product_id = i.variant_id
    WHERE p.product_id = ?
    ORDER BY i.item_id DESC LIMIT 1
");
$stm->execute([$id]);
$p = $stm->fetch();

if (!$p) {
    temp('error', 'Product not found');
    redirect('product_list.php');
}

// Fetch categories for the dropdown
$categories = $_db->query("SELECT cat_id, cat_name FROM categories")->fetchAll(PDO::FETCH_KEY_PAIR);

if (is_get()) {
    $_POST = (array)$p;
}

if (is_post()) {
    $product_name = post('product_name');
    $description  = post('description');
    $cat_id       = post('cat_id');
    // Note: Usually, you don't 'update' an old order item's price. 
    // If you want to change the price for NEW orders, you should add a price column to the product table.
    $f = get_file('photo');

    if (!$product_name) $_err['product_name'] = 'Required';

    if (!$_err) {
        $photo = $p->photo; 
        if ($f) {
            if ($photo && file_exists("../images/$photo")) {
                @unlink("../images/$photo");
            }
            $photo = save_photo($f, '../images');
        }

        // 2. Update the product table
        $stm = $_db->prepare("
            UPDATE product 
            SET product_name = ?, description = ?, cat_id = ?, photo = ? 
            WHERE product_id = ?
        ");
        $stm->execute([$product_name, $description, $cat_id, $photo, $id]);

        temp('info', 'Product updated successfully');
        redirect('product_list.php');
    }
}

$_title = 'Edit Product';
include '../_head.php';
?>

<style>
    .edit-table { width: 100%; border-collapse: collapse; border: 1px solid #ccc; }
    .edit-table th { 
        background-color: #2b91af; color: white; text-align: left; 
        padding: 15px; width: 200px; vertical-align: top; border-bottom: 1px solid #fff; 
    }
    .edit-table td { padding: 15px; border-bottom: 1px solid #eee; background: #fff; }
    .current-val { display: block; color: #888; font-size: 0.85em; margin-top: 5px; }
    .btn-update { background: #f0f0f0; border: 1px solid #999; padding: 6px 12px; cursor: pointer; border-radius: 2px; }
    .btn-cancel { background: #666; color: white; border: none; padding: 7px 15px; text-decoration: none; border-radius: 3px; font-size: 14px; }
</style>

<main>
    <form method="post" enctype="multipart/form-data">
        <table class="edit-table">
            <tr>
                <th>Product ID</th>
                <td><input type="text" value="<?= $p->product_id ?>" disabled style="width:300px; background:#f5f5f5; border:1px solid #ddd;"></td>
            </tr>

            <tr>
                <th>Product Name</th>
                <td>
                    <?php html_text('product_name', 'style="width:300px;"'); ?>
                    <span class="current-val">Current: <?= $p->product_name ?></span>
                    <?php err('product_name') ?>
                </td>
            </tr>

        <tr>
            <th>Desccription</th>
            <td>
                <?php html_text(''); ?>
                <div style="font-size: 0.8em; color: #888; margin-top: 4px;">Current: <?= $p->description ?></div>
                <?php err('description') ?>
            </td>
        </tr>

        <tr>
            <th>Category</th>
            <td>
                <?php html_text('cat_name'); ?>
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
                <?php html_file('photo', 'image/*'); ?>
                <p style="margin: 0 0 5px 0; font-size: 0.85em; color: #ca5959;">*Leave blank to keep current</p>
            </td>
        </tr>
        <tr>
            <th></th>
            <td>
                <button type="submit" class="btn-update">Update Product</button>
                <a href="product_list.php" class="back-link" style="background-color: #666; margin-left: 10px;">Cancel</a>
            </td>
        </tr>
    </table>
</form>
