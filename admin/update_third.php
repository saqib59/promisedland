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

$results = $db->query('SELECT * FROM nanonets_ids WHERE pdf_name = ?', $listing_id . '.pdf');
$dataSet = create_fields_set($results);

/*********************************/

$object_price = get_data($listing_id, 'object_val', 'listing');
$object_price = object_price($object_price);

$object_tax_rate = 0;
$transfer_tax = 0;
$court_costs = 0;
$land_register = 0;
$total_cost = 0;

$object_address = get_data($listing_id, 'object_address', 'listing');
$state = getStatebyAddress($object_address);
$obj_taxy = $object_tax_rate = taxRate($state);

$transfer_tax = (($object_price * $obj_taxy) / 100);
$court_costs = (($object_price * 0.5) / 100);
$land_register = (($object_price * 0.5) / 100);
$total_cost = $object_price + $transfer_tax  + $court_costs + $land_register;

/*********************************/

$data = $db->query('SELECT * FROM `construction` WHERE `listing_id` = ?', $listing_id)->fetchArray();
$data_status = $db->query('SELECT * FROM `description` WHERE `listing_id` = ?', $listing_id)->fetchArray();
$data_acqu = $db->query('SELECT * FROM `acquisition` WHERE `listing_id` = ?', $listing_id)->fetchArray();

if (empty($data_status)) {
    $data_status = array(
        'contaminated' => '',
        'commitments' => '',
    );
}

if (empty($data_acqu)) {
    $data_acqu = array(
        'object_price' => '',
        'transfer_tax' => '',
        'court_costs' => '',
        'land_register' => '',
        'total_cost' => '',
    );
}


/*********************************/
if (check_row($listing_id, 'listing_id', 'acquisition')) {
    $object_tax_rate = $data_acqu['tax_percentage'];
    $object_price = price($data_acqu['object_price']);
    $transfer_tax = price($data_acqu['transfer_tax']);
    $court_costs = price($data_acqu['court_costs']);
    $land_register = price($data_acqu['land_register']);
    //$total_cost = price($data_acqu['total_cost']);
    $total_cost = $object_price + $transfer_tax  + $court_costs + $land_register;
}
/*********************************/

if ($data == false || empty($data)) {
    $cons = '';
} else {
    $construct = $data["construction"];
    if ($construct && !empty($construct)) {
        if ($construct == '{"backlog":[{"title":"","table":[{"estimated":""}]}]}') {
            $cons = '';
        } else {
            $cons = json_decode($construct, true);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    /*********************************************/
    /* Costs */

    if (empty($p["backlog"])) {
        $construction = '';
    } else {
        $construction = json_encode($p["backlog"], true);
    }

    /*********************************************/
    /* Status */
    // none

    /*********************************************/
    /* Acquision */

    $object_tax_rate = $p['tax_percentage'];
    $object_price = $p['object_price'];
    $transfer_tax = $p['transfer_tax'];
    $court_costs = $p['court_costs'];
    $land_register = $p['land_register'];
    $object_price = $p['total_cost'];


    if (check_row($listing_id, 'listing_id', 'construction')) {
        $update = $db->query('UPDATE `construction` SET `construction` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `listing_id` = ?', $construction, $listing_id);
    } else {
        $update = $db->query('INSERT INTO `construction`(`id`, `listing_id`, `construction`) VALUES (NULL, ?, ?)', $listing_id, $construction);
    }

    if (check_row($listing_id, 'listing_id', 'description')) {
        $update_status = $db->query('UPDATE `description` SET `contaminated` = ?, `commitments` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `listing_id` = ?', $p['contaminated'], $p['commitments'], $listing_id);
    } else {
        $update_status = $db->query('INSERT INTO `description`(`id`, `listing_id`, `contaminated`, `commitments`) VALUES (NULL, ?, ?, ?)', $listing_id, $p['contaminated'], $p['commitments']);
    }

    if (check_row($listing_id, 'listing_id', 'acquisition')) {
        $update_foreclosure = $db->query('UPDATE `acquisition` SET `object_price` = ?, `estate_agent` = ?, `notary_fees` = ?, `tax_percentage` = ?, `transfer_tax` = ?, `court_costs` = ?, `land_register` = ?, `total_cost` = ? WHERE `listing_id` = ?;', $p['object_price'], $p['estate_agent'], $p['notary_fees'], $p['tax_percentage'], $p['transfer_tax'], $p['court_costs'], $p['land_register'], $p['total_cost'], $listing_id);
    } else {
        $update_foreclosure = $db->query('INSERT INTO `acquisition`(`id`, `listing_id`, `object_price`, `estate_agent`, `notary_fees`, `tax_percentage`, `transfer_tax`, `court_costs`, `land_register`, `total_cost`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $listing_id, $p['object_price'], $p['estate_agent'], $p['notary_fees'], $p['tax_percentage'], $p['transfer_tax'], $p['court_costs'], $p['land_register'], $p['total_cost']);
    }

    /* Complete Listing */
    $complete_listing = false;
    if (isset($p['complete_listing'])) {
        $complete_listing = $db->query("UPDATE `listing` SET `completed` = '1' WHERE `id` = ?;", $listing_id);
    }

    if (get_data($listing_id, 'completed', 'listing') == 0) {
        $redirectLink = ADMIN . '/pending_listings.php';
    } else {
        $redirectLink = ADMIN . '/complete_listings.php';
    }

    if ($update) {
        if ($complete_listing) {
            searchOrderMatching($listing_id);
            redirect('Details Updated & Marked as Completed!', $redirectLink);
        } else {
            redirect('Details Updated Successfully!', $redirectLink);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Update Step 4 - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Update Step 4</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Listings</li>
                            <li class="breadcrumb-item active">Step 4</li>
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
                            <div class="card-header">Baumängel</div>
                            <div class="card-body">

                                <div class="backlog-list">

                                    <?php
                                    $showBlank = false;
                                    if (check_row($listing_id, 'listing_id', 'construction')) {
                                        if (!empty($cons)) {
                                            foreach ($cons as $k => $item) {
                                    ?>
                                                <div class="cost-pack" data-current="<?= $k ?>">
                                                    <div class="delete-cost-main">
                                                        <i class="fa fa-trash"></i>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-md-6 col-12">
                                                            <label>Baumängel</label>
                                                            <input type="text" class="form-control" name="backlog[<?= $k ?>][title]" placeholder="Baumängel" value="<?= $item['title']; ?>">
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                            <label>Summe gesamt</label>
                                                            <input type="text" class="form-control" name="backlog[<?= $k ?>][total]" placeholder="Summe gesamt" value="<?= $item['total']; ?>">
                                                        </div>
                                                    </div>

                                                    <div class="cost-table">
                                                        <div class="cost-list">
                                                            <?php foreach ($item["table"] as $j => $tb) { ?>
                                                                <div class="cost-item" data-identity="<?= $j + 1 ?>">
                                                                    <div class="delete-cost-row">
                                                                        <i class="fa fa-times"></i>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-md-6 col-12">
                                                                            <label>Beschreibung</label>
                                                                            <input type="text" class="form-control" name="backlog[<?= $k ?>][table][<?= $j ?>][desc]" placeholder="Beschreibung" value="<?= $tb['desc']; ?>">
                                                                        </div>
                                                                        <div class="col-md-6 col-12">
                                                                            <label>Geschätzter Kostenpunkt</label>
                                                                            <input type="text" class="form-control" name="backlog[<?= $k ?>][table][<?= $j ?>][estimated]" placeholder="Geschätzter Kostenpunkt" value="<?= $tb['estimated']; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="cost-btn">
                                                            <div class="btn btn-secondary add-cost-row">Neue Zeile hinzufügen</div>
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
                                    }
                                    ?>

                                    <?php if ($showBlank) { ?>
                                        <div class="cost-pack" data-current="0">
                                            <div class="delete-cost-main">
                                                <i class="fa fa-trash"></i>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-6 col-12">
                                                    <label>Baumängel</label>
                                                    <input type="text" class="form-control" name="backlog[0][title]" placeholder="Baumängel">
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <label>Summe gesamt</label>
                                                    <input type="text" class="form-control" name="backlog[0][total]" placeholder="Summe gesamt">
                                                </div>
                                            </div>

                                            <div class="cost-table">
                                                <div class="cost-list">
                                                    <div class="cost-item" data-identity="1">
                                                        <div class="delete-cost-row">
                                                            <i class="fa fa-times"></i>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-6 col-12">
                                                                <label>Beschreibung</label>
                                                                <input type="text" class="form-control" name="backlog[0][table][0][desc]" placeholder="Beschreibung">
                                                            </div>
                                                            <div class="col-md-6 col-12">
                                                                <label>Geschätzter Kostenpunkt</label>
                                                                <input type="text" class="form-control" name="backlog[0][table][0][estimated]" placeholder="Geschätzter Kostenpunkt">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="cost-btn">
                                                    <div class="btn btn-secondary add-cost-row" data-current="0" data-next="1">Neue Zeile hinzufügen</div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                </div>

                                <div class="form-group">
                                    <div class="btn btn-outline-dark backlog-btn">Add New Backlog</div>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Baulast'); ?>
                                <?php get_extra_field_html($dataSet, 'Baumängel_1'); ?>
                                <?php get_extra_field_html($dataSet, 'Baumängel_gemeinschaftseigentum'); ?>
                                <?php get_extra_field_html($dataSet, 'Baumängel_Summe_gesamt'); ?>
                                <?php get_extra_field_html($dataSet, 'Lasten_und_Beschränkungen'); ?>
                                <?php get_extra_field_html($dataSet, 'Sonstige_Zahlungsrückstände'); ?>

                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">Beschränkungen</div>
                            <div class="card-body">

                                <?php get_extra_field_html($dataSet, 'Altlasten'); ?>

                                <div class="form-group">
                                    <label for="contaminated">Altlastenverdacht <span class="custom_input" data-name="contaminated" data-holder="Altlastenverdacht">Custom</span></label>
                                    <select name="contaminated" class="form-select" data-select="<?= isset($p['contaminated']) ? $p['contaminated'] : $data_status['contaminated']; ?>">
                                        <option value="">- Auswählen -</option>
                                        <option value="1">Bei diesem Grundstück liegen Altlasten vor</option>
                                        <option value="2">Bei diesem Grundstück liegen wahrscheinlich keine Altlasten vor</option>
                                    </select>
                                </div>

                                <?php get_extra_field_html($dataSet, 'Mietbindung'); ?>

                                <div class="form-group">
                                    <label for="commitments">Mietbindungen <span class="custom_input" data-name="commitments" data-holder="Mietbindungen">Custom</span></label>
                                    <select name="commitments" class="form-select" data-select="<?= isset($p['commitments']) ? $p['commitments'] : $data_status['commitments']; ?>">
                                        <option value="">- Auswählen -</option>
                                        <option value="1">Für dieses Objekt bestehen Vermietungsverpflichtungen</option>
                                        <option value="2">Für dieses Objekt bestehen keine Vermietungsverpflichtungen</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">Kaufrechner</div>
                            <div id="acquisition" class="card-body">

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Objektpreis</label>
                                        <input type="text" class="form-control" name="object_price" placeholder="Objektpreis" value="<?= priceGerman($object_price); ?>">
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <label>Grunderwerbssteuer <?= isset($obj_taxy) ? '(' . rateGerman($obj_taxy) . '%)' : '' ?></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="tax_percentage" placeholder="Grunderwerbssteuer" value="<?= rateGerman($object_tax_rate); ?>">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <label>Land Transfer Tax</label>
                                        <input type="text" class="form-control disabled" name="transfer_tax" placeholder="Land Transfer Tax" value="<?= priceGerman($transfer_tax); ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group row">
                                            <div class="col-md-6 col-12">
                                                <label>Maklerprovision</label>
                                                <input type="text" class="form-control disabled" name="estate_agent" placeholder="Maklerprovision" value="0">
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <label>Notarkosten</label>
                                                <input type="text" class="form-control disabled" name="notary_fees" placeholder="Notarkosten" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Gerichtskosten</label>
                                        <input type="text" class="form-control" name="court_costs" placeholder="Gerichtskosten" value="<?= priceGerman($court_costs); ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Grundbuchkosten</label>
                                        <input type="text" class="form-control" name="land_register" placeholder="Grundbuchkosten" value="<?= priceGerman($land_register); ?>">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Gesamtkosten</label>
                                        <input type="text" class="form-control disabled" name="total_cost" placeholder="Gesamtkosten" value="<?= priceGerman($total_cost); ?>">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus-square"></i>
                                <span>Baumängel aktualisieren</span>
                            </button>
                        </div> -->

                        <div class="form-group mb-5">
                            <button name="update_details" type="submit" class="btn btn-primary">
                                <i class="fa fa-plus-square"></i>
                                <span>Aktualisiere Beschränkungen</span>
                            </button>
                            <button name="complete_listing" type="submit" class="btn btn-success">
                                <i class="fa fa-check-double"></i>
                                <span>Aktualisieren und als fertiggestellt markieren</span>
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