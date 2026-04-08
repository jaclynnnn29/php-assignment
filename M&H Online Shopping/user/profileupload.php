<?php
require '_base.php';
//-----------------------------------------------------------------------------

if (is_post()) {
    $f = get_file('photo');

    // Validate: photo (file)
    if ($f == null) {
        $_err['photo'] = 'Required';
    }
    else if (!str_starts_with($f->type, 'image/')) { 
    }
    else if ($f->size > 1 * 1024 * 1024) { 
        $_err['photo'] = 'Maximum 1MB';
    }

    if (!$_err) {
        move_uploaded_file($f->tmp_name, "uploads/$f->name");

        temp('info', 'Photo uploaded');
        redirect();
    }
}

// ----------------------------------------------------------------------------
$_title = 'Demo 1 | Upload';
include '_head.php';
?>

<form method="post" class="form" enctype="multipart/form-data">
    <label for="photo">Photo</label>
    <label class="upload">
        <?= html_file('photo', 'image/*') ?>
        <img src="/images/photo.jpg">
    </label>
    <?= err('photo') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '_foot.php';