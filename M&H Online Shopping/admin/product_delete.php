<?php
require '../_base.php';
auth('Admin');

// 1. Get the product_id from the URL
$id = req('id');

// 2. Fetch product first to find the photo filename (optional but recommended)
$stm = $_db->prepare("SELECT photo FROM product WHERE product_id = ?");
$stm->execute([$id]);
$photo = $stm->fetchColumn();

// 3. Delete the photo file from your folder so you don't waste space
if ($photo) {
    @unlink("../images/$photo");
}

// 4. Delete the actual record from the product table
$stm = $_db->prepare("DELETE FROM product WHERE product_id = ?");
$stm->execute([$id]);

temp('info', "Product $id has been deleted.");

// 5. Redirect back to the Product List (not the user list!)
redirect('product_list.php');