<?php
require '../_base.php';

// 1. Security check: Only Admins allowed
auth('Admin');

// 2. Fetch all products and their category names
// We use a JOIN to show 'T-Shirt' instead of 'C001'

// Update your SQL to include the price from your variants table
// --- 1. Get search term from URL ---
$search = req('search');

// --- 2. Base SQL Query ---
$sql = "
    SELECT p.*, c.cat_name, v.price 
    FROM product p
    LEFT JOIN categories c ON p.cat_id = c.cat_id
    LEFT JOIN (
        SELECT product_id, MIN(price) as price 
        FROM product_variants 
        GROUP BY product_id
    ) v ON p.product_id = v.product_id
";

// --- 3. Apply Filter if searching ---
if ($search) {
    // Searches for the keyword in Name or Category Name
    $sql .= " WHERE p.product_name LIKE ? OR c.cat_name LIKE ? OR p.product_id LIKE ?";
    $stm = $_db->prepare($sql . " ORDER BY p.product_id ASC");
    $stm->execute(["%$search%", "%$search%", "%$search%"]);
} else {
    // Default view
    $stm = $_db->query($sql . " ORDER BY p.product_id ASC");
}

$products = $stm->fetchAll();

$_title = 'Admin | Product List';
include '../_head.php';
?>

<main>
    <div class="solid-container">
        <div class="management-header">
            <h1>Product Management</h1>
            <a href="product_add.php" class="btn-add">+ Add New Product</a> 
        </div>

        <div class="search-row">
            <form action="" method="get" class="search-form">
                <input type="text" name="search" placeholder="Search products..." 
                    value="<?= htmlspecialchars($search) ?>" class="search-input">
                <button type="submit" class="btn-search">Search</button>
                <?php if ($search): ?>
                    <a href="product_list.php" class="btn-clear">Clear</a>
                <?php endif; ?>
            </form>
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
                            <img src="/images/<?= $p->photo ?>" class="product-list-img">
                        </td>
                        <td><?= $p->product_id ?></td>
                        <td><?= $p->product_name ?></td>
                        <td><?= $p->cat_name ?></td>
                        <td class="right"><?= number_format($p->price, 2) ?></td>
                        <td>
                            <a href="product_edit.php?id=<?= $p->product_id ?>" class="link-edit">Edit</a> | 
                            <a href="product_delete.php?id=<?= $p->product_id ?>" class="link-delete" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
<p><?= count($products) ?> product(s) found.</p>

<?php include '../_foot.php'; ?>