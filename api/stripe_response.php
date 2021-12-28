<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once '../config/stripe_init.php';

// Include Stripe PHP library 
require_once '../library/stripe/init.php';

\Stripe\Stripe::setApiKey(STRIPE_API_KEY);

$payload = @file_get_contents('php://input');
$event = null;

try {
    $event = \Stripe\Event::constructFrom(
        json_decode($payload, true)
    );
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    echo 'Webhook error while parsing basic request.';
    http_response_code(400);
    exit();
}

if (isset($event->type) && !empty($event->type)) {
    $event_type = $event->type;

    if ($event_type == 'charge.succeeded') {

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

    if ($event_type == 'charge.failed' || $event_type == 'invoice.payment_failed' || $event_type == 'payment_intent.payment_failed') {

        // get stripe payment id
        $customer_id = $event->data->object->charges->data[0]->customer;

        //file_put_contents('test.txt', $customer_id);

        if (!$customer_id || empty($customer_id)) {
            echo 'customer id not found';
            exit();
        }
        // get stripe row id
        $transaction_id = get_col_data($customer_id, 'stripe_customer_id', 'id', 'stripe_logs');

        switch ($event_type) {
            case 'charge.failed':
                $status = 'Failed to Charge';
            case 'invoice.payment_failed':
                $status = 'Payment Failed';
            case 'payment_intent.payment_failed':
                $status = 'Payment Method Failed';
            default:
                $status = 'Payment Failed';
        }

        if (check_row($transaction_id, 'log_id', 'membership_cancel') == false) {
            $fail_log = subsFailed($transaction_id, 'stripe', $status);
            if ($fail_log == false) {
                echo 'failed to log the status';
                exit();
            }
        }

        /* 
        
        // update cancel status on stripe
        $upCancel = updateDatabyId('cancelled', 'status', $transaction_id, 'stripe_logs');
        if ($upCancel == false) {
            echo 'stripe log failed to cancel';
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

http_response_code(200);
