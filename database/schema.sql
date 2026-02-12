CREATE DATABASE IF NOT EXISTS bmipay;
USE bmipay;

CREATE TABLE IF NOT EXISTS payments (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    reference VARCHAR(64) NOT NULL,
    amount INT UNSIGNED NOT NULL,
    currency VARCHAR(8) NOT NULL,
    status VARCHAR(24) NOT NULL,
    channel VARCHAR(32) NULL,
    paid_at DATETIME NULL,
    customer_email VARCHAR(190) NULL,
    customer_name VARCHAR(190) NULL,
    raw_event JSON NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_reference (reference)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
