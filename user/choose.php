<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once HOME . '/inc/account/logged.php';

$package = '';

$info = userStatus($user);
if (isset($info)) {
    if ($info['status'] == 'pending') {
        user_redirect('Your membership is pending approval', 'warning', USER . '/subscription/');
    }
    if ($info['plan'] !== 'free' && $info['status'] == 'approved') {
        user_redirect('Du bist bereits Premium MItglied', 'info', USER . '/subscription/');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = $_POST;

    if (isset($d['package']) && !empty($d['package'])) {
        $package = $d['package'];
    } else {
        user_redirect('Ewas lief falsch! Bitte erneut probieren', 'error', LINK . '/packages/');
    }
} else {
    user_redirect('Ewas lief falsch! Bitte erneut probieren', 'error', LINK . '/packages/');
}

$plans = plans();

if ($package == 'premium') {
    $pack_title = 'Premium';
    $pricing = $plans['premium'];
}
if ($package == 'plus') {
    $pack_title = 'Premium+';
    $pricing = $plans['plus'];
}

/* if ($package == 'premium') {
    $pack_title = 'Premium';
    $pricing = array(
        '12' => '44.99',
        '6' => '25.99',
        '1' => '5.49'
    );
}
if ($package == 'plus') {
    $pack_title = 'Premium+';
    $pricing = array(
        '12' => '59.99',
        '6' => '35.49',
        '1' => '7.00'
    );
} */

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

    <!-- Start . Section : Premimum Features -->

    <section id="premium" class="no-bot">
        <div class="container">
            <div class="package_details <?= $package == 'plus' ? 'premium' : '' ?>">
                <div class="row row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1">
                    <div class="col">
                        <div class="package_box">
                            <div class="package_box__inner">
                                <div class="package_box__front">
                                    <div class="package_image">
                                        <img src="<?= LINK ?>/assets/img/pack/1.jpg" alt="">
                                    </div>
                                </div>
                                <div class="package_box__back">
                                    <div class="package_body__title">
                                        <h4>Künstliche Intelligenz</h4>
                                    </div>
                                    <div class="package_info">
                                        <p>Mit Hilfe unserer KI analysieren wir die Gutachten und zeigen dir Chancen und Risiken so auf, dass du in nur 3 Minuten das Wichtigste über die Immobilie weißt.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="package_box">
                            <div class="package_box__inner">
                                <div class="package_box__front">
                                    <div class="package_image">
                                        <img src="<?= LINK ?>/assets/img/pack/2.jpg" alt="">
                                    </div>
                                </div>
                                <div class="package_box__back">
                                    <div class="package_body__title">
                                        <h4>Standortanalyse</h4>
                                    </div>
                                    <div class="package_info">
                                        <p>Der wichtigste Werttreiber einer Immobilie ist der Standort. Neben einer umfangreichen Makroanalyse bieten wir dir mit dem TT-Software-Service den weltweit ersten zielgruppenorientierten Standort-Score.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="package_box">
                            <div class="package_box__inner">
                                <div class="package_box__front">
                                    <div class="package_image">
                                        <img src="<?= LINK ?>/assets/img/pack/3.jpg" alt="">
                                    </div>
                                </div>
                                <div class="package_box__back">
                                    <div class="package_body__title">
                                        <h4>Ertragsprognose</h4>
                                    </div>
                                    <div class="package_info">
                                        <p>Um besser einschätzen zu können, ob sich die Investition lohnt, berechnen wir dir die erwartete Rendite.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="package_box">
                            <div class="package_box__inner">
                                <div class="package_box__front">
                                    <div class="package_image">
                                        <img src="<?= LINK ?>/assets/img/pack/4.jpg" alt="">
                                    </div>
                                </div>
                                <div class="package_box__back">
                                    <div class="package_body__title">
                                        <h4>Erweiterte Immobiliensuche</h4>
                                    </div>
                                    <div class="package_info">
                                        <p>Hast du eine klare Vorstellung davon, wie deine Traumimmobilie aussehen oder welche Eigenschaften sie haben soll? Mit Hilfe der erweiterten Suchfunktion kannst du selbst nach den winzigsten Details filtern.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="package_box">
                            <div class="package_box__inner flipper">
                                <div class="package_box__back">
                                    <div class="package_body__title">
                                        <h4>Das Premium-Paket</h4>
                                    </div>
                                    <div class="package_info">
                                        <p>Der Favorit unserer Kunden. Das Premium-Paket unterstützt dich von Anfang bis Ende und darüber hinaus.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="package_box">
                            <div class="package_box__inner">
                                <div class="package_box__front">
                                    <div class="package_image">
                                        <img src="<?= LINK ?>/assets/img/pack/6.jpg" alt="">
                                    </div>
                                </div>
                                <div class="package_box__back">
                                    <div class="package_body__title">
                                        <h4>Suchauftrag und Merkliste</h4>
                                    </div>
                                    <div class="package_info">
                                        <p>Die erweiterte Suche kann auch dauerhaft gespeichert werden, sodass du sofort informiert wirst, wenn eine passende Immobilie veröffentlicht wird.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="package_box">
                            <div class="package_box__inner">
                                <div class="package_box__front">
                                    <div class="package_image">
                                        <img src="<?= LINK ?>/assets/img/pack/7.jpg" alt="">
                                    </div>
                                </div>
                                <div class="package_box__back <?= $package == 'plus' ? '' : 'blank' ?>">
                                    <div class="package_body__title">
                                        <h4>Formular-Center und Checklisten</h4>
                                    </div>
                                    <div class="package_info">
                                        <p>Ob Checklisten zur Vorbereitung, Mietverträge oder rechtssichere Schreiben für deine Mieterverwaltung, all das bietet dir das Formular-Center.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="package_box">
                            <div class="package_box__inner">
                                <div class="package_box__front">
                                    <div class="package_image">
                                        <img src="<?= LINK ?>/assets/img/pack/8.jpg" alt="">
                                    </div>
                                </div>
                                <div class="package_box__back <?= $package == 'plus' ? '' : 'blank' ?>">
                                    <div class="package_body__title">
                                        <h4>Video-Datenbank</h4>
                                    </div>
                                    <div class="package_info">
                                        <p>Unser detaillierter Auktionsleitfaden reicht dir nicht und du möchtest dir noch mehr Fachwissen aneignen?</p>
                                        <p>Jeden Monat bieten wir dir mehrere neue Videos zum Immobilienmarkt.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="package_box">
                            <div class="package_box__inner">
                                <div class="package_box__front">
                                    <div class="package_image">
                                        <img src="<?= LINK ?>/assets/img/pack/9.jpg" alt="">
                                    </div>
                                </div>
                                <div class="package_box__back <?= $package == 'plus' ? '' : 'blank' ?>">
                                    <div class="package_body__title">
                                        <h4>Persönliche Beratung</h4>
                                    </div>
                                    <div class="package_info">
                                        <p>Noch nicht sicher, wie du vorgehen sollst? Kein Problem, unsere Berater nehmen sich persönlich Zeit für dich und besprechen deinen Fall mit dir.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Start . Section : Payment -->

    <section id="payment">
        <div class="container">

            <form id="user_form" action="<?= LINK ?>/user/checkout/" method="POST">
                <input type="hidden" name="package" value="<?= $package ?>">

                <?= user() ? userMembInfo($user, false) : ''; ?>

                <div class="payment_section">
                    <div class="payment_option__title">
                        <h4>Wähle dein Paket:</h4>
                    </div>
                    <div class="payment_option__months">
                        <div class="row row-cols-xl-4 row-cols-lg-4 row-cols-md-2 row-cols-sm-2 row-cols-1 justify-content-center">
                            <div class="col">
                                <div class="payment_plan__box">

                                    <div class="payment_month">
                                        <strong>Einführungsangebot</strong>
                                        <p>12 Monate</p>
                                        <span>Mindestlaufzeit</span>
                                    </div>
                                    <div class="payment_price">
                                        <p><?= priceGerman($pricing[12]['amount']) ?>&euro;</p>
                                        <strong><?= priceGerman($pricing[12]['discounted']) ?>&euro;</strong>
                                        <span>/Monat</span>
                                    </div>
                                    <input type="radio" name="plan" value="12">
                                </div>
                            </div>
                            <div class="col">
                                <div class="payment_plan__box">
                                    <div class="payment_month">
                                        <strong>Einführungsangebot</strong>
                                        <p>6 Monate</p>
                                        <span>Mindestlaufzeit</span>
                                    </div>
                                    <div class="payment_price">
                                        <p><?= priceGerman($pricing[6]['amount']) ?>&euro;</p>
                                        <strong><?= priceGerman($pricing[6]['discounted']) ?>&euro;</strong>
                                        <span>/Monat</span>
                                    </div>
                                    <input type="radio" name="plan" value="6">
                                </div>
                            </div>
                            <div class="col">
                                <div class="payment_plan__box">
                                    <div class="payment_month">
                                        <strong>Einführungsangebot</strong>
                                        <p>3 Monate</p>
                                        <span>Mindestlaufzeit</span>
                                    </div>
                                    <div class="payment_price">
                                        <p><?= priceGerman($pricing[3]['amount']) ?>&euro;</p>
                                        <strong><?= priceGerman($pricing[3]['discounted']) ?>&euro;</strong>
                                        <span>/Monat</span>
                                    </div>
                                    <input type="radio" name="plan" value="3">
                                </div>
                            </div>
                            <div class="col">
                                <div class="payment_plan__box">
                                    <div class="payment_month">
                                        <strong>Einführungsangebot</strong>
                                        <p>1 Monat</p>
                                        <span>Mindestlaufzeit</span>
                                    </div>
                                    <div class="payment_price">
                                        <p><?= priceGerman($pricing[1]['amount']) ?>&euro;</p>
                                        <strong><?= priceGerman($pricing[1]['discounted']) ?>&euro;</strong>
                                        <span>/Monat</span>
                                    </div>
                                    <input type="radio" name="plan" value="1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="payment_section">
                    <div class="payment_method">
                        <div class="payment_option__title">
                            <h4>Wie möchtest du bezahlen?</h4>
                        </div>
                        <div class="payment_method__option">
                            <div class="payment_method__option-col">
                                <div class="payment_icon">
                                    <i class="fab fa-paypal"></i>
                                    <span>PayPal</span>
                                    <input type="radio" name="method" value="paypal">
                                </div>
                            </div>
                            <div class="payment_method__option-col">
                                <div class="payment_icon">
                                    <i class="fab fa-stripe-s"></i>
                                    <span>Stripe</span>
                                    <input type="radio" name="method" value="stripe">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="buy_now">
                        <div class="payment_notice">
                            <i class="fa fa-info-circle"></i>
                            <p>Nachdem du auf Kaufen geklickt hast, bestätige die Zahlung in deinem PayPal-Konto.</p>
                        </div>
                        <div class="buy_now__button">
                            <button class="btn btn-dark" disabled>
                                <i class="fa fa-shopping-cart"></i>
                                <span>Kaufe <?= $pack_title ?></span>
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </section>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>
</body>

</html>