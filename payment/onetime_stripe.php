<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';
require_once '../config/stripe_init.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;
$plans = plans();

if (isset($_GET['status']) && $_GET['status'] == "success") {

    // do stripe functions
    if (isset($_GET['stripe_token']) && !empty($_GET['stripe_token'])) {

        $transaction = $db->query("SELECT * FROM `stripe_logs_onetime` WHERE `transaction_id` = ?;", $_GET['stripe_token'])->fetchArray();

        if ($transaction) {

            $txn_id = $transaction['id'];
            $course_id = $transaction['course_id'];

            if (check_row($txn_id, 'txn_id', 'course_subscribe') == false) {

                // check course already have
                if (checkCourse($user, $course) == false) {

                    // create course subscription
                    $course_subscribe = courseSubscribe($course_id, $user, $txn_id, 'stripe');
                    if ($course_subscribe == false) {
                        redirect("Failed to subscribe to the course. Please contact us immediately", LINK . "/course/payment/?status=error");
                    } else {
                        // redirect to new complete link
                        header("Location: " . LINK . "/course/payment/?subscription={$course_subscribe}&status=complete");
                        exit();
                    }
                }
            } else {
                echo '1';
                //redirect("Course if already subscribed!", USER . '/courses/');
            }
        } else {
            echo '2';
            // redirect("Invalid Transaction ID!" . $trans_query->numRows(), LINK . '/courses/');
        }
    }
}
exit();
