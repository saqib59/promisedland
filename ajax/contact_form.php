<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    $name = $p['name'];
    $email = $p['email'];
    $reason = $p['reason'];
    $message = $p['message'];

    if (empty($name) || empty($email) || empty($reason) || empty($message)) {
        echo 'empty';
    } else {
        $contact = $db->query("INSERT INTO `contact`(`id`, `name`, `email`, `reason`, `msg`) VALUES (NULL, ?, ?, ?, ?);", $name, $email, $reason, $message);
        if ($contact) {
            echo 'success';
        } else {
            echo 'failed';
        }
    }
} else {
    echo 'error';
}
