<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    // TODO
    $id = req('id');
    $unit = req('unit');
    update_cart($id, $unit);
    redirect();
}
$_db->query("USE shopping_cart");
$arr = $_db->query('SELECT * FROM product');

// ----------------------------------------------------------------------------

$_title = 'Product | List';
include '../_head.php';
?>

<style>
    #product {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .product {
        border: 1px solid #333;
        width: 200px;
        height: 200px;
        position: relative;
    }

    .product img {
        display: block;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .product form,
    .product div {
        position: absolute;
        background: #0009;
        color: #fff;
        padding: 5px;
        text-align: center;
    }

    .product form {
        inset: 0 0 auto auto;
    }

    .product div {
        inset: auto 0 0 0;
    }
</style>

<div id="product">
    <?php foreach ($arr as $p): ?>
        <!-- TODO -->
        <?php
         $cart = get_cart();
         $id = $p->product_id;
         $unit = $cart[$p->product_id] ?? 0;
         ?>

        <div class="product">
            <form method="post">
                <!-- TODO ✅ -->
                 <?=  $unit ? '✅' : '' ?>
                 <?= html_hidden('id') ?>
                 <?= html_select('unit', $_units, '') ?>
            </form>
                
            <img src="/images/<?= $p->photo ?>" 
            data-get="/product/detail.php?id=<?= $p->product_id ?>" 
            alt="Product Image" 
            style="width:200px; height:200px; object-fit:cover;">

            <div><?= $p->product_id ?> | <?= $p->size ?> | RM <?= $p->price ?></div>
        </div>
    <?php endforeach ?>
</div>

<script>
    // TODO
    $('select').on('change', e => e.target.form.submit());
</script>

<?php
include '../_foot.php';