<?php
include '_base.php'; 

// ----------------------------------------------------------------------------
// $stm = $_db->query("SELECT * FROM product LIMIT 3");
// $featured = $stm->fetchAll();
// ----------------------------------------------------------------------------

$_title = 'Home';
include '_head.php'; 
?>

<div class="welcome-section" style="text-align: center; padding: 60px 20px;">
    <h2 style="font-size: 2.5em; color: #333;">Welcome to M&H Online Shopping</h2>
    <p style="font-size: 1.2em; color: #666; margin-bottom: 30px;">
        Discover our latest collection and enjoy the best shopping experience.
    </p>
    
    <div class="hero-buttons">
        <a href="/product/list.php" style="padding: 12px 30px; background: #6e8efb; color: #fff; text-decoration: none; border-radius: 25px; font-weight: bold;">
            Browse Products
        </a>
    </div>
</div>

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