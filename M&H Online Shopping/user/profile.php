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

<main class="profile-container">
    <div class="profile-card">
        <h1>Edit Profile</h1>

        <form method="post" enctype="multipart/form-data" class="profile-form">
            <div class="photo-section">
                <div class="image-wrapper">
                    <img src="../photos/<?= $_user->photo ?? 'default_user.jpg' ?>" alt="Profile Photo">
                    <label class="upload-btn">
                        <i class="bx bx-camera"></i>
                        
                    </label>             
                </div>
                <div style="display: none;">
                    <?php html_file('photo', 'image/*', 'hidden'); ?> //This will be triggered by the camera button (hide)
                </div>
                <p class="photo-hint">Click the camera to change photo</p>
                <?php err('photo'); ?>
            </div>

            <div class="input-group">
                <label><i class="bx bx-user"></i> New Username</label>
                <?php html_text('user_name', 'placeholder="Enter username"'); ?>
                <?php err('user_name'); ?>
            </div>

            <div class="input-group">
                <label><i class="bx bx-envelope"></i> New Email Address</label>
                <?php html_text('email', 'placeholder="Enter email"'); ?>
                <?php err('email'); ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Update New Profile</button>
                <a href="../home.php" class="btn-secondary">Cancel</a>
            </div>
        </form>

        <div class="profile-footer">
            <a href="password.php"><i class="bx bx-lock-alt"></i> Change Password</a>
        </div>
    </div>
</main>

<script>
    // 1. Select the hidden file input and the profile image element
    const fileInput = document.querySelector('input[type="file"]');
    const profileImg = document.querySelector('.image-wrapper img');

    // 2. Listen for when a user selects a file
    if (fileInput) {
        fileInput.addEventListener('change', e => {
            const file = e.target.files[0];
            
            if (file) {
                // 3. Create a temporary URL for the selected file and update the <img> src
                profileImg.src = URL.createObjectURL(file);
            }
        });
    }
</script>

<?php include '../_foot.php'; ?>