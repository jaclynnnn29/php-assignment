<?php
require '../_base.php';

if (is_post()) {
    $email = trim(post('email'));
    
    // Check if user exists
    $stm = $_db->prepare("SELECT * FROM user WHERE email = ?");
    $stm->execute([$email]);
    $user = $stm->fetch();

    if ($user) {
        // In a real website, you would send an email here.
        // For localhost, we will just redirect them to the reset page.
        redirect("reset_password.php?email=$email");
    } else {
        $_err['email'] = "Email address not found.";
    }
}

$_title = 'Forgot Password';
include '../_head.php';
?>

<main>
    <style>
    /* Centers the main container and limits its width */
    main {
        max-width: 500px;
        margin: 50px auto; 
        padding: 30px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        text-align: center;
    }

    /* Makes the input and button look more balanced */
    form {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    input[type="text"], 
    input[type="email"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #248faf;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
    }

    button:hover {
        background-color: #1a6d87;
    }
    </style>

    <h1>Forgot Password</h1>
    <form method="post">
        <label>Enter your registered email:</label>
        <?php html_text('email'); ?> 
        <?php err('email'); ?>   
        <button type="submit">Verify Email</button>       
    </form>
</main>