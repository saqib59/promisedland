<?php

// Wohnfläche
$listing_space = get_col_data($listing_id, 'listing_id', 'living_space', 'about');
$listing_space = price($listing_space);

// object value
$listing_value = get_data($listing_id, 'object_val', 'listing');
$listing_value = object_price($listing_value);

// object address
$listing_address = get_data($listing_id, 'object_address', 'listing');

// object category
/* $listing_cat = get_data($listing_id, 'new_cat', 'listing');
$new_cats = json_decode($listing_cat, true); */
$new_cats = getCatArray($listing_id);

// cold rent
$earn_month = get_col_data($listing_id, 'listing_id', 'earn_month', 'about');
$earn_month = price($earn_month);

// listing zip
$listing_zip = getZip($listing_address);

// listing type
$rent_table = 'rent_house';
$buy_table = 'buy_house';
if (in_array('Zweifamilienhaus', $new_cats)) {
    $rent_table = 'rent_house';
    $buy_table = 'buy_house';
} elseif (in_array('Eigentumswohnungen', $new_cats)) {
    $rent_table = 'rent_flat';
    $buy_table = 'buy_flat';
}

$potential_rent_db = get_col_data($listing_zip, 'zip', 'avarage_rent', $rent_table);

$earn_month_fix = '';
if(empty($earn_month)) {
    if(!empty($listing_space)) {
        $earn_month_fix = (float)$potential_rent_db * (float)$listing_space;
    }
}

/*********************************************************************************/

/*************************************/
/* 1st Meta */

$actual_rent = '';
if (!empty($earn_month) && !empty($listing_space)) {
    $actual_rent = (float)$earn_month / (float)$listing_space;
    $actual_rent = round((float)$actual_rent, 2);
}

/*************************************/
/* 2nd Meta */
$potential_rent = $potential_rent_db;

/*************************************/
/* 3rd Meta */

$purchase_price = '';

if (!empty($listing_value) && !empty($listing_space)) {
    $purchase_price = (float)$listing_value / (float)$listing_space;
    $purchase_price = round((float)$purchase_price, 2);
}

/*************************************/
/* 4th Meta */
$avarage_buying = get_col_data($listing_zip, 'zip', 'avarage_rent', $buy_table);

/*********************************************************************************/

$actual_rent_width = 0;
$potential_rent_width = 0;

$potential_rent_clean = 0;
if (!empty($potential_rent)) {
    $potential_rent_clean = (float)$potential_rent;
}


if ($actual_rent > $potential_rent_clean) {
    $actual_rent_width = 80;

    if (empty($potential_rent_clean)) {
        $potential_rent_clean_x = '0';
    } else {
        $potential_rent_clean_x = $potential_rent_clean;
    }
    if (!empty($actual_rent)) {
        $potential_rent_w = ($potential_rent_clean_x * 100) / $actual_rent;
        $potential_rent_width = ($potential_rent_w * 80) / 100;
    }
} else {
    $potential_rent_width = 80;

    if (empty($actual_rent)) {
        $actual_rent_x = '0';
    } else {
        $actual_rent_x = $actual_rent;
    }
    if ($potential_rent_clean !== 0) {
        $actual_rent_w = ($actual_rent_x * 100) / $potential_rent_clean;
        $actual_rent_width = ($actual_rent_w * 80) / 100;
    }
}

/*************************************/

$purchase_price_width = 0;
$avarage_buying_width = 0;

$avarage_buying_clean = 0;
if (!empty($avarage_buying)) {
    $avarage_buying_clean =  (float)$avarage_buying;
}

if ($purchase_price > $avarage_buying_clean) {
    $purchase_price_width = 80;

    if (empty($avarage_buying_clean)) {
        $avarage_buying_clean_x = '0';
    } else {
        $avarage_buying_clean_x = $avarage_buying_clean;
    }
    $avarage_buying_w = ($avarage_buying_clean_x * 100) / $purchase_price;
    $avarage_buying_width = ($avarage_buying_w * 80) / 100;
} else {
    $avarage_buying_width = 80;

    if (empty($purchase_price)) {
        $purchase_price_x = '0';
    } else {
        $purchase_price_x = $purchase_price;
    }
    if ($avarage_buying_clean !== 0) {
        $purchase_price_w = ($purchase_price_x * 100) / $avarage_buying_clean;
        $purchase_price_width = ($purchase_price_w * 80) / 100;
    }
}

/*********************************************************************************/

/*************************************/
/* 1st Tab */
$potential_return = '';
$potential_return_format = '';

if(!empty($earn_month)) {
  $earn_month_fix = $earn_month;
}

if (!empty($earn_month_fix) && !empty($listing_value)) {
    $potential_return = ($earn_month_fix * 12 * 100) / $listing_value;
    $potential_return_format = number_format($potential_return, 2, ',', '.');
}

if ($potential_return >= 6) {
    $potential_return_color = 'green';
} elseif ($potential_return >= 4 && $potential_return < 6) {
    $potential_return_color = 'orange';
} else {
    $potential_return_color = 'red';
}

/*************************************/
/* 2nd Tab */
$multiplier_gross = '';
$multiplier_gross_format = '';

if (!empty($earn_month_fix) && !empty($listing_value)) {
    $multiplier_gross = $listing_value / ($earn_month_fix * 12);
    $multiplier_gross_format = number_format($multiplier_gross, 2, ',', '.');
}

if ($multiplier_gross < 16.7) {
    $multiplier_gross_color = 'green';
} elseif ($multiplier_gross >= 16.7 && $multiplier_gross < 25) {
    $multiplier_gross_color = 'orange';
} else {
    $multiplier_gross_color = 'red';
}

/*************************************/
/* 3rd Tab */
$current_usage = get_col_data($listing_id, 'listing_id', 'current_usage', 'about');

switch ($current_usage) {
    case 'Nicht vermietet':
        $current_usage_color = 'green';
        break;
    case 'Unbekannt':
        $current_usage_color = 'red';
        break;
    case 'Eigennutzung':
        $current_usage_color = 'orange';
        break;
    case 'Vermietet':
        $current_usage_color = 'red';
        break;
    default:
        $current_usage_color = 'red';
}

/*************************************/
/* 4th Tab */
$inspection_type = get_col_data($listing_id, 'listing_id', 'inspection_type', 'foreclosure');
switch ($inspection_type) {
    case '':
        $inspection_type_color = 'red';
        break;
    case 'Keine Angabe':
        $inspection_type_color = 'red';
        break;
    case 'Außenbesichtigung':
        $inspection_type_color = 'orange';
        break;
    case 'Innenbesichtigung':
        $inspection_type_color = 'green';
        break;
    default:
        $inspection_type_color = 'green';
}

$inspection_type_color = '';

?>

<div class="listing_gallery__panel-charts">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="listing_chart">
                <div class="listing_chart__title">
                    <h4>Mietdaten</h4>
                </div>
                <div class="listing_chart__list">
                    <div class="listing_chart__list-item">
                        <div class="listing_chart__list-item--chart">
                            <div style="width: <?= $actual_rent_width ?>%;"></div>
                        </div>
                        <div class="listing_chart__list-item--title <?= $actual_rent_width == 0 ? 'gray' : '' ?>">
                            <strong>Ist-Miete</strong>
                            <span><?= !empty($actual_rent) ? priceGerman($actual_rent) . ' &euro;/m<sup>2</sup>' : 'N/A' ?></span>
                        </div>
                    </div>
                    <div class="listing_chart__list-item">
                        <div class="listing_chart__list-item--chart">
                            <div style="width: <?= $potential_rent_width ?>%;"></div>
                        </div>
                        <div class="listing_chart__list-item--title">
                            <strong>Potenzielle Miete</strong>
                            <span><?= !empty($potential_rent) ? priceGerman($potential_rent) . ' &euro;/m<sup>2</sup>' : 'N/A' ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="listing_chart">
                <div class="listing_chart__title">
                    <h4>Kaufdaten</h4>
                </div>
                <div class="listing_chart__list">
                    <div class="listing_chart__list-item">
                        <div class="listing_chart__list-item--chart">
                            <div style="width: <?= $purchase_price_width ?>%;"></div>
                        </div>
                        <div class="listing_chart__list-item--title">
                            <strong>Kaufpreis</strong>
                            <span><?= !empty($purchase_price) ? priceGerman($purchase_price) . ' &euro;/m<sup>2</sup>' : 'N/A' ?></span>
                        </div>
                    </div>
                    <div class="listing_chart__list-item">
                        <div class="listing_chart__list-item--chart">
                            <div style="width: <?= $avarage_buying_width ?>%;"></div>
                        </div>
                        <div class="listing_chart__list-item--title">
                            <strong>Durchschnittlicher Kaufpreis</strong>
                            <span><?= !empty($avarage_buying) ? priceGerman($avarage_buying) . ' &euro;/m<sup>2</sup>' : 'N/A' ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($potential_return > 15) { ?>
    <div class="listing_redruler">
        <div class="listing_redruler__notice">
            <span>Bei diesem Objekt liegen vermutlich Baumängel vor</span>
        </div>
    </div>
<?php } ?>

<?php if (empty($potential_return_format) && empty($multiplier_gross_format) && empty($current_usage) && empty($inspection_type)) { ?>
<?php } else { ?>
    <div class="listing_ruler">

        <div class="listing_ruler__bar"></div>

        <div class="listing_ruler__blocks">
            <div class="listing_ruler__blocks-inner">

                <div class="listing_ruler__blocks-col">
                    <div class="listing_ruler__blocks-item <?= !empty($potential_return_format) ? $potential_return_color : 'gray' ?>">
                        <div class="listing_ruler__blocks-item--info">
                            <span><?= !empty($potential_return_format) ? $potential_return_format . '%' : 'N/A' ?></span>
                        </div>
                        <div class="listing_ruler__blocks-item--title">
                            <span>Potentielle Rendite</span>
                        </div>
                    </div>
                </div>

                <div class="listing_ruler__blocks-col">
                    <div class="listing_ruler__blocks-item <?= !empty($multiplier_gross_format) ? $multiplier_gross_color : 'gray' ?>">
                        <div class="listing_ruler__blocks-item--info">
                            <span><?= !empty($multiplier_gross_format) ? $multiplier_gross_format : 'N/A' ?></span>
                        </div>
                        <div class="listing_ruler__blocks-item--title">
                            <span>Mietmultiplikator</span>
                        </div>
                    </div>
                </div>

                <div class="listing_ruler__blocks-col">
                    <div class="listing_ruler__blocks-item <?= !empty($current_usage) ? $current_usage_color : 'gray' ?>">
                        <div class="listing_ruler__blocks-item--info">
                            <span><?= !empty($current_usage) ? $current_usage : 'N/A' ?></span>
                        </div>
                        <div class="listing_ruler__blocks-item--title">
                            <span>Vermietungstatus</span>
                        </div>
                    </div>
                </div>

                <div class="listing_ruler__blocks-col">
                    <div class="listing_ruler__blocks-item <?= !empty($inspection_type) ? $inspection_type_color : 'gray' ?>">
                        <div class="listing_ruler__blocks-item--info">
                            <span><?= !empty($inspection_type) ? $inspection_type : 'N/A' ?></span>
                        </div>
                        <div class="listing_ruler__blocks-item--title">
                            <span>Besichtigungsart</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php } ?>