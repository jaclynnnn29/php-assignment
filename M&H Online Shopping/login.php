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

        // 1. 
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

include '_head.php';
?>

<main>
    <div class="login-container">
        <h1>User Login</h1>

        <form method="post">
            <?php if (isset($_err['login'])): ?>
                <div class="err-msg"><?php err('login'); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="email">Email Address</label>
                <?php html_text('email', 'placeholder="e.g. jaclyn@gmail.com"'); ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <?php html_password('password', 'placeholder="••••••••"'); ?>
            </div>

            <div class="form-group" style="flex-direction: row; justify-content: left; gap: 10px;margin-left: 50px;margin-bottom: 20px;">
                <input type="checkbox" name="remember" id="remember" style="width: 18px; height: 18px; cursor: pointer;">
                <label for="remember" style="margin-bottom: 0; cursor: pointer; font-weight: normal; color: #555;">Remember Me</label>
            </div>

            <button type="submit" class="btn-login">Login to Account</button>
            <button type="reset" class="btn-reset">Clear Fields</button>

            <p style="margin-top: 20px;">
                New here? <a href="user/register.php" style="color: #248faf; font-weight: bold;">Join Our Membership</a>
            </p>
        </form>
    </div>
</main>
<?php include '_foot.php'; ?>