<?php
include '../_base.php';
auth();

// ----------------------------------------------------------------------------

if (is_post()) {
    $id = req('id'); 
    $unit = req('quantity');
    
    // Update cart using the correct table 'product_variants'
    update_cart($id, $unit);
    redirect();
}

$search = req('search');
$category = req('category');

// 1. Fetch categories for the filter dropdown
$stm = $_db->query("SELECT * FROM categories ORDER BY cat_name");
$categories = $stm->fetchAll();
$cat_list = [];
foreach ($categories as $c) {
    $cat_list[$c->cat_id] = $c->cat_name;
}

// 2. Build the search query
// Build the search query to include Product ID
$query = 'SELECT * FROM product WHERE 1=1';
$params = [];

if ($search) {
    // This allows you to search by name OR by the ID (P20002)
    $query .= ' AND (product_name LIKE ? OR product_id LIKE ?)';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}

if ($category) {
    $query .= ' AND cat_id = ?';
    $params[] = $category;
}
$stm = $_db->prepare($query);
$stm->execute($params);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'Product | List';
include '../_head.php';
?>

<div class="search-container">
    <form method="get">
        <input type="text" name="search" value="<?= encode($search) ?>" placeholder="Search products...">
        
        <?= html_select('category', $cat_list, $category, '- All Categories -') ?>
        
        <button type="submit">Search</button>
        
        <?php if ($search || $category): ?>
            <a href="?" class="search-clear-link">Clear</a>
        <?php endif; ?>
        
        <a href="favourite.php" class="wishlist-link">
            <span class="heart-icon">❤️</span> View Wishlist
        </a>
    </form>
</div>

<div id="product">
    <?php foreach ($arr as $p): 
        $cart = get_cart();
        $product_id = $p->product_id;

        // Fetch the specific variant and price
        $stm2 = $_db->prepare("SELECT variant_id, price FROM product_variants WHERE product_id = ? LIMIT 1");
        $stm2->execute([$product_id]);
        $v = $stm2->fetch();
        
        $variant_id = $v->variant_id ?? $product_id; 
        $unit = $cart[$variant_id] ?? 0;
        $display_price = $v->price ?? 0.00;

        // Check favorites
        $is_fav = false;
        if ($_user) {
            $stm_f = $_db->prepare("SELECT 1 FROM favorites WHERE user_id = ? AND product_id = ?");
            $stm_f->execute([$_user->user_id, $product_id]);
            $is_fav = (bool)$stm_f->fetch();
        }
    ?>

    <div class="product">
        <a href="add_favourite.php?id=<?= $product_id ?>" class="favorite-btn">
            <?= $is_fav ? '❤️' : '♡' ?>
        </a>

        <form method="post">
            <?= html_hidden('id', $variant_id) ?>
            <?= html_select('quantity', $_units, $unit, '', 'class="cart-select"') ?>
        </form>

        <img src="../images/<?= $p->photo ?>" 
             onclick="location='detail.php?id=<?= $product_id ?>'">

        <div class="product-info">
            <strong><?= encode($p->product_name) ?></strong><br>
            <span class="product-price">RM <?= number_format($display_price, 2) ?></span>
        </div>

        <div class="cart-info">
            In Cart: <?= (int)$unit ?>
        </div>
    </div>
    <?php endforeach ?>
</div>

<script>
    $('.cart-select').on('change', e => e.target.form.submit());
</script>

<?php include '../_foot.php'; ?>