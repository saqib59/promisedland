<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

$favorites = $db->query("SELECT * FROM `favorite` WHERE `user_id` = ?;", $user)->fetchAll();
$emptyOk = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Favoriten - Promised Land</title>
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
                        <section id="favourite" class="no-gap">
                            <div class="favourite_list">

                                <?php
                                if ($favorites && !empty($favorites)) {
                                    echo '<div class="row row-cols-xl-2 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 row-cols-1">';
                                    foreach ($favorites as $fav) {
                                        $listing_id = $fav['listing_id'];

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

                                            echo '<div class="col">';
                                            include HOME . '/inc/layout/list.php';
                                            echo '</div>';
                                        }
                                    }
                                    echo '</div>';
                                } else { ?>
                                    <div class="alert alert-info">
                                        <i class="fa fa-exclamation-circle"></i>
                                        <span>Du hast keine Objekte auf der Merkliste gespeichert</span>
                                    </div>
                                <?php } ?>

                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>
</body>

</html>