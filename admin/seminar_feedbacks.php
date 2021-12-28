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

$feedbacks = $db->query('SELECT * FROM `seminar_feedback`;')->fetchAll();

if (isset($_GET['seminar_id']) && $_GET['seminar_id'] !== '') {
    $seminar_id = $_GET['seminar_id'];
}

if (isset($_GET['feedback_id']) && $_GET['feedback_id'] !== '') {
    $feedback_id = $_GET['feedback_id'];

    if (isset($_GET['action']) && !empty($_GET['action'])) {
        if ($_GET['action'] == 'delete') {
            $delete_listing = $db->query("DELETE FROM `seminar_feedback` WHERE `id` = ?;", $feedback_id);
            if ($delete_listing) {
                redirect('Feedback Deleted Successfully!', ADMIN . '/seminar_feedbacks.php?seminar_id=' . $seminar_id);
            }
        }

        if ($_GET['action'] == 'pending') {
            $hide_listing = $db->query("UPDATE `seminar_feedback` SET `status` = '0' WHERE `id` = ?;", $feedback_id);
            if ($hide_listing) {
                redirect('Feedback Pending Successfully!', ADMIN . '/seminar_feedbacks.php?seminar_id=' . $seminar_id);
            }
        }

        if ($_GET['action'] == 'approve') {
            $hide_listing = $db->query("UPDATE `seminar_feedback` SET `status` = '1' WHERE `id` = ?;", $feedback_id);
            if ($hide_listing) {
                redirect('Feedback Approved Successfully!', ADMIN . '/seminar_feedbacks.php?seminar_id=' . $seminar_id);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Manage Seminar Feedbacks - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Feedbacks: <?= get_data($seminar_id, 'title', 'seminar'); ?> (<?= get_data($seminar_id, 'event_date', 'seminar'); ?>)</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Seminar</li>
                            <li class="breadcrumb-item active">Feedbacks</li>
                        </ol>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th style="min-width: 200px;">User</th>
                                            <th style="min-width: 120px;">Rating</th>
                                            <th>Feedback</th>
                                            <th style="min-width: 180px;">Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($feedbacks  as $item) {

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            $actions .= '<a href="' . ADMIN . '/seminar_feedbacks.php?seminar_id=' . $seminar_id . '&feedback_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Feedback" onclick="return confirm(\'Are you sure to delete this feedback?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . get_data($item['user_id'], 'name', 'users') . '</td>';

                                            echo '<td><div class="star_ratings">';
                                            for ($i = 0; $i < $item["rating"]; $i++) {
                                                echo '<i class="fa fa-star gold"></i>';
                                            }
                                            for ($i = 5; $i > $item["rating"]; $i--) {
                                                echo '<i class="fa fa-star"></i>';
                                            }
                                            echo '</div></td>';

                                            echo '<td>' . $item['feedback'] . '</td>';
                                            echo '<td>' . $item['insert_at'] . '</td>';
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