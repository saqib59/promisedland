<?php

/* $listing_category = get_data($listing_id, 'new_cat', 'listing');
if (!empty($listing_category)) {
    $new_cats = json_decode($listing_category, true);
} */

$new_cats = getCatArray($listing_id);

$object_val = get_data($listing_id, 'object_val', 'listing');
$object_val = object_price($object_val);

$aboutData = $db->query('SELECT * FROM `about` WHERE `listing_id` = ?', $listing_id)->fetchArray();

$plot_area = '';
$use_space = '';
$living_space = '';

if (isset($aboutData['plot_area'])) {
    $plot_area = $aboutData['plot_area'];
}
if (isset($aboutData['use_space'])) {
    $use_space = $aboutData['use_space'];
}
if (isset($aboutData['living_space'])) {
    $living_space = $aboutData['living_space'];
}

?>

<div class="listing_details">
    <div class="row justify-content-between">

        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-12">
            <div class="listing_details__item">
                <h4><?= !empty($object_val) ? priceGerman($object_val) : 'N/A'; ?>&euro;</h4>
                <p>Verkehrswert</p>
            </div>
        </div>

        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-12">
            <div class="listing_details__item" data-find="monthly_cost">
                <h4><?= !empty($listing_monthly_cost) ? priceGerman($listing_monthly_cost) : 'N/A'; ?>&euro;</h4>
                <p>Geschätzte monatliche Rate</p>
                <a href="<?= LINK ?>/advisor/" class="btn btn-blue">Finanzierungsseite</a>
                <div class="custom_tooltip" data-tool="monthly_cost">
                    <span>Diese Rate wurde basierend auf einem Zinssatz von 1,5% mit einer anfänglichen Tilgung von 2% berechnet. Auf unserer <a href="<?= LINK ?>/advisor/">Finanzierungsseite</a> kann die genaue Rate berechnet werden.</span>
                </div>
            </div>
        </div>

        <?php if (in_array('Land- und forstwirtschaftlich genutzte Flächen', $new_cats) == false && in_array('Unbebaute Grundstücke', $new_cats) == false) { ?>
            <?php if (in_array('Wohn-/ Geschäftshäuser', $new_cats) || in_array('Gewerbegrundstücke', $new_cats) || in_array('KFZ-Stellplatz/ Garagen', $new_cats)) { ?>
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-6">
                    <div class="listing_details__item">
                        <h4><?= !empty($use_space) ? $use_space . ' m<sup>2</sup>' : 'N/A' ?></h4>
                        <p>Use Space</p>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-6">
                    <div class="listing_details__item">
                        <?php if (in_array('Mehrfamilienhäuser', $new_cats)) { ?>
                            <h4><?= !empty($data['listing_flats']) ? $data['listing_flats'] : 'N/A' ?></h4>
                            <p>Flats</p>
                        <?php } else { ?>
                            <h4><?= !empty($data['listing_rooms']) ? $data['listing_rooms'] : 'N/A' ?></h4>
                            <p>Zimmer</p>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

        <?php if (in_array('KFZ-Stellplatz/ Garagen', $new_cats)) { ?>
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-6">
                <div class="listing_details__item">
                    <h4><?= !empty($plot_area) ? $plot_area . ' m<sup>2</sup>' : 'N/A'; ?></h4>
                    <p>Plot Area</p>
                </div>
            </div>
        <?php } else { ?>
            <?php if (in_array('Gewerbegrundstücke', $new_cats) == false && in_array('Land- und forstwirtschaftlich genutzte Flächen', $new_cats) == false && in_array('Unbebaute Grundstücke', $new_cats) == false) { ?>
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-6">
                    <div class="listing_details__item">
                        <h4><?= !empty($living_space) ? $living_space . ' m<sup>2</sup>' : 'N/A' ?></h4>
                        <p>Wohnfläche</p>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>



        <?php if (in_array('Einfamilienhäuser', $new_cats) || in_array('Zweifamilienhaus', $new_cats) || in_array('Unbebaute Grundstücke', $new_cats) || in_array('Unbebaute Grundstücke', $new_cats)) { ?>
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-6">
                <div class="listing_details__item">
                    <h4><?= !empty($plot_area) ? $plot_area . ' m<sup>2</sup>' : 'N/A'; ?></h4>
                    <p>Area</p>
                </div>
            </div>
        <?php } elseif (in_array('Mehrfamilienhäuser', $new_cats) || in_array('Wohn-/ Geschäftshäuser', $new_cats) || in_array('Gewerbegrundstücke', $new_cats) || in_array('Land- und forstwirtschaftlich genutzte Flächen', $new_cats) || in_array('Baugrundstücke', $new_cats)) { ?>
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-6">
                <div class="listing_details__item">
                    <h4><?= !empty($plot_area) ? $plot_area . ' m<sup>2</sup>' : 'N/A'; ?></h4>
                    <p>Plot Area</p>
                </div>
            </div>
        <?php } else { ?>
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-6">
                <div class="listing_details__item">
                    <h4><?= !empty($data['listing_ownership']) ? $data['listing_ownership'] : 'N/A' ?></h4>
                    <p>Miteigentumsanteile</p>
                </div>
            </div>
        <?php } ?>

        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-6">
            <div class="listing_details__item">
                <h4><?= !empty($data['value_limit']) ? $data['value_limit'] : 'N/A' ?></h4>
                <p>Wertgrenze</p>
            </div>
        </div>

    </div>
</div>