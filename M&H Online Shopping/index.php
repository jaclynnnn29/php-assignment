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

    <div class="search-container">
        <form action="" method="get" class="search-form">
            <input type="search" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Search by name or email..." class="search-input">
            
            <button type="submit" class="btn-search">Search</button>
            
            <?php if ($search): ?>
                <a href="index.php" class="btn-clear">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <table class="table">
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
                    <td colspan="3" class="no-data">
                        No users found matching "<?= htmlspecialchars($search) ?>"
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="table-actions">
        <a href="user/register.php" class="btn-add">
            + Add New User
        </a>
    </div>
</main>

<?php include '_foot.php'; ?>