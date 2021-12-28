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

if ( !role('admin') && !role('tutor') ) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

if (isset($_GET['course_id']) && !empty($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
} else {
    redirect('Course ID is Missing!', ADMIN . '/manage_courses.php');
}

$course = $db->query('SELECT * FROM `course` WHERE `id` = ?;', $course_id)->fetchArray();

// get faq list
$faq = $db->query('SELECT * FROM `course_faq` WHERE `course_id` = ?;', $course_id)->fetchAll();

if (isset($_GET['faq_id']) && $_GET['faq_id'] !== '') {
    $faq_id = $_GET['faq_id'];
    if (isset($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_question = $db->query("DELETE FROM `course_faq` WHERE `id` = ?;", $faq_id);
            if ($delete_question) {
                redirect('Question Deleted Successfully!', ADMIN . '/course_faq.php?course_id=' . $course_id);
            }
        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>F.A.Q - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">F.A.Q: <?= $course['title'] ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Course</li>
                            <li class="breadcrumb-item active">F.A.Q</li>
                        </ol>
                    </div>

                    <?php include HOME . '/admin/inc/course_steps.php'; ?>

                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Question</th>
                                            <th>Asked by</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($faq as $item) {

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            $actions .= '<a href="' . LINK . '/views/video.php?slug=' . get_data($item['course_id'], 'slug', 'course') . '" target="_blank" class="btn btn-primary" title="View Answers"><i class="fa fa-sign-out"></i></a>';
                                            $actions .= '<a href="' . ADMIN . '/faq_comments.php?faq_id=' . $item['id'] . '" class="btn btn-dark" title="Manage Answers"><i class="fa fa-comments"></i></a>';
                                            $actions .= '<a href="' . ADMIN . '/course_faq.php?course_id=' . $course_id . '&faq_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Question" onclick="return confirm(\'Are you sure to delete this question?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';


                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . $item['title'] . '</td>';
                                            echo '<td>' . $item['question'] . '</td>';
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