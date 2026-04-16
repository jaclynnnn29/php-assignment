<?php
require '../_base.php';
auth(); 

if (is_post()) {
    $current_pass = post('current_pass');
    $new_pass     = post('new_pass');
    $confirm_pass = post('confirm_pass');

    if (!password_verify($current_pass, $_user->password_hash)) {
        $_err['current_pass'] = 'Incorrect current password';
    }

    if (!$new_pass) {
        $_err['new_pass'] = 'Required';
    } else if (strlen($new_pass) < 6) {
        $_err['new_pass'] = 'Minimum 6 characters';
    }

    if ($confirm_pass != $new_pass) {
        $_err['confirm_pass'] = 'Passwords do not match';
    }

    if (!$_err) {
        $hash = password_hash($new_pass, PASSWORD_DEFAULT);
        $stm = $_db->prepare("UPDATE user SET password_hash = ? WHERE user_id = ?");
        $stm->execute([$hash, $_user->user_id]);

        $_user->password_hash = $hash;
        $_SESSION['user'] = $_user;

        temp('info', 'Password updated successfully!');
        redirect('profile.php');
    }
}

$_title = 'Change Password';
include '../_head.php';
?>

<main class="pass-container">
    <div class="pass-card">
        <h1><i class="bx bx-lock-open"></i> Change Password</h1>
        <p>Update your password to keep your account secure.</p>
        
        <form method="post">
            <div class="input-group">
                <label>Current Password</label>
                <?php html_password('current_pass', 'placeholder="Enter current password"'); ?>
                <?php err('current_pass'); ?>
            </div>

            <div class="input-group">
                <label>New Password</label>
                <?php html_password('new_pass', 'placeholder="Minimum 6 characters"'); ?>
                <?php err('new_pass'); ?>
            </div>

            <div class="input-group">
                <label>Confirm New Password</label>
                <?php html_password('confirm_pass', 'placeholder="Repeat new password"'); ?>
                <?php err('confirm_pass'); ?>
            </div>

            <button type="submit" class="btn-update">Update Password</button>
            <a href="profile.php" class="cancel-link">Cancel</a>
        </form>
    </div>
</main>

<?php include '../_foot.php'; ?>