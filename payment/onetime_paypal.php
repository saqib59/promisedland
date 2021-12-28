<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

dump($_GET);
dump($_POST);

$error = 0;

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $transaction_token = $_GET['token'];

    // check transaction token available
    $transaction = $db->query("SELECT * FROM `paypal_logs_onetime` WHERE `transaction_id` = ?;", $transaction_token);

    if ($transaction->numRows() == 0) {
        $error = 1;
    } else {
        // get transaction id
        $trans_data = $transaction->fetchArray();
        $transaction_id = $trans_data['id'];

        // check payment success

        // update transaction status
        $update_transaction = updateDatabyId('1', 'status', $transaction_id, 'paypal_logs_onetime');

        // check course subscribed already
        $checkSub = $db->query("SELECT * FROM `course_subscribe` WHERE `gateway` = 'paypal' AND `txn_id` = ?;", $transaction_id);

        if ($checkSub->numRows() == 0) {

            // get course id
            $course_id = $trans_data['course_id'];

            // create course subscription
            $course_subscribe = courseSubscribe($course_id, $user, $transaction_id, 'paypal');
            if ($course_subscribe == false) {
                $error = 1;
            } else {
                // redirect to new complete link
                header("Location: " . LINK . "/course/payment/?subscription={$course_subscribe}&status=complete");
                exit();
            }
        }
    }
} else {
    $error = 1;
}

if ($error == 1) {
    header("Location: " . LINK . '/course/payment/?status=error');
}

/* if (!empty($_GET['item_number']) && !empty($_GET['tx']) && !empty($_GET['amt']) && !empty($_GET['cc']) && !empty($_GET['st'])) {

    // Get transaction information from URL 
    $course_id = $_GET['item_number'];
    $txn_id = $_GET['tx'];
    $paid_amount = $_GET['amt'];
    $price_currency = $_GET['cc'];
    $status = $_GET['st'];

    // Check if transaction is exist with txn id
    if (check_row($txn_id, 'transaction_id', 'paypal_logs_onetime') == false) {

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
 */