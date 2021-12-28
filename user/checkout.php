<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';
require_once '../config/stripe_init.php';

$info = userStatus($user);
if (isset($info)) {
    if ($info['status'] == 'pending') {
        user_redirect('Your membership is pending approval', 'warning', USER . '/subscription/');
    }
    if ($info['plan'] !== 'free' && $info['status'] == 'approved') {
        user_redirect('Du bist bereits Premium MItglied', 'info', USER . '/subscription/');
    }
}

$plans = plans();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = $_POST;
    if (
        isset($d['method']) && $d['method'] !== '' &&
        isset($d['package']) && $d['package'] !== '' &&
        isset($d['plan']) && $d['plan'] !== ''
    ) {
        if (
            ($d['method'] == 'paypal' || $d['method'] == 'stripe') &&
            ($d['package'] == 'premium' || $d['package'] == 'plus') &&
            ($d['plan'] == '1' || $d['plan'] == '3' || $d['plan'] == '6' || $d['plan'] == '12')
        ) {
            $method = $d['method'];
            $package = $d['package'];
            $plan = $d['plan'];
        } else {
            user_redirect('Etwas lief falsch! Bitte probiere es erneut ', 'error', USER . '/subscription/');
        }
    } else {
        user_redirect('Etwas lief falsch! Bitte probiere es erneut ', 'error', USER . '/subscription/');
    }
} else {
    user_redirect('Etwas lief falsch! Bitte probiere es erneut ', 'error', USER . '/subscription/');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <section id="checkout">
        <div class="container">

            <form id="user_checkout" action="<?= fullUrl() ?>" method="POST">
                <div class="row justify-content-center">

                    <div class="col-xl-8 col-lg-7 col-md-9 col-sm-12 col-12">
                        <?php include HOME . '/inc/checkout.php'; ?>

                        <div class="coupon_apply">
                            <div class="coupon_apply__alert"></div>
                            <div class="form-group">
                                <label>Coupon Code</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <div class="btn btn-dark btn-sm">Activate Coupon</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-5 col-md-9 col-sm-12 col-12">
                        <div class="checkout_details">

                            <input type="hidden" id="package" value="<?= $package ?>">
                            <input type="hidden" id="plan_id" value="<?= $plan ?>">
                            <input type="hidden" id="plan_amount" value="<?= $plans[$package][$plan]['amount'] ?>">

                            <div class="checkout_details__info">
                                <table>
                                    <tr>
                                        <td>Zahlungsmethode</td>
                                        <td><?= ucfirst($method) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Plan</td>
                                        <td>Premium</td>
                                    </tr>
                                    <tr>
                                        <td>Dauer</td>
                                        <td><?= $d['plan'] ?> <?= $d['plan'] == 1 ? 'Monat' : 'Monate' ?></td>
                                    </tr>
                                    <tr>
                                        <td>Betrag</td>
                                        <td>&euro; <?= $plans[$package][$plan]['amount'] ?></td>
                                    </tr>
                                </table>
                            </div>

                            <?php if ($d['method'] == 'stripe') { ?>

                                <div id="paymentResponse"></div>

                                <div class="checkout_details__card">
                                    <div class="form-group">
                                        <label>Kartennummer</label>
                                        <div id="card_number" class="field form-control"></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                            <label>Ablaufdatum</label>
                                            <div id="card_expiry" class="field form-control"></div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                            <label>CVC</label>
                                            <div id="card_cvc" class="field form-control"></div>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>

                            <div class="checkout_details__privacy">
                                <p>Ich stimme der Ausführung des Vertrages vor Ablauf der Widerrufsfrist ausdrücklich zu. Ich habe zur Kenntnis genommen, dass das Widerrufsrecht mit Beginn der Ausführung des Vertrages erlischt. Mit der Bestellung akzeptierst du unsere <a href="https://www.iubenda.com/nutzungsbedingungen/17675138" class="ft_menu__item iubenda-nostyle no-brand iubenda-noiframe iubenda-embed iubenda-noiframe " title="AGBs ">AGBs</a>.</p>
                            </div>

                            <div class="checkout_details__checkout">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fa fa-shopping-basket"></i>
                                    <span>Weiter zum bezahlen</span>
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
            </form>

        </div>
    </section>

    <div id="stripe_key" data-key="<?= STRIPE_PUBLISHABLE_KEY ?>"></div>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>
</body>

</html>