<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './config/config.php';

//contentRestric(array('plus'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>4rent - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->


    <!-- Start . Section : Hero -->
    <section id="land_hero" class="no-bot">
        <div class="land_hero">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="home_search__title">
                            <h2>Mietermanagement leicht gemacht</h2>
                            <p>Spare Zeit und Geld mit 4Rent. Mit uns automatisierst du deine Vermietungsangelegenheiten.</p>
                            <a href="#" class="btn btn-dark right">
                                <span>Coming soon!</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="land_hero__img">
                            <img src="https://4rent.alcaline.lk/assets/images/cover/about.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Hero -->

    <!-- Start . Section : Features -->
    <section id="land_features" class="no-bot">
        <div class="land_features">
            <div class="container">
                <div class="row row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1 justify-content-center">
                    <div class="col">
                        <div class="land_feature__panel">
                            <div class="land_feature___icon">
                                <img src="https://4rent.alcaline.lk/assets/images/icons/icon-code.svg" alt="">
                            </div>
                            <div class="land_feature__content">
                                <h4>Smartes ImmoManagement</h4>
                                <p>Im Dashboard bekommst du einen Überblick über alles rund um deine Immobilien. Egal ob es um Zahlungseingänge, Nachrichten oder Besichtigungstermine geht. </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="land_feature__panel">
                            <div class="land_feature___icon">
                                <img src="https://4rent.alcaline.lk/assets/images/icons/icon-paint.svg" alt="">
                            </div>
                            <div class="land_feature__content">
                                <h4>Vertragsgenerator</h4>
                                <p>Wir bieten dir umfangreiche Mietverträge sowie Schriftsätze die du zum vermieten brauchst. Du entscheidest: Herunterladen oder online ausfüllen und automatisch an den Mieter senden. </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="land_feature__panel">
                            <div class="land_feature___icon">
                                <img src="https://4rent.alcaline.lk/assets/images/icons/icon-screen.svg" alt="">
                            </div>
                            <div class="land_feature__content">
                                <h4>Schluss mit hohen Kosten</h4>
                                <p>Mieterverwaltung muss nicht teuer sein. Bei uns verwaltest du deine Objekte bereits ab 2&euro; pro Monat. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Features -->

    <!-- Start . Section : Video -->
    <section id="land_video" class="no-bot">
        <div class="container">
            <div class="land_video">
                <video width="100%" autoplay loop muted>
                    <source src="<?= LINK ?>/assets/vids/immo.webm" type="video/webm">
                    Your browser does not support HTML video.
                </video>

                <!-- <div class="container">
                <div class="col-12">
                    <div class="land course_title__img">
                        <img src="https://www.wallpapertip.com/wmimgs/0-6583_firewatch-screenshot.jpg">

                        <div class="land_video__body">
                            <div class="land_video__img-play" data-title="Intro: Check is it worthy" data-intro="<?= LINK ?>/assets/course/intro/442881629199056.mp4">
                                <i class="fa fa-play"></i>
                            </div>
                            <div class="land_video_txt">
                                <h4>Watch Video</h4>
                            </div>
                        </div>

                    </div>

                </div>
            </div> -->

            </div>
        </div>
    </section>
    <!-- End . Section : Video -->

    <!-- Start . Section : About -->
    <section id="land_about" class="no-bot">
        <div class="land_about">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="land_ab__img">
                            <img src="<?= LINK ?>/assets/img/stars.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="land_ab__body">
                            <div class="land_heading">
                                <h2>Werde unser Kunde</h2>
                                <p>3 Gründe warum du 4Rent nutzen solltest</p>
                            </div>
                            <div class="land_ab__body">
                                <div class="land_ab__list">
                                    <div class="land_ab__icon">
                                        <i class="fas fa-sack"></i>
                                    </div>
                                    <div class="land_ab__desc">
                                        <h4>Kostenloser Gratismonat</h4>
                                        <p>Probiere alle Services von 4Rent einen Monat lang kostenlos aus.</p>
                                    </div>
                                </div>
                                <div class="land_ab__list">
                                    <div class="land_ab__icon">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                    <div class="land_ab__desc">
                                        <h4>Ich habe bereits ein Abo bei einem anderen Anbieter</h4>
                                        <p>Gar kein Problem! Sende uns die Kündigungsbestätigung zu und wir erlassen dir 3 Monate die Abogebühr. </p>
                                    </div>
                                </div>
                                <div class="land_ab__list">
                                    <div class="land_ab__icon">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <div class="land_ab__desc">
                                        <h4>Treue zahlt sich aus</h4>
                                        <p>Im Jahresabo sparst du 20% im Vergleich zur monatlichen Zahlung. </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : About -->

    <!-- Start . Section : Gallery -->
    <!-- <section id="land_gallery" class="no-bot">
        <div class="land_gallery">
            <div class="container">
                <div class="land_gallery__title">
                    <h2>Unser Servicepaket</h2>
                    <p>Ein kurze Beschreibung unser Kernfunktionen</p>
                </div>

                <div class="owl-carousel owl-theme land_page__slider">
                    <div class="item">
                        <div class="land_gallery__box">
                            <div class="land_gallery__img">
                                <img src="https://cdn.pixabay.com/photo/2017/07/17/00/58/coffee-2511065_960_720.jpg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="land_gallery__box">
                            <div class="land_gallery__img">
                                <img src="https://cdn.pixabay.com/photo/2017/07/17/00/58/coffee-2511065_960_720.jpg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="land_gallery__box">
                            <div class="land_gallery__img">
                                <img src="https://cdn.pixabay.com/photo/2017/07/17/00/58/coffee-2511065_960_720.jpg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="land_gallery__box">
                            <div class="land_gallery__img">
                                <img src="https://cdn.pixabay.com/photo/2017/07/17/00/58/coffee-2511065_960_720.jpg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- End . Section : Gallery -->

    <!-- Start . Section : Pricing -->
    <section id="land_price">
        <div class="land_price">
            <div class="container">
                <div class="land_gallery__title text-center">
                    <h2>Die Preise</h2>
                    <p>Wähle ein auf dich zugeschnittenes Angebot</p>
                </div>

                <!-- Start Pricing Navigation -->
                <div class="land_menu">
                    <ul class="nav price-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-monatlich-tab" data-bs-toggle="pill" data-bs-target="#pills-monatlich" type="button" role="tab" aria-controls="pills-monatlich" aria-selected="true">Monatlich</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-jährlich-tab" data-bs-toggle="pill" data-bs-target="#pills-jährlich" type="button" role="tab" aria-controls="pills-jährlich" aria-selected="false">Jährlich</button>
                            <div class="show_discount__label">Sparen Sie 20%!</div>
                        </li>
                    </ul>
                    <div class="show_discount__label mobile">Sparen Sie 20%!</div>
                </div>
                <!-- End Pricing Navigation -->
                <div class="tab-content" id="pills-tabContent">

                    <div class="tab-pane fade show active" id="pills-monatlich" role="tabpanel" aria-labelledby="pills-monatlich-tab">
                        <!-- Pricing Tab -->
                        <div class="pricing_boxes">
                            <div class="row row-cols-xl-4 row-cols-lg-4 row-cols-md-2 row-cols-sm-2 row-cols-1">

                                <div class="col">
                                    <div class="land_price__box">
                                        <div class="land_price__icon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <div class="land_price_top">
                                            <div class="land_price_title">
                                                <h5>STARTER</h5>
                                            </div>
                                            <div class="land_price__list">
                                                <ul>
                                                    <li>bis zu 2 Objekte</li>
                                                    <li>Alle Funktionen</li>
                                                    <li>unbegrenzte Mieter</li>
                                                    <li>30 Verträge inbegriffen</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="land_price_bot">
                                            <div class="land_price__value">
                                                <h2><sup>&euro;</sup>9</h2>
                                                <p>/pro Monat</p>
                                            </div>
                                            <div class="land_price__btn">
                                                <a href="#" class="btn btn-dark btn-sm">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    <span>Hier lang</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="land_price__box">
                                        <div class="land_price__icon">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <div class="land_price_top">
                                            <div class="land_price_title">
                                                <h5>EXPERIENCED</h5>
                                            </div>
                                            <div class="land_price__list">
                                                <ul>
                                                    <li>bis zu 10 Objekte</li>
                                                    <li>Alle Funktionen</li>
                                                    <li>unbegrenzte Mieter</li>
                                                    <li>40 Verträge inbegriffen</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="land_price_bot">
                                            <div class="land_price__value">
                                                <h2><sup>&euro;</sup>39</h2>
                                                <p>/pro Monat</p>
                                            </div>
                                            <div class="land_price__btn">
                                                <a href="#" class="btn btn-dark btn-sm">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    <span>Hier lang</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="land_price__box">
                                        <div class="land_price__icon">
                                            <i class="fa fa-building"></i>
                                        </div>
                                        <div class="land_price_top">
                                            <div class="land_price_title">
                                                <h5>PROFESSIONAL</h5>
                                            </div>
                                            <div class="land_price__list">
                                                <ul>
                                                    <li>bis zu 50 Objekte</li>
                                                    <li>Alle Funktionen</li>
                                                    <li>unbegrenzte Mieter</li>
                                                    <li>40 Verträge inbegriffen</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="land_price_bot">
                                            <div class="land_price__value">
                                                <h2><sup>&euro;</sup>99</h2>
                                                <p>/pro Monat</p>
                                            </div>
                                            <div class="land_price__btn">
                                                <a href="#" class="btn btn-dark btn-sm">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    <span>Hier lang</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="land_price__box">
                                        <div class="land_price__icon">
                                            <i class="fa fa-city"></i>
                                        </div>
                                        <div class="land_price_top">
                                            <div class="land_price_title">
                                                <h5>HIGH LEVEL INVESTOR</h5>
                                            </div>
                                            <div class="land_price__list">
                                                <ul>
                                                    <li>über 50 Objekte </li>
                                                    <li>Alle Funktionen</li>
                                                    <li>unbegrenzte Mieter</li>
                                                    <li>40 Verträge inbegriffen</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="land_price_bot">
                                            <div class="land_price__value">
                                                <h2>auf Anfrage</h2>
                                                <p class="hype">.</p>
                                            </div>
                                            <div class="land_price__btn">
                                                <a href="#" class="btn btn-dark btn-sm">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    <span>Hier lang</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-jährlich" role="tabpanel" aria-labelledby="pills-jährlich-tab">
                        <!-- Pricing Tab -->
                        <div class="pricing_boxes">
                            <div class="row row-cols-xl-4 row-cols-lg-4 row-cols-md-2 row-cols-sm-2 row-cols-1">

                                <div class="col">
                                    <div class="land_price__box">
                                        <div class="land_price__icon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <div class="land_price_top">
                                            <div class="land_price_title">
                                                <h5>STARTER</h5>
                                            </div>
                                            <div class="land_price__list">
                                                <ul>
                                                    <li>bis zu 2 Objekte</li>
                                                    <li>Alle Funktionen</li>
                                                    <li>unbegrenzte Mieter</li>
                                                    <li>30 Verträge inbegriffen</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="land_price_bot">
                                            <div class="land_price__value">
                                                <h2><sup>&euro;</sup>7.20</h2>
                                                <p>/pro Monat</p>
                                            </div>
                                            <div class="land_price__btn">
                                                <a href="#" class="btn btn-dark btn-sm">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    <span>Hier lang</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="land_price__box">
                                        <div class="land_price__icon">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <div class="land_price_top">
                                            <div class="land_price_title">
                                                <h5>EXPERIENCED</h5>
                                            </div>
                                            <div class="land_price__list">
                                                <ul>
                                                    <li>bis zu 10 Objekte</li>
                                                    <li>Alle Funktionen</li>
                                                    <li>unbegrenzte Mieter</li>
                                                    <li>40 Verträge inbegriffen</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="land_price_bot">
                                            <div class="land_price__value">
                                                <h2><sup>&euro;</sup>31.20</h2>
                                                <p>/pro Monat</p>
                                            </div>
                                            <div class="land_price__btn">
                                                <a href="#" class="btn btn-dark btn-sm">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    <span>Hier lang</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="land_price__box">
                                        <div class="land_price__icon">
                                            <i class="fa fa-building"></i>
                                        </div>
                                        <div class="land_price_top">
                                            <div class="land_price_title">
                                                <h5>PROFESSIONAL</h5>
                                            </div>
                                            <div class="land_price__list">
                                                <ul>
                                                    <li>bis zu 50 Objekte</li>
                                                    <li>Alle Funktionen</li>
                                                    <li>unbegrenzte Mieter</li>
                                                    <li>40 Verträge inbegriffen</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="land_price_bot">
                                            <div class="land_price__value">
                                                <h2><sup>&euro;</sup>79.20</h2>
                                                <p>/pro Monat</p>
                                            </div>
                                            <div class="land_price__btn">
                                                <a href="#" class="btn btn-dark btn-sm">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    <span>Hier lang</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="land_price__box">
                                        <div class="land_price__icon">
                                            <i class="fa fa-city"></i>
                                        </div>
                                        <div class="land_price_top">
                                            <div class="land_price_title">
                                                <h5>HIGH LEVEL INVESTOR</h5>
                                            </div>
                                            <div class="land_price__list">
                                                <ul>
                                                    <li>über 50 Objekte </li>
                                                    <li>Alle Funktionen</li>
                                                    <li>unbegrenzte Mieter</li>
                                                    <li>40 Verträge inbegriffen</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="land_price_bot">
                                            <div class="land_price__value">
                                                <h2>auf Anfrage</h2>
                                                <p class="hype">.</p>
                                            </div>
                                            <div class="land_price__btn">
                                                <a href="#" class="btn btn-dark btn-sm">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    <span>Hier lang</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Pricing -->


    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>

</body>

</html>