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

<main>
    <style>
    /* Centers the main container and limits its width */
    main {
    max-width: 500px;
    margin: 50px auto;
    text-align: center;
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}

    /* Makes the input and button look more balanced */
    form {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    input[type="text"], 
    input[type="email"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #248faf;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
    }

    button:hover {
        background-color: #1a6d87;
    }
</style>

    <h1>Resetting Password \n for \n <?= htmlspecialchars($email) ?></h1>
    <form method="post">
        <label>New Password:</label>
        <?php html_password('pass1'); ?>
        
        <label>Confirm New Password:</label>
        <?php html_password('pass2'); ?>
        <?php err('pass2'); ?>

        <button type="submit">Update Password</button>
    </form>
</main>
