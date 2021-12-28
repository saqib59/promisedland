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
    <title>Persönliche Beratung - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->


    <!-- Start . Section : Hero -->
    <section id="search" class="no-gap">
        <div class="search" style="background-image: url('<?= LINK ?>/assets/img/pb/land_bg.jpg');">
            <div class="container">

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-10 col-sm-12 col-12">
                        <div class="hero_advice">
                            <div class="home_search__title">
                                <h2>Persönliche Beratung</h2>
                                <p>Zwangsversteigerungen sind ein komplexes Thema. Gerne begleiten wir dich auf dem Weg zu deiner Immobilie.</p>
                                <a onclick="return gtag_report_conversion_Consultant('<?= LINK ?>/contact/');" href="<?= LINK ?>/contact/" class="btn btn-dark">
                                    <span>Kontaktiere uns</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Hero -->

    <!-- Start . Section : Features -->
    <section id="pb_features">
        <div class="pb_features">
            <div class="container">
                <div class="row row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1 justify-content-center">
                    <div class="col">
                        <div class="pb_feature">
                            <div class="pb_features__icon">
                                <img src="<?= LINK ?>/assets/img/pb/innovation.png" alt="">
                            </div>
                            <div class="pb_features__info">
                                <h4>Innovative Ansätze</h4>
                                <p>Durch laufende Fortbildung bleiben unsere Mitarbeiter immer auf dem aktuellsten Stand.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="pb_feature">
                            <div class="pb_features__icon">
                                <img src="<?= LINK ?>/assets/img/pb/team.png" alt="">
                            </div>
                            <div class="pb_features__info">
                                <h4>Expertise</h4>
                                <p>Unsere Berater kennen Zwangsversteigerungsverfahren wie kein anderer.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="pb_feature">
                            <div class="pb_features__icon">
                                <img src="<?= LINK ?>/assets/img/pb/support.png" alt="">
                            </div>
                            <div class="pb_features__info">
                                <h4>Zu Hause oder vor Ort</h4>
                                <p>Wir bieten sowohl digitale als auch physische Termine an.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Features -->

    <!-- Start . Section : About -->
    <section id="pb_aboutus" class="no-bot">
        <div class="pb_aboutus">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="pb_about__video">
                            <div class="pb_video__body">
                                <img src="<?= LINK ?>/assets/img/pb/about.jpg">
                            </div>
                            <div class="pb_experience">
                                <div class="pb_exp__box">
                                    <div class="pb_exp__icon">
                                        <img src="<?= LINK ?>/assets/img/pb/expert.png" alt="">
                                    </div>
                                    <div class="pb_exp__info">
                                        <h4>6000</h4>
                                        <p>Gutachten</p>
                                    </div>
                                </div>
                                <div class="pb_exp__box">
                                    <div class="pb_exp__icon">
                                        <img src="<?= LINK ?>/assets/img/pb/years.png" alt="">
                                    </div>
                                    <div class="pb_exp__info">
                                        <h4>300</h4>
                                        <p>Erfolgreiche Verfahren</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="pb_about">
                            <div class="pb_about__heading">
                                <h4>Über Uns</h4>
                                <h2>Immobilienversteigerungen sind unser Steckenpferd</h2>
                            </div>
                            <div class="pb_about__desc">
                                <p>Wir beraten dich gerne zu deinen Fragen rund um Zwangsversteigerungen. Unsere Berater haben Erfahrung mit über 6000 Verkehrswertgutachten und können dir so bei deinen Fragen helfen.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : About -->

    <!-- Start . Section : Services -->
    <section id="pb_services">
        <div class="pb_services">
            <div class="container">
                <div class="row align-items-center row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1">
                    <div class="col">
                        <div class="pb_heading__box">
                            <div class="pb_service_heading">
                                <h4>Unsere Services</h4>
                                <h2>Beratung</h2>
                            </div>
                            <div class="pb_service__desc">
                                <p>Wir bieten Beratung zu den verschiedenen Bereichen der Versteigerung an. Buche jetzt dein Erstgespräch für 99 € plus Mehrwertsteuer.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="pb_service__box">
                            <div class="pb_service__icon">
                                <img src="<?= LINK ?>/assets/img/pb/investment.png" alt="">
                            </div>
                            <div class="pb_service__info">
                                <h4>Vorverhandlung</h4>
                                <p>Gerne helfen wir dir bereits vorab mit dem Gläubiger in Kontakt zu treten und das Objekt möglicherweise zu erwerben.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="pb_service__box">
                            <div class="pb_service__icon">
                                <img src="<?= LINK ?>/assets/img/pb/evaluation.png" alt="">
                            </div>
                            <div class="pb_service__info">
                                <h4>Gutachtendurchsicht</h4>
                                <p>Du hast spezifische Fragen zum Gutachten oder willst wissen, welche Fallsticke sich hier ergeben? Gerne helfen wir dir hierbei.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="pb_service__box">
                            <div class="pb_service__icon">
                                <img src="<?= LINK ?>/assets/img/pb/chart-up.png" alt="">
                            </div>
                            <div class="pb_service__info">
                                <h4>Versteigerungsbegleitung</h4>
                                <p>Unser Partneranwalt begleitet dich deutschlandweit zu deinem Termin. Preise auf Anfrage.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="pb_service__box">
                            <div class="pb_service__icon">
                                <img src="<?= LINK ?>/assets/img/pb/support.png" alt="">
                            </div>
                            <div class="pb_service__info">
                                <h4>Finanzierungsvermittlung</h4>
                                <p>Wir helfen dir dabei, den richtigen Finanzierungsplan zu finden, der auch wirklich zu deiner Situation passt.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="pb_service__box">
                            <div class="pb_service__icon">
                                <img src="<?= LINK ?>/assets/img/pb/planning.png" alt="">
                            </div>
                            <div class="pb_service__info">
                                <h4>Anwaltliche Erstberatung</h4>
                                <p>Wenn du eine anwaltliche Erstberatung für deine Fragen wünschst, vermitteln wir dir gerne den passenden Ansprechpartner.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Services -->

    <!-- Start . Section : Advice -->
    <section id="pb_advice" class="light">
        <div class="pb_advice">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="pb_advice__info">
                            <div class="pb_advice__heading">
                                <h2>Warum Beratung wichtig ist</h2>
                                <p>Das Thema Immobiliarversteigerung ist für die meisten Menschen Neuland. Daher kann guter Rat hier viel Geld sparen, denn wenn du ein Haus für mehrere Hundert tausend Euro kaufen willst, solltest du Fehler vermeiden.</p>
                            </div>
                            <div class="pb_advice__body">
                                <div class="pb_advice__list">
                                    <ul>
                                        <li><i class="fa fa-check"></i> Unterstützung bei der Objektsuche</li>
                                        <li><i class="fa fa-check"></i> Finanzierungsberatung</li>
                                        <li><i class="fa fa-check"></i> Gutachten-Situationsanalyse</li>
                                        <li><i class="fa fa-check"></i> Umfangreiche Beratungsleistungen</li>
                                    </ul>
                                </div>
                                <div class="pb_advice__btn">
                                    <a onclick="return gtag_report_conversion_Consultant('<?= LINK ?>/contact/');" href="<?= LINK ?>/contact/" class="btn btn-dark right">
                                        <span>Kontaktiere uns</span>
                                        <i class="fa fa-phone-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="pb_advice__right">
                            <div class="pb_advice__img">
                                <img src="<?= LINK ?>/assets/img/pb/advice.jpg" alt="">
                            </div>
                            <div class="pb_advice__quote">
                                <h6><span>"</span> Wer neue Antworten will, muss neue Fragen stellen. <span>"</span></h6>
                                <p>-Johann Wolfgang Goethe-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Advice -->


    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>



</body>

</html>