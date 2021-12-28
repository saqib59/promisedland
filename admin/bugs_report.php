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

if (!role('admin') && !role('writer')) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

$feedbacks = $db->query('SELECT * FROM `bug_submit`;')->fetchAll();

if (isset($_GET['feedback_id']) && $_GET['feedback_id'] !== '') {
    $feedback_id = $_GET['feedback_id'];

    if (isset($_GET['action']) && !empty($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_feedback = $db->query("DELETE FROM `bug_submit` WHERE `id` = ?;", $feedback_id);
            if ($delete_feedback) {
                redirect('Report Deleted Successfully!', ADMIN . '/bugs_report.php');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Listing Bugs - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Listing Bugs</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Listings</li>
                            <li class="breadcrumb-item active">Bug Reports</li>
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
                                            <th style="min-width: 200px;">Listing ID</th>
                                            <th>Feedback</th>
                                            <th style="min-width: 180px;">Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($feedbacks  as $item) {

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            $actions .= '<a href="' . ADMIN . '/feedbacks.php?bugs_report=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Report" onclick="return confirm(\'Are you sure to delete this report?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            if ($item['user'] == '0') {
                                                echo '<td>Guest</td>';
                                            } else {
                                                echo '<td>' . get_data($item['user'], 'name', 'users') . '</td>';
                                            }
                                            echo '<td>' . get_data($item['listing_id'], 'listing_label', 'listing') . '</td>';
                                            echo '<td>' . $item['info'] . '</td>';
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