<?php
session_start();
require_once '_base.php';

// Simple search - get what user typed in search box
$search = $_GET['search'] ?? '';
?>

<html>
<head>
    <title>Member List - Admin</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .search-box {
            margin: 20px 0;
        }
    </style>
</head>
<body>

<?php include '_head.php'; ?>

<h2>Member List (Admin)</h2>

<!-- SIMPLE SEARCH FORM -->
<div class="search-box">
    <form method="GET">
        <input type="text" name="search" placeholder="Search by name or email..." 
               value="<?php echo $search; ?>" size="40">
        <button type="submit">Search</button>
        <a href="member_list.php">Clear</a>
    </form>
</div>

<!-- MEMBER TABLE -->
<table>
    <tr>
        <th>ID</th>
        <th>Photo</th>
        <th>Username</th>
        <th>Email</th>
        <th>Action</th>
    </tr>
    
<?php
// Database connection (adjust to your connection)
$conn = mysqli_connect("localhost", "root", "", "mh_shopping");

// SIMPLE SEARCH QUERY
if($search != "") {
    // If user searched something
    $sql = "SELECT * FROM  
            WHERE role = 'member' 
            AND (username LIKE '%$search%' 
            OR email LIKE '%$search%')";
} else {
    // If no search, show all members
    $sql = "SELECT * FROM user WHERE role = 'member'";
}

$result = mysqli_query($conn, $sql);

// Check if have members
if(mysqli_num_rows($result) > 0) {
    // Loop through each member
    while($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td>
                <?php if($row['profile_photo'] != "") { ?>
                    <img src="../photos/<?php echo $row['profile_photo']; ?>" 
                         width="50" height="50">
                <?php } else { ?>
                    No photo
                <?php } ?>
            </td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
                <a href="member_detail.php?id=<?php echo $row['user_id']; ?>">View</a>
            </td>
        </tr>
        <?php
    }
} else {
    // No members found
    ?>
    <tr>
        <td colspan="5">No members found</td>
    </tr>
    <?php
}

mysqli_close($conn);
?>

</table>

<?php include '_foot.php'; ?>

</body>
</html>