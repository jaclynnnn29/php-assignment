<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// (1) Authorization (member)
// TODO
auth('Member');

// (2) Return order (based on id) belong to the user
// TODO
$id = req('id'); // Remove the codes
$stm = $_db -> prepare('
    SELECT * FROM `order` 
    WHERE order_id = ? AND user_id = ?
');
$stm->execute([$id, $_user -> user_id]);
$o = $stm->fetch();
if (!$o) redirect('history.php');

// (3) Return items (and products) belong to the order
// TODO
$stm = $_db -> prepare('
    SELECT i.*, p.product_name, p.photo 
    FROM item AS i
    JOIN product AS p ON i.product_id = p.product_id
    WHERE i.order_id = ?
');
$stm->execute([$id]);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'Order | Detail';
include '../_head.php';
?>

<style>
    .popup {
        width: 100px;
        height: 100px;
    }
</style>

<form class="form">
    <label>Order Id</label>
    <b>ORD<?= str_pad($o->order_id, 3, '0', STR_PAD_LEFT) ?></b>
    <br>

    <label>Datetime</label>
    <div><?= $o->datetime ?></div>
    <br>

    <label>Total</label>
    <div>RM <?= $o->total ?></div>
    <br>
</form>

<p><?= count($arr) ?> item(s)</p>

<table class="table">
    <tr>
        <th>Product Id</th>
        <th>Product Name</th>
        <th>Price (RM)</th>
        <th>Unit</th>
        <th>Subtotal (RM)</th>
    </tr>

    <?php foreach ($arr as $i): ?>
    <tr>
        <td><?= $i->product_id ?></td>
        <td><?= $i->product_name ?></td>
        <td class="right"><?= $i->price ?></td>
        <td class="right"><?= $i->unit ?></td>
        <td class="right">
            <?= $i->subtotal ?>
            <img src="/products/<?= $i->photo ?>" class="popup">
        </td>
    </tr>
    <?php endforeach ?>

    <tr>
        <th colspan="3"></th>
        <th class="right"><?= $o->quantity ?></th>
        <th class="right"><?= $o->total ?></th>
    </tr>
</table>

<p>
    <button data-get="history.php">History</button>
</p>

<?php
include '../_foot.php';