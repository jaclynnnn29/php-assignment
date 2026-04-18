<?php
include '../_base.php';
auth('Member'); 

// 1. Get current cart data
$cart = get_cart();

// 2. If cart is empty, send them back
if (empty($cart)) {
    temp('info', 'Your cart is empty.');
    redirect('cart.php');
}

// 3. Handle "Confirm Order" Button
if (is_post()) {
    $user_id = $_user->user_id;
    $total_amount = post('total_amount'); // Passed from hidden field

    // Start a transaction to ensure everything saves or nothing saves
    $_db->beginTransaction();

    try {
        // A. Create the Order Record
        $stm = $_db->prepare("INSERT INTO orders (user_id, total_price, status, order_date) VALUES (?, ?, 'Pending', NOW())");
        $stm->execute([$user_id, $total_amount]);
        $order_id = $_db->lastInsertId();

        // B. Create the Order Items (the details)
        $stm_item = $_db->prepare("INSERT INTO order_items (order_id, variant_id, unit, unit_price) VALUES (?, ?, ?, ?)");
        
        // C. Loop through cart to save each item and its current price
        $stm_price = $_db->prepare("SELECT price FROM product_variants WHERE variant_id = ?");

        foreach ($cart as $id => $unit) {
            $stm_price->execute([$id]);
            $price = $stm_price->fetchColumn();

            $stm_item->execute([$order_id, $id, $unit, $price]);
        }

        $_db->commit(); // Save everything to DB

        // D. Clear the cart and go to payment
        set_cart(); 
        redirect("/order/payment.php?order_id=$order_id");

    } catch (Exception $e) {
        $_db->rollBack(); // Something went wrong, undo DB changes
        temp('error', 'Failed to process order. Please try again.');
    }
}

$_title = 'Checkout Order';
include '../_head.php';
?>

<main>
    <div class="solid-container">
        <h2>Confirm Your Order</h2>

        <table class="table solid-table">
            <thead>
                <tr>
                    <th>Product Details</th>
                    <th class="center">Qty</th>
                    <th class="right">Price (RM)</th>
                    <th class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_payable = 0;
                
                // Fetch variant and product name together
                $stm = $_db->prepare('
                    SELECT pv.*, p.product_name 
                    FROM product_variants pv 
                    JOIN product p ON pv.product_id = p.product_id 
                    WHERE pv.variant_id = ?
                ');

                foreach ($cart as $id => $unit):
                    $stm->execute([$id]);
                    $p = $stm->fetch();
                    if (!$p) continue;

                    $subtotal = $p->price * $unit;
                    $total_payable += $subtotal;
                ?>
                    <tr>
                        <td>
                            <strong><?= $p->product_name ?></strong><br>
                            <small>Size: <?= $p->size ?> | Color: <?= $p->colour ?></small>
                        </td>
                        <td class="center"><?= $unit ?></td>
                        <td class="right"><?= number_format($p->price, 2) ?></td>
                        <td class="right"><?= number_format($subtotal, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" class="right">Total Payable:</td>
                    <td class="right">RM <?= number_format($total_payable, 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="checkout-actions">
            <a href="cart.php" class="btn-cancel">
                ← Edit Cart
            </a>
            
            <form method="post">
                <input type="hidden" name="total_amount" value="<?= $total_payable ?>">
                
                <button type="submit" class="btn-payment">
                     PROCEED TO PAYMENT →
                </button>
            </form>
        </div>
    </div>
</main>

<?php include '../_foot.php'; ?>