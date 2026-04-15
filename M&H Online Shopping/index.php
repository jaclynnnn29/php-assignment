<?php
include '_base.php';
auth('Admin'); // Restrict access to Admins only

// ----------------------------------------------------------------------------

// 1. Get the search term from the URL
$search = trim(req('search'));

// 2. Logic: If there is a search term, filter the query. Otherwise, get all.
if ($search) {
    $stm = $_db->prepare("
        SELECT * FROM user 
        WHERE user_name LIKE ? OR email LIKE ?
    ");
    // Use % signs for partial matches (e.g., "ma" finds "Mary")
    $stm->execute(["%$search%", "%$search%"]);
} else {
    $stm = $_db->query("SELECT * FROM user");
}

$user = $stm->fetchAll();

// ----------------------------------------------------------------------------

$_title = 'User List';
include '_head.php';
?>

<main>
    <h1>Registered Users</h1>

    <div style="margin-bottom: 20px;">
        <form action="" method="get" style="display: flex; gap: 10px;">
            <input type="search" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Search by name or email..." 
                   style="flex-grow: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            
            <button type="submit" class="btn-login" style="width: auto; padding: 0 20px;">Search</button>
            
            <?php if ($search): ?>
                <a href="index.php" class="btn-login" style="text-decoration: none; background: #666; width: auto; padding: 10px 20px;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

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

            <?php if (empty($user)): ?>
                <tr>
                    <td colspan="3" style="text-align: center; padding: 20px; color: #999;">
                        No users found matching "<?= htmlspecialchars($search) ?>"
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 10px;">
        <a href="user/register.php" class="btn-login" style="text-decoration: none; display: inline-block; width: auto; padding: 5px 10px;">
            + Add New User
        </a>
    </div>
</main>·