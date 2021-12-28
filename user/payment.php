<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//require_once '../config/config.php';
require_once  '../inc/account/logged.php';

$membership = false;
$package = '';
$period = '';
$gateway = '';
$invoice_no = '';
$start_dt = '';
$end_dt = '';

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status = paymentStatus($_GET['status']);

    if ($_GET['status'] == 'complete') {
        if (isset($_GET['membership_id']) && !empty($_GET['membership_id'])) {
            $membership = $db->query("SELECT * FROM `membership` WHERE id = ?;", $_GET['membership_id'])->fetchArray();
            if ($membership && !empty($membership)) {

                if ($membership['plan'] == 'plus') {
                    $package = 'Premium+';
                } else {
                    $package = 'Premium';
                }

                if ($membership['period'] == '1') {
                    $period = '1 Monat';
                } else {
                    $period = $membership['period'] . ' Monate';
                }

                $gateway = ucfirst($membership['gateway']);
                $start_dt = dayOnly($membership['start_dt']);
                $end_dt = dayOnly($membership['end_dt']);

                if ($membership['invoice'] == '0') {

                    $txn_id = $membership['transaction_id'];
                    $invoice_id = $_GET['membership_id'];

                    // get invoice number
                    if ($membership['gateway'] == 'paypal') {
                        $invoice_no = get_data($txn_id, 'subscription_id', 'paypal_logs');
                    } elseif ($membership['gateway'] == 'stripe') {
                        $invoice_no = get_data($txn_id, 'stripe_subscription_id', 'stripe_logs');
                    }

                    // @@mail : invoice email
                    $user_email = get_data($user, 'email', 'users');
                    $invoice_sent = membership_invoice($user_email, $invoice_id, $invoice_no, $package, $period, $gateway, $start_dt, $end_dt);

                    // email sent
                    if($invoice_sent) {
                        updateDatabyId('1', 'invoice', $_GET['membership_id'], 'membership');
                    }
                }


                if ($user !== $membership['user_id']) {
                    user_redirect('Ewas lief falsch! Bitte erneut probieren.!', 'error', USER);
                }

                if (strtotime(dayOnly($membership['start_dt'])) < strtotime(dayOnly(today()))) {
                    header("Location: " . USER . "/subscription/");
                }
            } else {
                header("Location: " . USER);
            }
        }
    }
} else {
    header("Location: " . USER);
}

?>

<!DOCTYPE html>
<html lang="en">
<style>

</style>

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Promised Land</title>

    <!-- Event snippet for Website sale conversion page --> 
    <script> gtag('event', 'conversion', {'send_to': 'AW-347311738/yxhUCKfdl_8CEPqczqUB'}); </script>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!-- Start . Section : Packages -->
    <section id="payment">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10 col-md-10 col-sm-12 col-12">

                    <?php
                    if (isset($status) && !empty($status)) {
                        if ($_GET['status'] == 'complete') {
                            echo '<div class="payment_status complete">';
                        } else {
                            echo '<div class="payment_status">';
                        }
                        echo '<h4>' . $status['title'] . '</h4>
                                <p>' . $status['content'] . '</p>
                            </div>';
                    }
                    ?>

                    <?php if ($membership && !empty($membership)) { ?>

                        <div class="membership_info">

                            <div class="membership_info-title">
                                <h2>Mitgliedschaftsdetails</h2>
                            </div>

                            <div class="membership_info-table">
                                <table>
                                    <tr>
                                        <td>Mitgliedschaftsplan</td>
                                        <td><?= $package ?></td>
                                    </tr>
                                    <tr>
                                        <td>Dauer der Mitgliedschaft</td>
                                        <td><?= $period ?></td>
                                    </tr>
                                    <tr>
                                        <td>Zahlungsabwickler</td>
                                        <td><?= $gateway ?> <?= !empty($invoice_no) ? '(' . $invoice_no . ')' : '' ?></td>
                                    </tr>
                                    <tr>
                                        <td>Mitgliedschaftsstatus</td>
                                        <td>Genehmigt</td>
                                    </tr>
                                    <tr>
                                        <td>Beginn deiner Mitgliedschafts</td>
                                        <td><?= $start_dt ?></td>
                                    </tr>
                                    <tr>
                                        <td>Ablauf deiner Mitgliedschafts</td>
                                        <td><?= $end_dt ?></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="membership_info-btn">
                                <?php if ($_GET['status'] == 'complete') { ?>
                                    <a href="<?= LINK ?>/user/subscription/" class="btn btn-dark">
                                        <i class="fa fa-user"></i>
                                        <span>Besuche das Dashboard</span>
                                    </a>
                                <?php } elseif ($_GET['status'] == 'error' || $_GET['status'] == 'cancelled') { ?>
                                    <a href="<?= LINK ?>/packages/" class="btn btn-dark">
                                        <i class="fa fa-share"></i>
                                        <span>Zurück</span>
                                    </a>
                                    <a href="<?= USER ?>" class="btn btn-dark-outline">
                                        <span>Weiter ohne Bestellung</span>
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                <?php } ?>
                            </div>

                        </div>
                    <?php } ?>

                </div>
            </div>

        </div>
    </section>
    <!-- End . Section : Packages -->

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>
</body>

</html>