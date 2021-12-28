<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    $question = $p['question'];

    if (empty($question)) {
        echo '0';
    } else {
        if (user()) {
            $feedback = $db->query("INSERT INTO `webinar`(`id`, `user`, `question`) VALUES (NULL, ?, ?);", $user, $question);
            if ($feedback) {
                echo 'success';
                exit();
            }
        } else {
            echo 'logged';
            exit();
        }
    }
} else {
    echo '0';
}
