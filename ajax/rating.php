<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    $row_id = $p['row_id'];
    $row_type = $p['row_type'];
    $rating = $p['rating'];
    $feedback = $p['feedback'];

    if($row_type == 'seminar') {
        $table = 'seminar_feedback';
        $column = 'seminar_id';
    } else {
        $table = 'consulting_feedback';
        $column = 'consultant_id';
    }

    if ( empty($user) || empty($row_id) || empty($rating) || empty($feedback) || empty($row_type) ) {
        echo '0';
    } else {
        $rate = $db->query("INSERT INTO `{$table}` (`id`, `{$column}`, `user_id`, `rating`, `feedback`) VALUES (NULL, ?, ?, ?, ?);", $row_id, $user, $rating, $feedback);
        if ($rate) {
            echo 'success';
            exit();
        } else {
            echo '0';
        }
    }
} else {
    echo '0';
}
