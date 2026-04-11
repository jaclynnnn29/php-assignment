<?php
// Add this code inside your foreach loop for each product
// Get average rating for this product
$stm = $_db->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM product_reviews WHERE product_id = ?");
$stm->execute([$p->product_id]);
$rating_info = $stm->fetch();
$avg = round($rating_info->avg_rating ?? 0, 1);
$review_count = $rating_info->total ?? 0;
?>

<!-- Add this inside your product div where you show product info -->
<div style="font-size: 12px; margin-top: 5px;">
    <?php if ($review_count > 0): ?>
        <?php for($i = 1; $i <= 5; $i++): ?>
            <?= $i <= $avg ? '⭐' : '☆' ?>
        <?php endfor; ?>
        <span>(<?= $review_count ?>)</span>
    <?php else: ?>
        No reviews yet
    <?php endif; ?>
</div>