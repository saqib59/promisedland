<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';
//require_once '../config/paypal_init.php';

$membership = 0;
$plans = plans();

if (isset($_GET['status']) && $_GET['status'] == "success") {

    // do paypal functions
    if (isset($_GET['subscription_id']) && !empty($_GET['subscription_id'])) {

        // get paypal row id
        $transaction_id = get_col_data($_GET['subscription_id'], 'subscription_id', 'id', 'paypal_logs');
        if ($transaction_id && !empty($transaction_id)) {

            // update transaction to complete
            $complete = $db->query("UPDATE paypal_logs SET complete = 1, `updated_at` = CURRENT_TIMESTAMP WHERE `id` = ?;", $transaction_id);

            // check if already have a row with this data
            $checkMemb = $db->query("SELECT * FROM `membership` WHERE `gateway` = 'paypal' AND `transaction_id` = ?;", $transaction_id);

            if ($checkMemb == false || $checkMemb->numRows() == 0) {
            //if (check_row($transaction_id, 'transaction_id', 'membership') == false) {
                // create new membership row
                $membership = newMembership($_SESSION['user'], $_SESSION['package'], $_SESSION['plan'], 'paypal', $transaction_id);
                if ($membership == false) {
                    redirect("Failed to create membership for you. Please contact us immediately", USER . "/payment/?status=error");
                }
            } else {
                redirect("Your membership is already assigned!", USER);
            }


            // update user membership row id
            $userMem = updateDatabyId($membership, 'membership_id', $_SESSION['user'], 'users');
            if ($userMem == false) {
                redirect("Failed to assign the membership for you. Please contact us immediately", USER . "/payment/?status=error");
            }

            unset($_SESSION['package']);
            unset($_SESSION['plan']);

            if ($membership == 0) {
                redirect("Failed to create membership for you. Please contact us immediately", USER . "/payment/?status=error");
            } else {
                // redirect to new complete link
                header("Location: " . USER . "/payment/?membership_id={$membership}&status=complete");
                exit();
            }
            
        }

    }
}
