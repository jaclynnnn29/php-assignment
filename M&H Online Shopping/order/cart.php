<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $btn = req('btn');
    if ($btn == 'clear'){
        set_cart();
        redirect('?');
    }

    $original_id = req('id');
    $new_variant_id = req('variant_id') ?: $original_id;
    $unit = req('unit');

    if ($new_variant_id != $original_id) {
        $cart = get_cart();
        unset($cart[$original_id]);

        if ($unit >= 1 && $unit <= 10 && is_exists($new_variant_id, 'product_variants', 'variant_id')) {
            $cart[$new_variant_id] = min(10, ($cart[$new_variant_id] ?? 0) + $unit);
            ksort($cart);
        }

        set_cart($cart);
    } else {
        update_cart($original_id, $unit);
    }

    redirect();
}

$search = req('search');

// ----------------------------------------------------------------------------

$_title = 'Order | Shopping Cart';
include '../_head.php';
?>

<style>
    .table {
        width: auto; /* Allows the table to shrink to the size of its content */
        min-width: 700px; /* Ensures it remains readable on larger screens */
        margin-left: 100px; /* Indents the table from the left edge to match search form */
    }

    .table th, .table td {
        padding: 8px 12px; /* Reduces the bulky 15px padding from app.css */
        font-size: 0.95rem; /* Makes the text slightly smaller and professional */
    }

    .popup {
        width: 60px; /* Shrinks the hover/preview image */
        height: 60px;
    }

    .table select {
        text-align: right;
        padding: 2px;
    }
</style>
<form method="get" style="margin-bottom: 20px; padding-left: 100px;">
    <input type="text" name="search" value="<?= $search ?>" placeholder="Search cart items..." style="padding: 5px; width: 200px;">
    <button type="submit">Search</button>
    <?php if ($search): ?>
        <a href="?">Clear Search</a>
    <?php endif; ?>
</form>
<table class="table">
    <tr>
        <th>ID</th>
        <th>Type of Product</th>
        <th>Size</th>
        <th>Price (RM)</th>
        <th>Unit</th>
        <th>Subtotal (RM)</th>
    </tr>

    <?php
        // TODO
        $displayed_count = 0;
        $displayed_total = 0;
        
        $stm = $_db->prepare('SELECT pv.*, p.product_name FROM product_variants pv JOIN product p ON pv.product_id = p.product_id WHERE pv.variant_id = ?');
        $stm_variants = $_db->prepare('SELECT variant_id, size FROM product_variants WHERE product_id = ? ORDER BY size');
        $cart = get_cart();


        foreach ($cart as $id => $unit):
            $stm->execute([$id]);
            $p = $stm->fetch();
            if (!$p) {
                $stm2 = $_db->prepare('SELECT pv.*, p.product_name FROM product_variants pv JOIN product p ON pv.product_id = p.product_id WHERE pv.product_id = ? ORDER BY pv.size LIMIT 1');
                $stm2->execute([$id]);
                $p = $stm2->fetch();
                if (!$p) continue;
            }

            $variant_options = [];
            if ($p->product_id) {
                $stm_variants->execute([$p->product_id]);
                $variant_options = $stm_variants->fetchAll();
            }

            if ($search && stripos($p->product_name, $search) === false) continue;

            $subtotal = $p->price * $unit;
            $displayed_count += $unit;
            $displayed_total += $subtotal;
            
    ?>
        <tr>
            <td><?= $p->variant_id ?></td>
            <td><?= $p->product_name ?></td>
            <td>
                <form method="post">
                    <?= html_hidden('id', $id) ?>
                    <?= html_hidden('unit', $unit) ?>
                    <?= html_select('variant_id', array_column($variant_options, 'size', 'variant_id'), $id) ?>
                </form>
            </td>
            <td class="right"><?= $p->price ?></td>
            <td>
                <form method="post">
                    <?= html_hidden('id', $id) ?>
                    <?= html_hidden('variant_id', $id) ?>
                    <?= html_select('unit', $_units, $unit) ?>
                </form>
            </td>
            <td class="right">
                <?= sprintf('%.2f', $subtotal) ?>
                <img src="/images/<?= $p->photo ?>" class="popup">
            </td>
        </tr>
    <?php endforeach ?>

    <tr>
        <th colspan="4"></th>
        <th class="right"><?= $displayed_count ?></th>
        <th class="right"><?= sprintf('%.2f', $displayed_total) ?></th>
    </tr>
</table>

<p style="padding-left: 100px;">
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