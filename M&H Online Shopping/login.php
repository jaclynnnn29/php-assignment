<?php
require '_base.php';

if ($_user) redirect('index.php');

if (is_post()) {
    $email = trim(post('email'));
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

        
        
        // 3. Verify Password (Matches password_hash column in your DB)
        else if (password_verify($password, $user->password_hash)) {
            // 1. Update DB
            $stm = $_db->prepare("UPDATE user SET failed_attempts = 0, locked_until = NULL WHERE user_id = ?");
            $stm->execute([$user->user_id]);
            
            // 2. Set the session (DO NOT use the redirect inside the function yet)
            $_SESSION['user'] = $user; 

            // 3. Decide where to go based on the ROLE
            if ($user->role == 'Admin') {
                temp('info', 'Admin login successful!');
                redirect('admin/product_list.php');
            } else {
                temp('info', 'Member login successful!');
                redirect('product/list.php');
            }
        }
        
        // 5. Handling Wrong Passwords
        else {
            $attempts = $user->failed_attempts + 1;
            if ($attempts >= 3) {
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

    <p>Don't have an account? <a href="user/register.php">Join Our Membership</a>
    </p>
</form>

<?php include '_foot.php'; 
?>