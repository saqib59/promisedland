<?php

if (!isset($loop_featured)) $loop_featured = '';

if (!isset($loop_report)) $loop_report = '';

if (!isset($loop_label)) $loop_label = '';
if (!isset($loop_date)) $loop_date = '';

if (!isset($loop_slug)) $loop_slug = '';
if (!isset($loop_price)) $loop_price = '';
if (!isset($loop_address)) $loop_address = '';
if (!isset($loop_desc)) $loop_desc = '';

if (!isset($loop_catergory)) $loop_catergory = '';

if (!isset($loop_title)) $loop_title = '';
if (!isset($loop_rooms)) $loop_rooms = '';

if (!isset($loop_units)) $loop_units = '';
if (!isset($loop_owner)) $loop_owner = '';
if (!isset($loop_limit)) $loop_limit = '';

if (!isset($loop_equip)) $loop_equip = '';

if (!isset($loop_space)) $loop_space = '';
if (!isset($loop_use)) $loop_use = '';
if (!isset($loop_plot)) $loop_plot = '';
if (!isset($loop_earn_month)) $loop_earn_month = '';

if (!isset($loop_demolished)) $loop_demolished = '';

$user = 0;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

$listing_url = LINK . '/listing/' . $loop_slug . '/';

//$listing_location = $loop_address;

/* $listing_title = '';
if (!empty($loop_title)) {
    if (strpos($loop_title, 'gelegen in') !== false) {
        $titles = explode(' gelegen in ', $loop_title);
        $listing_title = $titles[0];
        //$listing_location = $titles[1];
    }
} */

$listing_title = $loop_title;
$listing_location = $loop_address;
if (!empty($loop_address)) {
    if (strpos($loop_address, ',') !== false) {
        $locs = explode(', ', $loop_address);
        $listing_location = $locs[1];
    }
}

$new_cats = getCatArray($listing_id);

if (!empty($loop_earn_month)) {
    $loop_earn_month = price($loop_earn_month);
}

$loop_bid = '';
$potential_return = 0;
if (!empty($loop_price)) {

    $loop_price = object_price($loop_price);
    if (!empty($loop_limit)) {
        $loop_bid = minimumBid($loop_price, $loop_limit);
    }

    if (!empty($loop_earn_month) && !empty($loop_price)) {
        $potential_return = ($loop_earn_month * 12 * 100) / $loop_price;
    }
}

/* Equipments */
$equipments = '';
if (!empty($loop_equip)) {
    $equipments = json_decode($loop_equip, true);
}

// date
$listing_date = '';
if (!empty($loop_date)) {
    if (!empty(dayOnly($loop_date))) {
        $listing_date = dayOnly($loop_date);
    }
}

?>

<div class="favourite_list__item" data-listing_id="<?= $listing_id ?>">
    <div class="favourite_list__left">
        <div class="fav_item__image">

            <div class="tli-chart">
                <?php include HOME . '/inc/layout/chart.php'; ?>
            </div>

            <?php if ($loop_featured == 1) { ?>
                <div class="image_feat_badge">
                    <div class="fav_badge featured_badge">
                        <span>Featured</span>
                    </div>
                </div>
            <?php } ?>

            <?php if (contentStatus(array('premium', 'plus'))) { ?>
                <div class="image_report_badge">
                    <?php if ($loop_report !== 'none') { ?>
                        <div class="fav_badge report_avai_badge">
                            <span>Gutachten liegt uns vor</span>
                        </div>
                    <?php } else { ?>
                        <div class="fav_badge report_not_badge">
                            <span>Gutachten liegt uns nicht vor</span>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if ($potential_return > 15) { ?>
                <div class="listing_redruler">
                    <div class="listing_redruler__notice">
                        <span>Bei diesem Objekt liegen vermutlich Baumängel vor</span>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
    <div class="favourite_list__right">

        <div class="fav_item__details">
            <div class="fav_item__date">
                <i class="fa fa-calendar"></i>
                <span><?= !empty($listing_date) ? $listing_date : 'N/A' ?></span>
            </div>
            <div class="fav_item__label">
                <span><?= !empty($loop_label) ? $loop_label : 'N/A' ?></span>
            </div>
        </div>

        <div class="fav_item__heading">

            <div class="fav_item__title">
                <h4><a href="<?= !empty($listing_url) ? $listing_url : '#' ?>"><?= !empty($listing_title) ? $listing_title : 'N/A' ?></a></h4>
            </div>

            <?php if (user() !== false) { ?>
                <div class="favourite_icon">
                    <?php $favStatus = checkFav($user, $listing_id); ?>
                    <div class="listing_fav <?= $favStatus ? 'active' : '' ?>" data-listing="<?= $listing_id ?>" data-method="<?= $favStatus ? 'remove' : 'add' ?>">
                        <button class="btn"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
            <?php } else { ?>
                <div class="favourite_icon">
                    <div class="listing_fav disabled">
                        <button class="btn"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
            <?php } ?>

        </div>
        <div class="fav_item__sub">
            <div class="fav_item__loc">
                <span>gelegen in <?= ($listing_location) ? $listing_location : 'N/A' ?></span>
            </div>
            <div class="fav_item__price">
                <strong><?= !empty($loop_price) ? priceGerman($loop_price) . '&euro;' : 'N/A' ?></strong>
                <span>Verkehrswert</span>
            </div>
        </div>

        <?php if (!empty($equipments)) { ?>
            <div class="fav_item__facilities">
                <ul>
                    <?php foreach ($equipments as $k) { ?>
                        <li><?= get_data($k, 'label', 'equipments') ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <div class="fav_item__info" data-sectitle="fav_item__info" data-secelem="fav_item__meta-item">
            <div class="fav_item__meta___inner">

                <?php if (in_array('Einfamilienhäuser', $new_cats) || in_array('Eigentumswohnungen', $new_cats)) { ?>
                    <?php if (!empty($loop_rooms)) { ?>
                        <div class="fav_item__meta-item">
                            <h4><?= $loop_rooms ?></h4>
                            <p>Zimmer</p>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if (in_array('Mehrfamilienhäuser', $new_cats) || in_array('Wohn-/ Geschäftshäuser', $new_cats)) { ?>
                    <?php if (!empty($loop_units)) { ?>
                        <div class="fav_item__meta-item">
                            <h4><?= $loop_units ?></h4>
                            <p>Wohnungseinheiten</p>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if (
                    in_array('Einfamilienhäuser', $new_cats) || in_array('Mehrfamilienhäuser', $new_cats) ||
                    in_array('Eigentumswohnungen', $new_cats) || in_array('Wohn-/ Geschäftshäuser', $new_cats)
                ) { ?>
                    <?php if (!empty($loop_space)) { ?>
                        <div class="fav_item__meta-item">
                            <h4><?= $loop_space ?>m<sup>2</sup></h4>
                            <p>Wohnfläche</p>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if (
                    //in_array('Gewerbegrundstücke', $new_cats) || 
                    in_array('KFZ-Stellplatz/ Garagen', $new_cats) ||
                    in_array('Zweifamilienhaus', $new_cats) || in_array('Wohn-/ Geschäftshäuser', $new_cats)
                ) { ?>
                    <?php if (!empty($loop_use)) { ?>
                        <div class="fav_item__meta-item">
                            <h4><?= $loop_use ?>m<sup>2</sup></h4>
                            <p>Nutzfläche</p>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if (
                    in_array('Einfamilienhäuser', $new_cats) || in_array('Mehrfamilienhäuser', $new_cats) || in_array('Wohn-/ Geschäftshäuser', $new_cats) ||
                    in_array('Gewerbegrundstücke', $new_cats) || in_array('Unbebaute Grundstücke', $new_cats) ||  in_array('Zweifamilienhaus', $new_cats) ||
                    //in_array('KFZ-Stellplatz/ Garagen', $new_cats) || 
                    in_array('Land- und forstwirtschaftlich genutzte Flächen', $new_cats) ||
                    in_array('Baugrundstücke', $new_cats)
                ) { ?>
                    <?php if (!empty($loop_plot)) { ?>
                        <div class="fav_item__meta-item">
                            <h4><?= $loop_plot ?>m<sup>2</sup></h4>
                            <p>Grundstücksfläche</p>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if (in_array('Baugrundstücke', $new_cats)) { ?>
                    <?php if (!empty($loop_demolished)) { ?>
                        <div class="fav_item__meta-item">
                            <h4><?= $loop_demolished ?>m<sup>2</sup></h4>
                            <p>Abzureißendes Gebäude</p>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if (
                    in_array('Eigentumswohnungen', $new_cats) ||
                    //in_array('KFZ-Stellplatz/ Garagen', $new_cats) || 
                    in_array('Zweifamilienhaus', $new_cats)
                ) { ?>
                    <?php if (!empty($loop_owner)) { ?>
                        <div class="fav_item__meta-item">
                            <h4><?= $loop_owner ?></h4>
                            <p>Miteigentumsanteil</p>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if (in_array('Unbebaute Grundstücke', $new_cats)) { ?>
                    <?php if (!empty($loop_bid)) { ?>
                        <div class="fav_item__meta-item">
                            <h4><?= $loop_bid ?></h4>
                            <p>Geringstes Gebot</p>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if (in_array('Unbebaute Grundstücke', $new_cats)) { ?>
                    <?php if (!empty($loop_limit)) { ?>
                        <div class="fav_item__meta-item">
                            <h4><?= $loop_limit ?></h4>
                            <p>Wertgrenze</p>
                        </div>
                    <?php } ?>
                <?php } ?>

            </div>
        </div>

    </div>
</div>