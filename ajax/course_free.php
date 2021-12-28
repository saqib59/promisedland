<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    // save user details
    if (check_row($user, 'user_id', 'user_details') == false) {
        newDetails($user, $p["address"], $p["state"], $p["city"], $p["zip"]);
    }

    if (checkCourse($user, $p['course_id']) == false) {
        // create course subscription
        $course_subscribe = courseSubscribe($p['course_id'], $user, '0', 'free');
        if ($course_subscribe == false) {
            echo 'failed';
            exit();
        } else {
            updateDatabyId('approved', 'status', $course_subscribe, 'course_subscribe');
            // redirect to new complete link
            echo LINK . "/course/payment/?subscription={$course_subscribe}&status=complete";
            exit();
        }
    } else {
        echo 'already';
        exit();
    }
} else {
    echo '0';
    exit();
}
