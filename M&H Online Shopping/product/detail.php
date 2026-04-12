<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    // Handle cart update
    if (isset($_POST['quantity'])) {
        $id = req('id');
        $unit = req('quantity');
        update_cart($id, $unit);
        redirect();
    }
    
    // Handle review submission
    if (isset($_POST['rating'])) {
        $rating = req('rating');
        $review = req('review');
        $product_id = req('id');
        
        // Check if user already reviewed
        $stm = $_db->prepare("SELECT * FROM product_reviews WHERE user_id = ? AND product_id = ?");
        $stm->execute([$_user->user_id, $product_id]);
        $existing = $stm->fetch();
        
        if ($existing) {
            // Update existing review
            $stm = $_db->prepare("UPDATE product_reviews SET rating = ?, review = ? WHERE user_id = ? AND product_id = ?");
            $stm->execute([$rating, $review, $_user->user_id, $product_id]);
            temp('info', 'Review updated!');
        } else {
            // Add new review
            $stm = $_db->prepare("INSERT INTO product_reviews (product_id, user_id, rating, review) VALUES (?, ?, ?, ?)");
            $stm->execute([$product_id, $_user->user_id, $rating, $review]);
            temp('info', 'Review added! Thank you!');
        }
        redirect("detail.php?id=$product_id");
    }
}

$id = req('id');
$stm = $_db->prepare('SELECT pv.*, p.product_name FROM product_variants pv JOIN product p ON pv.product_id = p.product_id WHERE pv.product_id = ? ORDER BY pv.size');
$stm->execute([$id]);
$variants = $stm->fetchAll();
if (!$variants) redirect('list.php');

// Select variant
$selected_variant_id = req('variant_id') ?: ($variants[0]->variant_id ?? null);
$p = null;
foreach ($variants as $v) {
    if ($v->variant_id == $selected_variant_id) {
        $p = $v;
        break;
    }
}
if (!$p) $p = $variants[0];

// Get average rating for this product
$stm = $_db->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM product_reviews WHERE product_id = ?");
$stm->execute([$id]);
$rating_data = $stm->fetch();
$avg_rating = round($rating_data->avg_rating ?? 0, 1);
$total_reviews = $rating_data->total ?? 0;

// Get all reviews for this product
$stm = $_db->prepare("
    SELECT r.*, u.email 
    FROM product_reviews r 
    JOIN user u ON r.user_id = u.user_id 
    WHERE r.product_id = ? 
    ORDER BY r.created_at DESC
");
$stm->execute([$id]);
$reviews = $stm->fetchAll();

// Get user's existing review (if logged in)
$user_review = null;
if ($_user) {
    $stm = $_db->prepare("SELECT * FROM product_reviews WHERE user_id = ? AND product_id = ?");
    $stm->execute([$_user->user_id, $id]);
    $user_review = $stm->fetch();
}

// ----------------------------------------------------------------------------

$_title = 'Product | Detail';
include '../_head.php';
?>

<style>
    #photo {
        display: block;
        border: 1px solid #333;
        width: 200px;
        height: 200px;
    }
    
    .rating-stars {
        font-size: 20px;
        margin: 10px 0;
    }
    
    .review-box {
        border: 1px solid #ddd;
        padding: 15px;
        margin: 15px 0;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    
    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-weight: bold;
    }
    
    .review-rating {
        color: gold;
        margin-bottom: 10px;
    }
    
    .review-text {
        margin-top: 10px;
        line-height: 1.5;
    }
    
    .review-form {
        background-color: #f0f0f0;
        padding: 20px;
        border-radius: 5px;
        margin: 20px 0;
    }
    
    .review-form select, 
    .review-form textarea {
        padding: 8px;
        margin: 10px 0;
        width: 100%;
        max-width: 400px;
    }
    
    .review-form button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }
    
    .review-form button:hover {
        background-color: #45a049;
    }
    
    .avg-rating {
        font-size: 24px;
        margin: 20px 0;
        padding: 10px;
        background-color: #f5f5f5;
        border-radius: 5px;
    }
</style>

<p>
    <img src="/images/<?= $p->photo ?>" id="photo">
</p>

<table class="table detail">
    <tr>
        <th>ID</th>
        <td><?= $p->product_id ?></td>
    </tr>
    <tr>
        <th>Type of Product</th>
        <td><?= $p->product_name ?></td>        
    </tr>
    <tr>
        <th>Price</th>
        <td>RM <?= $p->price ?></td>
    </tr>
    <tr>
        <th>Select Size:</th>
        <td>
            <form method="get" style="display: inline;">
            <input type="hidden" name="id" value="<?= $id ?>">
            <select name="variant_id" id="variant_id" onchange="this.form.submit()">
                <?php foreach ($variants as $v): ?>
                    <option value="<?= $v->variant_id ?>" <?= $v->variant_id == $p->variant_id ? 'selected' : '' ?>><?= $v->size ?? 'Default' ?></option>
                <?php endforeach; ?>
            </select>
    </form>
    
        </td>        
    </tr>
    <tr>
        <th>Unit</th>
        <td>
            <form method="post">
                <?php
                $cart = get_cart();
                $unit = $cart[$p->variant_id] ?? 0;
                ?>
                <?= html_hidden('id', $p->variant_id) ?>
                <?= html_select('quantity', $_units, $unit, '', 'id="quantity"') ?>               
                <?php
                echo $unit ? " ✅ In cart: $unit" : '';
                ?>
                <div style="margin-top: 15px;">
                    <button type="submit">Add to Cart</button>
                </div>
            </form>
        </td>
    </tr>
</table>

<!-- RATINGS SECTION -->
<h2>⭐ Ratings & Reviews</h2>

<div class="avg-rating">
    <strong>Average Rating:</strong>
    <?php if ($total_reviews > 0): ?>
        <?php for($i = 1; $i <= 5; $i++): ?>
            <?= $i <= $avg_rating ? '⭐' : '☆' ?>
        <?php endfor; ?>
        <span style="font-size: 16px;">(<?= $avg_rating ?> / 5 from <?= $total_reviews ?> reviews)</span>
    <?php else: ?>
        <span>No reviews yet. Be the first to review!</span>
    <?php endif; ?>
</div>

<!-- REVIEW FORM (Only for logged in users) -->
<?php if ($_user): ?>
    <div class="review-form">
        <h3><?= $user_review ? 'Edit Your Review' : 'Write a Review' ?></h3>
        <form method="post">
            <?= html_hidden('id', $p->product_id) ?>
            
            <label><strong>Rating (1-5 stars):</strong></label><br>
            <select name="rating" required>
                <option value="">Select rating</option>
                <option value="5" <?= $user_review && $user_review->rating == 5 ? 'selected' : '' ?>>⭐⭐⭐⭐⭐ - Excellent (5)</option>
                <option value="4" <?= $user_review && $user_review->rating == 4 ? 'selected' : '' ?>>⭐⭐⭐⭐ - Good (4)</option>
                <option value="3" <?= $user_review && $user_review->rating == 3 ? 'selected' : '' ?>>⭐⭐⭐ - Average (3)</option>
                <option value="2" <?= $user_review && $user_review->rating == 2 ? 'selected' : '' ?>>⭐⭐ - Poor (2)</option>
                <option value="1" <?= $user_review && $user_review->rating == 1 ? 'selected' : '' ?>>⭐ - Terrible (1)</option>
            </select>
            <br>
            
            <label><strong>Your Review:</strong></label><br>
            <textarea name="review" rows="4" cols="50" placeholder="Share your experience with this product..."><?= $user_review ? htmlspecialchars($user_review->review) : '' ?></textarea>
            <br>
            
            <button type="submit"><?= $user_review ? 'Update Review' : 'Submit Review' ?></button>
        </form>
    </div>
<?php else: ?>
    <p style="margin: 20px 0;">
        <a href="../login.php">Login</a> to write a review.
    </p>
<?php endif; ?>

<!-- ALL REVIEWS LIST -->
<h3>Customer Reviews (<?= $total_reviews ?>)</h3>

<?php if (count($reviews) > 0): ?>
    <?php foreach($reviews as $review): ?>
        <div class="review-box">
            <div class="review-header">
                <span>✍️ <?= htmlspecialchars($review->email) ?></span>
                <span>📅 <?= date('d/m/Y H:i', strtotime($review->created_at)) ?></span>
            </div>
            <div class="review-rating">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <?= $i <= $review->rating ? '⭐' : '☆' ?>
                <?php endfor; ?>
            </div>
            <div class="review-text">
                <?= nl2br(htmlspecialchars($review->review)) ?>
            </div>
            <?php if ($_user && $_user->user_id == $review->user_id): ?>
                <small style="color: gray;">(Your review)</small>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No reviews yet. Be the first to share your experience!</p>
<?php endif; ?>

<p style="margin-top: 20px;">
    <button data-get="list.php">Back to List</button>
</p>

<script>
    // Handle cart quantity change
    $('select').not('#quantity').on('change', function(e) {
        e.target.form.submit();
    });
    
    // Handle back button
    $('button').on('click', function() {
        window.location = $(this).data('get');
    });
</script>

<?php
include '../_foot.php';
?>