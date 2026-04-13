<?php
include '../_base.php';
auth('Member');

if (is_post()) {
    $cart = get_cart();
    if (!$cart) redirect('cart.php');

    // Fetch prices from product table
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
    } // <-- ADDED MISSING BRACE HERE

    try {
        $_db->beginTransaction();

        // 1. Insert into 'order' table
        $stm = $_db->prepare("INSERT INTO `order` (datetime, total, quantity, user_id, status) VALUES (NOW(), ?, ?, ?, 'Pending')");
        $stm->execute([$total_amt, $total_qty, $_user->user_id]);

        $order_id = $_db->lastInsertId();

        // 2. Insert items using variant_id
        $stm_item = $_db->prepare('INSERT INTO item (order_id, variant_id, unit, price) VALUES (?, ?, ?, ?)');
        foreach ($cart as $id => $unit) {
            $price = $prices[$id];
            $stm_item->execute([$order_id, $id, $unit, $price]);
        }

        $_db->commit();
        set_cart(); // Clear the cart

        temp('info', 'Order placed successfully!');
        redirect("history.php"); // Redirect to history to see the new order
        
    } catch (Exception $e) {
        $_db->rollBack();
        temp('error', 'Checkout Failed: ' . $e->getMessage());
        redirect('cart.php');
    } 
}

$_title = 'Checkout';
include '../_head.php';

$cart = get_cart();
$ids = array_keys($cart);
$items = [];
$subtotal = 0;

if ($ids) {
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    // Simplified join to match your item/product tables
    $stm = $_db->prepare("
        SELECT product_id as variant_id, product_name, price, photo 
        FROM product 
        WHERE product_id IN ($placeholders)");
    $stm->execute($ids);
    $items = $stm->fetchAll();
}
?>

<main>
    <div class="checkout-wrapper">
        <h1>Checkout Order</h1>
        
        <table class="table plain-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
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
                        <td>
                            <img src="/images/<?= $p->photo ?>" style="width: 40px; margin-right: 10px;">
                            <span><?= $p->product_name ?></span>
                        </td>
                        <td><?= $unit ?></td>
                        <td>RM <?= number_format($item_total, 2) ?></td>
                        <td>
                            <a href="cart.php?id=<?= $p->variant_id ?>&unit=0" style="color: red;">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals-section">
            <div class="grand-total">Total: RM <?= number_format($subtotal, 2) ?></div>
        </div>

        <form method="post">
            <button type="submit" class="btn-checkout small">Confirm Order</button>
            <a href="../product/list.php">Continue Shopping</a>
        </form>
    </div>
</main>

<?php include '../_foot.php'; ?>