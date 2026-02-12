<?php
require_once __DIR__ . '/../config.php';
$user_email = isset($_GET['user_email']) ? htmlspecialchars($_GET['user_email']) : '';
$user_name = isset($_GET['user_name']) ? htmlspecialchars($_GET['user_name']) : '';
$amount = isset($_GET['amount']) ? htmlspecialchars($_GET['amount']) : '';
$hash = isset($_GET['hash']) ? $_GET['hash'] : '';
$selected_method = isset($_GET['method']) && $_GET['method'] === 'mobilemoneyghana' ? 'mobilemoneyghana' : 'card';

// Validate hash
function bmi_pay_hash($user_email, $user_name, $amount) {
    $data = $user_email . '|' . $user_name . '|' . $amount;
    return hash_hmac('sha256', $data, BMI_PAY_SECRET);
}
$has_params = $user_email || $user_name || $amount || $hash;
if ($has_params && (!$user_email || !$user_name || !$amount || !$hash || $hash !== bmi_pay_hash($user_email, $user_name, $amount))) {
    echo '<div style="max-width:500px;margin:3rem auto;padding:2rem 1.5rem;background:#fff;border-radius:1.2rem;text-align:center;color:#b71c1c;font-weight:600;box-shadow:0 2px 16px rgba(10,23,78,0.07);">Invalid or tampered payment link. Please return to the store and try again.</div>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay with BMI Pay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light paystack-body">
<div class="paystack-page">
    <div class="paystack-shell">
        <header class="paystack-header">
            <div class="paystack-brand">
                <img src="../assets/logo.png" alt="BMI Pay Logo" class="paystack-logo">
                <div>
                    <h1 class="paystack-title mb-1">Pay with BMI Pay</h1>
                    <p class="paystack-subtitle mb-0">Secure card and mobile money checkout</p>
                </div>
            </div>
            <a href="../index.php" class="paystack-back">Back to Home</a>
        </header>
        <div class="paystack-grid">
            <section class="paystack-form-card">
                <div class="paystack-form-header">
                    <h3 class="mb-1">Payment Details</h3>
                    <p class="text-muted mb-0">Your information is used to confirm the transaction.</p>
                </div>
                <form id="paystack-form" onsubmit="payWithPaystack(event)">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" value="<?php echo $user_email; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" value="<?php echo $user_name; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (GHS)</label>
                        <input type="number" class="form-control" id="amount" required min="1" step="0.01" placeholder="e.g. 50.00" value="<?php echo $amount; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" required>
                            <option value="card" <?php if ($selected_method === 'card') echo 'selected'; ?>>Card</option>
                            <option value="mobilemoneyghana" <?php if ($selected_method === 'mobilemoneyghana') echo 'selected'; ?>>Mobile Money (All Networks)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Continue to Paystack</button>
                </form>
                <div id="paystack-message" class="mt-3"></div>
            </section>
        </div>
    </div>
</div>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
function payWithPaystack(e) {
    e.preventDefault();
    var email = document.getElementById('email').value;
    var amount = document.getElementById('amount').value * 100;
    var paymentMethod = document.getElementById('payment_method').value;
    var messageDiv = document.getElementById('paystack-message');
    messageDiv.innerHTML = '';
    var handler = PaystackPop.setup({
        key: '<?php echo PAYSTACK_PUBLIC_KEY; ?>',
        email: email,
        amount: amount,
        currency: 'GHS',
        channels: [paymentMethod],
        callback: function(response){
            messageDiv.innerHTML = '<div class="alert alert-success">Payment successful! Reference: ' + response.reference + '</div>';
        },
        onClose: function(){
            messageDiv.innerHTML = '<div class="alert alert-warning">Transaction was not completed.</div>';
        }
    });
    handler.openIframe();
}
</script>
</body>
</html>
