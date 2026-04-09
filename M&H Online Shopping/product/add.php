<?php
require '../_base.php'; 

// 1. Fetch Categories & Sub-categories for the dropdown
// This handles the hierarchy for Men, Women, and Child categories
$stm = $_db->query("SELECT * FROM categories ORDER BY cat_id ASC");
$rows = $stm->fetchAll();

$cat_list = [];
foreach ($rows as $r) {
    // Logic to visually distinguish parent and sub-categories in the dropdown
    $cat_list[$r->cat_id] = $r->cat_name;
}

// 2. Handle Form Submission
if (is_post()) {
    $product_id   = post('product_id');
    $product_name = post('product_name');
    $cat_id       = post('cat_id');
    $price        = post('price');
    $colour       = post('colour');
    $size         = post('size');
    $f            = get_file('photo');

    // Validation: Product ID (Manual entry required for VARCHAR 10)
    if ($product_id == '') {
        $_err['product_id'] = 'Required';
    } else if (!preg_match('/^[A-Z]\d{3}$/', $product_id)) {
        $_err['product_id'] = 'Invalid format (e.g., P001)';
    } else if (!is_unique($product_id, 'products', 'product_id')) {
        $_err['product_id'] = 'Duplicate Product ID';
    }

    // Validation: Standard fields
    if ($product_name == '') $_err['product_name'] = 'Required';
    if ($cat_id == '')       $_err['cat_id'] = 'Required';
    
    if (!is_money($price)) {
        $_err['price'] = 'Invalid price format';
    }

    // Validation: Photo (Handles PNG/JPG)
    if ($f == null) {
        $_err['photo'] = 'Required';
    } else if (!str_starts_with($f->type, 'image/')) {
        $_err['photo'] = 'Must be an image';
    }

    // 3. Database Insertion
    if (!$_err) {
        // save_photo processes the image and saves it to the uploads folder
        $photo = save_photo($f, '../uploads', 400, 400);

        $sql = "INSERT INTO products 
                (product_id, product_name, cat_id, price, colour, size, photo) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stm = $_db->prepare($sql);
        $stm->execute([
            $product_id, 
            $product_name, 
            $cat_id, 
            $price, 
            $colour, 
            $size,
            $photo
        ]);

        temp('info', 'Product added successfully!');
        redirect('list.php');
    }
}

$_title = 'Add Product';
include '../_head.php';
?>

<form method="post" class="form" enctype="multipart/form-data">
    <label for="product_id">Product ID</label>
    <?php html_text('product_id', 'maxlength="10" placeholder="e.g. P001"'); ?>
    <?= err('product_id') ?>

    <label for="product_name">Product Name</label>
    <?php html_text('product_name', 'maxlength="255"'); ?>
    <?= err('product_name') ?>

    <label for="cat_id">Category</label>
    <?php html_select('cat_id', $cat_list, '- Select Category -'); ?>
    <?= err('cat_id') ?>

    <label for="price">Price (RM)</label>
    <?php html_number('price', 0, 9999, 0.01); ?>
    <?= err('price') ?>

    <label for="size">Size</label>
    <?php html_text('size', 'maxlength="10" placeholder="e.g. S, M, L"'); ?>

    <label for="colour">Colour</label>
    <?php html_text('colour', 'maxlength="30"'); ?>

    <label for="quantity">Stock Quantity</label>
    <?php html_number('quantity', 0, 1000, 1); ?>

    <label for="description">Description</label>
    <textarea name="description" id="description" rows="4"></textarea>

    <label for="photo">Product Image</label>
    <label class="upload" tabindex="0">
        <?php html_file('photo', 'image/*', 'hidden'); ?>
        <img src="/images/photo.jpg" style="width:150px; height:150px; object-fit: cover; cursor: pointer;">
    </label>
    <?= err('photo') ?>

    <section>
        <button type="submit">Submit Product</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php include '../_foot.php'; ?>