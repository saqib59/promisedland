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

$request = $db->query('SELECT * FROM `contact`;')->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Contact Inquiries - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Contact Inquiries</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Site</li>
                            <li class="breadcrumb-item active">Inquiries</li>
                        </ol>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Reason</th>
                                            <th>Message</th>
                                            <th style="min-width: 180px">Date/Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        if ($request) {
                                            foreach ($request as $item) {
                                                echo '<tr>';
                                                echo '<td>' . $item['id'] . '</td>';
                                                echo '<td>' . $item['name'] . '</td>';
                                                echo '<td>' . $item['email'] . '</td>';
                                                echo '<td>' . $item['reason'] . '</td>';
                                                echo '<td>' . $item['msg'] . '</td>';
                                                echo '<td>' . $item['insert_at'] . '</td>';
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