<?php
require '../_base.php';
auth('Admin');

if (is_post()) {
    $id          = post('product_id');
    $name        = post('product_name');
    $cat         = post('cat_id');
    $description = post('description'); // Capture the description
    $sizes       = $_POST['sizes'] ?? [];
    $prices      = $_POST['prices'] ?? [];
    $file        = $_FILES['photo'];

    if ($id && $name) {
        $f = get_file('photo'); 
        $filename = null;
        
        if ($f) {
            // This automatically saves it to ../images/ and gives it a safe random name
            $filename = save_photo($f, '../images'); 
        }

        // A. Single Update for Product (Includes Description)
        $stm1 = $_db->prepare("
            INSERT INTO product (product_id, product_name, description, photo, cat_id) 
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                product_name = VALUES(product_name), 
                description  = VALUES(description),
                photo        = IFNULL(VALUES(photo), photo),
                cat_id       = VALUES(cat_id)
        ");
        $stm1->execute([$id, $name, $description, $filename, $cat]);


        $stm2 = $_db->prepare("
            INSERT INTO product_variants (product_id, size, colour, stock_quantity, price)
            VALUES (?, ?, 'Default', 10, ?)
            ON DUPLICATE KEY UPDATE price = VALUES(price)
        ");

        foreach ($sizes as $index => $s) {
            $p = $prices[$index] ?? 0;
            if ($p > 0) {
                // Pass only 3 values: product_id, size, and price
                $stm2->execute([$id, $s, $p]);
            }
        }

        temp('info', 'Product and sizes updated successfully!');
        redirect();
    }
}
// Fetch categories for dropdown
$categories = $_db->query("SELECT * FROM categories")->fetchAll();
// Fetch products for list
$products = $_db->query("SELECT * FROM product ORDER BY product_id DESC")->fetchAll();

include '../_head.php';
?>

<main>
    <div class="solid-container">
        <h1>Product Maintenance</h1>

        <section class="maintenance-form-section">
            <h3>Add / Update Product</h3>
            <form method="post" enctype="multipart/form-data">
                <div class="form-field-group">
                    <label>Product ID:</label>
                    <input type="text" name="product_id" id="product_id" required>
                </div>
                <div class="form-field-group">
                    <label>Product Name:</label>
                    <input type="text" name="product_name" id="product_name" required>
                </div>

                <div class="form-field-group">
                    <label for="cat_id">Category:</label>
                    <select name="cat_id" id="cat_id" required>
                <option value="">- Select Category -</option>
                <?php foreach ($categories as $c): ?>
                 <option value="<?= $c->cat_id ?>"><?= htmlspecialchars($c->cat_name) ?></option>
                <?php endforeach; ?>
        </select>
    </div>

<div class="form-field-group">
    <label for="description">Description:</label>
    <textarea name="description" id="description" rows="4"></textarea>
</div>

                <table class="variant-input-table">
                    <thead>
                        <tr><th>Size</th><th>Price (RM)</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>S <input type="hidden" name="sizes[]" value="S"></td>
                            <td><input type="number" name="prices[]" step="0.01" placeholder="0.00"></td>
                        </tr>
                        <tr>
                            <td>M <input type="hidden" name="sizes[]" value="M"></td>
                            <td><input type="number" name="prices[]" step="0.01" placeholder="0.00"></td>
                        </tr>
                        <tr>
                            <td>L <input type="hidden" name="sizes[]" value="L"></td>
                            <td><input type="number" name="prices[]" step="0.01" placeholder="0.00"></td>
                        </tr>
                    </tbody>
                </table>

                <div id="drop-zone" class="drop-zone" onclick="document.getElementById('photo-input').click()">
                    <span class="browse-text">Click or Drag Photo Here</span>
                    <img id="img-preview" src="" style="display:none; max-width:150px; margin: 10px auto;">
                    <input type="file" name="photo" id="photo-input" hidden accept="image/*">
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
                            <img src="../images/<?= $p->photo ?>" class="product-thumbnail">
                        <?php else: ?>
                            <div class="no-photo-placeholder">No Image</div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= $p->product_id ?></strong></td>
                    <td><?= htmlspecialchars($p->product_name) ?></td>
                    <td>
            <button type="button" class="btn-clear" 
                    onclick="fillForm('<?= $p->product_id ?>', '<?= addslashes($p->product_name) ?>', '<?= $p->photo ?>')">
                    Edit
            </button>
                    </td>
                <?php endforeach; ?>

                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="4" class="no-data">No products found in inventory.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        </div>
</main>

<script>
const dropZone = document.getElementById('drop-zone');
const photoInput = document.getElementById('photo-input');
const previewImg = document.getElementById('img-preview');

// 1. Prevent browser from opening the file
['dragover', 'drop'].forEach(evt => {
    dropZone.addEventListener(evt, e => {
        e.preventDefault();
        e.stopPropagation();
    });
});

// 2. Handle the Drop
dropZone.addEventListener('drop', e => {
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        photoInput.files = files; 
        handlePreview(files[0]);
    }
});

// 3. Handle Manual Selection
photoInput.onchange = () => {
    if (photoInput.files[0]) handlePreview(photoInput.files[0]);
};

function handlePreview(file) {
    previewImg.src = URL.createObjectURL(file);
    previewImg.style.display = 'block';
}

function fillForm(id, name, photo) {
    document.getElementById('product_id').value = id;
    document.getElementById('product_name').value = name;
    // Note: To fill prices/sizes for edit, you would need to fetch variant data via AJAX
}
</script>

<?php include '../_foot.php'; 
?>