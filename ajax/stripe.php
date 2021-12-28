<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once '../config/stripe_init.php';

$plans = plans();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

$payment_id = $statusMsg = $api_error = '';
$ordStatus = 'error';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    // Check whether stripe token is not empty 
    if (!empty($p['package']) && !empty($p['plan']) && !empty($p['stripeToken'])) {

        $currency = 'EUR';

        // Retrieve stripe token and user info from the submitted form data 
        $token  = $p['stripeToken'];
        $name = $p['name'];
        $email = $p['email'];

        // Plan info 
        $package = $p['package'];
        $plan_id = $p['plan'];

        $planName = $plans[$package][$plan_id]['title'];
        $planInterval = $plans[$package][$plan_id]['count'];

        /////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////

        $planPrice = $plans[$package][$plan_id]['amount'];
        //$planPrice = $plans[$package][$plan_id]['single'];

        /////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////

        $couponUsage = false;
        $coupon_row = 0;
        $discount_percentage = 0;
        $coupon_usage_id = 0;

        // Calculate Coupon Pricing
        if (isset($p['coupon_code']) && !empty($p['coupon_code'])) {
            // retrieve coupon id
            $coupon_row = get_col_data($p['coupon_code'], 'code', 'id', 'coupon');

            // check user have a unused coupon session
            $coupon_usage = $db->query("SELECT * FROM `coupon_usage` WHERE `coupon_id` = ? AND `user` = ? AND `used` = '0' ORDER BY id DESC LIMIT 0, 1;", $coupon_row, $user)->fetchArray();
            if ($coupon_usage && !empty($coupon_usage)) {
                $couponUsage = true;
                $coupon_usage_id = $coupon_usage['id'];
            }

            // fetch the coupon data
            $couponFetch = $db->query("SELECT * FROM `coupon` WHERE `id` = ? AND `status` = '1';", $coupon_row)->fetchArray();
            if ($couponFetch && !empty($couponFetch)) {
                // retrieve the discount amount
                $discount_percentage = $couponFetch['discount'];
            }

            // reduce the price to pay
            if($couponUsage == true && $discount_percentage !== 0 && $discount_percentage !== '0') {
                $planPrice = $planPrice * ( (100 - $discount_percentage) / 100 );
            }
        }

        /////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////

        

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
            $priceCents = round($planPrice * 100);

            // Create a plan 
            try {
                $plan = \Stripe\Plan::create(array(
                    "product" => [
                        "name" => $planName
                    ],
                    "amount" => $priceCents,
                    "currency" => $currency,
                    "interval" => 'month',
                    "interval_count" => $planInterval,
                    "usage_type" => 'metered',
                    //'aggregate_usage' => 'max',
                    'billing_scheme' => 'per_unit',
                ));
            } catch (Exception $e) {
                $api_error = $e->getMessage();
            }

            if (empty($api_error) && $plan) {
                // Creates a new subscription 
                try {
                    $subscription = \Stripe\Subscription::create(array(
                        "customer" => $customer->id,
                        "items" => array(
                            array(
                                "plan" => $plan->id,
                            ),
                        ),
                    ));
                } catch (Exception $e) {
                    $api_error = $e->getMessage();
                }

                if (empty($api_error) && $subscription) {
                    // Retrieve subscription data 
                    $subsData = $subscription->jsonSerialize();

                    // Check whether the subscription activation is successful 
                    if ($subsData['status'] == 'active') {
                        // Subscription info 
                        $subscrID = $subsData['id'];
                        $custID = $subsData['customer'];
                        $planID = $subsData['plan']['id'];
                        $planAmount = ($subsData['plan']['amount'] / 100);
                        $planCurrency = $subsData['plan']['currency'];
                        $planinterval = $subsData['plan']['interval'];
                        $planIntervalCount = $subsData['plan']['interval_count'];

                        $created = date("Y-m-d H:i:s", $subsData['created']);
                        $current_period_start = date("Y-m-d H:i:s", $subsData['current_period_start']);
                        $current_period_end = date("Y-m-d H:i:s", $subsData['current_period_end']);

                        $status = $subsData['status'];

                        // log the transaction in database
                        //$transaction = $db->query("INSERT INTO `stripe_logs` (`id`, `user_id`, `stripe_subscription_id`, `stripe_customer_id`, `stripe_plan_id`, `plan_amount`, `plan_amount_currency`, `plan_interval`, `plan_interval_count`, `payer_email`, `status`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", $user, $subscrID, $custID, $planID, $planAmount, $planCurrency, $planinterval, $planIntervalCount, $email, $status);
                        $transaction = logStripe($user, $subscrID, $custID, $planID, $planAmount, $planCurrency, $planinterval, $planIntervalCount, $email, $status);

                        $_SESSION['package'] = $p['package'];
                        $_SESSION['plan'] = $p['plan'];
                        $_SESSION['stripe_token'] = $subscrID;
                        
                        $_SESSION['coupon_usage_id'] = $coupon_usage_id;

                        //echo LINK . "/payment/membership_stripe.php?status=success&stripe_token=" . $subscrID;
                        echo LINK . "/payment/membership_stripe.php?status=success";
                        exit();
                        //redirect("Your Subscription Payment has been Successful!", LINK . "/payment/membership_stripe.php?status=success&stripe_token=" . $subscrID);

                    } else {
                        echo "Subscription activation failed!";
                    }
                } else {
                    echo "Subscription creation failed! " . $api_error;
                }
            } else {
                echo "Plan creation failed! " . $api_error;
            }
        } else {
            echo "Invalid card details! $api_error";
        }
    } else {
        echo "Stripe token is missing!";
    }
}
