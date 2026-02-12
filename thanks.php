<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - BMI Pay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-light paystack-body">
<div class="paystack-page">
    <div class="paystack-shell">
        <div class="thanks-container">
            <div class="thanks-icon">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="40" fill="#0f8a7f" opacity="0.15"/>
                    <circle cx="40" cy="40" r="32" fill="#0f8a7f"/>
                    <path d="M25 40L35 50L55 30" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="thanks-title">Payment Successful!</h1>
            <p class="thanks-subtitle">Thank you for your payment. Your transaction has been completed successfully.</p>
            
            <?php if (isset($_GET['reference'])): ?>
            <div class="thanks-reference">
                <span class="thanks-reference-label">Transaction Reference</span>
                <span class="thanks-reference-value"><?php echo htmlspecialchars($_GET['reference']); ?></span>
            </div>
            <?php endif; ?>

            <a href="index.php" class="btn btn-primary thanks-btn">Back to Home</a>
        </div>
    </div>
</div>
</body>
</html>
