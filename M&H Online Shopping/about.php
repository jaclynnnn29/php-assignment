<?php
include '_base.php'; 

$_title = 'About Us';
include '_head.php'; 
?>

<div class="about-container" style="padding: 40px 50px; line-height: 1.6; color: #333;">
    <section class="about-hero" style="text-align: center; margin-bottom: 50px;">
        <h2 style="font-size: 2.5em; color: #6e8efb;">About M&H Online Shopping</h2>
        <p style="font-size: 1.2em; max-width: 800px; margin: 20px auto;">
            We are a leading e-commerce platform in Malaysia, dedicated to providing high-quality fashion and lifestyle products to our valued customers.
        </p>
    </section>

    <div class="about-content" style="display: flex; gap: 40px; align-items: center; margin-bottom: 50px;">
        <div style="flex: 1;">
            <img src="/images/about_store.jpg" alt="Our Store" style="width: 100%; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
        </div>
        <div style="flex: 1;">
            <h3>Our Mission</h3>
            <p>
                Our mission is to simplify online shopping by offering a curated selection of products combined with secure payment methods and fast delivery services within Malaysia.
            </p>
            <h3>Why Choose Us?</h3>
            <ul style="list-style: none; padding: 0;">
                <li><i class="fa fa-check-circle" style="color: #6e8efb; margin-right: 10px;"></i> 100% Secure Payments</li>
                <li><i class="fa fa-check-circle" style="color: #6e8efb; margin-right: 10px;"></i> Fast Delivery across Malaysia</li>
                <li><i class="fa fa-check-circle" style="color: #6e8efb; margin-right: 10px;"></i> Dedicated Support</li>
            </ul>
        </div>
    </div>

    <section class="contact-info" style="background: #f9f9f9; padding: 30px; border-radius: 10px; text-align: center;">
        <h3>Visit Us</h3>
        <p>If you have any questions, feel free to visit our office or reach out to our support team.</p>
        <a href="/contact.php" style="display: inline-block; margin-top: 15px; padding: 10px 25px; background: #6e8efb; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold;">Contact Us</a>
    </section>
</div>

<?php
include '_foot.php'; 
?>
