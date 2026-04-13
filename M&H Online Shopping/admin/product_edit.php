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
    <input type="text" value="<?= $p->product_id ?>" disabled> 
    <br>

    <label>Product Name</label>
    <?php html_text('product_name'); ?>
    <?php err('product_name') ?>
    <br>

    <label>Price (RM)</label>
    <?php html_text('price'); ?>
    <?php err('price') ?>
    <br>

    <label>Colour</label>
    <?php html_text('colour'); ?>
    <br>

    <label>Size</label>
    <?php html_text('size'); ?>
    <br>

    <label>Current Photo</label><br>
    <img src="../uploads/<?= $p->photo ?>" width="100" style="border: 1px solid #ccc; margin: 10px 0;"><br>
    
    <label>Upload New Photo (Leave blank to keep current)</label><br>
    <?php html_file('photo', 'image/*'); ?>
    <br>

    <section style="margin-top: 20px;">
        <button type="submit">Update Product</button>
        <a href="product_list.php">Cancel</a>
    </section>
</form>

<?php include '../_foot.php'; ?>