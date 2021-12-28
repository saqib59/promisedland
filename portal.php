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
    <title>Bildungsportal - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!-- Start . Section : Quote -->
    <section id="quote" class="sm">
        <div class="container">
            <div class="quote">
                <div class="quote_title">
                    <h4>Nichts macht Menschen misstrauischer als wenig zu wissen.</h4>
                    <p>-Fransis Bacon</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Start . Section : Quote -->

    <!-- Start . Section : Slider -->
    <section id="slider" class="no-gap">
        <div class="container">

            <div class="owl-carousel owl-theme portal_slider">
                <div class="item">
                    <div class="slider_item">
                        <img src="<?= LINK ?>/assets/img/edu/courses.jpg" class="d-block w-100" alt="...">
                        <div class="slider_item__body">
                            <div class="slider_item__body-inner">
                                <div class="slider_item__title">
                                    <h2>Video-Datenbank</h2>
                                </div>
                                <div class="slider_item__button">
                                    <a href="<?= LINK ?>/courses/" class="btn btn-white-outline right">
                                        <span>Mehr erfahren</span>
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="slider_item">
                        <img src="<?= LINK ?>/assets/img/edu/consulting.jpg" class="d-block w-100" alt="...">
                        <div class="slider_item__body">
                            <div class="slider_item__body-inner">
                                <div class="slider_item__title">
                                    <h2>Persönliche Beratung</h2>
                                </div>
                                <div class="slider_item__button">
                                    <a href="<?= LINK ?>/consultant/" class="btn btn-white-outline right">
                                        <span>Mehr erfahren</span>
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="slider_item">
                        <img src="<?= LINK ?>/assets/img/edu/advisor.jpg" class="d-block w-100" alt="...">
                        <div class="slider_item__body">
                            <div class="slider_item__body-inner">
                                <div class="slider_item__title">
                                    <h2>Versteigerungsratgeber</h2>
                                </div>
                                <div class="slider_item__button">
                                    <a href="<?= LINK ?>/process/" class="btn btn-white-outline right">
                                        <span>Mehr erfahren</span>
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="slider_item">
                        <img src="<?= LINK ?>/assets/img/edu/seminar.jpg" class="d-block w-100" alt="...">
                        <div class="slider_item__body">
                            <div class="slider_item__body-inner">
                                <div class="slider_item__title">
                                    <h2>Seminar</h2>
                                </div>
                                <div class="slider_item__button">
                                    <a href="<?= LINK ?>/seminar/" class="btn btn-white-outline right">
                                        <span>Mehr erfahren</span>
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="portal_slider">
                <div id="carouselIndicators" class="carousel slide" data-bs-ride="carousel">

                    <div class="carousel-inner">

                        <div class="carousel-item active">
                            <div class="slider_item">
                                <img src="https://trustbuilders.lk/wp-content/uploads/2020/12/128385633_3614693561902099_5304800848003653118_o-800x400.jpg" class="d-block w-100" alt="...">
                                <div class="slider_item__body">
                                    <div class="slider_item__title">
                                        <h2>Video-Datenbank</h2>
                                    </div>
                                    <div class="slider_item__button">
                                        <a class="btn btn-white-outline right" href="<?= LINK ?>/courses/">
                                            <span>Mehr erfahren</span>
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="slider_item">
                                <img src="https://www.lankapropertyweb.com/pics/419384/419384_1618654307_3971.jpg" class="d-block w-100" alt="...">
                                <div class="slider_item__body">
                                    <div class="slider_item__title">
                                        <h2>Persönliche Beratung</h2>
                                    </div>
                                    <div class="slider_item__button">
                                        <a class="btn btn-white-outline right" href="<?= LINK ?>/advisor/">
                                            <span>Mehr erfahren</span>
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="slider_item">
                                <img src="https://storeys.com/wp-content/uploads/2021/02/white-and-blue-wooden-house-3958954.jpg" class="d-block w-100" alt="...">
                                <div class="slider_item__body">
                                    <div class="slider_item__title">
                                        <h2>Fachliteratur / Ratgeber</h2>
                                    </div>
                                    <div class="slider_item__button">
                                        <a class="btn btn-white-outline right" href="<?= LINK ?>/consulting/">
                                            <span>Mehr erfahren</span>
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="slider_item">
                                <img src="https://storeys.com/wp-content/uploads/2021/02/white-and-blue-wooden-house-3958954.jpg" class="d-block w-100" alt="...">
                                <div class="slider_item__body">
                                    <div class="slider_item__title">
                                        <h2>Seminar</h2>
                                    </div>
                                    <div class="slider_item__button">
                                        <a class="btn btn-white-outline right" href="<?= LINK ?>/seminar/">
                                            <span>Mehr erfahren</span>
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div> -->

        </div>
    </section>
    <!-- End . Section : Slider -->



    <section id="eduportal" class="no-bot">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports">
                        <div class="reports_title">
                            <h4>Unser Bildungsportal</h4>
                        </div>
                        <div class="reports_body">
                            <p>Jeder eignet sich Wissen auf eine andere Art und Weise an. Bei uns hast du die Möglichkeit, zwischen Videos, persönlicher Beratung oder Fachliteratur zu wählen. Wenn du eine Kombination der drei Optionen bevorzugst, bieten wir dir ein passendes Seminar für dein Interessengebiet an.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports_img">
                        <img src="<?= LINK ?>/assets/img/portal.jpg">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="knowledge">
        <div class="container">

            <div class="reports">
                <div class="reports_title">
                    <h4>Welches Wissen vermitteln wir?</h4>
                </div>
                <div class="reports_body">
                    <p>Viele potenzielle Immobilienkäufer scheuen den Zwangsversteigerungsmarkt und lassen sich dadurch zahlreiche Investitionsmöglichkeiten entgehen. Um diese wahrnehmen zu können, ist ein grundlegendes Verständnis des Marktes mit seinen Chancen und Risiken erforderlich. Und an dieser Stelle setzen wir an.</p>
                    <p>Auch für Experten, die sich bereits lange mit diesem Thema befassen, bieten wir umfangreiche Materialien für die Weiterbildung.</p>
                    <p>Zukünftig werden wir das Bildungsportal immer weiter ausbauen: Vorteile von Immobilien aus steuerlicher Sicht, Dein ganz persönliches Know-How für Besichtigungstermine oder spezifisches Fachwissen von Maklern, die sich auf Gewerbeimmobilien spezialisiert haben - im Bildungsportal machen wir einen Immobilienexperten aus dir.</p>
                    <a href="<?= LINK ?>" class="btn btn-dark">Premium-Zugang ansehen</a>
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