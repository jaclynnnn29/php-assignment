<?php
require '../_base.php';
auth('Admin'); // Restrict access to Admins only

// 1. Handle Form Submission (Add/Update Product with Photo)
if (is_post()) {
    $id   = post('product_id');
    $name = post('product_name');
    $file = $_FILES['photo']; // File data from the upload input

    // Basic Validation
    if (!$id || !$name) {
        temp('info', 'Error: Product ID and Name are required.');
    } else {
        $filename = null;

        if($file) {
            $ext = pathinfo($file->name, PATHINFO_EXTENSION);
            $filename = $id . '.' . $ext; // Name the file after the ID
            $dest = "../images/$filename";
        }

        if(!move_uploaded_file($file->tmp_name, $dest)) {
            temp('info', 'Error: Failed to save uploaded photo.');
            $filename = null; // Reset if upload failed
        }

        $stm = $_db->prepare("
            INSERT INTO product (product_id, product_name, photo) 
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE product_name = VALUES(product_name), photo = IFNULL(VALUES(photo), photo)
        ");
        $stm->execute([$id, $name, $filename]);

        temp('info', "Product $id updated successfully.");
        redirect(); 
    }
}

// 2. Fetch all products to display in the table
$products = $_db->query("SELECT * FROM product ORDER BY product_id DESC")->fetchAll();

$_title = 'Manage Products';
include '../_head.php';
?>

<main>
    <div class="solid-container">
        <h1>Product Maintenance</h1>

        <?php if ($msg = temp('info')): ?>
            <p class="<?= strpos($msg, 'Error') !== false ? 'msg-error' : 'msg-success' ?>; font-weight: bold;"><?= $msg ?></p>
        <?php endif; ?>

        <section class="maintenance-form-section">
            <h3>Add / Update Product</h3>
            <form method="post" enctype="multipart/form-data">
        <div class="form-field-group">
            <label>Product ID:</label><br>
            <input type="text" name="product_id" required placeholder="e.g., P001">
        </div>
        <div class="form-field-group">
            <label>Name:</label><br>
            <input type="text" name="product_name" required>
        </div>

        <div id="drop-zone" class="drop-zone" onclick="document.getElementById('photo-input').click()">
    <i class='bx bx-cloud-upload upload-icon'></i>
    <span class="browse-text">Click to Upload Photo</span>
    
    <img id="img-preview" src="">
    
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
        
        <p class="inventory-footer"><?= count($products) ?> product(s) in database.</p>
    </div>
</main>

<script>
const dropZone = document.getElementById('drop-zone');
const photoInput = document.getElementById('photo-input');
const previewImg = document.getElementById('img-preview');
const browseText = document.querySelector('.browse-text');

// A. Handle Drag & Drop
// Prevents the browser from just opening the image file in a new tab
['dragover', 'drop'].forEach(evt => {
    dropZone.addEventListener(evt, e => e.preventDefault());
});

dropZone.ondrop = e => {
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        photoInput.files = files; // Move the dropped file into the hidden input
        handlePreview(files[0]);
    }
};

// B. Handle Manual Browse
photoInput.onchange = () => {
    if (photoInput.files[0]) handlePreview(photoInput.files[0]);
};

// C. Show Preview Image
function handlePreview(file) {
    previewImg.src = URL.createObjectURL(file);
    previewImg.style.display = 'block';
}

// D. Handle Edit Button (Fill Form)
function fillForm(id, name, photo) {
    document.getElementsByName('product_id')[0].value = id;
    document.getElementsByName('product_name')[0].value = name;
    
    if (photo && photo !== "") {
        previewImg.src = '../images/' + photo;
        previewImg.style.display = 'block';
    } else {
        previewImg.src = '';
        previewImg.style.display = 'none';
    }
}
</script>

<?php include '../_foot.php'; ?>