<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../inc/account/logged.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // arrange all posted fields
    $p = $_POST;
    if (!empty($p["package"]) && !empty($p["plan"])) {

        $couponUsage = false;
        $coupon_row = 0;
        $coupon_usage_id = 0;

        $package = $p["package"];
        $plan_id = $p["plan"];

        // save user details
        if (check_row($user, 'user_id', 'user_details') == false) {
            $details = newDetails($user, $p['address'], $p['state'], $p['city'], $p['zip']);
        }

        if (isset($p['coupon_code']) && !empty($p['coupon_code'])) {

            // retrieve coupon id
            $coupon_row = get_col_data($p['coupon_code'], 'code', 'id', 'coupon');

            // check user have a unused coupon session
            $coupon_usage = $db->query("SELECT * FROM `coupon_usage` WHERE `coupon_id` = ? AND `user` = ? AND `used` = '0' ORDER BY id DESC LIMIT 0, 1;", $coupon_row, $user)->fetchArray();
            if ($coupon_usage && !empty($coupon_usage)) {
                $couponUsage = true;
                // return coupon usage id
                $coupon_usage_id = $coupon_usage['id'];
            }

            // fetch the coupon data
            $couponFetch = $db->query("SELECT * FROM `coupon` WHERE `id` = ? AND `status` = '1';", $coupon_row)->fetchArray();
            if ($couponFetch && !empty($couponFetch)) {
                // retrieve the discount amount
                $discount_percentage = $couponFetch['discount'];
            }

            // reduce the price to pay
            if ($couponUsage == true && $discount_percentage !== 0 && $discount_percentage !== '0' && $discount_percentage == '100') {
                $_SESSION['package'] = $p['package'];
                $_SESSION['plan'] = $p['plan'];
                $_SESSION['coupon_id'] = $coupon_row;
                $_SESSION['coupon_usage_id'] = $coupon_usage_id;

                echo LINK . "/payment/membership_free.php?status=success";
            } else {
                echo USER . "/payment/?status=error";
            }
        }
    }
}
