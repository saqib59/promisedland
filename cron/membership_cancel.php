<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';


















// product id = PROD-1ER96733624779529

// premium product id = PROD-05K29298Y7013210T
// premium+ product id = PROD-2EJ363319V777813L

$membships = $db->query("SELECT * FROM `membership` WHERE `status` = 'approved';")->fetchAll();

if ($membships && !empty($membships)) {
    foreach ($membships as $item) {

        if ($item['gateway'] == 'paypal') {
            
            // get plan id
            $plan = get_data($item['transaction_id'], 'plan_id', 'paypal_logs');

            // check if subscription not cancelled
            $headers = [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Bearer A21AAL8MJVqvEudD1DBwu2U5agPp-rrrIwNEuV_MiP7qiwVt3p7api6eVYP0P1ACvO0fx74boH8vkl19rFi5IkUigmxWDX1FA',
            ];
            
            //$url = "https://api-m.sandbox.paypal.com/v1/billing/plans/{$plan}";
            $url = "https://api-m.paypal.com/v1/billing/plans/{$plan}";

            $server_output = curl(0, $url, $headers);
            //dump($server_output);

            //$fields = http_build_query(array('grant_type' => 'client_credentials'));
            //$server_output = curl(1, 'https://api-m.sandbox.paypal.com/v1/oauth2/token', $headers, $fields);

            dump(json_decode($server_output));

            if ($server_output == "OK") {
                // if cancelled, cancel the status
                // update end date
            }

        } elseif ($item['gateway'] == 'stripe') {
            // check if subscription not cancelled
            // if cancelled, cancel the status
            // update end date
        }
    }
}
