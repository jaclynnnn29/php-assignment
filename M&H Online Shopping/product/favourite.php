<?php
include '../_base.php';

// Check if user is logged in
if (!$_user) {
    redirect('../login.php');
}

// 1. Fetch user's favorite products
// We JOIN with product_variants to get the actual price, size, and photo
$stm = $_db->prepare("
    SELECT p.product_id, p.product_name, v.photo, v.price, v.size, f.added_at 
    FROM favorites f 
    JOIN product p ON f.product_id = p.product_id 
    JOIN product_variants v ON p.product_id = v.product_id
    WHERE f.user_id = ?
    GROUP BY p.product_id
    ORDER BY f.added_at DESC
");
$stm->execute([$_user->user_id]);
$favorites = $stm->fetchAll();

$_title = 'My Wishlist';
include '../_head.php';
?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: white;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }
    th {
        background-color: #2b91af;
        color: white;
    }
    .wishlist-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
    }
    .remove-btn {
        color: #e74c3c;
        text-decoration: none;
        font-weight: bold;
    }
    .view-btn {
        color: #2b91af;
        text-decoration: none;
    }
</style>

<h1 style="color: white; padding: 10px; display: inline-block;">
    My Wishlist ❤️
</h1>

<?php if (count($favorites) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Size</th>
                <th>Price</th>
                <th>Added On</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($favorites as $item): ?>
            <tr class="wishlist-item">
                <td>
                    <img src="../images/<?= $item->photo ?>" alt="<?= encode($item->product_name) ?>">
                </td>
                <td><strong><?= encode($item->product_name) ?></strong></td>
                <td><?= encode($item->size) ?></td>
                <td>RM <?= number_format($item->price, 2) ?></td>
                <td><?= date('d M Y', strtotime($item->added_at)) ?></td>
                <td>
                    <a href="add_favorite.php?id=<?= $item->product_id ?>" 
                       class="remove-btn" 
                       onclick="return confirm('Remove this item from your wishlist?')">
                       Remove
                    </a> 
                    |
                    <a href="detail.php?id=<?= $item->product_id ?>" class="view-btn">View Details</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div style="padding: 90px; text-align: center; background: #f9f9f9; border: 1px dashed #ccc;">
        <p>Your wishlist is empty 😢</p>
        <a href="list.php" style="color: #2b91af; font-weight: bold;">Browse products and add some!</a>
    </div>
<?php endif; ?>

<div style="margin-top: 20px;">
    <a href="list.php" style="text-decoration: none; color: #1208cb;">← Continue Shopping</a>
</div>

