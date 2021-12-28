<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once '../config/paypal_data.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    //dump($p);

    /* array(10) {
        ["course_id"]=>
        string(1) "3"
        ["method"]=>
        string(6) "paypal"
        ["package"]=>
        string(0) ""
        ["plan"]=>
        string(0) ""
        ["name"]=>
        string(20) "Pruthuvi Kaweeshwara"
        ["email"]=>
        string(20) "rulemax567@gmail.com"
        ["address"]=>
        string(43) "VIDYAWARDANA MAWATHA, HENNATHOTA, DODANDUWA"
        ["state"]=>
        string(1) "1"
        ["city"]=>
        string(5) "Galle"
        ["zip"]=>
        string(5) "80250"
      } */



    /* $curl = curl_init(PAYPAL_URL);
    curl_setopt($curl, CURLOPT_URL, PAYPAL_URL);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_VERBOSE, true);

    $headers = array(
        "Content-Type: application/x-www-form-urlencoded",
        'Connection: Close', 
        'User-Agent: PromisedLand'
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    //$data = "param1=value1&param2=value2";

    $data = '';
    $data .= 'business=' . PAYPAL_ID . '&';
    $data .= 'cmd=_xclick&';

    $data .= 'item_name=' . get_data($p['course_id'], 'title', 'course') . '&';
    $data .= 'item_number=' . $p['course_id'] . '&';
    $data .= 'amount=' . get_data($p['course_id'], 'price', 'course') . '&';
    $data .= 'currency_code=EUR&';

    $data .= 'return=' . PAYPAL_RETURN_URL . '&';
    $data .= 'cancel_return=' . PAYPAL_CANCEL_URL;

    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    //for debug only!
    //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    echo curl_exec($curl);
    curl_close($curl);
    // echo $resp; */

    if (check_row($user, 'user_id', 'user_details') == false) {
        newDetails($user, $p["address"], $p["state"], $p["city"], $p["zip"]);
    }

    $data = array(
        'business' => PAYPAL_ID,
        'cmd' => '_xclick',

        'first_name' => $p["name"],
        'address1' => $p["address"],
        'city' => $p["city"],
        'email' => ["email"],
        'state' => ["state"],
        'zip' => ["zip"],

        'item_name' => get_data($p['course_id'], 'title', 'course'),
        'item_number' => $p['course_id'],
        'amount' => get_data($p['course_id'], 'price', 'course'),
        'currency_code' => 'EUR',

        //'notify_url' => PAYPAL_NOTIFY_URL,
        'return' => PAYPAL_RETURN_URL,
        'cancel_return' => PAYPAL_CANCEL_URL,
    );

    echo PAYPAL_URL . '?' . http_build_query($data);
    exit;

} else {
    echo '0';
}