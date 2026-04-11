<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// (1) Authorization (member)
// TODO
auth('Member');

// (2) Return orders belong to the user (descending)
// TODO
$stm = $_db ->prepare('
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
        <th></th>
    </tr>

    <?php foreach ($arr as $o): ?>
    <tr>
        <td><?= $o->order_id ?></td>
        <td><?= $o->datetime ?></td>
        <td class="right"><?= $o->quantity ?></td>
        <td class="right"><?= $o->total ?></td>
        <td>
            <button data-get="detail.php?id=<?= $o->order_id ?>">Detail</button>
            <!-- (A) EXTRA: Product photos -->
            <!-- TODO -->
             <div class="popup">
                <?php
                $stm = $_db -> prepare('
                    SELECT p.photo 
                    FROM item AS i
                    JOIN product AS p ON i.product_id = p.product_id
                    WHERE i.order_id = ?
                ');
                
                $stm->execute([$o->order_id]);
                $photos = $stm->fetchAll(PDO::FETCH_COLUMN);

                foreach ($photos as $photo) {
                    echo "<img src='../$uploads/$photo' class='pop'>";
                }
                ?>
             </div>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<?php
include '../_foot.php';