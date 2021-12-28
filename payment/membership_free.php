<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../inc/account/logged.php';

if (isset($_GET['status']) && $_GET['status'] == "success") {

    if (isset($_SESSION['coupon_usage_id']) && !empty($_SESSION['coupon_usage_id'])) {

        $coupon_id = $_SESSION['coupon_id'];
        $coupon_usage_id = $_SESSION['coupon_usage_id'];

        // check membership
        if (check_row($coupon_id, 'coupon_id', 'membership') == false) {
            // create new membership row
            $membership = newMembership($user, $_SESSION['package'], $_SESSION['plan'], 'free', '0', $coupon_id);
            if ($membership == false) {
                redirect("Failed to create membership for you. Please contact us immediately", USER . "/payment/?status=error");
            } else {
                // update coupon status
                $update_status = updateDatabyId('1', 'used', $coupon_usage_id, 'coupon_usage');

                // unset sessions
                unset($_SESSION['coupon_id']);
                unset($_SESSION['coupon_usage_id']);
                unset($_SESSION['package']);
                unset($_SESSION['plan']);

                // redirect to new complete link
                header("Location: " . USER . "/payment/?membership_id={$membership}&status=complete");
                exit();
            }
        } else {
            redirect("Your membership is already assigned!", USER);
        }
    }
} else {
    // redirect to new complete link
    header("Location: " . USER . "/payment/?status=error");
    exit();
}
