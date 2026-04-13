<?php
require '../_base.php';
auth('Admin');

// Fetch categories for the select dropdown
$categories = $_db->query("SELECT cat_id, cat_name FROM categories")->fetchAll(PDO::FETCH_KEY_PAIR);

if (is_post()) {
    $product_id   = post('product_id');
    $product_name = post('product_name');
    $description  = post('description');
    $cat_id       = post('cat_id');
    $price        = post('price');
    $f            = get_file('photo');

    // Validation
    if (!$product_id)   $_err['product_id']   = 'Required';
    else if (!preg_match('/^P\d{5}$/', $product_id)) $_err['product_id'] = 'Format must be P + 5 digits (e.g., P20015)';
    
    if (!$product_name) $_err['product_name'] = 'Required';
    if (!$cat_id)       $_err['cat_id']       = 'Required';
    if (!$price)        $_err['price']        = 'Required';
    if (!$f)            $_err['photo']        = 'Photo is required';

    if (!$_err) {
        $photo = save_photo($f, '../images');

        $stm = $_db->prepare("
            INSERT INTO product (product_id, product_name, description, photo, cat_id, price)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stm->execute([$product_id, $product_name, $description, $photo, $cat_id, $price]);

        temp('info', 'Product added successfully');
        redirect('product_list.php');
    }
}

$_title = 'Add New Product';
include '../_head.php';
?>

<style>
    .add-table { width: 100%; border-collapse: collapse; border: 1px solid #ccc; margin-top: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .add-table th { 
        background-color: #2b91af; color: white; text-align: left; 
        padding: 15px; width: 220px; vertical-align: top; border-bottom: 1px solid #fff; 
    }
    .add-table td { padding: 15px; border-bottom: 1px solid #eee; background: #fff; }
    
    .input-field { width: 400px; padding: 8px; border: 1px solid #ddd; border-radius: 3px; }
    .input-field:focus { border-color: #2b91af; outline: none; box-shadow: 0 0 5px rgba(43,145,175,0.3); }
    
    .hint { display: block; color: #888; font-size: 0.85em; margin-top: 5px; }
    .error-msg { color: #ca5959; font-size: 0.85em; margin-top: 5px; font-weight: bold; }
    
    .btn-submit { background: #2b91af; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 3px; font-weight: bold; }
    .btn-submit:hover { background: #237a94; }
    .btn-cancel { background: #666; color: white; border: none; padding: 10px 20px; text-decoration: none; border-radius: 3px; font-size: 14px; margin-left: 10px; display: inline-block; }
</style>

<main>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Add New Product to Shopping Cart</h1>
        <a href="product_list.php" class="btn-cancel" style="background: #999;">Back to List</a>
    </div>

    <form method="post" enctype="multipart/form-data">
        <table class="add-table">
            <tr>
                <th>Product ID</th>
                <td>
                    <?php html_text('product_id', 'class="input-field" maxlength="6" placeholder="P20015"'); ?>
                    <span class="hint">Format: P followed by 5 digits</span>
                    <?php err('product_id') ?>
                </td>
            </tr>

            <tr>
                <th>Product Name</th>
                <td>
                    <?php html_text('product_name', 'class="input-field" placeholder="e.g., Black T-Shirt"'); ?>
                    <?php err('product_name') ?>
                </td>
            </tr>

            <tr>
                <th>Category</th>
                <td>
                    <?php html_select('cat_id', $categories, 'class="input-field" style="width: 418px;"'); ?>
                    <span class="hint">Select the clothing category</span>
                    <?php err('cat_id') ?>
                </td>
            </tr>

            <tr>
                <th>Selling Price (RM)</th>
                <td>
                    <input type="number" name="price" step="0.01" class="input-field" value="<?= post('price') ?>" placeholder="0.00">
                    <?php err('price') ?>
                </td>
            </tr>

            <tr>
                <th>Product Description</th>
                <td>
                    <textarea name="description" class="input-field" style="height: 100px;"><?= post('description') ?></textarea>
                    <span class="hint">Brief details about material, fit, or care instructions.</span>
                </td>
            </tr>

            <tr>
                <th>Product Photo</th>
                <td>
                    <div style="border: 1px dashed #ccc; padding: 15px; width: 400px; background: #fafafa;">
                        <?php html_file('photo', 'accept="image/*"'); ?>
                        <p class="hint" style="color: #ca5959;">* Image file is required for catalog display</p>
                    </div>
                    <?php err('photo') ?>
                </td>
            </tr>

            <tr>
                <th>Actions</th>
                <td>
                    <button type="submit" class="btn-submit">Confirm & Add Product</button>
                    <button type="reset" class="btn-cancel" style="background: #eee; color: #333; border: 1px solid #ccc;">Clear Form</button>
                </td>
            </tr>
        </table>
    </form>
</main>

<?php include '../_foot.php'; ?>