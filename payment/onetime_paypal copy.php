<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';
require_once '../config/paypal_init.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

dump($_GET);

if (isset($_GET['PayerID'])) {
    header("Location: " . LINK . "/course/payment/?subscription=1&status=complete");
}

if (!empty($_GET['item_number']) && !empty($_GET['tx']) && !empty($_GET['amt']) && !empty($_GET['cc']) && !empty($_GET['st'])) {

    // Get transaction information from URL 
    $course_id = $_GET['item_number'];
    $txn_id = $_GET['tx'];
    $paid_amount = $_GET['amt'];
    $price_currency = $_GET['cc'];
    $status = $_GET['st'];

    // Check if transaction is exist with txn id
    if (check_row($txn_id, 'transaction_id', 'paypal_logs_onetime') == false) {
        // insert paypal one time log
        $paypal_log = logPaypal_oneTime($user, $txn_id, $course_id, $price_currency, $paid_amount, $status);

        if ($paypal_log !== false) {

            // check payment success
            // if payment not success

            // check course subscribed already
            if (check_row($paypal_log, 'txn_id', 'course_subscribe') == false) {

                // create course subscription
                $course_subscribe = courseSubscribe($course_id, $user, $paypal_log, 'paypal');
                if ($course_subscribe == false) {
                    //redirect("Failed to subscribe to the course. Please contact us immediately", LINK . "/course/payment/?status=error");
                } else {
                    // redirect to new complete link
                    //header("Location: " . LINK . "/course/payment/?subscription={$course_subscribe}&status=complete");
                    //exit();
                }

            }

        } else {
            echo '1';
        }
    } else {
        echo '2';
    }

}
