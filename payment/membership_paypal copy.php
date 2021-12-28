<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';
require_once '../config/paypal_init.php';

$plans = plans();

if (isset($_GET['status']) && $_GET['status'] == "success") {

    // do paypal functions
    if (isset($_GET['token']) && !empty($_GET['token'])) {
        $transaction = $db->query("SELECT * FROM `paypal_logs` WHERE `hash` = ?;", $_SESSION['paypal_hash'])->fetchArray();
        $plan_id = $transaction['plan_id'];
        $transaction_id = $transaction['id'];

        $token = $_GET['token'];
        $agreement = new \PayPal\Api\Agreement();

        try {
            // Execute agreement
            $payment = $agreement->execute($token, $apiContext);

            // update transaction to complete
            $complete = $db->query("UPDATE paypal_logs SET complete = 1, `updated_at` = CURRENT_TIMESTAMP WHERE plan_id = ?;", $plan_id);


            // check if already have a row with this data
            if (check_row($transaction_id, 'transaction_id', 'membership') == false) {
                // create new membership row
                $membership = newMembership($_SESSION['user'], $_SESSION['package'], $_SESSION['plan'], 'paypal', $transaction_id);
                if ($membership == false) {
                    redirect("Failed to create membership for you. Please contact us immediately", USER . "/payment/?status=error");
                }
            } else {
                redirect("Your membership is already assigned!", USER );
            }


            // update user membership row id
            $userMem = updateDatabyId($membership, 'membership_id', $_SESSION['user'], 'users');
            if ($userMem == false) {
                redirect("Failed to assign the membership for you. Please contact us immediately", USER . "/payment/?status=error");
            }

            // unset sessions
            unset($_SESSION['paypal_hash']);
            unset($_SESSION['package']);
            unset($_SESSION['plan']);

            // redirect to new complete link
            header("Location: " . USER . "/payment/?membership_id={$membership}&status=complete");
            exit();
            //echo '<pre>' . $payment . '</pre>';
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            header("Location: " . USER . "/payment/?status=error");
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            header("Location: " . USER . "/payment/?status=error");
            die($ex);
        }
        exit;
    }
}
