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

if (!role('admin') && !role('tutor')) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

if (isset($_GET['course_id']) && $_GET['course_id'] !== '') {
    $course_id = $_GET['course_id'];
}

$subs = $db->query('SELECT * FROM `course_subscribe` WHERE `course_id` = ?;', $course_id)->fetchAll();

if (isset($_GET['sub_id']) && $_GET['sub_id'] !== '') {
    $sub_id = $_GET['sub_id'];

    if (isset($_GET['action']) && !empty($_GET['action'])) {
        $process = $_GET['action'];
        $hide_listing = $db->query("UPDATE `course_subscribe` SET `status` = ? WHERE `id` = ?;", $process, $sub_id);
        if ($hide_listing) {
            redirect('Subscription marked as ' . ucfirst($process) . ' Successfully!', ADMIN . '/course_subscribers.php?course_id=' . $course_id);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Course Subscribers - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Subscribers: <?= get_data($course_id, 'title', 'course'); ?> (<?= get_data(get_data($course_id, 'author', 'course'), 'name', 'course_author'); ?>)</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Course</li>
                            <li class="breadcrumb-item active">Subscribers</li>
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
                                            <th>Payment Gateway</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Transaction</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($subs  as $item) {

                                            $trans = '';
                                            $trans .= '<div class="listing_action">';
                                            if ($item['gateway'] == 'paypal') {
                                                $trans .= '<a href="' . ADMIN . '/paypal_logs_onetime.php?log_id=' . $item['txn_id'] . '" class="btn btn-info" title="View Transaction"><i class="fa fa-money-check-edit"></i></a>';
                                            } elseif ($item['gateway'] == 'paypal') {
                                                $trans .= '<a href="' . ADMIN . '/stripe_logs_onetime.php?log_id=' . $item['txn_id'] . '" class="btn btn-info" title="View Transaction"><i class="fa fa-money-check-edit"></i></a>';
                                            } else {
                                                $trans .= '<button class="btn btn-info" title="View Transaction" disabled><i class="fa fa-money-check-edit"></i></button>';
                                            }
                                            $trans .= '</div>';

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            if ($item['status'] == 'pending') {
                                                $actions .= '<a href="' . ADMIN . '/course_subscribers.php?course_id=' . $course_id . '&sub_id=' . $item['id'] . '&action=approved" class="btn btn-success" title="Approve Subscription"><i class="fa fa-check"></i></a>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/course_subscribers.php?course_id=' . $course_id . '&sub_id=' . $item['id'] . '&action=pending" class="btn btn-warning" title="Pending Subscription" onclick="return confirm(\'Are you sure to mark as pending this subscription?\');"><i class="fa fa-times"></i></a>';
                                            }
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . get_data($item['user_id'], 'name', 'users') . '</td>';
                                            echo '<td>' . ucfirst($item['gateway']) . '</td>';
                                            echo '<td>' . $item['insert_at'] . '</td>';
                                            echo '<td>' . ucfirst($item['status']) . '</td>';
                                            echo '<td>' . $trans . '</td>';
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