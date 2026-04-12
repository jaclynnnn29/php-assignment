// Order Status Update (Admin)  Additional function 
<?php
require '../_base.php';
auth('Admin'); // Restrict access to Admins only

// Handle the status update POST request
if (is_post()) {
    $order_id = post('order_id');
    $status = post('status');

    $stm = $_db->prepare("UPDATE `order` SET status = ? WHERE order_id = ?");
    $stm->execute([$status, $order_id]);

    temp('info', "Order $order_id updated to $status"); // Set success message
    redirect(); // Refresh the page
}

// Fetch all orders to display
$orders = $_db->query("SELECT * FROM `order` ORDER BY datetime DESC")->fetchAll();

$_title = 'Manage Orders';
include '../_head.php';
?>

<table class="table">
    <tr>
        <th>Order ID</th>
        <th>Date</th>
        <th>Total</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php foreach ($orders as $o): ?>
    <tr>
        <form method="post">
            <td><?= $o->order_id ?></td>
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
                // Use the html_select helper from your _base.php
                html_select('status', $arr, $o->status, null); 
                ?>
                <input type="hidden" name="order_id" value="<?= $o->order_id ?>">
            </td>
            <td><button type="submit">Update</button></td>
        </form>
    </tr>
    <?php endforeach; ?>
</table>

<?php include '../_foot.php'; ?>