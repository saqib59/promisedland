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

if ( !role('admin') && !role('writer') ) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

if ($_SESSION['role'] == 'writer') {
    $blog = $db->query('SELECT * FROM `blog` WHERE `author` = ?;', $_SESSION['admin'])->fetchAll();
} else {
    $blog = $db->query('SELECT * FROM `blog`;')->fetchAll();
}



if (isset($_GET['post_id']) && $_GET['post_id'] !== '') {
    $post_id = $_GET['post_id'];
    if (isset($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_listing = $db->query("DELETE FROM `blog` WHERE `id` = ?;", $post_id);
            if ($delete_listing) {
                redirect('Post Deleted Successfully!', ADMIN . '/manage_blog.php');
            }
        }

        if ($_GET['action'] == 'draft') {
            $hide_listing = $db->query("UPDATE `blog` SET `status` = '0' WHERE `id` = ?;", $post_id);
            if ($hide_listing) {
                redirect('Post Drafted Successfully!', ADMIN . '/manage_blog.php');
            }
        }

        if ($_GET['action'] == 'publish') {
            $hide_listing = $db->query("UPDATE `blog` SET `status` = '1' WHERE `id` = ?;", $post_id);
            if ($hide_listing) {
                redirect('Post Published Successfully!', ADMIN . '/manage_blog.php');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Manage Blog - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Manage Blog</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Blog</li>
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
                                            <th>Category</th>
                                            <th>Author</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($blog as $item) {

                                            if (!empty($item['gallery'])) {
                                                $gallery = json_decode($item['gallery'], true);
                                                $gallery = $gallery[0];
                                            } else {
                                                $gallery = '';
                                            }


                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            $actions .= '<a href="' . LINK . '/article/' . $item['slug'] . '/" target="_blank" class="btn btn-primary" title="View Post"><i class="fa fa-sign-out"></i></a>';

                                            if ($item['status'] == 0) {
                                                $actions .= '<a href="' . ADMIN . '/manage_blog.php?post_id=' . $item['id'] . '&action=publish" class="btn btn-success" title="Publish Post"><i class="fa fa-check"></i></a>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/manage_blog.php?post_id=' . $item['id'] . '&action=draft" class="btn btn-warning" title="Draft Post"><i class="fa fa-times"></i></a>';
                                            }

                                            $actions .= '<a href="' . ADMIN . '/new_post.php?edit=' . $item['id'] . '" class="btn btn-dark" title="Edit Post"><i class="fa fa-edit"></i></a>';
                                            $actions .= '<a href="' . ADMIN . '/manage_blog.php?post_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Post" onclick="return confirm(\'Are you sure to delete this post?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td><img width="150" src="' . LINK . $gallery . '"></td>';
                                            echo '<td>' . $item['title'] . '</td>';
                                            echo '<td>' . get_data($item['category'], 'cat_name', 'blog_cat') . '</td>';
                                            echo '<td>' . get_data($item['author'], 'user', 'admin') . '</td>';
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