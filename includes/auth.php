<?php
require_once __DIR__ . '/../config.php';

function bmi_pay_start_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function bmi_pay_is_admin() {
    bmi_pay_start_session();
    return !empty($_SESSION['bmi_admin']);
}

function bmi_pay_require_admin() {
    if (!bmi_pay_is_admin()) {
        header('Location: login.php');
        exit;
    }
}
