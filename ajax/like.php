<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    $faq_id = $p['faq_id'];
    $like_status = $p['like_status'];

    if (empty($user) || empty($faq_id) || empty($like_status)) {
        echo '0';
    } else {

        $like = false;
        
        if (checkLike($user, $faq_id) == false) {
            if ($like_status == 'like') {
                $like = $db->query("INSERT INTO `course_faq_likes` (`id`, `faq_id`, `user_id`) VALUES (NULL, ?, ?);", $faq_id, $user);
            }
        } else {
            if ($like_status == 'unlike') {
                $like = $db->query("DELETE FROM `course_faq_likes` WHERE `user_id` = ? AND `faq_id` = ?;", $user, $faq_id);
            }
        }

        if ($like) {
            echo 'success';
        } else {
            echo '0';
        }
    }
} else {
    echo '0';
}
