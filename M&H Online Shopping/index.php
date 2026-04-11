<?php
include '_base.php';

// ----------------------------------------------------------------------------

$stm = $_db->query("SELECT * FROM user");
$user = $stm->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'User List';
include '_head.php';
?>

<table class="table">
    <tr>
        <th>User ID</th>
        <th>User Name</th>
        <th>Email</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($user as $u): ?>
            <tr>
                <td><?= encode($u->user_id) ?></td>
                <td><?= encode($u->user_name) ?></td>
                <td><?= encode($u->email) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
include '_foot.php';