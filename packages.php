<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './config/config.php';

if (isset($_SESSION['email'])) {
    unset($_SESSION['email']);
}

if (user()) {
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;
    $info = userStatus($user);
}
if (isset($info)) {
    if ($info['status'] == 'pending') {
        user_redirect('Your membership is pending approval', 'warning', USER . '/subscription/');
    }
    if ($info['plan'] !== 'free' && $info['status'] == 'approved') {
        user_redirect('Du bist bereits Premium MItglied', 'info', USER . '/subscription/');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<style>

</style>

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Packages - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!-- Start . Section : Packages -->
    <section id="packages">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 col-12">

                    <div class="page_header__title gap">
                        <?php if (isset($info) && $info['status'] == 'expired') { ?>
                            <h2>Erneuern</h2>
                            <p>Erneure deine Mitgliedschaft hier.</p>
                        <?php } else { ?>
                            <h2>Willkommen</h2>
                            <p>Die Suche nach der Traumimmobilie ist oft schwieriger als gedacht. Mit unseren Kundenvorteilen erhältst du alle nötigen Werkzeuge von uns.</p>
                        <?php } ?>

                    </div>

                    <?= user() ? userMembInfo($user, false) : ''; ?>

                    <div class="packages_list">
                        <div class="row">

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="pr-pack premium">
                                    <div class="pr-top">
                                        <h4>Premium <span>+</span></h4>
                                    </div>
                                    <div class="pr-price">
                                        <h2>49,99€</h2>
                                        <p>/Monat</p>
                                    </div>
                                    <div class="pr-body">
                                        <ul>
                                            <li><i class="fa fa-check"></i> Gutachtenauswertung mit KI</li>
                                            <li><i class="fa fa-check"></i> Standortanalyse</li>
                                            <li><i class="fa fa-check"></i> Ertragsprognose</li>
                                            <li><i class="fa fa-check"></i> Erweiterte Immobiliensuche</li>
                                            <li><i class="fa fa-check"></i> Suchauftrag / Merkliste</li>
                                            <li><i class="fa fa-check"></i> Auktionsleitfaden</li>
                                            <li><i class="fa fa-check"></i> Vermittlungsdienste</li>
                                            <li><i class="fa fa-check"></i> Formular-Center und Checklisten</li>
                                            <li><i class="fa fa-check"></i> Seminar-Reihe</li>
                                            <li><i class="fa fa-check"></i> Persönliche Beratung</li>
                                        </ul>
                                    </div>
                                    <div class="pr-btn">
                                        <?php if (user()) { ?>
                                            <form action="<?= USER ?>/choose/" method="POST">
                                                <input type="hidden" name="package" value="plus">
                                                <button type="submit" class="btn btn-dark btn-block">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    <?php if (isset($info) && $info['status'] == 'expired') { ?>
                                                        <span>Als Premium+ erneuern</span>
                                                    <?php } else { ?>
                                                        <span>Premium+ kaufen</span>
                                                    <?php } ?>
                                                </button>
                                            </form>
                                        <?php } else { ?>
                                            <a href="<?= USER ?>/register/" class="btn btn-dark btn-block">
                                                <i class="fa fa-shopping-cart"></i>
                                                <span>Jetzt registrieren</span>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="pr-pack">
                                    <div class="pr-top">
                                        <h4>Premium</h4>
                                    </div>
                                    <div class="pr-price">
                                        <h2>29,99€</h2>
                                        <p>/Monat</p>
                                    </div>
                                    <div class="pr-body">
                                        <ul>
                                            <li><i class="fa fa-check"></i> Gutachtenauswertung mit KI</li>
                                            <li><i class="fa fa-check"></i> Standortanalyse</li>
                                            <li><i class="fa fa-check"></i> Ertragsprognose</li>
                                            <li><i class="fa fa-check"></i> Erweiterte Immobiliensuche</li>
                                            <li><i class="fa fa-check"></i> Suchauftrag / Merkliste</li>
                                            <li><i class="fa fa-check"></i> Auktionsleitfaden</li>
                                            <li><i class="fa fa-check"></i> Vermittlungsdienste</li>
                                            <li><i class="fas fa-times"></i> Formular-Center und Checklisten</li>
                                            <li><i class="fas fa-times"></i> Seminar-Reihe</li>
                                            <li><i class="fas fa-times"></i> Persönliche Beratung</li>
                                        </ul>
                                    </div>
                                    <div class="pr-btn">
                                        <?php if (user()) { ?>
                                            <form action="<?= USER ?>/choose/" method="POST">
                                                <input type="hidden" name="package" value="premium">
                                                <button type="submit" class="btn btn-blue btn-block">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    <?php if (isset($info) && $info['status'] == 'expired') { ?>
                                                        <span>Als Premium erneuern</span>
                                                    <?php } else { ?>
                                                        <span>Premium kaufen</span>
                                                    <?php } ?>
                                                </button>
                                            </form>
                                        <?php } else { ?>
                                            <a href="<?= USER ?>/register/" class="btn btn-blue btn-block">
                                                <i class="fa fa-shopping-cart"></i>
                                                <span>Jetzt registrieren</span>
                                            </a>
                                        <?php } ?>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    <?php if (user()) { ?>
                        <?php if (isset($info) && $info['status'] !== 'expired' && $info['status'] !== 'rejected') { ?>
                            <div class="packages_list__continue">
                                <form action="<?= USER ?>" method="POST">
                                    <input type="hidden" value="free">
                                    <button type="submit" class="btn btn-dark-outline">
                                        <span>Weiter ohne Bestellung </span>
                                        <i class="fa fa-arrow-right"></i>
                                    </button>
                                </form>
                            </div>
                        <?php } ?>
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