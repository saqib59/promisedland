<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

// get all data from API
$listings = getAPI();
$all = json_decode($listings, true);

foreach ($all as $item) {

    // check if this listing id is already presented in the website listing list
    $check_listing = $db->query("SELECT * FROM `listing` WHERE `listing_label` = ?;", $item["id"]);
    if ($check_listing->numRows() == 0) { // if not presented

        // insert that row of data to the website
        $update_listing = $db->query("INSERT INTO `listing` (`id`, `platform`, `listing_label`, `listing_slug`, `foreclosure_cat`, `foreclosure_court`, `object_cat`, `object_address`, `object_desc`, `object_val`, `foreclosure_date`, `foreclosure_add`, `amtlichebekanntmachung_pdf`, `gutachten_pdf`, `exposee_pdf`, `misc`, `canceled`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", NULL, $item["platform"], $item["id"], createSlug($item["id"]), $item["foreclosure_cat"], $item["foreclosure_court"], $item["object_cat"], $item["object_address"], $item["object_desc"], $item["object_val"], $item["foreclosure_date"], $item["foreclosure_add"], $item["amtlichebekanntmachung_pdf"], $item["gutachten_pdf"], $item["exposee_pdf"], $item["misc"], $item["canceled"]);
        if($update_listing) echo 'New listing data row has been copied from the api successfully!';
        break; // there can be more so break the loop for limiting the process to one data row
    }
}
