<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$begin = date("Y-m-d 00:00:00", strtotime("+7 days"));
$end = date("Y-m-d 23:59:59", strtotime("+7 days"));

$listings = $db->query("SELECT * FROM `listing` WHERE `foreclosure_date` BETWEEN ? AND ?;", $begin, $end);

if ($listings && !empty($listings)) {
    foreach ($listings as $item) {
        
        // retrieve all listing id's
        $listing = $item['id'];

        // retrieve all search orders based on this listing id
        $orders = $db->query("SELECT * FROM `search_order_results` WHERE `listing_id` = ?;", $listing_id)->fetchAll();
        if ($orders && !empty($orders)) {
            foreach ($orders as $item) {
                $order_id = $item['order_id'];
                $order_user = get_data($order_id, 'user', 'search_order');

                // @@mail : send auction email
                listing_auction($listing_id, $order_user);
            }
        }

        // retrieve all favorites based on this listing id
        $favs = $db->query("SELECT * FROM `favorite` WHERE `listing_id` = ?;", $listing_id)->fetchAll();
        if ($favs && !empty($favs)) {
            foreach ($favs as $item) {
                $fav_user = $item['user_id'];

                // @@mail : send auction email
                listing_auction($listing_id, $fav_user);
            }
        }

    }
}
