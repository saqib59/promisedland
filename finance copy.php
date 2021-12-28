<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './config/config.php';
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

    <section id="secure" class="no-bot">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports">
                        <div class="reports_title">
                            <h4>Zu welchen Bedingungen kannst du dir eine Finanzierung sichern?</h4>
                        </div>
                        <div class="reports_body">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports_img">
                        <img src="<?= LINK ?>/assets/img/loop.jpg">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="advice" class="no-bot">
        <div class="container">
            <div class="row row-cols-xl-3 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1">

                <div class="col">
                    <div class="advice_box">
                        <div class="advice_box-icon">
                            <i class="fa fa-retweet"></i>
                        </div>
                        <div class="advice_box-info">
                            <h4>1. Für Zwangsversteigerungen <br>optimierte Beratung</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="advice_box">
                        <div class="advice_box-icon">
                            <i class="fa fa-bullseye-pointer"></i>
                        </div>
                        <div class="advice_box-info">
                            <h4>2. Individuell auf dich und deine <br>Ziele angepasst</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="advice_box">
                        <div class="advice_box-icon">
                            <i class="fa fa-university"></i>
                        </div>
                        <div class="advice_box-info">
                            <h4>3. Die Besten Konditionen <br>aus 400 verschiedenen Banken</h4>
                            <p>Um sicherzustellen, dass die richtige Wahl getroffen wird.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="contact" class="no-bot">
        <div class="container">
            <div class="finance">
                <div class="finance_title">
                    <div class="finance_title__inner">
                        <div class="finance_title__inner-left">
                            <h4>Professionelle Unterstützung</h4>
                            <p>für eine kostenlose persönliche Beratung zur Immobilienfinanzierung</p>
                        </div>
                        <div class="finance_title__inner-right">
                            <a href="<?= LINK ?>/advisor/" class="btn btn-white white">
                                <i class="fa fa-user-headset"></i>
                                <span>Get Now</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="finance_body">
                    <iframe src="https://promised-land-ug.drklein-plattform.de/apps/interest-tableau/01b8e51c-ec6f-490e-a951-8ddc49261fb7" title="Bauzinsenchart" frameborder="0" style="width: 100%; min-height: 900px;" onload="window.addEventListener('message', function (event) {if(event?.data?.type === 'drk-rechner.iframe.resized' && event.data.data.id === 'interest-tableau') {this.style.height = event.data.data.height + 'px'; this.style.minHeight = 0;}}.bind(this))"></iframe>
                </div>
            </div>
        </div>
    </section>

    <section id="calculator">
        <div class="container">
            <div class="finance">
                <div class="finance_title">
                    <h4>Zinsrechner</h4>
                    <p>Zinskonditionen selbst berechnen</p>
                </div>
                <div class="finance_body">
                    <iframe src="https://promised-land-ug.drklein-plattform.de/apps/construction-interest-calculator/5edc8a5f-6d93-4c28-a4d8-5627578feb82" title="Bauzinsrechner" frameborder="0" style="width: 100%; min-height: 900px;" onload="window.addEventListener('message', function (event) {if(event?.data?.type === 'drk-rechner.iframe.resized' && event.data.data.id === 'construction-interest-calculator') {this.style.height = event.data.data.height + 'px'; this.style.minHeight = 0;}}.bind(this))"></iframe>
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