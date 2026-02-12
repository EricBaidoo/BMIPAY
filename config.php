<?php
// config.php
// Secrets should be provided via environment variables or config.local.php.

function env_value(string $key, string $default = ''): string
{
	$value = getenv($key);
	if ($value === false) {
		$value = $_ENV[$key] ?? $_SERVER[$key] ?? '';
	}

	return $value !== '' ? (string) $value : $default;
}

$local_config_path = __DIR__ . DIRECTORY_SEPARATOR . 'config.local.php';
$local_overrides = [];
if (is_file($local_config_path)) {
	$local_overrides = include $local_config_path;
	if (!is_array($local_overrides)) {
		$local_overrides = [];
	}
}

$secrets = [
	'BMI_PAY_SECRET' => env_value('BMI_PAY_SECRET'),
	'PAYSTACK_PUBLIC_KEY' => env_value('PAYSTACK_PUBLIC_KEY'),
	'PAYSTACK_SECRET_KEY' => env_value('PAYSTACK_SECRET_KEY'),
];

foreach ($secrets as $key => $value) {
	if (array_key_exists($key, $local_overrides)) {
		$secrets[$key] = (string) $local_overrides[$key];
	}
}

define('BMI_PAY_SECRET', $secrets['BMI_PAY_SECRET']);
define('PAYSTACK_PUBLIC_KEY', $secrets['PAYSTACK_PUBLIC_KEY']);
define('PAYSTACK_SECRET_KEY', $secrets['PAYSTACK_SECRET_KEY']);

// Database settings
$is_local = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1'], true) || PHP_SAPI === 'cli';
$db_local = [
	'host' => env_value('DB_LOCAL_HOST', 'localhost'),
	'name' => env_value('DB_LOCAL_NAME', 'bmipay'),
	'user' => env_value('DB_LOCAL_USER', 'root'),
	'pass' => env_value('DB_LOCAL_PASS', 'root'),
];
$db_live = [
	'host' => env_value('DB_LIVE_HOST', 'localhost'),
	'name' => env_value('DB_LIVE_NAME', ''),
	'user' => env_value('DB_LIVE_USER', ''),
	'pass' => env_value('DB_LIVE_PASS', ''),
];

if (isset($local_overrides['db_local']) && is_array($local_overrides['db_local'])) {
	$db_local = array_merge($db_local, $local_overrides['db_local']);
}

if (isset($local_overrides['db_live']) && is_array($local_overrides['db_live'])) {
	$db_live = array_merge($db_live, $local_overrides['db_live']);
}

$db = $is_local ? $db_local : $db_live;
define('DB_HOST', $db['host']);
define('DB_NAME', $db['name']);
define('DB_USER', $db['user']);
define('DB_PASS', $db['pass']);

// Admin login for payments page
define('ADMIN_USER', 'admin');
define('ADMIN_PASSWORD_HASH', '$2y$10$bXckNOIudSu1pu3f.8WuWuSM/RkHh604mE8V3yCKp/GH1HJ7PJ8Ii');
