<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

/* $newLocale = setlocale(LC_TIME, 'de_DE', 'de_DE.UTF-8');
$gem_date = strftime('%A, %d %B %Y, %H:%M', time()) . " Uhr";
dump($gem_date); */

$listings = $db->query("SELECT * FROM `listing`;")->fetchAll();
foreach ($listings as $item) {

    /* $date = explode(', ', $item['foreclosure_date']);

    $normal_date = fixDate($date[1]);
    $normal_date = str_replace(' ', '-', $normal_date); */

    $normal_date = $item['foreclosure_date'];
    
    /* if (strtotime($normal_date) < strtotime('-7 day')) {
        $listing_id = $item['id'];
        
        $delete_listing = $db->query('DELETE FROM `listing` WHERE `id` = ?;', $listing_id);
        $delete_details = $db->query('DELETE FROM `details` WHERE `listing_id` = ?;', $listing_id);
        $delete_foreclosure = $db->query('DELETE FROM `foreclosure` WHERE `listing_id` = ?;', $listing_id);
        $delete_acquisition = $db->query('DELETE FROM `acquisition` WHERE `listing_id` = ?;', $listing_id);
        $delete_energy = $db->query('DELETE FROM `energy` WHERE `listing_id` = ?;', $listing_id);
        $delete_construction = $db->query('DELETE FROM `construction` WHERE `listing_id` = ?;', $listing_id);
        $delete_facility = $db->query('DELETE FROM `facility` WHERE `listing_id` = ?;', $listing_id);
        $delete_description = $db->query('DELETE FROM `description` WHERE `listing_id` = ?;', $listing_id);

        if ($delete_listing || $delete_details || $delete_foreclosure || $delete_acquisition || $delete_energy || $delete_construction || $delete_facility || $delete_description) {
            echo 'Listing Deleted';
        }
    } */
}
