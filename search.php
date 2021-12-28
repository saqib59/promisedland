<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './config/config.php';

$get_address = '';
$get_category = '';
//$get_price = '';

$get_space_from = '';
$get_space_to = '';

$get_rooms_from = '';
$get_rooms_to = '';

$get_value = '';

$searchOk = 0;
if (isset($_GET)) {
    foreach ($_GET as $item) {
        if (!empty($item)) {
            $searchOk = 1;
            break;
        }
    }
}

if (isset($_GET['address']) && !empty($_GET['address'])) {
    $get_address = $_GET['address'];
}
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $get_category = $_GET['category'];
}
/* if(isset($_GET['max_price']) && !empty($_GET['max_price'])) {
    $get_price = $_GET['max_price'];
} */

if (isset($_GET['living_space_from']) && !empty($_GET['living_space_from'])) {
    $get_space_from = $_GET['living_space_from'];
}
if (isset($_GET['living_space_to']) && !empty($_GET['living_space_to'])) {
    $get_space_to = $_GET['living_space_to'];
}

if (isset($_GET['room_count_from']) && !empty($_GET['room_count_from'])) {
    $get_rooms_from = $_GET['room_count_from'];
}
if (isset($_GET['room_count_to']) && !empty($_GET['room_count_to'])) {
    $get_rooms_to = $_GET['room_count_to'];
}

if (isset($_GET['value_count']) && !empty($_GET['value_count'])) {
    $get_value = $_GET['value_count'];
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
    <title>Search - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!-- Start . Section : Search -->
    <section id="search" class="no-gap">
        <div class="row stacked">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                <div id="map" class="search_map"></div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="search_info">

                    <div class="search_info__form">
                        <form action="<?= LINK ?>/search/" method="POST" autocomplete="off">

                            <div class="search_info__form-alert"></div>

                            <div class="search_info__form-address">
                                <div class="form-group">
                                    <input type="text" id="searchInput" name="address" placeholder="Adresse" class="form-control" value="<?= $get_address ?>">
                                </div>
                            </div>

                            <div class="search_info__form-radius">
                                <div class="form-group row row-sm align-items-center">
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                                        <div class="search_info__form-price--slider">
                                            <label for="radius" class="form-label mb-0">Radius:</label>
                                            <span class="show_radius_range" id="show_radius_range"></span>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-md-6 col-sm-12 col-12">
                                        <div class="radius_range"></div>
                                    </div>

                                </div>
                            </div>

                            <div class="search_info__form-labels">
                                <div class="form-group row row-sm">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                        <label>Objektart</label>
                                        <select name="category" class="form-select" data-select="<?= $get_category ?>">
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
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                        <label>Wertgrenze</label>
                                        <select name="value_count" class="form-select" data-select="<?= $get_value ?>">
                                            <option value="">- Wertgrenze -</option>
                                            <option value="5/10">5/10</option>
                                            <option value="7/10">7/10</option>
                                            <option value="Wertgrenzen entfallen">Wertgrenzen entfallen</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="search_info__form-filter">
                                <div class="row row-sm">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                        <div class="search_custom__choose">
                                            <div class="search_custom__choose-title">Wohnfläche</div>
                                            <div class="form-group row row-sm">
                                                <div class="col-6">
                                                    <input type="number" name="living_space_from" class="form-control" placeholder="Von" value="<?= $get_space_from ?>">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" name="living_space_to" class="form-control" placeholder="Bis" value="<?= $get_space_to ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                        <div class="search_custom__choose">
                                            <div class="search_custom__choose-title">Zimmer</div>
                                            <div class="form-group row row-sm">
                                                <div class="col-6">
                                                    <input type="number" name="room_count_from" class="form-control" placeholder="Von" value="<?= $get_rooms_from ?>">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" name="room_count_to" class="form-control" placeholder="Bis" value="<?= $get_rooms_to ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="search_info__form-price">
                                <div class="search_info__form-price--slider">
                                    <label for="amount">Verkehrswert:</label>
                                    <span class="show_price_range"></span>
                                    <div class="price_range"></div>
                                </div>
                            </div>

                            <?php if (contentStatus(array('premium', 'plus'))) { ?>
                                <div class="premium_search">
                                    <?php include HOME . '/inc/search/premium.php'; ?>
                                </div>
                            <?php } ?>

                            <div class="search_info__form-search">
                                <div class="search_info__form-search--inner">
                                    <div class="colu">
                                        <button type="submit" class="btn btn-dark">
                                            <i class="fa fa-search"></i>
                                            <span>Suchen</span>
                                        </button>
                                        <?php if (user()) { ?>
                                            <div id="search_order" class="btn btn-dark-outline">
                                                <span>Als Suchauftrag</span>
                                            </div>
                                        <?php } else { ?>
                                            <div class="btn btn-dark-outline disabled position-relative">
                                                <span>Als Suchauftrag</span>
                                                <span class="premium_label">Users</span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="colu">
                                        <?php if (contentStatus(array('premium', 'plus'))) { ?>
                                            <div class="btn btn-blue" data-bs-toggle="modal" data-bs-target="#additionalModal">
                                                <span>Additional Filters</span>
                                            </div>
                                        <?php } else { ?>
                                            <div class="btn btn-blue disabled position-relative">
                                                <span>Additional Filters</span>
                                                <span class="premium_label">Premium</span>
                                            </div>
                                        <?php } ?>
                                    </div>

                                </div>
                            </div>

                            <input type="hidden" id="radius_cal" name="radius" value="">

                            <input type="hidden" id="price_from" name="price_from" value="">
                            <input type="hidden" id="price_to" name="price_to" value="">

                            <input type="hidden" id="lat" name="lat" value="">
                            <input type="hidden" id="lng" name="lng" value="">

                            <input type="hidden" id="page" name="page" value="0">
                        </form>
                    </div>

                    <div class="search_info__result">
                        <div class="search_info__result-loop show_line"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Start . Section : Search -->

    <?php if (user()) {
        include HOME . '/inc/search/search_order.php';
    } ?>

    <div id="search_status" data-search="<?= $searchOk ?>"></div>
    <?php include HOME . '/block/scripts.php'; ?>

</body>

</html>