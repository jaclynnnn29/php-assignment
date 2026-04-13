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
            INSERT INTO `order` (user_id, datetime, total, quantity)
            VALUES (?, NOW(), 0, 0)
        ');
        $stm->execute([$_user->user_id]);
        $id = $_db->lastInsertId();

        // (C) Insert into 'item' table
        // We join product and variants to get the correct product_id and price
        $stm = $_db->prepare('
            INSERT INTO `item` (order_id, product_id, unit, subtotal)
            SELECT ?, pv.product_id, ?, pv.price * ? 
            FROM product_variants pv
            JOIN product p ON pv.product_id = p.product_id
            WHERE pv.variant_id = ?
        ');
        
        $total_qty = 0;
        $total_amt = 0;
        
        // We need the price to calculate totals in PHP to avoid MySQL Update subquery errors
        $stm_price = $_db->prepare('SELECT price FROM product_variants WHERE variant_id = ?');

        foreach ($cart as $variant_id => $unit) {
            $stm_price->execute([$variant_id]);
            $v = $stm_price->fetch();
            
            $total_qty += $unit;
            $total_amt += ($v->price * $unit);

            $stm->execute([$id, $unit, $unit, $variant_id]);
        }

        // (D) Update the 'order' table totals
        $stm = $_db->prepare('
            UPDATE `order` SET quantity = ?, total = ? WHERE order_id = ?
        ');
        $stm->execute([$total_qty, $total_amt, $id]);

        $_db->commit();
        set_cart(); // Clear cart after successful DB commit

        temp('info', 'Order created successfully.');
        redirect("payment.php?id=$id");
        
    } catch (Exception $e) {
        $_db->rollBack();
        temp('info', 'Checkout Failed: ' . $e->getMessage());
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
    $stm = $_db->prepare("
        SELECT pv.variant_id, p.product_id, p.product_name, pv.price, p.photo, pv.size
        FROM product_variants pv
        JOIN product p ON pv.product_id = p.product_id
        WHERE pv.variant_id IN ($placeholders)");
    $stm->execute($ids);
    $items = $stm->fetchAll();
}
?>

<style>
    .plain-table {
        width: auto !important; /* Shrinks the table to fit its content */
        min-width: 550px;
    }
    .plain-table th, .plain-table td {
        padding: 8px 12px !important; /* Reduces bulky 15px padding */
        font-size: 0.9rem; /* Makes the list look more professional and compact */
    }
    .btn-checkout.small {
        width: auto !important; /* Overrides the 100% width in app.css */
        padding: 10px 30px !important;
        font-size: 1rem !important;
    }
</style>

<main>
    <div class="checkout-wrapper">
        <div class="checkout-left">
            <h1>Checkout Order</h1>
            
            <table class="table plain-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $p): ?>
                        <?php 
                            $unit = $cart[$p->variant_id];
                            $item_total = $p->price * $unit;
                            $subtotal += $item_total;
                        ?>
                        <tr>
                            <td class="product-col">
                                <img src="/images/<?= $p->photo ?>" alt="Photo" style="width: 40px; vertical-align: middle; margin-right: 10px;">
                                <span><?= $p->product_name ?></span>
                            </td>
                            <td><?= $p->size ?></td>
                            <td><?= $unit ?></td>
                            <td>RM <?= number_format($item_total, 2) ?></td>
                            <td class="action-col">
                                <a href="cart.php?id=<?= $p->variant_id ?>&unit=0" style="color: red; text-decoration: none;" onclick="return confirm('Remove this item?')"><i class="fa fa-times"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="totals-section">
                <div class="total-row grand-total">Total: <span>RM <?= number_format($subtotal, 2) ?></span></div>
            </div>
        </div>

        <div class="checkout-right">
            <form method="post" style="display: flex; gap: 15px; align-items: center; margin-top: 20px;">
                <button type="submit" 
                        class="btn-checkout small" 
                        data-confirm="Are you sure you want to place this order?">
                    Check Out
                </button>
        
                <a href="../product/list.php" class="back-link" style="white-space: nowrap;">
                    <i class="fa fa-angle-left"></i> Continue Shopping
                </a>
            </form>
        </div>
    </div>
</main>

<?php
include '../_foot.php';

// ----------------------------------------------------------------------------
