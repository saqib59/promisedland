<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once '../config/stripe_init.php';

require_once  '../inc/account/logged.php';

if (user() == false) {
    header("Location: " . USER);
    exit();
}

if (!isset($_POST['course_id']) || empty($_POST['course_id'])) {
    user_redirect("Ungültiger Link", 'error', LINK . '/courses/');
}
$course_id = $_POST['course_id'];

$course = $db->query('SELECT * FROM `course` WHERE `id` = ?', $course_id)->fetchArray();
if (!$course) {
    user_redirect("Ungültiger Link", 'error', LINK . '/courses/');
}

if (checkCourse($user, $course['id'])) {
    user_redirect("Du hast dir den Kurs bereits gebucht.", 'info', USER . '/courses/');
}

$course_price = $course['price'];
if (contentStatus(array('premium', 'plus'))) {
    $course_price = '0';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Purchase Course - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!--Start . Section : Purchase -->
    <section id="purchase">
        <div class="container">

            <form id="<?= ($course_price == '0' || $course_price == '0,00') ? 'course_free__checkout' : 'course_checkout' ?>" action="<?= fullUrl() ?>" method="POST">
                <input name="course_id" type="hidden" value="<?= $course_id ?>">

                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-7 col-md-9 col-sm-12 col-12">
                        <?php include HOME . '/inc/checkout.php'; ?>
                    </div>
                    <div class="col-xl-4 col-lg-5 col-md-9 col-sm-12 col-12">
                        <div class="checkout_details">

                            <div class="checkout_details__info">
                                <table>
                                    <tr>
                                        <td>Kurs</td>
                                        <td><?= $course['title'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Verfasser</td>
                                        <td><?= get_data($course['author'], 'name', 'course_author') ?></td>
                                    </tr>
                                    <tr>
                                        <td>Preis</td>
                                        <td><?= $course_price == '0' || $course_price == '0.00' ? 'Free' : priceClean($course_price) . '&euro;' ?></td>
                                    </tr>
                                </table>
                            </div>

                            <?php if ($course_price == '0' || $course_price == '0,00') { ?>

                            <?php } else { ?>

                                <div class="payment_method course_pay_method">
                                    <div class="payment_option__title">
                                        <h4>Wie möchtest du bezahlen?</h4>
                                    </div>
                                    <div class="payment_method__option">
                                        <div class="payment_method__option-col">
                                            <div class="payment_icon">
                                                <i class="fab fa-paypal"></i>
                                                <span>PayPal</span>
                                                <input type="radio" name="gateway" value="paypal">
                                            </div>
                                        </div>
                                        <div class="payment_method__option-col">
                                            <div class="payment_icon">
                                                <i class="fab fa-stripe-s"></i>
                                                <span>Stripe</span>
                                                <input type="radio" name="gateway" value="stripe">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="course_checkout__stripe"></div>

                            <?php } ?>

                            <div class="checkout_details__privacy">
                                <p>Ich stimme der Ausführung des Vertrages vor Ablauf der Widerrufsfrist ausdrücklich zu. Ich habe zur Kenntnis genommen, dass das Widerrufsrecht mit Beginn der Ausführung des Vertrages erlischt. Mit der Bestellung akzeptierst du unsere <a href="https://www.iubenda.com/nutzungsbedingungen/17675138" class="ft_menu__item iubenda-nostyle no-brand iubenda-noiframe iubenda-embed iubenda-noiframe " title="AGBs ">AGBs</a>.</p>
                            </div>

                            <?php if ($course_price == '0' || $course_price == '0,00') { ?>
                                <div class="course_free__claim">
                                    <button type="submit" class="btn btn-dark">
                                        <i class="fa fa-comment-alt-check"></i>
                                        <span>Jetzt kostenfrei ansehen</span>
                                    </button>
                                </div>
                            <?php } else { ?>
                                <div class="course_payment">
                                    <button type="submit" class="btn btn-dark" disabled>
                                        <i class="fa fa-shopping-cart"></i>
                                        <span>Bestellen</span>
                                    </button>
                                </div>
                            <?php } ?>

                            <div class="course_checking">
                                <div class="overlay"></div>
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