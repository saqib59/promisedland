<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../library/PayPal/autoload.php';

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'AQpLZpoJtIKjsAVyVhwJuHkegzbHtmNBPgMBzIQ2w_gmy11TgpHtYqri9BN1zlDAJ9HwdGchoNPjrEQB',
        'EO_lPoV_HONr1nHFpkWYcx7RHVmgCWPh-MpbOPkbhPwOvzOZdoEV_PQHNOBnTgJ4dm0Xp9BCnzqNx6HE'
    )
);

?>