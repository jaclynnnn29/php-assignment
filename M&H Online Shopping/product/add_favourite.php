<?php
include '../_base.php';

// Check if user is logged in
if (!$_user) {
    temp('info', 'Please login to add to wishlist');
    redirect('../login.php');
}

$product_id = req('id');

if (!$product_id) {
    redirect('list.php');
}

// Check if already in favorites
$stm = $_db->prepare("SELECT * FROM favorites WHERE user_id = ? AND product_id = ?");
$stm->execute([$_user->user_id, $product_id]);
$exists = $stm->fetch();

if ($exists) {
    // Remove from favorites
    $stm = $_db->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
    $stm->execute([$_user->user_id, $product_id]);
    temp('info', 'Removed from wishlist');
} else {
    // Add to favorites
    $stm = $_db->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
    $stm->execute([$_user->user_id, $product_id]);
    temp('info', 'Added to wishlist! ❤️');
}

// Go back to previous page
redirect($_SERVER['HTTP_REFERER'] ?? 'list.php');
?>