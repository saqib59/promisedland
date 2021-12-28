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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Dashboard - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Index</li>
                        </ol>
                    </div>

                    <div class="admin_info">
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <a href="<?= ADMIN ?>/complete_listings.php" class="card bg-success text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="card-left">
                                                <h2 class="mb-0"><?= getRowCount('listing', 'completed', '1') ?></h2>
                                                <p class="mb-0 small">Fertige Einträge</p>
                                            </div>
                                            <div class="card-right">
                                                <i class="fas fa-comment-check fa-4x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <a href="<?= ADMIN ?>/pending_listings.php" class="card bg-warning text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="card-left">
                                                <h2 class="mb-0"><?= getRowCount('listing', 'completed', '0') ?></h2>
                                                <p class="mb-0 small">Ausstehende Einträge</p>
                                            </div>
                                            <div class="card-right">
                                                <i class="fas fa-comment-slash fa-4x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <a href="<?= ADMIN ?>/archived_listings.php" class="card bg-primary text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="card-left">
                                                <h2 class="mb-0"><?= getRowCount('listing', 'completed', '2') ?></h2>
                                                <p class="mb-0 small">Archivierte Einträge</p>
                                            </div>
                                            <div class="card-right">
                                                <i class="fas fa-comment-dots fa-4x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <a href="<?= ADMIN ?>/report_requests.php" class="card bg-danger text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="card-left">
                                                <h2 class="mb-0"><?= getRowCount('request', 'status', '0') ?></h2>
                                                <p class="mb-0 small">Report Requests</p>
                                            </div>
                                            <div class="card-right">
                                                <i class="fas fa-flag fa-4x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
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