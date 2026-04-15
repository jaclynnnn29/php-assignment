<?php
require '../_base.php';
auth('Admin');

$id = req('id');

// Fetch product details
// Fetch product details
$stm = $_db->prepare("
    SELECT p.*, c.cat_name
    FROM product p
    LEFT JOIN categories c ON p.cat_id = c.cat_id
    WHERE p.product_id = ?
");
$stm->execute([$id]);
$p = $stm->fetch();

if (!$p) {
    temp('error', 'Product not found');
    redirect('product_list.php');
}

if (is_get()) {
    $_POST = (array)$p;
}

if (is_post()) {
    $product_name = post('product_name');
    $description  = post('description');
    
    // 1. Use your _base.php helper to get the uploaded file object
    $f = get_file('photo');

    if (!$product_name) $_err['product_name'] = 'Required';

    if (!$_err) {
        $photo = $p->photo; 
        
        // 2. Handle the photo replacement if a new one was dropped/selected
        if ($f) {
            // Delete the old photo file if it exists
            if ($photo && file_exists("../images/$photo")) {
                @unlink("../images/$photo");
            }
            // Save the new photo using the helper
            $photo = save_photo($f, '../images');
        }

        $stm = $_db->prepare("
            UPDATE product 
            SET product_name = ?, description = ?, photo = ? 
            WHERE product_id = ?
        ");
        $stm->execute([$product_name, $description, $photo, $id]);

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
    
    /* Drag & Drop Styles */
    #drop-zone {
        border: 2px dashed #ccc;
        padding: 30px;
        text-align: center;
        border-radius: 8px;
        cursor: pointer;
        background: #fafafa;
        transition: all 0.3s ease;
    }
    #drop-zone.hover {
        background-color: #f0f9ff;
        border-color: #1a8a83 !important;
        border-style: solid !important;
    }
    #drop-zone i { font-size: 40px; color: #aaa; }
    #browse-click { color: #1a8a83; cursor: pointer; font-weight: bold; text-decoration: underline; }
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
                <th>Description</th>
                <td>
                    <?php html_text('description', 'style="width:300px;"'); ?>
                    <div class="current-val">Current: <?= $p->description ?></div>
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
                    <div id="drop-zone">
                        <i class='bx bx-cloud-upload'></i>
                        <p>Drag & Drop photo here or <span id="browse-click">Browse</span></p>
                        <input type="file" name="photo" id="photo-input" accept="image/*" hidden>
                        <img id="img-preview" src="" style="display:none; max-width: 150px; margin: 10px auto; border-radius: 4px;">
                    </div>
                    <p style="margin: 5px 0 0 0; font-size: 0.85em; color: #ca5959;">*Leave blank to keep current photo</p>
                </td>
            </tr>

            <tr>
                <th></th>
                <td>
                    <button type="submit" class="btn-update">Update Product</button>
                    <a href="product_list.php" style="background-color: #666; color: white; padding: 8px 15px; text-decoration: none; margin-left: 10px; border-radius: 3px;">Cancel</a>
                </td>
            </tr>
        </table>
    </form>
</main>

<script>
// 1. Declare variables ONCE at the top
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('photo-input');
const browseBtn = document.getElementById('browse-click');
const imgPreview = document.getElementById('img-preview');

// 2. Drag & Drop Logic
['dragover', 'drop'].forEach(name => {
    dropZone.addEventListener(name, e => e.preventDefault());
});

// Visual hover effect
dropZone.addEventListener('dragover', () => dropZone.classList.add('hover'));
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('hover'));

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('hover');
    
    // CRITICAL: This line moves the dropped file into the form
    fileInput.files = e.dataTransfer.files; 

    // Update the preview so you can see the photo before clicking Update
    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imgPreview.src = e.target.result;
            imgPreview.style.display = 'block';
        };
        reader.readAsDataURL(fileInput.files[0]);
    }
});

// 3. Click Logic (triggers file explorer)
browseBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    fileInput.click(); 
});

// Also update preview when choosing file via "Browse"
fileInput.addEventListener('change', () => {
    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imgPreview.src = e.target.result;
            imgPreview.style.display = 'block';
        };
        reader.readAsDataURL(fileInput.files[0]);
    }
});
</script>

<?php include '../_foot.php'; ?>