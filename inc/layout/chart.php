<?php

// Wohnfläche
$listing_space = get_col_data($listing_id, 'listing_id', 'living_space', 'about');
$listing_space = price($listing_space);

// object value
$listing_value = get_data($listing_id, 'object_val', 'listing');
$listing_value = object_price($listing_value);

// object address
$listing_address = get_data($listing_id, 'object_address', 'listing');

// listing zip
$listing_zip = getZip($listing_address);

// object category
/* $new_cats = array();
$listing_cat = get_data($listing_id, 'new_cat', 'listing');
$new_catsy = json_decode($listing_cat, true);
$new_cats = array_merge($new_cats, $new_catsy); */

$new_cats = getCatArray($listing_id);

$buy_table = 'buy_house';
if (in_array('Zweifamilienhaus', $new_cats)) {
    $buy_table = 'buy_house';
} elseif (in_array('Eigentumswohnungen', $new_cats)) {
    $buy_table = 'buy_flat';
}

$purchase_price = '';
if (!empty($listing_value) && !empty($listing_space)) {
    $purchase_price = (float)$listing_value / (float)$listing_space;
    $purchase_price = round((float)$purchase_price, 2);
}

$avarage_buying = get_col_data($listing_zip, 'zip', 'avarage_rent', $buy_table);

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

?>

<?php if (user() == false) { ?>
    <div class="listing_chart-guest">
        <a href="<?= LINK ?>/packages/" class="btn btn-dark">Get Premium now</a>
    </div>
<?php } ?>

<div class="listing_chart__list <?= (user() == false) ? 'guest' : '' ?>">

    <div class="listing_chart__list-item">
        <div class="listing_chart__list-item--chart">
            <div style="width: <?= $purchase_price_width ?>%;"></div>
        </div>
        <div class="listing_chart__list-item--title">
            <strong>Kaufpreis</strong>
            <?php if (user()) { ?>
                <span><?= !empty($purchase_price) ? priceGerman($purchase_price) . ' &euro;/m<sup>2</sup>' : 'N/A' ?></span>
            <?php } else { ?>
                <span>XXX,XX &euro;/m<sup>2</sup></span>
            <?php } ?>

        </div>
    </div>
    <div class="listing_chart__list-item">
        <div class="listing_chart__list-item--chart">
            <div style="width: <?= $avarage_buying_width ?>%;"></div>
        </div>
        <div class="listing_chart__list-item--title">
            <strong>Durchschnittlicher Kaufpreis</strong>
            <?php if (user()) { ?>
                <span><?= !empty($avarage_buying) ? priceGerman($avarage_buying) . ' &euro;/m<sup>2</sup>' : 'N/A' ?></span>
            <?php } else { ?>
                <span>XXX,XX &euro;/m<sup>2</sup></span>
            <?php } ?>

        </div>
    </div>
</div>