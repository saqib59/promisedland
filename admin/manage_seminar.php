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

if ( !role('admin') ) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

$seminar = $db->query('SELECT * FROM `seminar`;')->fetchAll();

if (isset($_GET['seminar_id']) && $_GET['seminar_id'] !== '') {
    $seminar_id = $_GET['seminar_id'];
    if (isset($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_listing = $db->query("DELETE FROM `seminar` WHERE `id` = ?;", $seminar_id);
            if ($delete_listing) {
                redirect('Seminar Deleted Successfully!', ADMIN . '/manage_seminar.php');
            }
        }

        if ($_GET['action'] == 'draft') {
            $hide_listing = $db->query("UPDATE `seminar` SET `status` = '0' WHERE `id` = ?;", $seminar_id);
            if ($hide_listing) {
                redirect('Seminar Drafted Successfully!', ADMIN . '/manage_seminar.php');
            }
        }

        if ($_GET['action'] == 'publish') {
            $hide_listing = $db->query("UPDATE `seminar` SET `status` = '1' WHERE `id` = ?;", $seminar_id);
            if ($hide_listing) {
                redirect('Seminar Published Successfully!', ADMIN . '/manage_seminar.php');
            }
        }
        
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Manage Seminars - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Manage Seminar</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Seminar</li>
                            <li class="breadcrumb-item active">Manage</li>
                        </ol>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Speaker</th>
                                            <th>Date</th>
                                            <th>Method</th>
                                            <th>View</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($seminar as $item) {

                                            $view = '';
                                            $view .= '<div class="listing_action">';
                                            $view .= '<a href="' . ADMIN . '/seminar_booking.php?seminar_id=' . $item['id'] . '" class="btn btn-dark" title="View Bookings"><i class="fa fa-address-book"></i></a>';
                                            $view .= '<a href="' . ADMIN . '/seminar_feedbacks.php?seminar_id=' . $item['id'] . '" class="btn btn-info" title="View Feedbacks"><i class="fa fa-comment-alt"></i></a>';
                                            $view .= '</div>';

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            if ($item['status'] == 0) {
                                                $actions .= '<a href="' . ADMIN . '/manage_seminar.php?seminar_id=' . $item['id'] . '&action=publish" class="btn btn-success" title="Publish Seminar"><i class="fa fa-check"></i></a>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/manage_seminar.php?seminar_id=' . $item['id'] . '&action=draft" class="btn btn-warning" title="Draft Seminar"><i class="fa fa-times"></i></a>';
                                            }

                                            $actions .= '<a href="' . ADMIN . '/new_seminar.php?edit=' . $item['id'] . '" class="btn btn-primary" title="Edit Seminar"><i class="fa fa-edit"></i></a>';
                                            $actions .= '<a href="' . ADMIN . '/manage_seminar.php?seminar_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Seminar" onclick="return confirm(\'Are you sure to delete this seminar?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . $item['title'] . '</td>';
                                            echo '<td>' . $item['speaker'] . '</td>';
                                            echo '<td>' . $item['event_date'] . '</td>';
                                            echo '<td>' . ucfirst($item['method']) . '</td>';
                                            echo '<td>' . $view . '</td>';
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