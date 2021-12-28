<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';
require_once '../config/paypal_init.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* 
    * Read POST data 
    * reading posted data directly from $_POST causes serialization 
    * issues with array data in POST. 
    * Reading raw POST data from input stream instead. 
    */

    $raw_post_data = file_get_contents('php://input');
    $raw_post_array = explode('&', $raw_post_data);
    $myPost = array();
    foreach ($raw_post_array as $keyval) {
        $keyval = explode('=', $keyval);
        if (count($keyval) == 2)
            $myPost[$keyval[0]] = urldecode($keyval[1]);
    }

    // Read the post from PayPal system and add 'cmd' 
    $req = 'cmd=_notify-validate';

    /* if (function_exists('get_magic_quotes_gpc')) {
        $get_magic_quotes_exists = true;
    } */

    foreach ($myPost as $key => $value) {
        //if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
        //    $value = urlencode(stripslashes($value));
        //} else {
        $value = urlencode($value);
        //}
        $req .= "&$key=$value";
    }

    /* 
    * Post IPN data back to PayPal to validate the IPN data is genuine 
    * Without this step anyone can fake IPN data 
    */

    $paypalURL = PAYPAL_URL;
    $ch = curl_init($paypalURL);
    if ($ch == FALSE) {
        return FALSE;
    }
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

    // Set TCP timeout to 30 seconds 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: PromisedLand'));
    $res = curl_exec($ch);

    /* 
    * Inspect IPN validation result and act accordingly 
    * Split response headers and payload, a better way for strcmp 
    */

    $tokens = explode("\r\n\r\n", trim($res));
    $res = trim(end($tokens));
    if (strcmp($res, "VERIFIED") == 0 || strcasecmp($res, "VERIFIED") == 0) {

        // Retrieve transaction info from PayPal 
        $course_id    = $_POST['item_number'];
        $txn_id         = $_POST['txn_id'];
        $paid_amount     = $_POST['mc_gross'];
        $price_currency     = $_POST['mc_currency'];
        $status = $_POST['payment_status'];

        // Check if transaction data exists with the same TXN ID 
        if (check_row($txn_id, 'transaction_id', 'paypal_logs_onetime') == false) {

            // Insert transaction data into the database 
            $paypal_log = logPaypal_oneTime($user, $txn_id, $course_id, $price_currency, $paid_amount, $status);

            if ($paypal_log !== false) {

                // check course subscribed already with transaction
                if (check_row($paypal_log, 'txn_id', 'course_subscribe') == false) {

                    // check course already have
                    if (checkCourse($user, $course) == false) {

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
                }
            }
        } else {
            exit();
        }
    }
}
