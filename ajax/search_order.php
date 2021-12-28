<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

$address = '';
$radius = '';
$category = '';

$living_space_from = '';
$living_space_to = '';

$room_count_from = '';
$room_count_to = '';

$value_count = '';

$price_from = '0';
$price_to = '1000000';

$model = '';
$denkmalschutz = '';
$reports = '';

$miete_from = '';
$miete_to = '';

$potential_to = '';
$potential_from = '';

$kauf_from = '';
$kauf_to = '';

$preis_from = '';
$preis_to = '';

$month_payment_from = '0';
$month_payment_to = '10000';

$rendite_from = '';
$rendite_to = '';

$multiplier_gross_from = '';
$multiplier_gross_to = '';

$current_usage = '';
$inspection_type = '';

$contaminated = '';
$commitments = '';

$listing_equipment = '';

$construction_year_from = '';
$construction_year_to = '';

$report_time = '';

$latitude = '';
$longitude = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    if (isset($p['address']) & !empty($p['address'])) $address = $p['address'];

    if (isset($p['radius']) & !empty($p['radius'])) {
        if (!empty($address)) {
            $radius = $p['radius'];
        }
    }

    if (isset($p['category']) & !empty($p['category'])) $category = $p['category'];

    if (isset($p['space_from']) & !empty($p['space_from'])) $living_space_from = $p['space_from'];
    if (isset($p['space_to']) & !empty($p['space_to'])) $living_space_to = $p['space_to'];

    if (isset($p['rooms_from']) & !empty($p['rooms_from'])) $room_count_from = $p['rooms_from'];
    if (isset($p['rooms_to']) & !empty($p['rooms_to'])) $room_count_to = $p['rooms_to'];

    if (isset($p['value']) & !empty($p['value'])) $value_count = $p['value'];
    if (isset($p['price_from']) & !empty($p['price_from'])) $price_from = $p['price_from'];
    if (isset($p['price_to']) & !empty($p['price_to'])) $price_to = $p['price_to'];

    if (isset($p['model']) & !empty($p['model'])) $model = $p['model'];
    if (isset($p['denkmalschutz']) & !empty($p['denkmalschutz'])) $denkmalschutz = $p['denkmalschutz'];
    if (isset($p['reports']) & !empty($p['reports'])) $reports = $p['reports'];

    if (isset($p['miete_from']) & !empty($p['miete_from'])) $miete_from = $p['miete_from'];
    if (isset($p['miete_to']) & !empty($p['miete_to'])) $miete_to = $p['miete_to'];

    if (isset($p['potential_from']) & !empty($p['potential_from'])) $potential_from = $p['potential_from'];
    if (isset($p['potential_to']) & !empty($p['potential_to'])) $potential_to = $p['potential_to'];

    if (isset($p['kauf_from']) & !empty($p['kauf_from'])) $kauf_from = $p['kauf_from'];
    if (isset($p['kauf_to']) & !empty($p['kauf_to'])) $kauf_to = $p['kauf_to'];

    if (isset($p['preis_from']) & !empty($p['preis_from'])) $preis_from = $p['preis_from'];
    if (isset($p['preis_to']) & !empty($p['preis_to'])) $preis_to = $p['preis_to'];

    if (isset($p['month_payment_from']) & !empty($p['month_payment_from'])) $month_payment_from = $p['month_payment_from'];
    if (isset($p['month_payment_to']) & !empty($p['month_payment_to'])) $month_payment_to = $p['month_payment_to'];

    if (isset($p['rendite_from']) & !empty($p['rendite_from'])) $rendite_from = $p['rendite_from'];
    if (isset($p['rendite_to']) & !empty($p['rendite_to'])) $rendite_to = $p['rendite_to'];

    if (isset($p['multiplier_gross_from']) & !empty($p['multiplier_gross_from'])) $multiplier_gross_from = $p['multiplier_gross_from'];
    if (isset($p['multiplier_gross_to']) & !empty($p['multiplier_gross_to'])) $multiplier_gross_to = $p['multiplier_gross_to'];

    if (isset($p['current_usage']) & !empty($p['current_usage'])) $current_usage = $p['current_usage'];
    if (isset($p['inspection_type']) & !empty($p['inspection_type'])) $inspection_type = $p['inspection_type'];

    if (isset($p['contaminated']) & !empty($p['contaminated'])) $contaminated = $p['contaminated'];
    if (isset($p['commitments']) & !empty($p['commitments'])) $commitments = $p['commitments'];

    if (isset($p['listing_equipment']) & !empty($p['listing_equipment'])) $listing_equipment = $p['listing_equipment'];

    if (isset($p['construction_year_from']) & !empty($p['construction_year_from'])) $construction_year_from = $p['construction_year_from'];
    if (isset($p['construction_year_to']) & !empty($p['construction_year_to'])) $construction_year_to = $p['construction_year_to'];

    if (isset($p['report_time']) & !empty($p['report_time'])) $report_time = $p['report_time'];

    if (user() !== false) {
        if (
            empty($address) && empty($radius) && empty($category) &&
            empty($living_space_from) && empty($living_space_to) &&
            empty($room_count_from) && empty($room_count_to) &&
            empty($value_count) &&
            empty($price_from) && empty($price_to) &&

            empty($model) && empty($denkmalschutz) && empty($reports) &&

            empty($miete_from) && empty($miete_to) &&
            empty($potential_from) && empty($potential_to) &&
            empty($kauf_from) && empty($kauf_to) &&
            empty($preis_from) && empty($preis_to) &&
            empty($month_payment_from) && empty($month_payment_to) &&
            empty($rendite_from) && empty($rendite_to) &&
            empty($multiplier_gross_from) && empty($multiplier_gross_to) &&

            empty($current_usage) && empty($inspection_type) &&
            empty($contaminated) && empty($commitments) &&
            empty($listing_equipment) &&
            empty($construction_year_from) && empty($construction_year_to) &&
            empty($report_time)
        ) {
            echo 'empty';
            exit();
        } else {

            // check search order count
            $search_order_count = getRowCount('search_order', 'user', $user);

            // set search order count limit
            $search_order_limit = 0;
            if (contentStatus(array('premium'))) {
                $search_order_limit = 3;
            }
            if (contentStatus(array('free'))) {
                $search_order_limit = 1;
            }

            // permission to process search order
            $process_search_order = false;
            if ($search_order_limit == 0) {
                $process_search_order = true;
            } elseif ($search_order_limit > $search_order_count) {
                $process_search_order = true;
            }

            if ($process_search_order == true) {

                // check if have a search order with same details already
                if (checkSearchOrder($user, $address, $radius, $category, $living_space_from, $living_space_to, $room_count_from, $room_count_to, $value_count, $price_from, $price_to, $model, $denkmalschutz, $reports, $miete_from, $miete_to, $potential_from, $potential_to, $kauf_from, $kauf_to, $preis_from, $preis_to, $month_payment_from, $month_payment_to, $rendite_from, $rendite_to, $multiplier_gross_from, $multiplier_gross_to, $current_usage, $inspection_type, $contaminated, $commitments, $listing_equipment, $construction_year_from, $construction_year_to, $report_time)) {
                    echo 'already';
                    exit();
                } else {

                    if (!empty($address)) {
                        $address_coords = geocode($address);
                        if (!empty($address_coords)) {
                            $latitude = $address_coords[0];
                            $longitude = $address_coords[1];
                        }
                    }

                    $order = $db->query("INSERT INTO `search_order`(`id`, 
                    `user`, `address`, `lat`, `lng`, `radius`, 
                    `category`, `living_space_from`, `living_space_to`, `room_count_from`, `room_count_to`, 
                    `value_count`, `price_from`, `price_to`, `model3d`, `denkmalschutz`, 
                    `reports`, `miete_from`, `miete_to`, `potential_from`, `potential_to`, 
                    `kauf_from`, `kauf_to`, `preis_from`, `preis_to`, `month_payment_from`, 
                    `month_payment_to`, `rendite_from`, `rendite_to`, `multiplier_gross_from`, `multiplier_gross_to`, 
                    `current_usage`, `inspection_type`, `contaminated`, `commitments`, `listing_equipment`, 
                    `construction_year_from`, `construction_year_to`, `report_time`) VALUES (NULL, 
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", 
                    $user, $address, $latitude, $longitude, $radius, 
                    $category, $living_space_from, $living_space_to, $room_count_from, $room_count_to, 
                    $value_count, $price_from, $price_to, $model, $denkmalschutz, 
                    $reports, $miete_from, $miete_to, $potential_from, $potential_to, 
                    $kauf_from, $kauf_to, $preis_from, $preis_to, $month_payment_from, 
                    $month_payment_to, $rendite_from, $rendite_to, $multiplier_gross_from, $multiplier_gross_to, 
                    $current_usage, $inspection_type, $contaminated, $commitments, $listing_equipment, 
                    $construction_year_from, $construction_year_to, $report_time);
                    
                    if ($order) {

                        $data = array(
                            'address' => $address,
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'radius' => $radius, 
                            'category' => $category,
                            'living_space_from' => $living_space_from,
                            'living_space_to' => $living_space_to,
                            'room_count_from' => $room_count_from,
                            'room_count_to' => $room_count_to, 
                            'value_count' => $value_count,
                            'price_from' => $price_from, 
                            'price_to' => $price_to, 
                            'model' => $model, 
                            'denkmalschutz' => $denkmalschutz, 
                            'reports' => $reports, 
                            'miete_from' => $miete_from, 
                            'miete_to' => $miete_to, 
                            'potential_from' => $potential_from,
                            'potential_to' =>  $potential_to, 
                            'kauf_from' => $kauf_from, 
                            'kauf_to' => $kauf_to, 
                            'preis_from' => $preis_from, 
                            'preis_to' => $preis_to, 
                            'month_payment_from' => $month_payment_from, 
                            'month_payment_to' => $month_payment_to, 
                            'rendite_from' => $rendite_from, 
                            'rendite_to' => $rendite_to, 
                            'multiplier_gross_from' => $multiplier_gross_from, 
                            'multiplier_gross_to' => $multiplier_gross_to, 
                            'current_usage' => $current_usage, 
                            'inspection_type' => $inspection_type, 
                            'contaminated' => $contaminated, 
                            'commitments' => $commitments, 
                            'listing_equipment' => $listing_equipment, 
                            'construction_year_from' => $construction_year_from, 
                            'construction_year_to' => $construction_year_to, 
                            'report_time' => $report_time
                        );

                        // @@ mail : search order set mail
                        search_order_create($user, $data);

                        echo 'success';
                        exit();
                    }
                }
            } else {
                echo 'limited';
                exit();
            }
        }
    } else {
        echo 'logged';
        exit();
    }
}
echo '0';
exit();
