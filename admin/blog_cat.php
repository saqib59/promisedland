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

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $cat_id = $_GET['edit'];
}

$data = array(
    'cat_name' => '',
);

$blog_cats = $db->query('SELECT * FROM `blog_cat`;')->fetchAll();

$edit = 0;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit = 1;
    $data = $db->query('SELECT * FROM `blog_cat` WHERE `id` = ?', $cat_id)->fetchAll();
    if (!$data) removeEdit();
    $data = $data[0];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    if (!isset($p['cat_name']) || $p['cat_name'] == '') {
        $error[] = 'Please enter category name';
    }

    $update_cat = false;
    if (empty($error)) {
        if ($edit == 1) {
            $update_cat = $db->query('UPDATE `blog_cat` SET `cat_name` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `id` = ?', $p['cat_name'], $cat_id);
            $msg = 'Category Updated Successfully!';
        } else {
            $update_cat = $db->query('INSERT INTO `blog_cat`(`id`, `cat_name`) VALUES (NULL, ?)', $p['cat_name']);
            $msg = 'Category Added Successfully!';
        }
    }

    if ($update_cat) {
        redirect($msg, ADMIN . '/blog_cat.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Blog Categories - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Blog Categories</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Blog</li>
                            <li class="breadcrumb-item active">Categories</li>
                        </ol>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <?php if (isset($error) && !empty($error)) {
                                echo '<div class="alert alert-danger"><ul class="mb-0">';
                                foreach ($error as $e) {
                                    echo '<li>' . $e . '</li>';
                                }
                                echo '</ul></div>';
                            } ?>

                            <form action="<?= fullUrl() ?>" method="POST">

                                <div class="form-group">
                                    <label>Category Name</label>
                                    <input type="text" class="form-control" name="cat_name" value="<?= isset($p['cat_name']) ? $p['cat_name'] : $data['cat_name']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-plus-square"></i>
                                        <?php if ($edit == 1) { ?>
                                            <span>Update Category</span>
                                        <?php } else { ?>
                                            <span>Add Category</span>
                                        <?php } ?>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <?php if ($edit !== 1) { ?>
                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Equipment</th>
                                            <th>Created at</th>
                                            <th>Last Changed</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($blog_cats as $item) {
                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            $actions .= '<a href="' . ADMIN . '/blog_cat.php?edit=' . $item['id'] . '" class="btn btn-primary" title="Edit Equipments"><i class="fa fa-edit"></i></a>';
                                            $actions .= '</div>';
                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . $item['cat_name'] . '</td>';
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
                    <?php } ?>

                </div>
            </main>

            <?php include HOME . '/admin/inc/footer.php'; ?>

        </div>

    </div>

    <?php include HOME . '/admin/inc/scripts.php'; ?>

</body>

</html>