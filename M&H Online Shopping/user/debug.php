<?php
require '_base.php';

// Check users in database
$stm = $_db->query("SELECT user_id, email, password_hash, failed_attempts, locked_until FROM user");
$users = $stm->fetchAll();

echo "<h2>Users in Database:</h2>";
echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>Email</th><th>Password Hash</th><th>Failed Attempts</th><th>Locked Until</th></tr>";

foreach($users as $user) {
    echo "<tr>";
    echo "<td>{$user->user_id}</td>";
    echo "<td>{$user->email}</td>";
    echo "<td style='font-size:12px'>{$user->password_hash}</td>";
    echo "<td>{$user->failed_attempts}</td>";
    echo "<td>{$user->locked_until}</td>";
    echo "</tr>";
}
echo "</table>";

// Test password verification for first user
if (count($users) > 0) {
    $test_user = $users[0];
    echo "<h3>Test Password Verification for: {$test_user->email}</h3>";
    
    // You can test with a password you know
    $test_password = "your_password_here"; // CHANGE THIS to the password you think is correct
    
    if (password_verify($test_password, $test_user->password_hash)) {
        echo "<p style='color:green'>✓ Password is CORRECT!</p>";
    } else {
        echo "<p style='color:red'>✗ Password is WRONG or hash doesn't match!</p>";
    }
}
?>