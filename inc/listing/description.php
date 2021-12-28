<?php
$about = $db->query('SELECT * FROM `about` WHERE `listing_id` = ?', $listing_id)->fetchArray();
$acquisition = $db->query('SELECT * FROM `acquisition` WHERE `listing_id` = ?', $listing_id)->fetchArray();
$energy = $db->query('SELECT * FROM `energy` WHERE `listing_id` = ?', $listing_id)->fetchArray();
$construction = $db->query('SELECT * FROM `construction` WHERE `listing_id` = ?', $listing_id)->fetchArray();
$description = $db->query('SELECT * FROM `description` WHERE `listing_id` = ?', $listing_id)->fetchArray();

$zip = 0;
$object_taxy = 0;

if (isset($list['object_address']) && !empty($list['object_address'])) {
    //$lisy = explode(', ', $list['object_address']);
    //$ciy = explode(' ', end($lisy));
    $zipCode = getZip($list['object_address']);
    $object_taxy = taxRate($zipCode);
}

if ($acquisition) {
    $object_taxy = price($acquisition['tax_percentage']);
    $listing_value = price($acquisition['object_price']);
}

// sum of rates
$addition_rates_sum = '';
$addition_rates_sum_extra = '';

if (!empty($object_taxy)) {
    $addition_rates_sum = $object_taxy + 0.5 + 0.5;
    $addition_rates_sum_extra = $object_taxy + 2.74 + 1.5 + 0.5;
}

$land_transfer_tax = (($listing_value * $object_taxy) / 100);
$object_court_costs = (($listing_value * 0.5) / 100);
$object_land_register = (($listing_value * 0.5) / 100);

$maklerprovision = (($listing_value * 2.74) / 100);
$object_court_costs_extra = (($listing_value * 1.5) / 100);

if ($acquisition) {
    $object_court_costs = price($acquisition['court_costs']);
    $object_land_register = price($acquisition['land_register']);
    $land_transfer_tax = price($acquisition['transfer_tax']);
}

$acquistion_total = $listing_value + $land_transfer_tax + $object_court_costs + $object_land_register;
$acquistion_total_add = $land_transfer_tax + $object_court_costs + $object_land_register;

$acquistion_total_extra = $listing_value + $maklerprovision + $land_transfer_tax + $object_court_costs_extra + $object_land_register;
$acquistion_total_add_extra = $maklerprovision + $land_transfer_tax + $object_court_costs_extra + $object_land_register;

$showAquision = true;
if ($listing_value == '0.00' || $listing_value == '0') {
    $showAquision = false;
}

// category
/* $listing_category = get_data($listing_id, 'new_cat', 'listing');
if (!empty($listing_category)) {
    $new_cats = json_decode($listing_category, true);
} */
$new_cats = getCatArray($listing_id);

$room_count = get_col_data($listing_id, 'listing_id', 'listing_rooms', 'details');

$cons = '';
$construct = '';
if (isset($construction["construction"])) {
    $construct = $construction["construction"];
}

if ($construct && !empty($construct)) {
    if (
        $construct == '[{"title":"","total":"","table":[{"desc":"","estimated":""}]}]' ||
        $construct == '[{"title":"","table":[{"desc":"","estimated":""}]}]' ||
        $construct == '{"backlog":[{"title":"","table":[{"estimated":""}]}]}' ||
        $construct == '{"backlog":{"title":"","total":""}}'
    ) {
        $cons = '';
    } else {
        $cons = json_decode($construct, true);
    }
}

?>

<div class="desc_tab">

    <?php if ($about && !empty($about)) { ?>
        <div class="tab_content" data-sectitle="about_listing" data-secelem="about_more_body-column--item">
            <div class="about_more_title">
                <h4>Informationen zur Immobilie</h4>
            </div>
            <div class="about_more_body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="about_more_body-column">
                            <?php if (in_array('Eigentumswohnungen', $new_cats) || in_array('Einfamilienhäuser', $new_cats) || in_array('Zweifamilienhaus', $new_cats) || in_array('Mehrfamilienhäuser', $new_cats) || in_array('Wohn-/ Geschäftshäuser', $new_cats)) { ?>
                                <div class="about_more_body-column--item">
                                    <strong>Wohnfläche</strong>
                                    <span><?= !empty($about['living_space']) ? $about['living_space'] . 'm<sup>2</sup>' : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Einfamilienhäuser', $new_cats) || in_array('Zweifamilienhaus', $new_cats) || in_array('Mehrfamilienhäuser', $new_cats) || in_array('Wohn-/ Geschäftshäuser', $new_cats) || in_array('Gewerbegrundstücke', $new_cats) || in_array('Baugrundstücke', $new_cats)) { ?>
                                <div class="about_more_body-column--item">
                                    <strong>Nutzfläche</strong>
                                    <span><?= !empty($about['use_space']) ? $about['use_space'] . 'm<sup>2</sup>' : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Eigentumswohnungen', $new_cats) || in_array('Einfamilienhäuser', $new_cats) || in_array('Zweifamilienhaus', $new_cats)) { ?>
                                <div class="about_more_body-column--item">
                                    <strong>Vermietungsstatus</strong>
                                    <span><?= !empty($about['current_usage']) ? $about['current_usage'] : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Eigentumswohnungen', $new_cats) || in_array('Einfamilienhäuser', $new_cats) || in_array('Zweifamilienhaus', $new_cats) || in_array('Mehrfamilienhäuser', $new_cats) || in_array('Wohn-/ Geschäftshäuser', $new_cats) || in_array('Gewerbegrundstücke', $new_cats) || in_array('Baugrundstücke', $new_cats)) { ?>
                                <div class="about_more_body-column--item">
                                    <strong>Grundstücksfläche</strong>
                                    <span><?= !empty($about['plot_area']) ? $about['plot_area'] . 'm<sup>2</sup>' : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Eigentumswohnungen', $new_cats) || in_array('Wohn-/ Geschäftshäuser', $new_cats)) { ?>
                                <div class="about_more_body-column--item">
                                    <strong>Stockwerk</strong>
                                    <span><?= !empty($about['floor_flat']) && !empty($about['floor_house']) ? $about['floor_flat'] . ' ( von ' . $about['floor_house'] . ' )' : 'N/A' ?></span>
                                </div>
                            <?php } elseif (in_array('Mehrfamilienhäuser', $new_cats) || in_array('Einfamilienhäuser', $new_cats) || in_array('Zweifamilienhaus', $new_cats)) { ?>
                                <div class="about_more_body-column--item">
                                    <strong>Stockwerk</strong>
                                    <span><?= !empty($about['floor_house']) ? $about['floor_house'] : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Mehrfamilienhäuser', $new_cats)) { ?>
                                <div class="about_more_body-column--item">
                                    <strong>Wohnungseinheiten</strong>
                                    <span><?= !empty($about['listing_flats']) ? $about['listing_flats'] : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Gewerbegrundstücke', $new_cats) || in_array('Baugrundstücke', $new_cats)) { ?>
                                <div class="about_more_body-column--item">
                                    <strong>Buildings on the plot area</strong>
                                    <span><?= !empty($about['buildings_plot']) ? $about['buildings_plot'] : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Eigentumswohnungen', $new_cats)) { ?>
                                <?php
                                $rooms = '';
                                if ($about['room_count'] && !empty($about['room_count'])) {
                                    $rooms = json_decode($about['room_count'], true);
                                ?>
                                    <div class="about_more_body-column--item">
                                        <strong>Zimmer</strong>

                                        <?php if (!empty($rooms) && $about['room_count'] !== '[{"type":"","count":""}]') { ?>
                                            <span>
                                                <div class="about_more_body-column--item---sub">
                                                    <ul>
                                                        <?php
                                                        foreach ($rooms as $room) {
                                                            echo '<li>
                                                        <strong>' . (!empty($room["type"]) ? $room["type"] : 'N/A') . '</strong>
                                                        <span>' . (!empty($room["count"]) ? $room["count"] : 'N/A') . '</span>
                                                    </li>';
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </span>
                                        <?php } else { ?>
                                            <span>N/A</span>
                                        <?php } ?>


                                    </div>
                                <?php } else { ?>
                                    <div class="about_more_body-column--item">
                                        <strong>Zimmer</strong>
                                        <span>N/A</span>
                                    </div>
                                <?php } ?>
                            <?php } ?>


                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="about_more_body-column">

                            <?php if (in_array('Gewerbegrundstücke', $new_cats) || in_array('Baugrundstücke', $new_cats)) { ?>
                                <div class="about_more_body-column--item extra">
                                    <strong>Entwicklungszustand</strong>
                                    <span><?= !empty($about['development']) ? $about['development'] : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Baugrundstücke', $new_cats)) { ?>
                                <div class="about_more_body-column--item extra">
                                    <strong>Nutzung</strong>
                                    <span><?= !empty($about['current_usage']) ? $about['current_usage'] : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Eigentumswohnungen', $new_cats) || in_array('Einfamilienhäuser', $new_cats) || in_array('Zweifamilienhaus', $new_cats) || in_array('Mehrfamilienhäuser', $new_cats) || in_array('Wohn-/ Geschäftshäuser', $new_cats)) { ?>
                                <div class="about_more_body-column--item extra">
                                    <strong>Ausstattungsstandart</strong>
                                    <span><?= !empty($about['condition_furniture']) ? $about['condition_furniture'] : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Eigentumswohnungen', $new_cats)) { ?>
                                <div class="about_more_body-column--item extra">
                                    <strong>Bauzustand Wohnung</strong>
                                    <span><?= !empty($about['condition_flat']) ? $about['condition_flat'] : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Eigentumswohnungen', $new_cats)) { ?>
                                <div class="about_more_body-column--item extra">
                                    <strong>Hausgeld</strong>
                                    <span><?= !empty($about['additional_costs']) ? price($about['additional_costs']) . ' &euro;' : 'N/A' ?></span>
                                </div>
                            <?php } ?>


                            <?php if (in_array('Mehrfamilienhäuser', $new_cats)) { ?>
                                <div class="about_more_body-column--item extra">
                                    <strong>Zimmerzahl</strong>
                                    <span><?= !empty($room_count) ? $room_count : 'N/A'  ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Eigentumswohnungen', $new_cats) || in_array('Einfamilienhäuser', $new_cats) || in_array('Zweifamilienhaus', $new_cats) || in_array('Mehrfamilienhäuser', $new_cats) || in_array('Wohn-/ Geschäftshäuser', $new_cats) || in_array('Gewerbegrundstücke', $new_cats) || in_array('Baugrundstücke', $new_cats)) { ?>
                                <div class="about_more_body-column--item extra">
                                    <strong>Mieteinnahmen</strong>
                                    <span><?= !empty($about['earn_month']) ? $about['earn_month'] : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Eigentumswohnungen', $new_cats)) { ?>
                                <div class="about_more_body-column--item extra">
                                    <strong>Instandhaltungsrücklagen gemeinschaft</strong>
                                    <span><?= !empty($about['maintenance_flat']) ? price(object_price($about['maintenance_flat'])) . ' &euro;' : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Baugrundstücke', $new_cats) == false && in_array('Einfamilienhäuser', $new_cats) && in_array('Zweifamilienhaus', $new_cats) == false && in_array('Mehrfamilienhäuser', $new_cats) == false) { ?>
                                <div class="about_more_body-column--item extra">
                                    <strong>Instandhaltungsrücklage</strong>
                                    <span><?= !empty($about['maintenance_house']) ? price($about['maintenance_house']) . ' &euro;' : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Wohn-/ Geschäftshäuser', $new_cats) || in_array('Gewerbegrundstücke', $new_cats)) { ?>
                                <div class="about_more_body-column--item extra">
                                    <strong>Gewerbeart</strong>
                                    <span><?= !empty($about['business_kind']) ? $about['business_kind'] : 'N/A' ?></span>
                                </div>
                            <?php } ?>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($showAquision) { ?>
        <div class="tab_content">
            <div class="about_more_title">
                <h4>Geschätzte Kaufkosten: (Bei Kauf zum Verkehrswert)</h4>
            </div>
            <div class="about_more_body">
                <div class="row">

                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="about_more_body-column aquision_calc right">

                            <div class="about_more_body-column--item">
                                <strong>Kaufpreis</strong>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="object_price" placeholder="Kaufpreis" value="<?= !empty($listing_value) ? priceGerman($listing_value) : '0'; ?>" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">&euro;</div>
                                    </div>
                                </div>
                            </div>
                            <div class="about_more_body-column--item">
                                <strong>Makler</strong>
                                <div class="input-group">
                                    <input type="text" class="form-control disabled" name="real_estate_agent" placeholder="Makler" value="0" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">&euro;</div>
                                    </div>
                                </div>
                            </div>
                            <div class="about_more_body-column--item">
                                <strong>Notargebühr</strong>
                                <div class="input-group">
                                    <input type="text" class="form-control disabled" name="notary_fees" placeholder="Notargebühr" value="0" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">&euro;</div>
                                    </div>
                                </div>
                            </div>
                            <div class="about_more_body-column--item">
                                <input type="hidden" name="object_tax" value="<?= !empty($object_taxy) ? $object_taxy : '0'; ?>">
                                <strong>Grunderwerbssteuer <?= !empty($object_taxy) ? '(' . rateGerman($object_taxy) . '%)' : '' ?></strong>
                                <div class="input-group">
                                    <input type="text" class="form-control disabled" name="land_transfer_tax" placeholder="Grunderwerbssteuer" value="<?= !empty($land_transfer_tax) ? priceGerman($land_transfer_tax) : '0'; ?>" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">&euro;</div>
                                    </div>
                                </div>
                            </div>
                            <div class="about_more_body-column--item">
                                <strong>Gerichtskosten</strong>
                                <div class="input-group">
                                    <input type="text" class="form-control disabled" name="court_costs" placeholder="Gerichtskosten" value="<?= !empty($object_court_costs) ? priceGerman($object_court_costs) : '0'; ?>" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">&euro;</div>
                                    </div>
                                </div>
                            </div>
                            <div class="about_more_body-column--item">
                                <strong>Grundbuchkosten</strong>
                                <div class="input-group">
                                    <input type="text" class="form-control disabled" name="land_register" placeholder="Grundbuchkosten" value="<?= !empty($object_land_register) ? priceGerman($object_land_register) : '0'; ?>" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">&euro;</div>
                                    </div>
                                </div>
                            </div>
                            <div class="about_more_body-column--item highlight">
                                <strong>Gesamt</strong>
                                <div class="input-group">
                                    <input type="text" class="form-control disabled" name="aquistion_total" placeholder="Gesamt" value="<?= !empty($acquistion_total) ? priceGerman($acquistion_total) : '0' ?>" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">&euro;</div>
                                    </div>
                                </div>
                            </div>

                            <div class="about_more_body-column--btn">
                                <a href="<?= LINK ?>/finance/" class="btn btn-blue">
                                    <span>Finanzierung Sichern</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

                        <div class="pricing-ruler">
                            <div class="pricing-ruler--title">
                                <h4>Bei einer Zwangsversteigerung</h4>
                            </div>
                            <div class="pricing-ruler--body">
                                <div class="pricing-ruler--item">
                                    <div class="pricing-ruler--item_title ruler-1">
                                        <div class="pricing-ruler--item_title__col left">
                                            <p>Kaufpreis</p>
                                            <h4><span class="html_object_price"><?= !empty($listing_value) ? priceGerman($listing_value) : '0'; ?></span> <span>&euro;</span></h4>
                                        </div>
                                        <div class="pricing-ruler--item_title__col right">
                                            <p>Nebenkosten <?= !empty($addition_rates_sum) ? rateGerman($addition_rates_sum) . '%' : '' ?></p>
                                            <h4><span id="aquistion_total"><?= !empty($acquistion_total_add) ? priceGerman($acquistion_total_add) : '0' ?></span> <span>&euro;</span></h4>
                                        </div>
                                    </div>
                                    <div class="pricing-ruler--item_bar">
                                        <div class="pricing-ruler--item_bar__block item-1"></div>
                                        <div class="pricing-ruler--item_bar__block item-2"></div>
                                        <div class="pricing-ruler--item_bar__block item-3"></div>
                                        <div class="pricing-ruler--item_bar__block item-4"></div>
                                    </div>
                                </div>
                                <div class="pricing-ruler--info">
                                    <div class="pricing-ruler--info_item item-3">
                                        <div class="pricing-ruler--info_item__info">Grunderwerbssteuer</div>
                                        <div class="pricing-ruler--info_item__value"><?= !empty($object_taxy) ? rateGerman($object_taxy) . '%' : '' ?></div>
                                        <div class="pricing-ruler--info_item__amount"><span class="html_land_transfer_tax"><?= !empty($land_transfer_tax) ? priceGerman($land_transfer_tax) : '0'; ?></span> <span>&euro;</span></div>
                                    </div>
                                    <div class="pricing-ruler--info_item item-4">
                                        <div class="pricing-ruler--info_item__info">Gerichtskosten</div>
                                        <div class="pricing-ruler--info_item__value">0,5%</div>
                                        <div class="pricing-ruler--info_item__amount"><span id="court_costs"><?= !empty($object_court_costs) ? priceGerman($object_court_costs) : '0'; ?></span> <span>&euro;</span></div>
                                    </div>
                                    <div class="pricing-ruler--info_item item-5">
                                        <div class="pricing-ruler--info_item__info">Grundbuchkosten</div>
                                        <div class="pricing-ruler--info_item__value">0,5%</div>
                                        <div class="pricing-ruler--info_item__amount"><span class="html_land_register"><?= !empty($object_land_register) ? priceGerman($object_land_register) : '0'; ?></span> <span>&euro;</span></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="pricing-ruler">
                            <div class="pricing-ruler--title">
                                <h4>Bei typischen Immobilien</h4>
                            </div>
                            <div class="pricing-ruler--body">
                                <div class="pricing-ruler--item">
                                    <div class="pricing-ruler--item_title ruler-2">
                                        <div class="pricing-ruler--item_title__col left">
                                            <p>Kaufpreis</p>
                                            <h4><span class="html_object_price"><?= !empty($listing_value) ? priceGerman($listing_value) : '0'; ?></span> <span>&euro;</span></h4>
                                        </div>
                                        <div class="pricing-ruler--item_title__col right">
                                            <p>Nebenkosten <?= !empty($addition_rates_sum_extra) ? rateGerman($addition_rates_sum_extra) . '%' : '' ?></p>
                                            <h4><span id="aquistion_total_extra"><?= !empty($acquistion_total_add_extra) ? priceGerman($acquistion_total_add_extra) : '0' ?></span> <span>&euro;</span></h4>
                                        </div>
                                    </div>
                                    <div class="pricing-ruler--item_bar">
                                        <div class="pricing-ruler--item_bar__block item-5"></div>
                                        <div class="pricing-ruler--item_bar__block item-6"></div>
                                        <div class="pricing-ruler--item_bar__block item-7"></div>
                                        <div class="pricing-ruler--item_bar__block item-8"></div>
                                        <div class="pricing-ruler--item_bar__block item-9"></div>
                                    </div>
                                </div>
                                <div class="pricing-ruler--info">
                                    <div class="pricing-ruler--info_item item-2">
                                        <div class="pricing-ruler--info_item__info">Maklerprovision</div>
                                        <div class="pricing-ruler--info_item__value">2,74%</div>
                                        <div class="pricing-ruler--info_item__amount"><span id="maklerprovision"><?= !empty($maklerprovision) ? priceGerman($maklerprovision) : '0'; ?></span> <span>&euro;</span></div>
                                    </div>
                                    <div class="pricing-ruler--info_item item-3">
                                        <div class="pricing-ruler--info_item__info">Grunderwerbssteuer</div>
                                        <div class="pricing-ruler--info_item__value"><?= !empty($object_taxy) ? rateGerman($object_taxy) . '%' : '' ?></div>
                                        <div class="pricing-ruler--info_item__amount"><span class="html_land_transfer_tax"><?= !empty($land_transfer_tax) ? priceGerman($land_transfer_tax) : '0'; ?></span> <span>&euro;</span></div>
                                    </div>
                                    <div class="pricing-ruler--info_item item-4">
                                        <div class="pricing-ruler--info_item__info">Notar</div>
                                        <div class="pricing-ruler--info_item__value">1,5%</div>
                                        <div class="pricing-ruler--info_item__amount"><span id="court_costs_extra"><?= !empty($object_court_costs_extra) ? priceGerman($object_court_costs_extra) : '0'; ?></span> <span>&euro;</span></div>
                                    </div>
                                    <div class="pricing-ruler--info_item item-5">
                                        <div class="pricing-ruler--info_item__info">Grundbuchkosten</div>
                                        <div class="pricing-ruler--info_item__value">0,5%</div>
                                        <div class="pricing-ruler--info_item__amount"><span class="html_land_register"><?= !empty($object_land_register) ? priceGerman($object_land_register) : '0'; ?></span> <span>&euro;</span></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($energy && !empty($energy)) { ?>
        <div class="tab_content">
            <div class="about_more_title">
                <h4>Gebäudemerkmale und Heizung</h4>
            </div>
            <div class="about_more_body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="about_more_body-column">
                            <div class="about_more_body-column--item">
                                <strong>Baujahr</strong>
                                <span><?= !empty($energy['construction_year']) ? $energy['construction_year'] : 'N/A' ?></span>
                            </div>
                            <div class="about_more_body-column--item">
                                <strong>Modernisierung</strong>
                                <span><?= !empty($energy['modernization']) ? $energy['modernization'] : 'N/A' ?></span>
                            </div>
                            <div class="about_more_body-column--item">
                                <strong>Restnutzungsdauer</strong>
                                <span><?= !empty($energy['usable_years']) ? $energy['usable_years'] : 'N/A' ?></span>
                            </div>
                            <div class="about_more_body-column--item">
                                <strong>Gebäudezustand</strong>
                                <span><?= !empty($energy['property_condition']) ? $energy['property_condition'] : 'N/A' ?></span>
                            </div>

                            <?php if (in_array('Baugrundstücke', $new_cats)) { ?>
                                <div class="about_more_body-column--item">
                                    <strong>Gebäudeart</strong>
                                    <span><?= !empty($about['building_type']) ? $about['building_type'] : 'N/A' ?></span>
                                </div>
                            <?php } ?>


                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="about_more_body-column">

                            <?php if (in_array('Eigentumswohnungen', $new_cats) || in_array('Einfamilienhäuser', $new_cats) || in_array('Zweifamilienhaus', $new_cats)) { ?>
                                <div class="about_more_body-column--item">
                                    <strong>Gebäudeart</strong>
                                    <span><?= !empty($about['building_type']) ? $about['building_type'] : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <?php if (in_array('Mehrfamilienhäuser', $new_cats) || in_array('Wohn-/ Geschäftshäuser', $new_cats) || in_array('Gewerbegrundstücke', $new_cats) || in_array('Baugrundstücke', $new_cats)) { ?>
                                <div class="about_more_body-column--item">
                                    <strong>Restnutzungsdauer</strong>
                                    <span><?= !empty($energy['construction_adapted']) ? $energy['construction_adapted'] : 'N/A' ?></span>
                                </div>
                            <?php } ?>

                            <div class="about_more_body-column--item">
                                <strong>Heizungsart</strong>
                                <span><?= !empty($energy['heating_type']) ? $energy['heating_type'] : 'N/A' ?></span>
                            </div>
                            <div class="about_more_body-column--item">
                                <strong>Art des Energieausweises</strong>
                                <span><?= !empty($energy['certificate_type']) ? $energy['certificate_type'] : 'N/A' ?></span>
                            </div>
                            <div class="about_more_body-column--item">
                                <strong>Energieverbrauch</strong>
                                <span><?= !empty($energy['energy_requirements']) ? $energy['energy_requirements'] . ' kWh/m<sup>2</sup>a' : 'N/A' ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($energy['efficiency_class']) && !empty($energy['energy_requirements'])) { ?>
                    <div class="efficiency">
                        <?php
                        $enery_width = energy_width($energy['efficiency_class']);
                        $energy_transform = energy_transform($energy['energy_requirements']);
                        ?>
                        <div class="efficiency_tooltip <?= $enery_width > 66 ? 'right' : '' ?> <?= $energy_transform ? 'blocking' : '' ?>" style="left: <?= $enery_width ?>%;">
                            <h4><?= $energy['energy_requirements'] ?> kWh/m<sup>2</sup>a</h4>
                            <p>Energieeffizienzklasse <?= $energy['efficiency_class'] ?></p>
                        </div>
                        <div class="efficiency_ruler">
                            <ul>
                                <li>A+</li>
                                <li>A</li>
                                <li>B</li>
                                <li>C</li>
                                <li>D</li>
                                <li>E</li>
                                <li>F</li>
                                <li>G</li>
                                <li>H</li>
                            </ul>
                        </div>
                        <div class="efficiency_label">
                            <ul>
                                <li>0</li>
                                <li>30</li>
                                <li>50</li>
                                <li>75</li>
                                <li>100</li>
                                <li>130</li>
                                <li>160</li>
                                <li>200</li>
                                <li>250</li>
                                <li></li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    <?php } ?>

    <?php if ($cons && !empty($cons)) { ?>
        <div class="tab_content">
            <div class="about_more_title">
                <h4>Instandsetzungskosten (nach Gutachten)</h4>
            </div>
            <div class="about_more_body">

                <?php
                if (isset($cons["backlog"])) {
                    $cons = $cons["backlog"];
                }
                ?>

                <div class="construction">
                    <div class="accordion" id="cons_list">

                        <?php foreach ($cons as $k => $item) { ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button <?= $k == 0 ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $k ?>">
                                        <span><?= $item["title"] ?></span>
                                    </button>
                                </h2>
                                <div id="collapse<?= $k ?>" class="accordion-collapse collapse  <?= $k == 0 ? 'show' : '' ?>" data-bs-parent="#cons_list">
                                    <div class="accordion-body">
                                        <div class="construct-table <?= $addClass ?>" data-panel="<?= $k ?>">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Beschreibung</th>
                                                            <th scope="col" style="width: 300px;" class="text-right">Geschätzt (laut Gutachter)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($item["table"] as $tb) {
                                                            echo "<tr>
                                                                <td>" . (!empty($tb['desc']) ? $tb['desc'] : 'N/A') . "</td>
                                                                <td class=\"text-right\">" . (!empty($tb['estimated']) ? $tb['estimated'] : 'N/A') . "</td>
                                                            </tr>";
                                                        } ?>

                                                    </tbody>
                                                </table>
                                            </div>

                                            <?php if (!empty($item["total"])) { ?>
                                                <div class="construction_total">
                                                    <h4>
                                                        <strong>In Summe:</strong>
                                                        <span><?= price($item["total"]) ?> &euro;</span>
                                                    </h4>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    <?php } ?>

    <?php if ($description && !empty($description)) { ?>

        <?php
        $conta = '';
        $comm = '';
        if (isset($description['contaminated']) && !empty($description['contaminated'])) {
            $conta = $description['contaminated'];
        }
        if (isset($description['commitments']) && !empty($description['commitments'])) {
            $comm = $description['commitments'];
        }
        ?>
        <?php if (!empty($conta) || !empty($comm)) { ?>
            <div class="tab_content">
                <div class="about_more_body">
                    <div class="row">

                        <?php if (!empty($conta)) { ?>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="object <?= $conta == 1 ? 'red' : 'green' ?>">
                                    <div class="object_title">
                                        <h4>Altlasten</h4>
                                    </div>
                                    <div class="object_desc">
                                        <?php if ($conta == 1) {
                                            echo '<p>Bei diesem Grundstück liegen Altlasten vor</p>';
                                        } elseif ($conta == 2) {
                                            echo '<p>Bei diesem Grundstück liegen wahrscheinlich keine Altlasten vor</p>';
                                        } else {
                                            echo $conta;
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (!empty($comm)) { ?>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="object <?= $comm == 1 ? 'red' : 'green' ?>">
                                    <div class="object_title">
                                        <h4>Vermietungsverträge</h4>
                                    </div>
                                    <div class="object_desc">
                                        <?php if ($comm == 1) {
                                            echo '<p>Für dieses Objekt bestehen Vermietungsverpflichtungen</p>';
                                        } elseif ($comm == 2) {
                                            echo '<p>Für dieses Objekt bestehen keine Vermietungsverpflichtungen</p>';
                                        } else {
                                            echo $comm;
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>

    <div class="tab_content">
        <div class="about_more_body">

            <div class="listing_bugs__alert"></div>

            <div class="feedback">
                <div class="feedback_title">
                    <p>Unsere Gutachtenauswertung lernt immernoch dazu. Hast du Fehler gefunden? Dann teile uns diese gerne mit:</p>
                </div>
                <div class="feedback_form">
                    <form id="listing_bugs__feedback" action="<?= fullUrl() ?>" method="POST">
                        <div class="form-group">
                            <input type="hidden" name="listing_id" value="<?= $listing_id ?>">
                            <textarea name="info" class="form-control" placeholder="Welche Information wurde falsch dargestellt oder vergessen?"></textarea>
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-blue" disabled>
                                <i class="fa fa-paper-plane"></i>
                                <span>Abschicken</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>