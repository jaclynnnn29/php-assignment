<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    // TODO
    $btn = req('btn');
    if ($btn == 'clear'){
        set_cart();
        redirect('?');
    }
    $id = req('id');
    $unit = req('unit');
    update_cart($id, $unit);
    redirect();
}

// ----------------------------------------------------------------------------

$_title = 'Order | Shopping Cart';
include '../_head.php';
?>

<style>
    .popup {
        width: 100px;
        height: 100px;
    }
</style>

<table class="table">
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Price (RM)</th>
        <th>Unit</th>
        <th>Subtotal (RM)</th>
    </tr>

    <?php
        // TODO
        $count = 0;
        $total = 0;
        
        $stm = $_db->prepare('SELECT * FROM product WHERE product_id =?');
        $cart = get_cart();


        foreach ($cart as $id => $unit):
            // TODO
            $stm->execute([$id]);
            $p = $stm->fetch();

            $subtotal = $p->price * $unit;
            $count += $unit;
            $total += $subtotal;
            
    ?>
        <tr>
            <td><?= $p->product_id ?></td>
            <td><?= $p->product_name ?></td>
            <td class="right"><?= $p->price ?></td>
            <td>
                <form method="post">
                    <?= html_hidden('id') ?>
                    <?= html_select('unit', $_units, '') ?>
                    <!-- TODO -->
                </form>            
            </td>
            <td class="right">
                <?= sprintf('%.2f', $subtotal) ?>
                <img src="/products/<?= $p->photo ?>" class="popup">
            </td>
        </tr>
    <?php endforeach ?>

    <tr>
        <th colspan="3"></th>
        <th class="right"><?= $count ?></th>
        <th class="right"><?= sprintf('%.2f', $total) ?></th>
    </tr>
</table>

<p>
    <!-- TODO -->
    <?php if ($cart): ?>
        <button data-post="?btn=clear">Clear</button>

        <?php if ($_user?->role == 'Member'): ?>
            <button data-post="checkout.php">Checkout</button>
        <?php else: ?>
            Please <a href="/login.php">login</a> as member to checkout
        <?php endif ?>
    <?php endif ?>
</p>

<script>
    // TODO
    $('select'.on('change', e=> e.target.form.submit()));
</script>

<?php
include '../_foot.php';