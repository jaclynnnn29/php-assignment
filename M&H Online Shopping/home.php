<?php
include '_base.php'; 

// ----------------------------------------------------------------------------
$stm = $_db->query("SELECT * FROM product ORDER BY RAND() LIMIT 8");
$featured = $stm->fetchAll();
// ----------------------------------------------------------------------------

$_title = 'Home';
include '_head.php'; 
?>

<div class="welcome-section" 
    style="text-align: center; 
    padding: 100px 20px;
    background-image: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0)), url('/images/your-background.jpg'); 
    background-size: cover; 
    background-position: center;
    background-attachment: fixed;
    ">

    <h2 style="font-size: 2.5em; color: #fff1f1; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">Welcome to M&H Online Shopping</h2>
    <p style="font-size: 1.2em; color: #fff1f1; margin-bottom: 30px; font-weight: 500;">
        Discover our latest collection and enjoy the best shopping experience.
    </p>
    
    <div class="hero-buttons">
        <a href="/product/list.php" style="padding: 12px 30px; background: #6e8efb; color: #fff; text-decoration: none; border-radius: 25px; font-weight: bold; box-shadow: 0 4px 15px rgba(110,142,251,0.4);">
            Browse Products
        </a>
    </div>
</div>

// change idk ahh aaaaaaa
<div class="popular-products" style="padding: 40px 50px;">
    <h2 style="text-align: center; margin-bottom: 30px;">Popular Products</h2>
    
    <div class="product-grid">
        <?php if (!empty($products)): ?>
            
            <?php foreach ($products as $p): ?>
                <div class="product-card">
                    <a href="/product/detail.php?id=<?= $p->product_id ?>">
                        <img src="/images/<?= $p->photo ?>" alt="<?= encode($p->product_name) ?>">
                        <h4><?= encode($p->product_name) ?></h4>
                        <p class="price">RM <?= number_format($p->price, 2) ?></p>
                    </a>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p style="text-align: center; grid-column: 1 / -1;">No products found.</p>
        <?php endif; ?>
    </div>
</div>

//change

<div class="features" style="display: flex; justify-content: space-around; padding: 40px; background: #f9f9f9;">
    <div class="feature-item">
        <i class="fa fa-truck" style="font-size: 2em; color: #99f2e8;"></i>
        <h3>Fast Delivery</h3>
        <p>Within Malaysia</p>
    </div>
    <div class="feature-item">
        <i class="fa fa-shield-alt" style="font-size: 2em; color: #99f2e8;"></i>
        <h3>Secure Payment</h3>
        <p>100% Protected</p>
    </div>
    <div class="feature-item">
        <i class="fa fa-headset" style="font-size: 2em; color: #99f2e8;"></i>
        <h3>Support</h3>
        <p>Always here for you</p>
    </div>
</div>

<?php
include '_foot.php'; 
?>