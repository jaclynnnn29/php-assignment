<?php
require '_base.php';
auth(); // Only logged-in users can access

// Initialize local variables with current session data
$email = $_user->email;

if (is_post()) {
    $email = post('email');
    $f = get_file('photo'); // Using your helper function

    // 1. Validation
    if (!$email) {
        $_err['email'] = 'Required';
    } else if (!is_email($email)) {
        $_err['email'] = 'Invalid email format';
    } else if ($email != $_user->email && !is_unique($email, 'Users', 'email')) {
        $_err['email'] = 'Email is already taken by another user';
    }

    // 2. Process Update
    if (!$_err) {
        $photo = $_user->profile_photo; // Default to existing photo

        // If a new photo is uploaded
        if ($f) {
            // Delete the old photo if it exists and isn't the default
            if ($_user->profile_photo != 'default.jpg' && file_exists("photos/{$_user->profile_photo}")) {
                unlink("photos/{$_user->profile_photo}");
            }
            // Save new photo using your _base.php function
            $photo = save_photo($f, 'photos');
        }

        // Update Database
        $stm = $_db->prepare("UPDATE Users SET email = ?, profile_photo = ? WHERE user_id = ?");
        $stm->execute([$email, $photo, $_user->user_id]);

        // 3. Update Session Object so changes show immediately
        $_user->email = $email;
        $_user->profile_photo = $photo;
        $_SESSION['user'] = $_user;

        temp('info', 'Profile updated successfully!');
        redirect('profile.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <style>
        .profile-img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; }
    </style>
</head>
<body>
    <h1>Edit Profile</h1>

    <?php if ($msg = temp('info')): ?>
        <p style="color: green;"><?= $msg ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div>
            <label>Current Photo:</label><br>
            <img src="photos/<?= $_user->profile_photo ?>" class="profile-img"><br><br>
            
            <label>Upload New Photo:</label><br>
            <?php html_file('photo', 'image/*'); ?>
            <?php err('photo'); ?>
        </div>
        <br>

        <div>
            <label>Email Address:</label><br>
            <?php html_text('email'); ?>
            <?php err('email'); ?>
        </div>
        <br>

        <button type="submit">Update Profile</button>
        <a href="index.php">Back to Home</a>
    </form>

    <hr>
    <p>Security Settings: <a href="password.php">Change Password</a></p>
</body>
</html>