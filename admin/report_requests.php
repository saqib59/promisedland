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

if ( !role('admin') && !role('manager') ) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

$request = $db->query('SELECT * FROM `request`;')->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Report Requests - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Report Requests</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Listings</li>
                            <li class="breadcrumb-item active">Requests</li>
                        </ol>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Listing</th>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Requested at</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        if ($request) {
                                            foreach ($request as $item) {
                                                /* if ($item['status'] == '0') {
                                                    $status = 'Not Sent';
                                                } else {
                                                    $status = 'Sent';
                                                }
                                                if ($item['insert_at'] == $item['update_at']) {
                                                    if ($item['status'] == '0') {
                                                        $update_at = 'Not Sent';
                                                    }
                                                } else {
                                                    $update_at = $item['update_at'];
                                                } */
                                                echo '<tr>';
                                                echo '<td>' . $item['id'] . '</td>';
                                                echo '<td>' . get_data($item['listing_id'], 'listing_label', 'listing') . '</td>';
                                                echo '<td>' . get_data($item['user'], 'name', 'users') . '</td>';
                                                echo '<td>' . get_data($item['user'], 'email', 'users') . '</td>';
                                                //echo '<td>' . $status . '</td>';
                                                echo '<td>' . $item['insert_at'] . '</td>';
                                                //echo '<td>' . $update_at . '</td>';
                                                echo '</tr>';
                                            }
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