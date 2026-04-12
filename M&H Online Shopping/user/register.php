<?php
require '../_base.php';

if (is_post()) {
    $email = post('email');
    $password = post('password');
    $confirm = post('confirm');
    $f = get_file('photo');

    // 1. Verify
    if (!$email) $_err['email'] = 'Required';
    else if (!is_email($email)) $_err['email'] = 'Invalid email';
    else if (!is_unique($email, 'user', 'email')) $_err['email'] = 'Duplicated';

    if (!$password) $_err['password'] = 'Required';
    else if (strlen($password) < 6) $_err['password'] = 'Too short (min 6)';

    if ($password != $confirm) $_err['confirm'] = 'Not match';

    if (!$f) $_err['photo'] = 'Required';

    if (!$_err) {
        // 2. Generate next sequential user ID (e.g., U004)
        $stm = $_db->prepare("SELECT MAX(CAST(SUBSTRING(user_id, 2) AS UNSIGNED)) AS max_id FROM user");
        $stm->execute();
        $max_id = $stm->fetchColumn() ?: 0;
        $user_id = 'U' . str_pad($max_id + 1, 3, '0', STR_PAD_LEFT);

        $user_name = explode('@', $email)[0]; 
        
        // 3. Save photo (Fulfills Profile Photo requirement)
        // Note: Check if your folder is '../photos' or '../uploads'
        $photo = save_photo($f, '../photos', 200, 200);

        // 4. Password Hashing (Fulfills Security requirement)
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // FIXED SQL: Added 'role' to the column list
        $stm = $_db->prepare("INSERT INTO user (user_id, user_name, email, password_hash, photo, role) VALUES (?, ?, ?, ?, ?, 'Member')");
        $stm->execute([$user_id, $user_name, $email, $hash, $photo]);

        temp('info', 'Registration successful! Please login.');
        redirect('../login.php');
    }
}

$_title = 'JOIN OUR MEMBERSHIP';
include '../_head.php';
?>

<form method="post" enctype="multipart/form-data">
    <label>Email</label><br>
    <?php html_text('email'); err('email'); ?><br>

    <label>Create a Password</label><br>
    <?php html_password('password'); err('password'); ?><br>

    <label>Confirm Password</label><br>
    <?php html_password('confirm'); err('confirm'); ?><br>

    <label>Profile Photo</label><br>
    <?php html_file('photo', 'image/*'); err('photo'); ?><br>

    <button>Register</button>
</form>

<?php include '../_foot.php'; ?>