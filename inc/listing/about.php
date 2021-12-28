<?php
$about = $db->query('SELECT * FROM `about` WHERE `listing_id` = ?', $listing_id)->fetchArray();
$foreclosure = $db->query('SELECT * FROM `foreclosure` WHERE `listing_id` = ?', $listing_id)->fetchArray();
$description = $db->query('SELECT * FROM `description` WHERE `listing_id` = ?', $listing_id)->fetchArray();
$construction = $db->query('SELECT * FROM `construction` WHERE `listing_id` = ?', $listing_id)->fetchArray();

$facilities = '';
$facility = $db->query('SELECT * FROM `facility` WHERE `listing_id` = ?', $listing_id)->fetchArray();
if ($facility && !empty($facility['facility_table'])) {
    if ($facility['facility_table'] == '[{"share":"","description":"","area":"","estimated":""}]') {
        $facilities = '';
    } else {
        $facilities = json_decode($facility['facility_table'], true);
    }
}

$flr = '';
$floors = $db->query('SELECT * FROM `floors` WHERE `listing_id` = ?', $listing_id)->fetchArray();
if ($floors && !empty($floors["rooms"])) {
    if (
        $floors["rooms"] == '{"floor":[{"title":"","table":[{"room":""}]}]}' ||
        $floors["rooms"] == '{"title":"","table":[{"room":"","count":""}]}' ||
        $floors["rooms"] == '{"title":"","table":[{"section":"","status":"","rent":"","space":"","rooms":[{"room":"","count":""}]}]}'
    ) {
        $flr = '';
    } else {
        $flr = json_decode($floors["rooms"], true);
    }
}

if(!isset($extended)) $extended = false;
$filledCount = 0;
if (!empty($flr) && isset($flr["floor"])) {
    foreach ($flr["floor"] as $k => $item) {
        if (!empty($item["title"])) {
            $filledCount += 1;
        }
        if ($extended == true) {
            if (!empty($item["table"]["rooms"])) {
                $filledCount += 1;
            }
        } else {
            if (!empty($item["table"]["room"])) {
                $filledCount += 1;
            }
        }
    }
}

if($filledCount == 0) {
    $flr = '';
}

/* $listing_category = get_data($listing_id, 'new_cat', 'listing');
if (!empty($listing_category)) {
    $new_cats = json_decode($listing_category, true);
}
 */

$new_cats = getCatArray($listing_id);

$extended = extendStatus($flr);

$listing_ownership = get_col_data($listing_id, 'listing_id', 'listing_ownership', 'details');

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
<div class="about_tab">

    <div class="tab_content">
        <div class="row">
            <div class="col-xl-8 col-lg-7 col-md-12 col-sm-12 col-12">
                <div class="about_info">
                    <div class="about_info__title">
                        <h4>Objektbeschreibung</h4>
                    </div>

                    <div class="about_info__body">
                        <?php
                        if (!empty($list['object_desc'])) {
                            if (contentStatus(array('premium', 'plus'))) {
                                $object_desc = $list['object_desc'];
                            } else {
                                $string = $list['object_desc'];
                                $object_desc = substr($string, strpos($string, "<p"), strpos($string, "</p>") + 4);
                            }
                        } else {
                            $object_desc = 'N/A';
                        }
                        echo $object_desc;
                        ?>
                    </div>

                    <?php if (isset($foreclosure['special_comments']) && !empty($foreclosure['special_comments'])) { ?>
                        <div class="about_info__special">
                            <h4>Besonderheiten:</h4>
                            <p><?= $foreclosure['special_comments'] ?></p>
                        </div>
                    <?php } ?>

                </div>
            </div>

            <?php if (contentStatus(array('premium', 'plus'))) { ?>
                <div class="col-xl-4 col-lg-5 col-md-12 col-sm-12 col-12">
                    <div class="about_report <?= $list['report_available'] ? $list['report_available'] : 'none'; ?>">

                        <div class="about_report__question">
                            <div class="about_report__question-icon">
                                <i class="far fa-question-circle"></i>
                            </div>
                            <div class="about_report__question-tooltip">
                                <div class="about_report__question-tooltip--body">
                                    <h4>Was bedeuten die Farben dieser Box?</h4>
                                    <p>Es liegt uns nicht in allen Fällen das vollständige Gutachten vor, daher gibt es die verschiedenen Farben:</p>
                                    <p>Rot: Es liegt uns kein Gutachten vor. Solltest du mehr Informationen zum Objekt benötigen, klicke bitte unten auf den Button "Gutachtenanalyse anfordern" und wir beschaffen das Gutachten und werten es für dich aus. Dieses Feature ist unseren Premium-Kunden vorbehalten. </p>
                                    <p>Gelb: Es liegt uns das Kurzgutachten vor. Solltest du mehr Informationen zum Objekt benötigen, klicke bitte unten auf den Button "Gutachtenanalyse anfordern" und wir beschaffen das Gutachten und werten es für dich aus. Dieses Feature ist unseren Premium-Kunden vorbehalten. </p>
                                </div>
                            </div>
                        </div>
                        <div class="about_report__label <?= (isset($foreclosure['denkmalschutz']) && $foreclosure['denkmalschutz'] == '1') ? "crossed" : ''; ?>">
                            <div class="about_report__label-img">
                                <img src="<?= LINK ?>/assets/img/denkmalschutz.png">
                            </div>
                        </div>
                        <div class="about_report__info">
                            <div class="about_report__info-title">
                                <h4>Wichtige Informationen zum Gutachten</h4>
                            </div>
                            <div class="about_report__info-body">
                                <ul>
                                    <?php
                                    if (isset($foreclosure['inspection_type']) && !empty($foreclosure['inspection_type'])) {
                                        echo "<li><strong>Besichtigungsart: </strong> {$foreclosure['inspection_type']}</li>";
                                    }
                                    if (isset($foreclosure['inspection_date']) && !empty($foreclosure['inspection_date'])) {
                                        if (isset($foreclosure['inspection_status']) && $foreclosure['inspection_status'] == '0') {
                                            echo "<li><strong>Gutachten erstellt am: </strong> {$foreclosure['inspection_date']}</li>";
                                        } else {
                                            echo "<li><strong>Gutachten erstellt am: </strong> keine Angaben</li>";
                                        }
                                    }
                                    ?>
                                    <?php if (isset($foreclosure['inspection_participants']) && !empty($foreclosure['inspection_participants'])) { ?>
                                        <li>
                                            <strong>Anwesend bei der Besichtigung: </strong>
                                            <ul class="about_report__info-body--ul">
                                                <?php
                                                if (strpos($foreclosure['inspection_participants'], ',') !== false) {
                                                    $persons = explode(', ', $foreclosure['inspection_participants']);
                                                } else {
                                                    $persons = array($foreclosure['inspection_participants']);
                                                }
                                                foreach ($persons as $pers) {
                                                    echo '<li>' . $pers . '</li>';
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                </ul>

                                <?php
                                if ($list['report_available'] == 'short' || $list['report_available'] == 'none') {
                                ?>

                                    <div class="about_report__info-body--request">
                                        <p>Das Gutachten kann eingesehen werden. Wenn du hier klicks, dann senden wir dir eine Anleitung per Mail wo du das Gutachten einsehen kannst.</p>

                                        <?php if (contentStatus(array('plus'))) { ?>
                                            <form id="request_report" action="" method="POST">
                                                <input type="hidden" id="listing_id" value="<?= $listing_id ?>">
                                                <button type="submit" class="btn btn-blue btn-sm">Gutachtenanalyse anfordern</button>
                                            </form>
                                        <?php } else { ?>
                                            <button class="btn btn-blue btn-sm" disabled>
                                                <span>Gutachtenanalyse anfordern</span>
                                                <span class="premium_label">Premium+</span>
                                            </button>
                                        <?php } ?>

                                        <div id="report_status"></div>
                                        <div class="overlay"></div>
                                    </div>

                                <?php
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>

    <?php if (in_array('Eigentumswohnungen', $new_cats) == false) { ?>
        <?php if (isset($flr) && !empty($flr)) { ?>
            <div class="tab_content construe">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="nav construction_tabs" id="v-pills-tab">
                            <?php
                            if (isset($flr["floor"])) {
                                $flr = $flr["floor"];
                            }
                            foreach ($flr as $k => $item) {
                                $addClass = '';
                                if ($k == '0') {
                                    $addClass = 'active';
                                }
                                echo '<button class="nav-link ' . $addClass . '" id="v-pills-cons' . $k . '-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cons' . $k . '" type="button">' . (!empty($item["title"]) ? $item["title"] : 'N/A') . '</button>';
                            } ?>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                        <div class="tab-content">
                            <?php if (
                                $extended == true
                                //in_array('Mehrfamilienhäuser', $new_cats) ||
                                //in_array('Wohn-/ Geschäftshäuser', $new_cats)
                            ) { ?>
                                <?php
                                foreach ($flr as $k => $item) {
                                    $addClass = '';
                                    if ($k == '0') {
                                        $addClass = 'show active';
                                    } ?>
                                    <div class="tab-pane fade <?= $addClass ?>" id="v-pills-cons<?= $k ?>" role="tabpanel" aria-labelledby="v-pills-cons<?= $k ?>-tab">
                                        <div class="construction_table tab-content">
                                            <div class="row">
                                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">
                                                    <div class="nav construction_tabs" id="v-pills-tab-list">
                                                        <?php
                                                        //foreach ($flr["floor"] as $k => $item) {
                                                        foreach ($item["table"] as $j => $tb) {
                                                            $addClass = '';
                                                            if ($j == '0') {
                                                                $addClass = 'active';
                                                            }
                                                            echo '<button class="nav-link ' . $addClass . '" id="v-pills-cony' . $j . '-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cony' . $j . '" type="button">' . (!empty($tb["section"]) ? $tb["section"] : 'N/A') . '</button>';
                                                        }
                                                        //} 
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12">
                                                    <div class="tab-content">
                                                        <?php
                                                        foreach ($item["table"] as $j => $tb) {
                                                            $addClass = '';
                                                            if ($j == '0') {
                                                                $addClass = 'active';
                                                            }
                                                            $table = '';
                                                            if (isset($tb["rooms"])) {
                                                                $table = $tb["rooms"];
                                                            }

                                                        ?>
                                                            <div class="tab-pane fade show <?= $addClass ?>" id="v-pills-cony<?= $j ?>" role="tabpanel" aria-labelledby="v-pills-cons<?= $j ?>-tab">
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col"><?= !empty($tb["status"]) ? $tb["status"] . ', ' : '' ?><?= !empty($tb["rent"]) ? $tb["rent"] . ' &euro; per month, ' : '' ?><?= !empty($tb["space"]) ? $tb["space"] . ' m<sup>2</sup>' : '' ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            if (!empty($table)) {
                                                                                foreach ($table as $ta) {
                                                                                    echo "<tr>
                                                                                        <td>" . (!empty($ta['room']) ? $ta['room'] : 'N/A') . "</td>
                                                                                    </tr>";
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            <?php } else { ?>

                                <?php foreach ($flr as $k => $item) {
                                    $addClass = '';
                                    if ($k == '0') {
                                        $addClass = 'show active';
                                    }
                                    $table = '';
                                    if (!empty($item["table"])) {
                                        $table = $item["table"];
                                    }
                                ?>
                                    <div class="tab-pane fade <?= $addClass ?>" id="v-pills-cons<?= $k ?>" role="tabpanel" aria-labelledby="v-pills-cons<?= $k ?>-tab">
                                        <div class="construction_table tab-content">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col"><?= !empty($item["title"]) ? $item["title"] : 'N/A' ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (!empty($table)) {
                                                            foreach ($table as $tb) {
                                                                echo "<tr>
                                                                    <td>" . (!empty($tb['room']) ? $tb['room'] : 'N/A') . "</td>
                                                                </tr>";
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>

    <?php if (in_array('KFZ-Stellplatz/ Garagen', $new_cats)) { ?>
        <?php if ($about && !empty($about)) { ?>
            <div class="tab_content">
                <div class="about_more_title">
                    <h4>Informationen zur Immobilie</h4>
                </div>
                <div class="about_more_body">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="about_more_body-column">

                                <div class="about_more_body-column--item">
                                    <strong>Nutzfläche</strong>
                                    <span><?= !empty($about['use_space']) ? $about['use_space'] . 'm<sup>2</sup>' : 'N/A' ?></span>
                                </div>
                                <div class="about_more_body-column--item">
                                    <strong>Grundstücksfläche</strong>
                                    <span><?= !empty($about['plot_area']) ? $about['plot_area'] . 'm<sup>2</sup>' : 'N/A' ?></span>
                                </div>
                                <div class="about_more_body-column--item">
                                    <strong>Vermietungsstatus</strong>
                                    <span><?= !empty($about['current_usage']) ? $about['current_usage'] : 'N/A' ?></span>
                                </div>
                                <div class="about_more_body-column--item">
                                    <strong>Co-ownership</strong>
                                    <span><?= !empty($listing_ownership) ? $listing_ownership : 'N/A' ?></span>
                                </div>

                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="about_more_body-column">

                                <div class="about_more_body-column--item extra">
                                    <strong>Mieteinnahmen</strong>
                                    <span><?= !empty($about['earn_month']) ? $about['earn_month'] : 'N/A' ?></span>
                                </div>
                                <div class="about_more_body-column--item extra">
                                    <strong>Bauzustand Wohnung</strong>
                                    <span><?= !empty($about['condition_flat']) ? $about['condition_flat'] : 'N/A' ?></span>
                                </div>
                                <div class="about_more_body-column--item extra">
                                    <strong>Hausgeld</strong>
                                    <span><?= !empty($about['additional_costs']) ? price($about['additional_costs']) . ' &euro;' : 'N/A' ?></span>
                                </div>
                                <div class="about_more_body-column--item extra">
                                    <strong>Instandhaltungsrücklage</strong>
                                    <span><?= !empty($about['maintenance_house']) ? price($about['maintenance_house']) . ' &euro;' : 'N/A' ?></span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>

    <?php if (in_array('Unbebaute Grundstücke', $new_cats) || in_array('Land- und forstwirtschaftlich genutzte Flächen', $new_cats)) { ?>

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
                                            <h4>Verunreinigungen</h4>
                                        </div>
                                        <div class="object_desc">
                                            <?php if ($conta == 1) {
                                                echo '<p>Bei diesem Grundstück liegen Altlasten vor</p>';
                                            } else {
                                                echo '<p>Bei diesem Grundstück liegen wahrscheinlich keine Altlasten vor</p>';
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

        <?php if ($cons && !empty($cons)) { ?>
            <div class="tab_content">
                <div class="about_more_title">
                    <h4>Building Error/ Costs</h4>
                </div>
                <div class="about_more_body">

                    <?php
                    if (isset($cons["backlog"])) {
                        $cons = $cons["backlog"];
                    }
                    ?>

                    <?php if (count($cons) == 1) { ?>
                        <div class="construct-table">
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
                                        foreach ($cons[0]["table"] as $tb) {
                                            echo "<tr>
                                                    <td>" . (!empty($tb['desc']) ? $tb['desc'] : 'N/A') . "</td>
                                                    <td class=\"text-right\">" . (!empty($tb['estimated']) ? $tb['estimated'] : 'N/A') . "</td>
                                                </tr>";
                                        } ?>

                                    </tbody>
                                </table>
                            </div>

                            <?php if ($cons[0]["total"] !== '') { ?>
                                <div class="construction_total">
                                    <h4>
                                        <strong>In Summe:</strong>
                                        <span><?= price($cons[0]["total"]) ?> &euro;</span>
                                    </h4>
                                </div>
                            <?php } ?>

                        </div>
                    <?php } else { ?>

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

                                                    <?php if ($construction['wrap_total'] !== '') { ?>
                                                        <div class="construction_total">
                                                            <h4>
                                                                <strong>In Summe:</strong>
                                                                <span><?= price($construction['wrap_total']) ?> &euro;</span>
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

                    <?php } ?>

                </div>
            </div>
        <?php } ?>

    <?php } ?>

    <?php if ($facilities && !empty($facilities)) { ?>
        <div class="tab_content">
            <div class="about_more_title">
                <h4>Annex / Facilities / Machines</h4>
            </div>
            <div class="about_more_body">
                <div class="facility">

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 200px">Miteigentumsanteil</th>
                                    <th scope="col">Beschreibung</th>
                                    <th scope="col">Fläche</th>
                                    <th scope="col" style="width: 210px;" class="text-right">Geschätzter Wert</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $facility_total = 0;
                                foreach ($facilities as $tb) {
                                    if (!empty($tb['estimated'])) {
                                        $facility_total += (int)$tb['estimated'];
                                    }
                                    echo "<tr>
                                            <td>" . (!empty($tb['share']) ? $tb['share'] : 'N/A') . "</td>
                                            <td>" . (!empty($tb['description']) ? $tb['description'] : 'N/A') . "</td>
                                            <td>" . (!empty($tb['area']) ? $tb['area'] : 'N/A') . "</td>
                                            <td class=\"text-right\">" . (!empty($tb['estimated']) ? $tb['estimated'] : 'N/A') . "</td>
                                        </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($facility_total !== 0) { ?>
                        <div class="construction_total">
                            <h4>
                                <strong>Gesamtwert:</strong>
                                <span><?= priceClean($facility_total) ?> &euro;</span>
                            </h4>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    <?php } ?>

    <div class="tab_content">
        <div class="about_more_title">
            <h4>Wichtige Informationen zum Versteigerungstermin</h4>
        </div>
        <div class="about_more_body">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="about_more_body-column">
                        <div class="about_more_body-column--item">
                            <strong>Amtsgericht</strong>
                            <span><?= !empty($list["foreclosure_court"]) ? $list["foreclosure_court"] : 'N/A' ?></span>
                        </div>
                        <div class="about_more_body-column--item">
                            <strong>Aktenzeichen</strong>
                            <span><?= !empty($list["listing_label"]) ? $list["listing_label"] : 'N/A' ?></span>
                        </div>
                        <div class="about_more_body-column--item">
                            <strong>Versteigerungsdatum</strong>
                            <span><?= !empty($list["foreclosure_date"]) ? $list["foreclosure_date"] : 'N/A' ?></span>
                        </div>
                        <div class="about_more_body-column--item">
                            <strong>Versteigerungsort</strong>
                            <span><?= !empty($list["auction_place"]) ? $list["auction_place"] : 'N/A' ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="about_more_body-column">
                        <div class="about_more_body-column--item">
                            <strong>Art der Zwangsversteigerung</strong>
                            <span><?= !empty($list["foreclosure_cat"]) ? $list["foreclosure_cat"] : 'N/A' ?></span>
                        </div>
                        <div class="about_more_body-column--item">
                            <strong>Grundbuchamt</strong>
                            <span><?= !empty($list["foreclosure_court"]) ? $list["foreclosure_court"] : 'N/A' ?></span>
                        </div>
                        <div class="about_more_body-column--item">
                            <strong>Gläubiger</strong>
                            <!-- <span><a href="#">Premium</a></span> -->
                            <span><?= !empty($list["misc"]) ? $list["misc"] : 'N/A' ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>