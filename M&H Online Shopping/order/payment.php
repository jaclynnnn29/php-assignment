<?php
include '../_base.php';
auth('Member');

// 1. Get order_id from the URL (sent by checkout.php)
$id = req('order_id');

// 2. Fetch the order record from the database
// We use `orders` (plural) and backticks because 'order' is a reserved SQL word
$stm = $_db->prepare('SELECT * FROM `orders` WHERE order_id = ? AND user_id = ?');
$stm->execute([$id, $_user->user_id]);
$o = $stm->fetch();

// 3. If order not found, redirect to history
if (!$o) {
    temp('info', 'Order not found or already processed.');
    redirect('history.php');
}

// --- ADD THIS BLOCK FOR PAYPAL ---
if (req('paypal_success')) {
    // Update the database: Set status to 'Paid' and method to 'PayPal'
    $stm = $_db->prepare('UPDATE `orders` SET status = ?, payment_method = ? WHERE order_id = ?');
    $stm->execute(['Paid', 'PayPal', $id]);

    temp('info', 'PayPal Payment successful! Your order is confirmed.');
    redirect("detail.php?id=$id");
}
// ---------------------------------

// 4. Handle payment submission
if (is_post()) {
    $payment_method = post('payment_method');
    
    if (!$payment_method) {
        $_err['payment_method'] = 'Please select a payment method';
    } else {
        
        // ... inside your if (is_post()) block ...
        $stm = $_db->prepare('UPDATE `orders` SET status = ?, payment_method = ? WHERE order_id = ?');
        $stm->execute(['Paid', $payment_method, $id]);

        temp('info', 'Payment successful! Your order is confirmed.');
// Redirect to detail.php, NOT order_detail.php
        redirect("detail.php?id=$id");
    }
}

$_title = 'Order | Payment';
include '../_head.php';
?>

<script src="https://www.paypal.com/sdk/js?client-id=AUYbQd7KxkcMO_EDQcDGliBr0R9fMHn9TqIjDLxMWp2DyLvZYn8gT3kbg0MDdVTJQO0JGgLh5eOTGnh0&currency=MYR"></script>

<style>
    .payment-container {
        max-width: 600px;
        margin: 30px auto;
        padding: 30px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    .order-summary {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 25px;
        border-left: 5px solid #2b91af;
    }
    
    .payment-option {
        margin: 10px 0;
        padding: 15px;
        border: 1px solid #eee;
        border-radius: 5px;
        display: block;
        cursor: pointer;
        transition: 0.2s;
    }
    
    .payment-option:hover {
        background: #f0f7f9;
        border-color: #2b91af;
    }

    .payment-option input { margin-right: 15px; }
    
    .btn-pay {
        background-color: #2b91af;
        color: white;
        padding: 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 18px;
        width: 100%;
        font-weight: bold;
        margin-top: 20px;
    }
    
    .btn-pay:hover { background-color: #237a94; }
    .error { color: #ca5959; font-weight: bold; margin-bottom: 15px; }
</style>

<main>
    <div class="payment-container">
        <h1>Complete Your Payment</h1>

        <div class="order-summary">
            <h3>Order #<?= str_pad($o->order_id, 5, '0', STR_PAD_LEFT) ?></h3>
            <p><strong>Date:</strong> <?= date('d-M-Y H:i', strtotime($o->order_date)) ?></p>
            <p style="font-size: 1.2em; color: #2b91af;">
                <strong>Total Amount: RM <?= number_format($o->total_price, 2) ?></strong>
            </p>
        </div>

        
        <form method="post">
            <h3>How would you like to pay?</h3>

            <div id="paypal-button-container"></div>
            <div class="paypal-divider"></div>


            <?php if (isset($_err['payment_method'])): ?>
                <div class="error"><?= $_err['payment_method'] ?></div>
            <?php endif; ?>

            <button type="submit" class="btn-pay">Confirm Manual Payment</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="cart.php" style="color: #666; text-decoration: none;">← Return to Cart</a>
        </p>
    </div>
</main>

<script>
    paypal.Buttons({
        // Set up the transaction
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?= $o->total_price ?>' // Use your total_price variable
                    }
                }]
            });
        },

        // Finalize the transaction
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(orderData) {
                // Redirect to handle the DB update
                window.location.href = "payment.php?order_id=<?= $o->order_id ?>&paypal_success=1";
            });
        }
    }).render('#paypal-button-container');
</script>

<?php include '../_foot.php'; ?>