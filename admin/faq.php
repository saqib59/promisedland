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

$faq = $db->query('SELECT * FROM `faq`;')->fetchAll();

if (isset($_GET['faq_id']) && $_GET['faq_id'] !== '') {
    $faq_id = $_GET['faq_id'];

    if (isset($_GET['action']) && !empty($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_feedback = $db->query("DELETE FROM `faq` WHERE `id` = ?;", $faq_id);
            if ($delete_feedback) {
                redirect('F.A.Q Deleted Successfully!', ADMIN . '/faq.php');
            }
        }

        if ($_GET['action'] == 'draft') {
            $hide_listing = $db->query("UPDATE `faq` SET `status` = '0' WHERE `id` = ?;", $faq_id);
            if ($hide_listing) {
                redirect('F.A.Q Drafted Successfully!', ADMIN . '/faq.php');
            }
        }

        if ($_GET['action'] == 'publish') {
            $hide_listing = $db->query("UPDATE `faq` SET `status` = '1' WHERE `id` = ?;", $faq_id);
            if ($hide_listing) {
                redirect('F.A.Q Published Successfully!', ADMIN . '/faq.php');
            }
        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Manage F.A.Q - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Manage F.A.Q</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Site</li>
                            <li class="breadcrumb-item active">F.A.Q</li>
                        </ol>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="<?= ADMIN ?>/new_faq.php" class="btn btn-primary"><i class="fa fa-plus-square"></i> Create New F.A.Q</a>
                        </div>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Question</th>
                                            <th>Answer</th>
                                            <th style="min-width: 180px;">Created Date</th>
                                            <th style="min-width: 180px;">Last Update</th>
                                            <th style="min-width: 120px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($faq  as $item) {

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            $actions .= '<a href="' . ADMIN . '/new_faq.php?edit=' . $item['id'] . '" class="btn btn-dark" title="Edit Question"><i class="fa fa-edit"></i></a>';
                                            if ($item['status'] == 0) {
                                                $actions .= '<a href="' . ADMIN . '/faq.php?faq_id=' . $item['id'] . '&action=publish" class="btn btn-success" title="Publish Question"><i class="fa fa-check"></i></a>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/faq.php?faq_id=' . $item['id'] . '&action=draft" class="btn btn-warning" title="Draft Question"><i class="fa fa-times"></i></a>';
                                            }
                                            $actions .= '<a href="' . ADMIN . '/faq.php?faq_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Question" onclick="return confirm(\'Are you sure to delete this question?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . $item['question'] . '</td>';
                                            echo '<td>' . strip_tags($item['answer']) . '</td>';
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