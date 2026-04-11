<?php
include '../_base.php';

// ----------------------------------------------------------------------------

auth('Member');

if (is_post()) {
    // (2) Get shopping cart (reject if empty)
    // TODO
    $cart = get_cart();
    if (!$cart) redirect('cart.php');

    // ------------------------------------------
    // DB transaction (insert order and items)
    // ------------------------------------------

    // (A) Begin transaction
    // TODO
    $_db->beginTransaction();

    try {

    // (B) Insert order, keep order id
    // TODO
    $stm = $_db->prepare('
        INSERT INTO `order`(datetime,user_id)
        VALUES (NOW(),?)
    ');
    $stm->execute([$_user->user_id]);
    $id = $_db->lastInsertId();
    // (C) Insert items
    // TODO
        $stm = $_db->prepare('
            INSERT INTO `item` (order_id, product_id, price, unit, subtotal)
            SELECT ?, product_id, price, ?, price * ? 
            FROM product WHERE product_id = ?
        ');
        foreach ($cart as $product_id => $unit) {
            $stm->execute([$id, $unit, $unit, $product_id]);
        }
    // (D) Update order (count and total)
    // TODO
        $stm = $_db->prepare('
            UPDATE `order` 
            SET quantity = (SELECT SUM(unit) FROM item WHERE order_id = ?),
                total = (SELECT SUM(subtotal) FROM item WHERE order_id = ?)
            WHERE order_id = ?
        ');
        $stm->execute([$id, $id, $id]);

    // (E) Commit transcation
    // TODO
    $_db->commit();

    // ------------------------------------------

    // (3) Clear shopping cart
    // TODO
    set_cart();

    // (4) Redirect to payment.php?id=XXX
    // TODO
    temp('info', 'Order created. Please proceed to payment.');
    redirect("payment.php?id=$id");
    
} catch (Exception $e) {
    // Rollback on error
    $_db->rollBack();
    temp('error', 'Checkout Failed: ' . $e->getMessage());
    redirect('cart.php');

   } 
}

// ----------------------------------------------------------------------------
