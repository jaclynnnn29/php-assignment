<?php
require '../_base.php';

$email = req('email');

if (is_post()) {
    $pass1 = post('pass1');
    $pass2 = post('pass2');

    if ($pass1 !== $pass2) {
        $_err['pass2'] = "Passwords do not match.";
    } else {
        // 1. Hash the new password
        $hash = password_hash($pass1, PASSWORD_DEFAULT);
        
        // 2. Update the database
        $stm = $_db->prepare("UPDATE user SET password_hash = ?, failed_attempts = 0, locked_until = NULL WHERE email = ?");
        $stm->execute([$hash, $email]);

        temp('info', 'Password reset successful! Please login.');
        redirect('../login.php');
    }
}

$_title = 'Reset Password';
include '../_head.php';
?>

<main class="reset-container">
    <h1>Resetting Password \n for \n <?= htmlspecialchars($email) ?></h1>

    <form method="post" class="reset-form">
        <div>
            <label>New Password:</label>
            <?php html_password('pass1', 'class="reset-input"'); ?>
        </div>
        
        <div>
            <label>Confirm New Password:</label>
            <?php html_password('pass2', 'class="reset-input"'); ?>
            <?php err('pass2'); ?>
        </div>

        <button type="submit" class="btn-reset-submit">Update Password</button>
    </form>
</main>
