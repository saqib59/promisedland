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

if (!role('admin')) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $admin_id = $_GET['edit'];
}

$data = array(
    'user' => '',
    'email' => '',
    'pwd' => '',
    'role' => '',
);

$admins = $db->query('SELECT * FROM `admin`;')->fetchAll();

$edit = 0;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit = 1;
    $data = $db->query('SELECT * FROM `admin` WHERE `id` = ?', $admin_id)->fetchAll();
    if (!$data) removeEdit();
    $data = $data[0];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    foreach ($_POST as $post) {
        if (empty($post) || $post == '') {
            $error[] = 'Please fill all the fields';
            break;
        }
    }

    $update_admins = false;
    if (empty($error)) {
        if ($edit == 1) {
            if (empty($p['pwd'])) {
                $update_admins = $db->query('UPDATE `admin` SET `user` = ?, `email` = ?, `role` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `id` = ?', $p['user'], $p['email'], $p['role'], $admin_id);
            } else {
                $update_admins = $db->query('UPDATE `admin` SET `user` = ?, `email` = ?, `pwd` = ?, `role` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `id` = ?', $p['user'], $p['email'], md5($p['pwd']), $p['role'], $admin_id);
            }

            $msg = 'Admin Updated Successfully!';
        } else {
            $update_admins = $db->query('INSERT INTO `admin`(`id`, `user`, `email`, `pwd`, `role`) VALUES (NULL, ?, ?, ?, ?)', $p['user'], $p['email'], md5($p['pwd']), $p['role']);
            $msg = 'Admin Created Successfully!';
        }
    }

    if ($update_admins) {
        redirect($msg, ADMIN . '/admins.php');
    }
}

if (isset($_GET['admin_id']) && $_GET['admin_id'] !== '') {
    $admin_id = $_GET['admin_id'];

    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'delete') {
            $delete_admin = $db->query("DELETE FROM `admin` WHERE `id` = ?;", $admin_id);
            if ($delete_admin) {
                redirect('Admin Deleted Successfully!', ADMIN . '/admins.php');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Manage Admins - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Manage Admin</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Site</li>
                            <li class="breadcrumb-item active">Admin</li>
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

                                <div class="form-group row">
                                    <div class="col-md-3 col-12">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="user" placeholder="Username" value="<?= isset($p['user']) ? $p['user'] : $data['user']; ?>" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" placeholder="Email" value="<?= isset($p['email']) ? $p['email'] : $data['email']; ?>" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="pwd" placeholder="<?= $edit == 1 ? 'Leave this empty if don\'t want to change the password' : ''; ?>" value="<?= isset($p['pwd']) ? $p['pwd'] : ''; ?>" required>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <label>Role</label>
                                        <select name="role" class="form-select" data-select="<?= isset($p['role']) ? $p['role'] : $data['role']; ?>" required>
                                            <option value="">Select Role</option>
                                            <option value="admin">Admin</option>
                                            <option value="manager">Manager</option>
                                            <option value="writer">Writer</option>
                                            <option value="tutor">Tutor</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-plus-square"></i>
                                        <?php if ($edit == 1) { ?>
                                            <span>Update Admin</span>
                                        <?php } else { ?>
                                            <span>Create Admin</span>
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
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            foreach ($admins as $item) {
                                                $actions = '';
                                                $actions .= '<div class="listing_action">';
                                                $actions .= '<a href="' . ADMIN . '/admins.php?edit=' . $item['id'] . '" class="btn btn-primary" title="Edit Equipments"><i class="fa fa-edit"></i></a>';
                                                $actions .= '<a href="' . ADMIN . '/admins.php?admin_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Admin" onclick="return confirm(\'Are you sure to delete this Admin?\');"><i class="fa fa-trash-alt"></i></a>';
                                                $actions .= '</div>';

                                                echo '<tr>';
                                                echo '<td>' . $item['id'] . '</td>';
                                                echo '<td>' . $item['user'] . '</td>';
                                                echo '<td>' . $item['email'] . '</td>';
                                                echo '<td>' . $item['role'] . '</td>';
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