<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('MAIN', dirname(__DIR__, 2));

require_once MAIN . '/config/config.php';

if (admin() == false) {
    redirect("Please login first!", ADMIN . '/login.php');
    exit();
}