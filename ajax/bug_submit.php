<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    $info = $p['info'];
    $listing_id = $p['listing_id'];

    if (empty($info)) {
        echo '0';
    } else {
        $feedback = $db->query("INSERT INTO `bug_submit`(`id`, `user`, `listing_id`, `info`) VALUES (NULL, ?, ?, ?);", $user, $listing_id, $info);
        if ($feedback) {
            echo 'success';
            exit();
        }
    }
} else {
    echo '0';
}
