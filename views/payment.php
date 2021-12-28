<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

$course_subscribe = false;

$data = array(
    'title' => '',
    'author' => '',
    'amount' => '',
    'gateway' => ''
);

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status = paymentCourse($_GET['status']);

    if ($_GET['status'] == 'complete') {
        /* if (isset($_GET['membership_id']) && !empty($_GET['membership_id'])) {
            $membership = $db->query("SELECT * FROM `membership` WHERE id = ?;", $_GET['membership_id'])->fetchArray();
            if ($user !== $membership['user_id']) {
                redirect("Ewas lief falsch! Bitte erneut probieren.!", USER);
            }
        } */
    }
} else {
    user_redirect("Ungültiger Link", 'error', LINK . '/courses/');
}



if (isset($_GET['subscription']) && !empty($_GET['subscription'])) {
    $subscription_id = $_GET['subscription'];

    $subscription = $db->query("SELECT * FROM `course_subscribe` WHERE id = ?;", $subscription_id)->fetchArray();
    $gateway = $subscription['gateway'];

    if ($subscription && !empty($subscription)) {

        $course_id = $subscription['course_id'];

        if (checkCourse($user, $course_id)) {
            $course_subscribe = true;
        }

        $course = $db->query("SELECT * FROM `course` WHERE id = ?;", $course_id)->fetchArray();
        if ($course && !empty($course)) {
            $data['title'] = $course['title'];
            $data['author'] = get_data($course['author'], 'name', 'course_author');
            if ($course['price'] == '0' || $course['price'] == '0,00') {
                $data['amount'] = 'Free';
            } else {
                $data['amount'] = $course['price'] . '&euro;';
            }
            $data['gateway'] = ucfirst($gateway);
        } else {
            user_redirect("Ungültiger Link", 'error', LINK . '/courses/');
        }

        if ($subscription['email_status'] == '0') {
            
            $user_email = get_data($user, 'email', 'users');

            // @@mail : send course subscription email
            booking_confirm($user_email, 'course', $data);

            // update email status
            updateDatabyId('1', 'email_status', $subscription_id, 'course_subscribe');
        }
    } else {
        user_redirect("Ungültiger Link", 'error', LINK . '/courses/');
    }
} else {
    user_redirect("Ungültiger Link", 'error', LINK . '/courses/');
}

?>

<!DOCTYPE html>
<html lang="en">
<style>

</style>

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Payment Status - Promised Land</title>
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

                    <?php if (!empty($gateway) && $gateway == 'free') { ?>
                        <div class="payment_status complete">
                            <h4>Course Claimed Successfully!</h4>
                            <p>Please enjoy your new course.</p>
                        </div>
                    <?php } else {
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
                    }
                    ?>

                    <?php if ($course_subscribe) { ?>

                        <div class="membership_info">

                            <div class="membership_info-title">
                                <h2>Details zum Kurs</h2>
                            </div>

                            <div class="membership_info-table">
                                <table>
                                    <tr>
                                        <td style="width: 250px">Kurs</td>
                                        <td><?= $data['title'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Verfasser</td>
                                        <td><?= $data['author'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Preis</td>
                                        <td><?= $data['amount'] ?></td>
                                    </tr>

                                    <?php if (!empty($gateway) && $gateway !== 'free') { ?>
                                        <tr>
                                            <td>Zahlungsmethode</td>
                                            <td><?= $data['gateway'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Abonnement-Status</td>
                                            <td>Genehmigt</td>
                                        </tr>
                                    <?php } ?>

                                </table>
                            </div>

                            <div class="membership_info-btn">
                                <?php if ($_GET['status'] == 'complete') { ?>
                                    <a href="<?= USER ?>/courses/" class="btn btn-dark">
                                        <i class="fa fa-play-circle"></i>
                                        <span>Angemeldete Kurse ansehen </span>
                                    </a>
                                    <?php //} elseif ($_GET['status'] == 'error' || $_GET['status'] == 'cancelled') { 
                                    ?>
                                <?php } else { ?>
                                    <a href="<?= LINK ?>/courses/" class="btn btn-dark">
                                        <i class="fa fa-share"></i>
                                        <span>Zurück</span>
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