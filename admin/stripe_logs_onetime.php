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

if (!role('admin')) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

$logs = $db->query('SELECT * FROM `stripe_logs_onetime`;')->fetchAll();

if (isset($_GET['log_id']) && !empty($_GET['log_id'])) {
    $log_id = $_GET['log_id'];

    $logs = $db->query('SELECT * FROM `stripe_logs_onetime` WHERE id = ?;', $log_id)->fetchAll();

    if (isset($_GET['action']) && !empty($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_membership = $db->query("DELETE FROM `stripe_logs_onetime` WHERE `id` = ?;", $log_id);
            if ($delete_membership) {
                redirect('Log Deleted Successfully!', ADMIN . '/stripe_logs_onetime.php');
            }
        }

        if ($_GET['action'] == 'pending') {
            $pending_log = $db->query("UPDATE `stripe_logs_onetime` SET `status` = 'pending' WHERE `id` = ?;", $log_id);
            if ($pending_log) {
                redirect('Log mark as Pending Successfully!', ADMIN . '/stripe_logs_onetime.php?log_id=' . $log_id);
            }
        }

        if ($_GET['action'] == 'active') {
            $complete_log = $db->query("UPDATE `stripe_logs_onetime` SET `status` = 'succeeded' WHERE `id` = ?;", $log_id);
            if ($complete_log) {
                redirect('Log mark as Complete Successfully!', ADMIN . '/stripe_logs_onetime.php?log_id=' . $log_id);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Manage Stripe Logs - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">

                        <?php if (isset($log_id)) { ?>
                            <h1 class="mt-4">Stripe Log: <?= get_data($logs[0]['course_id'], 'title', 'course'); ?> (<?= get_data(get_data($logs[0]['course_id'], 'author', 'course'), 'name', 'course_author'); ?>)</h1>
                        <?php } else { ?>
                            <h1 class="mt-4">Manage Stripe Logs</h1>
                        <?php } ?>

                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Logs</li>
                            <li class="breadcrumb-item active">Stripe</li>
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
                                            <th>Course</th>
                                            <th>Transaction ID</th>
                                            <th>Status</th>
                                            <th style="min-width: 180px;">Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($logs  as $item) {

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            if ($item['status'] == 'succeeded') {
                                                $actions .= '<a href="' . ADMIN . '/stripe_logs_onetime.php?log_id=' . $item['id'] . '&action=pending" class="btn btn-warning" title="Pending Log"><i class="fa fa-times"></i></a>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/stripe_logs_onetime.php?log_id=' . $item['id'] . '&action=active" class="btn btn-success" title="Complete Log"><i class="fa fa-check"></i></a>';
                                            }
                                            $actions .= '<a href="' . ADMIN . '/stripe_logs_onetime.php?log_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Question" onclick="return confirm(\'Are you sure to delete this question?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . get_data($item['user_id'], 'name', 'users') . '</td>';
                                            echo '<td>' . get_data($item['course_id'], 'title', 'course') . '</td>';
                                            echo '<td>' . $item['transaction_id'] . '</td>';

                                            if ($item['status'] == 'succeeded') {
                                                echo '<td><span class="text-success">Complete</span></td>';
                                            } else {
                                                echo '<td><span class="text-primary">Pending</span></td>';
                                            }

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