<?php
require '_base.php';
include '_head.php';

if (is_post()) {
    $email = post('email');
    $password = post('password');
    $confirm = post('confirm');
    $f = get_file('photo');

    // 
    if (!$email) $_err['email'] = 'Required';
    else if (!is_email($email)) $_err['email'] = 'Invalid email';
    else if (!is_unique($email, 'Users', 'email')) $_err['email'] = 'Duplicated';

    if (!$password) $_err['password'] = 'Required';
    else if (strlen($password) < 6) $_err['password'] = 'Too short (min 6)';

    if ($password != $confirm) $_err['confirm'] = 'Not match';

    if (!$f) $_err['photo'] = 'Required';

    if (!$_err) {
        //save photo
        $photo = save_photo($f, 'photos', 200, 200);

        //password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stm = $_db->prepare("INSERT INTO Users (email, password_hash, profile_photo) VALUES (?, ?, ?)");
        $stm->execute([$email, $hash, $photo]);

        temp('info', 'Registration successful! Please login.');
        redirect('login.php');
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
    <h1>Member Registration</h1>
    <form method="post" enctype="multipart/form-data">
        <label>Email</label><br>
        <?php html_text('email'); err('email'); ?><br>

        <label>Password</label><br>
        <?php html_password('password'); err('password'); ?><br>

        <label>Confirm Password</label><br>
        <?php html_password('confirm'); err('confirm'); ?><br>

        <label>Profile Photo</label><br>
        <?php html_file('photo', 'image/*'); err('photo'); ?><br>

        <button>Register</button>
    </form>
</body>
</html>