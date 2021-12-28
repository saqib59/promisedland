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
    //$check = $db->query('SELECT * FROM `details` WHERE `listing_id` = ?', $listing_id)->fetchAll();
    //if ($check) addEdit();
} else {
    redirect('Listing ID is Missing!', ADMIN . '/pending_listings.php');
}

$results = $db->query('SELECT * FROM nanonets_ids WHERE pdf_name = ?', $listing_id . '.pdf');
$dataSet = create_fields_set($results);

$equipments = $db->query("SELECT * FROM `equipments` WHERE `status` = 1")->fetchAll();

$model_url = '';

$listing_ownership = '';
$listing_ownership1 = '';
$listing_ownership2 = '';

$data = $db->query('SELECT * FROM `details` WHERE `listing_id` = ?', $listing_id)->fetchArray();
if ($data == false || empty($data)) {
    $data = array(
        'gallery' => '',
        'about_type' => '',
        'listing_equipment' => '',
        'listing_rooms' => '',
        'listing_flats' => '',
        'value_limit' => '',
        'model_url' => '',
    );
}

$data_forclosure = $db->query('SELECT * FROM `foreclosure` WHERE `listing_id` = ?', $listing_id)->fetchArray();
if ($data_forclosure == false || empty($data_forclosure)) {
    $data_forclosure = array(
        'inspection_date' => date("d.m.Y"),
        'inspection_status' => '',
        'inspection_type' => '',
        'inspection_participants' => '',
        'special_comments' => '',
        'denkmalschutz' => '',
    );
}

$data_facility = $db->query('SELECT * FROM `facility` WHERE `listing_id` = ?', $listing_id)->fetchArray();

if (isset($data['listing_ownership']) && $data['listing_ownership'] !== '') {
    $listing_ownership = $data['listing_ownership'];
    $listing_owner = explode('/', $data['listing_ownership']);
    $listing_ownership1 = $listing_owner[0];
    $listing_ownership2 = $listing_owner[1];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    /*********************************************/
    /* Details */

    if (!isset($p['about_type']) || $p['about_type'] == '') {
        $error[] = 'Please enter a listing title';
    }
    if (isset($p['model_url']) && $p['model_url'] !== '') {
        $model_url = $p['model_url'];
    }
    if (isset($p['listing_ownership1']) && $p['listing_ownership1'] !== '') {
        $listing_ownership1 = $p['listing_ownership1'];
    }
    if (isset($p['listing_ownership2']) && $p['listing_ownership2'] !== '') {
        $listing_ownership2 = $p['listing_ownership2'];
    }
    if ($listing_ownership1 !== '' && $listing_ownership2 !== '') {
        $listing_ownership = $listing_ownership1 . '/' . $listing_ownership2;
    }
    if (!isset($p['listing_equipment']) || empty($p['listing_equipment'])) {
        //$error[] = 'Please select 1 Equipment atleast';
        $listing_equipment = '';
    } else {
        $listing_equipment = json_encode($p['listing_equipment'], true);
    }
    if (!isset($p['listing_gallery']) || $p['listing_gallery'] == '') {
        //$error[] = 'Please select 1 image atleast';
        $listing_gallery = '';
    } else {
        $listing_gallery = json_encode($p['listing_gallery'], true);
    }

    /*********************************************/
    /* Foreclosure */

    if (isset($p['inspection_date']) && $p['inspection_date'] !== '') {
        $inspection_date = date_format(date_create($p['inspection_date']), "d.m.Y");
    }
    if (!isset($p['inspection_status']) || $p['inspection_status'] == '') {
        $p['inspection_status'] = '0';
    }
    if (!isset($p['denkmalschutz']) || $p['denkmalschutz'] == '') {
        $p['denkmalschutz'] = '0';
    }

    /*********************************************/
    /* Facilities */

    if (empty($p)) {
        $facilities_table = '';
    } else {
        $facilities_table = json_encode($p["facilities"], true);
    }

    $update = false;
    if (empty($error)) {

        if (check_row($listing_id, 'listing_id', 'details')) {
            $update = $db->query('UPDATE `details` SET `listing_id` = ?, `gallery` = ?, `about_type` = ?, `listing_equipment` = ?, `model_url` = ?, `listing_rooms` = ?, `listing_flats` = ?, `listing_ownership` = ?, `value_limit` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE listing_id = ?', $listing_id, $listing_gallery, $p['about_type'], $listing_equipment, $model_url, $p['listing_rooms'], $p['listing_flats'], $listing_ownership, $p['value_limit'], $listing_id);
        } else {
            $update = $db->query('INSERT INTO `details`(`id`, `listing_id`, `gallery`, `about_type`, `listing_equipment`, `model_url`, `listing_rooms`, `listing_flats`, `listing_ownership`, `value_limit`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $listing_id, $listing_gallery, $p['about_type'], $listing_equipment, $model_url, $p['listing_rooms'], $p['listing_flats'], $listing_ownership, $p['value_limit']);
        }

        if (check_row($listing_id, 'listing_id', 'foreclosure')) {
            $update = $db->query('UPDATE `foreclosure` SET `denkmalschutz` = ?, `inspection_type` = ?, `inspection_status` = ?, `inspection_date` = ?, `inspection_participants` = ?, `special_comments` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `listing_id` = ?', $p['denkmalschutz'], $p['inspection_type'], $p['inspection_status'], $inspection_date, $p['inspection_participants'], $p['special_comments'], $listing_id);
        } else {
            $update = $db->query('INSERT INTO `foreclosure`(`id`, `listing_id`, `denkmalschutz`, `inspection_type`, `inspection_status`, `inspection_date`, `inspection_participants`, `special_comments`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)', $listing_id, $p['denkmalschutz'], $p['inspection_type'], $p['inspection_status'], $inspection_date, $p['inspection_participants'], $p['special_comments']);
        }

        if (check_row($listing_id, 'listing_id', 'facility')) {
            $update_facilities = $db->query('UPDATE `facility` SET `facility_table` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `listing_id` = ?', $facilities_table, $listing_id);
        } else {
            $update_facilities = $db->query('INSERT INTO `facility`(`id`, `listing_id`, `facility_table`) VALUES (NULL, ?, ?)', $listing_id, $facilities_table);
        }
    }

    if ($update) {
        redirect('Details Updated Successfully!', ADMIN . '/update_second.php?listing_id=' . $listing_id);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Update Step 2 - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Update Step 2</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Listings</li>
                            <li class="breadcrumb-item active">Step 2</li>
                        </ol>
                    </div>

                    <?php include HOME . '/admin/inc/steps.php'; ?>

                    <form action="<?= fullUrl() ?>" method="POST" enctype="multipart/form-data">

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
                            <div class="card-header">Details</div>
                            <div class="card-body">
                                <div class="shd-gallery-area">
                                    <div class="shd-gallery-info">
                                        <i class="far fa-images"></i>
                                        <p>Upload images of the listing <br>including the floor plans</p>
                                    </div>
                                    <div class="shd-gallery-btn">
                                        <span>Select Images</span>
                                    </div>
                                    <div id="img_path" data-path="listings"></div>
                                    <input type="file" name="upload_image" id="upload_image" accept="image/png, image/jpeg" />
                                </div>

                                <div id="uploaded_image" class="upg-gallery-list">
                                    <?php
                                    if (isset($p['listing_gallery']) && !empty($p['listing_gallery'])) {
                                        //$gallery = json_decode($p['listing_gallery'], true);
                                        $gallery = $p['listing_gallery'];
                                    } elseif (!isset($data['gallery']) && empty($data['gallery'])) {
                                        $gallery = '';
                                    } else {
                                        $gallery = json_decode($data['gallery'], true);
                                    }
                                    if (isset($gallery) && !empty($gallery)) {
                                        foreach ($gallery as $k) { ?>
                                            <div class="upg-inner">
                                                <input name="listing_gallery[]" type="hidden" value="<?= $k ?>">
                                                <img src="<?= LINK . $k ?>">
                                                <div class="upg-delete" data-imgr="<?= $k ?>">
                                                    <i class="fa fa-trash"></i>
                                                </div>
                                            </div>
                                    <?php }
                                    } ?>
                                </div>

                                <div class="form-group">
                                    <label>Objekttitel</label>
                                    <input type="text" class="form-control" name="about_type" placeholder="Ansprechende Beschreibung des Objekts" value="<?= isset($p['about_type']) ? $p['about_type'] : $data['about_type']; ?>" required>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Equipment'); ?>

                                <div class="form-group">
                                    <label for="equipment">Besondere Ausstattung (sowas wie Aufzug, Pool)</label>
                                    <select class="form-select select2" name="listing_equipment[]" multiple="multiple" data-select='<?= isset($p['listing_equipment']) ? htmlentities($p['listing_equipment']) : htmlentities($data['listing_equipment']); ?>'>
                                        <?php
                                        foreach ($equipments as $equip) {
                                            echo "<option value=\"{$equip['id']}\">{$equip['label']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="shd-gallery-area mt-4">
                                    <div class="shd-gallery-info">
                                        <i class="far fa-cubes"></i>
                                        <p>Upload 3d Model of the Listing</p>
                                    </div>
                                    <div class="shd-gallery-btn">
                                        <span>Select Model</span>
                                    </div>
                                    <input type="file" name="upload_model" id="upload_model" accept=".glb" />
                                </div>

                                <?php if (isset($data['model_url']) && !empty($data['model_url'])) { ?>
                                    <div id="model-viewer">
                                        <div class="model-preview">
                                            <div class="model-delete"><i class="fa fa-trash"></i></div>
                                            <input type="hidden" name="model_url" value="<?= $data['model_url']; ?>" />
                                            <model-viewer src="<?= LINK . $data['model_url']; ?>" ar ar-modes="webxr scene-viewer quick-look" environment-image="neutral" auto-rotate camera-controls></model-viewer>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div id="model-viewer"></div>
                                <?php } ?>

                                <?php get_extra_field_html($dataSet, 'Zimmerzahl'); ?>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Anzahl der Zimmer</label>
                                        <input type="text" class="form-control" name="listing_rooms" placeholder="Zimmeranzahl nummerisch" value="<?= isset($p['listing_rooms']) ? $p['listing_rooms'] : $data['listing_rooms']; ?>">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Wohnungseinheiten</label>
                                        <input type="text" class="form-control" name="listing_flats" placeholder="Anzahl Wohnungseinheiten" value="<?= isset($p['listing_flats']) ? $p['listing_flats'] : $data['listing_flats']; ?>">
                                    </div>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Miteigentumsanteil'); ?>

                                <div class="form-group">
                                    <div class="form-group row owner-separator">
                                        <div class="col-md-6 col-12">
                                            <label>Miteigentumsanteil</label>
                                            <input type="text" class="form-control" name="listing_ownership1" placeholder="Eigenanteil" value="<?= $listing_ownership1; ?>">
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label></label>
                                            <input type="text" class="form-control" name="listing_ownership2" placeholder="Gesamtanteil" value="<?= $listing_ownership2; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Wertgrenze <span class="custom_input" data-name="value_limit" data-holder="Wertgrenze">Custom</span></label>
                                    <select name="value_limit" class="form-select" data-select="<?= isset($p['value_limit']) ? $p['value_limit'] : $data['value_limit']; ?>">
                                        <option value="">- Wertgrenze auswählen -</option>
                                        <option value="5/10">5/10</option>
                                        <option value="7/10">7/10</option>
                                        <option value="Wertgrenzen entfallen">Wertgrenzen entfallen</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">Besichtigung</div>
                            <div class="card-body">
                                <?php get_extra_field_html($dataSet, 'Aussenbesichtigung'); ?>
                                <?php get_extra_field_html($dataSet, 'Innenbesichtigung'); ?>

                                <div class="form-group">
                                    <label>Besichtigungsart <span class="custom_input" data-name="inspection_type" data-holder="Besichtigungsart">Custom</span></label>
                                    <select name="inspection_type" class="form-select" data-select="<?= isset($p['inspection_type']) ? $p['inspection_type'] : $data_forclosure['inspection_type']; ?>">
                                        <option value="">- Besichtigungsart auswählen -</option>
                                        <option value="Innenbesichtigung">Innenbesichtigung</option>
                                        <option value="Außenbesichtigung">Außenbesichtigung</option>
                                        <option value="Teilweise besichtigt">Teilweise besichtigt</option>
                                    </select>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="inspection_status" name="inspection_status" <?= $data_forclosure['inspection_status'] == '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="inspection_status">Datum der Besichtigung nicht verfügbar</label>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Wertermittlungsstichtag'); ?>

                                <div class="form-group">
                                    <label>Besichtigungsdatum</label>
                                    <input type="date" class="form-control" name="inspection_date" value="<?= isset($p['inspection_date']) ? date_format(date_create($p['inspection_date']), "Y-m-d") : date_format(date_create($data_forclosure['inspection_date']), "Y-m-d"); ?>" <?= $data_forclosure['inspection_status'] == '1' ? 'readonly' : '' ?>>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Teilnehmer_Ortsbesichtigung'); ?>
                                <?php get_extra_field_html($dataSet, 'Teilnehmer_Ortsbesichtigung_1'); ?>

                                <div class="form-group">
                                    <label>Teilnehmer an der Ortsbesichtigung</label>
                                    <textarea class="form-control" name="inspection_participants"><?= isset($p['inspection_participants']) ? $p['inspection_participants'] : $data_forclosure['inspection_participants']; ?></textarea>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Besondere Anmerkung'); ?>

                                <div class="form-group">
                                    <label>Besondere Anmerkungen</label>
                                    <textarea class="form-control" placeholder="Bitte hier besondere Anmerkungen eintragen. Sowas wie: Haus ist abgebrannt, Keller ist einsturzgefährdet" name="special_comments"><?= isset($p['special_comments']) ? $p['special_comments'] : $data_forclosure['special_comments']; ?></textarea>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Denkmalschutz'); ?>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="denkmalschutz" name="denkmalschutz" <?= $data_forclosure['denkmalschutz'] == 1 ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="denkmalschutz">Als Denkmalschutz kennzeichnen</label>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">Nebengebäude und Einrichtung</div>
                            <div class="card-body">
                                <?php get_extra_field_html($dataSet, 'Einrichtung_Mashine_1'); ?>
                                <?php get_extra_field_html($dataSet, 'Miteigentumsanteil_2'); ?>
                                <?php get_extra_field_html($dataSet, 'Nebengebäude'); ?>
                                <?php get_extra_field_html($dataSet, 'Nebengebäude_Wert'); ?>
                                <?php get_extra_field_html($dataSet, 'Sondernutzungsrecht'); ?>
                                <?php get_extra_field_html($dataSet, 'Gebäudeart_2'); ?>

                                <div class="facility_list">

                                    <?php
                                    $showBlank = false;
                                    if (check_row($listing_id, 'listing_id', 'facility')) {
                                        if (isset($data_facility["facility_table"]) && !empty($data_facility["facility_table"])) {
                                            $result = json_decode($data_facility["facility_table"], true);
                                            foreach ($result as $k => $item) {
                                    ?>
                                                <div class="facility-item" data-next="<?= $k + 1 ?>">
                                                    <div class="facility-delete">
                                                        <i class="fa fa-times"></i>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-2 col-12">
                                                            <label>Miteigentumsanteil</label>
                                                            <input type="text" class="form-control" name="facilities[<?= $k ?>][share]" placeholder="Miteigentumsanteil" value="<?= isset($item['share']) ?  $item['share'] : '' ?>">
                                                        </div>
                                                        <div class="col-md-5 col-12">
                                                            <label>Beschreibung</label>
                                                            <input type="text" class="form-control" name="facilities[<?= $k ?>][description]" placeholder="Beschreibung" value="<?= isset($item['description']) ?  $item['description'] : '' ?>">
                                                        </div>
                                                        <div class="col-md-2 col-12">
                                                            <label>Fläche</label>
                                                            <input type="text" class="form-control" name="facilities[<?= $k ?>][area]" placeholder="Fläche" value="<?= isset($item['area']) ? $item['area'] : '' ?>">
                                                        </div>
                                                        <div class="col-md-3 col-12">
                                                            <label>Geschätzter Wert</label>
                                                            <input type="text" class="form-control" name="facilities[<?= $k ?>][estimated]" placeholder="Geschätzter Wert" value="<?= isset($item['estimated']) ? $item['estimated'] : '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="facility-check">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="1" name="facilities[<?= $k ?>][check]" <?= isset($item['check']) && $item['check'] == 1 ? 'checked' : '' ?>>
                                                            <label class="form-check-label">Miteigentumsanteil nicht vorhanden</label>
                                                        </div>
                                                    </div>
                                                </div>

                                    <?php
                                            }
                                        } else {
                                            $showBlank = true;
                                        }
                                    } else {
                                        $showBlank = true;
                                    } ?>

                                    <?php if ($showBlank) { ?>
                                        <div class="facility-item" data-next="1">
                                            <div class="facility-delete">
                                                <i class="fa fa-times"></i>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-2 col-12">
                                                    <label>Miteigentumsanteil</label>
                                                    <input type="text" class="form-control" name="facilities[0][share]" placeholder="Miteigentumsanteil">
                                                </div>
                                                <div class="col-md-5 col-12">
                                                    <label>Beschreibung</label>
                                                    <input type="text" class="form-control" name="facilities[0][description]" placeholder="Beschreibung">
                                                </div>
                                                <div class="col-md-2 col-12">
                                                    <label>Fläche</label>
                                                    <input type="text" class="form-control" name="facilities[0][area]" placeholder="Fläche">
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <label>Geschätzter Wert</label>
                                                    <input type="text" class="form-control" name="facilities[0][estimated]" placeholder="Geschätzter Wert">
                                                </div>
                                            </div>
                                            <div class="facility-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1" name="facilities[0][check]">
                                                    <label class="form-check-label">Miteigentumsanteil nicht vorhanden</label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                </div>

                                <div class="form-group">
                                    <div class="btn btn-outline-dark facility-btn">Weiteren Verkehrswert hinzufügen</div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group mb-5">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus-square"></i>
                                <span>Details updaten</span>
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