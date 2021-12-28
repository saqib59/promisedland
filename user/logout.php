<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

session_destroy();

user_redirect("Logged Out successfully!", "info", USER . "/login/");

exit;