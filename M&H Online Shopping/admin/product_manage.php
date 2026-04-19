<?php
require '../_base.php';
auth('Admin'); // Restrict access to Admins only


// --- START SEARCH LOGIC ---
$search = req('search'); // This gets the 'search' value from the URL

if ($search) {
    // This searches for the term in both order_id and shipment_status
    $stm = $_db->prepare("SELECT * FROM `orders` 
                          WHERE order_id LIKE ? OR shipment_status LIKE ? 
                          ORDER BY order_date DESC");
    $stm->execute(["%$search%", "%$search%"]);
    $orders = $stm->fetchAll();
} else {
    // Default: show everything if no search is performed
    $orders = $_db->query("SELECT * FROM `orders` ORDER BY order_date DESC")->fetchAll();
}
// --- END SEARCH LOGIC ---

// Order Status Update (Additional)
if (is_post()) {
    $order_id = post('order_id');
    $status = post('status');

    // Update the plural `orders` table
    $stm = $_db->prepare("UPDATE `orders` SET shipment_status= ? WHERE order_id = ?");
    $stm->execute([$status, $order_id]);

    temp('info', "Order ORD" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . " updated to $status"); 
    redirect(); 
}


// Select from the correct table and use correct date column
$orders = $_db->query("SELECT * FROM `orders` ORDER BY order_date DESC")->fetchAll();

$_title = 'Manage Orders';
include '../_head.php';
?>

<main>
    <div class="solid-container">
        <h1 class="mb-20">Order List</h1>

        <div class="search-container">
            <form action="" method="get" class="search-form">
                <input type="text" name="search" class="search-input" 
                    placeholder="Search Order ID or Status..." 
                    value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn-search">Search</button>
                <?php if ($search): ?>
                    <a href="product_manage.php" class="btn-clear">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <?php if ($msg = temp('info')): ?>
            <p class="status-info-msg"><?= $msg ?></p>
        <?php endif; ?>

        <table class="table solid-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Shipment Status</th>
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
                            html_select('status', $arr, $o->shipment_status ?? 'Pending'); 
                            ?>
                            <input type="hidden" name="order_id" value="<?= $o->order_id ?>">
                        </td>
                        <td><button type="submit" class="btn-update">Update Status</button></td>
                    </form>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="5" class="no-data-cell">No orders found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <p class="order-table-footer"><?= count($orders) ?> order(s) found.</p>
    </div>
</main>

<?php include '../_foot.php'; ?>