<?php
include '../_base.php';

// ----------------------------------------------------------------------------

<<<<<<< HEAD
// (1) Authorization (member)
// TODO
=======
>>>>>>> 8d47c8f44c9096f21b5050a53be446f634ca31ea
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
<<<<<<< HEAD
    
    // (B) Insert order, keep order id
    // TODO
    $stm = $_db->prepare('
        INSERT INTO `order` (datetime, user_id)
        VALUES (NOW(), ?)
    ');
    $stm->execute([$_user->id]);
    $id = $_db->lastInsertId();

    // (C) Insert items
    // TODO
    $stm = $_db->prepare('
        INSERT INTO item (order_id, product_id, price, unit, subtotal)
        VALUES (?, ?, (SELECT price FROM product WHERE id = ?), ?, price * unit)
    ');
    foreach ($cart as $product_id => $unit){
        $stm->execute([$id, $product_id, $product_id, $unit]);
    }

    // (D) Update order (count and total)
    // TODO
    $stm = $_db->prepare('
        UPDATE `order` 
        SET count = (SELECT SUM(unit) FROM item WHERE order_id = ?),
            total = (SELECT SUM(subtotal) FROM item WHERE order_id = ?)
        WHERE id = ?
    ');
    $stm->execute([$id, $id, $id]);
=======

    // (B) Insert order, keep order id
    // TODO
    $stm = $_db->prepare('
        INSERT INTO `order`(datetime,user_id)
        VALUES (NOW(),?)
    ');
    $stm->execute([$_user['id']]);
    $id = $_db->lastInsertId();
    // (C) Insert items
    // TODO
        $stm = $_db->prepare('
            INSERT INTO `order_item`(order_id,product_id,price,unit,subtotal)
            VALUES (?,?,(SELECT price FROM product WHERE id = ?),?,price * unit)
        ');
        foreach ($cart as $product_id => $unit) {
            $stm->execute([$id, $product_id, $product_id, $unit]);
        }
    // (D) Update order (count and total)
    // TODO
        $stm = $_db->prepare('
            UPDATE `order` 
            SET count = (SELECT SUM(unit) FROM item WHERE order_id = ?),
                total = (SELECT SUM(subtotal) FROM item WHERE order_id = ?)
            WHERE id = ?
        ');
        $stm->execute([$id, $id, $id]);
>>>>>>> 8d47c8f44c9096f21b5050a53be446f634ca31ea

    // (E) Commit transcation
    // TODO
    $_db->commit();

    // ------------------------------------------

    // (3) Clear shopping cart
    // TODO
    set_cart();

    // (4) Redirect to detail.php?id=XXX
    // TODO
    temp('info', 'Record inserted');
<<<<<<< HEAD
    redirect("detail.php?id=$id");
}

redirect('cart.php');
=======
    redirect("detail.php?id= $id");
}
>>>>>>> 8d47c8f44c9096f21b5050a53be446f634ca31ea

// ----------------------------------------------------------------------------
