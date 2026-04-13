<?php
include '../_base.php'; 
auth();// Allow both roles to view order details

// 1. Logic & Data Fetching
$id = req('id');

// Fetch the items for this specific order
$stm = $_db->prepare('
    SELECT * FROM item AS i
    JOIN product AS p ON i.variant_id = p.product_id
    WHERE i.order_id = ?
');
$stm->execute([$id]);
$items = $stm->fetchAll();

// If order has no items, it might be an invalid ID
if (!$items) {
    temp('error', 'Order not found or empty.');
    redirect('history.php');
}

// 2. Page Setup & Header
$_title = "Order Detail | ORD" . str_pad($id, 3, '0', STR_PAD_LEFT);
include '../_head.php'; 
?>

<main>
    <h1>Order Details (ID: ORD<?= str_pad($id, 3, '0', STR_PAD_LEFT) ?>)</h1>

    <table class="table">
        <tr>
            <th>Photo</th>
            <th>Product Name</th>
            <th>Price (RM)</th>
            <th>Quantity</th>
            <th>Subtotal (RM)</th>
        </tr>
        <?php foreach ($items as $i): ?>
        <tr>
            <td><img src="/images/<?= $i->photo ?>" style="width: 50px;"></td>
            <td><?= $i->product_name ?></td>
            <td><?= number_format($i->price, 2) ?></td>
            <td><?= $i->unit ?></td>
            <td><?= number_format($i->price * $i->unit, 2) ?></td>
        </tr>
        <?php endforeach ?>
    </table>

    <p>
        <a href="history.php" class="button">Back to History</a>
    </p>
</main>

<?php
include '../_foot.php';
?>