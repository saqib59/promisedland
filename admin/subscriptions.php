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

$membership = $db->query('SELECT * FROM `membership`;')->fetchAll();

if (isset($_GET['membership_id']) && $_GET['membership_id'] !== '') {
    $membership_id = $_GET['membership_id'];

    if (isset($_GET['action']) && !empty($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_membership = $db->query("DELETE FROM `membership` WHERE `id` = ?;", $membership_id);
            if ($delete_membership) {
                redirect('Membership Deleted Successfully!', ADMIN . '/subscriptions.php');
            }
        }

        if ($_GET['action'] == 'cancel') {
            $cancel = $db->query("UPDATE `membership` SET `end_dt` = ?, `status` = 'cancel' WHERE `id` = ?;", $new_end_dt, $membership_id);
            if ($cancel) {
                // @@mail : cancel memberhsip email
                cancel_membership($membership_id);

                redirect('Membership Cancelled Successfully!', ADMIN . '/subscriptions.php');
            }
        }

        if ($_GET['action'] == 'approved') {
            $approve = $db->query("UPDATE `membership` SET `status` = 'approved' WHERE `id` = ?;", $membership_id);
            if ($approve) {
                redirect('Membership Approved Successfully!', ADMIN . '/subscriptions.php');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Manage Subscriptions - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Manage Subscriptions</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Users</li>
                            <li class="breadcrumb-item active">Subscriptions</li>
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
                                            <th style="min-width: 180px;">Date</th>
                                            <th>Plan</th>
                                            <th>Period</th>
                                            <th>Gateway</th>
                                            <th style="min-width: 180px;">End Date</th>
                                            <th>Status</th>
                                            <th style="min-width: 160px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($membership  as $item) {

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';

                                            if ($item['gateway'] == 'paypal') {
                                                $actions .= '<a href="' . ADMIN . '/paypal_logs.php?log_id=' . $item['transaction_id'] . '" class="btn btn-dark" title="View Payment Log"><i class="fa fa-sign-out"></i></a>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/stripe_logs.php?log_id=' . $item['transaction_id'] . '" class="btn btn-dark" title="View Payment Log"><i class="fa fa-sign-out"></i></a>';
                                            }


                                            if ($item['status'] == 'pending') {
                                                $actions .= '<a href="' . ADMIN . '/subscriptions.php?membership_id=' . $item['id'] . '&action=approved" class="btn btn-success" title="Approved Subscription"><i class="fa fa-check"></i></a>';
                                                $actions .= '<a href="' . ADMIN . '/subscriptions.php?membership_id=' . $item['id'] . '&action=cancel" class="btn btn-warning" title="Cancel Subscription"><i class="fa fa-times"></i></a>';
                                            } elseif ($item['status'] == 'approved') {
                                                $actions .= '<button class="btn btn-success" title="Approved Subscription" disabled><i class="fa fa-check"></i></button>';
                                                $actions .= '<a href="' . ADMIN . '/subscriptions.php?membership_id=' . $item['id'] . '&action=cancel" class="btn btn-warning" title="Cancel Subscription"><i class="fa fa-times"></i></a>';
                                            } elseif ($item['status'] == 'cancel') {
                                                $actions .= '<a href="' . ADMIN . '/subscriptions.php?membership_id=' . $item['id'] . '&action=approved" class="btn btn-success" title="Approved Subscription"><i class="fa fa-check"></i></a>';
                                                $actions .= '<button class="btn btn-warning" title="Cancel Subscription" disabled><i class="fa fa-times"></i></button>';
                                            }

                                            $actions .= '<a href="' . ADMIN . '/subscriptions.php?membership_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Question" onclick="return confirm(\'Are you sure to delete this question?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . get_data($item['user_id'], 'name', 'users') . '</td>';
                                            echo '<td>' . $item['start_dt'] . '</td>';

                                            if ($item['plan'] == 'plus') {
                                                echo '<td>Premium+</td>';
                                            } else {
                                                echo '<td>Premium</td>';
                                            }

                                            if ($item['period'] == '1') {
                                                echo '<td>1 Month</td>';
                                            } else {
                                                echo '<td>' . $item['period'] . ' Monate</td>';
                                            }

                                            echo '<td>' . ucfirst($item['gateway']) . '</td>';
                                            echo '<td>' . $item['end_dt'] . '</td>';

                                            if ($item['status'] == 'pending') {
                                                echo '<td><span class="text-primary">Pending Approval</span></td>';
                                            } elseif ($item['status'] == 'approved') {
                                                echo '<td><span class="text-success">Approved</span></td>';
                                            } elseif ($item['status'] == 'cancel') {
                                                echo '<td><span class="text-danger">Cancelled</span></td>';
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