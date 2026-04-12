<?php
require '../_base.php';

// 1. Security check: Only Admins allowed
auth('Admin');

// 2. Fetch all products and their category names
// We use a JOIN to show 'T-Shirt' instead of 'C001'
$stm = $_db->query("
    SELECT p.*, c.cat_name 
    FROM product p
    JOIN categories c ON p.cat_id = c.cat_id
    ORDER BY p.product_id ASC
");
$products = $stm->fetchAll();

$_title = 'Admin | Product List';
include '../_head.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Product Maintenance</h2>
    <a href="product_add.php" style="background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
        Add New Product +
    </a>
</div>

<?php if ($msg = temp('info')) echo "<p style='color:green; font-weight:bold;'>$msg</p>"; ?>

<table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse; text-align: left;">
    <thead style="background-color: #f2f2f2;">
        <tr>
            <th>Photo</th>
            <th>Product ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price (RM)</th>
            <th>Stock Info</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $p): ?>
        <tr>
            <td style="text-align: center;">
                <img src="../uploads/<?= $p->photo ?>" width="50">
            </td>
            
            <td><strong><?= $p->product_id ?></strong></td>
            <td><?= $p->product_name ?></td>
            <td><?= $p->cat_name ?></td>
            <td><?= number_format($p->price, 2) ?></td>
            
            <td>
                <small>
                    Color: <?= $p->colour ?><br>
                    Size: <?= $p->size ?>
                </small>
            </td>

            <td>
                <a href="product_edit.php?id=<?= $p->product_id ?>" style="color: blue;">Edit</a> | 
                <a href="product_delete.php?id=<?= $p->product_id ?>" 
                   style="color: red;"
                   onclick="return confirm('Delete this product and all associated variants?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>

        <?php if (empty($products)): ?>
        <tr>
            <td colspan="7" style="text-align: center; padding: 20px;">No products found in the warehouse.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<p><?= count($products) ?> product(s) found.</p>

<?php include '../_foot.php'; ?>