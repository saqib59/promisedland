<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';
require_once '../config/stripe_init.php';

$membership = 0;
$coupon_usage_id = 0;
$plans = plans();

if (isset($_GET['status']) && $_GET['status'] == "success") {

    // do stripe functions
    //if (isset($_GET['stripe_token']) && !empty($_GET['stripe_token'])) {
    if (isset($_SESSION['stripe_token']) && !empty($_SESSION['stripe_token'])) {

        $session_package = $_SESSION['package'];
        $session_plan = $_SESSION['plan'];
        $session_stripe_token = $_SESSION['stripe_token'];

        $coupon_usage_id = $_SESSION['coupon_usage_id'];

        unset($_SESSION['package']);
        unset($_SESSION['plan']);
        unset($_SESSION['stripe_token']);
        unset($_SESSION['coupon_usage_id']);

        // check for stripe token
        $transaction = $db->query("SELECT * FROM `stripe_logs` WHERE `stripe_subscription_id` = ?;", $session_stripe_token)->fetchArray();
        $transaction_id = $transaction['id'];

        // check if already have a row with this data
        $checkMemb = $db->query("SELECT * FROM `membership` WHERE `gateway` = 'stripe' AND `transaction_id` = ?;", $transaction_id);

        if ($checkMemb == false || $checkMemb->numRows() == 0) {
            //if (check_row($transaction_id, 'transaction_id', 'membership') == false) {

            // create new membership row
            $membership = newMembership($user, $session_package, $session_plan, 'stripe', $transaction_id);
            if ($membership == false) {
                user_redirect("Failed to create membership for you. Please contact us immediately", "error", USER . "/payment/?status=error");
            }
        } else {
            user_redirect("Your membership is already assigned!", "info", USER);
        }

        if ($membership !== 0) {
            // update user membership row id
            $userMem = updateDatabyId($membership, 'membership_id', $user, 'users');
            if ($userMem == false) {
                user_redirect("Failed to assign membership for you. Please contact us immediately", "error", USER . "/payment/?status=error");
            } else {
                
                // update coupon status
                if ($coupon_usage_id !== 0) {
                    $update_status = updateDatabyId('1', 'used', $coupon_usage_id, 'coupon_usage');
                }

                // redirect to new complete link
                header("Location: " . USER . "/payment/?membership_id={$membership}&status=complete");
                exit();
            }
        } else {
            user_redirect("Ewas lief falsch! Bitte erneut probieren", "error", USER);
        }

    }
}
