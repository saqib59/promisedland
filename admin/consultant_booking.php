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

$bookings = $db->query('SELECT * FROM `consulting_booking`;')->fetchAll();

if (isset($_GET['consultant_id']) && $_GET['consultant_id'] !== '') {
    $consultant_id = $_GET['consultant_id'];
}

if (isset($_GET['booking_id']) && $_GET['booking_id'] !== '') {
    $booking_id = $_GET['booking_id'];

    if (isset($_GET['action']) && !empty($_GET['action'])) {
        $process = $_GET['action'];
        $hide_listing = $db->query("UPDATE `consulting_booking` SET `status` = ? WHERE `id` = ?;", $process, $booking_id);
        if ($hide_listing) {
            redirect('Booking marked as ' . $process . ' Successfully!', ADMIN . '/consultant_booking.php?consultant_id=' . $consultant_id);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Manage Consulting Bookings - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Bookings: <?= get_data($consultant_id, 'title', 'consulting'); ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Consulting</li>
                            <li class="breadcrumb-item active">Bookings</li>
                        </ol>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Object ID</th>
                                            <th>Files</th>
                                            <th>Contact</th>
                                            <th>Time</th>
                                            <th>Booking Date</th>
                                            <th>Actions</th>
                                            <th>Attend</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($bookings  as $item) {

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            if ($item['status'] == 'pending') {
                                                $actions .= '<a href="' . ADMIN . '/consultant_booking.php?consultant_id=' . $consultant_id . '&booking_id=' . $item['id'] . '&action=approved" class="btn btn-success" title="Approve Booking"><i class="fa fa-check"></i></a>';
                                            }
                                            
                                            if ($item['status'] == 'attended' || $item['status'] == 'notattended') {
                                                $actions .= '<span>Approved</span>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/consultant_booking.php?consultant_id=' . $consultant_id . '&booking_id=' . $item['id'] . '&action=cancelled" class="btn btn-danger" title="Cancel Booking" onclick="return confirm(\'Are you sure to cancel this booking?\');"><i class="fa fa-times"></i></a>';
                                            }
                                            
                                            $actions .= '</div>';

                                            $attend = '';
                                            if ($item['status'] == 'approved') {
                                                $attend .= '<div class="listing_action">';
                                                $attend .= '<a href="' . ADMIN . '/consultant_booking.php?consultant_id=' . $consultant_id . '&booking_id=' . $item['id'] . '&action=attended" class="btn btn-dark" title="Attended to Consulting"><i class="fa fa-user-check"></i></a>';
                                                $attend .= '<a href="' . ADMIN . '/consultant_booking.php?consultant_id=' . $consultant_id . '&booking_id=' . $item['id'] . '&action=notattended" class="btn btn-info" title="Not Attended to Consulting"><i class="fa fa-exclamation-circle"></i></a>';
                                                $attend .= '</div>';
                                            } elseif ($item['status'] == 'attended') {
                                                $attend .= '<span>Attended</span>';
                                            } elseif ($item['status'] == 'notattended') {
                                                $attend .= '<span>Not Attended</span>';
                                            }

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . get_data($item['user_id'], 'name', 'users') . '</td>';
                                            echo '<td>' . $item['object_id'] . '</td>';
                                            echo '<td><a href="' . LINK . $item['files'] . '" class="badge badge-primary" target="_blank">View File</a></td>';
                                            echo '<td>' . $item['contact'] . '</td>';
                                            echo '<td>' . ucfirst($item['time']) . '</td>';
                                            echo '<td>' . $item['insert_at'] . '</td>';
                                            echo '<td>' . $actions . '</td>';
                                            echo '<td>' . $attend . '</td>';
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