<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

$orders = $db->query("SELECT * FROM `search_order` WHERE user = ?;", $user)->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Suchaufträge - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <section id="account">
        <div class="account">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <?php include HOME . '/inc/account/sidebar.php'; ?>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                        <div class="account_body">
                            <section id="search_request" class="no-gap">

                                <div class="account_body__title">
                                    <h4>Deine Suchaufträge</h4>
                                    <p>Hier kannst du jederzeit deine Suchaufträge verwalten.</p>
                                </div>

                                <div class="account_body__content">

                                    <?php if ($orders && !empty($orders)) { ?>
                                        <div class="request">

                                            <?php foreach ($orders as $item) { ?>
                                                <div class="request_details">
                                                    <div class="request_details__title">
                                                        <div class="request_details__title-heading">
                                                            <h4><?= !empty($item["address"]) ? $item["address"] : 'Not Specified' ?></h4>
                                                            <p>Radius: <?= !empty($item["address"]) && !empty($item["radius"]) ? $item["radius"] . ' Kilometer' : 'Not Specified' ?></p>
                                                        </div>

                                                        <?php
                                                        $order_result_count = 0;
                                                        $order_results = $db->query("SELECT * FROM `search_order_results` WHERE order_id = ?;", $item["id"]);
                                                        $order_result_count = $order_results->numRows();
                                                        $order_result_all = $order_results->fetchAll();
                                                        if ($order_result_all && !empty($order_result_all)) { ?>
                                                            <div class="request_details__title-button">
                                                                <button class="btn btn-dark btn-sm" data-order="<?= $item["id"] ?>">
                                                                    <span>Ergebnisse ansehen</span>
                                                                    <span>(<?= $order_result_count ?>)</span>
                                                                </button>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="request_details__title-button">
                                                                <button class="btn btn-dark btn-sm" disabled>
                                                                    <span>Ergebnisse ansehen</span>
                                                                    <span>(0)</span>
                                                                </button>
                                                            </div>
                                                        <?php } ?>

                                                    </div>

                                                    <div class="request_details__body">

                                                        <div class="request_info">
                                                            <div class="request_info__item">
                                                                <span>Search Order ID</span>
                                                                <strong><?= !empty($item["id"]) ? $item["id"] : 'Any' ?></strong>
                                                            </div>
                                                            <div class="request_info__item">
                                                                <span>Category</span>
                                                                <strong><?= !empty($item["category"]) ? $item["category"] : 'Any' ?></strong>
                                                            </div>
                                                            <div class="request_info__item">
                                                                <span>Verkehrswert</span>
                                                                <strong><?= !empty($item["price_from"]) ? priceClean($item["price_from"]) . '&euro;' : 'Not Specified' ?> - <?= !empty($item["price_to"]) ? priceNoCents($item["price_to"]) . '&euro;' : 'Not Specified' ?>&euro;</strong>
                                                            </div>
                                                            <div class="request_info__item">
                                                                <span>Zimmer</span>
                                                                <strong><?= !empty($item["room_count_from"]) ? $item["room_count_from"] : 'Any' ?> - <?= !empty($item["room_count_to"]) ? $item["room_count_to"] : 'Any' ?></strong>
                                                            </div>
                                                            <div class="request_info__item">
                                                                <span>Wohnfläche</span>
                                                                <strong><?= !empty($item["living_space_from"]) ? $item["living_space_from"] : 'Any' ?> - <?= !empty($item["living_space_to"]) ? $item["living_space_to"] : 'Any' ?></strong>
                                                            </div>
                                                            <div class="request_info__item">
                                                                <span>Value Count</span>
                                                                <strong><?= !empty($item["value_count"]) ? $item["value_count"] : 'Any' ?></strong>
                                                            </div>

                                                            <?php if (contentStatus(array('premium', 'plus'))) { ?>
                                                                <div class="request_info__item">
                                                                    <span>Ist-Miete (&euro;)</span>
                                                                    <strong><?= !empty($item["miete_from"]) ? $item["miete_from"] : 'Any' ?> - <?= !empty($item["miete_to"]) ? $item["miete_to"] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Potenzielle Miete (&euro;)</span>
                                                                    <strong><?= !empty($item["potential_from"]) ? $item["potential_from"] : 'Any' ?> - <?= !empty($item["potential_to"]) ? $item["potential_to"] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Kaufpreis (&euro;)</span>
                                                                    <strong><?= !empty($item["kauf_from"]) ? $item["kauf_from"] : 'Any' ?> - <?= !empty($item["kauf_to"]) ? $item["kauf_to"] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Durchschnittlicher Kaufpreis (&euro;)</span>
                                                                    <strong><?= !empty($item["preis_from"]) ? $item["preis_from"] : 'Any' ?> - <?= !empty($item["preis_to"]) ? $item["preis_to"] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Potentielle Rendite (%)</span>
                                                                    <strong><?= !empty($item["rendite_from"]) ? $item["rendite_from"] : 'Any' ?> - <?= !empty($item["rendite_to"]) ? $item["rendite_to"] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Mietmultiplikator</span>
                                                                    <strong><?= !empty($item["multiplier_gross_from"]) ? $item["multiplier_gross_from"] : 'Any' ?> - <?= !empty($item["multiplier_gross_to"]) ? $item["multiplier_gross_to"] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Geschätzte monatliche Rate (&euro;)</span>
                                                                    <strong><?= !empty($item["month_payment_from"]) ? $item["month_payment_from"] : 'Any' ?> - <?= !empty($item["month_payment_to"]) ? $item["month_payment_to"] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Reports</span>
                                                                    <strong>
                                                                        <?php
                                                                        $report_list = array();
                                                                        if (!empty($item["reports"])) {
                                                                            $reports = explode(',', $item["reports"]);
                                                                            foreach ($reports as $rp) {
                                                                                dump($rp);
                                                                                switch($rp) {
                                                                                    case 'none' : $report_list[] = 'Kein Gutachten'; break;
                                                                                    case 'short' : $report_list[] = 'Exposé'; break;
                                                                                    case 'long' : $report_list[] = 'Gutachten'; break;
                                                                                }
                                                                            }
                                                                        }
                                                                        if (!empty($report_list)) {
                                                                            echo implode(", ", $report_list);
                                                                        }
                                                                        ?>
                                                                    </strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Baujahr</span>
                                                                    <strong><?= !empty($item["construction_year_from"]) ? $item["construction_year_from"] : 'Any' ?> - <?= !empty($item["construction_year_to"]) ? $item["construction_year_to"] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Besondere Ausstattung</span>
                                                                    <strong>
                                                                        <?php
                                                                        $equip_list = [];
                                                                        if (!empty($item["listing_equipment"])) {
                                                                            $equips = explode(',', $item["listing_equipment"]);
                                                                            foreach ($equips as $eq) {
                                                                                $equip_list[] = get_data($eq, 'label', 'equipments');
                                                                            }
                                                                        }
                                                                        if (!empty($equip_list)) {
                                                                            echo implode(", ", $equip_list);
                                                                        }
                                                                        ?>
                                                                    </strong>
                                                                </div>
                                                            <?php } ?>

                                                            <?php if (contentStatus(array('plus'))) { ?>
                                                                <div class="request_info__item">
                                                                    <span>3D Model</span>
                                                                    <strong><?= !empty($item["model"]) ? $item["model"] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Denkmalschutz</span>
                                                                    <strong><?= !empty($item["denkmalschutz"]) ? $item["denkmalschutz"] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Altlastenverdacht</span>
                                                                    <strong><?= !empty($item[""]) ? $item[""] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Vermietungsverpflichtungen</span>
                                                                    <strong><?= !empty($item["commitments"]) ? $item["commitments"] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Vermietungstatus</span>
                                                                    <strong><?= !empty($item["current_usage"]) ? $item["current_usage"] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Besichtigungsart</span>
                                                                    <strong><?= !empty($item["inspection_type"]) ? $item["inspection_type"] : 'Any' ?></strong>
                                                                </div>
                                                                <div class="request_info__item">
                                                                    <span>Wertermittlungsstichtag früher als</span>
                                                                    <strong><?= !empty($item["report_time"]) ? $item["report_time"] : 'Any' ?></strong>
                                                                </div>
                                                            <?php } ?>

                                                        </div>

                                                        <!-- <div class="request_points">
                                                            <div class="request_points__item">
                                                                <i class="fa fa-check"></i>
                                                                <span>Keine Baumängel</span>
                                                            </div>
                                                            <div class="request_points__item">
                                                                <i class="fa fa-check"></i>
                                                                <span>Fußbodenheizung</span>
                                                            </div>
                                                            <div class="request_points__item">
                                                                <i class="fa fa-check"></i>
                                                                <span>Aufzug</span>
                                                            </div>
                                                        </div> -->

                                                        <div class="request_actions">
                                                            <div class="request_actions__left">
                                                                <?php if ($item["pause"] == 1) { ?>
                                                                    <button class="btn btn-success" data-order="<?= $item["id"] ?>" data-action="resume">Resume</button>
                                                                <?php } else { ?>
                                                                    <button class="btn btn-blue" data-order="<?= $item["id"] ?>" data-action="pause">Pausieren</button>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="request_actions__right">
                                                                <button class="btn btn-nope" data-order="<?= $item["id"] ?>" data-action="delete">Suchauftrag löschen</button>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="overlay"></div>

                                                </div>
                                            <?php } ?>

                                        </div>
                                    <?php } else { ?>
                                        <div class="alert alert-info">
                                            <i class="fa fa-exclamation-circle"></i>
                                            <span>Kein Suchauftrag festgelegt!</span>
                                        </div>
                                    <?php } ?>

                                </div>

                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include HOME . '/inc/account/so_results.php'; ?>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>
</body>

</html>