<?php
include '../_base.php';

// --- Logic ---
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
$_title = 'Shopping Cart';
include '../_head.php';
?>

<main>
    <div class="solid-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Your Shopping Cart</h2>
            <form method="get" style="display: flex; gap: 5px;">
                <input type="text" name="search" value="<?= $search ?>" placeholder="Search items..." style="padding: 5px; width: 200px;">
                <button type="submit">Search</button>
                <?php if ($search): ?>
                    <a href="?" style="font-size: 0.8rem; align-self: center;">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <table class="table solid-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Type of Product</th>
                    <th>Size</th>
                    <th class="right">Price (RM)</th>
                    <th>Unit</th>
                    <th class="right">Subtotal (RM)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $displayed_count = 0;
                $displayed_total = 0;
                
                $stm = $_db->prepare('SELECT pv.*, p.product_name, p.photo FROM product_variants pv JOIN product p ON pv.product_id = p.product_id WHERE pv.variant_id = ?');
                $stm_variants = $_db->prepare('SELECT variant_id, size FROM product_variants WHERE product_id = ? ORDER BY size');
                $cart = get_cart();

                foreach ($cart as $id => $unit):
                    $stm->execute([$id]);
                    $p = $stm->fetch();
                    if (!$p) continue;

                    if ($search && stripos($p->product_name, $search) === false) continue;

                    $stm_variants->execute([$p->product_id]);
                    $variant_options = $stm_variants->fetchAll();

                    $subtotal = $p->price * $unit;
                    $displayed_count += $unit;
                    $displayed_total += $subtotal;
                ?>
                    <tr>
                        <td><img src="/images/<?= $p->photo ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"></td>
                        <td><?= $p->product_name ?></td>
                        <td>
                            <form method="post">
                                <?= html_hidden('id', $id) ?>
                                <?= html_hidden('unit', $unit) ?>
                                <?= html_select('variant_id', array_column($variant_options, 'size', 'variant_id'), $id) ?>
                            </form>
                        </td>
                        <td class="right"><?= number_format($p->price, 2) ?></td>
                        <td>
                            <form method="post">
                                <?= html_hidden('id', $id) ?>
                                <?= html_hidden('variant_id', $id) ?>
                                <?= html_select('unit', $_units, $unit) ?>
                            </form>
                        </td>
                        <td class="right"><?= number_format($subtotal, 2) ?></td>
                        <td>
                            <button data-post="?id=<?= $id ?>&unit=0" data-confirm="Remove?" class="link-delete" style="background:none; border:none; cursor:pointer;">
                                <i class="fa fa-trash"></i> Remove
                            </button>
                        </td>
                    </tr>
                <?php endforeach ?>

                <?php if (empty($cart)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #666;">
                            Your cart is currently empty. <a href="../product/list.php">Go shopping!</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="background: #f9f9f9; font-weight: bold;">
                    <td colspan="4" style="text-align: right;">Total Units:</td>
                    <td><?= $displayed_count ?></td>
                    <td class="right">RM <?= number_format($displayed_total, 2) ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top: 20px; text-align: right;">
            <?php if ($cart): ?>
                <button data-post="?btn=clear" style="background: #eee; color: #333;">Clear All Items</button>
                
                <?php if ($_user?->role == 'Member'): ?>
                    <button data-get="checkout.php" class="btn-add" style="margin-left: 10px;">Proceed to Checkout</button>
                <?php else: ?>
                    <span style="margin-left: 10px; color: #666;">
                        Please <a href="/login.php">login</a> to checkout.
                    </span>
                <?php endif ?>
            <?php endif ?>
        </div>
    </div>
</main>

<script>
    $('select').on('change', e => e.target.form.submit());
</script>

<?php 
?>
</body>
</html>
