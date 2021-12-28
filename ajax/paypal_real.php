<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//require_once '../config/config.php';
require_once '../inc/account/logged.php';
//require_once '../config/paypal_init.php';

$plans = plans();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    if (!empty($p["package"]) && !empty($p["plan"])) {

        $package = $p["package"];
        $plan_id = $p["plan"];

        // save user details
        if (check_row($user, 'user_id', 'user_details') == false) {
            $details = newDetails($user, $p['address'], $p['state'], $p['city'], $p['zip']);
        }

        $paypal_token = paypalToken();
        //dump($paypal_token);

        if (!empty($paypal_token)) {

            $headers = [
                'Accept: application/json',
                'Authorization: Bearer ' . $paypal_token,
                'Prefer: return=representation',
                'Content-Type: application/json',
            ];

            $url = "https://api-m.paypal.com/v1/billing/subscriptions";
            //$url = "https://api-m.sandbox.paypal.com/v1/billing/subscriptions";

            $names = explode(' ', $p['name']);
            if (count($names) > 1) {
                $first_name = $names[0];
                $last_name = $names[1];
            } else {
                $first_name = $p['name'];
                $last_name = '';
            }

            $fields = array(
                'plan_id' => $plans[$package][$plan_id]['plan'],
                'start_time' => date('Y-m-d\TH:i:s\Z', strtotime('+2 hours')),
                'subscriber' => array(
                    'name' => array(
                        'given_name' => $first_name,
                        'surname' => $last_name
                    ),
                    'email_address' => $p['email']
                ),
                'application_context' => array(
                    'brand_name' => 'Promised-land',
                    'locale' => 'de-DE',
                    'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                    'user_action' => 'SUBSCRIBE_NOW',
                    'payment_method' => array(
                        'payer_selected' => 'PAYPAL',
                        'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED'
                    ),
                    'return_url' => LINK . '/payment/checkout_paypal.php?status=success',
                    //'return_url' => USER . '/payment/?status=complete',
                    'cancel_url' => USER . '/payment/?status=cancel'
                ),
            );

            //$data = http_build_query($fields);
            $data = json_encode($fields);

            $server_output = curl(0, $url, $headers, $data);

            if ($server_output && !empty($server_output)) {
                $response = json_decode($server_output, true);
                //dump($response);
                if (isset($response['status']) && $response['status'] == 'APPROVAL_PENDING') {
                    // log the transaction in database
                    $transaction = logPaypal($_SESSION['user'], $response['plan_id'], $response['id']);
                    if ($transaction) {
                        foreach ($response['links'] as $link) {
                            if ($link['rel'] == 'approve') {

                                // set session data
                                $_SESSION['package'] = $package;
                                $_SESSION['plan'] = $plan_id;

                                // return paypal subscription link
                                echo $link['href'];
                            }
                        }
                    } else {
                        echo 'error_logging';
                    }
                } else {
                    echo 'status_not';
                }
            }

            //dump($server_output);
        } else {
            echo 'missing_token';
        }
    }
}
