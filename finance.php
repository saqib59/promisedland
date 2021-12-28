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
    <title>Finanzierung - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!-- Start . Section : Title -->
    <section id="title" class="no-gap">
        <div class="page_header">
            <div class="container">
                <div class="page_header__title">
                    <h2>Finanzierung</h2>
                    <p>Zu welchen Bedingungen kannst du dir eine Finanzierung sichern? <br>Frage jetzt an kostenlos und unverbindlich</p>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Title -->

    <section id="banners" class="no-bot">
        <div class="container">
            <div class="finance_banner">
                <div class="finance_banner__item">
                    <h4>Professionelle Unterstützung</h4>
                    <p>Für eine kostenlose persönliche Beratung zur Immobilienfinanzierung.</p>
                    <a href="<?= LINK ?>/advisor/" class="btn btn-white">
                        <i class="fa fa-user-headset"></i>
                        <span>Zur Kostenlosen Erstberatung</span>
                    </a>
                </div>
                <div class="finance_banner__item">
                    <h4>Zinsrechner</h4>
                    <p>Zinskonditionen selbst berechnen.</p>
                    <a href="<?= LINK ?>/calculator/" class="btn btn-white white">
                        <i class="fa fa-calculator"></i>
                        <span>Berechnen</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- <section id="finance" class="no-bot">
        <div class="container">
            <div class="account_booking mb-0">
                <div class="row row-cols-xl-2 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 row-cols-1">
                    <div class="col">
                        <div class="account_booking__card">
                            <div class="account_booking__card-title">
                                <h4>Professionelle Unterstützung</h4>
                            </div>
                            <div class="account_booking__card-body">
                                <div class="abc_info">
                                    <p>Für eine kostenlose persönliche Beratung zur Immobilienfinanzierung.</p>
                                    <a href="#" class="btn btn-white">
                                        <i class="fa fa-user-headset"></i>
                                        <span>Get Now</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="account_booking__card">
                            <div class="account_booking__card-title">
                                <h4>Zinsrechner</h4>
                            </div>
                            <div class="account_booking__card-body">
                                <div class="abc_info">
                                    <p>Zinskonditionen selbst berechnen</p>
                                    <a href="#" class="btn btn-white">
                                        <i class="fa fa-calculator"></i>
                                        <span>Calculate</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <section id="finance" class="no-bot">
        <div class="container">
            <div class="row flex-md-row-reverse">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports_img">
                        <img src="<?= LINK ?>/assets/img/finance/1.jpg">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports">
                        <div class="reports_title">
                            <h4>1. Finanzierung bei Zwangsversteigerungen</h4>
                        </div>
                        <div class="reports_body">
                            <p>In der Finanzierung von Zwangsversteigerungsimmobilien gibt es eine viele Unterschiede zur Finanzierung einer normalen Immobilie. </p>
                            <p>Wenn eine Immobilie freihändig, also mit Makler oder von anderen Personen, gekauft wird, so steht eine Vielzahl an Dokumenten zur Verfügung. </p>
                            <p>Die Bank fordert u.a. eine Hausgeldabrechnung, einen Grundbuchauszug, einen Wirtschaftsplan und ebenso bestehende Mietverträge an. Hierbei sind nur 4 von 15 üblichen Dokumenten genannt. Diese stehen bei Zwangsversteigerungsimmobilien nicht zur Verfügung, oder doch?</p>
                            <p>Die Antwort ist, doch diese gibt es. Viele dieser Unterlagen wurden vorab vom Gutachter eingesehen und sind dementsprechend auch im Gutachten enthalten. </p>
                            <p>Sollte dies nicht der Fall sein, helfen wir dir dabei genau diese zu besorgen.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="finance" class="no-bot">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports_img">
                        <img src="<?= LINK ?>/assets/img/finance/2.jpg">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports">
                        <div class="reports_title">
                            <h4>2. Die Finanzierungskürze und die Besichtigung</h4>
                        </div>
                        <div class="reports_body">
                            <p>Weitere Herausforderungen stellen die Finanzierungskürze und eine unter Umständen nicht mögliche Innenbesichtigung der Immobilie dar. Einige Banken wollen die Immobilie besichtigen, bevor sie diese finanzieren. </p>
                            <p>Da das bei Zwangsversteigerungen kaum möglich ist, muss man genau die Banken suchen, die diese Besichtigung nicht oder erst im Nachgang durchführen. Aber auch die Finanzierungskürze stellt eine wesentliche Herausforderung bei der Zwangsversteigerung dar. </p>
                            <p>Zwischen dem Versteigerungstermin selbst und dem spätesten Zahlungstermin vergehen nur 6 Wochen. Es ist also kaum denkbar erst nach der Versteigerung die Finanzierungssuche aufzunehmen. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="finance">
        <div class="container">
            <div class="row flex-md-row-reverse">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports_img">
                        <img src="<?= LINK ?>/assets/img/finance/3.jpg">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports">
                        <div class="reports_title">
                            <h4>3. Warum sollte ich über euren Partner finanzieren?</h4>
                        </div>
                        <div class="reports_body">
                            <p>Die Antwort ist ganz einfach: Weil du dich um nichts kümmern musst. </p>
                            <p>Durch unseren engen Kontakt mit unserem Finanzierungspartner, helfen wir dir die gewünschten Unterlagen zu beschaffen (wie z.B. das Vollgutachten oder Abrechnungen der WEG). </p>
                            <p>Diese übersenden wir dann direkt an unseren Partner, damit du so wenig Aufwand wie möglich hast. </p>
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