<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    $order_id = $p['order_id'];

    if (empty($order_id)) {
        echo '0';
        exit();
    } else {
        $results = $db->query("SELECT * FROM `search_order_results` WHERE `order_id` = ?;", $order_id)->fetchAll();
        if ($results) {
            echo '<div class="so_results show_line">';
            foreach ($results as $item) {
                $listing_id = $item['listing_id'];

                $listingData = $db->query('SELECT * FROM `listing` WHERE `id` = ?', $listing_id)->fetchArray();
                $detailsData = $db->query('SELECT * FROM `details` WHERE `listing_id` = ?', $listing_id)->fetchArray();
                $aboutData = $db->query('SELECT * FROM `about` WHERE `listing_id` = ?', $listing_id)->fetchArray();

                if ($listingData && !empty($listingData)) {
                    $loop_featured = $listingData['featured'];
                    $loop_report = $listingData['report_available'];

                    $loop_label = $listingData['listing_label'];
                    $loop_date = $listingData['foreclosure_date'];

                    $loop_slug = $listingData['listing_slug'];
                    $loop_price = $listingData['object_val'];
                    $loop_address = $listingData['object_address'];
                    $loop_desc = $listingData['object_desc'];
                    $loop_catergory = $listingData['new_cat'];

                    if ($detailsData && !empty($detailsData)) {
                        $loop_title = $detailsData['about_type'];
                        $loop_rooms = $detailsData['listing_rooms'];

                        $loop_units = $detailsData['listing_flats'];
                        $loop_owner = $detailsData['listing_ownership'];
                        $loop_limit = $detailsData['value_limit'];

                        $loop_equip = $detailsData['listing_equipment'];
                    }

                    if ($aboutData && !empty($aboutData)) {
                        $loop_space = $aboutData['living_space'];
                        $loop_use = $aboutData['use_space'];
                        $loop_plot = $aboutData['plot_area'];
                        $loop_earn_month = $aboutData['earn_month'];

                        $loop_demolished = $aboutData['demolished'];
                    }

                    include HOME . '/inc/layout/list.php';
                } else {
                    echo '0';
                    exit();
                }
            }
            echo '</div>';
        } else {
            echo '0';
            exit();
        }
    }
} else {
    echo '0';
    exit();
}
