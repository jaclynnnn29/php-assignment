// Order Status Update (Admin)  Additional function 
<?php
require '../_base.php';
auth('Admin'); // Restrict access to Admins only
 

if (is_post()) {
    $order_id = post('order_id');
    $status = post('status');

    $stm = $_db->prepare("UPDATE `order` SET status = ? WHERE order_id = ?");
    $stm->execute([$status, $order_id]);

    temp('info', "Order $order_id updated to $status"); 
    redirect(); 
}

$orders = $_db->query("SELECT * FROM `order` ORDER BY datetime DESC")->fetchAll();

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
                <?php foreach ($order as $o): ?>
                <tr>
                    <form method="post">
                        <td><strong><?= $o->order_id ?></strong></td>
                        <td><?= $o->datetime ?></td>
                        <td>RM <?= number_format($o->total, 2) ?></td>
                        <td>
                            <?php
                            $arr = [
                                'Pending'    => 'Pending',
                                'Processing' => 'Processing',
                                'Shipped'    => 'Shipped',
                                'Delivered'  => 'Delivered',
                                'Cancelled'  => 'Cancelled'
                            ];
                            html_select('status', $arr, $o->status, null); 
                            ?>
                            <input type="hidden" name="order_id" value="<?= $o->order_id ?>">
                        </td>
                        <td><button type="submit" class="btn-update">Update</button></td>
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

</body>
</html>
