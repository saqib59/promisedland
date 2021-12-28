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

$coupon = $db->query('SELECT * FROM `coupon`;')->fetchAll();

if (isset($_GET['coupon_id']) && $_GET['coupon_id'] !== '') {
    $coupon_id = $_GET['coupon_id'];

    if (isset($_GET['action']) && !empty($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_feedback = $db->query("DELETE FROM `coupon` WHERE `id` = ?;", $coupon_id);
            if ($delete_feedback) {
                redirect('Coupons Deleted Successfully!', ADMIN . '/manage_coupons.php');
            }
        }

        if ($_GET['action'] == 'disable') {
            $hide_listing = $db->query("UPDATE `coupon` SET `status` = '0' WHERE `id` = ?;", $coupon_id);
            if ($hide_listing) {
                redirect('Coupons Disabled Successfully!', ADMIN . '/manage_coupons.php');
            }
        }

        if ($_GET['action'] == 'enable') {
            $hide_listing = $db->query("UPDATE `coupon` SET `status` = '1' WHERE `id` = ?;", $coupon_id);
            if ($hide_listing) {
                redirect('Coupons Enabled Successfully!', ADMIN . '/manage_coupons.php');
            }
        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Manage Coupons - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Manage Coupons</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Coupons</li>
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
                                            <th>Code</th>
                                            <th>Discount</th>
                                            <th>Package</th>
                                            <th>Plan</th>
                                            <th>Usage Limit</th>
                                            <th style="min-width: 180px;">Created Date</th>
                                            <th style="min-width: 180px;">Last Update</th>
                                            <th style="min-width: 120px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($coupon  as $item) {

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            $actions .= '<a href="' . ADMIN . '/new_coupon.php?edit=' . $item['id'] . '" class="btn btn-dark" title="Edit Question"><i class="fa fa-edit"></i></a>';
                                            if ($item['status'] == 0) {
                                                $actions .= '<a href="' . ADMIN . '/manage_coupons.php?coupon_id=' . $item['id'] . '&action=enable" class="btn btn-success" title="Publish Question"><i class="fa fa-check"></i></a>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/manage_coupons.php?coupon_id=' . $item['id'] . '&action=disable" class="btn btn-warning" title="Draft Question"><i class="fa fa-times"></i></a>';
                                            }
                                            $actions .= '<a href="' . ADMIN . '/manage_coupons.php?coupon_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Question" onclick="return confirm(\'Are you sure to delete this question?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . $item['code'] . '</td>';
                                            echo '<td>' . $item['discount'] . '%</td>';

                                            if($item['package'] == 'premium') {
                                                echo '<td>Premium</td>';
                                            } elseif($item['package'] == 'plus') {
                                                echo '<td>Premium+</td>';
                                            } else {
                                                echo '<td>Any</td>';
                                            }

                                            if($item['plan'] == '0') {
                                                echo '<td>Any</td>';
                                            } elseif($item['plan'] == '1') {
                                                echo '<td>' . $item['plan'] . ' Month</td>';
                                            } else {
                                                echo '<td>' . $item['plan'] . ' Months</td>';
                                            }
                                            
                                            echo '<td>' . $item['user_limit'] . '</td>';
                                            echo '<td>' . $item['insert_at'] . '</td>';
                                            echo '<td>' . $item['updated_at'] . '</td>';
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