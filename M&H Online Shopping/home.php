<?php
include '_base.php'; 

// ----------------------------------------------------------------------------
$stm = $_db->query("
    SELECT p.*, v.price 
    FROM product p
    JOIN product_variants v ON p.product_id = v.product_id
    GROUP BY p.product_id 
    ORDER BY RAND() 
    LIMIT 8
");
$featured = $stm->fetchAll();

//$stm = $_db->query("SELECT * FROM product ORDER BY RAND() LIMIT 8");
//$featured = $stm->fetchAll();
// ----------------------------------------------------------------------------

$_title = 'Home';
include '_head.php'; 
?>

<div class="welcome-section">
    <h2>Welcome to M&H Online Shopping</h2>
    <p>Discover our latest collection and enjoy the best shopping experience.</p>
    
    <div class="hero-buttons">
        <a href="../product/list.php" class="btn-browse">
            Browse Products
        </a>
    </div>
</div>

<div class="featured-products">
    <?php foreach ($featured as $p): ?>
        <div class="product-card">
            <img src="/images/<?= $p->photo ?>" alt="<?= encode($p->product_name) ?>">
            <h4><?= encode($p->product_name) ?></h4>
            <p class="price">RM <?= number_format($p->price, 2) ?></p>
            <a href="../product/detail.php?id=<?= $p->product_id ?>" class="btn-view">View Details</a>
        </div>
    <?php endforeach; ?>
</div>

<div class="features">
    <div class="feature-item">
        <i class="fa fa-truck"></i>
        <h3>Fast Delivery</h3>
        <p>Within Malaysia</p>
    </div>
    <div class="feature-item">
        <i class="fa fa-shield-alt"></i>
        <h3>Secure Payment</h3>
        <p>100% Protected</p>
    </div>
    <div class="feature-item">
        <i class="fa fa-headset"></i>
        <h3>Support</h3>
        <p>Always here for you</p>
    </div>
</div>

<?php
include '_foot.php'; 
?>