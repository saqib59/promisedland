<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if (admin() == false) {
    redirect("Please login first!", ADMIN . '/login.php');
    exit();
}

if (!role('admin') && !role('manager')) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

if (role('manager')) {
    $listings = $db->query('SELECT * FROM `listing` WHERE completed = 1 AND `admin` = ?;', $_SESSION['admin'])->fetchAll();
} else {
    $listings = $db->query('SELECT * FROM `listing` WHERE completed = 1;')->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    if (role('manager')) {
        $listings = $db->query('SELECT * FROM `listing` WHERE completed = 1 AND `admin` = ? AND `foreclosure_date` BETWEEN ? AND ?;', $_SESSION['admin'], $p['start_date'], $p['end_date'])->fetchAll();
    } else {
        $listings = $db->query('SELECT * FROM `listing` WHERE completed = 1 AND `foreclosure_date` BETWEEN ? AND ?;', $p['start_date'], $p['end_date'])->fetchAll();
    }
}

if (isset($_GET['listing_id']) && $_GET['listing_id'] !== '') {
    $listing_id = $_GET['listing_id'];
    if (isset($_GET['action'])) {

        if ($_GET['action'] == 'delete') {

            $delete_listing = $db->query("UPDATE `listing` SET `completed` = '2' WHERE `id` = ?;", $listing_id);
            if ($delete_listing) {
                redirect('Listing Deleted Successfully!', ADMIN . '/complete_listings.php');
            }

        }

        if ($_GET['action'] == 'notcomplete') {
            $hide_listing = $db->query("UPDATE `listing` SET `completed` = '0' WHERE `id` = ?;", $listing_id);
            if ($hide_listing) {
                redirect('Listing Mark as Not Completed Successfully!', ADMIN . '/complete_listings.php');
            }
        }

        if ($_GET['action'] == 'complete') {
            $hide_listing = $db->query("UPDATE `listing` SET `completed` = '1' WHERE `id` = ?;", $listing_id);
            if ($hide_listing) {
                redirect('Listing Mark as Completed Successfully!', ADMIN . '/pending_listings.php');
            }
        }

        if ($_GET['action'] == 'notfeatured') {
            $notfeatured_listing = $db->query("UPDATE `listing` SET `featured` = '0' WHERE `id` = ?;", $listing_id);
            if ($notfeatured_listing) {
                redirect('Listing Mark as Featured Successfully!', ADMIN . '/complete_listings.php');
            }
        }

        if ($_GET['action'] == 'featured') {
            $featured_listing = $db->query("UPDATE `listing` SET `featured` = '1' WHERE `id` = ?;", $listing_id);
            if ($featured_listing) {
                redirect('Listing Mark as Featured Successfully!', ADMIN . '/complete_listings.php');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Completed Listings - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Fertige Einträge</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Listings</li>
                            <li class="breadcrumb-item active">Fertige</li>
                        </ol>
                    </div>

                    <div class="listing_actions">
                        <div id="listing_assign_overlay" class="overlay"></div>
                        <div id="current_page" data-url="complete_listings.php"></div>

                        <?php include HOME . '/admin/inc/listing_actions.php'; ?>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <?php include HOME . '/admin/inc/listing_filter.php'; ?>

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Auswählen</th>
                                            <th>Aktenzeichen</th>
                                            <th>Bundesland</th>
                                            <th>Portal</th>
                                            <th>Amtgericht</th>
                                            <th>Datum der Versteigerung</th>
                                            <th>Objektkategorie</th>
                                            <th>Bearbeiten</th>
                                            <th>Mitarbeiter</th>
                                            <th>Quelle</th>
                                            <th>PDF</th>
                                            <th>Befehle</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($listings as $item) {

                                            /* if (strpos($item['object_address'], ',') !== false) {
                                                $zip = explode(', ', $item['object_address']);
                                                $zip = explode(' ', $zip[1]);
                                                $city = getState($zip[0]);
                                            } else {
                                                $zip = $item['object_address'];
                                                $city = $zip;
                                            } */

                                            $city = getStatebyAddress($item['object_address']);

                                            /* if (strpos($item['foreclosure_date'], ',') !== false) {
                                                $date = explode(', ', $item['foreclosure_date']);
                                                $normal_date = fixDate($date[1]);
                                            } else {
                                                $normal_date = $item['foreclosure_date'];
                                            }

                                            $normal_date = str_replace(' ', '-', $normal_date); */
                                            $normal_date = $item['foreclosure_date'];

                                            $edit = getListingSteps($item['id']);


                                            $actions = '';
                                            $actions .= '<div class="listing_action">';

                                            $actions .= '<a href="' . LINK . '/listing/' . $item['listing_slug'] . '/" target="_blank" class="btn btn-primary" title="View Listing"><i class="fa fa-sign-out"></i></a>';

                                            if ($item['completed'] == 0) {
                                                $actions .= '<a href="' . ADMIN . '/complete_listings.php?listing_id=' . $item['id'] . '&action=complete" class="btn btn-success" title="Mark as Completed"><i class="fa fa-check"></i></a>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/complete_listings.php?listing_id=' . $item['id'] . '&action=notcomplete" class="btn btn-warning" title="Mark as not Completed"><i class="fa fa-times"></i></a>';
                                            }

                                            if ($item['featured'] == 0) {
                                                $actions .= '<a href="' . ADMIN . '/complete_listings.php?listing_id=' . $item['id'] . '&action=featured" class="btn btn-dark" title="Mark as Featured"><i class="fa fa-star"></i></a>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/complete_listings.php?listing_id=' . $item['id'] . '&action=notfeatured" class="btn btn-info" title="Mark as not Featured"><i class="fa fa-ban"></i></a>';
                                            }

                                            $actions .= '<a href="' . ADMIN . '/complete_listings.php?listing_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Listing" onclick="return confirm(\'Are you sure to delete this listing?\');"><i class="fa fa-trash-alt"></i></a>';

                                            //$report_link = '';
                                            $pdf = '';
                                            if (!empty($item['exposee_pdf'])) {
                                                //$report_link = $item['gutachten_pdf'];
                                                $pdf .= '<a href="' . $item['exposee_pdf'] . '" class="btn btn-dark" target="_blank"><i class="fa fa-file-alt"></i></a><div>(Exposé)</div>';
                                            } elseif (!empty($item['gutachten_pdf'])) {
                                                //$report_link = $item['exposee_pdf'];
                                                $pdf .= '<a href="' . $item['gutachten_pdf'] . '" class="btn btn-dark" target="_blank"><i class="fa fa-file-alt"></i></a><div>(Gutachten)</div>';
                                            } else {
                                                $pdf .= '<a href="#" class="btn btn-dark disabled" target="_blank"><i class="fa fa-file-alt"></i></a><div>(No Gutachten and No Exposé)</div>';
                                            }

                                            /* if (!empty($report_link)) {
                                                $actions .= '<a href="' . $report_link . '" class="btn btn-dark" target="_blank"><i class="fa fa-file-alt"></i></a>';
                                            } else {
                                                $actions .= '<a href="#" class="btn btn-dark disabled" target="_blank"><i class="fa fa-file-alt"></i></a>';
                                            } */

                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td class="text-center"><input type="checkbox" name="listing_checked[]" value="' . $item['id'] . '"></td>';
                                            echo '<td>' . $item['listing_label'] . '</td>';
                                            echo '<td>' . $city . '</td>';
                                            echo '<td>' . $item['platform'] . '</td>';
                                            echo '<td>' . $item['foreclosure_court'] . '</td>';
                                            echo '<td data-sort="' . strtotime($normal_date) . '">' . $item['foreclosure_date'] . '</td>';
                                            echo '<td>' . $item['object_cat'] . '</td>';
                                            echo '<td>' . $edit . '</td>';

                                            if ($item['admin'] == '0') {
                                                echo '<td>Not Assigned</td>';
                                            } else {
                                                echo '<td>' . get_data($item['admin'], 'user', 'admin') . '</td>';
                                            }

                                            echo '<td><div class="listing_action"><a href="' . LINK . '/preview.php?link=' . urlencode($item['source']) . '" class="btn btn-info" target="_blank"><i class="fa fa-eye"></i></a></div></td>';
                                            echo '<td class="text-center">' . $pdf . '</td>';
                                            echo '<td>' . $actions . '</td>';
                                            echo '</tr>';
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </main>

            <?php include HOME . '/admin/inc/footer.php'; ?>

        </div>

    </div>

    <?php include HOME . '/admin/inc/scripts.php'; ?>

</body>

</html>