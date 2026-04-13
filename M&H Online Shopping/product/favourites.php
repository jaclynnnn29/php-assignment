<?php
include '../_base.php';

// Check if user is logged in
if (!$_user) {
    redirect('../login.php');
}

$_title = 'My Wishlist';
include '../_head.php';
?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    .wishlist-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
    }
    .remove-btn {
        color: red;
        text-decoration: none;
    }
</style>

<h1 style="color: #ffffff;">My Wishlist ❤️</h1>

<?php
// Get user's favorite products
$stm = $_db->prepare("
    SELECT p.*, f.added_at 
    FROM favorites f 
    JOIN product p ON f.product_id = p.product_id 
    WHERE f.user_id = ?
    ORDER BY f.added_at DESC
");
$stm->execute([$_user->user_id]);
$favorites = $stm->fetchAll();

if (count($favorites) > 0):
?>
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
                    <img src="/images/<?= $item->photo ?>" alt="<?= $item->product_name ?>">
                </td>
                <td><?= htmlspecialchars($item->product_name) ?></td>
                <td><?= $item->size ?></td>
                <td>RM <?= number_format($item->price, 2) ?></td>
                <td><?= date('d/m/Y', strtotime($item->added_at)) ?></td>
                <td>
                    <a href="add_favorite.php?id=<?= $item->product_id ?>" class="remove-btn" onclick="return confirm('Remove from wishlist?')">Remove</a> |
                    <a href="detail.php?id=<?= $item->product_id ?>">View Details</a> |
                    <a href="add_to_cart.php?id=<?= $item->product_id ?>">Add to Cart</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="color: #ffffff;">Your wishlist is empty 😢</p>
    <p><a href="list.php"  style="color: #ffffff;">Browse products and add to wishlist!</a></p>
<?php endif; ?>

<br>
<a href="list.php" style="color: #ffffff;">← Continue Shopping</a>
