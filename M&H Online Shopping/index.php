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
        <th>Email</th>
        <th>Password</th>
        <th>Role</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($user as $u): ?>
            <tr>
                <td><?= encode($u->email) ?></td>
                <td><?= encode($u->password) ?></td>
                <td><?= encode($u->role) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
include '_foot.php';