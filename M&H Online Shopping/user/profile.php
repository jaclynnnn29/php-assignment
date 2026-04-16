<?php
require '../_base.php';
auth(); // Only logged-in users can access

// Initialize local variables for the form helpers
$email = $_user->email;
$user_name = $_user->user_name;

if (is_post()) {
    $email = post('email');
    $user_name = post('user_name');
    $f = get_file('photo'); 

    // 1. Validation
    if (!$email) {
        $_err['email'] = 'Required';
    } else if (!is_email($email)) {
        $_err['email'] = 'Invalid email format';
    } else if ($email != $_user->email && !is_unique($email, 'user', 'email')) {
        $_err['email'] = 'Email is already taken';
    }

    if (!$user_name) {
        $_err['user_name'] = 'Required';
    }

    // 2. Process Update
    if (!$_err) {
        // Use 'photo' to match your database column
        $photo = $_user->photo; 

        if ($f) {
            // Delete old photo if it exists and isn't the default
            if ($_user->photo != 'default_user.jpg' && file_exists("../photos/{$_user->photo}")) {
                unlink("../photos/{$_user->photo}");
            }
            // Save to the correct relative path
            $photo = save_photo($f, '../photos');
        }

        // Update Database using correct column names
        $stm = $_db->prepare("UPDATE user SET email = ?, user_name = ?, photo = ? WHERE user_id = ?");
        $stm->execute([$email, $user_name, $photo, $_user->user_id]);

        // 3. Update Session Object
        $_user->email = $email;
        $_user->user_name = $user_name;
        $_user->photo = $photo;
        $_SESSION['user'] = $_user;

        temp('info', 'Profile updated successfully!');
        redirect('../home.php');
    }
}

$_title = 'Edit Profile';
include '../_head.php'; // Use your standard header
?>

<main>
    <h1>Edit Profile</h1>

    <form method="post" enctype="multipart/form-data">
        <div>
            <label>Current Photo:</label><br>
            <img src="../photos/<?= $_user->photo ?? 'default_user.jpg' ?>" 
                 style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;"><br><br>
            
            <label>Upload New Photo:</label><br>
            <?php html_file('photo', 'image/*'); ?>
            <?php err('photo'); ?>
        </div>
        <br>

        <div>
            <label>Username:</label><br>
            <?php html_text('user_name'); ?>
            <?php err('user_name'); ?>
        </div>
        <br>

        <div>
            <label>Email Address:</label><br>
            <?php html_text('email'); ?>
            <?php err('email'); ?>
        </div>
        <br>

        <button type="submit">Update Profile</button>
        <a href="../home.php">Back to Home</a>
    </form>

    <hr>
    <p>Security Settings: <a href="password.php">Change Password</a></p>
</main>

<?php include '../_foot.php'; ?>