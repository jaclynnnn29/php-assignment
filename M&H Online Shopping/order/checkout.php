<?php
include '../_base.php';
auth('Member'); // Ensure only logged-in members can see this

// --- Logic ---
$cart = get_cart();

// Redirect back if cart is empty to prevent empty orders
if (empty($cart)) {
    redirect('cart.php');
}

if (is_post()) {
    // This is where you would eventually save to 'orders' and 'order_items' tables
    // For now, we will just clear the cart after "Confirm Order"
    set_cart(); 
    temp('Order placed successfully!');
    redirect('../index.php');
}

$_title = 'Checkout Order';
include '../_head.php';
?>

<main>
    <div class="solid-container">
        <h2>Checkout Order</h2>

        <table class="table solid-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="center">Quantity</th>
                    <th class="right">Price (RM)</th>
                    <th class="right">Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_payable = 0;
                
                // Prepare query to get variant details and the parent product name
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
                <tr style="font-weight: bold; background: #f0f0f0;">
                    <td colspan="3" class="right">Total Amount:</td>
                    <td class="right">RM <?= number_format($total_payable, 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
            <a href="cart.php" class="btn-cancel" style="text-decoration: none;">Continue Shopping</a>
            
            <form method="post">
                <button type="submit" class="btn-confirm" style="padding: 10px 40px; background-color: #333; color: white; border: none; cursor: pointer;">
                    CONFIRM ORDER
                </button>
            </form>
        </div>
    </div>
</main>

<?php
include '../_foot.php';
?>