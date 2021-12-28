<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    $listing_id = $p['listing_id'];

    $loop_label = $p['listing_label'];
    $loop_date = $p['foreclosure_date'];

    $loop_slug = $p['listing_slug'];

    $loop_featured = $p['featured'];
    $loop_report = $p['report_available'];

    $loop_price = $p['object_val'];
    $loop_desc = $p['object_desc'];
    $loop_address = $p['object_address'];

    $loop_catergory = $p['new_cat'];

    $loop_title = $p['about_type'];
    $loop_rooms = $p['listing_rooms'];

    $loop_space = $p['living_space'];

    $loop_units = $p['listing_flats'];
    $loop_use = $p['use_space'];
    $loop_plot = $p['plot_area'];
    $loop_owner = $p['listing_ownership'];
    $loop_limit = $p['value_limit'];
    
    $loop_earn_month = $p['earn_month'];

    $loop_demolished = $p['demolished'];
    
    $loop_equip = $p['listing_equipment'];

    include HOME . '/inc/layout/list.php';
} else {
    echo 0;
}
