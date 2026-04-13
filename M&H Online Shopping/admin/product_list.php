<?php
require '../_base.php';

// 1. Security check: Only Admins allowed
auth('Admin');

// 2. Fetch all products and their category names
// We use a JOIN to show 'T-Shirt' instead of 'C001'

// Update your SQL to include the price from your variants table
$stm = $_db->query("
    SELECT p.*, c.cat_name, v.price 
    FROM product p
    LEFT JOIN categories c ON p.cat_id = c.cat_id
    LEFT JOIN (
        SELECT product_id, MIN(price) as price 
        FROM product_variants 
        GROUP BY product_id
    ) v ON p.product_id = v.product_id
    ORDER BY p.product_id ASC
");
$products = $stm->fetchAll();

$_title = 'Admin | Product List';
include '../_head.php';
?>

<main>
    <div class="solid-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Product Management</h1>
            <a href="product_add.php" class="btn-add">+ Add New Product</a>
        </div>

        <table class="table solid-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th class="right">Price (RM)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Your existing PHP loop to fetch products
                foreach ($products as $p):
                ?>
                    <tr>
                        <td>
                            <img src="/images/<?= $p->photo ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                        </td>
                        <td><?= $p->product_id ?></td>
                        <td><?= $p->product_name ?></td>
                        <td><?= $p->cat_name ?></td>
                        <td class="right"><?= number_format($p->price, 2) ?></td>
                        <td>
                            <a href="product_edit.php?id=<?= $p->product_id ?>" class="link-edit">Edit</a> | 
                            <a href="product_delete.php?id=<?= $p->product_id ?>" class="link-delete">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<p><?= count($products) ?> product(s) found.</p>

<?php include '../_foot.php'; ?>