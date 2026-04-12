<?php
include '../_base.php';
auth('Member');

if (is_post()) {
    $cart = get_cart();
    if (!$cart) redirect('cart.php');

    $_db->beginTransaction();

    try {
        // (B) Insert into 'order' table using your specific columns
        // Note: Default 'status' to 'Pending' if not set in DB defaults
        $stm = $_db->prepare('
            INSERT INTO `order` (user_id, status)
            VALUES (?, "Pending")
        ');
        $stm->execute([$_user->user_id]);
        $id = $_db->lastInsertId();

        // (C) Insert into 'item' table
        // Ensure your 'item' table columns match: order_id, product_id, unit, subtotal
        $stm = $_db->prepare('
            INSERT INTO `item` (order_id, product_id, unit, subtotal)
            SELECT ?, product_id, ?, price * ? 
            FROM product WHERE product_id = ?
        ');
        
        foreach ($cart as $product_id => $unit) {
            $stm->execute([$id, $unit, $unit, $product_id]);
        }

        // (D) Update the 'order' table totals
        $stm = $_db->prepare('
            UPDATE `order` 
            SET quantity = (SELECT SUM(unit) FROM item WHERE order_id = ?),
                total = (SELECT SUM(subtotal) FROM item WHERE order_id = ?)
            WHERE order_id = ?
        ');
        $stm->execute([$id, $id, $id]);

        $_db->commit();
        set_cart(); // Clear cart after successful DB commit

        temp('info', 'Order created successfully.');
        redirect("/payment.php?id=$id");
        
    } catch (Exception $e) {
        $_db->rollBack();
        temp('error', 'Checkout Failed: ' . $e->getMessage());
        redirect('cart.php');
    } 
}



// ... (Your POST logic remains the same) ...

$_title = 'Checkout';
include '../_head.php';

$cart = get_cart();

// (1) Fetch product details for items in the cart
$ids = array_keys($cart);
$items = [];
$subtotal = 0;

if ($ids) {
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stm = $_db->prepare("SELECT * FROM product WHERE product_id IN ($placeholders)");
    $stm->execute($ids);
    $items = $stm->fetchAll();
}
?>

<main>
    <div class="checkout-wrapper">
        <div class="checkout-left">
            <h1>Shopping Cart</h1>
            
            <table class="table plain-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $p): ?>
                        <?php 
                            $unit = $cart[$p->product_id];
                            $item_total = $p->price * $unit;
                            $subtotal += $item_total;
                        ?>
                        <tr>
                            <td class="product-col">
                                <img src="/images/<?= $p->photo ?>" alt="Photo" style="width: 50px;">
                                <span><?= $p->product_name ?></span>
                            </td>
                            <td><?= $unit ?></td>
                            <td>$<?= number_format($item_total, 2) ?></td>
                            <td class="action-col"><i class="fa fa-times"></i></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="totals-section">
                <div class="total-row grand-total">Total: <span>$<?= number_format($subtotal, 2) ?></span></div>


            <div class="checkout-right">
            <form method="post">
            <button type="submit" class="btn-checkout">Check Out</button>
        
            <div class="back-link-container">
                <a href="product_list.php" class="back-link">
                    <i class="fa fa-angle-left"></i> Continue Shopping
                </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php
include '../_foot.php';

// ----------------------------------------------------------------------------
