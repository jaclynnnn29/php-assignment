<?php
require '../_base.php';
auth('Admin');

// 1. Fetch the order_id from the URL first
$order_id = req('id'); 

// 2. Handle status updates
if (is_post()) {
    $new_status = post('shipment_status'); 
    // Fix: Updating the correct column 'shipment_status' in 'orders' table
    $stm = $_db->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stm->execute([$new_status, $order_id]);
    temp('info', 'Order status updated successfully');
    redirect(); 
}

// 3. Fetch order header
$stm = $_db->prepare("SELECT o.*, u.user_name FROM orders o JOIN user u ON o.user_id = u.user_id WHERE o.order_id = ?");
$stm->execute([$order_id]);
$order = $stm->fetch();

// 4. If order doesn't exist, redirect
if (!$order) redirect('order_list.php');

// 5. Fetch order items - FIXED: Changed product_id to variant_id
// Fetch order items joined with product names
// 5. Fetch order items joined with product names through variants
$stm = $_db->prepare("
    SELECT oi.*, p.product_name, pv.size, pv.colour
    FROM order_items oi
    JOIN product_variants pv ON oi.variant_id = pv.variant_id
    JOIN product p ON pv.product_id = p.product_id
    WHERE oi.order_id = ?
");
$stm->execute([$order_id]);
$items = $stm->fetchAll();

$_title = "Order #$order_id";
include '../_head.php';
?>

<main>
    <div class="order-details-header">
        <h2>Order Details: #<?= $order->order_id ?></h2>
        <a href="order_list.php" class="btn-clear1">Back to List</a>
    </div>

    <section class="order-summary">
        <p><strong>Customer:</strong> <?= htmlspecialchars($order->user_name) ?></p>
        <p><strong>Date:</strong> <?= $order->order_date ?></p>
        
        <p><strong>Payment Status:</strong> 
            <span class="<?= $order->status == 'Paid' ? 'status-paid-text' : 'status-unpaid-text' ?>">
                <?= $order->status ?>
            </span>
        </p>
        
        <form method="post" class="status-form">
            <label><strong>Current Status:</strong></label>
            <select name="shipment_status">
                <?php html_options(['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'], $order->status); ?>
            </select>
            <button type="submit" class="btn-update1 btn-update btn-small">Update Status</button>
        </form>
    </section>

    <table class="table" class="table mt-20">
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
    <td>
        <?= encode($i->product_name) ?> 
        <small>(<?= encode($i->size) ?>, <?= encode($i->colour) ?>)</small>
    </td>
    <td><?= $i->unit ?></td> 
    <td>RM <?= number_format($i->unit_price, 2) ?></td>
    <td>RM <?= number_format($i->unit * $i->unit_price, 2) ?></td>
</tr>
<?php endforeach; ?>
            <tr class="table-summary-row">
                <td colspan="3" class="text-right">Total Amount:</td>
                <td>RM <?= number_format($order->total_price, 2) ?></td>
            </tr>
        </tbody>
    </table>
</main>
