<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once '../config/stripe_init.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

$payment_id = $statusMsg = '';
$ordStatus = 'error';

// Check whether stripe token is not empty 
if (!empty($_POST['stripeToken'])) {

    // Retrieve stripe token, card and user info from the submitted form data 
    $token  = $_POST['stripeToken'];
    //$name = $_POST['name']; 
    //$email = $_POST['email']; 
    $name = '';
    $email = $_POST['email']; 

    $course_id = $_POST['course_id'];
    $itemPrice = price(get_data($course_id, 'price', 'course'));
    $currency = 'EUR';
    $itemName = get_data($course_id, 'title', 'course');

    // Include Stripe PHP library 
    require_once '../library/stripe/init.php';

    // Set API key 
    \Stripe\Stripe::setApiKey(STRIPE_API_KEY);

    // Add customer to stripe 
    try {
        $customer = \Stripe\Customer::create(array(
            'email' => $email,
            'source'  => $token
        ));
    } catch (Exception $e) {
        $api_error = $e->getMessage();
    }

    if (empty($api_error) && $customer) {

        // Convert price to cents 
        $itemPriceCents = ($itemPrice * 100);

        // Charge a credit or a debit card 
        try {
            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $itemPriceCents,
                'currency' => $currency,
                'description' => $itemName
            ));
        } catch (Exception $e) {
            $api_error = $e->getMessage();
        }

        if (empty($api_error) && $charge) {

            // Retrieve charge details 
            $chargeJson = $charge->jsonSerialize();

            // Check whether the charge is successful 
            if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) {

                // Transaction details  
                $txn_id = $chargeJson['balance_transaction'];
                $customer = $chargeJson['customer'];
                $paidAmount = $chargeJson['amount'];
                $paidAmount = ($paidAmount / 100);
                $price_currency = $chargeJson['currency'];
                $payment_status = $chargeJson['status'];

                $transaction = logStripe_oneTime($user, $txn_id, $course_id, $customer, $price_currency, $paidAmount, $email, $payment_status);

                // If the order is successful 
                if ($payment_status == 'succeeded') {
                    echo LINK . "/payment/onetime_stripe.php?status=success&stripe_token=" . $txn_id;
                    exit();
                } else {
                    $statusMsg = "Your Payment has Failed!";
                }
            } else {
                $statusMsg = "Transaction has been failed!";
            }
        } else {
            $statusMsg = "Charge creation failed! $api_error";
        }
    } else {
        $statusMsg = "Invalid card details! $api_error";
    }
} else {
    $statusMsg = "Error on form submission.";
}
