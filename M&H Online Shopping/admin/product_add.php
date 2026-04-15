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

        // Database logic: Insert or Update (UPSERT style)
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
            <p style="color: <?= strpos($msg, 'Error') !== false ? 'red' : 'green' ?>; font-weight: bold;"><?= $msg ?></p>
        <?php endif; ?>

        <section style="background: #f4f4f4; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
    <h3>Add / Update Product</h3>
    <form method="post" enctype="multipart/form-data">
        <div style="margin-bottom: 15px;">
            <label>Product ID:</label><br>
            <input type="text" name="product_id" required placeholder="e.g., P001">
        </div>
        <div style="margin-bottom: 15px;">
            <label>Name:</label><br>
            <input type="text" name="product_name" required>
        </div>

        <div id="drop-zone" style="border: 2px dashed #bbb; padding: 40px; text-align: center; border-radius: 8px; cursor: pointer; background: #fff; margin-bottom: 15px;">
        <i class='bx bx-cloud-upload' style="font-size: 48px; color: #888;"></i>
        <p style="margin: 10px 0;">Drag & Drop product photo here or <span style="color: #1a8a83; font-weight: bold;">Browse</span></p>
    
        <input type="file" name="photo" id="photo-input" accept="image/png, image/jpeg" hidden>
    
        <img id="img-preview" src="" style="display:none; max-width: 200px; margin: 15px auto; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
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
                            <img src="../images/<?= $p->photo ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                        <?php else: ?>
                            <div style="width: 60px; height: 60px; background: #ddd; display: flex; align-items: center; justify-content: center; font-size: 10px;">No Image</div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= $p->product_id ?></strong></td>
                    <td><?= htmlspecialchars($p->product_name) ?></td>
                    <td>
            <button type="button" class="btn-clear" 
             onclick="
            document.getElementsByName('product_id')[0].value='<?= $p->product_id ?>'; 
            document.getElementsByName('product_name')[0].value='<?= htmlspecialchars($p->product_name) ?>';
            
            // Trigger the image preview
            const preview = document.getElementById('img-preview');
            <?php if ($p->photo): ?>
                preview.src = '../images/<?= $p->photo ?>';
                preview.style.display = 'block';
            <?php else: ?>
                preview.src = '';
                preview.style.display = 'none';
            <?php endif; ?>
        ">
        Edit
    </button>
</td>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px;">No products found in inventory.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <p style="margin-top: 15px; color: #666;"><?= count($products) ?> product(s) in database.</p>
    </div>
</main>

<?php include '../_foot.php'; ?>