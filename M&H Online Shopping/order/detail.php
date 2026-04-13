<?php
include '../_base.php';

// (1) Authorization: Only Members and Admins can view order details
auth('Member', 'Admin');

// (2) Get Order ID from URL
$id = req('id');

// (3) Fetch order items + product details
// We JOIN on variant_id to avoid the "product_id not found" error
$stm = $_db->prepare('
    SELECT i.*, p.product_name, p.photo 
    FROM item i 
    JOIN product p ON i.variant_id = p.product_id 
    WHERE i.order_id = ?
');
$stm->execute([$id]);
$items = $stm->fetchAll();

// (4) Fetch the main order info (to show the Date/Total at the top)
$stm = $_db->prepare('SELECT * FROM `order` WHERE order_id = ?');
$stm->execute([$id]);
$order = $stm->fetch();

// If order doesn't exist, go back to history
if (!$order) redirect('history.php');

// ----------------------------------------------------------------------------

$_title = 'Order | Detail';
include '../_head.php';
?>

<main>
    <h1>Order Details (ID: ORD<?= str_pad($order->order_id, 3, '0', STR_PAD_LEFT) ?>)</h1>

    <p>
        <strong>Order Date:</strong> <?= $order->datetime ?><br>
        <strong>Status:</strong> <?= $order->status ?? 'Completed' ?>
    </p>

    <table class="table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Product Name</th>
                <th>Price (RM)</th>
                <th>Quantity</th>
                <th>Subtotal (RM)</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $i): ?>
    <tr>
    <td><img src="/images/<?= $i->photo ?>" style="width: 50px;"></td>
    <td><?= $i->product_name ?></td>
    <td>RM <?= number_format($i->price, 2) ?></td>
    <td><?= $i->unit ?></td> <td>RM <?= number_format($i->price * $i->unit, 2) ?></td>
</tr>
<?php endforeach ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="right">Grand Total:</th>
                <th>RM <?= number_format($order->total, 2) ?></th>
            </tr>
        </tfoot>
    </table>

    <p style="margin-top: 20px;">
        <a href="history.php" class="button">Back to History</a>
    </p>
</main>

<?php
include '../_foot.php';
?>