<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// (1) Authorization (member)
// TODO
auth('Member', 'Admin');

// (2) Return orders belong to the user (descending)
$stm = $_db->prepare('
    SELECT * FROM `order` 
    WHERE user_id = ?
    ORDER BY datetime DESC
');
$stm->execute([$_user->user_id]);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'Order | History';
include '../_head.php';
?>

<style>
    tr:hover .popup {
        display: grid !important;
        grid:auto/repeat(5,auto);
        gap:1px;
        border: none;
    }

    .pop img{
        width:50px;
        height:50px;
        outline:1px solid #333;
    }
</style>

<main>
<p>
    <button data-post="reset.php" data-confirm>Reset</button>
</p>

<p><?= count($arr) ?> record(s)</p>

<table class="table">
    <tr>
        <th>Id</th>
        <th>Datetime</th>
        <th>Quantity</th>
        <th>Total (RM)</th>
        <th>User Id</th>
        <th>User email</th>
        <th>Action</th> </tr>

    <?php foreach ($arr as $o): ?>
    <tr>
        <td>ORD<?= str_pad($o->order_id, 3, '0', STR_PAD_LEFT) ?></td>
        <td><?= $o->datetime ?></td>
        <td class="right"><?= $o->quantity ?></td>
        <td class="right">RM <?= number_format($o->total, 2) ?></td>
        <td><?= $o->user_id ?></td>
        <td><?= $_user->email ?></td>
        <td>
            <button type="button" onclick="location.href='detail.php?id=<?= $o->order_id ?>'">Detail</button>
        </td>
    </tr>
    <?php endforeach ?>
</table>
 </main>

<?php
include '../_foot.php';