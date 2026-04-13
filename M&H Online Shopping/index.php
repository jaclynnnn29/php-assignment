<?php
include '_base.php';

// ----------------------------------------------------------------------------

$stm = $_db->query("SELECT * FROM user");
$user = $stm->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'User List';
include '_head.php';
?>

<main>
    <h1>Registered Users</h1>

    <table class="table" >
        <thead>
            <tr>
                <th>User ID</th>
                <th>User Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($user as $u): ?>
                <tr>
                    <td><?= $u->user_id ?></td>
                    <td><?= $u->user_name ?></td>
                    <td><?= $u->email ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 10px;">
        <a href="user/register.php" class="btn-login" style="text-decoration: none; display: inline-block; width: auto; padding: 5px 10px;">
            + Add New User
        </a>
    </div>

<?php include '_foot.php';