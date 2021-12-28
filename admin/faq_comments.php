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

if (isset($_GET['faq_id']) && !empty($_GET['faq_id'])) {
    $faq_id = $_GET['faq_id'];
} else {
    redirect('FAQ ID is Missing!', ADMIN . '/manage_courses.php');
}

$faq = $db->query('SELECT * FROM `course_faq` WHERE id = ?;', $faq_id)->fetchArray();

// get faq list
$comments = $db->query('SELECT * FROM `course_faq_comments` WHERE `faq_id` = ?;', $faq_id)->fetchAll();

if (isset($_GET['comment_id']) && $_GET['comment_id'] !== '') {
    $comment_id = $_GET['comment_id'];
    if (isset($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_question = $db->query("DELETE FROM `course_faq_comments` WHERE `id` = ?;", $comment_id);
            if ($delete_question) {
                redirect('Answer Deleted Successfully!', ADMIN . '/faq_comments.php?faq_id=' . $faq_id);
            }
        }

        if ($_GET['action'] == 'draft') {
            $hide_question = $db->query("UPDATE `course_faq_comments` SET `answer` = '0' WHERE `id` = ?;", $comment_id);
            if ($hide_question) {
                redirect('Comment unmarked as the Answer Successfully!', ADMIN . '/faq_comments.php?faq_id=' . $faq_id);
            }
        }

        if ($_GET['action'] == 'publish') {
            $update_comments = $db->query("UPDATE `course_faq_comments` SET `answer` = '0' WHERE `faq_id` = ?;", $faq_id);
            $show_question = $db->query("UPDATE `course_faq_comments` SET `answer` = '1' WHERE `id` = ?;", $comment_id);
            if ($update_comments && $show_question) {
                redirect('Comment marked as the Answer Successfully!', ADMIN . '/faq_comments.php?faq_id=' . $faq_id);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Comments - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Comments: <?= $faq['title'] ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Course</li>
                            <li class="breadcrumb-item">F.A.Q</li>
                            <li class="breadcrumb-item active">Comments</li>
                        </ol>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Comments</th>
                                            <th>Commented by</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($comments as $item) {

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            if ($item['answer'] == '0') {
                                                $actions .= '<a href="' . ADMIN . '/faq_comments.php?faq_id=' . $faq_id . '&comment_id=' . $item['id'] . '&action=publish" class="btn btn-success" title="Mark as Answered"><i class="fa fa-check"></i></a>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/faq_comments.php?faq_id=' . $faq_id . '&comment_id=' . $item['id'] . '&action=draft" class="btn btn-warning" title="Mark as not-Answered"><i class="fa fa-times"></i></a>';
                                            }
                                            $actions .= '<a href="' . ADMIN . '/faq_comments.php?faq_id=' . $faq_id . '&comment_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Answer" onclick="return confirm(\'Are you sure to delete this answer?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            if ($item['answer'] == '1') {
                                                echo '<tr class="bg-success text-white">';
                                            } else {
                                                echo '<tr>';
                                            }

                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . $item['reply'] . '</td>';
                                            echo '<td>' . get_data($item['user'], 'name', 'users') . '</td>';
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