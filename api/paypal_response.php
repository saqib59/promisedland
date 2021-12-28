<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = 0;

$email = '';
$payment_id = 0;
$plan_paypal_id = 0;

$paypalPlans = paypalPlans();

$response = @file_get_contents('php://input');
if (!empty($response)) {

    $results = json_decode($response, true);


    if (isset($results['event_type'])) {

        $event_type = $results['event_type'];

        // subscription activated
        if ($event_type == 'BILLING.SUBSCRIPTION.ACTIVATED') {

            if (!isset($results['resource']) || !isset($results['id']) || !isset($results['plan_id'])) {
                echo 'Missing resources';
                exit();
            }

            $payment_id = $results['resource']['id'];
            $plan_paypal_id = $results['resource']['plan_id'];

            // check paypal payment id
            if (check_row($payment_id, 'subscription_id', 'paypal_logs')) {

                if (!isset($paypalPlans[$plan_paypal_id])) {
                    echo 'Invalid plan id';
                    exit();
                }

                $package = $paypalPlans[$plan_paypal_id]['type'];
                $plan = $paypalPlans[$plan_paypal_id]['months'];

                if (!isset($results['resource']['subscriber']['email_address'])) {
                    echo 'User not found';
                    exit();
                }

                $user_email = $results['resource']['subscriber']['email_address'];
                $user = get_col_data($user_email, 'email', 'id', 'users');

                // check for user
                if ($user && $user !== '' || $user !== 0) {

                    // get paypal row id
                    $transaction_id = get_col_data($payment_id, 'subscription_id', 'id', 'paypal_logs');

                    // update transaction to complete
                    $complete = $db->query("UPDATE paypal_logs SET complete = 1, `updated_at` = CURRENT_TIMESTAMP WHERE `id` = ?;", $transaction_id);

                    // check if already have a row with this data
                    if (check_row($transaction_id, 'transaction_id', 'membership') == false) {
                        // create new membership row
                        $membership = newMembership($user, $package, $plan, 'paypal', $transaction_id);
                        if ($membership == false) {
                            echo 'membership failed to create';
                            exit();
                            //redirect("Failed to create membership for you. Please contact us immediately", USER . "/payment/?status=error");
                        }
                    } else {
                        echo 'membership is already assigned';
                        exit();
                        //redirect("Your membership is already assigned!", USER);
                    }


                    // update user membership row id
                    $userMem = updateDatabyId($membership, 'membership_id', $user, 'users');
                    if ($userMem == false) {
                        echo 'membership failed to assign';
                        exit();
                        //redirect("Failed to assign the membership for you. Please contact us immediately", USER . "/payment/?status=error");
                    }

                    ///////////////////////////////////////////////////
                    ///////////////////////////////////////////////////
                    ///////////////////////////////////////////////////
                    ///////////////////////////////////////////////////
                    // Need to send an email membership active
                    ///////////////////////////////////////////////////
                    ///////////////////////////////////////////////////
                    ///////////////////////////////////////////////////
                    ///////////////////////////////////////////////////

                }
            }
        }


        // subscription cancelled
        if (
            $event_type == 'BILLING.SUBSCRIPTION.DEACTIVATED' ||
            $event_type == 'BILLING.SUBSCRIPTION.CANCELLED' ||
            $event_type == 'BILLING.SUBSCRIPTION.SUSPENDED' ||
            $event_type == 'BILLING.SUBSCRIPTION.PAYMENT.FAILED'
        ) {

            if (!isset($results['resource']) || !isset($results['id'])) {
                echo 'Missing resources';
                exit();
            }

            // check if subscription cancelled
            //if ($results['state'] == 'Cancelled' || $results['state'] == 'Suspended') {
            if (1 == 1) {

                // get paypal row id
                $transaction_id = get_col_data($payment_id, 'subscription_id', 'id', 'paypal_logs');

                switch ($event_type) {
                    case 'BILLING.SUBSCRIPTION.DEACTIVATED':
                        $status = 'Subscription Deactivated';
                    case 'BILLING.SUBSCRIPTION.CANCELLED':
                        $status = 'Subscription Cancelled';
                    case 'BILLING.SUBSCRIPTION.SUSPENDED':
                        $status = 'Subscription Suspended';
                    case 'BILLING.SUBSCRIPTION.PAYMENT.FAILED':
                        $status = 'Payment Failed';
                    default:
                        $status = 'Payment Failed';
                }

                if (check_row($transaction_id, 'log_id', 'membership_cancel') == false) {
                    $fail_log = subsFailed($transaction_id, 'paypal', $status);
                    if ($fail_log == false) {
                        echo 'failed to log the status';
                        exit();
                    }
                }


                /* 
                
                // update cancel status on paypal
                $upCancel = updateDatabyId('2', 'complete', $transaction_id, 'paypal_logs');
                if ($upCancel == false) {
                    echo 'paypal log failed to cancel';
                    exit();
                }

                // get membership id
                $memb_id = get_col_data($transaction_id, 'transaction_id', 'id', 'membership');
                // update cancel status on membership
                $memCancel = updateDatabyId('cancelled', 'status', $memb_id, 'membership');
                if ($memCancel == false) {
                    echo 'membership failed to cancel';
                    exit();
                }
                // update end_dt to today
                $memEnd = updateDatabyId(today(), 'end_dt', $memb_id, 'membership'); 
                
                */
            }
        }
    }
}

http_response_code(200);
