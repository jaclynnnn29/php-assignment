<?php
require '_base.php';

// If already logged in, sent to index
if ($_user) redirect('index.php');

if (is_post()) {
    $email    = trim(post('email'));
    $password = trim(post('password'));

    // 1. Check database for user
    $stm = $_db->prepare("SELECT * FROM user WHERE email = ?");
    $stm->execute([$email]);
    $user = $stm->fetch();

    if ($user) {
        $now = time();
        $lock_time = $user->locked_until ? strtotime($user->locked_until) : 0;

        // 2. Check Lock Status
        if ($lock_time > $now) {
            $wait = ceil(($lock_time - $now) / 60);
            $_err['login'] = "Account locked. Try again in $wait mins.";
        } 
        
        // 3. Verify Password (using the hash we verified in your DB)
        else if (password_verify($password, $user->password_hash)) {
            // Reset attempts on success
            $stm = $_db->prepare("UPDATE user SET failed_attempts = 0, locked_until = NULL WHERE user_id = ?");
            $stm->execute([$user->user_id]);

            // Determine redirect URL based on role
            if ($user->role == 'Admin') {
                temp('info', 'Admin login successful!');
                $url = 'admin/product_list.php';
            } else {
                temp('info', 'Member login successful!');
                $url = 'product/list.php';
            }

            // Log in and Redirect
            login($user, $url);
        } 
        
        // 4. Handling Wrong Passwords
        else {
            $attempts = $user->failed_attempts + 1;
            if ($attempts >= 3) {
                // Lock for 1 minute after 3 tries
                $until = date('Y-m-d H:i:s', strtotime('+1 minutes'));
                $stm = $_db->prepare("UPDATE user SET failed_attempts = ?, locked_until = ? WHERE user_id = ?");
                $stm->execute([$attempts, $until, $user->user_id]);
                $_err['login'] = "3 failed attempts. Account locked for 1 min.";
            } else {
                $stm = $_db->prepare("UPDATE user SET failed_attempts = ? WHERE user_id = ?");
                $stm->execute([$attempts, $user->user_id]);
                $_err['login'] = "Invalid password. Attempt: $attempts/3";
            }
        }
    } else {
        // Runs if email field is empty or not in database
        $_err['login'] = "Email not found.";
    }
}

$_title = 'User Login';
include '_head.php';
?>

<div class="container">

    <?php if ($msg = temp('info')) echo "<p style='color:green'>$msg</p>"; ?>

    <form method="post" class="form">
        <div class="form-group">
            <?php err('login'); ?>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <?php html_text('email', 'class="form-control" placeholder="Enter your email"'); ?>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <?php html_password('password', 'class="form-control" placeholder="Enter your password"'); ?>
        </div>

        <section class="form-actions">
            <button type="submit" class="btn-submit">Login</button>
            <button type="reset" class="btn-reset">Reset</button>
        </section>

        <p class="mt-3">Don't have an account? <a href="user/register.php">Join Our Membership</a></p>
    </form>
</div>

<?php include '_foot.php'; ?>