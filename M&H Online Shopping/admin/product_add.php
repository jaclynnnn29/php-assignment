<?php
require '../_base.php';
auth('Admin'); // Restrict access to Admins only

// 1. Handle Form Submission (Add/Update Product with Photo)
if (is_post()) {
    $id    = post('product_id');   // e.g., P20078
    $name  = post('product_name');
    $cat   = post('cat_id');
    $price = post('price');
    $size  = post('size');         // e.g., S, M, or L
    $file  = $_FILES['photo'];

    // 1. File Upload
    $filename = null;
    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $filename = $file['name']; 
        move_uploaded_file($file['tmp_name'], "../images/$filename");
    }

    // 2. Insert/Update into 'product' table (The Main Info)
    $stm1 = $_db->prepare("
        INSERT INTO product (product_id, product_name, photo, cat_id) 
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            product_name = VALUES(product_name), 
            photo = IFNULL(VALUES(photo), photo),
            cat_id = VALUES(cat_id)
    ");
    $stm1->execute([$id, $name, $filename, $cat]);

    // 3. Insert/Update into 'product_variants' table (The Size/Price Info)
    // This creates an ID like: P20078-S
    $variant_id = $id . '-' . ($size ?: 'Default');

    $stm2 = $_db->prepare("
        INSERT INTO product_variants (variant_id, product_id, size, colour, stock_quantity, price)
        VALUES (?, ?, ?, 'Default', 10, ?)
        ON DUPLICATE KEY UPDATE 
            price = VALUES(price),
            size = VALUES(size)
    ");
    $stm2->execute([$variant_id, $id, $size, $price]);

    temp('info', "Product $id ($size) updated successfully!");
    redirect(); 
}

// Fetch categories for the dropdown
$categories = $_db->query("SELECT * FROM categories")->fetchAll();

// Fetch all products to display in the table below the form
$products = $_db->query("SELECT * FROM product ORDER BY product_id DESC")->fetchAll();

$_title = 'Manage Products';
include '../_head.php';
?>

<main>
    <div class="solid-container">
        <h1>Product Maintenance</h1>

        <?php if ($msg = temp('info')): ?>
            <p class="<?= strpos($msg, 'Error') !== false ? 'msg-error' : 'msg-success' ?>" style="font-weight: bold;"><?= $msg ?></p>
        <?php endif; ?>

        <section class="maintenance-form-section">
            <h3>Add / Update Product</h3>
            <form method="post" enctype="multipart/form-data">
                <div class="form-field-group">
                    <label>Product ID:</label><br>
                    <input type="text" name="product_id" id="product_id" required placeholder="e.g., P20078">
                </div>
                <div class="form-field-group">
                    <label>Name:</label><br>
                    <input type="text" name="product_name" id="product_name" required>
                </div>
                <div class="form-field-group">
                    <label>Category:</label><br>
                    <select name="cat_id" id="cat_id">
                        <option value="">-- Select Category --</option>
                        <?php foreach($categories as $c): ?>
                            <option value="<?= $c->cat_id ?>"><?= $c->cat_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-field-group">
                    <label>Size (e.g., S, M, L):</label>
                    <input type="text" name="size" required>
                </div>

                <div class="form-field-group">
                    <label>Price (RM):</label>
                    <input type="number" name="price" step="0.01" required>
                </div>


                <div id="drop-zone" class="drop-zone" onclick="document.getElementById('photo-input').click()">
                    <i class='bx bx-cloud-upload upload-icon'></i>
                    <span class="browse-text">Click or Drag Photo Here</span>
                    <img id="img-preview" src="" style="display:none; max-width:100%; margin-top:10px;">
                    <input type="file" name="photo" id="photo-input" accept="image/*" hidden>
                </div>

                <button type="submit" class="btn-update">Save Product</button>
            </form>
        </section>

        <table class="table solid-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td>
                        <?php if ($p->photo): ?>
                            <img src="../images/<?= $p->photo ?>" class="product-thumbnail" width="50">
                        <?php else: ?>
                            <div class="no-photo-placeholder">No Image</div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= $p->product_id ?></strong></td>
                    <td><?= htmlspecialchars($p->product_name) ?></td>
                    <td>
                        <button type="button" class="btn-clear" 
                                onclick="fillForm('<?= $p->product_id ?>', '<?= addslashes($p->product_name) ?>', '<?= $p->photo ?>', '<?= $p->cat_id ?>')">
                                Edit
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
const photoInput = document.getElementById('photo-input');
const previewImg = document.getElementById('img-preview');

// Handle Preview
photoInput.onchange = () => {
    const file = photoInput.files[0];
    if (file) {
        previewImg.src = URL.createObjectURL(file);
        previewImg.style.display = 'block';
    }
};

// Fill form for editing
function fillForm(id, name, photo, catId) {
    document.getElementById('product_id').value = id;
    document.getElementById('product_name').value = name;
    document.getElementById('cat_id').value = catId;
    
    if (photo) {
        previewImg.src = '../images/' + photo;
        previewImg.style.display = 'block';
    } else {
        previewImg.src = '';
        previewImg.style.display = 'none';
    }
    window.scrollTo(0, 0);
}
</script>

<?php include '../_foot.php'; ?>