<?php
require '../_base.php';
auth('Admin');

// 1. Basic Searching
$name = req('name');
$stm = $_db->prepare("SELECT * FROM product WHERE name LIKE ?");
$stm->execute(["%$name%"]);
$products = $stm->fetchAll();

$_title = 'Admin | Product List';
include '../_head.php';
?>

<form>
    <input type="search" name="name" value="<?= $name ?>" placeholder="Search product...">
    <button>Search</button>
</form>

<table class="table">
    <tr>
        <th>Photo</th> 
        <th>Product Name</th>
        <th>Price</th>
        <th>Colour</th>
        <th>Size</th>
    </tr>
    <?php foreach ($products as $p): ?>
    <tr>
        <td><img src="../uploads/<?= $p->photo ?>" width="50"></td>
        <td><?= $p->product_name ?></td>
        <td><?= number_format($p->price, 2) ?></td>
        <td><?= $p->colour ?></td>
        <td><?= $p->size ?></td>
        <td>
            <a href="product_edit.php?id=<?= $p->id ?>">Edit</a> |
            <button data-post="product_delete.php?id=<?= $p->id ?>">Delete</button>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include '../_foot.php'; ?>