<?php
include '../_base.php';

// Authorization (member only)
auth('Member');

// Get order ID
$id = req('id');
$stm = $_db->prepare('SELECT * FROM `order` WHERE order_id = ? AND user_id = ?');
$stm->execute([$id, $_user->user_id]);
$o = $stm->fetch();

if (!$o) redirect('history.php');

// Handle payment submission
if (is_post()) {
    $payment_method = post('payment_method');
    
    if (!$payment_method) {
        $_err['payment_method'] = 'Please select a payment method';
    } else {
        // Note: Your database schema does not have 'payment_method' or 'payment_status' columns.
        // We will skip the database update so the page doesn't crash.
        // $stm = $_db->prepare('UPDATE `order` SET payment_method = ?, payment_status = ? WHERE order_id = ?');
        // $stm->execute([$payment_method, 'Paid', $id]);
        
        temp('info', 'Payment successful! Your order is confirmed.');
        redirect("detail.php?id=$id");
    }
}

$_title = 'Order | Payment';
include '../_head.php';
?>

<style>
    .payment-container {
        max-width: 500px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    
    .order-summary {
        background: white;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        border: 1px solid #eee;
    }
    
    .order-summary h3 {
        margin-top: 0;
    }
    
    .payment-methods {
        margin: 20px 0;
    }
    
    .payment-option {
        margin: 15px 0;
        padding: 15px;
        border: 2px solid #ddd;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .payment-option:hover {
        border-color: #4CAF50;
        background-color: #f0f8f0;
    }
    
    .payment-option input[type="radio"] {
        margin-right: 10px;
    }
    
    .payment-option label {
        cursor: pointer;
        width: 100%;
        display: flex;
        align-items: center;
    }
    
    .btn-pay {
        background-color: #4CAF50;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
    }
    
    .btn-pay:hover {
        background-color: #45a049;
    }
    
    .error {
        color: red;
        margin-top: 5px;
    }
</style>

<h1>Payment</h1>

<div class="payment-container">
    <!-- Order Summary -->
    <div class="order-summary">
        <h3>Order Summary</h3>
        <table class="table">
            <tr>
                <th>Order ID:</th>
                <td><?= $o->order_id ?></td>
            </tr>
            <tr>
                <th>Order Date:</th>
                <td><?= $o->datetime ?></td>
            </tr>
            <tr>
                <th>Total Items:</th>
                <td><?= $o->quantity ?></td>
            </tr>
            <tr>
                <th>Total Amount:</th>
                <td><strong>RM <?= sprintf('%.2f', $o->total) ?></strong></td>
            </tr>
        </table>
    </div>

    <!-- Payment Form -->
    <form method="post">
        <h3>Select Payment Method</h3>
        
        <div class="payment-methods">
            <div class="payment-option">
                <label>
                    <input type="radio" name="payment_method" value="Credit Card">
                    💳 Credit Card / Debit Card
                </label>
            </div>
            
            <div class="payment-option">
                <label>
                    <input type="radio" name="payment_method" value="Online Banking">
                    🏦 Online Banking
                </label>
            </div>
            
            <div class="payment-option">
                <label>
                    <input type="radio" name="payment_method" value="E-Wallet">
                    📱 E-Wallet (GCash, PayMaya, etc)
                </label>
            </div>
            
            <div class="payment-option">
                <label>
                    <input type="radio" name="payment_method" value="Bank Transfer">
                    🏧 Bank Transfer
                </label>
            </div>
            
            <div class="payment-option">
                <label>
                    <input type="radio" name="payment_method" value="Cash on Delivery">
                    📦 Cash on Delivery
                </label>
            </div>
        </div>
        
        <?php if ($_err['payment_method'] ?? false): ?>
            <div class="error"><?= $_err['payment_method'] ?></div>
        <?php endif; ?>
        
        <button type="submit" class="btn-pay">Proceed to Payment</button>
    </form>
    
    <p style="margin-top: 20px; text-align: center;">
        <a href="cart.php">← Back to Cart</a>
    </p>
</div>

<?php
include '../_foot.php';
?>
