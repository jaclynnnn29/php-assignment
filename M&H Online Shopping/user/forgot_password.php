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

<main class="auth-page">
    <h1>Forgot Password</h1>
    <form method="post" class="auth-form">
        <label for="email">Enter your registered email:</label>
        <?php html_text('email', 'placeholder="e.g. user@example.com"'); ?> 
        <?php err('email'); ?>   
        <button type="submit" class="btn-primary">Verify Email</button>       
    </form>
    
    <p class="auth-footer">
        Remembered your password? <a href="../login.php">Back to Login</a>
    </p>
</main>

<?php include '../_foot.php'; ?>