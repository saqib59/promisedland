<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './config/config.php';

if (user()) {
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Gratis Webinar - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!-- Start . Section : Hero -->
    <section id="search" class="no-gap">
        <div class="search" style="background-image: url('<?= LINK ?>/assets/img/webinar/main.jpg');">
            <div class="container">

                <div class="row justify-content-center">
                    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="hero_advice">
                            <div class="home_search__title">
                                <span class="home_search__title-label">17. November 2021, 18:00 Uhr</span>
                                <h2 class="mb-4">Zwangsversteigerungen - <br>Gratis Webinar/ Fragestunde</h2>
                            </div>
                            <div class="webinar_register">

                                <?php if (user() && check_row($user, 'user', 'webinar')) { ?>
                                    <div class="webinar_register__alert">
                                        <div class="alert alert-success mb-0">Danke für deine Anmeldung für unser Webinar!</div>
                                    </div>
                                <?php } else { ?>
                                    <div class="webinar_register__form">
                                        <div class="webinar_register__alert"></div>
                                        <form id="webinar_register" action="<?= fullUrl() ?>" method="POST" autocomplete="off">
                                            <div class="form-group">
                                                <label>Stelle deine Frage, die im Seminar besprochen werden soll</label>
                                                <textarea name="question" placeholder="Deine Frage..." class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-dark">
                                                    <span>Jetzt registrieren und teilnehmen</span>
                                                    <i class="fa fa-sign-in-alt"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Hero -->

    <!-- Start . Section : Finance -->
    <section id="webinar">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="webinar">
                        <div class="row justify-content-center flex-column-reverse flex-lg-row align-items-center">
                            <div class="col-xl-7 col-lg-6 col-md-12 col-sm-12 col-12">
                                <div class="reports">
                                    <div class="reports_title">
                                        <h4>Dieter Schüll</h4>
                                    </div>
                                    <div class="reports_body">
                                        <p>Dieter Schüll ist langjähriger erfahrender Praktiker und gilt mit nahezu 45-jähriger Tätigkeit (zur Zeit Anwaltsbürovorsteher in einer Düsseldorfer Immobilienrechtskanzlei) als erfahrener Experte sowohl im Zwangsvollstreckungs- als auch Zwangsversteigerungsrecht und ist anerkannter Referent bei Handel, Banken, Anwaltskammern sowie Weiterbildungsakademien.</p>
                                        <p>Schüll beherrscht nicht nur die spezielle und vielfach komplizierte Gesetzesmaterie des Zwangsversteigerungsrechts aus dem „eff eff, kennt aber auch insbesondere „Tücken und Risiken“ der Schuldversteigerung bzw. Zwangsversteigerung zum Zwecke der Aufhebung einer Gemeinschaft (Teilungsversteigerung)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-12">
                                <div class="reports_img">
                                    <img src="<?= LINK ?>/assets/img/webinar/person.jpg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- End . Section : Finance -->


    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>
</body>

</html>