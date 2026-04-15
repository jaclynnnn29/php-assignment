<?php
require '../_base.php';

if (is_post()) {
    $email = trim(post('email'));
    
    // Check if user exists
    $stm = $_db->prepare("SELECT * FROM user WHERE email = ?");
    $stm->execute([$email]);
    $user = $stm->fetch();

    if ($user) {
        // In a real website, you would send an email here.
        // For localhost, we will just redirect them to the reset page.
        redirect("reset_password.php?email=$email");
    } else {
        $_err['email'] = "Email address not found.";
    }
}

$_title = 'Forgot Password';
include '../_head.php';
?>

<main>
    <h1>Forgot Password</h1>
    <form method="post">
        <label>Enter your registered email:</label>
        <?php html_text('email'); ?>
        <?php err('email'); ?>
        
        <button type="submit">Verify Email</button>
    </form>
</main>

<?php include '../_foot.php'; ?>