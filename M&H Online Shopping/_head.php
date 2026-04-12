<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="shortcut icon" href="/images/favicon.png">
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/app.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Flash message -->
    <div id="info"><?= temp('info') ?></div>

    <header>
        <h1>
            <a href="index.php">
                <img src="/images/MnH_Logo.png" alt="M&H Logo"> 
                <span>M&H Online Shopping</span>
            </a>
        </h1>

        <div>
            <a href="/home.php" class="<?= $_title == 'Home' ? 'active' : '' ?>">
                        <i class="bx bx-home-alt"></i> Home
            </a>
            <a href="/product/list.php">
                <i class="bx bx-package"></i> Products
            </a>
            <a href="/about.php">
                <i class="bx bx-info-circle"></i> About
            </a>
            <a href="/contact.php">
                <i class="bx bx-id-card"></i> Contact
            </a>

            <?php if ($_user): ?>
                <a href="/logout.php"><i class="bx bx-door-open-alt"></i> Logout</a>
            <?php else: ?>
                <a href="/login.php"><i class="bx bx-key"></i> Login</a>
                <a href="/user/register.php"><i class="bx bx-user-plus"></i> Register</a>
            <?php endif ?>        
                
        </div>
    </header>

        <nav class="sub_nav">
            <a href="/home.php">Index</a>
            <a href="/order/checkout.php">CheckOut</a>
            <a href="/order/cart.php">
                Shopping Cart
                
                <?php 
                    $cart = get_cart();
                    $count = count($cart);
                    if ($count) {
                        echo "($count)";
                    }
                ?>
            </a>

            <?php if ($_user?->role == 'Member'): ?>
                <a href="/order/history.php">Order History</a>
            <?php endif ?>

            <?php if ($_user?->role == 'Admin'): ?>
                <a href="/admin/user_list.php">Order Listing (Admin)</a>
                <a href="/admin/order_manage.php">Order Status (Admin)</a>
            <?php endif ?>

        </nav>

    <main>
        <h1><?= $_title ?? 'Untitled' ?></h1>