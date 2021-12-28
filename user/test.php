<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../config/config.php';
confirm_email('rulemax567@gmail.com');
?>