<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    $title = $p['title'];
    $question = $p['question'];
    $course_id = $p['course_id'];

    if (empty($user) || empty($title) || empty($question) || empty($course_id)) {
        echo '1';
    } else {
        $thread = $db->query("INSERT INTO `course_faq`(`id`, `course_id`, `title`, `question`, `user`) VALUES (NULL, ?, ?, ?, ?);", $course_id, $title, $question, $user);
        if ($thread) {
            echo 'success';
        } else {
            echo '2';
        }
    }
} else {
    echo '3';
}
