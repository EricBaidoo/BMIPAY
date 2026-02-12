<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Pay - Payment Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php
    // Enforce HTTPS
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
        $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('Location: ' . $redirect);
        exit;
    }

    // Add Content Security Policy header
    header("Content-Security-Policy: default-src 'self' https://cdn.jsdelivr.net; script-src 'self' https://cdn.jsdelivr.net https://js.paystack.co https://www.paypal.com; style-src 'self' https://cdn.jsdelivr.net https://fonts.googleapis.com 'unsafe-inline'; font-src 'self' https://fonts.gstatic.com data:; img-src 'self' data:;");

    // Load secret key
    require_once __DIR__ . '/config.php';

    // Sanitize and validate input
    $user_email = isset($_GET['user_email']) ? htmlspecialchars($_GET['user_email']) : '';
    $user_name = isset($_GET['user_name']) ? htmlspecialchars($_GET['user_name']) : '';
    $amount = isset($_GET['amount']) ? htmlspecialchars($_GET['amount']) : '';

    // Validate email
    if ($user_email && !filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $user_email = '';
    }
    // Validate amount (must be positive number)
    if ($amount && (!is_numeric($amount) || $amount <= 0)) {
        $amount = '';
    }

    // Generate HMAC hash for payment links
    function bmi_pay_hash($user_email, $user_name, $amount) {
        $data = $user_email . '|' . $user_name . '|' . $amount;
        return hash_hmac('sha256', $data, BMI_PAY_SECRET);
    }
    ?>
    <div class="main-bg">
        <header class="main-header container">
            <img src="assets/logo.png" alt="BMI Pay Logo" class="main-logo">
            <div>
                <h1 class="main-title mb-0">BMI Pay</h1>
                <div class="main-subtitle">Pay With Ease<?php 
                    if ($amount) { 
                        // If amount is numeric, format to 2 decimals
                        $formatted_amount = is_numeric($amount) ? number_format($amount, 2) : $amount;
                        // Show GHS by default
                        echo ' &mdash; Amount: <span style="color:#0a174e;font-weight:600">GHS ' . $formatted_amount . '</span>';
                    } 
                ?></div>
            </div>
        </header>
        <section class="hero-section container text-center py-4 mb-4">
            <h2 class="hero-title mb-3">Fast, Secure &amp; Flexible Payments</h2>
            <p class="hero-desc mb-0">Choose your preferred payment method and complete your transaction in seconds.<br>We support Paystack, PayPal, and Zelle for your convenience.</p>
        </section>
        <section class="container">
            <div class="row payment-options-row justify-content-center">
                <div class="col-md-4 mb-4">
                    <div class="card payment-card text-center h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <img src="assets/logo.png" alt="BMI Pay Logo" class="payment-logo mb-2">
                            <h5 class="card-title">Card Payment</h5>
                            <p class="card-text">Pay securely with your debit or credit card.</p>
                            <a href="payments/paystack.php<?php
                                $params = [];
                                if ($user_email) $params[] = 'user_email=' . urlencode($user_email);
                                if ($user_name) $params[] = 'user_name=' . urlencode($user_name);
                                if ($amount) $params[] = 'amount=' . urlencode($amount);
                                // Add hash if all present
                                if ($user_email && $user_name && $amount) {
                                    $params[] = 'hash=' . bmi_pay_hash($user_email, $user_name, $amount);
                                }
                                echo $params ? '?' . implode('&', $params) : '';
                            ?>" class="btn btn-primary w-100 mt-auto">Pay with Card</a>
                            <div class="payment-logos mt-3">
                                <img src="assets/visa.png" alt="Visa" class="pay-logo-icon">
                                <img src="assets/mastercard.png" alt="Mastercard" class="pay-logo-icon">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card payment-card text-center h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <img src="assets/logo.png" alt="BMI Pay Logo" class="payment-logo mb-2">
                            <h5 class="card-title">Mobile Money</h5>
                            <p class="card-text">Pay with any mobile money service in Ghana.</p>
                            <a href="payments/paystack.php<?php
                                $params = ['method=mobilemoneyghana'];
                                if ($user_email) $params[] = 'user_email=' . urlencode($user_email);
                                if ($user_name) $params[] = 'user_name=' . urlencode($user_name);
                                if ($amount) $params[] = 'amount=' . urlencode($amount);
                                if ($user_email && $user_name && $amount) {
                                    $params[] = 'hash=' . bmi_pay_hash($user_email, $user_name, $amount);
                                }
                                echo '?' . implode('&', $params);
                            ?>" class="btn btn-success w-100 mt-auto">Pay with Mobile Money</a>
                            <div class="payment-logos mt-3">
                                <img src="assets/mtn.jpeg" alt="MTN" class="pay-logo-icon">
                                <img src="assets/telecel.png" alt="Telecel" class="pay-logo-icon">
                                <img src="assets/airteltigo.png" alt="AirtelTigo" class="pay-logo-icon">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card payment-card text-center h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <img src="assets/logo.png" alt="BMI Pay Logo" class="payment-logo mb-2">
                            <h5 class="card-title">International Payment</h5>
                            <p class="card-text">Pay from anywhere in the world (Card/PayPal/Zelle).</p>
                            <a href="payments/paypal.php<?php
                                $params = [];
                                if ($user_email) $params[] = 'user_email=' . urlencode($user_email);
                                if ($user_name) $params[] = 'user_name=' . urlencode($user_name);
                                if ($amount) $params[] = 'amount=' . urlencode($amount);
                                if ($user_email && $user_name && $amount) {
                                    $params[] = 'hash=' . bmi_pay_hash($user_email, $user_name, $amount);
                                }
                                echo $params ? '?' . implode('&', $params) : '';
                            ?>" class="btn btn-info w-100 mt-auto">Pay Internationally</a>
                            <div class="payment-logos mt-3">
                                <img src="assets/paypal.png" alt="PayPal" class="pay-logo-icon">
                                <img src="assets/zelle.png" alt="Zelle" class="pay-logo-icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer class="footer text-center py-4 mt-5">
            <div class="container">
                <span class="footer-text">&copy; <?php echo date('Y'); ?> BMI Pay. All rights reserved.</span>
            </div>
        </footer>
