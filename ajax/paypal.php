<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once '../config/paypal_init.php';

use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;

use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\ShippingAddress;

$plans = plans();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    if (!empty($p["package"]) && !empty($p["plan"])) {

        $package = $p["package"];
        $plan_id = $p["plan"];

        /////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////

        //$planPrice = (float)$plans[$package][$plan_id]['amount'] / (float)$plans[$package][$plan_id]['count'];
        //$planPrice = number_format($planPrice, 2);

        //$planPrice = (float)$plans[$package][$plan_id]['single'];
        $planPrice = (float)$plans[$package][$plan_id]['amount'];

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
            if ($couponUsage == true && $discount_percentage !== 0 && $discount_percentage !== '0') {
                $planPrice = $planPrice * ((100 - $discount_percentage) / 100);
            }
        }

        /////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////


        $plan = new Plan();
        $plan->setName($plans[$package][$plan_id]['title'])->setDescription($plans[$package][$plan_id]['description'])->setType('FIXED');

        // Set billing plan definitions
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName($plans[$package][$plan_id]['title'])
            ->setType('REGULAR')
            ->setFrequency('MONTH')
            ->setFrequencyInterval(1)
            ->setCycles($plans[$package][$plan_id]['count'])
            ->setAmount(new Currency(array(
                'value' => $planPrice,
                'currency' => 'EUR'
            )));

        // Set charge models
        $chargeModel = new ChargeModel();
        $chargeModel->setType('SHIPPING')->setAmount(new Currency(array(
            'value' => 0,
            'currency' => 'EUR'
        )));
        $paymentDefinition->setChargeModels(array(
            $chargeModel
        ));

        // Set merchant preferences
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl(LINK . '/payment/membership_paypal.php?status=success')
            ->setCancelUrl(USER . '/payment/?status=cancel')
            ->setAutoBillAmount('yes')
            ->setInitialFailAmountAction('CONTINUE')
            ->setMaxFailAttempts('3')
            ->setSetupFee(new Currency(array(
                'value' => 0,
                'currency' => 'EUR'
            )));

        $plan->setPaymentDefinitions(array(
            $paymentDefinition
        ));
        $plan->setMerchantPreferences($merchantPreferences);

        try {
            $createdPlan = $plan->create($apiContext);

            // Generate and store hash
            $hash = md5($createdPlan->getId());
            $_SESSION['paypal_hash'] = $hash;

            $_SESSION['package'] = $p['package'];
            $_SESSION['plan'] = $p['plan'];

            $_SESSION['coupon_usage_id'] = $coupon_usage_id;

            // log the transaction in database
            $transaction = logPaypal($_SESSION['user'], $createdPlan->getId(), $hash);
            // save user details
            $details = newDetails($_SESSION['user'], $p['address'], $p['state'], $p['city'], $p['zip']);

            if ($transaction) {
                try {
                    $patch = new Patch();
                    $value = new PayPalModel('{"state":"ACTIVE"}');
                    $patch->setOp('replace')->setPath('/')->setValue($value);
                    $patchRequest = new PatchRequest();
                    $patchRequest->addPatch($patch);
                    $createdPlan->update($patchRequest, $apiContext);
                    $patchedPlan = Plan::get($createdPlan->getId(), $apiContext);

                    // Create new agreement
                    $startDate = date('c', time() + 3600);
                    $agreement = new Agreement();
                    $agreement->setName('Promised Land Monthly Premium Membership')->setDescription('Promised Land Monthly Premium Membership Agreement')->setStartDate($startDate);

                    // Set plan id
                    $plan = new Plan();
                    $plan->setId($patchedPlan->getId());
                    $agreement->setPlan($plan);

                    // Add payer type
                    $payer = new Payer();
                    $payer->setPaymentMethod('paypal');
                    $agreement->setPayer($payer);

                    // Adding shipping details
                    $shippingAddress = new ShippingAddress();
                    $shippingAddress->setLine1($p['address'])
                        ->setCity($p['city'])
                        ->setState($p['state'])
                        ->setPostalCode($p['zip'])
                        ->setCountryCode('DE');
                    $agreement->setShippingAddress($shippingAddress);

                    try {
                        // Create agreement
                        $agreement = $agreement->create($apiContext);

                        // Extract approval URL to redirect user
                        $approvalUrl = $agreement->getApprovalLink();

                        //header("Location: " . $approvalUrl);
                        echo $approvalUrl;
                        exit();
                    } catch (PayPal\Exception\PayPalConnectionException $ex) {
                        echo $ex->getCode();
                        echo $ex->getData();
                        die($ex);
                    } catch (Exception $ex) {
                        die($ex);
                    }
                } catch (PayPal\Exception\PayPalConnectionException $ex) {
                    echo $ex->getCode();
                    echo $ex->getData();
                    die($ex);
                } catch (Exception $ex) {
                    die($ex);
                }
            }
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
    }
}
