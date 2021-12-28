<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

if (isset($_SESSION['email'])) {
    unset($_SESSION['email']);
}

$dates = array();

$favorites = $db->query("SELECT * FROM `favorite` WHERE `user_id` = ?;", $user)->fetchAll();
if (!empty($favorites)) {
    foreach ($favorites as $item) {
        $listing_id = $item["listing_id"];

        $listings = $db->query("SELECT * FROM `listing` WHERE `id` = ?;", $listing_id)->fetchArray();
        if ($listings && !empty($listings)) {
            $listing_label = $listings['listing_label'];
            $listing_slug = $listings['listing_slug'];
            $listing_day = $listings['foreclosure_date'];

            if (!empty($listing_day)) {
                //$dates[] = '"' . dayComma($listing_day) . '"';
                $dates[] = array(
                    'Org' => $listing_day,
                    'Date' => dayComma($listing_day),
                    'Title' => 'Listing: ' . $listing_label,
                    'Link' => LINK . '/listing/' . $listing_slug,
                );
            }
        }
    }
}

$orders = $db->query("SELECT * FROM `search_order` WHERE `user` = ?;", $user)->fetchAll();
if (!empty($orders)) {
    foreach ($orders as $item) {
        $order_id = $item["id"];
        $listing_id = get_col_data($order_id, 'order_id', 'listing_id', 'search_order_results');
        if (!empty($listing_id)) {

            $listings = $db->query("SELECT * FROM `listing` WHERE `id` = ?;", $listing_id)->fetchArray();
            if ($listings && !empty($listings)) {
                $listing_label = $listings['listing_label'];
                $listing_slug = $listings['listing_slug'];
                $listing_day = $listings['foreclosure_date'];

                if (!empty($listing_day)) {
                    //$dates[] = '"' . dayOnly($listing_day) . '"';
                    $dates[] = array(
                        'Org' => $listing_day,
                        'Date' => dayComma($listing_day),
                        'Title' => 'Listing: ' . $listing_label,
                        'Link' => LINK . '/listing/' . $listing_slug,
                    );
                }
            }
        }
    }
}

/* Check auction in next 7 days */

$days_between = '';
if (!empty($dates)) {
    foreach ($dates as $item) {
        $start = strtotime($item['Org']);
        $end = strtotime(date('Y-m-d'));
        if ($start > $end) {
            if ($start < strtotime('+7 day')) {
                $days_between = ceil(abs($end - $start) / 86400);
                $days_between = $days_between - 1;
                if ($days_between !== 0) {
                    break;
                }
            }
        }
    }
}

// construction year
$construction_year = construction_years();

// equipments
$equipments = $db->query("SELECT * FROM `equipments` WHERE `status` = 1")->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Mein Profil - Promised Land</title>
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
                            <section id="profile" class="no-gap">

                                <!-- Start . Include Info -->
                                <!-- <div class="account_include">
                                    <div class="account_include__title">
                                        <h4>Ich möchte folgende Informationen aus den Gutachten erhalten:</h4>
                                    </div>
                                    <div class="account_include__list">
                                        <div class="account_include__list-item">
                                            <input class="form-check-input" type="checkbox" value="checkbox1" id="checkbox1">
                                            <label for="checkbox1">Checkbox 1</label>
                                        </div>
                                        <div class="account_include__list-item">
                                            <input class="form-check-input" type="checkbox" value="checkbox2" id="checkbox2">
                                            <label for="checkbox2">Checkbox 2</label>
                                        </div>
                                        <div class="account_include__list-item">
                                            <input class="form-check-input" type="checkbox" value="checkbox3" id="checkbox3">
                                            <label for="checkbox3">Checkbox 3</label>
                                        </div>
                                        <div class="account_include__list-item">
                                            <input class="form-check-input" type="checkbox" value="checkbox4" id="checkbox4">
                                            <label for="checkbox4">Checkbox 4</label>
                                        </div>
                                        <div class="account_include__list-item">
                                            <input class="form-check-input" type="checkbox" value="checkbox5" id="checkbox5">
                                            <label for="checkbox5">Checkbox 5</label>
                                        </div>
                                        <div class="account_include__list-item">
                                            <input class="form-check-input" type="checkbox" value="checkbox6" id="checkbox6">
                                            <label for="checkbox6">Checkbox 6</label>
                                        </div>
                                    </div>
                                </div> -->
                                <!-- End . Include Info -->

                                <!-- Start . Calender & Alerts -->
                                <div class="account_times">
                                    <div class="row row-cols-xl-2 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 row-cols-1">
                                        <div class="col">

                                            <div class="account_alerts account_calendar">
                                                <div class="account_alerts__title">
                                                    <h4>Kalender</h4>
                                                </div>
                                                <div class="account_alerts__list">
                                                    <div class="account_calendar__inside">
                                                        <div id="caleandar"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if (!empty($days_between)) { ?>
                                                <div class="account_auction">
                                                    <div class="account_auction_alert">
                                                        <i class="fa fa-info-circle"></i>
                                                        <span>Deine nächste Auktion ist in <?= $days_between ?> Tagen.</span>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>

                                        <div class="col">
                                            <div class="account_alerts">
                                                <div class="account_alerts__title">
                                                    <h4>Benachrichtigungen</h4>
                                                </div>
                                                <div class="account_alerts__list">
                                                    <?php include HOME . '/inc/account/alerts.php'; ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- End . Calender & Alerts -->

                                <!-- Start . Dream Property -->
                                <div class="account_dream">
                                    <div class="account_dream__title">
                                        <h4>Meine Traumimmobilie</h4>
                                    </div>
                                    <div class="account_dream__body">
                                        <div class="account_dream__body-form">

                                            <div class="course_thread-alert"></div>
                                            <div class="overlay"></div>

                                            <form id="search_order__submit" action="<?= fullUrl() ?>" method="POST" autocomplete="off">

                                                <div class="row">

                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <input name="address" type="text" id="searchInput" placeholder="Adresse" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <div class="form-group row row-sm align-items-center">
                                                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                                                                <label for="radius" class="form-label mb-0">Radius:</label>
                                                                <span class="show_radius_range" id="show_radius_range"></span>
                                                            </div>
                                                            <div class="col-xl-8 col-lg-8 col-md-6 col-sm-12 col-12">
                                                                <div class="radius_range"></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                        <label>Objektart</label>
                                                        <select name="category" id="category" class="form-select" data-select="<?= $get_category ?>">
                                                            <option value="">- Objektart -</option>
                                                            <option value="Eigentumswohnungen">Eigentumswohnungen</option>
                                                            <option disabled value="Mehrfamilienhäuser">Mehrfamilienhäuser</option>
                                                            <option disabled value="Wohn-/ Geschäftshäuser">Wohn-/ Geschäftshäuser</option>
                                                            <option disabled value="Gewerbegrundstücke">Gewerbegrundstücke</option>
                                                            <option value="Einfamilienhäuser">Einfamilienhäuser</option>
                                                            <option disabled value="Unbebaute Grundstücke">Unbebaute Grundstücke</option>
                                                            <option disabled value="Land- und forstwirtschaftlich genutzte Flächen">Land- und forstwirtschaftlich genutzte Flächen</option>
                                                            <option disabled value="Baugrundstücke">Baugrundstücke</option>
                                                            <option value="KFZ-Stellplatz/ Garagen">KFZ-Stellplatz/ Garagen</option>
                                                            <option disabled value="Zweifamilienhaus">Zweifamilienhaus</option>
                                                            <option disabled value="Sonstige">Sonstige</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                                        <label>Wohnfläche</label>
                                                        <input type="number" name="space_from" id="living_space_from" class="form-control" placeholder="Von">

                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                                        <label>Wohnfläche</label>
                                                        <input type="number" name="space_to" id="living_space_to" class="form-control" placeholder="Bis">
                                                    </div>

                                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                                        <label>Zimmer</label>
                                                        <input type="number" name="rooms_from" id="room_count_from" class="form-control" placeholder="Von">
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                                        <label>Zimmer</label>
                                                        <input type="number" name="rooms_to" id="room_count_to" class="form-control" placeholder="Bis">
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                        <label>Wertgrenze</label>
                                                        <select name="value" id="value_count" class="form-select" data-select="<?= $get_value ?>">
                                                            <option value="">- Wertgrenze -</option>
                                                            <option value="5/10">5/10</option>
                                                            <option value="7/10">7/10</option>
                                                            <option value="Wertgrenzen entfallen">Wertgrenzen entfallen</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <div class="search_info__form-price--slider">
                                                            <label for="amount">Verkehrswert:</label>
                                                            <span class="show_price_range"></span>
                                                            <div class="price_range"></div>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" id="radius_cal" name="radius" value="">

                                                    <input type="hidden" id="price_from" name="price_from" value="">
                                                    <input type="hidden" id="price_to" name="price_to" value="">

                                                </div>

                                                <?php if (contentStatus(array('premium', 'plus'))) { ?>
                                                    <div class="premium_search">
                                                        <?php include HOME . '/inc/search/premium.php'; ?>
                                                    </div>
                                                <?php } ?>

                                                <div class="account_dream__body-form--btn">

                                                    <div class="search_info__form-search--inner">
                                                        <div class="colu">
                                                            <button class="btn btn-dark">
                                                                <i class="fa fa-save"></i>
                                                                <span>Als Suchauftrag</span>
                                                            </button>
                                                        </div>

                                                        <?php if (contentStatus(array('premium', 'plus'))) { ?>
                                                            <div class="colu">
                                                                <div class="btn btn-blue" data-bs-toggle="modal" data-bs-target="#additionalModal">
                                                                    <span>Additional Filters</span>
                                                                </div>
                                                            </div>
                                                        <?php } ?>

                                                    </div>



                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                                <!-- End . Dream Property -->

                                <!-- Start . Bookings -->
                                <div class="account_booking">
                                    <div class="row row-cols-xl-2 row-cols-lg-2 row-cols-md-1 row-cols-sm-1 row-cols-1">
                                        <div class="col">
                                            <div class="account_booking__card gray">
                                                <div class="account_booking__card-ribbon">
                                                    <span>Coming Soon</span>
                                                </div>
                                                <div class="account_booking__card-title">
                                                    <h4>Beschäftigst du dich erst kürzlich mit Zwangsvollstreckungen?</h4>
                                                </div>
                                                <div class="account_booking__card-body">
                                                    <div class="row">
                                                        <div class="col-10">
                                                            <div class="abc_info">
                                                                <p>Du hast ein passendes Objekt gefunden aber es sind noch Fragen offen? Dann buche dir jetzt einen Platz in unserem Seminar.</p>
                                                                <!-- <a href="#" class="btn btn-white">Coming Soon!</a> -->
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-xl-5 col-lg-5 col-md-4 col-sm-12 col-12">
                                                            <div class="abc_img">
                                                                <img src="<?= LINK ?>/assets/img/author.jpg">
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="account_booking__card gray">
                                                <div class="account_booking__card-ribbon">
                                                    <span>Coming Soon</span>
                                                </div>
                                                <div class="account_booking__card-title">
                                                    <h4>Hast du an <br> alles gedacht?</h4>
                                                </div>
                                                <div class="account_booking__card-body">
                                                    <div class="row">
                                                        <div class="col-10">
                                                            <div class="abc_info">
                                                                <p>Wir haben eine Checkliste zusammengestellt. Vergewissere dich, dass du nichts vergessen hast.</p>
                                                                <!-- <a href="#" class="btn btn-white">Coming Soon!</a> -->
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-xl-5 col-lg-5 col-md-4 col-sm-12 col-12">
                                                            <div class="abc_img">
                                                                <img src="<?= LINK ?>/assets/img/author.jpg">
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End . Bookings -->

                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="home_map">
        <div class="home_map__inner">
            <div id="map"></div>
        </div>
    </div>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>

    <!-- Start . Block Days -->
    <script>
        $(document).ready(function() {

            var events = [
                <?php foreach ($dates as $item) { ?> {
                        'Date': new Date(<?= $item['Date'] ?>),
                        'Title': '<?= $item['Title'] ?>',
                        'Link': '<?= $item['Link'] ?>'
                    },
                <?php } ?>
            ];

            var settings = {
                Color: '#17304e',
                LinkColor: '#17304e',
            };
            var element = document.getElementById('caleandar');
            caleandar(element, events, settings);

        })
    </script>
    <!-- End . Block Days -->

</body>

</html>