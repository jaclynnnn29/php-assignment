<?php
include '../_base.php';

// 1. Authorization: Allow Members to see their own and Admins to see all
auth('Member', 'Admin');

// 2. Get Order ID from URL
$id = req('id');

// 3. Fetch order items + product details
// Must JOIN item -> product_variants -> product to get the name and photo
$stm = $_db->prepare('
    SELECT i.*, p.product_name, p.photo, pv.size 
    FROM order_items i 
    JOIN product_variants pv ON i.variant_id = pv.variant_id 
    JOIN product p ON pv.product_id = p.product_id 
    WHERE i.order_id = ?
');
$stm->execute([$id]);
$items = $stm->fetchAll();

// 4. Fetch the main order info
// Table is `orders` (plural) and we use backticks for safety
$stm = $_db->prepare('SELECT * FROM `orders` WHERE order_id = ?');
$stm->execute([$id]);
$order = $stm->fetch();

// Redirect if order doesn't exist
if (!$order) {
    temp('info', 'Order record not found.');
    redirect('history.php');
}

// Security: If not Admin, ensure the user can only see their own order
if ($_user->role == 'Member' && $order->user_id != $_user->user_id) {
    redirect('history.php');
}

$_title = 'Order | Detail';
include '../_head.php';
?>

<main>
    <div class="solid-container">
        <div class="order-header-flex">
            <div>
                <h1>Order #<?= str_pad($order->order_id, 5, '0', STR_PAD_LEFT) ?></h1>
                <p><strong>Payment Status:</strong> 
                <span class="status-badge <?= $order->status == 'Paid' ? 'status-paid' : 'status-unpaid' ?>">
                    <?= $order->status ?>
                </span></p>
            </div>
            <div class="order-header-right">
                <p><strong>Date:</strong> <?= date('d-M-Y H:i', strtotime($order->order_date)) ?></p>
                <p ><strong>Shipment Status:</strong> <span class="status-shipment"><?= $order->shipment_status ?? 'Pending' ?></span></p>
            </div>
        </div>

        <table class="table solid-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th class="right">Price (RM)</th>
                    <th class="center">Unit</th>
                    <th class="right">Subtotal (RM)</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($items as $i): ?>
        <tr>
            <td><img src="/images/<?= $i->photo ?>" class="order-item-img"></td>
            <td><?= htmlspecialchars($i->product_name) ?></td>
            <td><?= $i->size ?></td>
            <td class="right">RM <?= number_format($i->unit_price, 2) ?></td>
            <td class="center"><?= $i->unit ?></td>
            <td class="right">RM <?= number_format($i->unit_price * $i->unit, 2) ?></td>
        </tr>
        <?php endforeach ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" class="right">Grand Total:</td>
                    <td class="right total-price-highlight">RM <?= number_format($order->total_price, 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="order-footer-actions">
            <a href="history.php" class="btn-clear1 no-underline">← Back to Order History</a>
            <button onclick="window.print()" class="btn-clear" class="btn-clear btn-print">
                🖨️ Print Receipt
            </button>
        </div>
    </div>
</main>

<?php include '../_foot.php'; ?>