<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (defined('HOME')) {
    require_once HOME . '/config/config.php';
} else {
    require_once '../config/config.php';
}

if (user() == false) {
    user_redirect("Logge dich zuerst ein!", "warning", USER . '/login/');
    exit();
}

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;
