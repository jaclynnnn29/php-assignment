<?php
require '../_base.php';
auth('Admin'); // Restrict access to Admins only

// Order Status Update (Additional)
if (is_post()) {
    $order_id = post('order_id');
    $status = post('status');

    // Update the plural `orders` table
    $stm = $_db->prepare("UPDATE `orders` SET status = ? WHERE order_id = ?");
    $stm->execute([$status, $order_id]);

    temp('info', "Order ORD" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . " updated to $status"); 
    redirect(); 
}

//Order Listing (Additional)
// Select from the correct table and use correct date column
$orders = $_db->query("SELECT * FROM `orders` ORDER BY order_date DESC")->fetchAll();

$_title = 'Manage Orders';
include '../_head.php';
?>

<main>
    <div class="solid-container">
        <h1 style="margin-bottom: 20px;">Order Management</h1>

        <?php if ($msg = temp('info')): ?>
            <p style="color: green; font-weight: bold;"><?= $msg ?></p>
        <?php endif; ?>

        <table class="table solid-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <form method="post">
                        <td><strong>ORD<?= str_pad($o->order_id, 5, '0', STR_PAD_LEFT) ?></strong></td>
                        <td><?= $o->order_date ?></td>
                        <td>RM <?= number_format($o->total_price, 2) ?></td>
                        <td>
                            <?php
                            $arr = [
                                'Pending'    => 'Pending',
                                'Processing' => 'Processing',
                                'Shipped'    => 'Shipped',
                                'Delivered'  => 'Delivered',
                                'Cancelled'  => 'Cancelled'
                            ];
                            // Now $o->status exists because we are using the correct table
                            html_select('status', $arr, $o->status); 
                            ?>
                            <input type="hidden" name="order_id" value="<?= $o->order_id ?>">
                        </td>
                        <td><button type="submit" class="btn-update">Update Status</button></td>
                    </form>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">No orders found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <p style="margin-top: 15px; color: #666;"><?= count($orders) ?> order(s) found.</p>
    </div>
</main>

<?php include '../_foot.php'; ?>