<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="icon" href="/images/favicon.png">
    <link rel="stylesheet" href="../css/app.css"> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div id="info"><?= temp('info') ?></div>

    <header>
        <h1>
            <a href="../home.php">
                <img src="../images/MnH_Logo.png" alt="M&H Logo"> 
                <span>M&H Online Shopping</span>
            </a>
        </h1>

        <div style="display: flex; align-items: center;">
            <a href="../home.php" class="<?= ($_title ?? '') == 'Home' ? 'active' : '' ?>">
                <i class="bx bx-home-alt"></i> Home
            </a>
            <a href="../product/list.php">
                <i class="bx bx-package"></i> Products
            </a>
            
            <?php if ($_user): ?>
                <a href="../logout.php"><i class="bx bx-door-open-alt"></i> Logout</a>
                
                <a href="../user/profile.php" style="text-decoration: none; margin-left: 10px;">
                    <span class="user-info" style="display: flex; flex-direction: column; align-items: center; gap: 2px;">
                        <img src="../photos/<?= $_user->photo ?? 'default_user.jpg' ?>" 
                             style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid white;">
                        <b style="color: white; font-size: 0.75em;"><?= encode($_user->user_name) ?></b>
                    </span>
                </a>

            <?php else: ?>
                <a href="../login.php"><i class="bx bx-key"></i> Login</a>
                <a href="../user/register.php"><i class="bx bx-user-plus"></i> Register</a>
            <?php endif ?>  

            <a href="../about.php"><i class="bx bx-info-circle"></i> About</a>
        </div>
    </header>

    <nav class="sub_nav">
        <?php if (($_user->role ?? '') == 'Member'): ?>
            <a href="../product/list.php">Products</a>
            <a href="../order/cart.php">
                Shopping Cart
                <?php 
                    $cart = get_cart();
                    if ($count = count($cart)) echo "($count)";
                ?>
            </a>
            <a href="../order/history.php">Order History</a>
        <?php endif ?>

        <?php if (($_user->role ?? '') == 'Admin'): ?> 
            <a href="../product/list.php">Products</a>
            <a href="../index.php">Index</a>
            <a href="../admin/product_list.php">Product Management</a>
            <a href="../admin/order_list.php">Order List</a>
            
            <?php if (isset($order_id)): ?>
                <a href="../admin/order_detail.php?id=<?= $order_id ?>" class="active">Order Details #<?= $order_id ?></a>
            <?php endif ?>
        <?php endif ?>
    </nav>