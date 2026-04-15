<?php
include '../_base.php';
auth('Member');

// --- Logic ---
if (is_post()) {
    $btn = req('btn');
    $id = req('id');
    $unit = req('unit'); // <--- ADD THIS LINE: It captures the quantity from the form
    $new_variant_id = req('variant_id'); // <--- ADD THIS LINE: Capture the new variant if size changed

    // 1. Handle Clear All
    if ($btn == 'clear') {
        set_cart(); // Clear the session array
        temp('info', 'Cart cleared.');
        redirect('cart.php');
    }

    // 2. Handle Remove Single Item
    if ($btn == 'delete' || $unit === '0') {
        update_cart($id, 0); // Set quantity to 0 to remove it
        temp('info', 'Item removed.');
        redirect('cart.php');
    }
    

    // 3. Handle Variant Swapping or Quantity Updates
    $target_id = $new_variant_id ?: $id;

    if ($target_id != $id) {
        // Switching to a different size/variant
        $cart = get_cart();
        unset($cart[$id]); // Remove the old size

        // Ensure we have a valid quantity before adding
        if ($unit >= 1 && $unit <= 10) {
            $cart[$target_id] = ($cart[$target_id] ?? 0) + $unit;
            ksort($cart);
        }
        set_cart($cart);
    } else {
        // Standard quantity update for the same item
        update_cart($id, $unit);
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
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search items..." style="padding: 5px; width: 200px;">
                <button type="submit">Search</button>
                <?php if ($search): ?>
                    <a href="?" style="font-size: 0.8rem; align-self: center; margin-left: 5px;">Clear Search</a>
                <?php endif; ?>
            </form>
        </div>

        <table class="table solid-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Type of Product</th>
                    <th>Size</th>
                    <th class="right">Price per Unit (RM)</th>
                    <th>Unit</th>
                    <th class="right">SubTotal (RM)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $displayed_count = 0;
                $displayed_total = 0;
                
                // Query to get product details via variant
                $stm = $_db->prepare('
                    SELECT pv.*, p.product_name, p.photo 
                    FROM product_variants pv 
                    JOIN product p ON pv.product_id = p.product_id 
                    WHERE pv.variant_id = ?
                ');
                
                // Query to get all available sizes for the size-dropdown
                $stm_variants = $_db->prepare('SELECT variant_id, size FROM product_variants WHERE product_id = ? ORDER BY size');
                
                $cart = get_cart();

                foreach ($cart as $id => $unit):
                    $stm->execute([$id]);
                    $p = $stm->fetch();
                    
                    if (!$p) continue;

                    // Filter display if searching
                    if ($search && stripos($p->product_name, $search) === false) continue;

                    $stm_variants->execute([$p->product_id]);
                    $variant_options = $stm_variants->fetchAll();

                    $subtotal = $p->price * $unit;
                    $displayed_count += $unit;
                    $displayed_total += $subtotal;
                ?>
                    <tr>
                        <td><img src="/images/<?= $p->photo ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"></td>
                        <td><?= htmlspecialchars($p->product_name) ?></td>
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
                            <button data-post="?id=<?= $id ?>&unit=0&btn=delete" 
                                data-confirm="Remove this item?" 
                                class="link-delete" 
                                style="background:none; border:none; cursor:pointer; color: #d9534f;">
                                🗑️ Remove
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
                    <td class="right">Total Amount: RM <?= number_format($displayed_total, 2) ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top: 20px; text-align: right; display: flex; justify-content: flex-end; gap: 10px;">
    <?php if ($cart): ?>
        <button class="btn-clear" 
                data-post="?btn=clear" 
                data-confirm="Clear all items in your cart?"
                style="
                    background-color: #e74c3c; 
                    color: white; 
                    border: none; 
                    padding: 8px 16px; 
                    border-radius: 4px; 
                    cursor: pointer;
                    font-weight: bold;
                    font-family: inherit;
                ">
            Clear all items
        </button>
        
        <a href="checkout.php" style="
            text-decoration: none; 
            background-color: #2b91af; 
            color: white; 
            padding: 8px 16px; 
            border-radius: 4px; 
            display: inline-block;
            font-weight: bold;
            font-family: inherit;
            font-size: 13.33px;
            transition: opacity 0.2s;
        " onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
            Check Out
        </a>
    <?php endif; ?>
</div>
    </div>
</main>

<script>
    // Automatically submit the form when a dropdown (size or quantity) changes
    $('select').on('change', e => e.target.form.submit());
</script>


<?php include '../_foot.php'; ?>