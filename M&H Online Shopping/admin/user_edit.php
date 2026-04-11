<?php
require '../_base.php';
auth('Admin');

// 1. Get the user_id from the URL
$id = req('id');

// 2. Fetch the current data for THIS specific user
$stm = $_db->prepare("SELECT * FROM user WHERE user_id = ?");
$stm->execute([$id]);
$u = $stm->fetch();

// If the user doesn't exist, go back to the list
if (!$u) redirect('user_list.php');

if (is_post()) {
    $user_name = post('user_name');
    $email     = post('email');
    $role      = post('role');

    // 3. Update the record using the user_id
    $stm = $_db->prepare("UPDATE user SET user_name = ?, email = ?, role = ? WHERE user_id = ?");
    $stm->execute([$user_name, $email, $role, $id]);

    temp('info', 'User updated successfully');
    redirect('user_list.php');
}

$_title = 'Edit User';
include '../_head.php';
?>

<form method="post">
    <label>User ID</label>
    <input type="text" value="<?= $u->user_id ?>" disabled> <br>

    <label>Name</label>
    <?php html_text('user_name', "value='$u->user_name'"); ?>
    <br>

    <label>Role</label>
    <select name="role">
        <option <?= $u->role == 'Member' ? 'selected' : '' ?>>Member</option>
        <option <?= $u->role == 'Admin' ? 'selected' : '' ?>>Admin</option>
    </select>
    <br>

    <button>Update User</button>
</form>

<?php include '../_foot.php'; ?>