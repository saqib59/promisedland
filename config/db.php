<?php

// my localhost db data
error_reporting(0);
$dbhost = 'localhost';
$dbuser = 'saqib';
$dbpass = 'password';

// db connect
//$db = new db($dbhost, $dbuser, $dbpass, 'promised');
$db = new db($dbhost, $dbuser, $dbpass, 'admin_pland');
//$db = new db($dbhost, $dbuser, $dbpass, 'promised_latest');

//$db = new db($dbhost, 'admin_landus', 'MIpt6yMebvR', 'admin_pland');
//$db = new db($dbhost, 'admin_dummys', 'RAmxaYYtbXwfzcf3', 'admin_dummy');
//$db = new db($dbhost, 'site', 'RAmxaYYtbXwfzcf3', 'site');

// get the secret key to access api data
function secretKey()
{
    global $db;
    $key = $db->query('SELECT * FROM `settings` WHERE `setting_name` = ?', 'request_key')->fetchArray();
    if ($key && !empty($key)) {
        return $key['setting_value'];
    }
    return false;
}

// get selected value from column of any table
function get_data($id, $col, $table)
{
    global $db;
    $key = $db->query("SELECT `{$col}` FROM `{$table}` WHERE `id` = ?", $id)->fetchArray();
    if ($key && !empty($key)) {
        return $key[$col];
    }
    return '';
}

// get selected value from column of any table
function get_col_data($val, $col, $result, $table)
{
    global $db;
    $key = $db->query("SELECT `{$result}` FROM `{$table}` WHERE `{$col}` = ?", $val)->fetchArray();
    if ($key && !empty($key)) {
        return $key[$result];
    }
    return '';
}

// check if row available with id
function check_row($value, $col, $table)
{
    global $db;
    $row = $db->query("SELECT * FROM `{$table}` WHERE `{$col}` = ?", $value);
    if ($row->numRows() > 0) {
        return true;
    }
    return false;
}

function getRowCount($table, $col, $value)
{
    global $db;
    $check = $db->query("SELECT * FROM `{$table}` WHERE `{$col}` = ?;", $value);
    if ($check) {
        return $check->numRows();
    }
    return 0;
}

function updateDatabyId($val, $col, $id, $table)
{
    global $db;
    $query = $db->query("UPDATE `{$table}` SET `{$col}` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `id` = ?;", $val, $id);
    if ($query) {
        return true;
    }
    return false;
}

// user login
function userLogin($email, $pwd)
{
    global $db;
    $pwd = md5($pwd);
    $account = $db->query('SELECT * FROM `users` WHERE `email` = ? AND `pwd` = ?', $email, $pwd);
    if ($account->numRows() == 1) {
        return true;
    }
    return false;
}

function adminAttemp($email, $pwd, $ip)
{
    global $db;
    $details = $db->query('INSERT INTO `admin_attemps` (`id`, `email`, `pwd`, `ip`) VALUES (NULL, ?, ?, ?);', $email, $pwd, $ip);
    return $details;
}

function attempChecker($ip)
{
    global $db;
    $key = $db->query("SELECT COUNT(*) FROM `admin_attemps` WHERE `ip` LIKE ? AND `insert_at` > (now() - interval 30 minute)", $ip)->fetchArray();
    if ($key && !empty($key)) {
        if ($key['COUNT(*)'] >= 3) {
            return false;
        }
    }
    return true;
}

// user register
function userRegister($name, $surname, $bday, $email, $pwd)
{
    global $db;
    $email_key = randomKey(15);
    $register = $db->query('INSERT INTO `users`(`name`, `surname`, `bday`, `email`, `email_key`, `pwd`) VALUES (?, ?, ?, ?, ?, ?);', $name, $surname, $bday, $email, $email_key, md5($pwd));
    if ($register) {
        return true;
    }
    return false;
}


// get selected value from column of listing
function getState($zip)
{
    //dump($zip);
    global $db;
    $key = $db->query("SELECT * FROM `zip` WHERE `zip` = ?", $zip)->fetchArray();
    if ($key) {
        return $key['state'];
    }
    return '';
}

function courseVideoInfo($id, $type)
{
    global $db;
    $video_count = 0;
    $duration = 0;
    $video_uploaded = $db->query('SELECT * FROM `course_video` WHERE `course_id` = ?;', $id)->fetchAll();
    if ($video_uploaded) {
        $video_uploaded = $video_uploaded[0];

        $course_videos = json_decode($video_uploaded['videos'], true);
        $course_videos = $course_videos["course"];

        foreach ($course_videos as $item) {
            $video_count += 1;
            $duration += $item['length'];
        }

        switch ($type) {
            case 'duration':
                return $duration;
                break;
            case 'videos':
                return $video_count;
                break;
            default:
                return 0;
        }
    }
    return 0;
}

function faqCountInfo($question_id, $type)
{
    global $db;
    $query = $db->query("SELECT * FROM `$type` WHERE faq_id = ?;", $question_id);
    return $query->numRows();
}

function checkBooking($table, $col, $seminar_id, $user_id)
{
    global $db;
    $check = $db->query("SELECT * FROM `{$table}` WHERE `{$col}` = ? AND `user_id` = ?;", $seminar_id, $user_id);
    if ($check->numRows() > 0) {
        return true;
    }
    return false;
}

function checkFeedback($table, $col, $id, $user_id)
{
    global $db;
    $check = $db->query("SELECT * FROM `{$table}` WHERE `{$col}` = ? AND `user_id` = ?;", $id, $user_id);
    if ($check->numRows() > 0) {
        return true;
    }
    return false;
}

function checkAnswered($question_id, $user_id)
{
    global $db;
    $check = $db->query("SELECT * FROM `answers` WHERE `question_id` = ? AND `user_id` = ?;", $question_id, $user_id);
    if ($check->numRows() > 0) {
        return true;
    }
    return false;
}

function checkSearchOrder($user_id, $address, $radius, $category, $living_space_from, $living_space_to, $room_count_from, $room_count_to, $value_count, $price_from, $price_to, $model, $denkmalschutz, $reports, $miete_from, $miete_to, $potential_from, $potential_to, $kauf_from, $kauf_to, $preis_from, $preis_to, $month_payment_from, $month_payment_to, $rendite_from, $rendite_to, $multiplier_gross_from, $multiplier_gross_to, $current_usage, $inspection_type, $contaminated, $commitments, $listing_equipment, $construction_year_from, $construction_year_to, $report_time)
{
    global $db;
    $check = $db->query("SELECT * FROM `search_order` WHERE 
    `user` = ? AND `address` = ? AND `radius` = ? AND `category` = ? AND `living_space_from` = ? AND 
    `living_space_to` = ? AND `room_count_from` = ? AND `room_count_to` = ? AND `value_count` = ? AND `price_from` = ? AND 
    `price_to` = ? AND `model3d` = ? AND `denkmalschutz` = ? AND `reports` = ? AND `miete_from` = ? AND 
    `miete_to` = ? AND `potential_from` = ? AND `potential_to` = ? AND `kauf_from` = ? AND `kauf_to` = ? AND 
    `preis_from` = ? AND `preis_to` = ? AND  `month_payment_from` = ? AND `month_payment_to` = ? AND `rendite_from` = ? AND 
    `rendite_to` = ? AND `multiplier_gross_from` = ? AND `multiplier_gross_to` = ? AND `current_usage` = ? AND `inspection_type` = ? AND 
    `contaminated` = ? AND `commitments` = ? AND `listing_equipment` = ? AND `construction_year_from` = ? AND `construction_year_to` = ? AND 
    `report_time` = ?;", 
    $user_id, $address, $radius, $category, $living_space_from, 
    $living_space_to, $room_count_from, $room_count_to, $value_count, $price_from, 
    $price_to, $model, $denkmalschutz, $reports, $miete_from, 
    $miete_to, $potential_from, $potential_to, $kauf_from, $kauf_to, 
    $preis_from, $preis_to, $month_payment_from, $month_payment_to, $rendite_from, 
    $rendite_to, $multiplier_gross_from, $multiplier_gross_to, $current_usage, $inspection_type, 
    $contaminated, $commitments, $listing_equipment, $construction_year_from, $construction_year_to, 
    $report_time);

    if ($check->numRows() > 0) {
        return true;
    }
    return false;
}

function avgRating($table, $col, $seminar_id)
{
    global $db;
    $rowCount = 0;
    $starCount = 0;
    $check = $db->query("SELECT * FROM `{$table}` WHERE `{$col}` = ?;", $seminar_id)->fetchAll();
    if ($check) {
        foreach ($check as $item) {
            $rowCount += 1;
            $starCount += $item['rating'];
        }
        return round($starCount / $rowCount);
    }
    return false;
}

function newDetails($user_id, $address, $state, $city, $zip)
{
    global $db;
    $details = $db->query('INSERT INTO `user_details` (`id`, `user_id`, `address`, `state`, `city`, `zipcode`) VALUES (NULL, ?, ?, ?, ?, ?);', $user_id, $address, $state, $city, $zip);
    return $details;
}

function newMembership($user_id, $plan, $period, $gateway, $trans_id, $coupon_id = '0')
{
    global $db;
    $start_dt = date("Y-m-d H:i:s");
    $end_dt = date("Y-m-d H:i:s", strtotime("+" . $period . " months", strtotime($start_dt)));
    $register = $db->query('INSERT INTO `membership` (`id`, `user_id`, `plan`, `period`, `gateway`, `transaction_id`, `coupon_id`, `start_dt`, `end_dt`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);', $user_id, $plan, $period, $gateway, $trans_id, $coupon_id, $start_dt, $end_dt);
    if ($register) {
        return $db->lastInsertID();
    }
    return false;
}

function userMembership($user_id)
{
    global $db;
    $membership_id = get_data($user_id, 'membership_id', 'users');
    if ($membership_id !== 0) {
        $membship = $db->query("SELECT * FROM `membership` WHERE `id` = ? AND status = 'approved';", $membership_id)->fetchArray();
        if ($membship && !empty($membship)) {
            return $membship['plan'];
        }
    }
    return 'free';
}

function currentMembership($user)
{
    global $db;
    //$current = $db->query('SELECT * FROM `membership` WHERE `user_id` = ? AND start_dt <= ? AND end_dt >= ? ORDER BY `id` DESC LIMIT 1;', $user, today(), today());
    $current = $db->query('SELECT * FROM `membership` WHERE `user_id` = ? ORDER BY `id` DESC LIMIT 1;', $user);
    if ($current->numRows() > 0) {
        return $current->fetchArray();
    }
    return false;
}

function userStatus($user)
{
    $info = array(
        'plan' => 'free',
        'status' => 'approved'
    );
    $current_subscription = currentMembership($user);
    if ($current_subscription !== false || !empty($current_subscription)) {
        $info = array(
            'plan' => $current_subscription['plan'],
            'status' => $current_subscription['status']
        );
    }
    return $info;
}

function userMembInfo($user, $show_renew)
{
    $user_plan = 'Free';
    $cus_status = '';

    $info = userStatus($user);

    $cus_plan = $info['plan'];
    switch ($cus_plan) {
        case 'free':
            $user_plan = 'Free';
            break;
        case 'premium':
            $user_plan = 'Premium';
            break;
        case 'plus':
            $user_plan = 'Premium+';
            break;
        default:
            $user_plan = 'Free';
    }

    $cus_status = $info['status'];

    echo userMembBox($cus_status, $user_plan, $show_renew);
}

function logPaypal($user_id, $plan_id, $subscription_id)
{
    global $db;
    $transaction = $db->query("INSERT INTO `paypal_logs` (`id`, `user_id`, `plan_id`, `subscription_id`, `complete`) VALUES (NULL, ?, ?, ?, ?);", $user_id, $plan_id, $subscription_id, '0');
    return $transaction;
}

function logPaypal_oneTime($user, $txn_id, $course_id, $price_currency, $paid_amount, $payer_email, $status)
{
    global $db;
    $transaction = $db->query("INSERT INTO `paypal_logs_onetime`(`id`, `user_id`, `transaction_id`, `course_id`, `price_currency`, `paid_amount`, `payer_email`, `status`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?);", $user, $txn_id, $course_id, $price_currency, $paid_amount, $payer_email, $status);
    if ($transaction) {
        return $db->lastInsertID();
    }
    return false;
}

function logStripe($user, $subscrID, $custID, $planID, $planAmount, $planCurrency, $planinterval, $planIntervalCount, $email, $status)
{
    global $db;
    $transaction = $db->query("INSERT INTO `stripe_logs` (`id`, `user_id`, `stripe_subscription_id`, `stripe_customer_id`, `stripe_plan_id`, `plan_amount`, `plan_amount_currency`, `plan_interval`, `plan_interval_count`, `payer_email`, `status`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", $user, $subscrID, $custID, $planID, $planAmount, $planCurrency, $planinterval, $planIntervalCount, $email, $status);
    return $transaction;
}

function logStripe_oneTime($user, $txn_id, $course_id, $customer_id, $price_currency, $paid_amount, $payer_email, $status)
{
    global $db;
    $transaction = $db->query("INSERT INTO `stripe_logs_onetime`(`id`, `user_id`, `transaction_id`, `course_id`, `customer_id`, `price_currency`, `paid_amount`, `payer_email`, `status`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);", $user, $txn_id, $course_id, $customer_id, $price_currency, $paid_amount, $payer_email, $status);
    return $transaction;
}

function coupon_usage($coupon_id, $user)
{
    global $db;
    $use = $db->query('INSERT INTO `coupon_usage` (`id`, `coupon_id`, `user`) VALUES (NULL, ?, ?);', $coupon_id, $user);
    if ($use) {
        return $db->lastInsertID();
    }
    return false;
}

function courseSubscribe($course_id, $user_id, $txn_id, $gateway)
{
    global $db;
    $register = $db->query('INSERT INTO `course_subscribe`(`id`, `course_id`, `user_id`, `txn_id`, `gateway`) VALUES (NULL, ?, ?, ?, ?);', $course_id, $user_id, $txn_id, $gateway);
    if ($register) {
        return $db->lastInsertID();
    }
    return false;
}

function checkCourse($user_id, $course)
{
    global $db;
    $check = $db->query("SELECT * FROM `course_subscribe` WHERE `course_id` = ? AND `user_id` = ?;", $course, $user_id);
    if ($check->numRows() > 0) {
        return true;
    }
    return false;
}

function checkFav($user_id, $listing_id)
{
    global $db;
    $check = $db->query("SELECT * FROM `favorite` WHERE `user_id` = ? AND `listing_id` = ?;", $user_id, $listing_id);
    if ($check->numRows() > 0) {
        return true;
    }
    return false;
}

function checkLike($user_id, $faq_id)
{
    global $db;
    $check = $db->query("SELECT * FROM `course_faq_likes` WHERE `user_id` = ? AND `faq_id` = ?;", $user_id, $faq_id);
    if ($check->numRows() > 0) {
        return true;
    }
    return false;
}

function newUserAlert($user, $type, $alert, $link)
{
    global $db;
    $alert = $db->query("INSERT INTO `user_alerts`(`id`, `user`, `type`, `alert`, `link`) VALUES (NULL, ?, ?, ?, ?);", $user, $type, $alert, $link);
    return $alert;
}

function setSearchMatch($listing_id, $order_id)
{
    global $db;
    $match = $db->query("INSERT INTO `search_order_results`(`id`, `listing_id`, `order_id`) VALUES (NULL, ?, ?);", $listing_id, $order_id);
    return $match;
}

function searchOrderMatching($listing_id)
{
    global $db;

    $match_slug = '';
    $match_address = '';
    $match_lat = '';
    $match_lng = '';
    $match_price = '';

    $match_space = '';
    $match_rooms = '';

    $match_limit = '';

    $match_earn_month = '';

    $match_model = '';
    $match_denkmalschutz = '';
    $match_reports = '';
    $match_current_usage = '';
    $match_inspection_type = '';

    $match_contaminated = '';
    $match_commitments = '';

    $match_equipment = array();

    $match_construction_year = '';

    $match_report_date = '';

    // retrieve data for search order match
    /* $sql = "SELECT * FROM ((listing
    INNER JOIN about ON about.listing_id = listing.id)
    INNER JOIN details ON details.listing_id = listing.id) WHERE listing.id = '{$listing_id}';";
    $listingData = $db->query($sql)->fetchArray(); */

    $listingData = $db->query("SELECT * FROM `listing` WHERE `id` = ?;", $listing_id)->fetchArray();
    $aboutData = $db->query("SELECT * FROM `about` WHERE `listing_id` = ?;", $listing_id)->fetchArray();
    $detailsData = $db->query("SELECT * FROM `details` WHERE `listing_id` = ?;", $listing_id)->fetchArray();
    $descriptionData = $db->query("SELECT * FROM `description` WHERE `listing_id` = ?;", $listing_id)->fetchArray();
    $foreclosureData = $db->query("SELECT * FROM `foreclosure` WHERE `listing_id` = ?;", $listing_id)->fetchArray();
    $energyData = $db->query("SELECT * FROM `energy` WHERE `listing_id` = ?;", $listing_id)->fetchArray();

    $match_listing = $listing_id;

    if ($listingData && !empty($listingData)) {
        $match_slug = $listingData['listing_slug'];
        $match_address = $listingData['object_address'];
        $match_lat = $listingData['lat'];
        $match_lng = $listingData['lng'];
        $match_price = $listingData['object_val'];
        $match_reports = $listingData['report_available'];
    }

    if ($aboutData && !empty($aboutData)) {
        $match_space = $aboutData['living_space'];
        $match_current_usage = $aboutData['current_usage'];
        $match_earn_month = $aboutData['earn_month'];
    }

    if ($detailsData && !empty($detailsData)) {
        $match_rooms = $detailsData['listing_rooms'];
        $match_limit = $detailsData['value_limit'];
        $match_model = $detailsData['model_url'];

        if(isset($detailsData['listing_equipment']) && !empty($detailsData['listing_equipment'])) {
            $match_equipment = json_decode($detailsData['listing_equipment'], true);
        }
    }

    if ($foreclosureData && !empty($foreclosureData)) {
        $match_denkmalschutz = $foreclosureData['denkmalschutz'];
        $match_inspection_type = $foreclosureData['inspection_type'];
        $match_report_date = $foreclosureData['inspection_date'];
    }

    if ($descriptionData && !empty($descriptionData)) {
        $match_contaminated = $descriptionData['contaminated'];
        $match_commitments = $descriptionData['commitments'];
    }
    if ($energyData && !empty($energyData)) {
        $match_construction_year = $energyData['construction_year'];
    }

    $match_category_all = getCatArray($match_listing);

    $results = array();

    // get all search orders and check for facts
    $all_results = $db->query("SELECT * FROM `search_order` WHERE `pause` = '0';")->fetchAll();
    if ($all_results && !empty($all_results)) {
        foreach ($all_results as $one_result) {
            $include = true;

            // location filter
            $db_address = $one_result['address'];
            $latitude = $one_result['lat'];
            $longitude = $one_result['lng'];
            $db_radius = $one_result['radius'];

            if (!empty($db_address) && !empty($latitude) && !empty($longitude) && !empty($db_radius)) {
                $match_distance = (((acos(sin(($latitude * pi() / 180)) * sin(($match_lat * pi() / 180)) + cos(($latitude * pi() / 180)) * cos(($match_lat * pi() / 180)) * cos((($longitude - $match_lng) * pi() / 180)))) * 180 / pi()) * 60 * 1.1515 * 1.609344);

                if ($db_radius < $match_distance) {
                    $include = false;
                }
            }

            // category filter
            $db_category = $one_result['category'];
            $match_category_all = getCatArray($listing_id);
            if (!empty($db_category)) {
                if (!in_array($db_category,  $match_category_all)) {
                    $include = false;
                }
            }

            // value limit
            $db_value_count = $one_result['value_count'];
            if (!empty($db_value_count)) {
                if ($db_value_count !== $match_limit) {
                    $include = false;
                }
            }

            // living space
            $db_living_space_from = $one_result['living_space_from'];
            $db_living_space_to = $one_result['living_space_to'];
            $status_living_space = includeStatus($db_living_space_from, $db_living_space_to, $match_space);
            if ($status_living_space == 0) {
                $include = false;
            }

            // room count
            $db_room_count_from = $one_result['room_count_from'];
            $db_room_count_to = $one_result['room_count_to'];
            $status_room_count = includeStatus($db_room_count_from, $db_room_count_to, $match_rooms);
            if ($status_room_count == 0) {
                $include = false;
            }

            // price
            $db_price_from = $one_result['price_from'];
            $db_price_to = $one_result['price_to'];
            $status_price = includeStatus($db_price_from, $db_price_to, $match_price);
            if ($status_price == 0) {
                $include = false;
            }

            // 3d model
            if (!empty($one_result['model3d'])) {
                if ($one_result['model3d'] == 'yes') {
                    if (empty($match_model)) {
                        $include = false;
                    }
                }
                if ($one_result['model3d'] == 'no') {
                    if (!empty($match_model)) {
                        $include = false;
                    }
                }
            }

            // denkmalschutz
            if (!empty($one_result['denkmalschutz'])) {
                if ($one_result['denkmalschutz'] == 'yes') {
                    if ($match_denkmalschutz == '0') {
                        $include = false;
                    }
                }
                if ($one_result['denkmalschutz'] == 'no') {
                    if ($match_denkmalschutz == '1') {
                        $include = false;
                    }
                }
            }

            // reports
            /* if (!empty($one_result['reports'])) {
                if ($one_result['reports'] == 'yes') {
                    if ($match_reports == 'none') {
                        $include = false;
                    }
                }
                if ($one_result['reports'] == 'no') {
                    if ($match_reports == 'long' || $match_reports == 'short') {
                        $include = false;
                    }
                }
            } */
            if (!empty($one_result['reports'])) {
                $matchCount = 0;
                $report_list = explode(',', $one_result['reports']);
                foreach ($report_list as $report_item) {
                    if ($report_item == $match_reports) {
                        $matchCount += 1;
                    }
                }
                if ($matchCount == 0) {
                    $include = false;
                }
            }

            ////////////////////////////////////////////
            ////////////////////////////////////////////
            ////////////////////////////////////////////

            $earn_month = price($match_earn_month);
            $listing_space = $match_space;

            // listing type
            $rent_table = 'rent_house';
            $buy_table = 'buy_house';
            if (in_array('Zweifamilienhaus', $match_category_all)) {
                $rent_table = 'rent_house';
                $buy_table = 'buy_house';
            } elseif (in_array('Eigentumswohnungen', $match_category_all)) {
                $rent_table = 'rent_flat';
                $buy_table = 'buy_flat';
            }

            // listing zip
            $listing_zip = getZip($match_address);
            $potential_rent_db = get_col_data($listing_zip, 'zip', 'avarage_rent', $rent_table);

            $earn_month_fix = '';
            if (empty($earn_month)) {
                if (!empty($listing_space)) {
                    $earn_month_fix = (float)$potential_rent_db * (float)$listing_space;
                }
            } else {
                $earn_month_fix = $earn_month;
            }

            ////////////////////////////////////////////
            ////////////////////////////////////////////
            ////////////////////////////////////////////

            // Ist-Miete
            $actual_rent = '';
            if (!empty($earn_month) && !empty($listing_space)) {
                $actual_rent = (float)$earn_month / (float)$listing_space;
            }
            $status_actual_rent = includeStatus($one_result['miete_from'], $one_result['miete_to'], $actual_rent);
            if ($status_actual_rent == 0) {
                $include = false;
            }

            // Potenzielle Miete
            $potential_rent = get_col_data($listing_zip, 'zip', 'avarage_rent', $rent_table);
            $status_potential_rent = includeStatus($one_result['potential_from'], $one_result['potential_to'], $potential_rent);
            if ($status_potential_rent == 0) {
                $include = false;
            }

            // Kaufpreis
            $purchase_price = '';
            if (!empty($match_price) && !empty($listing_space)) {
                $purchase_price = (float)$match_price / (float)$listing_space;
            }
            $status_purchase_price = includeStatus($one_result['kauf_from'], $one_result['kauf_to'], $purchase_price);
            if ($status_purchase_price == 0) {
                $include = false;
            }

            // Durchschnittlicher Kaufpreis
            $avarage_buying = get_col_data($listing_zip, 'zip', 'avarage_rent', $buy_table);
            $status_avarage_buying = includeStatus($one_result['preis_from'], $one_result['preis_to'], $avarage_buying);
            if ($status_avarage_buying == 0) {
                $include = false;
            }

            // Potentielle Rendite
            $potential_return = '';
            if (!empty($earn_month_fix) && !empty($match_price)) {
                $potential_return = ($earn_month_fix * 12 * 100) / $match_price;
            }
            $status_room_count = includeStatus($one_result['rendite_from'], $one_result['rendite_to'], $potential_return);
            if ($status_room_count == 0) {
                $include = false;
            }

            // Mietmultiplikator
            $multiplier_gross = '';
            if (!empty($earn_month_fix) && !empty($db_price)) {
                $multiplier_gross = $db_price / ($earn_month_fix * 12);
            }
            $status_multiplier_gross = includeStatus($one_result['multiplier_gross_from'], $one_result['multiplier_gross_to'], $multiplier_gross);
            if ($status_multiplier_gross == 0) {
                $include = false;
            }

            ////////////////////////////////////////////

            // Vermietungsstatus
            if (!empty($one_result['current_usage'])) {
                if ($one_result['current_usage'] !== $match_current_usage) {
                    $include = false;
                }
            }

            // Besichtigungsart
            if (!empty($one_result['inspection_type'])) {
                if ($one_result['inspection_type'] !== $match_inspection_type) {
                    $include = false;
                }
            }

            // Altlastenverdacht
            $status_contaminated = 0;
            if (isset($one_result['altlastenverdacht']) && !empty($one_result['altlastenverdacht'])) {
                if ($one_result['altlastenverdacht'] == 'yes') {
                    //
                } else {
                    $status_contaminated = 1;
                }
            } else {
                $status_contaminated = 1;
            }

            if ($status_contaminated == 0) {
                if ($match_contaminated !== '2') {
                    $include = false;
                }
            }

            // mietbindungen
            $status_commitments = 0;
            if (isset($one_result['mietbindungen']) && !empty($one_result['mietbindungen'])) {
                if ($one_result['mietbindungen'] == 'yes') {
                    //$status_commitments = 0;
                } else {
                    $status_commitments = 1;
                }
            } else {
                $status_commitments = 1;
            }

            if ($status_commitments == 0) {
                if ($match_commitments !== '2') {
                    $include = false;
                }
            }

            /* if (!empty($one_result['contaminated'])) {
                if ($one_result['contaminated'] !== $match_contaminated) {
                    $include = false;
                }
            } */

            // Besondere Ausstattung
            if (isset($one_result['listing_equipment']) && !empty($one_result['listing_equipment'])) {
                $listing_equipment = explode(',', $one_result['listing_equipment']);
                $contain_equips = array_intersect($match_equipment, $listing_equipment);
                if (empty($contain_equips)) {
                    $include = false;
                }
            }

            // Baujahr
            if (!empty($one_result['construction_year_from']) || !empty($one_result['construction_year_to'])) {
                if (empty($match_construction_year)) {
                    $include = false;
                } elseif (!is_numeric($match_construction_year)) {
                    $include = false;
                } else {
                    if (!empty($one_result['construction_year_from']) && !empty($one_result['construction_year_to'])) {
                        if (($one_result['construction_year_from'] <= $match_construction_year) && ($match_construction_year <= $one_result['construction_year_to'])) {
                            //
                        } else {
                            $include = false;
                        }
                    } else if (!empty($one_result['construction_year_from'])) {
                        if (($one_result['construction_year_from'] > $match_construction_year)) {
                            $include = false;
                        }
                    } elseif (!empty($p['construction_year_to'])) {
                        if (($match_construction_year > $p['construction_year_to'])) {
                            $include = false;
                        }
                    }
                }
            }

            /* if (!empty($one_result['construction_year'])) {
                if (strpos($match_construction_year, $one_result['construction_year']) == false) {
                    $include = false;
                }
            } */

            // Report created earlier than
            $inspection_date = '';
            if (!empty($match_report_date)) {
                $inspection_date = date_create($match_report_date);
                $inspection_date = date_format($inspection_date, "Y-m-d");
            }

            if (!empty($inspection_date) && !empty($one_result['report_time'])) {
                if (strtotime($inspection_date) > strtotime($one_result['report_time'])) {
                    $include = false;
                }
            }

            // if everything is true, save it to main loop
            if ($include == true) {
                $new_result = array($one_result);
                $results = array_merge($results, $new_result);
            }
        }
    }

    // get all search orders that match to this listing data
    if (!empty($results)) {
        if (count($results) > 1) {
            //dump($results);
            foreach ($results as $sr_item) {
                // assign listing to search orders
                if (setSearchMatch($match_listing, $sr_item['id'])) {

                    // @@mail : search order result hit
                    search_order_result($sr_item['user'], $listing_id);

                    newUserAlert($sr_item['user'], 'search_order', 'Dein Suchauftrag hat einen neuen Treffer ergeben (# ' . $sr_item['id'] . ')', '/listing/' . $match_slug . '/');
                    return true;
                }
            }
        } else {
            $results = $results[0];
            if (setSearchMatch($match_listing, $results['id'])) {

                // @@mail : search order result hit
                search_order_result($results['user'], $listing_id);

                newUserAlert($results['user'], 'search_order', 'Dein Suchauftrag hat einen neuen Treffer ergeben (# ' . $results['id'] . ')', '/listing/' . $match_slug . '/');
                return true;
            }
        }
    }

    return false;
}

function subsFailed($log_id, $gateway, $status)
{
    global $db;
    $register = $db->query('INSERT INTO `membership_cancel`(`id`, `log_id`, `gateway`, `status`) VALUES (NULL, ?, ?, ?);', $log_id, $gateway, $status);
    if ($register) {
        return true;
    }
    return false;
}
