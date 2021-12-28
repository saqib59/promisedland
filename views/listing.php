<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if (!isset($_GET['label']) || $_GET['label'] == '') {
    header("Location: " . LINK);
}

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

/* Get Listing */
$listing_slug = $_GET['label'];
$list = $db->query("SELECT * FROM `listing` WHERE completed = '1' AND `listing_slug` = ?;", $listing_slug)->fetchArray();
if (!$list || empty($list)) {
    redirect('Ungültiger Link', LINK);
}

$listing_id = $list["id"];

$data = $db->query('SELECT * FROM `details` WHERE `listing_id` = ?;', $listing_id)->fetchArray();
$about = $db->query('SELECT * FROM `about` WHERE `listing_id` = ?', $listing_id)->fetchArray();

$listing_monthly_cost = '';
$listing_value = get_data($listing_id, 'object_val', 'listing');
if (!empty($listing_value)) {
    $listing_value = object_price($listing_value);

    $listing_monthly_cost = ((($listing_value * 1.5) / 100) + (($listing_value * 2) / 100)) / 12;
    $listing_monthly_cost = number_format((float)$listing_monthly_cost, 2, '.', '');
}

$minimum_bid = 0;
if (isset($data['value_limit']) && !empty($data['value_limit'])) {
    $minimum_bid = minimumBid($listing_value, $data['value_limit']);
}


// report url
$report_link = '';
if (!empty($list['gutachten_pdf'])) {
    $report_link = $list['gutachten_pdf'];
} elseif (!empty($list['exposee_pdf'])) {
    $report_link = $list['exposee_pdf'];
}

/* $listing_category = get_data($listing_id, 'new_cat', 'listing');
if (!empty($listing_category)) {
    $new_cats = json_decode($listing_category, true);
} */

$new_cats = getCatArray($listing_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title><?= !empty($data['about_type']) ? $data['about_type'] : 'Listing' ?> - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!-- Start . Section : Slider -->
    <section id="listing">
        <div class="container">

            <div class="listing">

                <?php if (isset($list['canceled']) && $list['canceled'] == '1') { ?>
                    <div class="payment_status mb-4">
                        <p>Der Versteigerungstermin am <strong><?= !empty($list['foreclosure_date']) ? dayOnly($list['foreclosure_date']) : 'N/A' ?></strong> wurde aufgehoben.</p>
                    </div>
                <?php } ?>

                <div class="listing_gallery">

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="sliderReturn">
                            <div class="listing_gallery__panel">

                                <?php include HOME . '/inc/listing/charts.php'; ?>

                            </div>
                        </div>

                        <?php //if (in_array('Eigentumswohnungen', $new_cats)) { 
                        ?>
                        <?php //if (isset($data['model_url']) && !empty($data['model_url'])) { 
                        ?>
                        <div class="tab-pane fade" id="slider3D">
                            <div class="listing_gallery__panel">
                                <div class="listing_gallery__panel-embed">
                                    <?php if (isset($data['model_url']) && !empty($data['model_url'])) { ?>
                                        <model-viewer src="<?= LINK . $data['model_url'] ?>" ar ar-modes="webxr scene-viewer quick-look" environment-image="neutral" auto-rotate camera-controls></model-viewer>
                                    <?php } else { ?>
                                        <div class="alert alert-info">Coming Soon</div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php //} 
                        ?>
                        <?php //} 
                        ?>

                        <div class="tab-pane fade" id="sliderVideo">
                            <div class="listing_gallery__panel">
                                <div class="listing_gallery__panel-video">

                                    <div class="row row-cols-xl-2 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 row-cols-1">
                                        <div class="col">
                                            <div class="listing_gallery__panel-video--item">
                                                <video width="100%" controls>
                                                    <source src="<?= LINK ?>/assets/vids/objektanalyse.mp4" type="video/mp4">
                                                    Your browser does not support HTML video.
                                                </video>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="listing_gallery__panel-video--item">
                                                <video width="100%" controls>
                                                    <source src="<?= LINK ?>/assets/vids/gutachten-anfordern.mp4" type="video/mp4">
                                                    Your browser does not support HTML video.
                                                </video>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="listing_gallery__tabs">
                        <div class="nav nav-tabs justify-content-center" id="nav-tab">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sliderReturn" type="button">Return</button>

                            <?php //if (in_array('Eigentumswohnungen', $new_cats)) { 
                            ?>
                            <?php //if (isset($data['model_url']) && !empty($data['model_url'])) { 
                            ?>
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#slider3D" type="button">3D Model</button>
                            <?php //} 
                            ?>
                            <?php //} 
                            ?>

                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sliderVideo" type="button">Video</button>
                        </div>
                    </div>

                </div>

                <?php if (!empty($data) || !empty($about)) { ?>
                    <!-- Start . Title -->
                    <div class="listing_title">
                        <div class="listing_title__inner">

                            <div class="listing_title__inner-left">
                                <?= !empty($about['business_kind']) ? '<span>' . $about['business_kind'] . '</span>' : '' ?>
                                <h4><?= !empty($data['about_type']) ? $data['about_type'] : '' ?></span></h4>
                            </div>


                            <div class="listing_title__inner-right">

                                <?php if (user() !== false) { ?>
                                    <?php $favStatus = checkFav($user, $listing_id); ?>
                                    <div class="listing_fav <?= $favStatus ? 'active' : '' ?>" data-listing="<?= $listing_id ?>" data-method="<?= $favStatus ? 'remove' : 'add' ?>">
                                        <button class="btn">
                                            <i class="fa fa-heart"></i>
                                        </button>
                                    </div>
                                <?php } else { ?>
                                    <div class="favourite_icon">
                                        <div class="listing_fav disabled">
                                            <button class="btn"><i class="fas fa-heart"></i></button>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                    <!-- End . Title -->
                <?php } ?>

                <!-- Start . Info -->
                <div class="listing_info">
                    <div class="row">
                        <div class="col-xl-9 col-lg-8 col-md-7 col-sm-12 col-12">
                            <div class="listing_info__offers">

                                <?php if (isset($list['object_address']) && !empty($list['object_address'])) { ?>
                                    <div class="listing_info__offers-address">
                                        <i class="fa fa-map-marker-alt"></i>
                                        <span><?= $list['object_address']; ?></span>
                                    </div>
                                <?php } ?>

                                <?php if (isset($data['listing_equipment']) && !empty($data['listing_equipment'])) { ?>
                                    <div class="listing_info__offers-facilities">
                                        <ul>
                                            <?php
                                            $equipments = json_decode($data['listing_equipment'], true);
                                            foreach ($equipments as $item) {
                                                echo "<li>" . get_data($item, 'label', 'equipments') . "</li>";
                                            } ?>
                                        </ul>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-5 col-sm-12 col-12">
                            <?php if (isset($minimum_bid) && !empty($minimum_bid)) { ?>
                                <div class="listing_info__lowest">
                                    <h4><?= isset($minimum_bid) ? priceClean($minimum_bid) . ' &euro;' : 'N/A' ?></h4>
                                    <p>Geringstes Gebot</p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- End . Info -->

                <!-- Start . Details -->
                <?php include HOME . '/inc/listing/meta.php'; ?>
                <!-- End . Details -->

                <!-- Start . Description -->
                <div class="listing_description">
                    <div class="listing_description__buttons">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">

                            <button class="nav-link active" id="nav-foreclosure-tab" data-bs-toggle="tab" data-bs-target="#nav-foreclosure" type="button">Informationen zur Versteigerung</button>

                            <?php if (contentStatus(array('premium', 'plus'))) { ?>
                                <?php if (in_array('Land- und forstwirtschaftlich genutzte Flächen', $new_cats) || in_array('Unbebaute Grundstücke', $new_cats) || in_array('KFZ-Stellplatz/ Garagen', $new_cats)) { ?>
                                    <button class="nav-link disabled" id="nav-description-tab" data-bs-toggle="tab" data-bs-target="#nav-description" type="button">Objektbeschreibung</button>
                                <?php } else { ?>
                                    <button class="nav-link" id="nav-description-tab" data-bs-toggle="tab" data-bs-target="#nav-description" type="button">Objektbeschreibung</button>
                                <?php } ?>

                                <button class="nav-link" id="nav-analysis-tab" data-bs-toggle="tab" data-bs-target="#nav-analysis" type="button">Standortanalyse</button>
                                <button class="nav-link" id="nav-calculator-tab" data-bs-toggle="tab" data-bs-target="#nav-calculator" type="button">Rendite-Rechner</button>
                            <?php } else { ?>
                                <button class="nav-link disabled" id="nav-description-tab" data-bs-toggle="tab" data-bs-target="#nav-description" type="button">Objektbeschreibung<span class="premium_label">Premium</span></button>
                                <button class="nav-link disabled" id="nav-analysis-tab" data-bs-toggle="tab" data-bs-target="#nav-analysis" type="button">Standortanalyse<span class="premium_label">Premium</span></button>
                                <button class="nav-link disabled" id="nav-calculator-tab" data-bs-toggle="tab" data-bs-target="#nav-calculator" type="button">Rendite-Rechner<span class="premium_label">Premium</span></button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-foreclosure" role="tabpanel" aria-labelledby="nav-foreclosure-tab">
                            <div class="listing_description__body">
                                <?php include HOME . '/inc/listing/about.php'; ?>
                            </div>
                        </div>
                        <?php if (contentStatus(array('premium', 'plus'))) { ?>
                            <?php if (in_array('Land- und forstwirtschaftlich genutzte Flächen', $new_cats) == false && in_array('Unbebaute Grundstücke', $new_cats) == false) { ?>
                                <div class="tab-pane fade" id="nav-description" role="tabpanel" aria-labelledby="nav-description-tab">
                                    <div class="listing_description__body">
                                        <?php include HOME . '/inc/listing/description.php'; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="tab-pane fade" id="nav-analysis" role="tabpanel" aria-labelledby="nav-analysis-tab">
                                <?php include HOME . '/inc/listing/analysis.php'; ?>
                            </div>
                            <div class="tab-pane fade" id="nav-calculator" role="tabpanel" aria-labelledby="nav-calculator-tab">
                                <?php include HOME . '/inc/listing/calculator.php'; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- End . Description -->

            </div>

        </div>
    </section>
    <!-- End . Section : Slider -->

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>
</body>

</html>