<?php
require '_base.php';
auth(); // Ensure user is logged in

if (is_post()) {
    $current_pass = post('current_pass');
    $new_pass     = post('new_pass');
    $confirm_pass = post('confirm_pass');

    // 1. Validate Current Password
    // If you are currently using the "plain text" bypass, 
    // this check might fail if your DB still has the old broken hash.
    if (!password_verify($current_pass, $_user->password_hash)) {
        $_err['current_pass'] = 'Incorrect current password';
    }

    // 2. Validate New Password
    if (!$new_pass) {
        $_err['new_pass'] = 'Required';
    } else if (strlen($new_pass) < 6) {
        $_err['new_pass'] = 'Minimum 6 characters';
    }

    if ($confirm_pass != $new_pass) {
        $_err['confirm_pass'] = 'Passwords do not match';
    }

    // 3. Update Database
    if (!$_err) {
        $hash = password_hash($new_pass, PASSWORD_DEFAULT);
        
        $stm = $_db->prepare("UPDATE user SET password_hash = ? WHERE user_id = ?");
        $stm->execute([$hash, $_user->user_id]);

        // Update the session object so it has the new hash
        $_user->password_hash = $hash;
        $_SESSION['user'] = $_user;

        temp('info', 'Password updated successfully!');
        
        // Pathing: If this file is in the root, use 'index.php'
        // If this file is in admin/ folder, use '../index.php'
        redirect('index.php');
    }
}

$_title = 'Update Password';
include '_head.php';
?>

<div class="container">
    <h1>Update Password</h1>
    
    <form method="post">
        <label>Current Password:</label><br>
        <?php html_password('current_pass'); ?> 
        <?php err('current_pass'); ?><br><br>

        <hr>

        <label>New Password:</label><br>
        <?php html_password('new_pass'); ?> 
        <?php err('new_pass'); ?><br><br>

        <label>Confirm New Password:</label><br>
        <?php html_password('confirm_pass'); ?> 
        <?php err('confirm_pass'); ?><br><br>

        <button type="submit">Update Password</button>
        <a href="index.php">Cancel</a>
    </form>
</div>

<?php include '_foot.php'; ?>