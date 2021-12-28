<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../inc/account/logged.php';

$status = '0';
$discount = '0';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    $package = $p['package'];
    $plan_id = $p['plan_id'];
    $coupon_code = $p['coupon'];

    // check coupon is valid
    $coupon_select = $db->query("SELECT * FROM `coupon` WHERE `code` = ? AND `status` = 1;", $coupon_code);
    if ($coupon_select->numRows() > 0) {
        // coupon is valid
        $coupon_details = $coupon_select->fetchArray();

        if ($coupon_details && !empty($coupon_details)) {
            // retrieve user limit & discount
            $coupon_id = $coupon_details['id'];
            $coupon_discount = $coupon_details['discount'];
            $coupon_package = $coupon_details['package'];
            $coupon_plan = $coupon_details['plan'];
            $coupon_limit = $coupon_details['user_limit'];

            // retrieve coupon used times
            $coupon_used_times = 0;
            $coupon_usage = $db->query("SELECT * FROM `coupon_usage` WHERE `coupon_id` = ? AND `user` = ?;", $coupon_id, $user);
            $coupon_used_times = $coupon_usage->numRows();

            // check user have reached his limit
            if ((int)$coupon_limit <= (int)$coupon_used_times) {
                $status = 'used';
            } elseif (($coupon_package !== 'any') && ((int)$package !== (int)$coupon_package)) {
                $status = 'unmatch_package';
            } elseif (
                ($coupon_plan !== '0' && $coupon_plan !== 0) && 
                ((int)$plan_id !== (int)$coupon_plan)
            ) {
                $status = 'unmatch_plan';
            } else {

                // insert to coupon usage
                $usage_insert = coupon_usage($coupon_id, $user);
                if ($usage_insert !== false) {
                    // coupon can be used
                    $status = 'success';
                    $discount = $coupon_discount;
                }
            }
        } else {
            $status = 'invalid';
        }
    } else {
        $status = 'invalid';
    }
}

$result = array(
    'status' => $status,
    'discount' => $discount,
);
echo json_encode($result, true);
