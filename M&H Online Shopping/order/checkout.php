<?php
include '../_base.php';
auth('Member');

if (is_post()) {
    $cart = get_cart();
    if (!$cart) redirect('cart.php');

    // Fetch all prices at once instead of inside the loop
    $ids = array_keys($cart);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stm_prices = $_db->prepare("SELECT product_id, price FROM product WHERE product_id IN ($placeholders)");
    $stm_prices->execute($ids);
    $prices = $stm_prices->fetchAll(PDO::FETCH_KEY_PAIR);

    $total_qty = 0;
    $total_amt = 0;

    foreach ($cart as $id => $unit) {
        $total_amt += ($prices[$id] * $unit);
        $total_qty += $unit;

    try {
        $_db->beginTransaction();

        // 2. Insert into 'order' table with the REAL totals
        $stm = $_db->prepare("INSERT INTO `order` (datetime, total, quantity, user_id, status) VALUES (NOW(), ?, ?, ?, 'Pending')");
        $stm->execute([$total_amt, $total_qty, $_user->user_id]);

        $order_id = $_db->lastInsertId();

        // 3. Insert items
        $stm_item = $_db->prepare('INSERT INTO item (order_id, variant_id, unit, price) VALUES (?, ?, ?, ?)');
        foreach ($cart as $variant_id => $unit) {
            $s = $_db->prepare('SELECT price FROM product_variants WHERE variant_id = ?');
            $s->execute([$variant_id]);
            $price = $s->fetchColumn();

            $stm_item->execute([$order_id, $variant_id, $unit, $price]);
        }

        $_db->commit();
        set_cart(); // Clear the cart after successful checkout

        temp('info', 'Order placed successfully!');
        redirect("payment.php?id=$order_id");
        
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
