<?php
include '_base.php'; 

// 1. Logic & Data Fetching (Do this FIRST)
$id = req('id');

$stm = $_db->prepare('
    SELECT p.*, c.cat_name 
    FROM product p 
    JOIN categories c ON p.cat_id = c.cat_id 
    WHERE p.product_id = ?
');
$stm->execute([$id]);
$p = $stm->fetch();

if (!$p) redirect('index.php');

$stm = $_db->prepare('SELECT * FROM product_variants WHERE product_id = ? ORDER BY size');
$stm->execute([$id]);
$variants = $stm->fetchAll();

// 2. Handle Form Submission (Before any HTML is sent)
if (is_post()) {
    $v_id = post('variant_id');
    $unit = post('unit');

    if (is_exists($v_id, 'product_variants', 'variant_id')) {
        update_cart($v_id, $unit);
        temp('info', 'Item added to cart');
        redirect('cart.php'); // This will fail if _head.php is included above!
    }
}

// 3. Page Setup & Header (Only after all logic is finished)
$_title = "Product Details | $p->product_name";
include '_head.php'; 
?>
