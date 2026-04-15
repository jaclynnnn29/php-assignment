<?php
include '../_base.php';

auth('Member', 'Admin');

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
    <p>
        <button data-post="reset.php" data-confirm>Reset</button>
    </p>

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
                <span style="color: <?= $o->status == 'Paid' ? 'green' : 'red' ?>; font-weight: bold;">
                    <?= $o->status ?>
                </span>
            </td>
            <td><?= $o->shipment_status ?? 'Pending' ?></td>
            <td>
            <button onclick="location.href='detail.php?id=<?= $o->order_id ?>'">Detail</button>
            </td> 
        </tr>
        <?php endforeach ?>
    </table>
</main>

<?php include '../_foot.php'; ?>