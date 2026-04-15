<?php
require '../_base.php';
auth('Admin');

$order_id = req('id');

// Update order status logic
if (is_post()) {
    $new_status = post('status');
    $stm = $_db->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stm->execute([$new_status, $order_id]);
    temp('info', 'Order status updated successfully');
}

// Fetch order header
$stm = $_db->prepare("SELECT o.*, u.user_name FROM orders o JOIN user u ON o.user_id = u.user_id WHERE o.order_id = ?");
$stm->execute([$order_id]);
$order = $stm->fetch();

// Fetch order items joined with product names
$stm = $_db->prepare("
    SELECT oi.*, p.product_name 
    FROM order_items oi
    JOIN product p ON oi.variant_id = p.product_id
    WHERE oi.order_id = ?
");
$stm->execute([$id]); // Use the $id from your URL req('id')
$items = $stm->fetchAll();

$_title = "Order #$order_id";
include '../_head.php';
?>

<main>
    <div style="display:flex; justify-content: space-between;">
        <h2>Order Details: #<?= $order->order_id ?></h2>
        <a href="order_list.php" class="btn-clear">Back to List</a>
    </div>

    <section class="order-summary">
        <p><strong>Customer:</strong> <?= htmlspecialchars($order->user_name) ?></p>
        <p><strong>Date:</strong> <?= $order->order_date ?></p>
        <form method="post" style="margin-top: 10px;">
            <label><strong>Current Status:</strong></label>
            <select name="status">
                <?php html_options(['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'], $order->status); ?>
            </select>
            <button type="submit" class="btn-update" style="padding: 5px 10px;">Update Status</button>
        </form>
    </section>

    <table class="table" style="margin-top: 20px;">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $i): ?>
<tr>
    <td><?= encode($i->product_name) ?></td>
    <td><?= $i->unit ?></td> <td>RM <?= number_format($i->unit_price, 2) ?></td>
    <td>RM <?= number_format($i->unit * $i->unit_price, 2) ?></td>
</tr>
<?php endforeach; ?>
            <tr style="font-weight: bold; background: #f9f9f9;">
                <td colspan="3" style="text-align: right;">Total Amount:</td>
                <td>RM <?= number_format($order->total_price, 2) ?></td>
            </tr>
        </tbody>
    </table>
</main>
