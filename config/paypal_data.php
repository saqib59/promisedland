<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('PAYPAL_ID', 'info@promised-land.de'); 
define('PAYPAL_SANDBOX', FALSE); //TRUE or FALSE 
 
define('PAYPAL_NOTIFY_URL', LINK . '/payment/onetime_paypal_notify.php'); 
define('PAYPAL_RETURN_URL', LINK . '/payment/onetime_paypal.php'); 
define('PAYPAL_CANCEL_URL', LINK . '/course/payment/?status=cancel'); 

// Change not required 
define('PAYPAL_URL', (PAYPAL_SANDBOX == true)?"https://www.sandbox.paypal.com/cgi-bin/webscr":"https://www.paypal.com/cgi-bin/webscr");