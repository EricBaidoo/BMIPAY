<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

bmi_pay_require_admin();

$db = bmi_pay_db();
$stmt = $db->query('SELECT reference, amount, currency, status, channel, paid_at, customer_email, customer_name, purpose, created_at FROM payments ORDER BY COALESCE(paid_at, created_at) DESC LIMIT 200');
$payments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - BMI Pay</title>
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
                    <h1 class="paystack-title mb-1">Payments</h1>
                    <p class="paystack-subtitle mb-0">Latest 200 transactions</p>
                </div>
            </div>
            <a href="logout.php" class="paystack-back">Log Out</a>
        </header>

        <div class="paystack-form-card">
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Purpose</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Channel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($payments)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No payments recorded yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($payment['paid_at'] ?: $payment['created_at']); ?></td>
                                    <td><?php echo htmlspecialchars($payment['reference']); ?></td>
                                    <td><?php echo htmlspecialchars($payment['purpose'] ?: '-'); ?></td>
                                    <td>
                                        <div class="fw-semibold"><?php echo htmlspecialchars($payment['customer_name'] ?: ''); ?></div>
                                        <div class="text-muted small"><?php echo htmlspecialchars($payment['customer_email'] ?: ''); ?></div>
                                    </td>
                                    <td><?php echo htmlspecialchars($payment['currency']); ?> <?php echo number_format(((int)$payment['amount']) / 100, 2); ?></td>
                                    <td><?php echo htmlspecialchars($payment['status']); ?></td>
                                    <td><?php echo htmlspecialchars($payment['channel'] ?: '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
