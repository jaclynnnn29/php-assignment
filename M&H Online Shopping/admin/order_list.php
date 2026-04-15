<?php
require '../_base.php';
auth('Admin');

// Fetch orders joined with user names for clarity

$stm = $_db->query("
    SELECT o.*, u.user_name 
    FROM orders o
    JOIN user u ON o.user_id = u.user_id
    ORDER BY o.order_date DESC
");
$orders = $stm->fetchAll();

$_title = 'Order List';
include '../_head.php';
?>

<main>
    <h1>Order List</h1>

    <table class="table solid-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total (RM)</th>
                <th>Payment Status</th>
                <th>Shipment Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
            <tr>
                <td><?= $o->order_id ?></td>
                <td><?= htmlspecialchars($o->user_name) ?></td>
                <td><?= date('d-m-Y H:i', strtotime($o->order_date)) ?></td>
                <td><?= number_format($o->total_price, 2) ?></td>

                <td>
                    <span class="status-pill <?= strtolower($o->status) ?>" 
                            style="color: <?= $o->status == 'Paid' ? 'green' : 'red' ?>; font-weight: bold;">
                        <?= $o->status ?>
                    </span>
                </td>

                <td>
                    <span style="color: #0000ff; font-weight: bold;">
                        <?= $o->shipment_status ?? 'Processing' ?>
                    </span>
                </td>

                <td>
                    
                    <a style="color: #ff0000; font-weight: bold;" href="order_detail.php?id=<?= $o->order_id ?>" class="btn-update">View Details</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include '../_foot.php'; ?>