<?php
include '../_base.php';

auth('Member', 'Admin');

if (is_post()) {
    $cancel_id = post('cancel_id');
    if ($cancel_id) {
        // Update status to 'Cancelled' only if it's currently 'Paid' 
        // and belongs to the logged-in user for security
        $stm = $_db->prepare('
            UPDATE `orders` 
            SET status = "Cancelled" 
            WHERE order_id = ? AND user_id = ? AND status = ？
        ');
        $stm->execute([$cancel_id, $_user->user_id]);
        
        temp('info', 'Order has been successfully cancelled.');
        redirect('history.php');
    }
}

// (2) Fetch orders using the correct table and column names
$stm = $_db->prepare('
    SELECT * FROM `orders` 
    WHERE user_id = ? 
    ORDER BY order_date DESC
');
$stm->execute([$_user->user_id]);
$arr = $stm->fetchAll();

$_title = 'Order | History';
include '../_head.php';
?>

<main>

    <p><?= count($arr) ?> record(s)</p>

    <table class="table">
        <tr>
            <th>Id</th>
            <th>Date</th>
            <th>Total (RM)</th>
            <th>Payment Status</th>
            <th>Shipment Status</th>
            <th>Action</th> 
        </tr>

        <?php foreach ($arr as $o): ?>

        <tr>
            <td>ORD<?= str_pad($o->order_id, 5, '0', STR_PAD_LEFT) ?></td>
            <td><?= $o->order_date ?></td>
            <td class="right">RM <?= number_format($o->total_price, 2) ?></td>
            <td>
                <span class="<?= $o->status == 'Paid' ? 'status-paid' : 'status-unpaid' ?>">
                    <?= $o->status ?>
                </span>
            </td>
            <td><span class="status-pending"><?= $o->shipment_status ?? 'Pending' ?></span></td>
            <td>
                <button class="btn-detail" onclick="location.href='detail.php?id=<?= $o->order_id ?>'">Detail</button>
                
                <?php if ($o->status == 'Paid' || $o->status == 'Pending'): ?>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Cancel this order?')">
                        <input type="hidden" name="cancel_id" value="<?= $o->order_id ?>">
                        <button class="btn-cancel9">Cancel</button>
                    </form>
                <?php endif; ?>

            </td> 
        </tr>
        <?php endforeach ?>
    </table>
</main>


