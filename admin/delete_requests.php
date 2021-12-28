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

$requests = $db->query('SELECT * FROM `user_delete`;')->fetchAll();

if (isset($_GET['request_id']) && $_GET['request_id'] !== '') {
    $request_id = $_GET['request_id'];

    if (isset($_GET['action']) && !empty($_GET['action'])) {

        if ($_GET['action'] == 'request') {
            // get user id
            $request_user_id = get_data($request_id, 'user', 'user_delete');

            // delete from user table
            $user_delete_status = $db->query("DELETE FROM `users` WHERE `id` = ?;", $request_user_id);

            if ($user_delete_status) {
                // delete from other tables
                $db->query("DELETE FROM `bug_submit` WHERE `user` = ?;", $request_user_id);
                $db->query("DELETE FROM `course_faq` WHERE `user` = ?;", $request_user_id);
                $db->query("DELETE FROM `course_faq_comments` WHERE `user` = ?;", $request_user_id);
                $db->query("DELETE FROM `course_faq_likes` WHERE `user` = ?;", $request_user_id);
                $db->query("DELETE FROM `request` WHERE `user` = ?;", $request_user_id);
                $db->query("DELETE FROM `search_order` WHERE `user` = ?;", $request_user_id);
                $db->query("DELETE FROM `user_alerts` WHERE `user` = ?;", $request_user_id);


                $db->query("DELETE FROM `answers` WHERE `user_id` = ?;", $request_user_id);
                $db->query("DELETE FROM `consulting_booking` WHERE `user_id` = ?;", $request_user_id);
                $db->query("DELETE FROM `consulting_feedback` WHERE `user_id` = ?;", $request_user_id);
                $db->query("DELETE FROM `course_subscribe` WHERE `user_id` = ?;", $request_user_id);
                $db->query("DELETE FROM `favorite` WHERE `user_id` = ?;", $request_user_id);
                $db->query("DELETE FROM `feedback` WHERE `user_id` = ?;", $request_user_id);
                $db->query("DELETE FROM `membership` WHERE `user_id` = ?;", $request_user_id);
                $db->query("DELETE FROM `paypal_logs` WHERE `user_id` = ?;", $request_user_id);
                $db->query("DELETE FROM `paypal_logs_onetime` WHERE `user_id` = ?;", $request_user_id);
                $db->query("DELETE FROM `seminar_booking` WHERE `user_id` = ?;", $request_user_id);
                $db->query("DELETE FROM `seminar_feedback` WHERE `user_id` = ?;", $request_user_id);
                $db->query("DELETE FROM `stripe_logs` WHERE `user_id` = ?;", $request_user_id);
                $db->query("DELETE FROM `stripe_logs_onetime` WHERE `user_id` = ?;", $request_user_id);
                $db->query("DELETE FROM `user_details` WHERE `user_id` = ?;", $request_user_id);

                updateDatabyId('1', 'status', $request_id, 'user_delete');
                redirect('User Deletion Has Completed Successfully!', ADMIN . '/delete_requests.php');
            }
        }

        if ($_GET['action'] == 'delete') {
            $delete_feedback = $db->query("DELETE FROM `user_delete` WHERE `id` = ?;", $request_id);
            if ($delete_feedback) {
                redirect('Delete Request Deleted Successfully!', ADMIN . '/delete_requests.php');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Manage Delete Requests - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Manage Delete Requests</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Users</li>
                            <li class="breadcrumb-item active">Delete Requests</li>
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
                                            <th style="min-width: 200px;">Status</th>
                                            <th style="min-width: 180px;">Requested At</th>
                                            <th style="min-width: 180px;">Action At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($requests  as $item) {

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            $actions .= '<a href="' . ADMIN . '/delete_requests.php?request_id=' . $item['id'] . '&action=request" class="btn btn-danger" title="Delete USer" onclick="return confirm(\'Are you sure to delete this user?\');"><i class="fa fa-user-slash"></i></a>';
                                            $actions .= '<a href="' . ADMIN . '/delete_requests.php?request_id=' . $item['id'] . '&action=delete" class="btn btn-warning" title="Delete Feedback" onclick="return confirm(\'Are you sure to delete this request?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            $user_name = get_data($item['user'], 'name', 'users');
                                            if (!empty($user_name)) {
                                                echo '<td>' . $user_name . '</td>';
                                            } else {
                                                echo '<td>User ID #' . $item['user'] . '</td>';
                                            }

                                            if ($item['status'] == 0) {
                                                echo '<td>Pending Deletion</td>';
                                            } else {
                                                echo '<td>Deletion Completed</td>';
                                            }

                                            echo '<td>' . $item['insert_at'] . '</td>';
                                            if ($item['insert_at'] == $item['updated_at']) {
                                                echo '<td>N/A</td>';
                                            } else {
                                                echo '<td>' . $item['updated_at'] . '</td>';
                                            }

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