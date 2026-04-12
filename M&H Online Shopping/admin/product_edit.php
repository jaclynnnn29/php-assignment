<?php
require '../_base.php';
auth('Admin');

// 1. Get the product_id from the URL
$id = req('id');

// 2. Fetch the current data from the PRODUCT table
$stm = $_db->prepare("SELECT * FROM product WHERE product_id = ?");
$stm->execute([$id]);
$p = $stm->fetch();

// If product doesn't exist, redirect to product list
if (!$p) redirect('product_list.php');

if (is_post()) {
    $product_name = post('product_name');
    $price        = post('price');
    $colour       = post('colour');
    $size         = post('size');
    $f            = get_file('photo');

    // Validation (Basic)
    if (!$product_name) $_err['product_name'] = 'Required';
    if (!$price) $_err['price'] = 'Required';

    if (!$_err) {
        // Handle Photo Upload (Requirement: Product Photo Upload)
        $photo = $p->photo; // Keep old photo by default
        if ($f) {
            // Remove old photo file if it exists
            if ($photo) @unlink("../uploads/$photo");
            // Save new photo
            $photo = save_photo($f, '../uploads');
        }

        // 3. Update the PRODUCT record
        $stm = $_db->prepare("UPDATE product SET product_name = ?, price = ?, colour = ?, size = ?, photo = ? WHERE product_id = ?");
        $stm->execute([$product_name, $price, $colour, $size, $photo, $id]);

        temp('info', 'Product updated successfully');
        redirect('product_list.php');
    }
}

$_title = 'Edit Product';
include '../_head.php';
?>

<form method="post" enctype="multipart/form-data">
    <label>Product ID</label>
    <input type="text" value="<?= $p->product_id ?>" disabled> <br>

    <label>Product Name</label>
    <?php html_text('product_name', "value='$p->product_name'"); ?>
    <?php err('product_name') ?>
    <br>

    <label>Price (RM)</label>
    <?php html_text('price', "value='$p->price'"); ?>
    <?php err('price') ?>
    <br>

    <label>Colour</label>
    <?php html_text('colour', "value='$p->colour'"); ?>
    <br>

    <label>Size</label>
    <?php html_text('size', "value='$p->size'"); ?>
    <br>

    <label>Current Photo</label><br>
    <img src="../uploads/<?= $p->photo ?>" width="100"><br>
    
    <label>Upload New Photo</label>
    <?php html_file('photo', 'image/*'); ?>
    <br>

    <button>Update Product</button>
</form>

<?php include '../_foot.php'; ?>