<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Einstellungen - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <section id="account">
        <div class="account">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <?php include HOME . '/inc/account/sidebar.php'; ?>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                        <div class="account_body">
                            <section id="manage" class="no-gap">

                                <div class="account_body__title">
                                    <h4>Kontoeinstellungen</h4>
                                    <p>Hier kannst du dein Konto einstellen. </p>
                                </div>

                                <div class="account_body__content">

                                    <div class="account_manage">

                                        <div class="account_manage__box">
                                            <div class="manage_title">
                                                <h4>Konoteinstellungen verwalten</h4>
                                            </div>
                                            <div class="manage_info">
                                                <ul>
                                                    <li><a href="<?= LINK ?>/user/change_details/">Details ändern</a></li>
                                                    <li><a href="<?= LINK ?>/user/change_credentials/">Anmeldeinformationen ändern</a></li>
                                                    <li><a href="<?= LINK ?>/user/delete_account/">Konto löschen</a></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="account_manage__box gray">
                                            <div class="account_booking__card-ribbon">
                                                <span>Coming Soon</span>
                                            </div>
                                            <div class="manage_title">
                                                <h4>Mitgliedschaft verwalten</h4>
                                            </div>
                                            <div class="manage_info">
                                                <p>Mit der Premium-Mitgliedschaft erhältst du exklusive Unterstützung auf deinem Weg zum Bieten und darüber hinaus.</p>
                                            </div>
                                        </div>

                                        <div class="account_manage__box gray">
                                            <div class="account_booking__card-ribbon">
                                                <span>Coming Soon</span>
                                            </div>
                                            <div class="manage_title">
                                                <h4>Meine Rechnungen</h4>
                                            </div>
                                            <div class="manage_info">
                                                <p>Eine Rechnung ist nicht mehr in deinem E-Mail-Postfach auffindbar? Hier erhältst du einen Überblick über die Rechnungen der letzten 12 Monate.</p>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>
</body>

</html>