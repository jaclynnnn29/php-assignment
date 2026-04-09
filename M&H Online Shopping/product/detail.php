<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    // TODO
    $id = req('id');
    $unit = req('quantity');
    update_cart($id, $unit);
    redirect();
}

$id  = req('id');
$stm = $_db->prepare('SELECT * FROM product WHERE product_id = ?');
$stm->execute([$id]);
$p = $stm->fetch();
if (!$p) redirect('list.php');

// ----------------------------------------------------------------------------

$_title = 'Product | Detail';
include '../_head.php';
?>

<style>
    #photo {
        display: block;
        border: 1px solid #333;
        width: 200px;
        height: 200px;
    }
</style>

<p>
    <img src="/images/<?= $p->photo ?>" id="photo">
</p>

<table class="table detail">
    <tr>
        <th>ID</th>
        <td><?= $p->product_id ?></td>
    </tr>
    <tr>
        <th>Type of Product</th>
        <td><?= $p->product_name ?></td>
    </tr>
    <tr>
        <th>Price</th>
        <td>RM <?= $p->price ?></td>
    </tr>
    <tr>
        <th>Unit</th>
        <td>
            <!-- TODO -->
             <?php
             $cart = get_cart();
             $id = $p->product_id;
             $unit = $cart[$p->product_id] ?? 0;
             ?>
            <form method="post">
                <!-- TODO ✅ -->
                 <?= html_hidden('id') ?>
                 <?= html_select('quantity', $_units, '') ?>
                 <?=  $unit ? '✅' : '' ?>
            </form>
        </td>
    </tr>
</table>

<p>
    <button data-get="list.php">List</button>
</p>

<script>
    // TODO
    $('select').on('change', e => e.target.form.submit());
</script>

<?php
include '../_foot.php';