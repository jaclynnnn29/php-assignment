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
        <a href="../product/list.php" style="padding: 12px 30px; background: #6e8efb; color: #fff; text-decoration: none; border-radius: 25px; font-weight: bold; box-shadow: 0 4px 15px rgba(110,142,251,0.4);">
            Browse Products
        </a>
    </div>
</div>
 
<style>
    .product-card {
        position: relative;
        overflow: hidden;
    }
    
    .product-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .product-card h4 {
        margin: 10px 0 5px;
        color: #333;
    }
    
    .product-card p {
        color: #6e8efb;
        font-weight: bold;
        margin-bottom: 10px;
    }
</style>

<div class="featured-products" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; padding: 20px;">
    <?php foreach ($featured as $p): ?>
        <div class="product-card" style="background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
            
            <img src="/images/<?= $p->photo ?>" style="width: 100%; height: 200px; object-fit: cover; border-radius: 5px;">
            
            <h4 style="margin: 10px 0 5px; color: #333;"><?= encode($p->product_name) ?></h4>
            
            <p style="color: #6e8efb; font-weight: bold; margin-bottom: 10px;">RM <?= number_format($p->price, 2) ?></p>
            
            <a href="../product/detail.php?id=<?= $p->product_id ?>" style="display: block; text-align: center; background: #6e8efb; color: white; padding: 8px; border-radius: 5px; text-decoration: none;">View Details</a>
            
        </div>
    <?php endforeach; ?>
</div>

<div class="features" style="display: flex; justify-content: space-around; padding: 40px; background: #f9f9f9;">
    <div class="feature-item">
        <i class="fa fa-truck" style="font-size: 2em; color: #6e8efb;"></i>
        <h3>Fast Delivery</h3>
        <p>Within Malaysia</p>
    </div>
    <div class="feature-item">
        <i class="fa fa-shield-alt" style="font-size: 2em; color: #6e8efb;"></i>
        <h3>Secure Payment</h3>
        <p>100% Protected</p>
    </div>
    <div class="feature-item">
        <i class="fa fa-headset" style="font-size: 2em; color: #6e8efb;"></i>
        <h3>Support</h3>
        <p>Always here for you</p>
    </div>
</div>

<?php
include '_foot.php'; 
?>