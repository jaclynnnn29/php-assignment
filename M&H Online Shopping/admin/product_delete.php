<?php
require '../_base.php';
auth('Admin');

// 1. Get the product_id from the URL
$id = req('id');

// 2. Fetch product first to find the photo filename
$stm = $_db->prepare("SELECT photo FROM product WHERE product_id = ?");
$stm->execute([$id]);
$photo = $stm->fetchColumn();

// 3. Delete the photo file from your folder
if ($photo) {
    // Make sure this path matches where your save_photo() function stores files
    @unlink("../images/$photo"); 
}

// 4. Delete the actual record from the product table
$stm = $_db->prepare("DELETE FROM product WHERE product_id = ?");
$stm->execute([$id]);

temp('info', "Product $id has been deleted.");

// 5. ALWAYS redirect back to the Product List
redirect('product_list.php'); 
?>