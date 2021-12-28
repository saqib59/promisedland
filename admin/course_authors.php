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

$authors = $db->query('SELECT * FROM `course_author`;')->fetchAll();

if (isset($_GET['author_id']) && $_GET['author_id'] !== '') {
    $author_id = $_GET['author_id'];
    if (isset($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_listing = $db->query("DELETE FROM `course_author` WHERE `id` = ?;", $author_id);
            if ($delete_listing) {
                redirect('Author Deleted Successfully!', ADMIN . '/course_authors.php');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Course Authors - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Course Authors</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Course</li>
                            <li class="breadcrumb-item active">Course Authors</li>
                        </ol>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="<?= ADMIN ?>/new_author.php" class="btn btn-primary"><i class="fa fa-plus-square"></i> Create New Author</a>
                        </div>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>About</th>
                                            <th>Tutor</th>
                                            <th>Updated at</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($authors as $item) {

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            $actions .= '<a href="' . ADMIN . '/new_author.php?edit=' . $item['id'] . '" class="btn btn-primary" title="Edit Author"><i class="fa fa-edit"></i></a>';
                                            $actions .= '<a href="' . ADMIN . '/course_authors.php?author_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Author" onclick="return confirm(\'Are you sure to delete this author?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td><img width="100" src="' . LINK . $item['image'] . '"></td>';
                                            echo '<td>' . $item['name'] . '</td>';
                                            echo '<td>' . substr($item['content'], 0, 60) . '... </td>';
                                            echo '<td>' . get_data($item['tutor'], 'user', 'admin') . '</td>';
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