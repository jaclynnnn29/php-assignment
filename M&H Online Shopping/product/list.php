<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $id = req('id');
    $unit = req('quantity');
    update_cart($id, $unit);
    redirect();
}

$search = req('search');
$query = 'SELECT * FROM product';
$params = [];
if ($search) {
    $query .= ' WHERE product_name LIKE ?';
    $params[] = '%' . $search . '%';
}
$stm = $_db->prepare($query);
$stm->execute($params);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'Product | List';
include '../_head.php';
?>

<style>
    #product {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .product {
        border: 1px solid #333;
        width: 250px;
        position: relative;
        padding-bottom: 50px;
    }

    .product img {
        display: block;
        width: 100%;
        height: 200px;
        object-fit: cover;
        cursor: pointer;
    }

    .product form,
    .product .cart-info {
        position: absolute;
        background: #0009;
        color: #fff;
        padding: 5px;
        text-align: center;
    }

    .product form {
        top: 5px;
        right: 5px;
    }

    .product form select {
        text-align: right;
    }

    .product .cart-info {
        bottom: 0;
        left: 0;
        right: 0;
    }
    
    .product-info {
        position: absolute;
        bottom: 30px;
        left: 0;
        right: 0;
        background: #0009;
        color: #fff;
        padding: 8px;
        font-size: 12px;
    }
    
    .product-info strong {
        font-size: 16px;
    }
    
    .favorite-btn {
        position: absolute;
        top: 5px;
        left: 5px;
        background: #fff9;
        padding: 5px;
        border-radius: 50%;
        text-decoration: none;
        font-size: 20px;
        z-index: 10;
    }
    
    .favorite-btn:hover {
        transform: scale(1.1);
    }
    
    .rating-stars {
        font-size: 12px;
        margin-top: 5px;
    }
</style>

<form method="get" style="margin-bottom: 20px;">
    <input type="text" name="search" value="<?= $search ?>" placeholder="Search products..." style="padding: 5px; width: 200px;">
    <button type="submit">Search</button>
    <?php if ($search): ?>
        <a href="?">Clear Search</a>
    <?php endif; ?>
</form>

<div id="product">
    <?php foreach ($arr as $p): 
        $cart = get_cart();
        $product_id = $p->product_id;
        $stm2 = $_db->prepare("SELECT variant_id FROM product_variants WHERE product_id = ? ORDER BY size LIMIT 1");
        $stm2->execute([$product_id]);
        $default_variant = $stm2->fetch();
        $variant_id = $default_variant->variant_id ?? $product_id;
        $unit = $cart[$variant_id] ?? 0;
        
        // Get average rating for this product
        $stm = $_db->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM product_reviews WHERE product_id = ?");
        $stm->execute([$p->product_id]);
        $rating_data = $stm->fetch();
        $avg_rating = round($rating_data->avg_rating ?? 0, 1);
        $total_reviews = $rating_data->total ?? 0;
        
        // Check if product is in user's favorites
        $is_favorite = false;
        if ($_user) {
            $stm = $_db->prepare("SELECT * FROM favorites WHERE user_id = ? AND product_id = ?");
            $stm->execute([$_user->user_id, $product_id]);
            $is_favorite = $stm->fetch() ? true : false;
        }
    ?>
    
        <div class="product">
            <!-- FAVORITE BUTTON -->
            <?php if ($_user): ?>
                <a href="add_favorite.php?id=<?= $product_id ?>" class="favorite-btn">
                    <?= $is_favorite ? '❤️' : '♡' ?>
                </a>
            <?php else: ?>
                <a href="../login.php" class="favorite-btn">♡</a>
            <?php endif; ?>
            
            <!-- CART FORM -->
            <form method="post">
                <?= $unit ? '✅' : '' ?>
                <?= html_hidden('id', $variant_id) ?>
                <?= html_select('quantity', $_units, $unit) ?>
            </form>
            
            <!-- PRODUCT IMAGE -->
            <img src="/images/<?= $p->photo ?>" 
                data-get="/product/detail.php?id=<?= $p->product_id ?>" 
                alt="Product Image">
            
            <!-- RATING STARS -->
            <div class="rating-stars" style="position: absolute; bottom: 70px; left: 8px; background: #0009; padding: 3px 8px; border-radius: 10px;">
                <?php if ($total_reviews > 0): ?>
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <?= $i <= $avg_rating ? '⭐' : '☆' ?>
                    <?php endfor; ?>
                    <span style="font-size: 10px;">(<?= $total_reviews ?>)</span>
                <?php else: ?>
                    <span style="font-size: 10px;">No reviews yet</span>
                <?php endif; ?>
            </div>
            
            <!-- PRODUCT INFO -->
            <div class="product-info">
                <strong><?= encode($p->product_name) ?></strong><br>
                <?= nl2br(encode($p->description)) ?>
            </div>
            
            <!-- CART INFO -->
            <div class="cart-info">
                In cart: <?= $unit ?>
            </div>
        </div>
    <?php endforeach ?>
</div>

<!-- WISHLIST LINK -->
<div style="margin: 20px; text-align: center;">
    <a href="favorites.php" style="font-size: 18px;">❤️ View My Wishlist</a>
</div>

<script>
    $('select').on('change', e => e.target.form.submit());
    
    // Image click to view detail
    $('img').on('click', function() {
        window.location = $(this).data('get');
    });
</script>

<?php
include '../_foot.php';
?>