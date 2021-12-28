<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if (admin() == false) {
    redirect("Please login first!", ADMIN . '/login.php');
    exit();
}

if (!role('admin') && !role('manager')) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

if (isset($_GET['listing_id']) && !empty($_GET['listing_id'])) {
    $listing_id = $_GET['listing_id'];
} else {
    redirect('Listing ID is Missing!', ADMIN . '/pending_listings.php');
}

/* $new_cats = array();
$listing_category = get_data($listing_id, 'new_cat', 'listing');
if (!empty($listing_category)) {
    $new_cats = json_decode($listing_category, true);
} */

$new_cats = getCatArray($listing_id);

$results = $db->query('SELECT * FROM nanonets_ids WHERE pdf_name = ?', $listing_id . '.pdf');
$dataSet = create_fields_set($results);

$data = $db->query('SELECT * FROM `about` WHERE `listing_id` = ?', $listing_id)->fetchArray();
if ($data == false || empty($data)) {
    $data = array(
        'living_space' => '',
        'use_space' => '',
        'plot_area' => '',
        'buildings_plot' => '',
        'development' => '',
        'floor_flat' => '',
        'floor_house' => '',
        'room_count' => '',
        'current_usage' => '',
        'demolished' => '',
        'building_type' => '',
        'business_kind' => '',
        'earn_month' => '',
        'additional_costs' => '',
        'maintenance_flat' => '',
        'maintenance_house' => '',
        'condition_flat' => '',
        'condition_furniture' => '',
    );
}

$data_floor = $db->query('SELECT * FROM `floors` WHERE `listing_id` = ?', $listing_id)->fetchArray();
if ($data_floor == false || empty($data_floor)) {
    $flezz = '';
}

$data_energy = $db->query('SELECT * FROM `energy` WHERE `listing_id` = ?', $listing_id)->fetchArray();
if ($data_energy == false || empty($data_energy)) {
    $data_energy = array(
        'construction_year' => '',
        'modernization' => '',
        'property_condition' => '',
        'heating_type' => '',
        'certificate_type' => '',
        'energy_requirements' => '',
        'efficiency_class' => '',
        'usable_years' => '',
        'construction_adapted' => '',
    );
}

//dump($data_floor);

if (isset($data_floor["rooms"])) {
    if (
        $data_floor["rooms"] == '{"floor":[{"title":"","table":[{"room":""}]}]}' ||
        $data_floor["rooms"] == '{"title":"","table":[{"room":"","count":""}]}' ||
        $data_floor["rooms"] == '{"title":"","table":[{"section":"","status":"","rent":"","space":"","rooms":[{"room":"","count":""}]}]}'
    ) {
        $flezz = '';
    } else {
        $flezz = json_decode($data_floor["rooms"], true);
    }
} else {
    $flezz = '';
}


$extended = extendStatus($flezz);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    /*********************************************/
    /* About */

    $rooms = '[{"type":"","count":""}]';
    if (isset($p['room']) && $p['room'] !== '') {
        $rooms = json_encode($p['room'], true);
    }

    /*********************************************/
    /* Floors */

    if (empty($p["floor"])) {
        //$rooms = '{"floor":[{"title":"","table":[{"room":""}]}]}';
        $floors = '';
    } else {
        $floors = json_encode($p["floor"], true);
    }

    /*********************************************/
    /* Energy */
    // nope

    $update = false;
    if (empty($error)) {

        if (check_row($listing_id, 'listing_id', 'about')) {
            $update = $db->query('UPDATE `about` SET `living_space` = ?, `use_space` = ?, `plot_area` = ?, `buildings_plot` = ?, `development` = ?, `building_type` = ?, `business_kind` = ?, `floor_flat` = ?, `floor_house` = ?, `room_count` = ?, `current_usage` = ?, `demolished` = ?, `condition_flat` = ?, `condition_furniture` = ?, `earn_month` = ?, `additional_costs` = ?, `maintenance_flat` = ?, `maintenance_house` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `listing_id` = ?', $p['living_space'], $p['use_space'], $p['plot_area'], $p['buildings_plot'], $p['development'], $p['building_type'], $p['business_kind'], $p['floor_flat'], $p['floor_house'], $rooms, $p['current_usage'], $p['demolished'], $p['condition_flat'], $p['condition_furniture'], $p['earn_month'], $p['additional_costs'], $p['maintenance_flat'], $p['maintenance_house'], $listing_id);
        } else {
            $update = $db->query('INSERT INTO `about`(`id`, `listing_id`, `living_space`, `use_space`, `plot_area`, `buildings_plot`, `development`, `building_type`, `business_kind`, `floor_flat`, `floor_house`, `room_count`, `current_usage`, `demolished`, `condition_flat`, `condition_furniture`, `earn_month`, `additional_costs`, `maintenance_flat`, `maintenance_house`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $listing_id, $p['living_space'], $p['use_space'], $p['plot_area'], $p['buildings_plot'], $p['development'], $p['building_type'], $p['business_kind'], $p['floor_flat'], $p['floor_house'], $rooms, $p['current_usage'], $p['demolished'], $p['condition_flat'], $p['condition_furniture'], $p['earn_month'], $p['additional_costs'], $p['maintenance_flat'], $p['maintenance_house']);
        }

        if (check_row($listing_id, 'listing_id', 'floors')) {
            $update_floors = $db->query('UPDATE `floors` SET `rooms` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `listing_id` = ?', $floors, $listing_id);
        } else {
            $update_floors = $db->query('INSERT INTO `floors`(`id`, `listing_id`, `rooms`) VALUES (NULL, ?, ?)', $listing_id, $floors);
        }

        if (check_row($listing_id, 'listing_id', 'energy')) {
            $update_certificate = $db->query('UPDATE `energy` SET `construction_year` = ?, `modernization` = ?, `usable_years` = ?, `construction_adapted` = ?, `property_condition` = ?, `heating_type` = ?, `certificate_type` = ?, `efficiency_class` = ?, `energy_requirements` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `listing_id` = ?', $p['construction_year'], $p['modernization'], $p['usable_years'], $p['construction_adapted'], $p['property_condition'], $p['heating_type'], $p['certificate_type'], $p['efficiency_class'], $p['energy_requirements'], $listing_id);
        } else {
            $update_certificate = $db->query('INSERT INTO `energy`(`id`, `listing_id`, `construction_year`, `modernization`, `usable_years`, `construction_adapted`, `property_condition`, `heating_type`, `certificate_type`, `efficiency_class`, `energy_requirements`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $listing_id, $p['construction_year'], $p['modernization'], $p['usable_years'], $p['construction_adapted'], $p['property_condition'], $p['heating_type'], $p['certificate_type'], $p['efficiency_class'], $p['energy_requirements']);
        }
    }
    if ($update) {
        redirect('Details Updated Successfully!', ADMIN . '/update_third.php?listing_id=' . $listing_id);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Update Step 3 - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Update Step 3</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Listings</li>
                            <li class="breadcrumb-item active">Step 3</li>
                        </ol>
                    </div>

                    <?php include HOME . '/admin/inc/steps.php'; ?>

                    <form action="<?= fullUrl() ?>" method="POST">

                        <div class="alert alert-primary">
                            <i class="fa fa-info-circle"></i>
                            <span>Aktuell in Bearbeitung:</span>
                            <strong><?= get_data($listing_id, 'listing_label', 'listing'); ?></strong>
                        </div>

                        <?php if (isset($error) && !empty($error)) {
                            echo '<div class="alert alert-danger"><ul class="mb-0">';
                            foreach ($error as $e) {
                                echo '<li>' . $e . '</li>';
                            }
                            echo '</ul></div>';
                        } ?>

                        <div class="card mb-4">
                            <div class="card-header">Informationen zur ZV</div>
                            <div class="card-body">

                                <?php get_extra_field_html($dataSet, 'Wohnfläche'); ?>
                                <?php get_extra_field_html($dataSet, 'Nutzfläche'); ?>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Wohnfläche</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="living_space" placeholder="Wohnfläche nummerisch" value="<?= isset($p['living_space']) ? $p['living_space'] : $data['living_space']; ?>">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">m<sup>2</sup></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Nutzfläche</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="use_space" placeholder="Nutzfläche" value="<?= isset($p['use_space']) ? $p['use_space'] : $data['use_space']; ?>">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">m<sup>2</sup></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Grundstücksfläche_gesamt'); ?>
                                <?php get_extra_field_html($dataSet, 'Wohnungseinheiten_Anzahl'); ?>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Grundstücksfläche</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="plot_area" placeholder="Grundstücksfläche nummerisch" value="<?= isset($p['plot_area']) ? $p['plot_area'] : $data['plot_area']; ?>">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">m<sup>2</sup></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Gebäude auf dem Grundstück</label>
                                        <input type="text" class="form-control" name="buildings_plot" placeholder="Anzahl der Gebäude auf dem Grundstück" value="<?= isset($p['buildings_plot']) ? $p['buildings_plot'] : $data['buildings_plot']; ?>">
                                    </div>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Zustand_Gemeinschaftseigentum'); ?>

                                <div class="form-group">
                                    <label>Entwicklungszustand <span class="custom_input" data-name="development" data-holder="Entwicklungszustand">Custom</span></label>
                                    <select name="development" class="form-select" data-select="<?= isset($p['development']) ? $p['development'] : $data['development']; ?>">
                                        <option value="">- Entwicklungszustand auswählen -</option>
                                        <option value="developed">Developed</option>
                                        <option value="pending">pending</option>
                                    </select>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Gebäudeart_1'); ?>
                                <?php get_extra_field_html($dataSet, 'Gewerbebetrieb'); ?>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Gebäudeart <span class="custom_input" data-name="building_type" data-holder="Gebäudeart">Custom</span></label>
                                        <select name="building_type" class="form-select" data-select="<?= isset($p['building_type']) ? $p['building_type'] : $data['building_type']; ?>">
                                            <option value="">- Gebäudeart auswählen -</option>
                                            <option value="Einfamilienhaus">Einfamilienhaus</option>
                                            <option value="Doppelhaushälfte">Doppelhaushälfte</option>
                                            <option value="Reihenhaus">Reihenhaus</option>
                                            <option value="Mehrfamilienhaus">Mehrfamilienhaus</option>
                                            <option value="Bungalow">Bungalow</option>
                                            <option value="Wohnhaus">Wohnhaus</option>
                                            <option value="Wohn-/ und Geschäftshaus">Wohn-/ und Geschäftshaus</option>
                                            <option value="Reihenmittelhaus">Reihenmittelhaus</option>
                                            <option value="Reiheneckhaus">Reiheneckhaus</option>
                                            <option value="Stadtvilla">Stadtvilla</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Gewerbeart <span class="custom_input" data-name="business_kind" data-holder="Gewerbeart">Custom</span></label>
                                        <select name="business_kind" class="form-select" data-select="<?= isset($p['business_kind']) ? $p['business_kind'] : $data['business_kind']; ?>">
                                            <option value="">- Art des Gewerbebetriebs auswählen -</option>
                                            <option value="Gaststätte">Gaststätte</option>
                                            <option value="Arztpraxis">Arztpraxis</option>
                                            <option value="Gastgewerbe">Gastgewerbe</option>
                                            <option value="Hotel">Hotel</option>
                                            <option value="Produzierendes Gewerbe">Produzierendes Gewerbe</option>
                                            <option value="Lagerhalle">Lagerhalle</option>
                                            <option value="Werkstatt">Werkstatt</option>
                                            <option value="Büroräume">Büroräume</option>
                                        </select>
                                    </div>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Stockwerk_Wohnung'); ?>
                                <?php get_extra_field_html($dataSet, 'Stockwerk_Gebäude'); ?>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Stockwerk Wohnung</label>
                                        <input type="text" class="form-control" name="floor_flat" placeholder="Stockwerkwohnung nummerisch oder DG, KG" value="<?= isset($p['floor_flat']) ? $p['floor_flat'] : $data['floor_flat']; ?>">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Stockwerke Gebäude</label>
                                        <input type="text" class="form-control" name="floor_house" placeholder="Anzahl der Gesamtstockwerke" value="<?= isset($p['floor_house']) ? $p['floor_house'] : $data['floor_house']; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="room_count">Rooms</label>
                                    <div class="room-list">

                                        <?php
                                        if (check_row($listing_id, 'listing_id', 'about')) {
                                            if (isset($p['room']) && $p['room'] !== '') {
                                                $result = $p['room'];
                                            } else {
                                                $result = json_decode($data["room_count"], true);
                                            }
                                            foreach ($result as $k => $item) {
                                        ?>
                                                <div class="room-item" data-next="<?= $k ?>">
                                                    <div class="delete-room">
                                                        <i class="fa fa-times"></i>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-6 col-12">
                                                            <label>Room Type</label>
                                                            <input type="text" class="form-control" name="room[<?= $k ?>][type]" placeholder="Room Type" value="<?= $item["type"] ?>">
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                            <label>Room Count</label>
                                                            <input type="text" class="form-control" name="room[<?= $k ?>][count]" placeholder="Room Count" value="<?= $item["count"] ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <div class="room-item" data-next="0">
                                                <div class="delete-room">
                                                    <i class="fa fa-times"></i>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-6 col-12">
                                                        <label>Room Type</label>
                                                        <input type="text" class="form-control" name="room[0][type]" placeholder="Room Type" value="">
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <label>Room Count</label>
                                                        <input type="text" class="form-control" name="room[0][count]" placeholder="Room Count" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>

                                    </div>
                                    <div class="room-btn">
                                        <div class="btn btn-secondary add-room-row">Add New Row</div>
                                    </div>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Vermietungsstatus'); ?>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Vermietungsstatus <span class="custom_input" data-name="current_usage" data-holder="Vermietungsstatus">Custom</span></label>
                                        <select name="current_usage" class="form-select" data-select="<?= isset($p['current_usage']) ? $p['current_usage'] : $data['current_usage']; ?>">
                                            <option value="">- Derzeitigen Vermietungsstatus auswählen -</option>
                                            <option value="Vermietet">Vermietet</option>
                                            <option value="Eigennutzung">Eigennutzung</option>
                                            <option value="Leerstehend">Leerstehend</option>
                                            <option value="Verpachtet">Verpachtet</option>
                                            <option value="Nicht bewohnbar">Nicht bewohnbar</option>
                                            <option value="Nicht vermietet">Nicht vermietet</option>
                                            <option value="Unbekannt">Unbekannt</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Abzureißendes Gebäude <span class="custom_input" data-name="demolished" data-holder="Abzureißendes Gebäude">Custom</span></label>
                                        <select name="demolished" class="form-select" data-select="<?= isset($p['demolished']) ? $p['demolished'] : $data['demolished']; ?>">
                                            <option value="">- Ja / Nein -</option>
                                            <option value="Ja">Ja</option>
                                            <option value="Nein">Nein</option>
                                        </select>
                                    </div>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Zustand_Wohnung'); ?>
                                <?php get_extra_field_html($dataSet, 'Ausstattungsstandard'); ?>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Zustand Wohnung <span class="custom_input" data-name="condition_flat" data-holder="Zustand Wohnung">Custom</span></label>
                                        <select name="condition_flat" class="form-select" data-select="<?= isset($p['condition_flat']) ? $p['condition_flat'] : $data['condition_flat']; ?>">
                                            <option value="">- Zustand Wohnung auswählen -</option>
                                            <option value="Durchschnittlich">Durchschnittlich</option>
                                            <option value="Unterdurchschnittlich">Unterdurchschnittlich</option>
                                            <option value="Dem Alter entsprechend">Dem Alter entsprechend</option>
                                            <option value="Gepflegt">Gepflegt</option>
                                            <option value="Gehoben">Gehoben</option>
                                            <option value="Vernachlässigt">Vernachlässigt</option>
                                            <option value="Verwahrlost">Verwahrlost </option>
                                            <option value="Teilmodernisiert">Teilmodernisiert</option>
                                            <option value="Modernisiert">Modernisiert</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <label>Ausstattungsstandart <span class="custom_input" data-name="condition_furniture" data-holder="Ausstattungsstandart">Custom</span></label>
                                        <select name="condition_furniture" class="form-select" data-select="<?= isset($p['condition_furniture']) ? $p['condition_furniture'] : $data['condition_furniture']; ?>">
                                            <option value="">- Ausstattungsstandart auswählen -</option>
                                            <option value="Durchschnittlich ">Durchschnittlich</option>
                                            <option value="Unterdurchnittlich">Unterdurchnittlich</option>
                                            <option value="Gepflegt ">Gepflegt</option>
                                            <option value="Dem Alter entsprechend ">Dem Alter entsprechend</option>
                                            <option value="Modernisiert">Modernisiert</option>
                                            <option value="Teilmodernisiert ">Teilmodernisiert</option>
                                            <option value="Vernachlässigt">Vernachlässigt</option>
                                            <option value="Stark verschmutzt ">Stark verschmutzt</option>
                                        </select>
                                    </div>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Mieteinnahmen'); ?>

                                <?php get_extra_field_html($dataSet, 'Hausgeld'); ?>

                                <div class="form-group row">

                                    <div class="col-md-6 col-12">
                                        <label>Mieteinnahmen/ monatlich</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="earn_month" placeholder="Monatliche Mieteinnahmen, nummerisch" value="<?= isset($p['earn_month']) ? $p['earn_month'] : $data['earn_month']; ?>">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">&euro;</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <label>Hausgeld</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="additional_costs" placeholder="Hausgeld pro Monat, nummerisch" value="<?= isset($p['additional_costs']) ? $p['additional_costs'] : $data['additional_costs']; ?>">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">&euro;</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <?php get_extra_field_html($dataSet, 'Instandhaltungsrücklage_Gemeinschaft'); ?>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Instandhaltungsrücklage Wohnung</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="maintenance_flat" placeholder="Instandhaltungsrücklage Wohnung, nummerisch" value="<?= isset($p['maintenance_flat']) ? $p['maintenance_flat'] : $data['maintenance_flat']; ?>">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">&euro;</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Instandhaltungsrücklage Gemeinschaft</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="maintenance_house" placeholder="Instandhaltungsrücklagen Gemeinschaft, nummerisch" value="<?= isset($p['maintenance_house']) ? $p['maintenance_house'] : $data['maintenance_house']; ?>">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">&euro;</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">Räume</div>
                            <div class="card-body">

                                <?php
                                $showBlank = false;
                                if ($extended) {
                                ?>

                                    <?php
                                    if (check_row($listing_id, 'listing_id', 'floors')) {
                                        if (!empty($flezz)) { ?>
                                            <div class="floor-list">
                                                <?php foreach ($flezz as $k => $item) { ?>
                                                    <div class="rmmsk-pack" data-current="<?= $k ?>">
                                                        <div class="delete-rmmsk-main">
                                                            <i class="fa fa-trash"></i>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Stockwerk</label>
                                                            <input type="text" class="form-control" name="floor[<?= $k ?>][title]" value="<?= isset($item['title']) ? $item['title'] : '' ?>" placeholder="Stockwerk">
                                                        </div>

                                                        <div class="rmmsk-table">
                                                            <div class="rmmsk-list">

                                                                <?php foreach ($item['table'] as $j => $tb) { ?>
                                                                    <div class="rmmsk-item" data-identity="<?= $j ?>">
                                                                        <div class="delete-rmmsk-row">
                                                                            <i class="fa fa-times"></i>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Aufteilung im Stockwerk</label>
                                                                            <input type="text" class="form-control" name="floor[<?= $k ?>][table][<?= $j ?>][section]" value="<?= isset($tb['section']) ? $tb['section'] : '' ?>" placeholder="(links, rechts, mitte)">
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <div class="col-md-4 col-12">
                                                                                <label>Vermietungsstatus</label>
                                                                                <input type="text" class="form-control" name="floor[<?= $k ?>][table][<?= $j ?>][status]" value="<?= isset($tb['status']) ? $tb['status'] : '' ?>" placeholder="Vermietungsstatus (z.B. vermietet, persönlich bewohnt, unbekannt, Leerstand)">
                                                                            </div>
                                                                            <div class="col-md-4 col-12">
                                                                                <label>Monatliche Mieteinnahmen</label>
                                                                                <input type="text" class="form-control" name="floor[<?= $k ?>][table][<?= $j ?>][rent]" value="<?= isset($tb['rent']) ? $tb['rent'] : '' ?>" placeholder="Mieteinnahmen pro Monat">
                                                                            </div>
                                                                            <div class="col-md-4 col-12">
                                                                                <label>Wohnfläche</label>
                                                                                <input type="text" class="form-control" name="floor[<?= $k ?>][table][<?= $j ?>][space]" value="<?= isset($tb['space']) ? $tb['space'] : '' ?>" placeholder="Wohnfläche">
                                                                            </div>
                                                                        </div>
                                                                        <div class="lmsk-list">

                                                                            <?php foreach ($tb['rooms'] as $l => $hg) { ?>
                                                                                <div class="lmsk-item" data-identity="<?= $l ?>">
                                                                                    <div class="delete-lmsk-row">
                                                                                        <i class="fa fa-times"></i>
                                                                                    </div>
                                                                                    <div class="form-group row">
                                                                                        <div class="col-md-6 col-12">
                                                                                            <label>Zimmername</label>
                                                                                            <input type="text" class="form-control" name="floor[<?= $k ?>][table][<?= $j ?>][rooms][<?= $l ?>][room]" value="<?= isset($hg['room']) ? $hg['room'] : '' ?>" placeholder="Zimmername, z.B. Keller, Schlafzimmer, Abstellraum">
                                                                                        </div>
                                                                                        <div class="col-md-6 col-12">
                                                                                            <label>Anzahl der Räume</label>
                                                                                            <input type="text" class="form-control" name="floor[<?= $k ?>][table][<?= $j ?>][rooms][<?= $l ?>][count]" value="<?= isset($hg['count']) ? $hg['count'] : '' ?>" placeholder="Anzahl der Räume, nummerisch ohne Einheit">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>

                                                                        </div>
                                                                        <div class="rmmsk-btn">
                                                                            <div class="btn btn-secondary btn-sm add-floor-lmsk-row">Weiteren Raum hinzufügen</div>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>

                                                            </div>
                                                            <div class="rmmsk-btn">
                                                                <div class="btn btn-dark add-floor-rmmsk-row">Weitere Wohneinheit hinzufügen</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>

                                            <div class="form-group row">
                                                <div class="btn btn-outline-dark floor-whole-btn">Weiteres Stockwerk hinzufügen</div>
                                            </div>
                                    <?php
                                        } else {
                                            $showBlank = true;
                                        }
                                    } else {
                                        $showBlank = true;
                                    }
                                    ?>

                                <?php } else { ?>

                                    <?php
                                    if (check_row($listing_id, 'listing_id', 'floors')) {
                                        $flezz = json_decode($data_floor["rooms"], true);
                                        if (!empty($flezz)) { ?>
                                            <div class="floor-list">
                                                <?php foreach ($flezz as $k => $item) { ?>
                                                    <div class="room-pack" data-current="<?= $k ?>">
                                                        <div class="delete-room-main">
                                                            <i class="fa fa-trash"></i>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Floor Title</label>
                                                            <input type="text" class="form-control" name="floor[<?= $k ?>][title]" placeholder="Floor Title" value="<?= $item['title']; ?>">
                                                        </div>

                                                        <div class="room-table">
                                                            <div class="room-list">
                                                                <?php if (isset($item["table"]) && !empty($item["table"])) { ?>
                                                                    <?php foreach ($item["table"] as $j => $tb) { ?>
                                                                        <div class="room-item" data-identity="<?= $j + 1 ?>">
                                                                            <div class="delete-room-row">
                                                                                <i class="fa fa-times"></i>
                                                                            </div>

                                                                            <div class="form-group row">
                                                                                <div class="col-md-6 col-12">
                                                                                    <label>Zimmername</label>
                                                                                    <input type="text" class="form-control" name="floor[<?= $k ?>][table][<?= $j ?>][room]" value="<?= isset($tb['room']) ? $tb['room'] : ''; ?>" placeholder="Zimmername, z.B. Keller, Schlafzimmer, Abstellraum">
                                                                                </div>
                                                                                <div class="col-md-6 col-12">
                                                                                    <label>Anzahl der Räume</label>
                                                                                    <input type="text" class="form-control" name="floor[<?= $k ?>][table][<?= $j ?>][count]" value="<?= isset($tb['room']) ? $tb['count'] : ''; ?>" placeholder="Anzahl der Räume, nummerisch ohne Einheit">
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="room-btn">
                                                                <div class="btn btn-secondary add-floor-room-row">Add New Row</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>

                                            <div class="form-group row">
                                                <div class="btn btn-outline-dark floor-btn">Weiteres Stockwerk hinzufügen</div>
                                            </div>
                                    <?php
                                        } else {
                                            $showBlank = true;
                                        }
                                    } else {
                                        $showBlank = true;
                                    }
                                    ?>

                                <?php } ?>

                                <?php if ($showBlank) { ?>
                                    <?php if (in_array('Mehrfamilienhäuser', $new_cats) || in_array('Wohn-/ Geschäftshäuser', $new_cats)) {  ?>

                                        <div class="floor-list">
                                            <div class="rmmsk-pack" data-current="0">
                                                <div class="delete-rmmsk-main">
                                                    <i class="fa fa-trash"></i>
                                                </div>

                                                <div class="form-group">
                                                    <label>Stockwerk</label>
                                                    <input type="text" class="form-control" name="floor[0][title]" placeholder="Stockwerk">
                                                </div>

                                                <div class="rmmsk-table">
                                                    <div class="rmmsk-list">

                                                        <div class="rmmsk-item" data-identity="0">
                                                            <div class="delete-rmmsk-row">
                                                                <i class="fa fa-times"></i>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Aufteilung im Stockwerk</label>
                                                                <input type="text" class="form-control" name="floor[0][table][0][section]" placeholder="(links, rechts, mitte)">
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-4 col-12">
                                                                    <label>Vermietungsstatus</label>
                                                                    <input type="text" class="form-control" name="floor[0][table][0][status]" placeholder="Vermietungsstatus (z.B. vermietet, persönlich bewohnt, unbekannt, Leerstand)">
                                                                </div>
                                                                <div class="col-md-4 col-12">
                                                                    <label>Monatliche Mieteinnahmen</label>
                                                                    <input type="text" class="form-control" name="floor[0][table][0][rent]" placeholder="Mieteinnahmen pro Monat">
                                                                </div>
                                                                <div class="col-md-4 col-12">
                                                                    <label>Wohnfläche</label>
                                                                    <input type="text" class="form-control" name="floor[0][table][0][space]" placeholder="Wohnfläche">
                                                                </div>
                                                            </div>
                                                            <div class="lmsk-list">
                                                                <div class="lmsk-item" data-identity="0">
                                                                    <div class="delete-lmsk-row">
                                                                        <i class="fa fa-times"></i>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-md-6 col-12">
                                                                            <label>Zimmername</label>
                                                                            <input type="text" class="form-control" name="floor[0][table][0][rooms][0][room]" placeholder="Zimmername, z.B. Keller, Schlafzimmer, Abstellraum">
                                                                        </div>
                                                                        <div class="col-md-6 col-12">
                                                                            <label>Anzahl der Räume</label>
                                                                            <input type="text" class="form-control" name="floor[0][table][0][rooms][0][count]" placeholder="Anzahl der Räume, nummerisch ohne Einheit">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="rmmsk-btn">
                                                                <div class="btn btn-secondary btn-sm add-floor-lmsk-row">Weiteren Raum hinzufügen</div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="rmmsk-btn">
                                                        <div class="btn btn-dark add-floor-rmmsk-row">Weitere Wohneinheit hinzufügen</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="btn btn-outline-dark floor-whole-btn">Weiteres Stockwerk hinzufügen</div>
                                        </div>

                                    <?php } else { ?>

                                        <div class="floor-list">
                                            <div class="room-pack" data-current="0">
                                                <div class="delete-room-main">
                                                    <i class="fa fa-trash"></i>
                                                </div>

                                                <div class="form-group">
                                                    <label>Floor Title</label>
                                                    <input type="text" class="form-control" name="floor[0][title]" placeholder="Floor Title">
                                                </div>

                                                <div class="room-table">
                                                    <div class="room-list">
                                                        <div class="room-item" data-identity="1">
                                                            <div class="delete-room-row">
                                                                <i class="fa fa-times"></i>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-md-6 col-12">
                                                                    <label>Zimmername</label>
                                                                    <input type="text" class="form-control" name="floor[0][table][0][room]" placeholder="Zimmername, z.B. Keller, Schlafzimmer, Abstellraum">
                                                                </div>
                                                                <div class="col-md-6 col-12">
                                                                    <label>Anzahl der Räume</label>
                                                                    <input type="text" class="form-control" name="floor[0][table][0][count]" placeholder="Anzahl der Räume, nummerisch ohne Einheit">
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="room-btn">
                                                        <div class="btn btn-secondary add-floor-room-row" data-current="0" data-next="1">Add New Row</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="btn btn-outline-dark floor-btn">Weiteres Stockwerk hinzufügen</div>
                                        </div>

                                    <?php } ?>
                                <?php } ?>

                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">Heizung und Gebäude</div>
                            <div class="card-body">

                                <?php get_extra_field_html($dataSet, 'Baujahr'); ?>
                                <?php get_extra_field_html($dataSet, 'Modernisierungsmaßnahme'); ?>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Baujahr</label>
                                        <input type="text" class="form-control" name="construction_year" placeholder="Baujahr" value="<?= isset($p['construction_year']) ? $p['construction_year'] : $data_energy['construction_year']; ?>">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Modernisierungsmaßnahme</label>
                                        <input type="text" class="form-control" name="modernization" placeholder="Modernisierungsmaßnahme" value="<?= isset($p['modernization']) ? $p['modernization'] : $data_energy['modernization']; ?>">
                                    </div>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Restnutzungsdauer'); ?>
                                <?php get_extra_field_html($dataSet, 'Baujahr_fiktiv'); ?>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Restnutzungsdauer</label>
                                        <input type="text" class="form-control" name="usable_years" placeholder="Restnutzungsdauer" value="<?= isset($p['usable_years']) ? $p['usable_years'] : $data_energy['usable_years']; ?>">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Baujahr fiktiv</label>
                                        <input type="text" class="form-control" name="construction_adapted" placeholder="Baujahr fiktiv" value="<?= isset($p['construction_adapted']) ? $p['construction_adapted'] : $data_energy['construction_adapted']; ?>">
                                    </div>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Heizungsart'); ?>
                                <?php get_extra_field_html($dataSet, 'Heizungsart_1'); ?>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Zustand Gebäude <span class="custom_input" data-name="property_condition" data-holder="Zustand Gebäude">Custom</span></label>
                                        <select name="property_condition" class="form-select" data-select="<?= isset($p['property_condition']) ? $p['property_condition'] : $data_energy['property_condition']; ?>">
                                            <option value="">- Zustand Gebäude auswählen -</option>
                                            <option value="Durchschnittlich">Durchschnittlich</option>
                                            <option value="Unterdurchschnittlich">Unterdurchschnittlich</option>
                                            <option value="Dem Alter entsprechend">Dem Alter entsprechend</option>
                                            <option value="Gepflegt">Gepflegt</option>
                                            <option value="Modernisiert">Modernisiert</option>
                                            <option value="Gehoben">Gehoben</option>
                                            <option value="Teilmodernisiert">Teilmodernisiert</option>
                                            <option value="Verwahrlost">Verwahrlost</option>
                                            <option value="Vernachlässigt">Vernachlässigt</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <label>Heizungsart <span class="custom_input" data-name="heating_type" data-holder="Heizungsart">Custom</span></label>
                                        <select name="heating_type" class="form-select" data-select="<?= isset($p['heating_type']) ? $p['heating_type'] : $data_energy['heating_type']; ?>">
                                            <option value="">- Heizungsartauswählen -</option>
                                            <option value="Etagenheizung">Etagenheizung</option>
                                            <option value="Zentralheizung">Zentralheizung</option>
                                        </select>
                                    </div>
                                </div>

                                <hr>

                                <?php get_extra_field_html($dataSet, 'Energiezertifikat_Typ'); ?>

                                <div class="form-group">
                                    <label>Energiezertifikat_Typ <span class="custom_input" data-name="property_condition" data-holder="Energiezertifikat_Typ">Custom</span></label>
                                    <select name="certificate_type" class="form-select" data-select="<?= isset($p['certificate_type']) ? $p['certificate_type'] : $data_energy['certificate_type']; ?>">
                                        <option value="">- Energieausweisauswählen -</option>
                                        <option value="Verbrauchsausweis">Verbrauchsausweis</option>
                                        <option value="Bedarfsausweis">Bedarfsausweis</option>
                                    </select>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Energiebedarf'); ?>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Energiebedarf</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="energy_requirements" placeholder="kWh/m^2a" value="<?= isset($p['energy_requirements']) ? $p['energy_requirements'] : $data_energy['energy_requirements']; ?>">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">kWh/m^2a</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Energy Efficiency Class</label>
                                        <select name="efficiency_class" class="form-control disabled" data-select="<?= isset($p['efficiency_class']) ? $p['efficiency_class'] : $data_energy['efficiency_class']; ?>">
                                            <option value="">Select Certificate Status</option>
                                            <option value="A+">A+</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                            <option value="E">E</option>
                                            <option value="F">F</option>
                                            <option value="G">G</option>
                                            <option value="H">H</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group mb-5">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus-square"></i>
                                <span>Übers Objekt updaten</span>
                            </button>
                        </div>

                    </form>

                </div>
            </main>

            <?php include HOME . '/admin/inc/footer.php'; ?>

        </div>

    </div>

    <?php include HOME . '/admin/inc/scripts.php'; ?>

</body>

</html>