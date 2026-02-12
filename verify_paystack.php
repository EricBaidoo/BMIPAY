<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';

$reference = isset($_GET['reference']) ? trim($_GET['reference']) : '';
if (!$reference) {
    http_response_code(400);
    echo json_encode(['status' => false, 'message' => 'Missing reference']);
    exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.paystack.co/transaction/verify/' . rawurlencode($reference));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . PAYSTACK_SECRET_KEY,
    'Content-Type: application/json',
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($response === false || $http_code >= 400) {
    http_response_code(502);
    echo json_encode(['status' => false, 'message' => 'Verification failed', 'error' => $error]);
    exit;
}

$body = json_decode($response, true);
if (!is_array($body) || empty($body['status']) || empty($body['data'])) {
    http_response_code(502);
    echo json_encode(['status' => false, 'message' => 'Invalid response from Paystack']);
    exit;
}

$txn = $body['data'];
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
    ':raw_event' => $response,
]);

http_response_code(200);
echo json_encode(['status' => true, 'reference' => $reference]);
