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

$listing_id = '';

if (isset($_GET['listing_id']) && !empty($_GET['listing_id'])) {
    $listing_id = $_GET['listing_id'];
}

$results = $db->query('SELECT * FROM nanonets_ids WHERE pdf_name = ?', $listing_id . '.pdf');
$dataSet = create_fields_set($results);

if ($_SESSION['role'] == 'manager') {
    $admin_id = $_SESSION['admin'];
} else {
    $admin_id = '0';
}

$data = array(
    'platform' => '',
    'foreclosure_cat' => '',
    'foreclosure_court' => '',
    'land' => '',
    'foreclosure_place' => '',
    'auction_place' => '',
    'object_cat' => '',

    'main_cat' => '',
    'new_cat' => '',

    'object_address' => '',

    'lat' => '',
    'lng' => '',

    'object_desc' => '',
    'object_val' => '',
    'misc' => '',
    'foreclosure_date' => '',
    'foreclosure_add' => '',
    'amtlichebekanntmachung_pdf' => '',
    'gutachten_pdf' => '',
    'exposee_pdf' => '',
    'report_available' => '',
    'canceled' => '',
    'completed' => '',
);

$lat_cord = '';
$lng_cord = '';

$complete_status = '0';

if (check_row($listing_id, 'id', 'listing')) addEdit();

$edit = 0;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit = 1;
    $data = $db->query('SELECT * FROM `listing` WHERE `id` = ?', $listing_id)->fetchAll();
    if (!$data) removeEdit();
    $data = $data[0];
}

if ($data && !empty($data)) {
    $lat_cord = $data['lat'];
    $lng_cord = $data['lng'];
    $complete_status = $data['completed'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    //if(empty($lat_cord) && empty($lng_cord)) {
    if (!empty($p['object_address'])) {
        $value = geocode($p['object_address']);
        if (!empty($value)) {
            $lat_cord = $value[0];
            $lng_cord = $value[1];
        }
    }

    if (!isset($p['new_cat']) || empty($p['new_cat'])) {
        //$error[] = 'Please select 1 Equipment atleast';
        $new_cat = '';
    } else {
        $new_cat = json_encode($p['new_cat'], true);
    }

    if (isset($p['canceled']) && $p['canceled'] !== '') {

        if ($data['cancel_email'] == '0') {

            // retrieve all search orders based on this listing id
            $orders = $db->query("SELECT * FROM `search_order_results` WHERE `listing_id` = ?;", $listing_id)->fetchAll();
            if ($orders && !empty($orders)) {
                foreach ($orders as $item) {
                    $order_id = $item['order_id'];
                    $order_user = get_data($order_id, 'user', 'search_order');

                    // @@mail : send cancelled listing email
                    listing_cancelled($listing_id, $order_user);
                }
            }

            // retrieve all favorites based on this listing id
            $favs = $db->query("SELECT * FROM `favorite` WHERE `listing_id` = ?;", $listing_id)->fetchAll();
            if ($favs && !empty($favs)) {
                foreach ($favs as $item) {
                    $fav_user = $item['user_id'];

                    // @@mail : send cancelled listing email
                    listing_cancelled($listing_id, $fav_user);
                }
            }

            // update email status
            updateDatabyId('1', 'cancel_email', $listing_id, 'listing');

        }
    } else {
        $p['canceled'] = '0';
    }

    $update_listing = false;
    if (empty($error)) {
        if ($edit == 1) {
            $update_listing = $db->query('UPDATE `listing` SET `platform` = ?, `listing_label` = ?, `foreclosure_cat` = ?, `foreclosure_court` = ?, `foreclosure_place` = ? , `land` = ?, `auction_place` = ?, `object_cat` = ?, `main_cat` = ?, `new_cat` = ?, `object_address` = ?, `lat` = ?, `lng` = ?, `object_desc` = ?, `object_val` = ?, `foreclosure_date` = ?, `foreclosure_add` = ?, `amtlichebekanntmachung_pdf` = ?, `gutachten_pdf` = ?, `exposee_pdf` = ?, `misc` = ?, `report_available` = ?, `canceled` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `id` = ?;', $p['platform'], $p['listing_label'], $p['foreclosure_cat'], $p['foreclosure_court'], $p['foreclosure_place'], $p['land'], $p['auction_place'], $p['object_cat'], $p['main_cat'], $new_cat, $p['object_address'], $lat_cord, $lng_cord, $p['object_desc'], $p['object_val'], $p['foreclosure_date'], $p['foreclosure_add'], $p['amtlichebekanntmachung_pdf'], $p['gutachten_pdf'], $p['exposee_pdf'], $p['misc'], $p['report_available'], $p['canceled'], $listing_id);
        } else {
            $update_listing = $db->query('INSERT INTO `listing`(`id`, `platform`, `listing_label`, `foreclosure_cat`, `foreclosure_court`, `foreclosure_place`, `land`, `auction_place`, `object_cat`, `main_cat`, `new_cat`, `object_address`, `lat`, `lng`, `object_desc`, `object_val`, `foreclosure_date`, `foreclosure_add`, `amtlichebekanntmachung_pdf`, `gutachten_pdf`, `exposee_pdf`, `misc`, `report_available`, `canceled`, `completed`, `admin`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $p['platform'], $p['listing_label'], $p['foreclosure_cat'], $p['foreclosure_court'], $p['foreclosure_place'], $p['land'], $p['auction_place'], $p['object_cat'], $p['main_cat'], $new_cat, $p['object_address'], $lat_cord, $lng_cord, $p['object_desc'], $p['object_val'], $p['foreclosure_date'], $p['foreclosure_add'], $p['amtlichebekanntmachung_pdf'], $p['gutachten_pdf'], $p['exposee_pdf'], $p['misc'], $p['report_available'], $p['canceled'], $complete_status, $p['admin_id']);
            $listing_id = $db->lastInsertID();
        }
    }

    if ($update_listing) {
        redirect('Foreclosure Updated Successfully!', ADMIN . '/update_first.php?listing_id=' . $listing_id);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Update Step 1 - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Update Step 1</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Listings</li>
                            <li class="breadcrumb-item active">Step 1</li>
                        </ol>
                    </div>

                    <?php include HOME . '/admin/inc/steps.php'; ?>

                    <?php if (isset($listing_id) && !empty($listing_id)) { ?>
                        <div class="alert alert-primary">
                            <i class="fa fa-info-circle"></i>
                            <span>Aktuell in Bearbeitung:</span>
                            <strong><?= get_data($listing_id, 'listing_label', 'listing'); ?></strong>
                        </div>
                    <?php } ?>

                    <?php if (isset($error) && !empty($error)) {
                        echo '<div class="alert alert-danger"><ul class="mb-0">';
                        foreach ($error as $e) {
                            echo '<li>' . $e . '</li>';
                        }
                        echo '</ul></div>';
                    } ?>

                    <form action="<?= fullUrl() ?>" method="POST">

                        <div class="card mb-5">
                            <div class="card-body">

                                <?php if (isset($_GET['listing_id']) && !empty($_GET['listing_id'])) { ?>
                                    <input name="listing_label" type="hidden" value="<?= get_data($listing_id, 'listing_label', 'listing'); ?>">
                                <?php } ?>

                                <input name="admin_id" type="hidden" value="<?= $admin_id; ?>">

                                <input type="hidden" name="foreclosure_add" value="<?= isset($p['foreclosure_add']) ? $p['foreclosure_add'] : $data['foreclosure_add']; ?>">
                                <input type="hidden" name="auction_place" value="<?= isset($p['auction_place']) ? $p['auction_place'] : $data['auction_place']; ?>">

                                <?php get_extra_field_html($dataSet, 'Aktenzeichen'); ?>

                                <div class="form-group select-radio" data-value="<?= isset($p['report_available']) ? $p['report_available'] : $data['report_available']; ?>">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="report_available1" name="report_available" value="long">
                                        <label class="form-check-label" for="report_available1">Langgutachten liegt vor</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="report_available2" name="report_available" value="short">
                                        <label class="form-check-label" for="report_available2">Kurzgutachten liegt vor</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="report_available3" name="report_available" value="none">
                                        <label class="form-check-label" for="report_available3">Kein Gutachten</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Portal</label>
                                    <input type="text" class="form-control" name="platform" value="<?= isset($p['platform']) ? $p['platform'] : $data['platform']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Art der Zwangsversteigerung</label>
                                    <input type="text" class="form-control" name="foreclosure_cat" value="<?= isset($p['foreclosure_cat']) ? $p['foreclosure_cat'] : $data['foreclosure_cat']; ?>">
                                </div>

                                <?php get_extra_field_html($dataSet, 'Amtsgericht'); ?>

                                <div class="form-group">
                                    <label>Amtsgericht</label>
                                    <input type="text" class="form-control" name="foreclosure_court" value="<?= isset($p['foreclosure_court']) ? $p['foreclosure_court'] : $data['foreclosure_court']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Ort der Versteigerung</label>
                                    <input type="text" class="form-control" name="foreclosure_place" value="<?= isset($p['foreclosure_place']) ? $p['foreclosure_place'] : $data['foreclosure_place']; ?>">
                                </div>

                                <?php get_extra_field_html($dataSet, 'Grundbuchblatt'); ?>
                                <?php get_extra_field_html($dataSet, 'Grundbuchort'); ?>

                                <div class="form-group">
                                    <label>Grundbuchort und Grundbuchblatt</label>
                                    <input type="text" class="form-control" name="land" value="<?= isset($p['land']) ? $p['land'] : $data['land']; ?>">
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Objektkategorie (Bearbeitervermerk)</label>
                                        <input type="text" class="form-control" name="object_cat" value="<?= isset($p['object_cat']) ? $p['object_cat'] : $data['object_cat']; ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Objektkategorie (Main)</label>
                                        <select name="main_cat[]" class="form-select" data-select="<?= isset($p['main_cat']) ? $p['main_cat'] : $data['main_cat']; ?>">
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

                                </div>


                                <div class="form-group">
                                    <label>Objektkategorie (wird Kunden gezeigt)</label>
                                    <select name="new_cat[]" class="form-select select2" multiple="multiple" data-select="<?= isset($p['new_cat']) ? htmlentities($p['new_cat']) : htmlentities($data['new_cat']); ?>">
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

                                <div class="form-group">
                                    <label>Objektadresse</label>
                                    <input type="text" class="form-control" name="object_address" value="<?= isset($p['object_address']) ? $p['object_address'] : $data['object_address']; ?>">
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Lat</label>
                                        <input type="text" class="form-control" name="lat" value="<?= $lat_cord; ?>" readonly>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Lng</label>
                                        <input type="text" class="form-control" name="lng" value="<?= $lng_cord; ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Objekt- und Lagebeschreibung</label>
                                    <textarea id="foreclosure_desc" class="form-control" name="object_desc"><?= isset($p['object_desc']) ? $p['object_desc'] : $data['object_desc']; ?></textarea>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Vekehrswert'); ?>

                                <div class="form-group">
                                    <label>Verkehrswert (immer reines Zahlenformat 10000, Werte addieren falls mehrere dort stehen)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="object_val" value="<?= isset($p['object_val']) ? $p['object_val'] : $data['object_val']; ?>">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">&euro;</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Ansprechpartner des Gläubigers</label>
                                    <textarea class="form-control" name="misc"><?= isset($p['misc']) ? $p['misc'] : $data['misc']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Datum der Versteigerung (nicht bearbeiten)</label>
                                    <input type="text" class="form-control" name="foreclosure_date" value="<?= isset($p['foreclosure_date']) ? $p['foreclosure_date'] : $data['foreclosure_date']; ?>">
                                </div>
                                <!-- <div class="form-group">
                                    <label>Foreclosure Address</label>
                                    <input type="text" class="form-control" name="foreclosure_add" value="<?= isset($p['foreclosure_add']) ? $p['foreclosure_add'] : $data['foreclosure_add']; ?>">
                                </div> -->
                                <div class="form-group">
                                    <label>Datei Amtliche Bekanntmachung</label>
                                    <input type="text" class="form-control" name="amtlichebekanntmachung_pdf" value="<?= isset($p['amtlichebekanntmachung_pdf']) ? $p['amtlichebekanntmachung_pdf'] : $data['amtlichebekanntmachung_pdf']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Datei Gutachten</label>
                                    <input type="text" class="form-control" name="gutachten_pdf" value="<?= isset($p['gutachten_pdf']) ? $p['gutachten_pdf'] : $data['gutachten_pdf']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Datei Exposé</label>
                                    <input type="text" class="form-control" name="exposee_pdf" value="<?= isset($p['exposee_pdf']) ? $p['exposee_pdf'] : $data['exposee_pdf']; ?>">
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="canceled" name="canceled" <?= $data['canceled'] == 1 ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="canceled">Abgesagt</label>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> Listing updaten</button>
                                </div>

                            </div>
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