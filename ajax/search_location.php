<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $p = $_POST;
    $results = array();

    $page_limit = 10;
    $start = 0;

    if (isset($p['page']) && !empty($p['page'])) {
        $pageNo = $p['page'];
    } else {
        $pageNo = 0;
    }

    $start = $pageNo * $page_limit;

    /* $sql = "SELECT * FROM (((((listing
    INNER JOIN `about` ON about.listing_id = listing.id)
    INNER JOIN `details` ON details.listing_id = listing.id)
    INNER JOIN `foreclosure` ON foreclosure.listing_id = listing.id)
    INNER JOIN `energy` ON energy.listing_id = listing.id)
    INNER JOIN `description` ON description.listing_id = listing.id) WHERE listing.completed = '1';"; */

    //$starttime = microtime(true);

    /* $sql = "SELECT 
    lat, lng, new_cat, main_cat, value_limit, living_space, listing_rooms, object_val, model_url, denkmalschutz, report_available, earn_month, object_address, 
    current_usage, inspection_type, contaminated, commitments, listing_equipment, construction_year, inspection_date, 
    listing_label, foreclosure_date, listing_slug, featured, object_desc, about_type, listing_flats, use_space, plot_area, listing_ownership, demolished, 
    listing.id as listing_id, about.id as about_id, details.id as details_id, foreclosure.id as foreclosure_id, energy.id as energy_id, description.id as description_id
    FROM (((((listing
    INNER JOIN `about` ON about.listing_id = listing.id)
    INNER JOIN `details` ON details.listing_id = listing.id)
    INNER JOIN `foreclosure` ON foreclosure.listing_id = listing.id)
    INNER JOIN `energy` ON energy.listing_id = listing.id)
    INNER JOIN `description` ON description.listing_id = listing.id) WHERE listing.completed = '1';"; */

    //LIMIT $start, $page_limit;

    $sql = "SELECT 
    lat, lng, new_cat, main_cat, value_limit, living_space, listing_rooms, object_val, model_url, denkmalschutz, report_available, earn_month, object_address, 
    current_usage, inspection_type, contaminated, commitments, listing_equipment, construction_year, inspection_date, 
    listing_label, foreclosure_date, listing_slug, featured, object_desc, about_type, listing_flats, use_space, plot_area, listing_ownership, demolished, 
    listing.id as listing_id, about.id as about_id, details.id as details_id, foreclosure.id as foreclosure_id, energy.id as energy_id, description.id as description_id
    FROM (((((listing
    LEFT JOIN `about` ON about.listing_id = listing.id)
    LEFT JOIN `details` ON details.listing_id = listing.id)
    LEFT JOIN `foreclosure` ON foreclosure.listing_id = listing.id)
    LEFT JOIN `energy` ON energy.listing_id = listing.id)
    LEFT JOIN `description` ON description.listing_id = listing.id) WHERE listing.completed = '1';";

    $listings = $db->query($sql)->fetchAll();

    //$endtime = microtime(true);
    //$duration = $endtime - $starttime;
    //echo $duration . ' ------ ';

    //echo count($listings) . ' \ ';

    /* foreach($p as $index => $k) {
        if(!empty($k)) {
            echo $index . ' ' . $k . '<br>';
        }
    } */

    foreach ($listings as $one_result) {

        $include = true;

        // location filter
        if (!empty($p['address']) && !empty($p['lat']) && !empty($p['lng']) && !empty($p['radius'])) {
            $match_distance = (((acos(sin(($p['lat'] * pi() / 180)) * sin(($one_result['lat'] * pi() / 180)) + cos(($p['lat'] * pi() / 180)) * cos(($one_result['lat'] * pi() / 180)) * cos((($p['lng'] - $one_result['lng']) * pi() / 180)))) * 180 / pi()) * 60 * 1.1515 * 1.609344);
            if ($p['radius'] < $match_distance) {
                $include = false;
            }
        }

        /* if (
            !empty($p['address']) && 
            !empty($p['lat']) && !empty($p['lng']) && 
            !empty($p['radius']) && 
            !empty($one_result['lat']) && !empty($one_result['lng'])
        ) {
            $listing_distance = distance($p['lat'], $p['lng'], $one_result['lat'], $one_result['lng'], "K");
            if($listing_distance > $p['radius']) {
                $include = false;
            }
        } */

        // category filter
        /* echo $one_result['listing_id'] . ' ';
        echo $one_result['about_id'] . ' ';
        echo $one_result['details_id'] . ' ';
        echo $one_result['foreclosure_id'] . ' ';
        echo $one_result['energy_id'] . ' ';
        echo $one_result['description_id'] . ' ';
        echo ' ---- '; */

        $db_category_all = categoryArray($one_result['new_cat'], $one_result['main_cat']);
        //dump($db_category_all);
        if (!empty($p['category'])) {
            if (!in_array($p['category'], $db_category_all)) {
                $include = false;
            }
        }

        // value count
        $db_value_count = $one_result['value_limit'];
        if (!empty($p['value_count'])) {
            if ($p['value_count'] !== $db_value_count) {
                $include = false;
            }
        }

        // living space
        $db_living_space = $one_result['living_space'];
        $status_living_space = includeStatus($p['living_space_from'], $p['living_space_to'], $db_living_space);
        if ($status_living_space == 0) {
            $include = false;
        }

        // room count
        $db_room_count = $one_result['listing_rooms'];
        $status_room_count = includeStatus($p['room_count_from'], $p['room_count_to'], $db_room_count);
        if ($status_room_count == 0) {
            $include = false;
        }

        // price
        $db_price = object_price($one_result['object_val']);
        $status_price = includeStatus($p['price_from'], $p['price_to'], $db_price);
        if ($status_price == 0) {
            $include = false;
        }

        // 3d model
        $db_model = $one_result['model_url'];
        if (!empty($p['model3d'])) {
            if ($p['model3d'] == 'yes') {
                if (empty($db_model)) {
                    $include = false;
                }
            }
            /* if ($p['model3d'] == 'no') {
                if (!empty($db_model)) {
                    $include = false;
                }
            } */
        }

        // denkmalschutz
        $db_denkmalschutz = $one_result['denkmalschutz'];
        if (!empty($p['denkmalschutz'])) {
            if ($p['denkmalschutz'] == 'yes') {
                if ($db_denkmalschutz == '0') {
                    $include = false;
                }
            }
            /* if ($p['denkmalschutz'] == 'no') {
                if ($db_denkmalschutz == '1') {
                    $include = false;
                }
            } */
        }

        // reports
        $db_reports = $one_result['report_available'];
        if (!empty($p['reports'])) {
            $matchCount = 0;
            foreach ($p['reports'] as $report_type) {
                if ($p['reports'] == $db_reports) {
                    $matchCount += 1;
                }
            }
            if ($matchCount == 0) {
                $include = false;
            }
            /* if ($p['reports'] !== $db_reports) {
                $include = false;
            } */
            /* if ($p['reports'] == 'yes') {
                if ($db_reports == 'none') {
                    $include = false;
                }
            }
            if ($p['reports'] == 'no') {
                if ($db_reports == 'long' || $db_reports == 'short') {
                    $include = false;
                }
            } */
        }

        ////////////////////////////////////////////
        ////////////////////////////////////////////
        ////////////////////////////////////////////

        $earn_month = object_price($one_result['earn_month']);
        $listing_space = $db_living_space;

        // listing type
        $rent_table = 'rent_house';
        $buy_table = 'buy_house';
        if (in_array('Zweifamilienhaus', $db_category_all)) {
            $rent_table = 'rent_house';
            $buy_table = 'buy_house';
        } elseif (in_array('Eigentumswohnungen', $db_category_all)) {
            $rent_table = 'rent_flat';
            $buy_table = 'buy_flat';
        }

        // listing zip
        $listing_zip = getZip($one_result['object_address']);
        $potential_rent_db = get_col_data($listing_zip, 'zip', 'avarage_rent', $rent_table);

        $earn_month_fix = '';
        if (empty($earn_month)) {
            if (!empty($listing_space)) {
                $earn_month_fix = (float)$potential_rent_db * (float)$listing_space;
            }
        } else {
            $earn_month_fix = $earn_month;
        }

        $listing_value = object_price($db_price);

        ////////////////////////////////////////////
        ////////////////////////////////////////////
        ////////////////////////////////////////////

        // Ist-Miete
        $actual_rent = '';
        if (!empty($earn_month) && !empty($listing_space)) {
            $actual_rent = (float)$earn_month / (float)$listing_space;
        }

        $miete_from = '';
        $miete_to = '';
        if (isset($p['miete_from']) && !empty($p['miete_from'])) $miete_from = $p['miete_from'];
        if (isset($p['miete_to']) && !empty($p['miete_to'])) $miete_to = $p['miete_to'];

        $status_actual_rent = includeStatus($miete_from, $miete_to, $actual_rent);
        if ($status_actual_rent == 0) {
            $include = false;
        }

        // Potenzielle Miete
        $potential_rent = get_col_data($listing_zip, 'zip', 'avarage_rent', $rent_table);

        $potential_from = '';
        $potential_to = '';
        if (isset($p['potential_from']) && !empty($p['potential_from'])) $potential_from = $p['potential_from'];
        if (isset($p['potential_to']) && !empty($p['potential_to'])) $potential_to = $p['potential_to'];

        $status_potential_rent = includeStatus($potential_from, $potential_to, $potential_rent);
        if ($status_potential_rent == 0) {
            $include = false;
        }

        // Kaufpreis
        $purchase_price = '';
        if (!empty($listing_value) && !empty($listing_space)) {
            $purchase_price = (float)$listing_value / (float)$listing_space;
        }

        $kauf_from = '';
        $kauf_to = '';
        if (isset($p['kauf_from']) && !empty($p['kauf_from'])) $kauf_from = $p['kauf_from'];
        if (isset($p['kauf_to']) && !empty($p['kauf_to'])) $kauf_to = $p['kauf_to'];

        $status_purchase_price = includeStatus($kauf_from, $kauf_to, $purchase_price);
        if ($status_purchase_price == 0) {
            $include = false;
        }

        // Durchschnittlicher Kaufpreis
        $avarage_buying = get_col_data($listing_zip, 'zip', 'avarage_rent', $buy_table);

        $preis_from = '';
        $preis_to = '';
        if (isset($p['preis_from']) && !empty($p['preis_from'])) $preis_from = $p['preis_from'];
        if (isset($p['preis_to']) && !empty($p['preis_to'])) $preis_to = $p['preis_to'];

        $status_avarage_buying = includeStatus($preis_from, $preis_to, $avarage_buying);
        if ($status_avarage_buying == 0) {
            $include = false;
        }

        // Potentielle Rendite
        $potential_return = '';
        if (!empty($earn_month_fix) && !empty($listing_value)) {
            if ($listing_value !== '0' && $listing_value > 0 && is_numeric($listing_value)) {
                $potential_return = ((float)$earn_month_fix * 12 * 100) / (float)$listing_value;
            }
        }

        $rendite_from = '';
        $rendite_to = '';
        if (isset($p['rendite_from']) && !empty($p['rendite_from'])) $rendite_from = $p['rendite_from'];
        if (isset($p['rendite_to']) && !empty($p['rendite_to'])) $rendite_to = $p['rendite_to'];

        $status_room_count = includeStatus($rendite_from, $rendite_to, $potential_return);
        if ($status_room_count == 0) {
            $include = false;
        }

        // Mietmultiplikator
        $multiplier_gross = '';
        if (!empty($earn_month_fix) && !empty($listing_value)) {
            //$listing_value = object_price($db_price);
            $multiplier_gross = (float)$listing_value / ((float)$earn_month_fix * 12);
        }

        $multiplier_gross_from = '';
        $multiplier_gross_to = '';
        if (isset($p['multiplier_gross_from']) && !empty($p['multiplier_gross_from'])) $multiplier_gross_from = $p['multiplier_gross_from'];
        if (isset($p['multiplier_gross_to']) && !empty($p['multiplier_gross_to'])) $multiplier_gross_to = $p['multiplier_gross_to'];

        $status_multiplier_gross = includeStatus($multiplier_gross_from, $multiplier_gross_to, $multiplier_gross);
        if ($status_multiplier_gross == 0) {
            $include = false;
        }

        ////////////////////////////////////////////

        // Geschätzte monatliche Rate
        $listing_monthly_cost = '';
        if (!empty($listing_value)) {
            //$listing_value = object_price($db_price);

            $listing_monthly_cost = ((($listing_value * 1.5) / 100) + (($listing_value * 2) / 100)) / 12;
            $listing_monthly_cost = number_format((float)$listing_monthly_cost, 2, '.', '');
        }

        $month_payment_from = '';
        $month_payment_to = '';
        if (isset($p['month_payment_from']) && !empty($p['month_payment_from'])) $month_payment_from = $p['month_payment_from'];
        if (isset($p['month_payment_to']) && !empty($p['month_payment_to'])) $month_payment_to = $p['month_payment_to'];

        $status_month_payment = includeStatus($month_payment_from, $month_payment_to, $listing_monthly_cost);
        if ($status_month_payment == 0) {
            $include = false;
        }

        ////////////////////////////////////////////

        // Vermietungsstatus
        $db_current_usage = $one_result['current_usage'];
        if (!empty($p['current_usage'])) {
            if ($p['current_usage'] !== $db_current_usage) {
                $include = false;
            }
        }

        // Besichtigungsart
        $db_inspection_type = $one_result['inspection_type'];
        if (!empty($p['inspection_type'])) {
            if ($p['inspection_type'] !== $db_inspection_type) {
                $include = false;
            }
        }

        // Altlastenverdacht
        $status_contaminated = 0;
        $db_contaminated = $one_result['contaminated'];
        if (isset($p['altlastenverdacht']) && !empty($p['altlastenverdacht'])) {
            if ($p['altlastenverdacht'] == 'yes') {
                //$status_contaminated = 0;
            } else {
                $status_contaminated = 1;
            }
        } else {
            $status_contaminated = 1;
        }

        if ($status_contaminated == 0) {
            if ($db_contaminated !== '2') {
                $include = false;
            }
        }

        // mietbindungen
        $status_commitments = 0;
        $db_commitments = $one_result['commitments'];
        if (isset($p['mietbindungen']) && !empty($p['mietbindungen'])) {
            if ($p['mietbindungen'] == 'yes') {
                //$status_commitments = 0;
            } else {
                $status_commitments = 1;
            }
        } else {
            $status_commitments = 1;
        }

        if ($status_commitments == 0) {
            if ($db_commitments !== '2') {
                $include = false;
            }
        }

        /* $db_contaminated = $one_result['contaminated'];
        if (!empty($p['contaminated'])) {
            if ($p['contaminated'] !== $db_contaminated) {
                $include = false;
            }
        } */

        // Besondere Ausstattung
        $db_equipment = $one_result['listing_equipment'];
        if (isset($p['listing_equipment']) && !empty($p['listing_equipment'])) {
            $listing_equipment = $p['listing_equipment'];

            $contain_equips = array_intersect($db_equipment, $listing_equipment);
            if (empty($contain_equips)) {
                $include = false;
            }
        }

        // Baujahr
        $db_construction_year = $one_result['construction_year'];
        $main_year = $db_construction_year;

        if (!empty($p['construction_year_from']) || !empty($p['construction_year_to'])) {
            if (empty($main_year)) {
                $include = false;
            } elseif (!is_numeric($main_year)) {
                $include = false;
            } else {
                if (!empty($p['construction_year_from']) && !empty($p['construction_year_to'])) {
                    if (($p['construction_year_from'] <= $main_year) && ($main_year <= $p['construction_year_to'])) {
                        //
                    } else {
                        $include = false;
                    }
                } else if (!empty($p['construction_year_from'])) {
                    if (($p['construction_year_from'] > $main_year)) {
                        $include = false;
                    }
                } elseif (!empty($p['construction_year_to'])) {
                    if (($main_year > $p['construction_year_to'])) {
                        $include = false;
                    }
                }
            }
        }

        /* if (!empty($p['construction_year'])) {
            if (strpos($db_construction_year, $p['construction_year']) == false) {
                $include = false;
            }
        } */

        // Report created earlier than
        $inspection_date = '';
        $db_report_date = $one_result['inspection_date'];
        if (!empty($db_report_date)) {
            $inspection_date = date_create($db_report_date);
            $inspection_date = date_format($inspection_date, "Y-m-d");
        }

        /* $prev_date = strtotime($p['report_time_from']);
        $next_date = strtotime($p['report_time_to']);
        $main_date = strtotime($inspection_date);

        if (!empty($prev_date) || !empty($next_date)) {
            if (empty($main_date)) {
                $include = false;
            } else {
                if (!empty($prev_date) && !empty($next_date)) {
                    if (($prev_date <= $main_date) && ($main_date <= $next_date)) {
                        //
                    } else {
                        $include = false;
                    }
                } else if (!empty($prev_date)) {
                    if (($prev_date > $main_date)) {
                        $include = false;
                    }
                } elseif (!empty($next_date)) {
                    if (($main_date > $next_date)) {
                        $include = false;
                    }
                }
            }
        } */

        if (!empty($inspection_date) && !empty($p['report_time'])) {
            if (strtotime($inspection_date) > strtotime($p['report_time'])) {
                $include = false;
            }
        }


        //dump($include);

        // if everything is true, save it to main loop
        if ($include == true) {
            $new_result = array($one_result);
            $results = array_merge($results, $new_result);
        }
    }

    $response = array(
        'status' => 'empty',
        'elements' => '',
        'cords' => '',
    );

    $elements = '';
    $coordinates = array();

    $output = array_slice($results, $start, $page_limit);

    if ($output && !empty($output)) {

        //dump($results);

        $elements .= '<div class="search_info__result-title"><h4><span>' . count($results) . '</span> Ergebnisse</h4></div>';

        foreach ($output as $listing) {
            ob_start();
            $listing_id = $listing['listing_id'];

            $loop_label = $listing['listing_label'];
            $loop_date = $listing['foreclosure_date'];

            $loop_slug = $listing['listing_slug'];
            $loop_featured = $listing['featured'];
            $loop_report = $listing['report_available'];

            $loop_price = $listing['object_val'];
            $loop_desc = $listing['object_desc'];
            $loop_address = $listing['object_address'];

            $loop_catergory = $listing['new_cat'];

            $loop_title = $listing['about_type'];
            $loop_rooms = $listing['listing_rooms'];

            $loop_space = $listing['living_space'];

            $loop_units = $listing['listing_flats'];
            $loop_use = $listing['use_space'];
            $loop_plot = $listing['plot_area'];
            $loop_owner = $listing['listing_ownership'];
            $loop_limit = $listing['value_limit'];

            $loop_earn_month = $listing['earn_month'];

            $loop_demolished = $listing['demolished'];

            $loop_equip = $listing['listing_equipment'];

            include HOME . '/inc/layout/list.php';
            $elements .=  ob_get_clean();

            $coordinates[] = array(
                'listing_id' => $listing_id,
                'lst' => array(
                    'lat' => $listing['lat'],
                    'lng' => $listing['lng']
                )
            );
        }

        if (count($output) !== '0') {
            $elements .= '<div class="search_info__result-loop--next">';

            if (count($output) == '10') {
                if($pageNo !== 0) {
                    $elements .= '<button class="btn btn-blue btn-sm" data-action="prev"><i class="fa fa-angle-double-left"></i><span>Previous Page</span></button>';
                }
                $elements .= '<button class="btn btn-dark btn-sm right" data-action="next"><span>Next Page</span><i class="fa fa-angle-double-right"></i></button>';
            } else {
                if($pageNo !== 0) {
                    $elements .= '<button class="btn btn-blue btn-sm" data-action="prev"><i class="fa fa-angle-double-left"></i><span>Previous Page</span></button>';
                }
            }
            $elements .= '</div>';
        }

        //dump($elements);
        //dump($coordinates);
        if (!empty($elements) && !empty($coordinates)) {
            $response = array(
                'status' => 'success',
                'elements' => $elements,
                'cords' => $coordinates,
            );
        }
    }

    //echo count($results);

    echo json_encode($response, true);
}
