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

if ($_SESSION['role'] == 'tutor') {
    $author_id = get_col_data($_SESSION['admin'], 'tutor', 'id', 'course_author');
    $courses = $db->query('SELECT * FROM `course` WHERE `author` = ?;', $author_id)->fetchAll();
} else {
    $courses = $db->query('SELECT * FROM `course`;')->fetchAll();
}


if (isset($_GET['course_id']) && $_GET['course_id'] !== '') {
    $course_id = $_GET['course_id'];
    if (isset($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_listing = $db->query("DELETE FROM `course` WHERE `id` = ?;", $course_id);
            if ($delete_listing) {
                redirect('Course Deleted Successfully!', ADMIN . '/manage_courses.php');
            }
        }

        if ($_GET['action'] == 'draft') {
            $hide_listing = $db->query("UPDATE `course` SET `status` = '0' WHERE `id` = ?;", $course_id);
            if ($hide_listing) {
                redirect('Course Drafted Successfully!', ADMIN . '/manage_courses.php');
            }
        }

        if ($_GET['action'] == 'publish') {
            $hide_listing = $db->query("UPDATE `course` SET `status` = '1' WHERE `id` = ?;", $course_id);
            if ($hide_listing) {
                redirect('Course Published Successfully!', ADMIN . '/manage_courses.php');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Manage Courses - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Manage Courses</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Course</li>
                            <li class="breadcrumb-item active">Manage Courses</li>
                        </ol>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Price</th>
                                            <th>Author</th>
                                            <th>Edit</th>
                                            <th>Users</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($courses as $item) {


                                            $subs = '';
                                            $subs .= '<div class="listing_action">';
                                            $subs .= '<a href="' . ADMIN . '/course_subscribers.php?course_id=' . $item['id'] . '" class="btn btn-dark" title="View Subscribers"><i class="fa fa-users"></i></a>';
                                            $subs .= '</div>';

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            $actions .= '<a href="' . LINK . '/courses/' . $item['slug'] . '/" target="_blank" class="btn btn-primary" title="View Course"><i class="fa fa-sign-out"></i></a>';

                                            if ($item['status'] == 0) {
                                                $actions .= '<a href="' . ADMIN . '/manage_courses.php?course_id=' . $item['id'] . '&action=publish" class="btn btn-success" title="Publish Course"><i class="fa fa-check"></i></a>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/manage_courses.php?course_id=' . $item['id'] . '&action=draft" class="btn btn-warning" title="Draft Course"><i class="fa fa-times"></i></a>';
                                            }

                                            //$actions .= '<a href="' . ADMIN . '/new_course.php?edit=' . $item['id'] . '" class="btn btn-dark" title="Edit Course"><i class="fa fa-edit"></i></a>';
                                            $actions .= '<a href="' . ADMIN . '/manage_courses.php?course_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Course" onclick="return confirm(\'Are you sure to delete this post?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            $edit = '';
                                            $edit .= '<div class="actions">';

                                            $edit .= '<a class="btn btn-outline-secondary ';
                                            if (check_row($item['id'], 'id', 'course')) $edit .= 'active';
                                            $edit .= '" href="' . ADMIN . '/new_course.php?course_id=' . $item['id'] . '&edit=1">Information</a>';

                                            $edit .= '<a class="btn btn-outline-secondary ';
                                            if (check_row($item['id'], 'course_id', 'course_learn')) $edit .= 'active';
                                            $edit .= '" href="' . ADMIN . '/course_learn.php?course_id=' . $item['id'] . '&edit=1">Learn</a>';

                                            $edit .= '<a class="btn btn-outline-secondary ';
                                            if (check_row($item['id'], 'course_id', 'course_video')) $edit .= 'active';
                                            $edit .= '" href="' . ADMIN . '/course_videos.php?course_id=' . $item['id'] . '&edit=1">Videos</a>';

                                            $edit .= '<a class="btn btn-outline-secondary ';
                                            if (check_row($item['id'], 'course_id', 'course_faq')) $edit .= 'active';
                                            $edit .= '" href="' . ADMIN . '/course_faq.php?course_id=' . $item['id'] . '&edit=1">F.A.Q</a>';

                                            $edit .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td><img width="120" src="' . LINK . $item['image'] . '"></td>';
                                            echo '<td>' . $item['title'] . '</td>';
                                            echo '<td>' . $item['price'] . '</td>';
                                            echo '<td>' . get_data($item['author'], 'name', 'course_author') . '</td>';
                                            echo '<td>' . $edit . '</td>';
                                            echo '<td>' . $subs . '</td>';
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