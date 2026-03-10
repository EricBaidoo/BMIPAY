<!DOCTYPE html>
<?php
require_once __DIR__ . '/config.php';

$reference = isset($_GET['reference']) ? htmlspecialchars(trim($_GET['reference'])) : '';
$return_url_raw = isset($_GET['return_url']) ? trim($_GET['return_url']) : '';
$safe_return_url = '';

if ($return_url_raw !== '') {
    $parsed = parse_url($return_url_raw);
    $host = isset($parsed['host']) ? strtolower($parsed['host']) : '';
    $scheme = isset($parsed['scheme']) ? strtolower($parsed['scheme']) : '';
    // Only allow https redirects to explicitly allowlisted domains
    if ($scheme === 'https' && in_array($host, ALLOWED_RETURN_DOMAINS, true)) {
        // Append the Paystack reference so the store can confirm the payment
        $separator = (strpos($return_url_raw, '?') !== false) ? '&' : '?';
        $safe_return_url = $return_url_raw . $separator . 'reference=' . urlencode($reference);
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - BMI Pay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css?v=20260310">
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
            
            <?php if ($reference): ?>
            <div class="thanks-reference">
                <span class="thanks-reference-label">Transaction Reference</span>
                <span class="thanks-reference-value"><?php echo $reference; ?></span>
            </div>
            <?php endif; ?>

            <?php if ($safe_return_url): ?>
            <p class="text-muted mt-3 mb-2" id="redirect-msg">Returning you to the store in <span id="countdown">5</span> seconds&hellip;</p>
            <a href="<?php echo htmlspecialchars($safe_return_url); ?>" class="btn btn-primary thanks-btn">Return to Store</a>
            <?php else: ?>
            <a href="index.php" class="btn btn-primary thanks-btn">Back to Home</a>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
<?php if ($safe_return_url): ?>
<script>
(function() {
    var target = <?php echo json_encode($safe_return_url); ?>;
    var secs = 5;
    var el = document.getElementById('countdown');
    var interval = setInterval(function() {
        secs--;
        if (el) el.textContent = secs;
        if (secs <= 0) {
            clearInterval(interval);
            window.location.href = target;
        }
    }, 1000);
})();
</script>
<?php endif; ?>
