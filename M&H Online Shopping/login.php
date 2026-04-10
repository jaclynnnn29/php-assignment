<?php
require '_base.php';

// If user is already logged in, send them to home
if ($_user) redirect('index.php');

if (is_post()) {
    $email = post('email');
    $password = post('password');

    $stm = $_db->prepare("SELECT * FROM user WHERE email = ?");
    $stm->execute([$email]);
    $user = $stm->fetch();

    if ($user) {
        $now = time();
        $lock_time = $user->locked_until ? strtotime($user->locked_until) : 0;

        // 1. Check if Account is Locked
        if ($lock_time > $now) {
            $wait = ceil(($lock_time - $now) / 60);
            $_err['login'] = "Account locked. Try again in $wait mins.";
        } 
        // 2. Verify Password (SECURITY REQUIREMENT)
        else if (password_verify($password, $user->password_hash)) {
            // Success: Reset attempts and unlock
            $stm = $_db->prepare("UPDATE user SET failed_attempts = 0, locked_until = NULL WHERE user_id = ?");
            $stm->execute([$user->user_id]);
            
            // Log user into session
            login($user, 'index.php'); 
        } 
        // 3. Handle Failed Attempt (SELF-STUDY REQUIREMENT)
        else {
            $attempts = $user->failed_attempts + 1;
            if ($attempts >= 3) {
                // Lock for 5 mins
                $until = date('Y-m-d H:i:s', strtotime('+5 minutes'));
                $stm = $_db->prepare("UPDATE user SET failed_attempts = ?, locked_until = ? WHERE user_id = ?");
                $stm->execute([$attempts, $until, $user->user_id]);
                $_err['login'] = "3 failed attempts. Account locked for 30 mins.";
            } else {
                // Update attempt count
                
                $stm = $_db->prepare("UPDATE user SET failed_attempts = ? WHERE user_id = ?");
                $stm->execute([$attempts, $user->user_id]);
                $_err['login'] = "Invalid password. Attempt: $attempts/3";
            }
        }
    } else {
        $_err['login'] = "Email not found.";
    }
}

$_title = 'User Login';
include '_head.php';
?>

<?php if ($msg = temp('info')) echo "<p style='color:green'>$msg</p>"; ?>

<form method="post">
    <?php err('login'); ?><br>
    
    <label for="email">Email</label><br>
    <?php html_text('email'); ?><br>

    <label for="password">Password</label><br>
    <?php html_password('password'); ?><br>

    <section>
        <button type="submit">Login</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php include '_foot.php'; ?>