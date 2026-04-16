<?php
session_start();
require_once '_base.php';

// Simple search - get what user typed in search box
$search = $_GET['search'] ?? '';

// Connection (Adjust to your connection)
$conn = mysqli_connect("localhost", "root", "", "mh_shopping");

// SIMPLE SEARCH QUERY
if($search != "") {
    $sql = "SELECT * FROM user 
            WHERE role = 'member' 
            AND (username LIKE '%$search%' 
            OR email LIKE '%$search%')";
} else {
    $sql = "SELECT * FROM user WHERE role = 'member'";
}

$result = mysqli_query($conn, $sql);

$_title = 'Member List - Admin';
include '_head.php'; 
?>

<main>
    <h2>Member List (Admin)</h2>

    <div class="search-container">
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by name or email..." 
                   value="<?php echo htmlspecialchars($search); ?>" class="search-input">
            <button type="submit" class="btn-search">Search</button>
            <a href="member_list.php" class="btn-clear">Clear</a>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['user_id']; ?></td>
                        <td>
                            <?php if($row['profile_photo'] != ""): ?>
                                <img src="../photos/<?php echo $row['profile_photo']; ?>" class="member-photo">
                            <?php else: ?>
                                <span class="no-photo">No photo</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <a href="member_detail.php?id=<?php echo $row['user_id']; ?>" class="btn-view">View</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="no-data">No members found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include '_foot.php'; ?>