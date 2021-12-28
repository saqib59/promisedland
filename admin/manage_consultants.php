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

$consultings = $db->query('SELECT * FROM `consulting`;')->fetchAll();

if (isset($_GET['consultant_id']) && $_GET['consultant_id'] !== '') {
    $consultant_id = $_GET['consultant_id'];
    if (isset($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_listing = $db->query("DELETE FROM `consulting` WHERE `id` = ?;", $consultant_id);
            if ($delete_listing) {
                redirect('Consultant Deleted Successfully!', ADMIN . '/manage_consultants.php');
            }
        }

        if ($_GET['action'] == 'draft') {
            $hide_listing = $db->query("UPDATE `consulting` SET `status` = '0' WHERE `id` = ?;", $consultant_id);
            if ($hide_listing) {
                redirect('Consultant Drafted Successfully!', ADMIN . '/manage_consultants.php');
            }
        }

        if ($_GET['action'] == 'publish') {
            $hide_listing = $db->query("UPDATE `consulting` SET `status` = '1' WHERE `id` = ?;", $consultant_id);
            if ($hide_listing) {
                redirect('Consultant Published Successfully!', ADMIN . '/manage_consultants.php');
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
                        <h1 class="mt-4">Manage Consultant</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Consulting</li>
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
                                            <th>Price</th>
                                            <th>Time</th>
                                            <th>View</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($consultings as $item) {

                                            $view = '';
                                            $view .= '<div class="listing_action">';
                                            $view .= '<a href="' . ADMIN . '/consultant_booking.php?consultant_id=' . $item['id'] . '" class="btn btn-dark" title="View Bookings"><i class="fa fa-address-book"></i></a>';
                                            $view .= '<a href="' . ADMIN . '/consultant_feedbacks.php?consultant_id=' . $item['id'] . '" class="btn btn-info" title="View Feedbacks"><i class="fa fa-comment-alt"></i></a>';
                                            $view .= '</div>';

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            if ($item['status'] == 0) {
                                                $actions .= '<a href="' . ADMIN . '/manage_consultants.php?consultant_id=' . $item['id'] . '&action=publish" class="btn btn-success" title="Publish Consultant"><i class="fa fa-check"></i></a>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/manage_consultants.php?consultant_id=' . $item['id'] . '&action=draft" class="btn btn-warning" title="Draft Consultant"><i class="fa fa-times"></i></a>';
                                            }

                                            $actions .= '<a href="' . ADMIN . '/new_consultant.php?edit=' . $item['id'] . '" class="btn btn-primary" title="Edit Consultant"><i class="fa fa-edit"></i></a>';
                                            $actions .= '<a href="' . ADMIN . '/manage_consultants.php?consultant_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Consultant" onclick="return confirm(\'Are you sure to delete this consultant?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . $item['title'] . '</td>';
                                            echo '<td>' . $item['price'] . '</td>';
                                            echo '<td>' . $item['time'] . '</td>';
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