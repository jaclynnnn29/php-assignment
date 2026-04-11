<?php
require '../_base.php';
auth();

$_title = 'My Reviews';
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
    .rating-stars {
        color: gold;
    }
</style>

<h1>My Reviews</h1>

<?php
$stm = $_db->prepare("
    SELECT r.*, p.product_name, p.photo 
    FROM product_reviews r 
    JOIN product p ON r.product_id = p.product_id 
    WHERE r.user_id = ? 
    ORDER BY r.created_at DESC
");
$stm->execute([$_user->user_id]);
$reviews = $stm->fetchAll();

if (count($reviews) > 0):
?>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($reviews as $review): ?>
            <tr>
                <td>
                    <img src="/images/<?= $review->photo ?>" width="50" height="50">
                    <?= htmlspecialchars($review->product_name) ?>
                </td>
                <td class="rating-stars">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <?= $i <= $review->rating ? '⭐' : '☆' ?>
                    <?php endfor; ?>
                </td>
                <td><?= nl2br(htmlspecialchars(substr($review->review, 0, 100))) ?>...</td>
                <td><?= date('d/m/Y', strtotime($review->created_at)) ?></td>
                <td>
                    <a href="/product/detail.php?id=<?= $review->product_id ?>">View Product</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>You haven't written any reviews yet.</p>
    <p><a href="/product/list.php">Browse products and write a review!</a></p>
<?php endif; ?>

<?php include '../_foot.php'; ?>