<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//require_once '../config/config.php';
//require_once '../config/paypal_data.php';
require_once '../inc/account/logged.php';


$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    // save user details
    if (check_row($user, 'user_id', 'user_details') == false) {
        $details = newDetails($user, $p['address'], $p['state'], $p['city'], $p['zip']);
    }

    // paypal token
    $paypal_token = paypalToken();
    if (!empty($paypal_token)) {

        // set headers
        $headers = [
            'Accept: application/json',
            'Authorization: Bearer ' . $paypal_token,
            'Content-Type: application/json',
        ];

        // post url
        $url = "https://api-m.paypal.com/v2/checkout/orders";
        //$url = "https://api-m.sandbox.paypal.com/v2/checkout/orders";

        // gather data
        $item_name = get_data($p['course_id'], 'title', 'course');
        $course_id = $p['course_id'];
        $course_price = get_data($p['course_id'], 'price', 'course');
        $item_amount = price($course_price);
        $currency_code = 'EUR';

        $email_address = $p["email"];

        // post data
        $fields = array(
            "intent" => "CAPTURE",
            "payer" => array(
                "email_address" => $email_address,
                "name" => array(
                    "name" => $p["name"],
                ),
                "address" => array(
                    "country_code" => "DE",
                    "address_line_1" => $p["address"],
                    "admin_area_1" => $p["state"],
                    "admin_area_2" => $p["city"],
                    "postal_code" => $p["zip"],
                ),
            ),
            "purchase_units" => array(
                array(
                    "description" => $item_name,
                    "custom_id" => $course_id . "-" . rand(10000, 99999),
                    "amount" => array(
                        "currency_code" => $currency_code,
                        "value" => $item_amount,
                    )
                )
            ),
            "application_context" => array(
                "locale" => 'de-DE',
                "shipping_preference" => 'NO_SHIPPING',
                "return_url" => LINK . '/payment/onetime_paypal.php',
                "cancel_url" => LINK . '/course/payment/?status=cancel',
            ),
        );

        // curl
        $data = json_encode($fields, true);

        $server_output = curl(1, $url, $headers, $data);

        if ($server_output && !empty($server_output)) {
            $response = json_decode($server_output, true);
            //dump($response);
            
            // check if status is ok
            if (isset($response['status']) && $response['status'] == 'CREATED') {

                if (isset($response['purchase_units']['amount']['currency_code'])) {
                    $currency_code = $response['purchase_units']['amount']['currency_code'];
                }
                if (isset($response['purchase_units']['amount']['value'])) {
                    $item_amount = $response['purchase_units']['amount']['value'];
                }
                if (isset($response['purchase_units']['payee']['email_address'])) {
                    $email_address = $response['purchase_units']['payee']['email_address'];
                }


                // save the payment log
                $transaction = logPaypal_oneTime($user, $response['id'], $course_id, $currency_code, $item_amount, $email_address, '0');

                // show hateos link
                if ($transaction) {
                    foreach ($response['links'] as $link) {
                        if ($link['rel'] == 'approve') {

                            // set session data
                            $_SESSION['course'] = $course_id;

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
    }
} else {
    echo '0';
}
