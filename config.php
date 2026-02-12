<?php
// config.php
// Store your secret key here. Keep this file outside web root in production.
define('BMI_PAY_SECRET', 'mevaGieB1sMI/lThG2Ztwl1vM/M5HnPDjdX/UWHsHZd9SxFBjyd5UaqokXN71xrV');

// Paystack keys - LIVE MODE
define('PAYSTACK_PUBLIC_KEY', 'REDACTED');
define('PAYSTACK_SECRET_KEY', 'REDACTED');

// Database settings
$is_local = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1'], true) || PHP_SAPI === 'cli';
$db_local = [
	'host' => 'localhost',
	'name' => 'bmipay',
	'user' => 'root',
	'pass' => 'root',
];
$db_live = [
	'host' => 'localhost',
	'name' => 'u145148023_bmipay',
	'user' => 'u145148023_bmi_admin2',
	'pass' => 'REDACTED',
];
$db = $is_local ? $db_local : $db_live;
define('DB_HOST', $db['host']);
define('DB_NAME', $db['name']);
define('DB_USER', $db['user']);
define('DB_PASS', $db['pass']);

// Admin login for payments page
define('ADMIN_USER', 'admin');
define('ADMIN_PASSWORD_HASH', '$2y$10$bXckNOIudSu1pu3f.8WuWuSM/RkHh604mE8V3yCKp/GH1HJ7PJ8Ii');
