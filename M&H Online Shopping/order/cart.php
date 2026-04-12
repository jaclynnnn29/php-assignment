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

$search = req('search');

// ----------------------------------------------------------------------------

$_title = 'Order | Shopping Cart';
include '../_head.php';
?>

<style>
    .popup {
        width: 100px;
        height: 100px;
    }

    .table select {
        text-align: right;
    }
</style>

<div class="login-container" style="max-width: 1000px;"> <h1>Shopping Cart</h1>

        <form method="get" style="margin-bottom: 20px; text-align: left;">
            <input type="text" name="search" value="<?= $search ?>" placeholder="Search items..." style="padding: 8px; width: 250px; border: 1px solid #ccc; border-radius: 4px;">
            <button type="submit" class="btn-login" style="width: auto; display: inline-block; padding: 8px 15px;">Search</button>
            <?php if ($search): ?>
                <a href="?" style="margin-left: 10px; color: #666;">Clear Search</a>
            <?php endif; ?>
        </form>

        <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th class="right">Price (RM)</th>
                <th>Unit</th>
                <th class="right">Subtotal (RM)</th>
            </tr>
        </thead>

    <?php
        // TODO
        $displayed_count = 0;
        $displayed_total = 0;
        
        $stm = $_db->prepare('SELECT * FROM product WHERE product_id =?');
        $cart = get_cart();


        foreach ($cart as $id => $unit):
            // TODO
            $stm->execute([$id]);
            $p = $stm->fetch();

            if ($search && stripos($p->product_name, $search) === false) continue;

            $subtotal = $p->price * $unit;
            $displayed_count += $unit;
            $displayed_total += $subtotal;
            
    ?>
        <tr>
            <td><?= $p->product_id ?></td>
            <td><?= $p->product_name ?></td>
            <td class="right"><?= $p->price ?></td>
            <td>
                <form method="post">
                    <?= html_hidden('id', $id) ?>
                    <?= html_select('unit', $_units, $unit) ?>
                    <!-- TODO -->
                </form>            
            </td>
            <td class="right">
                <?= sprintf('%.2f', $subtotal) ?>
                <img src="/images/<?= $p->photo ?>" class="popup">
            </td>
        </tr>
    <?php endforeach ?>

    <tr>
        <th colspan="3"></th>
        <th class="right"><?= $displayed_count ?></th>
        <th class="right"><?= sprintf('%.2f', $displayed_total) ?></th>
    </tr>
</table>

<p>
    <!-- TODO -->
    <?php if ($cart): ?>
        <button data-post="?btn=clear">Clear All</button>

        <?php if ($_user?->role == 'Member'): ?>
            <button data-post="checkout.php">Checkout</button>
        <?php else: ?>
            Please <a href="/login.php">login</a> as member to checkout
        <?php endif ?>
    <?php endif ?>
</p>

<script>
    // TODO
    $('select').on('change', e => e.target.form.submit());
</script>

<?php