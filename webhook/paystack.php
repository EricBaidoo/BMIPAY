<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';

$payload = file_get_contents('php://input');
$signature = isset($_SERVER['HTTP_X_PAYSTACK_SIGNATURE']) ? $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] : '';
$computed = hash_hmac('sha512', $payload, PAYSTACK_SECRET_KEY);

if (!$signature || !hash_equals($computed, $signature)) {
    http_response_code(400);
    echo 'Invalid signature';
    exit;
}

$data = json_decode($payload, true);
if (!is_array($data) || !isset($data['event'])) {
    http_response_code(400);
    echo 'Invalid payload';
    exit;
}

if ($data['event'] !== 'charge.success' || !isset($data['data'])) {
    http_response_code(200);
    echo 'Ignored';
    exit;
}

$txn = $data['data'];
$reference = isset($txn['reference']) ? $txn['reference'] : null;
if (!$reference) {
    http_response_code(400);
    echo 'Missing reference';
    exit;
}

$amount = isset($txn['amount']) ? (int)$txn['amount'] : 0;
$currency = isset($txn['currency']) ? $txn['currency'] : 'GHS';
$status = isset($txn['status']) ? $txn['status'] : 'success';
$channel = isset($txn['channel']) ? $txn['channel'] : null;
$paid_at = isset($txn['paid_at']) ? $txn['paid_at'] : null;
$customer_email = isset($txn['customer']['email']) ? $txn['customer']['email'] : null;
$customer_name = isset($txn['customer']['name']) ? $txn['customer']['name'] : null;
$purpose = null;
if (isset($txn['metadata']) && is_array($txn['metadata'])) {
    if (!empty($txn['metadata']['purpose'])) {
        $purpose = $txn['metadata']['purpose'];
    } elseif (!empty($txn['metadata']['custom_fields']) && is_array($txn['metadata']['custom_fields'])) {
        foreach ($txn['metadata']['custom_fields'] as $field) {
            if (!empty($field['variable_name']) && $field['variable_name'] === 'purpose') {
                $purpose = isset($field['value']) ? $field['value'] : null;
                break;
            }
        }
    }
}

$db = bmi_pay_db();
$stmt = $db->prepare('INSERT INTO payments (reference, amount, currency, status, channel, paid_at, customer_email, customer_name, purpose, raw_event)
    VALUES (:reference, :amount, :currency, :status, :channel, :paid_at, :customer_email, :customer_name, :purpose, :raw_event)
    ON DUPLICATE KEY UPDATE status = VALUES(status), channel = VALUES(channel), paid_at = VALUES(paid_at), purpose = VALUES(purpose), raw_event = VALUES(raw_event)');
$stmt->execute([
    ':reference' => $reference,
    ':amount' => $amount,
    ':currency' => $currency,
    ':status' => $status,
    ':channel' => $channel,
    ':paid_at' => $paid_at,
    ':customer_email' => $customer_email,
    ':customer_name' => $customer_name,
    ':purpose' => $purpose,
    ':raw_event' => $payload,
]);

http_response_code(200);
echo 'OK';
