<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './config/config.php';

$listings = $db->query("SELECT * FROM listing WHERE completed = 1 AND featured = 1;")->fetchAll();
//$listings = $db->query("SELECT * FROM listing WHERE completed = 1 LIMIT 0, 6;")->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Home - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!-- Start . Section : Search -->
    <section id="search" class="no-gap">
        <div class="search">
            <div class="container">

                <div class="row justify-content-between">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="home_search__title">
                            <h2>Die Nr. 1 bei Zwangsversteigerungen</h2>
                            <p>Der One-Stop-Shop für den Nischenmarkt des Immobiliensektors. Wie begleiten dich von Anfang bis Ende.</p>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12">
                        <div class="home_search__box">
                            <form action="<?= LINK ?>/search/" method="GET" autocomplete="off">

                                <!-- Start . Search Filters -->
                                <div class="home_search__box-form">

                                    <div class="home_search__box-form-item">
                                        <input id="searchInput" class="form-control" type="text" name="address" placeholder="Adresse (Wo: Stadt, PLZ, Straße)">
                                    </div>

                                    <div class="home_search__box-form-item">
                                        <select name="category" class="form-select">
                                            <option value="">- Objektart -</option>
                                            <option value="Eigentumswohnungen">Eigentumswohnungen</option>
                                            <option value="Mehrfamilienhäuser">Mehrfamilienhäuser</option>
                                            <option value="Wohn-/ Geschäftshäuser">Wohn-/ Geschäftshäuser</option>
                                            <option value="Gewerbegrundstücke">Gewerbegrundstücke</option>
                                            <option value="Einfamilienhäuser">Einfamilienhäuser</option>
                                            <option value="Unbebaute Grundstücke">Unbebaute Grundstücke</option>
                                            <option value="Land- und forstwirtschaftlich genutzte Flächen">Land- und forstwirtschaftlich genutzte Flächen</option>
                                            <option value="Baugrundstücke">Baugrundstücke</option>
                                            <option value="KFZ-Stellplatz/ Garagen">KFZ-Stellplatz/ Garagen</option>
                                            <option value="Zweifamilienhaus">Zweifamilienhaus</option>
                                            <option value="Sonstige">Sonstige</option>
                                        </select>
                                    </div>

                                    <div class="home_search__box-form-item">
                                        <div class="row row-sm">
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                <div class="home_search__box-form-item--inner">
                                                    <div class="home_search__box-form-item--inner---label">
                                                        <label>Wohnfläche</label>
                                                    </div>
                                                    <div class="home_search__box-form-item--inner---inputs">
                                                        <div class="colu">
                                                            <input type="number" name="living_space_from" id="living_space_from" class="form-control" placeholder="Von">
                                                        </div>
                                                        <div class="colu">
                                                            <input type="number" name="living_space_to" id="living_space_to" class="form-control" placeholder="Bis">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                <div class="home_search__box-form-item--inner">
                                                    <div class="home_search__box-form-item--inner---label">
                                                        <label>Zimmer</label>
                                                    </div>
                                                    <div class="home_search__box-form-item--inner---inputs">
                                                        <div class="colu">
                                                            <input type="number" name="room_count_from" id="room_count_from" class="form-control" placeholder="Von">
                                                        </div>
                                                        <div class="colu">
                                                            <input type="number" name="room_count_to" id="room_count_to" class="form-control" placeholder="Bis">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="home_search__box-form-item">
                                        <select name="value_count" id="value_count" class="form-select">
                                            <option value="">- Wertgrenze -</option>
                                            <option value="5/10">5/10</option>
                                            <option value="7/10">7/10</option>
                                            <option value="Wertgrenzen entfallen">Wertgrenzen entfallen</option>
                                        </select>
                                    </div>

                                </div>
                                <!-- End . Search Filters -->

                                <!-- Start . Search Buttons -->
                                <div class="search_form_buttons">
                                    <button type="submit" class="btn btn-dark">
                                        <i class="fa fa-search"></i>
                                        <span>Suchen</span>
                                    </button>
                                    <button type="submit" class="btn btn-blue right">
                                        <span>Suchauftrag anlegen</span>
                                        <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                                <!-- End . Search Buttons -->

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="space"></div>
    </section>
    <!-- End . Section : Search -->

    <!-- Start . Section : Top Listing -->
    <section id="listing">
        <div class="container">
            <div class="top-listing">
                <div class="row row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1">

                    <?php if (isset($listings) && !empty($listings)) { ?>
                        <?php foreach ($listings as $item) { ?>
                            <?php
                            $listing_id = $item['id'];

                            $loop_featured = $item['featured'];
                            $loop_report = $item['report_available'];

                            $loop_label = $item['listing_label'];
                            $loop_date = $item['foreclosure_date'];

                            $loop_slug = $item['listing_slug'];
                            $loop_price = $item['object_val'];
                            $loop_address = $item['object_address'];
                            $loop_desc = $item['object_desc'];

                            $loop_catergory = $item['new_cat'];

                            $detailsData = $db->query('SELECT * FROM `details` WHERE `listing_id` = ?', $listing_id)->fetchArray();
                            $aboutData = $db->query('SELECT * FROM `about` WHERE `listing_id` = ?', $listing_id)->fetchArray();

                            if ($detailsData && !empty($detailsData)) {
                                $loop_title = $detailsData['about_type'];
                                $loop_rooms = $detailsData['listing_rooms'];

                                $loop_units = $detailsData['listing_flats'];
                                $loop_owner = $detailsData['listing_ownership'];
                                $loop_limit = $detailsData['value_limit'];

                                $loop_equip = $detailsData['listing_equipment'];
                            }


                            if ($aboutData && !empty($aboutData)) {
                                $loop_space = $aboutData['living_space'];
                                $loop_use = $aboutData['use_space'];
                                $loop_plot = $aboutData['plot_area'];
                                $loop_earn_month = $aboutData['earn_month'];

                                $loop_demolished = $aboutData['demolished'];
                            }

                            ?>
                            <div class="col">
                                <?php include HOME . '/inc/layout/list.php'; ?>
                            </div>
                        <?php } ?>
                    <?php } ?>

                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Top Listing -->

    <!-- Start . Section : Intro -->
    <section id="intro" class="large dark">
        <div class="intro_box left">
            <div class="intro_box-inside"></div>
        </div>
        <div class="intro_box right">
            <div class="intro_box-inside"></div>
        </div>
        <div class="container">
            <div class="intro">
                <div class="intro_logo">
                    <img src="<?= LINK ?>/assets/img/logo.png">
                </div>
                <div class="intro_list">
                    <ul>
                        <li>Grundlagenkurse - Zwangsversteigerungen</li>
                        <li>Gutachtenanalyse mit Hilfe künstlicher Intelligenz</li>
                        <li>Renditeprognosen als Kaufentscheidungshilfe</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Intro -->

    <!-- Start . Section : Reports -->
    <section id="reports">
        <div class="container">
            <div class="row flex-md-row-reverse">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports_video">
                        <video width="100%" autoplay loop muted>
                            <source src="<?= LINK ?>/assets/vids/ai.webm" type="video/webm">
                            Your browser does not support HTML video.
                        </video>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports">
                        <div class="reports_title">
                            <h4>Gutachtenanalyse durch künstliche Intelligenz</h4>
                        </div>
                        <div class="reports_body">
                            <p>Mit der Hilfe unserer KI analysieren wir das Papierchaos. Die KI erkennt wiederkehrende Strukturen, definiert gängige Textphrasen und gibt dir so Informationen wie beispielsweise die Baumängel der Immobilie aus.</p>
                            <p>Dadurch ist es uns möglich dir innerhalb von 3 Minuten einen Überblick über die Chancen und Risiken der Immobilie zu verschaffen.</p>
                            <a href="<?= LINK ?>/search/" class="btn btn-dark">Zur Objektsuche</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Reports -->

    <!-- Start . Section : Manager -->
    <section id="manager" class="dark">
        <div class="container">
            <div class="manager">
                <div class="row align-items-center">
                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                        <div class="manager_title">
                            <h4>ImmoManager</h4>
                            <p>Alle Formulare, Immobilien und Mieter an einem Ort.</p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="manager_btn">
                            <a href="<?= LINK ?>/immomanager/" class="btn btn-white white">ImmoManager</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Manager -->

    <!-- Start . Section : education -->
    <section id="education" class="no-bot">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports_video">
                        <video width="100%" autoplay loop muted>
                            <source src="<?= LINK ?>/assets/vids/portal.webm" type="video/webm">
                            Your browser does not support HTML video.
                        </video>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports">
                        <div class="reports_title">
                            <h4>Bildungsportal</h4>
                        </div>
                        <div class="reports_body">
                            <p>Für Experten und Laien. Hier erfährst du alles über Zwangsversteigerungen. Angefangen bei den allgemeinen Abläufen und Formalitäten, über Tipps zum Vorgehen bei der Suche bis hin zur optimalen Strategie beim Bieten.</p>
                            <p>Hier efährst du alles Wissenswerte</p>
                            <a href="<?= LINK ?>/portal/" class="btn btn-dark">Zum Bildungsportal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Manager -->

    <!-- Start . Section : Finance -->
    <section id="finance">
        <div class="container">
            <div class="row flex-md-row-reverse">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports_img">
                        <img src="<?= LINK ?>/assets/img/finance.jpg">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="reports">
                        <div class="reports_title">
                            <h4>Die passende Finanzierung finden</h4>
                        </div>
                        <div class="reports_body">
                            <p>Weil Banken vorsichtig mit der Finanzierung Objekten aus den Versteigerungen sind und die Zeit zwischen Sicherheitsleistung und Verteilungstermin bei nur 6-8 Wochen liegt, ist es wichtig die passende Finanzierung zu den besten Konditionen zu finden. Bei unserem Partner Dr. Klein bist du damit in besten Händen.</p>
                            <ul>
                                <li>Dr. Klein als unser Finanzierungspartner</li>
                                <li>400 Banken im Vergleich</li>
                                <li>Finanzierung auf Zwangsversteigerungen spezialisiert</li>
                            </ul>
                            <a href="<?= LINK ?>/finance/" class="btn btn-dark">Zur Finanzierung</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Finance -->

    <!-- Start . Section : About -->
    <section id="about" class="image" style="background-image: url(<?= LINK ?>/assets/img/about_us.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"></div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="about">
                        <div class="about_title">
                            <h4>Über uns</h4>
                            <p>Die Nr. 1 bei Zwangsversteigerungen</p>
                        </div>
                        <div class="about_body">
                            <p>Wir sind ein aufstrebendes Start-Up aus Frankfurt am Main, welches auf der Mission ist, den veralteten Zwangsversteigerungsmarkt zu modernisieren.</p>
                            <p>Mit unseren digitalen Lösungen wollen wir unseren Kunden stets aktuelle und jederzeit abrufbare Informationen bieten. Unsere Dienstleistungen zeichnen sich dadurch aus, dass sie für jeden geeignet sind, egal ob Experte oder Neu-Interessent.</p>
                            <p>Wir geben unser Bestes dafür, dich bestmöglich auf dem Weg zur Immobilie und darüber hinaus zu begleiten.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : About -->

    <div class="home_map">
        <div class="home_map__inner">
            <div id="map"></div>
        </div>
    </div>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>

</body>

</html>